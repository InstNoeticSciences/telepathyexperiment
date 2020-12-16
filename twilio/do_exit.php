<?php
include("../lib/twilio.php");
include("../lib/twilio_util.php");

session_start();
$_SESSION = array();

$voice = array("voice" => "woman", "language" => "en", "loop" => "1");

// say goodbye
$r = new Response();
//$r->append(new Say("Goodbye.", $voice));
$r->append(new Play("../mp3/Goodbye.mp3"));
$r->append(new Hangup());

// end the session and hang up
session_destroy();
$r->Respond();
?>
