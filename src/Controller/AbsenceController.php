<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Controller\BaseController;
use App\Entity\Absence\AbsenceType;

/**
 * @Route("/absence", name="absence_")
 */
class AbsenceController extends BaseController
{
    /**
     * @Route(
     *   "/{argument}",
     *   name="homepage",
     *   requirements={"argument"="[sS]\d|(([01][0-9])|(2[0-3])):[0-5][0-9]"}
     * )
     */
    public function index($argument = null) {
        $user = $this->getUser();

        if (null === $user) {
            throw $this->createAccessDeniedException('Accès Interdit');
        }

        if ($user->hasRole('ROLE_STUDENT') && (null === $argument || preg_match('/^[sS]\d$/', $argument))) {
            return $this->student($argument);
        }

        if ($user->hasRole('ROLE_TEACHER') && (null === $argument || preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $argument))) {
            return $this->teacher($argument);
        }

        if ($user->hasRole('ROLE_SECRETARIAT') && null === $argument) {
            return $this->secretariat();
        }

        throw $this->createNotFoundException('Cette page n\'existe pas');
    }

    private function student($semester = null)
    {
        $this->denyAccessUnlessGranted('ROLE_STUDENT');

        return $this->show('absence/student.html.twig', [
            'absences' => [],
        ]);
    }

    private function teacher($hour = null)
    {
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        return $this->show('absence/teacher.html.twig', [
            'controller_name' => 'AbsenceController',
        ]);
    }

    private function secretariat()
    {
        $this->denyAccessUnlessGranted('ROLE_SECRETARIAT');

        $absenceTypes = $this->getDoctrine()
            ->getRepository(AbsenceType::class)
            ->findAll();

        return $this->show('absence/secretariat.html.twig', [
            'absenceTypes' => $absenceTypes,
            'groups' => [],
            'students' => [],
            'beginDate' => new \DateTime,
            'period' => [],
        ]);
    }
}
