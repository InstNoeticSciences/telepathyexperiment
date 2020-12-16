<?php
include("../inc/config.php");
include("../lib/twilio.php");
include("../classes/database.php");

// testing http://psiresearch.com/telepathyexperiment/ions/ions_agree_process_answer.php?participant=Arnaud&phone=18584057952&Digits=2

session_start();
$phone       = $_SESSION['phone'];
$participant = $_SESSION['participant'];
if (empty($phone)) {
	$phone       = $_REQUEST['phone'];
	$participant = $_REQUEST['participant'];
}
$guess       = $_REQUEST['Digits'];

$r = new Response();
$voice = array("voice" => "woman", "language" => "en", "loop" => "1");

if ($guess > 0 && $guess <= 3) {
	// ask participant to wait
	if ($guess == 1) { $newstatus = "ok";    $text = "../mp3/Informed_consent_ok.mp3"; }
	if ($guess == 2) { $newstatus = "later"; $text = "../mp3/Informed_consent_later.mp3"; }
	if ($guess == 3) { $newstatus = "never"; $text = "../mp3/Informed_consent_never.mp3"; }
	
	// update database
    $db = new database();
    $db->dblink();
	$db->db_update("tele_informedconsent", "status='{$newstatus}'", "phone='{$phone}'");
	//ß$db->db_insert("tele_informedconsent", "phone", "'$phone'");

	// append message	
	$r->append(new Play($text));
	$r->append(new Hangup());
} else {
    // invalid guess entered: try again
	$r->append(new Play("../mp3/Not_valid_option.mp3"));
    $r->append(new Redirect('ions_agree_experiment.php'));
} // end if

$r->Respond();

?>