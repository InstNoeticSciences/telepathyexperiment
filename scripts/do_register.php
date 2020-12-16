<?php
include("../inc/config.php");
include("../lib/util.php");
//include("../ions/ions_call_utils.php");
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
							   
$_SESSION['register_result'] = "";
if (!$resp->is_valid) {
    $_SESSION['register_result'] = "<br><br>The reCAPTCHA wasn't entered correctly";
    $error = true;
} // end if

$_SESSION['first_name'] = $_POST['first_name'];
$_SESSION['last_name'] = $_POST['last_name'];
$_SESSION['username'] = $_POST['username'];
$_SESSION['gender'] = $_POST['gender'];
$_SESSION['phone'] = $_POST['phone'];
$_SESSION['email'] = $_POST['email'];
$_SESSION['age'] = $_POST['age'];
$_SESSION['group_name'] = $_POST['group_name'];

    // user name validation
    switch(validate_username($_POST['username'])) {
        case BAD_LENGTH:
            $_SESSION['register_result'] = $_SESSION['register_result']."<br><br>Username must be between ".
                                            UNAME_MIN_LENGTH." "."and ".
                                            UNAME_MAX_LENGTH." characters";
            $error = true;
            break;
        case NOT_UNIQUE:
            $_SESSION['register_result'] = $_SESSION['register_result']."<br><br>Username ".$_POST['username']." ".
                                           "is already in use";
            $error = true;
            break;
        case BAD_CHARS:
            $_SESSION['register_result'] = $_SESSION['register_result']."<br><br>Username must only contain numbers ".
                                           "and letters";
            $error = true;
            break;
    } // end switch

    // password validation
	if (empty($_POST['rep_password']))
		$_SESSION['register_result'] = $_SESSION['register_result']."<br><br>Password cannot be empty";
    switch(validate_password($_POST['password'], $_POST['rep_password'])) {
 //       case BAD_LENGTH:
 //           $_SESSION['register_result'] = "Password must be ".PWD_LENGTH." ".
 //                                          "characters long";
 //           $error = true;
 //           break;
        case BAD_MATCH:
            $_SESSION['register_result'] = $_SESSION['register_result']."<br><br>The passwords you entered do not match";
            $error = true;
            break;
    } // end switch

    // cell phone validation
    switch(validate_phone($_POST['phone'])) {
        case BAD_LENGTH:
            $_SESSION['register_result'] = $_SESSION['register_result']."<br><br>You have not entered a phone number";
            $error = true;
            break;
        case BAD_CHARS:
            $_SESSION['register_result'] = $_SESSION['register_result']."<br><br>Phone number must only contain numbers";
            $error = true;
            break;
        case NOT_UNIQUE:
            $_SESSION['register_result'] = $_SESSION['register_result']."<br><br>A user is already registered ".
                                           "with number ".$_POST['phone'];
            $error = true;
            break;
    } // end switch

if(!empty($_POST['email'])) {
    // email validation
    switch(validate_email($_POST['email'])) {
        case BAD_LENGTH:
            $_SESSION['register_result'] = $_SESSION['register_result']."<br><br>You have not entered an email address";
            $error = true;
            break;
        case BAD_CHARS:
            $_SESSION['register_result'] = $_SESSION['register_result']."<br><br>Invalid email address";
            $error = true;
            break;
        case NOT_UNIQUE:
            $_SESSION['register_result'] = $_SESSION['register_result']."<br><br>User is already registered with ".
                                           "address ".$_POST['email'];
            $error = true;
            break;
    } // end switch
} // end if

    // first name validation
    switch(validate_name($_POST['first_name'])) {
        case BAD_LENGTH:
            $_SESSION['register_result'] = $_SESSION['register_result']."<br><br>You have not entered a first name";
            $error = true;
            break;
        case BAD_CHARS:
            $_SESSION['register_result'] = $_SESSION['register_result']."<br><br>First name must only contain letters";
            $error = true;
            break;
    } // end switch

/*if(!$error) {
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
    // group name validation
    switch(validate_name($_POST['group_name'])) {
        case BAD_LENGTH:
            $_SESSION['register_result'] = "You have not entered a group name";
            $error = true;
            break;
        case BAD_CHARS:
            $_SESSION['register_result'] = "Group names must only contain letters";
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
} // end if */

$pin = 0;

if(!$error) {
    // pin number generation
    $pin = generate_pin(PIN_MIN, PIN_MAX);

    if(!$pin > 0) {
        $_SESSION['register_result'] = "Pin generation failed. Contact us.";
        $error = true;
     } // end if
} // end if

if(!$error) {
    // register the user	
    //                     reformat_phone_number($_POST['phone']),
    if($_POST['register'] && !$error) {
        $user = new user($_POST['first_name'],
                         $_POST['last_name'],
                         $_POST['username'],
                         $_POST['password'],
                         $_POST['gender'],
                         reformat_phone_number($_POST['phone']),
                         $_POST['email'],
                         '',
                         $_POST['age'],
                         $pin,
						 $_POST['group_name']);

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
	$_SESSION['group_name'] = '';
    
    header("Location: ../login.php");
} else {
    // return to the registration form
    header("Location: ../register.php");
} // end if
?>
