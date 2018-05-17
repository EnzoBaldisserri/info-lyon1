<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Controller\BaseController;
use App\Entity\Absence\Absence;
use App\Entity\Absence\AbsenceType;
use App\Entity\Administration\Semester;
use App\Entity\Administration\Group;

/**
 * @Route("/absence", name="absence_")
 */
class AbsenceController extends BaseController
{
    private $translator;

    /**
     * @Route(
     *   "/{argument}",
     *   name="index",
     *   requirements={"argument"="[sS]\d|(([01][0-9])|(2[0-3])):[0-5][0-9]"}
     * )
     */
    public function index($argument = null, TranslatorInterface $translator) {
        $this->translator = $translator;

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

    private function student(string $semester = null)
    {
        $this->denyAccessUnlessGranted('ROLE_STUDENT');

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
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        return $this->createHtmlResponse('absence/teacher.html.twig', [
            'controller_name' => 'AbsenceController',
        ]);
    }

    private function secretariat()
    {
        $this->denyAccessUnlessGranted('ROLE_SECRETARIAT');

        $doctrine = $this->getDoctrine();

        $absenceTypesRaw = $doctrine
            ->getRepository(AbsenceType::class)
            ->findAll();

        $absenceTypes = [];
        foreach ($absenceTypesRaw as $type) {
            $name = $type->getName();
            $absenceTypes[$name] = $this->translator->trans("absence.type.$name");
        }

        return $this->createHtmlResponse('absence/secretariat.html.twig', [
            'absenceTypes' => $absenceTypes ?? null,
        ]);
    }
}
