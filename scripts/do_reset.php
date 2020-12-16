<?php
include("../inc/config.php");
include("../classes/database.php");
include("../classes/mailer.php");
include("../classes/user.php");
include("../lib/util.php");

session_start();

$error = false;

$_SESSION['username'] = $_POST['username'];
$_SESSION['email'] = $_POST['email'];

// email validation
switch(validate_email($_POST['email'])) {
    case BAD_LENGTH:
        $_SESSION['reset_result'] = "You have not entered an email address";
        $error = true;
        break;
    case BAD_CHARS:
        $_SESSION['reset_result'] = "You have not entered a valid email ".
                                    "address";
        $error = true;
        break;
    default:
        break;
} // end switch

// user name validation
if(!$error) {
    switch(validate_username($_POST['username'])) {
        case BAD_LENGTH:
            $_SESSION['reset_result'] = "Your user name must be between ".
                                         UNAME_MIN_LENGTH." and ".
                                         UNAME_MAX_LENGTH." characters";
            $error = true;
            break;
        case BAD_CHARS:
            $_SESSION['reset_result'] = "Username must only contain numbers and ".
                                        "letters";
            $error = true;
            break;
        default:
            $_SESSION['reset_result'] = '';
            break;
    } // end switch
} // end if

if(!$error) {
    // match username to email address
    switch(match_email_user($_POST['username'], $_POST['email'])) {
        case NO_MATCH:
            $_SESSION['reset_result'] = "Username ".$_SESSION['username']." does ".
                                        "not match email address ".
                                         $_SESSION['email'];
            $error = true;
            break;
        case OK:
            if($_POST['reset']) {
                // lock the user and add a validation code
                $code = random_string(RESET_STRING_LENGTH, SOURCE_CHARS);
                
                user::lock($_POST['username']);
                user::add_validation($_POST['username'], $code);

                $message = "<p>Hi ".$_POST['username'].",<br><br>".
                           "Your account has been temporarily locked. Your ".
                           "password reset code is <b>".$code.".</b> To reset ".
                           "your password and unlock your account, please go to ".
                           "<a href=http://54.186.177.103/telepathyexperiment/password.php>
                            http://psireserach.com/telepathyexperiment/password.php</a> ".
                           "and enter this code.<br><br>Regards,<br><br>".
                           "Telephone Telepathy Admin.</p>";
                
                $mailer = new mailer($_POST['email'],
                                     SYSTEM_ADMIN,
                                     "Telepathy Experiment Reset Password",
                                     $message);

                if($mailer->send()) {
                    $_SESSION['reset_result'] = "Reset instructions have been sent to ".
                                                 $_POST['email'];
                } else {
                    $_SESSION['reset_result'] = "Reset instructions could not be sent ".
                                                "due to a system error.";
                } // end if
            } // end if
            break;
        default:
            break;
    } // end switch
} // end if

// return to the reset form
header("Location: ../reset.php");
?>
