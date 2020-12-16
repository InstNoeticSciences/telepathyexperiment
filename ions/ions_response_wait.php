<?php
include("../twilio-twilio-php-f676f16/Services/Twilio.php");
include("../inc/config.php");
include("../lib/util.php");
include("../lib/twilio.php");
include("../lib/twilio_util.php");
include("../classes/twilio_sms.php");
include("../classes/experiment.php");
include("../classes/sms_queue.php");
include("../classes/database.php");
include("../classes/password.php");
include("../classes/mailer.php");
include("../classes/trial.php");
include("../classes/user.php");

session_start();

// get parameters
$trial_id    = $_SESSION['trial_id'];
$participant = $_SESSION['participant'];
$guess       = $_REQUEST['Digits']; // 1 or 2
$_SESSION['guess'] = $guess;

/*$parameters = $_REQUEST['parameters'];
$req_params = explode("_", $parameters);
$participant = $req_params[0];
$trial_id    = $req_params[1];*/

$r = new Response();
$voice = array("voice" => "woman", "language" => "en", "loop" => "1");

if($guess > 0 && $guess <= MAX_FRIENDS+1) {
	// ask participant to wait
	$r->append(new Play("../mp3/Please_wait.mp3"));
	$r->append(new Redirect('ions_collect_responses.php'));
} else {
    // invalid guess entered: try again
	$r->append(new Play("../mp3/Not_valid_option.mp3"));
    $r->append(new Redirect('ions_ask_question.php'));
} // end if

$r->Respond();

?>