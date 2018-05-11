<?php

namespace App\Service;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class NavbarBuilder
{
    const MENU = [
        'ROLE_STUDENT' => [
            'app.menu.absences'       => 'absence_homepage',
            'app.menu.marks'          => 'mark_homepage',
        ],
        'ROLE_TEACHER' => [
            'app.menu.absences'       => 'absence_homepage',
            'app.menu.controls'       => 'control_homepage',
        ],
        'ROLE_SECRETARIAT' => [
            'app.menu.absences'       => 'absence_homepage',
        ],
        'ROLE_PROJECT_MEMBER'   => [
            'app.menu.project'        => 'project_homepage',
        ],
        'ROLE_FORUM_ACCESS' => [
            'app.menu.forum'          => 'forum_homepage',
        ],
        'ROLE_FOLLOW_UP' => [
            'app.menu.follow_up'      => 'followup_homepage',
        ],
        'ROLE_ADMINISTRATIVE' => [
            'app.menu.administration' => 'administration_homepage',
        ],
        'ROLE_SCHEDULED' => [
            'app.menu.schedule'       => 'schedule_homepage',
        ]
    ];

    private $authChecker;

    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    public function getNavigation(): Array
    {
        $navigation = [];

        foreach (self::MENU as $role => $routes) {
            if ($this->authChecker->isGranted($role)) {
                $navigation = array_merge($navigation, $routes);
            }
        }

        return $navigation;
    }
}
