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
include("ions_call_utils.php");
//include("check_allconnected.php");

session_start();

// get parameters
$trial_id    = $_GET['trial_id'];
$participant = $_GET['participant'];
$answeredby  = $_POST['AnsweredBy'];
$_SESSION['trial_id']    = $trial_id;
$_SESSION['participant'] = $participant;

//$trial_id    = 220;
//$participant = "Arnaud";
//$answeredby  = "human";

// check participant and update field
$db = new database();
$db->dblink();
$result  = $db->get_recs("tele_trials", "*", "id='{$trial_id}'");
$recs    = $db->fetch_objects($result);
if ($participant == $recs[0]->participant_1) $status = "status_1";
if ($participant == $recs[0]->participant_2) $status = "status_2";
if ($participant == $recs[0]->participant_3) $status = "status_3";

// if answered by an answering machine hangup
if($answeredby == 'machine') {
	$db->update("tele_trials", "$status=-1", "id='{$trial_id}'"); // status -1 means answering machine
	$r1 = new Response();
	$r1->append(new Hangup());
	$r1->Respond();
	exit();
}
$db->db_update("tele_trials", "$status=1", "id='{$trial_id}'"); // status 1 means active

$voice = array("voice" => "woman", "language" => "en", "loop" => "1");
$r = new Response();
//$r->append(new Say("This is the telepathy experiment. Please wait.", $voice));
$r->append(new Play("../mp3/This_is_the_telepathy_experiment.mp3"));
//$r->append(new Redirect('ions_ask_question.php?parameters='.$participant.'_'.$trial_id));
$r->append(new Redirect('ions_ask_question.php'));
$r->Respond();
?>
