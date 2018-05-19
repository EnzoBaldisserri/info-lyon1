<?php

namespace App\Controller\Api;

use App\Controller\BaseController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;
use App\Repository\Administration\SemesterRepository;
use App\Repository\Administration\GroupRepository;
use App\Service\TimeHelper;

/**
 * @Route("/absence", name="api_absence_")
 */
class AbsenceApiController extends BaseController
{
    /**
     * @Route("/get_all", name="getall")
     */
    public function getAll(
        TimeHelper $timeHelper,
        TranslatorInterface $translator,
        SemesterRepository $semesterRepository,
        GroupRepository $groupRepository
    ) {
        $doctrine = $this->getDoctrine();

        $semesters = $semesterRepository->findCurrent();

        if (empty($semesters)) {
            $error = $translate->trans('error.no_current_semester');
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
