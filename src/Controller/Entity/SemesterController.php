<?php

namespace App\Controller\Entity;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;
use App\Entity\Administration\Semester;
use App\Form\NewSemesterType;

/**
 * @Route("/semester", name="entity_semester_")
 */
class SemesterController extends BaseController
{
    /**
     * @Route("/new", name="new")
     */
    public function new()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMINISTRATIVE');

        $semester = new Semester();

        $form = $this->createForm(NewSemesterType::class, $semester);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($semester);
            $em->flush();
        }

        $this->redirectToRoute('administration_homepage');
    }
}
