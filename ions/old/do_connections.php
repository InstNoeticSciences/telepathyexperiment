<?php
include("../inc/config.php");
include("../lib/util.php");
include("../lib/twilio.php");
include("../lib/twilio_util.php");
include("../classes/experiment.php");
include("../classes/database.php");
include("../classes/password.php");
include("../classes/trial.php");
include("../classes/user.php");
include("../twilio-twilio-php-f676f16/Services/Twilio.php");

$sid = "AC0add840bb3a10cfa05b1bc6ba673d458";
$token = "6e8161deb8882445bc4a7a46970d8a35";
$client = new Services_Twilio($sid, $token);

//$call2 = $client->account->calls->create("+13156052265", "+18312244510", "http://sccn.ucsd.edu/~arno/connect.php", array());

$call1 = $client->account->calls->create("+13156052265", "+18584057952", "http://54.186.177.103/telepathyexperiment/ions/basic_conference.xml", array("timeLimit" => "10"));
//$call2 = $client->account->calls->create("+13156052265", "+17077798277", "http://psiresearch.com/telepathyexperiment/ions/basic_conference.xml", array());
//Dean $call2 = $client->account->calls->create("+13156052265", "+17073642466", "http://psiresearch.com/telepathyexperiment/ions/basic_conference.xml", array());
//echo $call1->sid;
//echo $call2->sid;

$phone1 = "858-405-7952";
$phone2 = "831-224-4510";
$phone3 = "+17077798277";

print "done\r\n";
//$d1 = new Dial(array("callerId" => TWILIO_PHONE));
//$d2 = new Dial(array("callerId" => TWILIO_PHONE));

//$n1 = new Number($phone1, array("url" => $text));
//$n2 = new Number($phone2, array("url" => $text)); 
//$d1->append($n1);
//$d2->append($n1);

//$r->append($d1);
//$r->append($d2);

//$r->Respond();
?>
