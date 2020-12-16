<?php
include_once("ions_config.php");
include_once("../twilio-twilio-php-f676f16/Services/Twilio.php");

function send_feedback($username, $phone, $score) {
	// send SMS to people
	$client = new Services_Twilio(SID, TOKEN);
	$message = "Dear ".$username.", the telepathy experiment is now over. ".
               "As a team, you scored ".$score."% (chance 33%). ".
               "Thank you for participating.";
	$sms = $client->account->sms_messages->create(PHONETEXT, $phone, $message, array());
}

function send_canceluser($username, $phone) {
	// send SMS to people
	$client = new Services_Twilio(SID, TOKEN);
	$message = "Dear $username, a user did not consent to doing the telepathy experiment. Your experiment is now canceled. ".
               "Try again when all the participants are ready.";
	$sms = $client->account->sms_messages->create(PHONETEXT, $phone, $message, array());
}

function send_cancelparticipant($username, $phone) {
	// send SMS to people
	$client = new Services_Twilio(SID, TOKEN);
	$message = "Dear $username, a user failed to consent to doing the telepathy experiment. This experiment is now canceled.";
	$sms = $client->account->sms_messages->create(PHONETEXT, $phone, $message, array());
}

?>