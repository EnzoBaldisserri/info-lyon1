<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Translation\TranslatorInterface;
use App\Repository\Administration\SemesterRepository;
use App\Repository\Administration\GroupRepository;
use App\Repository\Absence\AbsenceTypeRepository;
use App\Helper\TimeHelper;

/**
 * @Route("/absence", name="absence_")
 */
class AbsenceApiController extends BaseController
{
    /**
     * @Route("/get/all", name="get_all")
     * @Method({"GET"})
     */
    public function getAll(
        TimeHelper $timeHelper,
        TranslatorInterface $translator,
        AbsenceTypeRepository $absenceTypeRepository,
        SemesterRepository $semesterRepository,
        GroupRepository $groupRepository
    ) {
        $this->denyAccessUnlessGranted('ROLE_SECRETARIAT');

        $semesters = $semesterRepository->findCurrent();

        $absenceTypes = $absenceTypeRepository
            ->findAllWithNames();

        if (empty($semesters)) {
            $error = $translator->trans('error.semester.no_current');
        } else {
            $period = reset($semesters)->getPeriod();

            $firstDay = $period->getStart()->format(TimeHelper::JSON_TIME_FORMAT);

            $months = $timeHelper->getPeriodMonths($period, true);

            $groups = $groupRepository
                ->findInSemestersWithAbsences($semesters);
        }

        return $this->createJsonResponse([
            'error' => $error ?? null,
            'firstDay' => $firstDay ?? null,
            'months' => $months ?? [],
            'groups' => $groups ?? [],
            'absenceTypes' => $absenceTypes,
        ]);
    }
}
