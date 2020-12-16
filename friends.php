<?php
include("inc/config.php");
include("lib/util.php");
include("classes/database.php");
include("classes/user.php");

session_start();

authenticate($_SESSION['auth'], "index.php");

if(!isset($_SESSION['friends'])) {
    $friends = $_SESSION['user']->get_friends();
} else {
    $friends = $_SESSION['friends'];
} // end if

if(!is_array($friends)) {
    // initialise the array
    for($i = 0; $i < MAX_FRIENDS; $i++) {
        $friends[$i]->friend_name = '[Click here then enter name]';
        $friends[$i]->phone = '[...and cell phone]';
        $friends[$i]->message = '';
    } // end for
} // end if

// empty the message variables from other pages
$_SESSION['password_result'] = '';
$_SESSION['register_result'] = '';
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
        <title>My Friends</title>
        <link rel="shortcut icon"
              href="graphics/telephone_telepathy_icon.jpg"
              type="image/x-icon" />
        <script type="text/javascript" src="js/util.js"></script>
    </head>
    <body>
        <form name="friends" method="post" action="scripts/do_friends.php">
            <div class="wrapper">
                <div class="banner">
                    <?php
                    banner();
                    ?>
                </div>
                <div class="header_left">
                    <?php
                    main_menu($_SESSION['auth'], FRIENDS);
                    ?>
                </div>
                <div class="header_right">
                    <?php
                    admin_menu($_SESSION['auth'], isset($_SESSION['user']) ?
                                                  $_SESSION['user']->is_admin() :
                                                  false);
                    ?>
                </div>
                <div class="big_box">
                    <table>
                        <tr>
                            <td><h3>First name</h3></td>
                            <td><h3>Phone</h3></td>
                        </tr>
                        <?php
                        for($i = 0; $i < MAX_FRIENDS; $i++) {
                        ?>
                        <tr>
                            <td>
                            <?php
                            input_field_text("name_$i", 
                                             $_SESSION['edit_friends'] == MODE_READONLY ? true : false,
                                             $friends[$i]->friend_name,
                                             30,
                                             30,
                                             "Characters A-Z, a-z and space only");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_text("phone_$i",
                                             $_SESSION['edit_friends'] == MODE_READONLY ? true : false,
                                             $friends[$i]->phone,
                                             20,
                                             20,
                                             "Format: 999 999 9999 or 999-999-9999");
                            ?>
                            </td>
                            <td>
                            <?php
                            input_field_message("message_$i", $friends[$i]->message, 50);
                            ?>
                            </td>
                        </tr>
                        <?php
                        } // end for
                        ?>
                    </table>
                </div>
                <div class="big_box">
                    <p class="info">
                    <?php
                    if($_SESSION['edit_friends'] == MODE_READONLY) {
                    ?>
                        Click the <b>Home</b> tab to go back to the experiment page.
                    <?php
                    } else {
                    ?>
                        Click the <b>Save</b> button to keep your changes or click
                        the <b>Discard Changes</b> button to ignore them.
                    <?php
                    } // end if
                    ?>
                    </p>
                </div>
                <div class="footer">
                    <?php
                    input_field_submit("enter", "x", "invisible");
                    
                    if($_SESSION['edit_friends'] == MODE_READONLY) {
                        input_field_submit("change", "Change", "button");
                    } else {
                        input_field_submit("display", "Discard Changes", "button");
                        input_field_submit("update", "Save", "button");
                    } // end if

                    input_field_message("update_result", $_SESSION['update_result'], 100);
                    ?>
                </div>
            </div>
        </form>
    </body>
</html>