<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Controller\BaseController;
use App\Entity\Administration\Semester;
use App\Entity\Administration\Group;

/**
 * @Route("/absence", name="api_absence_")
 */
class AbsenceApiController extends BaseController
{
    /**
     * @Route("/get_all", name="getall")
     */
    public function getAll()
    {
        $doctrine = $this->getDoctrine();

        $semester = $doctrine
            ->getRepository(Semester::class)
            ->findOneOfCurrent();

        if (null !== $semester) {
            $groups = $doctrine
                ->getRepository(Group::class)
                ->findInSemesterWithAbsences($semester);
        }

        return $this->createJsonResponse([
            'semester' => $semester,
            'groups' => $groups ?? [],
        ]);
    }
}
