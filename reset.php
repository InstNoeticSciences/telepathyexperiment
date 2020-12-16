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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <link rel="stylesheet" type="text/css" href="css/forms.css" />
        <link rel="stylesheet" type="text/css" href="css/tags.css" />
        <link rel="stylesheet" type="text/css" href="css/divs.css" />
        <title>Reset Password Request</title>
        <link rel="shortcut icon"
              href="graphics/telephone_telepathy_icon.jpg"
              type="image/x-icon" />
        <script type="text/javascript" src="js/util.js"></script>
    </head>
    <body>
        <form name="reset" method="post" action="scripts/do_reset.php">
            <div class="wrapper">
                <div class="banner">
                    <?php
                    banner();
                    ?>
                </div>
                <div class="header_left">
                    <?php
                    main_menu($_SESSION['auth'], INDEX);
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
                    <h3>Enter your username and email address:</h3>
                    <table>
                        <tr>
                            <td>
                            <?php
                            field_label("username", "Username");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("username", false, $_SESSION['username'], 12);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("email", "Email Address");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("email", false, $_SESSION['email'], 35, 50);
                            ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="right_box">
                    <h3>How to reset your password:</h3>
                    <p class="info">
                        Enter the username and the email address that you
                        supplied when you registered. Note that <u>your user
                        record will be locked</u> until the reset procedure
                        is complete. Further instructions will be sent to you
                        on completion of this form.
                    </p>
                </div>
                <div class="footer">
                    <?php
                    input_field_submit("enter", "x", "invisible");
                    input_field_submit("reset", "Reset", "button");
                    input_field_message("reset_result", $_SESSION['reset_result'], 100);
                    ?>
                    <div class="twilio">
                        
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>