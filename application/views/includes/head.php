<!DOCTYPE html>
<html>
<head>
    <title><?= isset($title) ? $title : 'Teckmeb' ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <?php
    if (!empty($css)) {
        foreach ($css as $c) { ?>
            <link rel="stylesheet" type="text/css" href="<?= css_url($c) ?>">
            <?php
        }
    } else { ?>
        <link rel="stylesheet" type="text/css" href="<?= css_url('style') ?>">
        <?php
    }

    $debug = ENVIRONMENT === 'development'; ?>
</head>
<body>
    <header>
        <?php
        if ($debug) {

            $data_print = $data;
            array_walk($data_print, function(&$value) {
                if (is_string($value)) {
                    $value = '"""' . htmlspecialchars($value, ENT_QUOTES | ENT_HTML5) . '"""';
                }
            });
            ?>
            <pre id="debug">
                <?php print_r($data_print); ?>
            </pre>
            <div id="debug-toolbar" class="row no-margin">
                <a href="/debug/session" class="btn-flat">session</a>
                <a href="/debug/fillnotif" class="btn-flat">fill notifs</a>
            </div>
            <?php
        }

        $nav = array(
            'student' => array(
                'absences' => 'absence',
                'notes' => 'mark',
                'ptut' => 'project',
                'questions' => 'question'
            ),
            'teacher' => array(
                'absences' => 'absence',
                'controles' => 'control',
                'ptut' => 'project',
                'questions' => 'question',
                'suivi' => 'student'
            ),
            'secretariat' => array(
                'absences' => 'absence',
                'administration' => 'administration',
                'suivi' => 'student'
            )
        );
        ?><nav class="nav-extended">
            <div class="nav-wrapper">
                <a class="brand-logo small-caps" href="/">Teckmeb</a>
                <!-- computer nav -->
                <ul class="right hide-on-med-and-down">
                    <?php foreach ($nav[$_SESSION['userType']] as $item => $url) {
                        $active = $pageName === $url
                            ? ' active' : '';
                        ?>
                        <li class="small-caps <?= $active ?>"><a href="<?= base_url($url) ?>"><?= $item ?></a></li>
                        <?php
                    } ?>
                    <li>
                        <?php
                        if (empty($notifications)) { ?>
                            <a class="dropdown-button notification-wrapper"
                               data-activates="nav-notifications" data-constrainwidth="false">
                               <i class="material-icons">notifications_none</i>
                            </a>
                            <ul id="nav-notifications" class="dropdown-content">
                                <li><p>Pas de notifications</p></li>
                            </ul>
                            <?php
                        } else { ?>
                            <a class="dropdown-button notification-wrapper"
                                data-activates="nav-notifications" data-constrainwidth="false">
                                <i class="material-icons">notifications</i>
                                <span class="badge new materialize-red lighten-2"
                                    data-badge-caption=""><?= count($notifications) ?></span>
                            </a>
                            <ul id="nav-notifications" class="dropdown-content">
                                <?php
                                foreach ($notifications as $notif) { ?>
                                    <li data-notif-id="<?= $notif['idNotification'] ?>"
                                        <?php
                                        if ($notif['link']) { ?>
                                            data-notif-link="<?= base_url($notif['link']) ?>"
                                            <?php
                                        } ?>
                                        class="notif notif-<?= $notif['type'] ?> notif-<?= $notif['storage'] ?>">
                                        <div class="valign-wrapper">
                                            <i class="material-icons left"><?= $notif['icon'] ?></i>
                                            <span><?= $notif['content'] ?></span>
                                        </div>
                                    </li>
                                    <?php
                                } ?>
                            </ul>
                            <?php
                        } ?>
                    </li>
                    <li>
                        <a class="dropdown-button"
                           data-activates="nav-user" data-constrainwidth="false">
                            <i class="material-icons">account_circle</i>
                        </a>
                        <ul id="nav-user" class="dropdown-content">
                            <li>
                                <div><?= $_SESSION['surname'] ?></div>
                                <div><?= $_SESSION['name'] ?></div>
                            </li>
                            <li class="divider"></li>
                            <li class="small-caps"><a href="/user/disconnect">Déconnexion</a></li>
                        </ul>
                    </li>
                </ul>

                <!-- mobile nav -->
                <a class="button-collapse hide-on-large-only" data-activates="nav-mobile">
                    <i class="material-icons">menu</i>
                </a>
                <ul class="right hide-on-large-only">
                    <li>
                        <a href="#m-notifications" class="modal-trigger notification-wrapper">
                            <?php
                            if (empty($notifications)) { ?>
                                <i class="material-icons right">notifications_none</i>
                                <?php
                            } else { ?>
                                <i class="material-icons right">notifications</i>
                                <span class="badge new materialize-red lighten-2"
                                    data-badge-caption=""><?= count($notifications) ?></span>
                                <?php
                            } ?>
                        </a>
                    </li>
                </ul>
                <ul class="side-nav" id="nav-mobile">
                    <?php
                    foreach ($nav[$_SESSION['userType']] as $item => $url)
                    {
                        $active = $pageName === $url
                            ? 'active' : '';
                        ?>
                        <li class="small-caps <?= $active ?>"><a href="<?= $url ?>"><?= $item ?></a></li>
                        <?php
                    } ?>
                    <li class="divider"></li>
                    <li>
                        <a class="dropdown-button" data-activates="m-nav-user">
                            <?= $_SESSION['surname'] . ' ' . $_SESSION['name'] ?>
                            <i class="material-icons right">keyboard_arrow_down</i>
                        </a>
                        <ul id="m-nav-user" class="dropdown-content">
                            <li class="small-caps"><a href="/user/disconnect">Déconnexion</a></li>
                        </ul>
                    </li>
                </ul>
                <div id="m-notifications" class="modal modal-fixed-footer black-text">
                    <div class="modal-content">
                        <h4 class="center-align">Notifications</h4>
                        <div class="collection">
                            <?php
                            if (empty($notifications)) { ?>
                                <div class="collection-item">Pas de notifications</div>
                                <?php
                            } else {
                                foreach ($notifications as $notif) { ?>
                                    <div data-notif-id="<?= $notif['idNotification'] ?>"
                                         <?php
                                         if ($notif['link']) { ?>
                                             data-notif-link="<?= base_url($notif['link']) ?>"
                                            <?php
                                         } ?>
                                         class="collection-item notif notif-<?= $notif['type'] ?> notif-<?= $notif['storage'] ?>">
                                        <div class="valign-wrapper">
                                            <i class="material-icons left"><?= $notif['icon'] ?></i>
                                            <span><?= $notif['content'] ?></span>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn waves-effect waves-light modal-action modal-close">Fermer</a>
                    </div>
                </div>
            </div>
            <?php
            if (isset($data['tabs'])) { ?>
                <div class="nav-content">
                    <ul class="tabs tabs-transparent">
                        <?php
                        foreach ($data['tabs'] as $tab) { ?>
                            <li class="tab">
                                <a target="_self" href="<?= base_url($tab->url) ?>"
                                    <?= $tab->active ? 'class="active"' : '' ?>
                                    ><?= $tab->content ?></a>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </div>
                <?php
            } ?>
        </nav>
    </header>
