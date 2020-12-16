<?php
include("inc/config.php");
include("lib/util.php");
include("classes/database.php");
include("classes/password.php");
include("classes/user.php");

session_start();

require_once("lib/recaptchalib.php");

// empty the message variables from other pages
$_SESSION['password_result'] = '';
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
        <link rel="stylesheet" type="text/css" href="js/dhtmlxCalendar/codebase/dhtmlxcalendar.css">
        <script type="text/javascript" src="js/dhtmlxCalendar/codebase/dhtmlxcommon.js"></script>
        <script type="text/javascript" src="js/dhtmlxCalendar/codebase/dhtmlxcalendar.js"></script>
        <script type="text/javascript">window.dhx_globalImgPath="js/dhtmlxCalendar/codebase/imgs/";</script>
        <script type="text/javascript">
            var RecaptchaOptions = {
               theme: 'white',
               tabindex: 8
            };
        </script>
        <title>Experimenter Registration</title>
        <link rel="shortcut icon"
              href="graphics/telephone_telepathy_icon.jpg"
              type="image/x-icon" />
        <script type="text/javascript" src="js/util.js"></script>
    </head>
    <body>
        <form name="register" method="post" action="scripts/do_register.php">
            <div class="wrapper">
                <div class="banner">
                    <?php
                    banner();
                    ?>
                </div>
                <div class="header_left">
                    <?php
                    main_menu($_SESSION['auth'], REGISTER);
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
                    <h3>Enter your details:</h3>
                    <table>
                        <tr>
                            <td>
                            <?php
                            field_label("first_name", "First Name");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("first_name", 
                                             false,
                                             $_SESSION['first_name'],
                                             20,
                                             20,
                                             "Characters A-Z, a-z and space only");
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("last_name", "Last Name");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("last_name", 
                                             false,
                                             $_SESSION['last_name'],
                                             20,
                                             20,
                                             "Characters A-Z, a-z and space only");
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("age", "Age");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("age", 
                                             false,
                                             $_SESSION['age'],
                                             3,
                                             3,
                                             "Between ".AGE_MIN." and ".AGE_MAX." years");
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("gender", "Gender");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_select("gender", 
                                               false,
                                               array("Male", "Female"),
                                               1,
                                               $_SESSION['gender']);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("phone", "Phone");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("phone", 
                                             false,
                                             $_SESSION['phone'],
                                             20,
                                             20,
                                             "Landline or cell phone");
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
                            input_field_text("email", 
                                             false,
                                             $_SESSION['email'],
                                             32,
                                             50);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("username", "Username");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("username",
                                             false,
                                             $_SESSION['username'],
                                             12,
                                             12,
                                             "Between ".UNAME_MIN_LENGTH." ".
                                             "and ".UNAME_MAX_LENGTH." ".
                                             "characters");
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
                            input_field_password("password",
                                                 false,
                                                 "",
                                                 10,
                                                 "Must be ".PWD_LENGTH." ".
                                                 "characters long");
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
                    <h3>Enter the code below:</h3>
                    <?php echo recaptcha_get_html(captcha_pub); ?><br>
                    <?php input_field_submit("register", "Register", "button"); ?>
                </div>
                <div class="footer">
                    <?php
                    input_field_submit("enter", "x", "invisible");
                    input_field_message("register_result", $_SESSION['register_result'], 100);
                    ?>
                </div>
            </div>
        </form>
    </body>
</html>
