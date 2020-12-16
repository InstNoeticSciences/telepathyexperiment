<?php

// testing http://psiresearch.com/telepathyexperiment/ions/ions_agree_experiment.php?participant=Arnaud&phone=18584057952

// collect parameters
include("../inc/config.php");
include("../lib/twilio.php");
include("../lib/util.php");

session_start();
$phone       = $_SESSION['phone'];
$participant = $_SESSION['participant'];
if (empty($phone)) {
	$mode        = $_REQUEST['mode'];
	$phone       = reformat_phone_number($_REQUEST['phone']);
	$participant = $_REQUEST['participant'];
	$_SESSION['phone']       = $phone;
	$_SESSION['participant'] = $participant;
}
	
$voice = array("voice" => "woman", "language" => "en", "loop" => "1");

// prompt for a guess
$r3 = new Response();
$g = $r3->append(new Gather(array("numDigits" => "1",
                                 "timeout" => "10",
                                 "action" => "ions_agree_process_answer.php",
                                 "method" => "POST")));

if ($mode == 2) {
	$g->append(new Play("../mp3/Informed_consent_askselfbeg.mp3"));
	$g->append(new Say($participant, $voice));
	$g->append(new Play("../mp3/Informed_consent_askselfend.mp3"));
}
elseif ($mode == 1) {
	$g->append(new Play("../mp3/Informed_consent_askbeg.mp3"));
	$g->append(new Say($participant, $voice));
	$g->append(new Play("../mp3/Informed_consent_askend1.mp3"));
}
else {
	$g->append(new Play("../mp3/Informed_consent_askbeg.mp3"));
	$g->append(new Say($participant, $voice));
	$g->append(new Play("../mp3/Informed_consent_askend2.mp3"));
}

$g->append(new Say($text, $voice));
/*$g->append(new Play("tell_me_whos_calling_enter_1_for.mp3"));
$g->append(new Say("Arnaud", $voice));
$g->append(new Play("or_2_for.mp3"));
$g->append(new Say("Dean", $voice)); */

// handle a timeout on the guess
//$r3->append(new Say("You did not answer in time. We are now ending the call.", $voice));
$r3->append(new Play("../mp3/Time_out.mp3"));
$r3->append(new Hangup());
$r3->Respond();

?>
