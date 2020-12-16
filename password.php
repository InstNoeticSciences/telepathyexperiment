<?php
include("inc/config.php");
include("lib/util.php");

session_start();

// empty the message variables from other pages
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
        <title>Reset Password</title>
        <link rel="shortcut icon"
              href="graphics/telephone_telepathy_icon.jpg"
              type="image/x-icon" />
        <script type="text/javascript" src="js/util.js"></script>
    </head>
    <body>
        <form name="new_password" method="post" action="scripts/do_password.php">
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
                    <h3>Verify your identity:</h3>
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
                            field_label("code", "Verification Code");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("code", false, "", 20);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("password", "Password");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_password("password", false, "", 10);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("rep_password", "Repeat Password");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_password("rep_password", false, "", 10);
                            ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="right_box">
                    <h3>Password reset instructions:</h3>
                    <p class="info">
                        You should have received an email message containing a
                        verification code. Enter it here (you can copy and
                        paste the value) as well as your username and new
                        password. When you click <b>Reset</b> your user will
                        be unlocked and you can <a href="login.php">login</a>
                        with your new password.
                    </p>
                </div>
                <div class="footer">
                    <?php
                        input_field_submit("enter", "x", "invisible");
                        input_field_submit("reset", "Reset", "button");
                        input_field_message("password_result", $_SESSION['password_result'],100);
                    ?>
                </div>
            </div>
        </form>
    </body>
</html>
