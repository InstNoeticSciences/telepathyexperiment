<?php
include("../inc/config.php");
include("../classes/database.php");
include("../classes/mailer.php");
include("../classes/user.php");
include("../lib/util.php");

session_start();

$error = false;

$_SESSION['first_name'] = $_POST['first_name'];
$_SESSION['last_name'] = $_POST['last_name'];
$_SESSION['msg_text'] = $_POST['msg_text'];
$_SESSION['email'] = $_POST['email'];

// first name validation
switch(validate_name($_POST['first_name'])) {
    case BAD_LENGTH:
        $_SESSION['contact_result'] = "You have not entered a first name";
        $error = true;
        break;
    case BAD_CHARS:
        $_SESSION['contact_result'] = "First name must only contain letters";
        $error = true;
        break;
    default:
        break;
} // end switch

// last name validation
switch(validate_name($_POST['last_name'])) {
    case BAD_LENGTH:
        $_SESSION['contact_result'] = "You have not entered a last name";
        $error = true;
        break;
    case BAD_CHARS:
        $_SESSION['contact_result'] = "Last name must only contain letters";
        $error = true;
        break;
    default:
        break;
} // end switch
//
// email validation
switch(validate_email($_POST['email'])) {
    case BAD_LENGTH:
        $_SESSION['contact_result'] = "You have not entered an email address";
        $error = true;
        break;
    case BAD_CHARS:
        $_SESSION['contact_result'] = "Invalid email address";
        $error = true;
        break;
    default:
        break;
} // end switch

// send the message
if($_POST['send'] && !$error) {
    $mailer = new mailer(SYSTEM_ADMIN,
                         $_SESSION['email'],
                         "Telepathy Experiment Query",
                         $_SESSION['msg_text']);

    if($mailer->send()) {
        $_SESSION['contact_result'] = "Message sent - thank you";
        $_SESSION['message_result'] = "[Your message here]";
    } else {
        $_SESSION['contact_result'] = "Message not sent due to a system error";
    } // end if
} // end if

// return to the contact form
header("Location: ../contact.php");
?>
