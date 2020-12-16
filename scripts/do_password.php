<?php
include("../inc/config.php");
include("../lib/util.php");
include("../classes/database.php");
include("../classes/password.php");
include("../classes/user.php");

session_start();

$error = false;

$_SESSION['username'] = $_POST['username'];

// user name validation
switch(validate_username($_POST['username'])) {
    case BAD_LENGTH:
        $_SESSION['password_result'] = "Username must be between ".
                                        UNAME_MIN_LENGTH." "."and ".
                                        UNAME_MAX_LENGTH." characters";
        $error = true;
        break;
    case BAD_CHARS:
        $_SESSION['password_result'] = "Username must only contain numbers ".
                                       "and letters";
        $error = true;
        break;
    case OK:
        $_SESSION['password_result'] = "User ".$_POST['username']." ".
                                       "does not exist";
        $error = true;
        break;
    default:
        $_SESSION['password_result'] = '';
        break;
} // end switch

if(!$error) {
    // match the username to the verification code
    switch(match_user_code($_POST['username'], $_POST['code'])) {
        case NO_MATCH:
            $_SESSION['password_result'] = "The verification code you have ".
                                           "entered is invalid";
            $error = true;
            break;
        default:
            $_SESSION['password_result'] = '';
            break;
    } // end switch
} // end if

if(!$error) {
    // password validation
    switch(validate_password($_POST['password'], $_POST['rep_password'])) {
        case BAD_MATCH:
            $_SESSION['password_result'] = "The passwords you entered do not match";
            $error = true;
            break;
        default:
            $_SESSION['password_result'] = '';
            break;
    } // end switch
} // end if

if(!$error) {
    // perform the reset
    if($_POST['reset'] || $_POST['enter']) {
        $db = new database();
        $db->dblink();

        $result = $db->get_recs("users", "*", "username='{$_POST['username']}'");
        $recs = $db->fetch_objects($result);

        $user = new user($recs[0]->first_name,
                         $recs[0]->last_name,
                         $recs[0]->username,
                         $_POST['password'],
                         $recs[0]->gender,
                         $recs[0]->phone,
                         $recs[0]->email,
                         $recs[0]->admin,
                         $recs[0]->age,
                         $recs[0]->pin,
                         $recs[0]->group_name);

        if($user->db_update(UPDATE)) {
            user::unlock($user->get_username());
            user::remove_validation($user->get_username());

            $_SESSION['password_result'] = "Your password has been reset";
        } else {
            $_SESSION['password_result'] = "Your password could not be ".
                                           "reset (system error)";
            $error = true;
        } // end if
    } // end if
} // end if

// redirect back to the password reset form
header("Location: ../password.php");
?>
