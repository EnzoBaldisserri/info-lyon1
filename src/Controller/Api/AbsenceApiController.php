<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use App\Repository\Administration\SemesterRepository;
use App\Repository\Administration\GroupRepository;
use App\Helper\TimeHelper;

/**
 * @Route("/absence", name="absence_")
 */
class AbsenceApiController extends BaseController
{
    /**
     * @Route("/get/all", name="get_all")
     */
    public function getAll(
        TimeHelper $timeHelper,
        TranslatorInterface $translator,
        SemesterRepository $semesterRepository,
        GroupRepository $groupRepository
    ) {
        $this->denyAccessUnlessGranted('ROLE_SECRETARIAT');

        $doctrine = $this->getDoctrine();

        $semesters = $semesterRepository->findCurrent();

        if (empty($semesters)) {
            $error = $translate->trans('error.semester.no_current');
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
        ]);
    }
}
