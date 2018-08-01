<?php

namespace App\Controller\Administration;

use App\Controller\BaseController;
use App\Entity\Administration\Course;
use App\Form\Administration\CourseType;
use App\Service\NotificationBuilder;
use Carbon\Translator;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/administration/course")
 */
class CourseController extends BaseController
{
    /**
     * @Route("/new", name="administration_course_new", methods="GET|POST")
     */
    public function new(Request $request, TranslatorInterface $translator): Response
    {
        $course = (new Course())
            ->setImplementationDate(\DateTime::createFromFormat(
                'Y-m-d',
                sprintf('%d-09-01', ((int) date('Y')) + (date('m-d') >= '09-01' ? 1 : 0))
            ));

        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $optCourse = $this->getDoctrine()
                ->getRepository(Course::class)
                ->findOneByTypeAndYear(
                    $course->getType(),
                    (int) $course->getImplementationDate()->format('Y')
                )
            ;

            if ($optCourse !== null) {
                $form->addError(new FormError(
                    $translator->trans('error.course.exists', [
                       '%name%' => $course->getName(),
                       '%implementationYear%' => $course->getImplementationDate()->format('Y'),
                    ])
                ));
            }

            if ($form->isValid()) {
                foreach ($course->getTeachingUnits() as $teachingUnit) {
                    $teachingUnit->addCourse($course);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($course);
                $em->flush();

                return $this->redirectToRoute('administration_index');
            }
        }

        return $this->createHtmlResponse('administration/course/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="administration_course_edit", methods="GET|POST")
     */
    public function edit(Request $request, Course $course): Response
    {
        if (!$course->isEditable()) {
            $this->createNotification()
                ->setContent('error.course.not_editable')
                ->setType(NotificationBuilder::ERROR)
                ->save();

            return $this->redirectToRoute('administration_index');
        }

        $formerTeachingUnits = $course->getTeachingUnits()->toArray();

        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentTeachingUnits = $course->getTeachingUnits();

            // Add new teaching units
            foreach ($currentTeachingUnits as $teachingUnit) {
                if (!in_array($teachingUnit, $formerTeachingUnits, true)) {
                    $teachingUnit->addCourse($course);
                }
            }

            // Remove former teaching units
            foreach ($formerTeachingUnits as $teachingUnit) {
                if (!$currentTeachingUnits->contains($teachingUnit)) {
                    $teachingUnit->removeCourse($course);
                }
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('administration_course_edit', ['id' => $course->getId()]);
        }

        return $this->createHtmlResponse('administration/course/edit.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="administration_course_delete", methods="DELETE")
     */
    public function delete(Request $request, Course $course): Response
    {
        if (!$course->isDeletable()) {
            $this->createNotification()
                ->setContent('error.course.not_deletable')
                ->setType(NotificationBuilder::ERROR)
                ->save();

            if ($course->isEditable()) {
                return $this->redirectToRoute('administration_course_edit', [
                    'id' => $course->getId(),
                ]);
            } else {
                return $this->redirectToRoute('administration_index');
            }
        }

        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($course);
            $em->flush();
        }

        return $this->redirectToRoute('administration_index');
    }
}
