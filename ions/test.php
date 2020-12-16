<?php

// collect parameters
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
include("ions_util.php");
//include("check_allconnected.php");

session_start();

	$r2 = new Response();
    //$r2->append(new Say("Some of the participant were not available. We will try again later. Goodbye.", $voice));
    $r2->append(new Play("../mp3/Participant_not_available.mp3"));
	$r2->append(new Hangup());
	$r2->Respond();

?>
