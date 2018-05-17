<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;
use App\Entity\Administration\Course;
use App\Entity\Administration\Semester;
use App\Form\EditCourseType;
use App\Form\EditSemesterType;

/**
 * @Route("/administration", name="administration_")
 */
class AdministrationController extends BaseController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $doctrine = $this->getDoctrine();

        $editableCourses = $doctrine
            ->getRepository(Course::class)
            ->findEditable();

        $semesters = $doctrine
            ->getRepository(Semester::class)
            ->findAfter(new \DateTime('-1 year'), 'endDate');

        // Course form
        $defaultCourse = (new Course())
            ->setImplementationDate(\DateTime::createFromFormat(
                'Y-m-d',
                sprintf('%d-09-01', ((int) date('Y')) + (date('d-m') >= '09-01' ? 1 : 0))
            ));

        $newCourse = $this->createForm(EditCourseType::class, $defaultCourse);

        // Semester form
        $nextSemesterBounds = $this->getNextSemesterBounds();
        $defaultSemester = (new Semester())
            ->setStartDate($nextSemesterBounds['start'])
            ->setEndDate($nextSemesterBounds['end']);

        $newSemester = $this->createForm(EditSemesterType::class, $defaultSemester);

        return $this->createHtmlResponse('administration/index.html.twig', [
            'editable_courses' => $editableCourses,
            'semesters' => $semesters,
            'new_course' => $newCourse->createView(),
            'new_semester' => $newSemester->createView(),
        ]);
    }

    private function getNextSemesterBounds()
    {
        $currentPeriod = $this->getDoctrine()
            ->getRepository(Semester::class)
            ->findCurrentPeriod();

        $start = $currentPeriod->getEnd()->modify('+1 day');
        $end = $currentPeriod->getStart()
            ->modify('-1 day')
            ->modify('+1 year');

        return [
            'start' => $start,
            'end' => $end,
        ];
    }

    /**
     * @Route("/semester/{id}", name="semester")
     */
    public function semester(Semester $semester)
    {

    }
}
