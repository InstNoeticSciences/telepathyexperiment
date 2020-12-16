<?php
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

session_start();

$sid = $_REQUEST['Sid'];
$guess = $_REQUEST['Digits'];
$extension = $_REQUEST['Ext'];

$experiment = get_experiment_extension($extension);

// get the trial for the extension
$experiment->read_trials();
$trial = $experiment->get_trial_extension($extension);

// read the participants list
$participants = $trial->get_participants();
$voice = array("voice" => "woman", "language" => "en", "loop" => "1");

$r = new Response();

if($guess > 0 && $guess <= MAX_FRIENDS) {
    $trial->set_caller_guess($participants[$guess - 1]->friend_name);
    $trial->set_guess_date(date("Y-m-d"));
    $trial->set_guess_time(date("G:i:s"));

    // check for a hit
    if($trial->is_hit()) {
        $experiment->increment_num_hits();
    } // end if

    // close this trial
    $trial->set_end_date(date("Y-m-d"));
    $trial->set_end_time(date("G:i:s"));
    $trial->set_status(COMPLETE);
    $trial->db_update(UPDATE);

    // increment the trial count
    $experiment->increment_trial_count();

    if($experiment->get_trial_count() >= MAX_TRIALS) {
        // calculate the score
        $score = $experiment->get_score();
        
        // experiment is complete: close it
        $experiment->set_end_date(date("Y-m-d"));
        $experiment->set_end_time(date("G:i:s"));
        $experiment->set_status(COMPLETE);

        $r->append(new Say("That is the end of this experiment. ".
                           "You scored ".$score." percent. ".
                           "I will now connect your call.", $voice));
    } else {
        // start the next trial
        $next_trial = new trial($experiment->get_experiment_id(),
                                $experiment->get_trial_count() + 1,
                                $experiment->get_experimenter(),
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

        $next_trial->select_caller();
        $next_trial->generate_extension();

        $next_trial->set_sms_date(date("Y-m-d"));
        $next_trial->set_sms_time(date("G:i:s"));
        
        $next_trial->set_status(IN_PROGRESS);
        $next_trial->db_update(INSERT);

        // create a notification for the next caller
        $message =
            "Please call ".
            $experiment->get_experimenter()->get_first_name()." ".
            $experiment->get_experimenter()->get_last_name()." ".
            "on ".TWILIO_PHONE.", enter 2 to identify yourself as a ".
            "participant, and then enter extension ".$next_trial->get_extension().".";

        // calculate a random delay to process before sending
        $delay = mt_rand(SMS_MIN, SMS_MAX);

        // append the notification to the sms queue
        $queue = new sms_queue($sid);
        $queue->append(new twilio_sms($next_trial->get_caller_phone(),
                                      TWILIO_PHONE,
                                      $message,
                                      $sid,
                                      $delay));
       
        $queue->db_update(INSERT);
    } // end if

    $r->append(new Say("Connecting.", $voice));
    $r->append(new Play("http://www.telepathyexperiment.com/sounds/beep-1.mp3"));

    // update the experiment
    $experiment->db_update(UPDATE);
} else {
    // invalid guess entered: try again
    $r->append(new Say("That is not a valid option. Please try again.", $voice));
    $r->append(new Redirect("do_guess.php?SidExt=".$sid."_".$extension));
} // end if

$r->Respond();
?>