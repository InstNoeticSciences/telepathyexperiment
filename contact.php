<?php
include("inc/config.php");
include("lib/util.php");
include("classes/database.php");
include("classes/user.php");

session_start();

if(strlen($_SESSION['msg_text']) == 0) {
    $_SESSION['msg_text'] = '[your message here]';
} // end if

if(isset($_SESSION['auth'])) {
    $first_name = $_SESSION['user']->get_first_name();
    $last_name = $_SESSION['user']->get_last_name();
    $email = $_SESSION['user']->get_email();
} else {
    $first_name = '';
    $last_name = '';
    $email = '';
} // end if

// empty the message variables from other pages
$_SESSION['password_result'] = '';
$_SESSION['register_result'] = '';
$_SESSION['update_result'] = '';
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
        <title>Contact Us</title>
        <link rel="shortcut icon"
              href="graphics/telephone_telepathy_icon.jpg"
              type="image/x-icon" />
        <script type="text/javascript" src="js/util.js"></script>
    </head>
    <body>
        <form name="contact" method="post" action="scripts/do_contact.php">
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
                    <h3>Enter your name and email address</h3>
                    <table>
                        <tr>
                            <td>
                            <?php
                            field_label("first_name", "First Name");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("first_name", false, $first_name, 20);
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
                            input_field_text("last_name", false, $last_name, 20);
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
                            input_field_text("email", false, $email, 35, 50);
                            ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="right_box">
                    <h3>Enter your message:</h3>
                    <textarea class="textarea"
                              id="msg_text"
                              name="msg_text"
                              rows="10"
                              cols="50"
                              onClick="highlight(this);"><?php echo $_SESSION['msg_text'] ?></textarea><br><br>
                    <?php input_field_submit("send", "Send", "button"); ?>
                </div>
                <div class="footer">
                    <?php
                    input_field_submit("enter", "x", "invisible");
                    input_field_message("contact_result", $_SESSION['contact_result'], 100);
                    ?>
                </div>
            </div>
        </form>
    </body>
</html>