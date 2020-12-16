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

session_start();

$experiment = $_SESSION['experiment'];
$extension = $_REQUEST['Digits'];

$phone = $experiment->get_experimenter()->get_phone();
$first_name = $experiment->get_experimenter()->get_first_name();
$voice = array("voice" => "woman", "language" => "en", "loop" => "1");

$r = new Response();

$r->append(new Say("Connecting you to ".$first_name.".", $voice));

$d = new Dial(array("callerId" => TWILIO_PHONE));
$sid_ext = $_POST['CallSid']."_".$extension;

$n = new Number($phone, array("url" => "do_guess.php?SidExt=$sid_ext"));

$d->append($n);
$r->append($d);

$r->Respond();
?>