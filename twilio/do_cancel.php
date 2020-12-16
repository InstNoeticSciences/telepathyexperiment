<?php
include("../inc/config.php");
include("../lib/util.php");
include("../lib/twilio.php");
include("../lib/twilio_util.php");
include("../classes/twilio_sms.php");
include("../classes/experiment.php");
include("../classes/database.php");
include("../classes/password.php");
include("../classes/trial.php");
include("../classes/user.php");

session_start();

$voice = array("voice" => "woman", "language" => "en", "loop" => "1");
$user = $_SESSION['user'];
$sid = $_POST['CallSid'];

$r = new Response();

// get the current experiment for the user
$e = $user->get_current_experiment();

if($e != false) {
    // cancel the experiment
    if($e->cancel()) {
		$r->append(new Play("../mp3/Cancel_ok.mp3"));
    } else {
		$r->append(new Play("../mp3/Cancel_problem.mp3"));
    } // end if
} else {
    // there is nothing to cancel
	$r->append(new Play("../mp3/Cancel_no_experiment.mp3"));
} // end if

// end the call
$r->append(new Redirect("do_exit.php"));

$r->Respond();
?>
