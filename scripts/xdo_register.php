<?php
include("../inc/config.php");
include("../lib/util.php");
include("../classes/database.php");
include("../classes/password.php");
include("../classes/user.php");

require_once("../lib/recaptchalib.php");

session_start();

$error = false;

// captcha validation
$resp = recaptcha_check_answer(captcha_prv,
                               $_SERVER["REMOTE_ADDR"],
                               $_POST["recaptcha_challenge_field"],
                               $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
    $_SESSION['register_result'] = "The reCAPTCHA wasn't entered correctly.";
    $error = true;
} // end if

$_SESSION['first_name'] = $_POST['first_name'];
$_SESSION['last_name'] = $_POST['last_name'];
$_SESSION['username'] = $_POST['username'];
$_SESSION['gender'] = $_POST['gender'];
$_SESSION['phone'] = $_POST['phone'];
$_SESSION['email'] = $_POST['email'];
$_SESSION['age'] = $_POST['age'];

if(!$error) {
    // user name validation
    switch(validate_username($_POST['username'])) {
        case BAD_LENGTH:
            $_SESSION['register_result'] = "Username must be between ".
                                            UNAME_MIN_LENGTH." "."and ".
                                            UNAME_MAX_LENGTH." characters";
            $error = true;
            break;
        case NOT_UNIQUE:
            $_SESSION['register_result'] = "Username ".$_POST['username']." ".
                                           "is already in use";
            $error = true;
            break;
        case BAD_CHARS:
            $_SESSION['register_result'] = "Username must only contain numbers ".
                                           "and letters";
            $error = true;
            break;
        default:
            $_SESSION['register_result'] = '';
            break;
    } // end switch
} // end if

if(!$error) {
    // password validation
    switch(validate_password($_POST['password'], $_POST['rep_password'])) {
        case BAD_LENGTH:
            $_SESSION['register_result'] = "Password must be ".PWD_LENGTH." ".
                                           "characters long";
            $error = true;
            break;
        case BAD_MATCH:
            $_SESSION['register_result'] = "The passwords you entered do not match";
            $error = true;
            break;
        default:
            $_SESSION['register_result'] = '';
            break;
    } // end switch
} // end if

if(!$error) {
    // cell phone validation
    switch(validate_phone($_POST['phone'])) {
        case BAD_LENGTH:
            $_SESSION['register_result'] = "You have not entered a phone number";
            $error = true;
            break;
        case BAD_CHARS:
            $_SESSION['register_result'] = "Phone number must only contain numbers";
            $error = true;
            break;
        case NOT_UNIQUE:
            $_SESSION['register_result'] = "A user is already registered ".
                                           "with number ".$_POST['phone'];
            $error = true;
            break;
        default:
            $_SESSION['register_result'] = '';
            break;
    } // end switch
} // end if

if(!$error) {
    // email validation
    switch(validate_email($_POST['email'])) {
        case BAD_LENGTH:
            $_SESSION['register_result'] = "You have not entered an email address";
            $error = true;
            break;
        case BAD_CHARS:
            $_SESSION['register_result'] = "Invalid email address";
            $error = true;
            break;
        case NOT_UNIQUE:
            $_SESSION['register_result'] = "User is already registered with ".
                                           "address ".$_POST['email'];
            $error = true;
            break;
        default:
            $_SESSION['register_result'] = '';
            break;
    } // end switch
} // end if

if(!$error) {
    // first name validation
    switch(validate_name($_POST['first_name'])) {
        case BAD_LENGTH:
            $_SESSION['register_result'] = "You have not entered a first name";
            $error = true;
            break;
        case BAD_CHARS:
            $_SESSION['register_result'] = "First name must only contain letters";
            $error = true;
            break;
        default:
            $_SESSION['register_result'] = '';
            break;
    } // end switch
} // end if

if(!$error) {
    // last name validation
    switch(validate_name($_POST['last_name'])) {
        case BAD_LENGTH:
            $_SESSION['register_result'] = "You have not entered a last name";
            $error = true;
            break;
        case BAD_CHARS:
            $_SESSION['register_result'] = "Last name must only contain letters";
            $error = true;
            break;
        default:
            $_SESSION['register_result'] = '';
            break;
    } // end switch
} // end if

if(!$error) {
    // age validation
    switch(validate_number($_POST['age'], AGE_MIN, AGE_MAX)) {
        case BAD_LENGTH:
            $_SESSION['register_result'] = "You have not entered your age";
            $error = true;
            break;
        case BAD_CHARS:
            $_SESSION['register_result'] = "Age must be numeric";
            $error = true;
            break;
        case BAD_VALUE:
            $_SESSION['register_result'] = "Age must be between ".AGE_MIN." ".
                                           "and ".AGE_MAX;
            $error = true;
            break;
        default:
            $_SESSION['register_result'] = '';
            break;
    } // end switch
} // end if

$pin = 0;

if(!$error) {
    // pin number generation
    $pin = generate_pin(PIN_MIN, PIN_MAX);

    if(!$pin > 0) {
        $_SESSION['register_result'] = "Pin generation failed";
        $error = true;
    } else {
        $_SESSION['register_result'] = '';
    } // end if
} // end if

if(!$error) {
    // register the user
    if($_POST['register'] && !$error) {
        $user = new user($_POST['first_name'],
                         $_POST['last_name'],
                         $_POST['username'],
                         $_POST['password'],
                         $_POST['gender'],
                         $_POST['phone'],
                         $_POST['email'],
                         '',
                         $_POST['age'],
                         $pin);

        if(!$user->db_update(INSERT)) {
            echo "insert failed";
            $_SESSION['register_result'] = "Your user could not be created due".
                                           "to a system error";
            $error = true;
        } // end if
    } // end if
} // end if

if(!$error) {
    // redirect to the login page
    $_SESSION['register_result'] = '';
    $_SESSION['first_name'] = '';
    $_SESSION['last_name'] = '';
    $_SESSION['gender'] = '';
    $_SESSION['phone'] = '';
    $_SESSION['email'] = '';
    $_SESSION['dob'] = '';
    
    header("Location: ../login.php");
} else {
    // return to the registration form
    header("Location: ../register.php");
} // end if
?>
