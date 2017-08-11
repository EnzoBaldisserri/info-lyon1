<!DOCTYPE html>
<html>
    <head>
        <title><?php echo isset($title) ? $title : 'Teckmeb'; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" href="<?php echo css_url('style') ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo css_url('head') ?>">
        <?php
        if (isset($css)) {
            foreach ($css as $c) {
                echo '<link rel="stylesheet" type="text/css" href="' . css_url($c) . '">';
            }
        }

        $debug = isset($js) && in_array('debug', $js) && isset($data);
        ?>
    </head>
    <body>
        <?php
        if ($debug) { ?>
        <pre id="debug">
        <?php
            function makeReceivedDataPrintable(&$value) {
                if (!is_array($value)) {
                    $value = '"""' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"""';
                }
            }

            array_walk($data, 'makeReceivedDataPrintable');
            print_r($data);
        ?>
        </pre>
        <?php } ?>

        <?php
        if ( !empty($_SESSION['notif']) ) { ?>
        <div id="notifications">
            <?php
            foreach ($_SESSION['notif'] as $notif) {
                echo '<div class="notif">' . $notif . '</div>';
            }
            echo html_img('close_icon.png', 'close icon');
            ?>
        </div>
        <?php } ?>


        <header>
            <a id="header_title" href="/">
                <?php echo html_img('teckmeb_logo.png', 'Logo Teckmeb'); ?>
            </a>
            <nav>
                <ul>
                    <?php
                        if ( isset($_SESSION['user_type']) &&
                            in_array($_SESSION['user_type'], array('student', 'teacher', 'secretariat')))
                        {
                                $nav = array(
                                    'student' => array( 'absences', 'note', 'ptut', 'questions' ),
                                    'teacher' => array( 'absences', 'controles', 'ptut', 'questions' ),
                                    'secretariat' => array( 'absences' )
                                );

                                // Display menu depending on the user
                                foreach ($nav[$_SESSION['user_type']] as $item) {
                                    echo '<li><a href="' . $item . '">' . $item . '</a></li>';
                                }

                        } else {
                            //TODO Change once connection is done
                            unset($_SESSION['user_type']);
                    ?>
                        <li><a href="#">ABSENCES</a></li>
                        <li><a href="#">NOTES</a></li>
                        <li><a href="#">PTUT</a></li>
                        <li><a href="#">QUESTIONS</a></li>
                    <?php } ?>
                </ul>
            </nav>
            <div id="header_profile">
                <?php
                    echo html_img('header_account.png', 'account');
                    if ( isset($_SESSION['name']) and isset($_SESSION['surname']) ) { ?>
                    <ul>
                        <li>
                            <div><?php echo $_SESSION['surname']; ?></div>
                            <div><?php echo $_SESSION['name']; ?></div>
                        </li>
                        <li><a href="/user/disconnect">Déconnexion</a></li>
                    </ul>
                <?php } ?>
            </div>
        </header>
