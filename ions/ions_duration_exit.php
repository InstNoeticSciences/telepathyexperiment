<?php
include("../lib/twilio.php");
include("../lib/twilio_util.php");

session_start();
$_SESSION = array();

$voice = array("voice" => "woman", "language" => "en", "loop" => "1");

// say goodbye
$r = new Response();
$r->append(new Play("../mp3/Call_time_limit.mp3"));
//$r->append(new Say("We are sorry. The call duraction limit has been reached. Goodbye.", $voice));
$r->append(new Hangup());

// end the session and hang up
session_destroy();
$r->Respond();
?>
