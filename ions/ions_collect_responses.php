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

session_start();

// get parameters
$trial_id     = $_SESSION['trial_id'];
$participant  = $_SESSION['participant'];
$target1      = $_SESSION['target1'];
$target2      = $_SESSION['target2'];
$guess        = $_SESSION['guess']; // 1, 2 or 3 

/*$req_params = explode("_", $_REQUEST['parameters']);
$participant   = $req_params[0];
$trial_id      = $req_params[1];
$target1 = $req_params[2];
$target2 = $req_params[3];
$guess         = $req_params[4];*/

/*$r = new Response();
$r->append(new Say("Participant is $participant. Trial is $trial_id. Guess target is $target1. Guess target is $target2. Actual guess is $guess. End of message.", $voice));
$r->append(new Hangup());
$r->Respond();
exit(0);*/

/*
$participant = "Arnaud";
$trial_id    = 251;
$target1     = "Dean";
$target2     = "Leena";
$guess       = 2;*/

// get trial information
$db = new database();
$db->dblink();
$result  = $db->get_recs("tele_trials", "*", "id='{$trial_id}'");
$recs    = $db->fetch_objects($result);
$expe_id = $recs[0]->experiment_id;
$participant_1 = $recs[0]->participant_1;
$participant_2 = $recs[0]->participant_2;
$participant_3 = $recs[0]->participant_3;

/*print "p1 is $participant_1\r\n";
print "p2 is $participant_2\r\n";
print "p3 is $participant_3\r\n";
$res = ($participant == $participant_2);
print "p=p2 is $res\r\n"; */

// create new response
if ($participant == $participant_1) $db->db_update("tele_trials", "status_1=2", "id='{$trial_id}'");
if ($participant == $participant_2) $db->db_update("tele_trials", "status_2=2", "id='{$trial_id}'");
if ($participant == $participant_3) $db->db_update("tele_trials", "status_3=2", "id='{$trial_id}'");

// wait for the two other participants to respond
$cont = 1; // XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
$loop = 0; // max 40 loops = 15 seconds
while ($cont == 1 && $loop < 56) {
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
	$r2->append(new Play("../mp3/Participant_not_available2.mp3"));
	$r2->append(new Hangup());
	$r2->Respond();
	exit();
}

// make the radom assignment
if ($participant == $participant_1) {
	$randomnum = file_get_contents('http://www.random.org/integers/?num=1&min=1&max=3&col=1&base=10&format=plain&rnd=new"'); // number 1 to 10
	$randomnum = intval($randomnum);
	if ($randomnum == 0) $randomnum = round(rand(0.50001,3.4999));
	switch ($randomnum) {
		case 1: $actual_1 = $participant_2; $actual_2 = $participant_1; $actual_3 = ""; break;
		case 2: $actual_1 = $participant_3; $actual_3 = $participant_1; $actual_2 = ""; break;
		case 3: $actual_3 = $participant_2; $actual_2 = $participant_3; $actual_1 = ""; break;
	}
	$db->db_update("tele_trials", "actual_1='{$actual_1}',actual_2='{$actual_2}',actual_3='{$actual_3}'", "id='{$trial_id}'");
}

// wait for the random assignment
$cont = 1;
$loop = 0; // max 20 loops = 5 seconds
while ($cont == 1 && $loop < 20) {
	$actual_results = $db->get_recs("tele_trials", "actual_1,actual_2,actual_3", "id='{$trial_id}'");
	$actual_recs    = $db->fetch_objects($actual_results);
	/*print "target1 is".$actual_recs[0]->actual_1."\r\n";
	print "target2 is".$actual_recs[0]->actual_2."\r\n";
	print "target3 is".$actual_recs[0]->actual_3."\r\n";*/
	if (!empty($actual_recs[0]->actual_1)  || !empty($actual_recs[0]->actual_2) || !empty($actual_recs[0]->actual_3)) $cont = 0;
	usleep(250000); // 1/4 of a second
	$loop++;
}

// exit if necessary
if ($cont == 1) {
	$r2 = new Response();
	$r2->append(new Play("../mp3/Technical_problem.mp3"));
	$r2->append(new Hangup());
	$r2->Respond();
	exit();
}

// get target for current participant
if ($participant == $participant_1) $target = $actual_recs[0]->actual_1;
if ($participant == $participant_2) $target = $actual_recs[0]->actual_2;
if ($participant == $participant_3) $target = $actual_recs[0]->actual_3;

/*print "guess is $guess\r\n";
print "Guess target1 is $target1\r\n";
print "Guess target2 is $target2\r\n";
print "target  is $target\r\n"; */

// cumulate hits
$response = "This is not a correct response. The correct response was nobody.";
$hit      = 0;
if ($guess == 1) $guessresp = $target1; 
if ($guess == 2) $guessresp = $target2; 
if ($guess == 3) $guessresp = ""; 
if ($target == $guessresp) $hit = 1;
//if ($guess == 1 && $target == $target1) $hit = 1;
//if ($guess == 2 && $target == $target2) $hit = 1;
//if ($guess == 3 && $target == ""      ) $hit = 1;
 
if ($hit == 1) {
	// This is a correct response 
	$db->db_update("experiments", "num_hits=num_hits+1", "experiment_id='{$expe_id}'");
	$db->db_update("tele_trials", "hit=hit+1", "id='{$trial_id}'");
}  
 
// update database
if ($participant == $participant_1) $db->db_update("tele_trials", "guess_1='{$guessresp}'", "id='{$trial_id}'");
if ($participant == $participant_2) $db->db_update("tele_trials", "guess_2='{$guessresp}'", "id='{$trial_id}'");
if ($participant == $participant_3) $db->db_update("tele_trials", "guess_3='{$guessresp}'", "id='{$trial_id}'");

// close this trial
$db->db_update("tele_trials", "status=2", "id='{$trial_id}'");

// increment the trial count and update experiment record
if ($participant == $participant_1)
{
	$db->db_update("experiments", "trial_count=trial_count+1", "experiment_id='{$expe_id}'");
	$db->db_update("experiments", "end_date=".date("Y-m-d"),   "experiment_id='{$expe_id}'");
	$db->db_update("experiments", "end_time=".date("G:i:s"),   "experiment_id='{$expe_id}'");
}

$r2 = new Response();
if (empty($target)) {
	//$r2->append(new Say($response."You are not being connected to anybody on this trial. Goodbye.", $voice));
	$r2->append(new Play("../mp3/Not_connecting.mp3"));
	$r2->append(new Hangup());
}
else {
	$r2->append(new Play("../mp3/Now_connecting.mp3"));
	$s2 = $r2->append(new Dial(""));
	$s2->append(new Conference('Conf'.$expe_id.'_'.$trial_id, array("beep"=>"false", "waitUrl"=>"", "startConferenceOnEnter"=>"true", "endConferenceOnExit"=>"true")));
}
//$r2->append(new Play("http://www.telepathyexperiment.com/sounds/beep-1.mp3"));
$r2->Respond();

?>