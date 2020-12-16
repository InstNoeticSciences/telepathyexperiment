<?php

// collect parameters
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

//foreach($_GET as $key => $value) {
//     $get[$key] = $value;
//}

//$trial_id    = $get["trial_id"];
//$participant = $get["participant"];
$trial_id = 251;
$participant = "Arnaud Delorme";
$db = new database();
$db->dblink();
$result = $db->get_recs("tele_trials", "*", "id='{$trial_id}'");
$recs = $db->fetch_objects($result);
$expe   = $recs[0]->experiment_id;
$trial  = $recs[0]->trial_num;

/* print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
// print "<info>Trial id=$trial_id; expe=$expe; trial=$trial </info>\r\n";
// print '<Response><Dial><Conference beep="false" waitUrl="" startConferenceOnEnter="true" endConferenceOnExit="true">NoMusicNoBeepRoom</Conference></Dial></Response>';
print "\r\n";
*/

$voice = array("voice" => "woman", "language" => "en", "loop" => "1");
$r = new Response();

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
//$r->append(new Redirect("do_timeout.php?Ext=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"));
//$s = $r->append(new Dial("+18584057952"));
//$s = $r->append(new Redirect(""));
//$s->append(new Conference("NoMusicNoBeepRoom", array("beep"=>"false", "waitUrl"=>"", "startConferenceOnEnter"=>"true", "endConferenceOnExit"=>"true")));

$r->Respond();

?>
