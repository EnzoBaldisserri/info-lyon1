<?php

namespace App\Controller\Entity;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Administration\Course;
use App\Form\NewCourseType;

/**
 * @Route("/course", name="entity_course_")
 */
class CourseController extends BaseController
{
    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMINISTRATIVE');

        $course = new Course();

        $form = $this->createForm(NewCourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();
        }

        $this->redirectToRoute('administration_homepage');
    }
}
