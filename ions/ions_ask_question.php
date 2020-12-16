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

// get parameters
$trial_id    = $_SESSION['trial_id'];
$participant = $_SESSION['participant'];
/*$req_params = explode("_", $_REQUEST['parameters']);
$participant = $req_params[0];
$trial_id    = $req_params[1];*/
//$trial_id    = $_GET['trial_id'];
//$participant = $_GET['participant'];
$answeredby  = $_POST['AnsweredBy'];

//$trial_id    = 220;
//$participant = "Arnaud";
//$answeredby  = "human";

$voice = array("voice" => "woman", "language" => "en", "loop" => "1");

// check participant and update field
$db = new database();
$db->dblink();

// if answered by an answering machine hangup
if($answeredby == 'machine') {
	$db->update("tele_trials", "$status=-1", "id='{$trial_id}'"); // status -1 means answering machine
	$r1 = new Response();
	$r1->append(new Hangup());
	$r1->Respond();
	exit(); // should hang up by itself but who knows
}

// wait for the two other participants to answer
// ---------------------------------------------
$cont = 1; // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
$loop = 0; // max 60 loops = 15 seconds
while ($cont == 1 && $loop < 56) {
	$status_results = $db->get_recs("tele_trials", "status_1,status_2,status_3", "id='{$trial_id}'");
	$status_recs    = $db->fetch_objects($status_results);
	if ($status_recs[0]->status_1 == 1  && $status_recs[0]->status_2 == 1  && $status_recs[0]->status_3 == 1)  $cont = 0;  // everybody has answered
	if ($status_recs[0]->status_1 == -1 || $status_recs[0]->status_2 == -1 || $status_recs[0]->status_3 == -1) $loop = 40; // hangup if anybody is on an answering machine
	usleep(250000); // wait 1/4 of a second
	$loop++;
}

// time out or answering machine, play appropriate message
if ($cont == 1) {
	$r2 = new Response();
    //$r2->append(new Say("Some of the participant were not available. We will try again later. Goodbye.", $voice));
    $r2->append(new Play("../mp3/Participant_not_available.mp3"));
	$r2->append(new Hangup());
	$r2->Respond();
	exit();	
}

// build the participants list
$result  = $db->get_recs("tele_trials", "*", "id='{$trial_id}'");
$recs    = $db->fetch_objects($result);
if ($participant == $recs[0]->participant_1) { $target = $recs[0]->actual_1; $target1 = $recs[0]->participant_2; $target2 = $recs[0]->participant_3; }
if ($participant == $recs[0]->participant_2) { $target = $recs[0]->actual_2; $target1 = $recs[0]->participant_1; $target2 = $recs[0]->participant_3; }
if ($participant == $recs[0]->participant_3) { $target = $recs[0]->actual_3; $target1 = $recs[0]->participant_1; $target2 = $recs[0]->participant_2; }
if ($participant != $recs[0]->participant_3 && $participant != $recs[0]->participant_2 && $participant != $recs[0]->participant_1) {
	$r2 = new Response();
    $r2->append(new Say("Trial is is $trial_id. Participant $participant not found. This error should never occur. There is a problem with the code. Goodbye.", $voice));
	$r2->append(new Hangup());
	$r2->Respond();
	exit();
}

/*$p1 = $recs[0]->participant_1;
$p2 = $recs[0]->participant_2;
$p3 = $recs[0]->participant_3;
print "participant 1 -> $p1\r\n";
print "participant 2 -> $p2\r\n";
print "participant 3 -> $p3\r\n";*/

// randomize order of 2 participants
if (round(rand(0.51,2.49)) == 2) {
	$tmptarget = $target1;
	$target1   = $target2;
	$target2   = $tmptarget;
}

//$list = "Tell me who is calling? Enter 1 for ".$target1.", or 2 for ".$target2.".";
// prompt for a guess
$_SESSION['target1'] = $target1;
$_SESSION['target2'] = $target2;
$r3 = new Response();
$g = $r3->append(new Gather(array("numDigits" => "1",
                                 "timeout" => "10",
                                 "action" => "ions_response_wait.php",
                                 "method" => "POST")));
$g->append(new Play("../mp3/Who_do_you_think.mp3"));
$g->append(new Say($target1, $voice));
$g->append(new Play("../mp3/Press_2_for.mp3"));
$g->append(new Say($target2, $voice));
$g->append(new Play("../mp3/Press_3_for.mp3"));

// handle a timeout on the guess
$r3->append(new Play("../mp3/Time_out.mp3"));
$r3->append(new Hangup());
$r3->Respond();

?>
