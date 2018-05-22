<?php

namespace App\Controller\Administration;

use App\Entity\Administration\Semester;
use App\Form\Administration\SemesterType;
use App\Repository\Administration\SemesterRepository;
use App\Repository\User\StudentRepository;
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

        $form = $this->createForm(SemesterType::class, $semester);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($semester);
            $em->flush();

            return $this->redirectToRoute('administration_semester_edit', ['id' => $semester->getId()]);
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
    public function edit(Request $request, Semester $semester, StudentRepository $studentRepository): Response
    {
        if (!$semester->isEditable()) {
            // TODO Add notification
            return $this->redirectToRoute('administration_semester_show', ['id' => $semester->getId()]);
        }

        $formerGroups = $semester->getGroups()->toArray();

        $form = $this->createForm(SemesterType::class, $semester);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // TODO Verify semester's course didn't change

            $currentGroups = $semester->getGroups();

            // Add new groups
            foreach ($currentGroups as $group) {
                if (!in_array($group, $formerGroups, true)) {
                    $group->setSemester($semester);
                }
            }

            // Remove former groups
            foreach ($formerGroups as $group) {
                if (!$currentGroups->contains($group)) {
                    $em->remove($group);
                }
            }

            $em->flush();

            return $this->redirectToRoute('administration_semester_edit', ['id' => $semester->getId()]);
        }

        $students = $studentRepository
            ->findAvailableForSemester($semester);

        return $this->render('administration/semester/edit.html.twig', [
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
            // TODO Add notification
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
