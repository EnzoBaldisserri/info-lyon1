<?php

namespace App\Service;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class NavbarBuilder
{
    const MENU = [
        'ROLE_STUDENT' => [
            'app.menu.absences'       => 'absence_index',
            'app.menu.marks'          => 'mark_index',
        ],
        'ROLE_TEACHER' => [
            'app.menu.absences'       => 'absence_index',
            'app.menu.controls'       => 'control_index',
        ],
        'ROLE_SECRETARIAT' => [
            'app.menu.absences'       => 'absence_index',
        ],
        'ROLE_PROJECT_MEMBER'   => [
            'app.menu.project'        => 'project_index',
        ],
        'ROLE_FORUM_ACCESS' => [
            'app.menu.forum'          => 'forum_index',
        ],
        'ROLE_FOLLOW_UP' => [
            'app.menu.follow_up'      => 'followup_index',
        ],
        'ROLE_ADMINISTRATIVE' => [
            'app.menu.administration' => 'administration_index',
        ],
        'ROLE_SCHEDULED' => [
            'app.menu.schedule'       => 'schedule_index',
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
