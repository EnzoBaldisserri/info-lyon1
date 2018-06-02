<?php

namespace App\Controller\Administration;

use App\Controller\BaseController;
use App\Entity\Administration\Semester;
use App\Form\Administration\SemesterType;
use App\Repository\Administration\SemesterRepository;
use App\Repository\User\StudentRepository;
use App\Service\NotificationBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/{id}", name="administration_semester_show", methods="GET")
     */
    public function show(Semester $semester): Response
    {
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
     * @Route("/{id}", name="administration_semester_delete", methods="DELETE")
     */
    public function delete(Request $request, Semester $semester): Response
    {
        if (!$semester->isDeletable()) {
            $this->createNotification()
                ->setContent('error.semester.not_deletable')
                ->setType(NotificationBuilder::WARNING)
                ->save();

            if ($semester->isEditable()) {
                return $this->redirectToRoute('administration_semester_edit', ['id' => $semester->getId()]);
            } else {
                return $this->redirectToRoute('administration_semester_show', ['id' => $semester->getId()]);
            }
        }

        if ($this->isCsrfTokenValid('delete'.$semester->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($semester);
            $em->flush();
        }

        return $this->redirectToRoute('administration_index');
    }
}
