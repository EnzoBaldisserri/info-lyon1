<?php

namespace App\Controller\Administration;

use App\Controller\BaseController;
use App\Entity\Administration\Course;
use App\Entity\Administration\Group;
use App\Entity\Administration\Semester;
use App\Entity\Period;
use App\Entity\User\Student;
use App\Entity\User\User;
use App\Exception\InvalidEntityException;
use App\Form\Administration\SemesterType;
use App\Helper\FileHelper;
use App\Repository\Administration\SemesterRepository;
use App\Repository\User\StudentRepository;
use App\Repository\User\UserRepository;
use App\Service\NotificationBuilder;
use App\Service\SpreadsheetService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use PhpOffice\PhpSpreadsheet\Exception as SpreadsheetException;

/**
 * @Route("/administration/semester")
 */
class SemesterController extends BaseController
{
    /**
     * @Route("/new", name="administration_semester_new", methods="GET|POST")
     */
    public function new(Request $request, SemesterRepository $semesterRepository): Response
    {
        $nextPeriod = $semesterRepository->findNextPeriod();
        $semester = (new Semester())
            ->setPeriod($nextPeriod);

        $form = $this->createForm(SemesterType::class, $semester);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($semester);
            $em->flush();

            return $this->redirectToRoute('administration_semester_edit', ['id' => $semester->getId()]);
        }

