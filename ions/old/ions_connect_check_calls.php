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
include("check_allconnected.php");

$voice = array("voice" => "woman", "language" => "en", "loop" => "1");

// if answered by an answering machine hangup
if($_POST['AnsweredBy'] == 'machine') {
	$r1 = new Response();
	$r1->append(new Hangup());
	$r1->Respond();
	exit();
}

// wait for the two other participants to respond
$cont = 1;
$loop = 0; // max 40 loops = 10 seconds
while ($cont == 1 && $loop < 40) {
	$status_results = $db->get_recs("tele_trials", "status_1,status_2,status_3", "id='{$trial_id}'");
	$status_recs    = $db->fetch_objects($status_results);
	if ($status_recs->status_1 == 2 && $status_recs->status_2 == 2 && $status_recs->status_3 == 2) $cont = 0;
	usleep(250000); // 1/4 of a second
	$loop++;
}

// Get trial and participant information
// -------------------------------------
$trial_id    = $_REQUEST['trial_id'];
$participant = $_REQUEST['participant'];
//$trial_id = 251;
//$participant = "Arnaud Delorme";
$db = new database();
$db->dblink();
$result = $db->get_recs("tele_trials", "*", "id='{$trial_id}'");
$recs = $db->fetch_objects($result);
$expe   = $recs[0]->experiment_id;
$trial  = $recs[0]->trial_num;
$phone_1  = $recs[0]->phone_1;
$phone_2  = $recs[0]->phone_2;
$phone_3  = $recs[0]->phone_3;

// wait until the 3 people are connected
$count = 0;
$loop  = 0;
$r = new Response();
$r->append(new Say("Hello. This is the telepathy experiment. Please wait.", $voice));
$r->Respond();
while ($count < 3 || $loop == 10) {
	$count = check_allconnected($client, $phone1, $phone2, $phone3);
	print "Count is $count\r\n";
	sleep(1);
	$loop++;
}
// check again in case this is an answering machine
sleep(1);
$count = check_allconnected($client, $phone1, $phone2, $phone3);	

// time out, play appropriate message
if ($count < 3) {
	$r2 = new Response();
    $r2->append(new Say("Some of the participant were not available. We will try again later. Goodbye.", $voice));
	$r2->append(new Hangup());
	$r2->Respond();
	exit();
}

/* print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
// print "<info>Trial id=$trial_id; expe=$expe; trial=$trial </info>\r\n";
// print '<Response><Dial><Conference beep="false" waitUrl="" startConferenceOnEnter="true" endConferenceOnExit="true">NoMusicNoBeepRoom</Conference></Dial></Response>';
print "\r\n";
*/

// build the participants list
if ($participant == $recs[0]->participant_1) { $target1 = $recs[0]->participant_2; $target2 = $recs[0]->participant_3; };
if ($participant == $recs[0]->participant_2) { $target1 = $recs[0]->participant_1; $target2 = $recs[0]->participant_3; };
if ($participant == $recs[0]->participant_3) { $target1 = $recs[0]->participant_1; $target2 = $recs[0]->participant_2; };

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

$list = "Who is calling? Enter 1 for ".$target1.", or 2 for ".$target2.".";

// prompt for a guess
$g = $r->append(new Gather(array("numDigits" => "1",
                                 "timeout" => "10",
                                 "action" => "ions_collect_response.php?trial_id=trial_id&participant=$participant&target1=$target1&target2=$target2",
                                 "method" => "POST")));
$g->append(new Say($list, $voice));

// handle a timeout on the guess
$r->append(new Say("You did not answer in time. We are now ending the call.", $voice));
$r->append(new Hangup());
$r->Respond();

?>
