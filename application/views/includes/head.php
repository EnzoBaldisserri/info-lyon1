<!DOCTYPE html>
<html>
    <head>
        <title><?= isset($title) ? $title : 'Teckmeb' ?></title>
        <meta charset="utf-8">
        <!-- Import Google Icon Font -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- Import materialize.css -->
        <link type="text/css" rel="stylesheet" href="<?= css_url('materialize') ?>"  media="screen,projection">

        <!-- Let browser know website is optimized for mobile -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php
        if ( !empty($css) ) {
            foreach ($css as $c) {
                echo '<link rel="stylesheet" type="text/css" href="' . css_url($c) . '">';
            }
        } else {
            echo '<link rel = "stylesheet" type = "text/css" href = "' . css_url('style') . '">';
        }

        $debug = isset($js) && in_array('debug', $js) && isset($data); ?>

    </head>
    <body>
    <?php
        if ($debug) {

            function makeReceivedDataPrintable(&$value) {
                if (is_string($value)) {
                    $value = '"""' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"""';
                }
            }

            echo '<pre id="debug">' . PHP_EOL;
            array_walk($data, 'makeReceivedDataPrintable');
            print_r($data);
            echo '</pre>' . PHP_EOL;
        }

        if ( !empty($_SESSION['notif']) ) { ?>
        <div id="notifications">
            <?php
            foreach ($_SESSION['notif'] as $notif) {
                echo '<div class="notif">' . $notif . '</div>';
            }
            echo html_img('close_icon.png', 'close icon'); ?>

        </div><?php } ?>

        <header>
            <a id="header_title" href="/">
                <?= html_img('teckmeb_logo.png', 'Logo Teckmeb') . PHP_EOL ?>
            </a>
            <nav>
                <ul>
                    <?php
                    $nav = array(
                        'student' => array(
                            'absences' => '/Etudiant/Absence',
                            'notes' => '/Etudiant/Note',
                            'ptut' => '/Etudiant/PTUT',
                            'questions' => '/Etudiant/Question'
                        ),
                        'teacher' => array(
                            'absences' => '/Professeur/Absence',
                            'controles' => '/Professeur/Controle',
                            'ptut' => '/Professeur/PTUT',
                            'questions' => '/Professeur/Question'
                        ),
                        'secretariat' => array(
                            'absences' => '/Secretariat/Absence'
                        )
                    );

                    // Display menu depending on the user
                    foreach ($nav[$_SESSION['user_type']] as $item => $url) {
                        echo '<li><a href="' . $url . '">' . $item . '</a></li>';
                    } ?>

                </ul>
            </nav>
            <div id="header_profile">
            <?php
            echo html_img('header_account.png', 'account');
            if ( isset($_SESSION['name']) and isset($_SESSION['surname']) ) { ?>

                <ul>
                    <li>
                        <div><?= $_SESSION['surname']; ?></div>
                        <div><?= $_SESSION['name']; ?></div>
                    </li>
                    <li><a href="/user/disconnect">Déconnexion</a></li>
                </ul><?php } ?>

            </div>
        </header>
