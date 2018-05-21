<?php

namespace App\Controller\Administration;

use App\Entity\Administration\Course;
use App\Form\Administration\CourseType;
use App\Repository\Administration\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/administration/course")
 */
class CourseController extends Controller
{
    /**
     * @Route("/new", name="administration_course_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $course = (new Course())
            ->setImplementationDate(\DateTime::createFromFormat(
                'Y-m-d',
                sprintf('%d-09-01', ((int) date('Y')) + (date('m-d') >= '09-01' ? 1 : 0))
            ));

        $form = $this->createForm(CourseType::class, $course, [ 'creation' => true ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();

            return $this->redirectToRoute('administration_index');
        }

        return $this->render('administration/course/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="administration_course_edit", methods="GET|POST")
     */
    public function edit(Request $request, Course $course): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('administration_course_edit', ['id' => $course->getId()]);
        }

        return $this->render('administration/course/edit.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="administration_course_delete", methods="DELETE")
     */
    public function delete(Request $request, Course $course): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($course);
            $em->flush();
        }

        return $this->redirectToRoute('administration_index');
    }
}
