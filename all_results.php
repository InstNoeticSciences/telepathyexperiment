<?php
include("inc/config.php");
include("lib/util.php");
include("classes/database.php");
include("classes/user.php");

session_start();

authenticate($_SESSION['auth'], "index.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <link rel="stylesheet" type="text/css" href="css/forms.css" />
        <link rel="stylesheet" type="text/css" href="css/tags.css" />
        <link rel="stylesheet" type="text/css" href="css/divs.css" />
        <script type="text/javascript" src="js/browser.js"></script>
        <title>Results Overview</title>
        <link rel="shortcut icon"
              href="graphics/telephone_telepathy_icon.jpg"
              type="image/x-icon" />
    </head>
    <body>
        <div class="wrapper">
            <div class="banner">
                <?php
                banner();
                ?>
            </div>
                <div class="header_left">
                    <?php
                    main_menu($_SESSION['auth'], ALL_RESULTS);
                    ?>
                </div>
                <div class="header_right">
                    <?php
                    admin_menu($_SESSION['auth'], isset($_SESSION['user']) ?
                                                  $_SESSION['user']->is_admin() :
                                                  false);
                    ?>
                </div>
            <div class="left_box">
            </div>
            <div class="right_box">
            </div>
            <div class="footer">
            </div>
        </div>
    </body>
</html>