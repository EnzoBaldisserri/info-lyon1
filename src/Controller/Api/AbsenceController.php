<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/absence", name="api_absence_")
 */
class AbsenceController extends Controller
{
    /**
     * @Route("/get_all", name="getall")
     */
    public function getAll() {
        $doctrine = $this->getDoctrine();

        $semester = $doctrine
            ->getRepository(Semester::class)
            ->findOneOfCurrent();

        if (null !== $semester) {
            $groups = $doctrine
                ->getRepository(Group::class)
                ->findInSemesterWithAbsences($semester);
        }

        return JsonResponse([
            'semester' => $semester,
            'groups' => $groups ?? [],
        ]);
    }
}
