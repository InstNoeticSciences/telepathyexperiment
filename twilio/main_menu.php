<?php
include("../inc/config.php");
include("../lib/util.php");
include("../lib/twilio.php");
include("../lib/twilio_util.php");
include("../classes/sms_queue.php");
include("../classes/twilio_sms.php");
include("../classes/experiment.php");
include("../classes/database.php");
include("../classes/password.php");
include("../classes/trial.php");
include("../classes/user.php");

session_start();

$voice = array("voice" => "woman", "language" => "en", "loop" => "1");

if(!isset($_REQUEST['MenuState'])) {
	// ******************* AD edition below
    //$menu_state = MENU_MAIN;
    $menu_state = MENU_EXPERIMENTER;
	// ******************* end of edition
} else {
    $menu_state = $_REQUEST['MenuState'];
} // end if

$user = get_user_phone($_REQUEST['From']);
if (empty($user)) $menu_state = ERROR;
$_SESSION['user'] = $user;

switch($menu_state) {
    case ERROR:
       $r = new Response();
       $r->append(new Say("Sorry, your phone number has not been recognized. Check that you entered the correct phone number when registering on our web site.",$voice));
	   $r->Respond();
	   break;
	   
    case MENU_MAIN:
        /*$_SESSION['caller'] = MENU_MAIN;

        $r = new Response();
        $g = $r->append(new Gather(array("numDigits" => "1",
                                         "timeout" => "10",
                                         "action" => "menu_response.php",
                                         "method" => "POST")));

        $g->append(new Play("../mp3/Initiate_experiment.mp3");
        $g->append(new Say("Welcome to the Telephone Telepathy Experiment. ".
                           "Please enter 1, if you are an experimenter, ".
                                        "2, if you are a participant, ".
                                        "8, to hear these options again, or, ".
                                        "9, to exit.", $voice));
        $r->Respond(); */
        break;
    case MENU_EXPERIMENTER:
        $_SESSION['caller'] = MENU_EXPERIMENTER;

        $r = new Response();
	    $r->append(new Play("../mp3/Cancel_experiment1.mp3"));
        $r->append(new Say($user->get_first_name(), $voice));
        $g = $r->append(new Gather(array("numDigits" => "1",
                                         "timeout" => "10",
                                         "action" => "menu_response.php",
                                         "method" => "POST")));

	    $g->append(new Play("../mp3/Initiate_experiment.mp3"));
	    $r->Respond();
        break;
    case MENU_PARTICIPANT:
        /*$_SESSION['caller'] = MENU_PARTICIPANT;

        $r = new Response();
        $g = $r->append(new Gather(array("numDigits" => "4",
                                         "timeout" => "10",
                                         "action" => "menu_response.php",
                                         "method" => "POST")));

        $g->append(new Say("Please enter the four digit extension number ".
                           "that was sent to you in a text message.", $voice));
        $r->Respond();*/
        break;
    case MENU_EXPERIMENT:
        $_SESSION['caller'] = MENU_EXPERIMENT;

        $r = new Response();
        $g = $r->append(new Gather(array("numDigits" => "5",
                                         "timeout" => "10",
                                         "action" => "menu_response.php",
                                         "method" => "POST")));

	    $g->append(new Play("../mp3/Enter_pin_number.mp3"));
        $r->Respond();
        break;
    case MENU_CANCEL:
        $_SESSION['caller'] = MENU_CANCEL;

        $r = new Response();
        $g = $r->append(new Gather(array("numDigits" => "5",
                                         "timeout" => "10",
                                         "action" => "menu_response.php",
                                         "method" => "POST")));

	    $g->append(new Play("../mp3/Enter_pin_number.mp3"));
        $r->Respond();        
    default:
        break;
} // end switch
?>
