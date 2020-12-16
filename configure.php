<?php
include("inc/config.php");
include("lib/util.php");
include("classes/database.php");
include("classes/config_file.php");
include("classes/user.php");

session_start();

authenticate($_SESSION['auth'], "index.php");
admin_check($_SESSION['user']->is_admin(), "index.php");

// empty the message variables from other pages
$_SESSION['password_result'] = '';
$_SESSION['register_result'] = '';
$_SESSION['update_result'] = '';
$_SESSION['contact_result'] = '';
$_SESSION['login_result'] = '';
$_SESSION['reset_result'] = '';

if(!isset($_SESSION['config_file'])) {
    $path = dirname(realpath("configure.php"))."/inc/config.php";
    $config_file = new config_file($path);
    $config_file->read_file();
    $_SESSION['config_file'] = $config_file;
    $_SESSION['edit_config'] = MODE_READONLY;
} // end if

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
        <title>Experiment Configuration</title>
        <link rel="shortcut icon"
              href="graphics/telephone_telepathy_icon.jpg"
              type="image/x-icon" />
    </head>
    <body>
        <form name="configure" method="post" action="scripts/do_config.php">
            <div class="wrapper">
                <div class="banner">
                    <?php
                    banner();
                    ?>
                </div>
                <div class="header_left">
                    <?php
                    main_menu($_SESSION['auth'], CONFIGURE);
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
                    <table>
                        <tr><td><h3>Database</h3></td></tr>
                        <tr>
                            <td>
                            <?php
                            field_label("db_host", "DB Host");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("db_host",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('db_host'),
                                             30);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("db_name", "DB Name");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("db_name",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('db_name'),
                                             30);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("db_type", "DB Type");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_select("db_type",
                                               strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                               array("MySQL", "PostgreSQL"),
                                               $_SESSION['config_file']->get_value('db_type'),
                                               $_SESSION['db_type']);
                            ?>
                            </td>
                        </tr>
                       <tr>
                            <td>
                            <?php
                            field_label("db_user", "DB User");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("db_user",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('db_user'),
                                             30);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("db_pass", "DB Password");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("db_pass",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('db_pass'),
                                             30);
                            ?>
                            </td>
                        </tr>
                        <tr><td><br /><h3>Twilio</h3></td></tr>
                        <tr>
                            <td>
                            <?php
                            field_label("TWILIO_USER", "Twilio User Email");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("TWILIO_USER",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('TWILIO_USER'),
                                             30);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("TWILIO_PASS", "Twilio Password");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("TWILIO_PASS",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('TWILIO_PASS'),
                                             30);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("TWILIO_PHONE", "Twilio Phone");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("TWILIO_PHONE",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('TWILIO_PHONE'),
                                             30);
                            ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="right_box">
                    <table>
                        <tr><td><h3>Experiment</h3></td></tr>
                        <tr>
                            <td>
                            <?php
                            field_label("MAX_FRIENDS", "Max. Friends");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("MAX_FRIENDS",
                                             true,
                                             $_SESSION['config_file']->get_value('MAX_FRIENDS'),
                                             1);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("MAX_TRIALS", "Max. Trials");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("MAX_TRIALS",
                                             true,
                                             $_SESSION['config_file']->get_value('MAX_TRIALS'),
                                             1);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("SMS_MIN", "SMS Delay (min)");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("SMS_MIN",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('SMS_MIN'),
                                             5);

                            field_label("SMS_MAX", " to ");

                            input_field_text("SMS_MAX",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('SMS_MAX'),
                                             5);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("AGE_MIN", "Age Range");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("AGE_MIN",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('AGE_MIN'),
                                             5);

                            field_label("AGE_MAX", " to ");

                            input_field_text("AGE_MAX",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('AGE_MAX'),
                                             5);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("PIN_MIN", "Pin Range");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("PIN_MIN",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('PIN_MIN'),
                                             5);

                            field_label("PIN_MAX", " to ");

                            input_field_text("PIN_MAX",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('PIN_MAX'),
                                             5);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("FIRST_EXTENSION", "Extension Range");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("FIRST_EXTENSION",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('FIRST_EXTENSION'),
                                             5);

                            field_label("LAST_EXTENSION", " to ");

                            input_field_text("LAST_EXTENSION",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('LAST_EXTENSION'),
                                             5);
                            ?>
                            </td>
                        </tr>
                        <tr><td><br /><h3>Site</h3></td></tr>
                        <tr>
                            <td>
                            <?php
                            field_label("SYSTEM_ADMIN", "Admin. Email");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("SYSTEM_ADMIN",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('SYSTEM_ADMIN'),
                                             23,
                                             30);
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("MAX_LOGIN_ATTEMPTS", "Login Attempts");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("MAX_LOGIN_ATTEMPTS",
                                             strcmp($_SESSION['edit_config'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['config_file']->get_value('MAX_LOGIN_ATTEMPTS'),
                                             2);
                            ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="footer">
                    <?php
                    input_field_submit("enter", "x", "invisible");

                    if($_SESSION['edit_config'] == MODE_READONLY) {
                        input_field_submit("change", "Change", "button");
                    } else {
                        input_field_submit("display", "Discard Changes", "button");
                        input_field_submit("update", "Save", "button");
                    } // end if

                    input_field_message("config_result", $_SESSION['config_result'], 100);
                    ?>
                </div>
            </div>
        </form>
    </body>
</html>
