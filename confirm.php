<?php
include("inc/config.php");
include("lib/util.php");
include("classes/database.php");
include("classes/password.php");
include("classes/user.php");

session_start();

// empty the message variables from other pages
$_SESSION['password_result'] = '';
$_SESSION['register_result'] = '';
$_SESSION['update_result'] = '';
$_SESSION['contact_result'] = '';
$_SESSION['profile_result'] = '';
$_SESSION['config_result'] = '';
$_SESSION['login_result'] = '';
$_SESSION['reset_result'] = '';
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
        <script type="text/javascript" src="js/util.js"></script>
        <title>Confirm User Delete</title>
        <link rel="shortcut icon"
              href="graphics/telephone_telepathy_icon.jpg"
              type="image/x-icon" />
    </head>
    <body>
        <form name="confirm" method="post" action="scripts/do_confirm.php">
            <div class="wrapper">
                <div class="banner">
                    <?php
                    banner();
                    ?>
                </div>
                <div class="header_left">
                    <h3>WARNING: Your profile and all of your friends will
                                 be permanently deleted!</h3>
                </div>
                <div class="header_right">
                    <?php
                    admin_menu($_SESSION['auth'], isset($_SESSION['user']) ?
                                                  $_SESSION['user']->is_admin() :
                                                  false);
                    ?>
                </div>
                <div class="left_box">
                    <p>Are you sure?</p>
                </div>
                <div class="right_box"></div>
                <div class="footer">
                    <?php
                    input_field_submit("enter", "x", "invisible");
                    input_field_submit("confirm", "Yes", "button");
                    input_field_submit("deny", "No", "button");
                    ?>
                </div>
            </div>
        </form>
    </body>
</html>