        return $this->createHtmlResponse('administration/semester/new.html.twig', [
            'semester' => $semester,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new/file", name="administration_semester_new_file", methods="GET|POST")
     */
    public function newWithFile(Request $request, SpreadsheetService $spreadsheetService): Response
    {
        $form = $this->createFormBuilder()
            ->add('attachment', FileType::class , ['label' => false])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $file = $form->get('attachment')->getData();

                /** @var Semester $semester */
                $semester = $spreadsheetService->readEntity(Semester::class, $file);
                $this->complete($semester);

                $em = $this->getDoctrine()->getManager();
                $em->persist($semester);
                $em->flush();

                $this->createNotification()
                    ->setContent('semester.form.edit.success')
                    ->setType(NotificationBuilder::SUCCESS)
                    ->save();

                return $this->redirectToRoute('administration_semester_edit', [
                    'id' => $semester->getId(),
                ]);
            } catch (SpreadsheetException $exception) {
                $this->createNotification()
                    ->setContent('error.semester.file.invalid')
                    ->setType(NotificationBuilder::ERROR)
                    ->save();
            } catch (InvalidEntityException $exception) {
                $this->createNotification()
                    ->setContent($exception->getMessage())
                    ->setType(NotificationBuilder::ERROR)
                    ->save();
            }
        }

        return $this->createHtmlResponse('administration/semester/file_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="administration_semester_show", methods="GET")
     */
    public function show(Semester $semester): Response
    {
        if ($semester->isEditable()) {
            $this->createNotification()
                ->setContent('error.semester.editable')
                ->setType(NotificationBuilder::WARNING)
                ->save();

            return $this->redirectToRoute('administration_semester_edit', ['id' => $semester->getId()]);
        }

        return $this->createHtmlResponse('administration/semester/show.html.twig', ['semester' => $semester]);
    }

    /**
     * @Route("/{id}/edit", name="administration_semester_edit", methods="GET|POST")
     */
    public function edit(Request $request, Semester $semester, StudentRepository $studentRepository): Response
    {
        if (!$semester->isEditable()) {
            $this->createNotification()
                ->setContent('error.semester.not_editable')
                ->setType(NotificationBuilder::WARNING)
                ->save();

            return $this->redirectToRoute('administration_semester_show', ['id' => $semester->getId()]);
        }

        // Store former students
        $formerGroupsStudents = array_reduce(
            $semester->getGroups()->toArray(),
            function ($groups, $group) {
                $groups[$group->getId()] = $group->getStudents()->toArray();
                return $groups;
            },
            array()
        );

        $form = $this->createForm(SemesterType::class, $semester);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO Verify semester's course didn't change

            // Move students
            foreach ($form->get('groups') as $groupForm) {
                /** @var Group $group */
                $group = $groupForm->getData();

                $formerStudents = $formerGroupsStudents[$group->getId()] ?? [];

                $currentStudentsIds = array_map(
                    function($studentForm) { return $studentForm->get('id')->getData(); },
                    iterator_to_array($groupForm->get('students'))
                );
                $currentStudents = $studentRepository->findBy(['id' => $currentStudentsIds]);

                // Add new students
                foreach ($currentStudents as $student) {
                    if (!in_array($student, $formerStudents, true)) {
                        $group->addStudent($student);
                    }
                }

                // Remove students that aren't in the group anymore
                foreach ($formerStudents as $student) {
                    if (!in_array($student, $currentStudents, true)) {
                        $student->removeClass($group);
                    }
                }

                // Fix problem with form, trying to create students from null values
                foreach ($group->getStudents() as $student) {
                    if ($student->getId() === null) {
                        $group->removeStudent($student);
                    }
                }
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('administration_semester_edit', ['id' => $semester->getId()]);
        }

        $students = $studentRepository
            ->findAvailableForSemester($semester);

        return $this->createHtmlResponse('administration/semester/edit.html.twig', [
            'semester' => $semester,
            'students' => $students,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit/file", name="administration_semester_edit_file", methods="GET|POST")
     */
    public function editWithFile(
        Request $request,
        Semester $semester,
        SpreadsheetService $spreadsheetService
    ): Response
    {
        if (!$semester->isEditable()) {
            $this->createNotification()
                ->setContent('error.semester.not_editable')
                ->setType(NotificationBuilder::WARNING)
                ->save();

            return $this->redirectToRoute('administration_semester_show', [
                'id' => $semester->getId(),
            ]);
        }

        $form = $this->createFormBuilder()
            ->add('attachment', FileType::class , ['label' => false])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $file = $form->get('attachment')->getData();

                /** @var Semester $semester */
                $semester = $spreadsheetService->readEntity($semester, $file);
                $this->complete($semester);

                $this->getDoctrine()->getManager()->flush();

                $this->createNotification()
                    ->setContent('semester.form.edit.success')
                    ->setType(NotificationBuilder::SUCCESS)
                    ->save();

                return $this->redirectToRoute('administration_semester_edit', [
                    'id' => $semester->getId(),
                ]);
            } catch (SpreadsheetException $exception) {
                $this->createNotification()
                    ->setContent('error.semester.file.invalid')
                    ->setType(NotificationBuilder::ERROR)
                    ->save();
            } catch (InvalidEntityException $exception) {
                $this->createNotification()
                    ->setContent($exception->getMessage(), $exception->getArguments())
                    ->setType(NotificationBuilder::ERROR)
                    ->save();
            }
        }

        return $this->createHtmlResponse('administration/semester/file_edit.html.twig', [
            'semester' => $semester,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/sample/file", name="administration_semester_generate_sample", methods="GET")
     *
     * @throws SpreadsheetException
     */
    public function generateSampleFile(SpreadsheetService $spreadsheetService, FileHelper $fileHelper): Response
    {
        $filepath = $fileHelper->getFolder('spreadsheet', true) . 'sample.xlsx';
        $file = new \SplFileInfo($filepath);

        if (!$file->isFile()) {
            $sample = $this->createSemesterSample();
            $spreadsheetService->writeEntity($sample, $file);
        }

        return $this->file($file);
    }

    /**
     * @Route(
     *     "/{id}/file",
     *     name="administration_semester_generate_file",
     *     methods="GET",
     *     requirements={"id"="\d+"}
     * )
     *
     * @throws SpreadsheetException
     */
    public function generateFile(
        Semester $semester,
        SpreadsheetService $spreadsheetService,
        FileHelper $fileHelper
    ): Response
    {
        $filepath = $fileHelper->getFolder('tmp', true) . $this->generateUniqueFileName() . '.xlsx';
        $file = new \SplFileInfo($filepath);

        $spreadsheetService->writeEntity($semester, $file);

        return $this->file($filepath);
    }

    /**
     * @Route("/{id}", name="administration_semester_delete", methods="DELETE")
     */
    public function delete(Request $request, Semester $semester): Response
    {
        if (!$semester->isDeletable()) {
            $this->createNotification()
                ->setContent('error.semester.not_deletable')
                ->setType(NotificationBuilder::WARNING)
                ->save();

            return $this->redirectToRoute(
                'administration_semester_' . ($semester->isEditable() ? 'edit' : 'show'),
                ['id' => $semester->getId()]
            );
        }

        if ($this->isCsrfTokenValid('delete'.$semester->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($semester);
            $em->flush();
        }

        return $this->redirectToRoute('administration_index');
    }

    /**
     * Make a complete entity from the semester
     *
     * @param Semester $semester
     *
     * @throws InvalidEntityException
     */
    private function complete(Semester &$semester)
    {
        $doctrine = $this->getDoctrine();

        // Check for course existence
        $course = $semester->getCourse();

        /** @var Course $optCourse */
        $optCourse = $doctrine
            ->getRepository(Course::class)
            ->findOneBySemesterAndYear(
                $course->getSemester(),
                (int) $course->getImplementationDate()->format('Y')
            )
        ;

        if (!$optCourse) {
            throw new InvalidEntityException('error.course.nonexistent', [
                '%name%' => $course->getName(),
                '%implementationYear%' => $course->getImplementationDate()->format('Y'),
            ]);
        }

        $semester->setCourse($optCourse);

        // Check for students existence
        $groups = $semester->getGroups();

        /** @var UserRepository $userRepository */
        $userRepository = $doctrine->getRepository(User::class);

        foreach ($groups as $group) {
            $students = $group->getStudents();

            foreach ($students as $key => $student) {
                $username = $student->getUsername();

                /** @var Student $optStudent */
                $optStudent = $userRepository->findByUsername($username);

                if (!$optStudent) {
                    throw new InvalidEntityException('error.student.nonexistent', [
                        '%username%' => $username,
                    ]);
                }

                $students->set($key, $optStudent);
            }
        }
    }

    private function createSemesterSample(): Semester
    {
        $doctrine = $this->getDoctrine();

        /** @var Period $nextPeriod */
        $nextPeriod = $doctrine
            ->getRepository(Semester::class)
            ->findNextPeriod();

        /** @var Course $lastBeginningCourse */
        $lastBeginningCourse = $doctrine
            ->getRepository(Course::class)
            ->findOneBy(
                [ 'semester' => 1 ],
                [ 'id' => 'DESC']
            )
        ;

        /** @var Student $sampleStudent1 */
        $sampleStudent1 = (new Student())
            ->setUsername('p0000001')
            ->setFirstname('John')
            ->setSurname('Doe');

        /** @var Student $sampleStudent2 */
        $sampleStudent2 = (new Student())
            ->setUsername('p0000012')
            ->setFirstname('Richard')
            ->setSurname('Roe');

        $sampleGroup1 = (new Group())
            ->setId('#id')
            ->setNumber(1)
            ->addStudent($sampleStudent1)
            ->addStudent($sampleStudent2)
        ;

        /** @var Student $sampleStudent3 */
        $sampleStudent3 = (new Student)
            ->setUsername('p0000008')
            ->setFirstname('Jane')
            ->setSurname('Doe');

        $sampleGroup2 = (new Group())
            ->setId('#id')
            ->setNumber(2)
            ->addStudent($sampleStudent3)
        ;

        return (new Semester())
            ->setId('#id')
            ->setPeriod($nextPeriod)
            ->setCourse($lastBeginningCourse)
            ->addGroup($sampleGroup1)
            ->addGroup($sampleGroup2)
        ;
    }

    private function generateUniqueFileName(): string
    {
        // md5() reduces the similarity of the file names generated by uniqid()
        return md5(uniqid());
    }
}
