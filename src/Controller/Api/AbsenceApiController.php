<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Translation\TranslatorInterface;
use App\Controller\BaseController;
use App\Entity\Administration\Semester;
use App\Entity\Administration\Group;
use App\Service\TimeHelper;

/**
 * @Route("/absence", name="api_absence_")
 */
class AbsenceApiController extends BaseController
{
    /**
     * @Route("/get_all", name="getall")
     */
    public function getAll(TimeHelper $timeHelper, TranslatorInterface $translator)
    {
        $doctrine = $this->getDoctrine();

        $semesters = $doctrine
            ->getRepository(Semester::class)
            ->findCurrent();

        if (empty($semesters)) {
            $error = $translate->trans('no_current_semester', [], 'error');
        } else {
            $period = reset($semesters)->getPeriod();

            $firstDay = $period->getStart()->format('Y-m-d\TH:i:s');

            $months = $timeHelper
                ->getPeriodMonths($period, true);

            $groups = $doctrine
                ->getRepository(Group::class)
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
