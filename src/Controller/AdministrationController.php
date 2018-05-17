<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;
use App\Entity\Administration\Course;
use App\Entity\Administration\Semester;
use App\Repository\Administration\CourseRepository;
use App\Repository\Administration\SemesterRepository;
use App\Form\Administration\CourseType;
use App\Form\Administration\SemesterType;

/**
 * @Route("/administration", name="administration_")
 */
class AdministrationController extends BaseController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CourseRepository $courseRepository, SemesterRepository $semesterRepository)
    {
        $editableCourses = $courseRepository
            ->findEditable();

        $semesters = $semesterRepository
            ->findAfter(new \DateTime('-1 year'), 'endDate');

        // Course form
        $defaultCourse = (new Course())
            ->setImplementationDate(\DateTime::createFromFormat(
                'Y-m-d',
                sprintf('%d-09-01', ((int) date('Y')) + (date('m-d') >= '09-01' ? 1 : 0))
            ));

        $newCourse = $this->createForm(CourseType::class, $defaultCourse);

        // Semester form
        $nextPeriod = $semesterRepository->findNextPeriod();
        $defaultSemester = (new Semester())
            ->setPeriod($nextPeriod);

        $newSemester = $this->createForm(SemesterType::class, $defaultSemester);

        return $this->createHtmlResponse('administration/index.html.twig', [
            'editable_courses' => $editableCourses,
            'semesters' => $semesters,
            'new_course' => $newCourse->createView(),
            'new_semester' => $newSemester->createView(),
        ]);
    }
}
