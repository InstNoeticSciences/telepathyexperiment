<?php
// get relevant variables
//$extension = $argv[1];
include_once("../twilio-twilio-php-f676f16/Services/Twilio.php");
include_once("ions_feedback.php");
include_once("ions_config.php");
include_once("ions_util.php");
$phone  = "18584057952";
$client = new Services_Twilio(SID, TOKEN);
$commonUrlInformedConsent = "http://54.186.177.103/telepathyexperiment/ions/ions_agree_experiment.php?participant=Arnaud&phone=";
//$call = $client->account->calls->create(PHONE, $phone, $commonUrlInformedConsent."18584057952", array("IfMachine" => "hangup"));
$call = $client->account->calls->create(PHONE, $phone, $commonUrlInformedConsent."18584057952");
$sid  = $call->sid;
add_currently_called_phone($phone,$sid);
print "Call SID is $sid\r\n";
?>
