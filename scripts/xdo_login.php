<?php
include("../inc/config.php");
include("../lib/util.php");
include("../classes/access_logger.php");
include("../classes/database.php");
include("../classes/password.php");
include("../classes/user.php");

session_start();

$error = false;
$redirect = null;

// attempt to log the user in
if($_POST['login'] || $_POST['enter']) {
    $_SESSION['username'] = $_POST['username'];

    // do not proceed if the user is locked
    if(user::is_locked($_POST['username'])) {
        $_SESSION['login_result'] = "Your account has been locked";
        $error = true;
    } // end if

    if(!$error) {
        $logger = new access_logger($_SERVER['REMOTE_ADDR'], MAX_LOGIN_ATTEMPTS);
        $logger->create_entry();

        if(validate_credentials($_POST['username'], $_POST['password'])) {
            // login details are correct
            $logger->delete_entry();

            // create a user object
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
                             $recs[0]->pin);

            // authenticate
            $_SESSION['user'] = $user;
            $_SESSION['auth'] = random_string(20, SOURCE_CHARS);
            $_SESSION['login_result'] = '';
        } else {
            if($logger->increment_attempts()) {
                // allow further attempts
                $_SESSION['login_result'] = "Invalid login details";
            } else {
                // lock the user and remove the log entry
                user::lock($_POST['username']);
                $logger->delete_entry();

                $_SESSION['login_result'] = "Account locked (maximum attempts)";
            } // end if

            $error = true;
        } // end if
    } // end if
} // end if

if(!$error) {
    // redirect to the main page
    $_SESSION['login_result'] = '';
    $_SESSION['edit_profile'] = MODE_READONLY;
    $_SESSION['edit_friends'] = MODE_READONLY;
    $_SESSION['edit_config'] = MODE_READONLY;
    
    $redirect = "Location: ../index.php";
} else {
    // return to the login form
    $redirect = "Location: ../login.php";
} // end if

header($redirect);
?>
