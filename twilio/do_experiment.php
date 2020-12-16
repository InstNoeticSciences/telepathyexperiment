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
$user = $_SESSION['user'];
$sid = $_POST['CallSid'];

$r = new Response();

// only continue if the user has some friends
if($user->has_friends()) {
    $experiment = new experiment($user,
                                 date("Y-m-d"),
                                 date("G:i:s"),
                                 EMPTY_DATE,
                                 EMPTY_TIME,
                                 0,
                                 0,
                                 NOT_STARTED);

    // only continue if the user has no outstanding experiments
    if($experiment->has_incomplete_experiments()) {
        $r->append(new Play("../mp3/Experiment_already_running.mp3"));
        $r->append(new Redirect("do_exit.php"));
    } else {
        $r->append(new Play("../mp3/Start_experiment2.mp3"));
        $experiment->set_status(IN_PROGRESS);
        $experiment->db_update(INSERT);

		// ******************* AD edition below (only commenting this whole section, no modifications)
		/*
        // start the first trial
        $trial = new trial($experiment->get_experiment_id(),
                           1,
                           $user,
                           null,
                           null,
                           null,
                           date("Y-m-d"),
                           date("G:i:s"),
                           EMPTY_DATE,
                           EMPTY_TIME,
                           EMPTY_DATE,
                           EMPTY_TIME,
                           EMPTY_DATE,
                           EMPTY_TIME,
                           EMPTY_DATE,
                           EMPTY_TIME,
                           NOT_STARTED,
                           0,
                           MISS);

        $trial->select_caller();
        $trial->generate_extension();

        $trial->set_status(IN_PROGRESS);
        $trial->db_update(INSERT);

        // notify the caller via sms
        $sms = new twilio_sms(
                $trial->get_caller_phone(),
                TWILIO_PHONE,
                "Please call ".
                $experiment->get_experimenter()->get_first_name()." ".
                $experiment->get_experimenter()->get_last_name()." ".
                "on ".TWILIO_PHONE.", enter '2' to identify yourself as a ".
                "participant and then enter extension ".$trial->get_extension().".");

        // send the sms now
        $sms->twiml($r);

        // record the date and time the sms was sent
        $trial->set_sms_date(date("Y-m-d"));
        $trial->set_sms_time(date("G:i:s"));
        $trial->db_update(UPDATE);

        $r->append(new Say("Trial ".$trial->get_trial_num()." of ".MAX_TRIALS." ".
                           "has begun. Soon you will receive a call from one of ".
                           "your nominated friends. You may now hang up.", $voice));
		*/		
		// ******************* end of edition

        // say goodbye and hang up
        $r->append(new Redirect("do_exit.php"));
    } // end if
} else {
    $r->append(new Play("../mp3/Friends_not_entered.mp3"));
    $r->append(new Redirect("do_exit.php"));
} // end if

$r->Respond();
?>
