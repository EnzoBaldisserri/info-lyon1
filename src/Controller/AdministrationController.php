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
        $courses = $courseRepository
            ->findEditable();

        $semesters = $semesterRepository
            ->findAfter(new \DateTime('-1 year'), 'endDate');

        return $this->createHtmlResponse('administration/index.html.twig', [
            'courses' => $courses,
            'semesters' => $semesters,
        ]);
    }
}
