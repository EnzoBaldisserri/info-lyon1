<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Absence\Absence;
use App\Entity\Absence\AbsenceType;
use App\Entity\Administration\Semester;

/**
 * @Route("/absence", name="absence_")
 */
class AbsenceController extends BaseController
{
    /**
     * @Route(
     *   "/{argument}",
     *   name="index",
     *   requirements={"argument"="[sS]\d|(([01][0-9])|(2[0-3])):[0-5][0-9]"}
     * )
     */
    public function index($argument = null)
    {
        $user = $this->getUser();

        if ($user->hasRole('ROLE_STUDENT') && (null === $argument || preg_match('/^[sS]\d$/', $argument))) {
            return $this->student($argument);
        }

        if ($user->hasRole('ROLE_TEACHER') && (null === $argument || preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $argument))) {
            return $this->teacher($argument);
        }

        if ($user->hasRole('ROLE_SECRETARIAT') && null === $argument) {
            return $this->secretariat();
        }

        throw $this->createNotFoundException();
    }

    private function student(string $semester = null)
    {
        $doctrine = $this->getDoctrine();

        $semester = $doctrine
            ->getRepository(Semester::class)
            ->findCurrentPeriod();

        if (null !== $semester) {
            $student = $this->getUser();

            $absences = $doctrine
                ->getRepository(Absence::class)
                ->getInPeriodForStudent($semester, $student);
        }

        return $this->createHtmlResponse('absence/student.html.twig', [
            'absences' => $absences ?? [],
        ]);
    }

    private function teacher(string $hour = null)
    {
        return $this->createHtmlResponse('absence/teacher.html.twig', [
            'controller_name' => 'AbsenceController',
        ]);
    }

    private function secretariat()
    {
        $doctrine = $this->getDoctrine();

        $absenceTypes = $doctrine
            ->getRepository(AbsenceType::class)
            ->findAllWithNames();

        return $this->createHtmlResponse('absence/secretariat.html.twig', [
            'absenceTypes' => $absenceTypes,
        ]);
    }
}
