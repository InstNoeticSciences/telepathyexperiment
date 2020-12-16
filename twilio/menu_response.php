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

// Doc https://www.twilio.com/docs/api/twiml/twilio_request

session_start();

$voice = array("voice" => "woman", "language" => "en", "loop" => "1");

switch($_SESSION['caller']) {
    case MENU_EXPERIMENTER:
        // experimenter menu
        $r = new Response();
        $user = $_SESSION['user'];

        switch($_REQUEST['Digits']) {
            case "1":
                // begin an experiment
                $r->append(new Redirect("do_experiment.php"));
				//$r->append(new Redirect("main_menu.php?MenuState=".MENU_EXPERIMENT));
                break;
            case "2":
                // cancel an experiment
            	$r->append(new Play("../mp3/Cancel_experiment2.mp3"));
            	$r->append(new Redirect("do_cancel.php"));
                //$r->append(new Redirect("main_menu.php?MenuState=".MENU_CANCEL));
                break;
            case "8":
                // repeat menu options
                $r->append(new Redirect("main_menu.php?MenuState=".MENU_EXPERIMENTER));
                break;
            case "9":
                // exit
                $r->append(new Redirect("do_exit.php"));
                break;
            default:
                // repeat menu options
	            $r->append(new Play("../mp3/Not_valid_option.mp3"));
                $r->append(new Redirect("main_menu.php?MenuState=".MENU_EXPERIMENTER));
                break;
        } // end switch

        $r->Respond();
        break;
    /*case MENU_MAIN:
         main menu
        $r = new Response();

        switch($_REQUEST['Digits']) {
            case "1":
                // caller is an experimenter
                $r->append(new Redirect("main_menu.php?MenuState=".MENU_EXPERIMENTER));
                break;
            case "2":
                // caller is a participant
                $r->append(new Redirect("main_menu.php?MenuState=".MENU_PARTICIPANT));
                break;
            case "8":
                // repeat menu options
                $r->append(new Redirect("main_menu.php?MenuState=".MENU_MAIN));
                break;
            case "9":
                // exit
                $r->append(new Redirect("do_exit.php"));
                break;
            default:
                // repeat menu options
                $r->append(new Say("Sorry, I do not recognize that option.", $voice));
                $r->append(new Redirect("main_menu.php?MenuState=".MENU_MAIN));
                break;
        } // end switch

        $r->Respond();
        break;
     case MENU_CANCEL:
        // experiment cancellation
        $r = new Response();

        // validate the pin number
        $user = get_user_pin($_REQUEST['Digits']);

        if($user == null) {
            // no such user
            $r->append(new Play("../mp3/Wrong_pin.mp3"));
            $r->append(new Redirect("main_menu.php?MenuState=".MENU_EXPERIMENT));
        } else if($user->is_locked($user->get_username())) {
            // user is locked
            $r->append(new Play("../mp3/Locked_user.mp3"));
            $r->append(new Redirect("do_exit.php"));
        } else {
            // everything ok: cancel the current experiment
            $_SESSION['user'] = $user;
            $r->append(new Play("../mp3/Cancel_experiment1.mp3"));
            $r->append(new Say($user->get_first_name(), $voice));
            $r->append(new Play("../mp3/Cancel_experiment2.mp3"));
            $r->append(new Redirect("do_cancel.php"));
        } // end if

        $r->Respond();
        break;
    case MENU_EXPERIMENT:
        // experiment menu
        $r = new Response();

        // validate the pin number
        $user = get_user_pin($_REQUEST['Digits']);

        if($user == null) {
            // no such user
            $r->append(new Play("../mp3/Wrong_pin.mp3"));
            $r->append(new Redirect("main_menu.php?MenuState=".MENU_EXPERIMENT));
        } else if($user->is_locked($user->get_username())) {
            // user is locked
            $r->append(new Play("../mp3/Locked_user.mp3"));
            $r->append(new Redirect("do_exit.php"));
        } else {
            // everything ok: begin the experiment
            $_SESSION['user'] = $user;
	        $r->append(new Redirect("do_experiment.php"));
        } // end if

        $r->Respond();
        break;
    case MENU_PARTICIPANT:
        participant menu
        $r = new Response();

        // get the experiment
        $experiment = get_experiment_extension($_REQUEST['Digits']);
        
        if($experiment == null) {
            // experiment not found
            $r->append(new Say("Sorry, that is not a valid extension. ".
                               "Please try again at the prompt, or just ".
                               "hang up.", $voice));
            $r->append(new Redirect("main_menu.php?MenuState=".MENU_PARTICIPANT));
        } else {
            // get the trial for the extension
            $experiment->read_trials();
            $trial = $experiment->get_trial_extension($_REQUEST['Digits']);
            
            if($trial == null) {
                // trial not found
                $r->append(new Say("Sorry, that is not a valid extension. ".
                                   "Please try again at the prompt, or just ".
                                   "hang up.", $voice));
                $r->append(new Redirect("main_menu.php?MenuState=".MENU_PARTICIPANT));
            } else if($trial->get_status() == COMPLETE) {
                // trial is already complete
                $r->append(new Say("Sorry, that trial is already complete.".
                                   "Please check your extension number and ".
                                   "try again at the prompt, or just hang up.", $voice));
                $r->append(new Redirect("main_menu.php?MenuState=".MENU_PARTICIPANT));
            } else if($trial->get_status() == ABORTED) {
                // trial was aborted
                $r->append(new Say("Sorry, that trial has been aborted.".
                                   "Please check your extension number and ".
                                   "try again at the prompt, or just hang up.", $voice));
                $r->append(new Redirect("main_menu.php?MenuState=".MENU_PARTICIPANT));
            } else if($trial->get_status() == NOT_STARTED) {
                // trial has not yet begun
                $r->append(new Say("Sorry, that trial has not yet started.".
                                   "Please check your extension number and ".
                                   "try again at the prompt, or just hang up.", $voice));
                $r->append(new Redirect("main_menu.php?MenuState=".MENU_PARTICIPANT));
            } else {
                // everything OK: connect the call
                $_SESSION['trial'] = $trial;
                $_SESSION['experiment'] = $experiment;

                $r->append(new Redirect("do_participant.php?Digits=".$_REQUEST['Digits']));
            } // end if
        } // end if
        $r->Respond();
        break; */
    default:
        $r = new Response();

	    $r->append(new Play("../mp3/Not_valid_option.mp3"));
        $r->append(new Redirect("main_menu.php?MenuState=".MENU_MAIN));

        $r->Respond();
        break;
} // end switch
?>
