<?php
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
$voice = array("voice" => "woman", "language" => "en", "loop" => "1");

/*
$req_params = explode("_", $_REQUEST['parameters']);
$participant = $req_params[0];
$trial_id    = $req_params[1];
$target1     = $req_params[2];
$target2     = $req_params[3];
$guess       = $req_params[4];
*/

$participant = "Arnaud";
$trial_id    = 240;
$target1     = "Mollie";
$target2     = "Alan";
$guess       = 2;

// get trial information
$db = new database();
$db->dblink();
$result  = $db->get_recs("tele_trials", "*", "id='{$trial_id}'");
$recs    = $db->fetch_objects($result);
$expe_id = $recs[0]->experiment_id;

// create new response
if ($participant == $recs[0]->participant_1) { $target = $recs[0]->actual_1; $status = "status_1"; }
if ($participant == $recs[0]->participant_2) { $target = $recs[0]->actual_2; $status = "status_2"; }
if ($participant == $recs[0]->participant_3) { $target = $recs[0]->actual_3; $status = "status_3"; }
$db->db_update("tele_trials", "$status=2", "id='{$trial_id}'");

// wait for the two other participants to respond
$cont = 0; // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
$loop = 0; // max 40 loops = 10 seconds
while ($cont == 1 && $loop < 40) {
	$status_results = $db->get_recs("tele_trials", "status_1,status_2,status_3", "id='{$trial_id}'");
	$status_recs    = $db->fetch_objects($status_results);
	if ($status_recs[0]->status_1 == 2  && $status_recs[0]->status_2 == 2  && $status_recs[0]->status_3 == 2)  $cont = 0;
	if ($status_recs[0]->status_1 == -1 || $status_recs[0]->status_2 == -1 || $status_recs[0]->status_3 == -1) $loop = 40; // hangup if anybody is on an answering machine
	usleep(250000); // 1/4 of a second
	$loop++;
}

// exit if necessary
if ($cont == 1) {
	$r2 = new Response();
    $r2->append(new Say("Some of the participant connected but failed to respond. We will try again later. Goodbye.", $voice));
	$r2->append(new Hangup());
	$r2->Respond();
	exit();
}

/*print "guess is $guess\r\n";
print "target1 is $target1\r\n";
print "target2 is $target2\r\n";
print "target  is $target\r\n";*/

// cumulate hits
$response = "This is not a good response.";
if ($guess == 1 && $target == $target1) { $response = "This is a good response."; $db->db_update("experiments", "hit=hit+1", "experiment_id='{$expe_id}'"); } 
if ($guess == 2 && $target == $target2) { $response = "This is a good response."; $db->db_update("experiments", "hit=hit+1", "experiment_id='{$expe_id}'"); } 
 
// update database
if ($participant == $recs[0]->participant_1) $db->db_update("tele_trials", "guess_1='{$target}'", "id='{$trial_id}'");
if ($participant == $recs[0]->participant_2) $db->db_update("tele_trials", "guess_2='{$target}'", "id='{$trial_id}'");
if ($participant == $recs[0]->participant_3) $db->db_update("tele_trials", "guess_3='{$target}'", "id='{$trial_id}'");

// close this trial
$db->db_update("tele_trials", "status=2", "id='{$trial_id}'");

// increment the trial count and update experiment record
if ($participant == $recs[0]->participant_3)
{
	$db->db_update("experiments", "trial_count=trial_count+1", "experiment_id='{$expe_id}'");
	$db->db_update("experiments", "end_date=".date("Y-m-d"), "experiment_id='{$expe_id}'");
	$db->db_update("experiments", "end_time=".date("G:i:s"), "experiment_id='{$expe_id}'");
	if ($experiment->get_trial_count() >= MAX_TRIALS)
		$db->db_update("experiments", "status=".COMPLETE, "experiment_id='{$expe_id}'");
}

$r2 = new Response();
if (empty($target)) {
	$r2->append(new Say($response."You are not being connected to anybody on this trial. Goodbye.", $voice));
	$r2->append(new Hangup());
}
else {
	$r2->append(new Say($response."Connecting.", $voice));
	$s2 = $r2->append(new Dial(""));
	$s2->append(new Conference('Conf'.$expe_id.'_'.$trial_id, array("beep"=>"false", "waitUrl"=>"", "startConferenceOnEnter"=>"true", "endConferenceOnExit"=>"true")));
}
//$r2->append(new Play("http://www.telepathyexperiment.com/sounds/beep-1.mp3"));
$r2->Respond();

?>