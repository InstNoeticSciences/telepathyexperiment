<?php
include("../inc/config.php");
include("../lib/util.php");
include("../lib/twilio.php");
include("../lib/twilio_util.php");
include("../classes/twilio_sms.php");
include("../classes/experiment.php");
include("../classes/database.php");
include("../classes/password.php");
include("../classes/trial.php");
include("../classes/user.php");

session_start();

$voice = array("voice" => "woman", "language" => "en", "loop" => "1");

$extension = $_REQUEST['Ext'];
$experiment = get_experiment_extension($extension);

// get the trial for the extension
$experiment->read_trials();
$trial = $experiment->get_trial_extension($extension);

$r = new Response();

// prepare a reminder sms for the caller
$sms = new twilio_sms(
        $trial->get_caller_phone(),
        TWILIO_PHONE,
        $experiment->get_experimenter()->get_first_name()." ".
        $experiment->get_experimenter()->get_last_name()." ".
        "did not respond. Please try again.".
        "Call ".TWILIO_PHONE.", enter '2' to identify yourself as a ".
        "participant and then enter extension ".$trial->get_extension().".");

// send the sms now
$sms->twiml($r);

// the call timed out before a guess was entered
$r->append(new Say("I cannot connect the call because you did not ".
                   "enter a guess in time. A reminder will be sent to ".
                   "the caller to phone you again.", $voice));

// say goodbye and hang up
$r->append(new Redirect("do_exit.php"));

$r->Respond();
?>
