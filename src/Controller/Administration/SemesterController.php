<?php

namespace App\Controller\Administration;

use App\Entity\Administration\Semester;
use App\Form\Administration\SemesterType;
use App\Repository\Administration\SemesterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/administration/semester")
 */
class SemesterController extends Controller
{
    /**
     * @Route("/new", name="administration_semester_new", methods="GET|POST")
     */
    public function new(Request $request, SemesterRepository $semesterRepository): Response
    {
        $nextPeriod = $semesterRepository->findNextPeriod();
        $semester = (new Semester())
            ->setPeriod($nextPeriod);

        $form = $this->createForm(SemesterType::class, $semester, [ 'creation' => true ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($semester);
            $em->flush();

            return $this->redirectToRoute('administration_semester_show', ['id' => $semester->getId()]);
        }

        return $this->render('administration/semester/new.html.twig', [
            'semester' => $semester,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="administration_semester_show", methods="GET")
     */
    public function show(Semester $semester): Response
    {
        return $this->render('administration/semester/show.html.twig', ['semester' => $semester]);
    }

    /**
     * @Route("/{id}/edit", name="administration_semester_edit", methods="GET|POST")
     */
    public function edit(Request $request, Semester $semester): Response
    {
        if (!$semester->isEditable()) {
            // TODO Add notification
            return $this->redirectToRoute('administration_semester_show', ['id' => $semester->getId()]);
        }

        $form = $this->createForm(SemesterType::class, $semester);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('administration_semester_edit', ['id' => $semester->getId()]);
        }

        return $this->render('administration/semester/edit.html.twig', [
            'semester' => $semester,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="administration_semester_delete", methods="DELETE")
     */
    public function delete(Request $request, Semester $semester): Response
    {
        if (!$semester->isDeletable()) {
            // TODO Add notification
            return $this->redirectToRoute('administration_semester_edit', ['id' => $semester->getId()]);
        }

        if ($this->isCsrfTokenValid('delete'.$semester->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($semester);
            $em->flush();
        }

        return $this->redirectToRoute('administration_index');
    }
}
