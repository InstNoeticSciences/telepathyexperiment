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
$_SESSION['config_result'] = '';
$_SESSION['login_result'] = '';
$_SESSION['reset_result'] = '';
$_SESSION['edit_friends'] = "readwrite";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <link rel="stylesheet" type="text/css" href="css/forms.css" />
        <link rel="stylesheet" type="text/css" href="css/tags.css" />
        <link rel="stylesheet" type="text/css" href="css/divs.css" />
        <title>The Telephone Telepathy Experiment</title>
        <link rel="shortcut icon"
              href="graphics/telephone_telepathy_icon.jpg"
              type="image/x-icon" />
        <script type="text/javascript" src="js/util.js"></script>
    </head>
    <body>
        <form name="index" method="post" action="scripts/do_index.php">
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
                    <?php
                    if(isset($_SESSION['auth'])) {
                    ?>
                    <h3>To conduct an experiment:</h3>
                    <ol>
                        <li><p><a href="friends.php">Click here to add two friends</a></p></li>
                        <li><p>Make sure your friends are available then call <?php echo TWILIO_PHONE ?></p></li> 
                        <li><p>Listen to the instructions</p></li>
                        <!-- li><p>Enter pin <b><?php echo $_SESSION['user']->get_pin() ?></b> at the prompt</p></li>
                        <li><p>Make sure your friends are available to participate</p></li -->
                    </ol>

                    <?php
                    } else {
                    ?>
                    <img src="graphics/telephone_telepathy.jpg"
                         alt="telephone telepathy"
                         width="425"
                         height="344" />
                    <?php
                    } // end if
                    ?>
                    <br>
                    <p><a href="instructions.pdf" target="_blank">Click here</a> to read the detailed instructions.
                    <P><a href="about.php">Click here</a> to see the instructional video.</p>
                </div>
                <div class="right_box">
                    <?php
                    if(isset($_SESSION['auth'])) {
                    ?>
                    <h3>My Details</h3>
                    <table>
                        <tr>
                        <tr>
                            <td>
                            <?php
                            field_label("username", "Username");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("username",
                                             true,
                                             $_SESSION['user']->get_username(),
                                             12);
                            ?>
                            </td>
                        </tr>
                        <td>
                            <?php
                            field_label("first_name", "First Name");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("first_name",
                                             strcmp($_SESSION['edit_profile'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['user']->get_first_name(),
                                             20,
                                             20,
                                             "Characters A-Z, a-z and space only");
                            ?>
                            </td>
                        </tr>
 <!--                       <tr>
                            <td>
                            <?php
                            field_label("last_name", "Last Name");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("last_name",
                                             strcmp($_SESSION['edit_profile'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['user']->get_last_name(),
                                             20,
                                             20,
                                             "Characters A-Z, a-z and space only");
                            ?>
                            </td>
                        <tr>
                            <td>
                            <?php
                            field_label("age", "Age");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("age",
                                             strcmp($_SESSION['edit_profile'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['user']->get_age(),
                                             3,
                                             3,
                                             "Between ".AGE_MIN." and ".AGE_MAX." years");
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <?php
                            field_label("group_name", "Experiment Group Name<br>(if you're part of a group)");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("group_name",
                                             strcmp($_SESSION['edit_profile'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['user']->get_group_name(),
                                             20,
                                             20,
                                             "Characters A-Z, a-z and space only");
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
                                               strcmp($_SESSION['edit_profile'], MODE_READONLY) == 0 ? true : false,
                                               array("Male", "Female"),
                                               1,
                                               $_SESSION['user']->get_gender());
                            ?>
                            </td>
                        </tr> -->
                        <tr>
                            <td>
                            <?php
                            field_label("phone", "Phone");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("phone",
                                             strcmp($_SESSION['edit_profile'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['user']->get_phone(),
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
                                             strcmp($_SESSION['edit_profile'], MODE_READONLY) == 0 ? true : false,
                                             $_SESSION['user']->get_email(),
                                             20,
                                             50);
                            ?>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <?php
                        input_field_submit("enter", "x", "invisible");

                        if(strcmp($_SESSION['edit_profile'], MODE_READONLY) == 0) {
                            input_field_submit("change", "Change", "button");
                            input_field_submit("delete", "Delete", "button");
                        } else {
                            input_field_submit("display", "Discard Changes", "button");
                            input_field_submit("update", "Save", "button");
                        } // end if
						echo "<br><br>&nbsp;&nbsp;&nbsp;";
                        input_field_message("profile_result", $_SESSION['profile_result'], 100);
                    ?>
                    <?php
                    } else {
                    ?>
                    <h3>The Telephone Telepathy Experiment</h3>
                    <p class="info">
                        This test is currently only available to U.S. residents.
                        To become an experimenter, please
                        <a href="register.php">register</a> and
                        <a href="login.php">login</a>. If you have
                        additional questions, please
                        <a href="contact.php">contact us</a>.<br><br>
                        Click <a href="about.php">here</a> to see an instructional video. 
                    </p>
                    <?php
                    } // end if
                    ?>
                </div>
                <div class="footer">
                </div>
            </div>
        </form>
    </body>
</html>
