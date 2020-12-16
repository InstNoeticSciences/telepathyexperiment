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

//session_start();

$voice = array("voice" => "woman", "language" => "en", "loop" => "1");

//$req_params = explode("_", $_REQUEST['SidExt']);
//$sid = $req_params[0]; // first of 2 paraetmers = phone of caller
//$extension = $req_params[1]; // second of 2 parameters = choice 1 or 2

$sid = 7963;
$extension = 1;

// $sid = $_REQUEST['Sid'];
// $extension = $_REQUEST['Ext'];

$experiment = get_experiment_extension($extension);

// get the trial for the extension
//$expid = $experiment->get_experiment_id();
//print $expid;
$experiment->read_trials();
$trial = $experiment->get_trial_extension($extension);

// record the date and time that the participant called
$trial->set_call_date(date("Y-m-d"));
$trial->set_call_time(date("G:i:s"));
$trial->db_update(UPDATE);

$participants = $trial->get_participants();
$num_participants = count($participants);
$list = null;

// build the participants list
for($i = 0; $i < $num_participants; $i++) {
    if($i == ($num_participants - 1)) {
        $list .= " or ".($i + 1).", for ".$participants[$i]->friend_name.".";
    } else {
        $list .= ($i + 1).", for ".$participants[$i]->friend_name.", ";
    } // end if
} // end for

$r = new Response();

// prompt for a guess
$g = $r->append(new Gather(array("numDigits" => "1",
                                 "timeout" => "10",
                                 "action" => "do_connect.php?Ext=".$extension.
                                                           "&Sid=".$sid,
                                 "method" => "POST")));

$g->append(new Say("Who is calling? Enter: ".$list, $voice));

// handle a timeout on the guess
$r->append(new Redirect("do_timeout.php?Ext=".$extension));

$r->Respond();
?>
