<?php
include("../inc/config.php");
include("../lib/util.php");
include("../classes/database.php");
include("../classes/password.php");
include("../classes/user.php");

session_start();

$error = false;
$redirect = null;

// switch to change mode
if($_POST['change']) {
    $_SESSION['edit_profile'] = '';
    $_SESSION['profile_result'] = '';
    $redirect = "Location: ../index.php";
} // end if

// discard changes and switch to display mode
if($_POST['display']) {
    $_SESSION['edit_profile'] = MODE_READONLY;
    $_SESSION['profile_result'] = '';

    // reset the user data
    $_SESSION['user']->reset();
    $redirect = "Location: ../index.php";
} // end if

// delete the user and all friends
if($_POST['delete']) {
    $redirect = "Location: ../confirm.php";
} // end if

if($_SESSION['confirm_delete'] == true) {
    // delete friends
    if($_SESSION['user']->has_friends()) {
        $_SESSION['user']->delete_friends();
    } // end if

    // delete user and log out
    $_SESSION['user']->db_update(DELETE);
    $_SESSION['confirm_delete'] = false;
    
    $redirect = "Location: ../logout.php";
} // end if

// validate and update the data
if($_POST['update'] || $_POST['enter']) {
    $_SESSION['user']->set_first_name($_POST['first_name']);
    $_SESSION['user']->set_last_name($_POST['last_name']);
    $_SESSION['user']->set_phone($_POST['phone']);
    $_SESSION['user']->set_email($_POST['email']);
    $_SESSION['user']->set_age($_POST['age']);
    $_SESSION['user']->set_gender($_POST['gender']);
    
    // first name validation
    switch(validate_name($_POST['first_name'])) {
        case BAD_LENGTH:
            $_SESSION['profile_result'] = "You have not entered a first name";
            $error = true;
            break;
        case BAD_CHARS:
            $_SESSION['profile_result'] = "First name must only contain letters";
            $error = true;
            break;
        default:
            $_SESSION['profile_result'] = '';
            break;
    } // end switch

    if(!$error) {
        // last name validation
        switch(validate_name($_POST['last_name'])) {
            case BAD_LENGTH:
                $_SESSION['profile_result'] = "You have not entered a last name";
                $error = true;
                break;
            case BAD_CHARS:
                $_SESSION['profile_result'] = "Last name must only contain letters";
                $error = true;
                break;
            default:
                $_SESSION['profile_result'] = '';
                break;
        } // end switch
    } // end if

    if(!$error) {
        // cell phone validation
        switch(validate_phone($_POST['phone'], $_POST['username'])) {
            case BAD_LENGTH:
                $_SESSION['profile_result'] = "You have not entered a phone number";
                $error = true;
                break;
            case BAD_CHARS:
                $_SESSION['profile_result'] = "Phone number must only contain numbers";
                $error = true;
                break;
            case NOT_UNIQUE:
                $_SESSION['profile_result'] = "A user is already registered ".
                                              "with number ".$_POST['phone'];
                $error = true;
                break;
            default:
                $_SESSION['profile_result'] = '';
                break;
        } // end switch
    } // end if

    if(!$error) {
        // email validation
        switch(validate_email($_POST['email'], $_POST['username'])) {
            case BAD_LENGTH:
                $_SESSION['profile_result'] = "You have not entered an email address";
                $error = true;
                break;
            case BAD_CHARS:
                $_SESSION['profile_result'] = "Invalid email address";
                $error = true;
                break;
            case NOT_UNIQUE:
                $_SESSION['profile_result'] = "User is already registered with ".
                                              "address ".$_POST['email'];
                $error = true;
                break;
            default:
                $_SESSION['profile_result'] = '';
                break;
        } // end switch
    } // end if
    
    if(!$error) {
        // age validation
        switch(validate_number($_POST['age'], AGE_MIN, AGE_MAX)) {
            case BAD_LENGTH:
                $_SESSION['profile_result'] = "You have not entered your age";
                $error = true;
                break;
            case BAD_CHARS:
                $_SESSION['profile_result'] = "Age must be numeric";
                $error = true;
                break;
            case BAD_VALUE:
                $_SESSION['profile_result'] = "Age must be between ".AGE_MIN." ".
                                              "and ".AGE_MAX;
                $error = true;
                break;
            default:
                $_SESSION['profile_result'] = '';
                break;
        } // end switch
    } // end if

    // do the update if requested
    if($_POST['update'] && !$error) {
        if($_SESSION['user']->db_update(UPDATE)) {
            $_SESSION['profile_result'] = 'Changes saved';
            $_SESSION['edit_profile'] = MODE_READONLY;
        } else {
            $_SESSION['profile_result'] = 'I could not save your changes';
            $error = true;
        } // end if
    } // end if

    $redirect = "Location: ../index.php";
} // end if

// return to the index page
header($redirect);
?>
