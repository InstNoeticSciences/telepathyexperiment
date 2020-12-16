<?php
// get relevant variables
//$extension = $argv[1];
include_once("../twilio-twilio-php-f676f16/Services/Twilio.php");
include_once("ions_feedback.php");
include_once("ions_config.php");

function newtrial($extension)
{
	$experiment = get_experiment_fromid($extension);
	if ($experiment == NULL) 
	{
		print "Could not find experiment $extension\r\n";
	}
	else
	{
		$dateval = date("F j, Y, g:i a");
		$experiment->set_experiment_id($extension);
		$user = $experiment->get_experimenter();
		$participant_1  = $user->get_first_name();
		$phone_1 = $user->get_phone();
		$friends = $user->get_friends();
		$participant_2 = $friends[0]->friend_name;
		$participant_3 = $friends[1]->friend_name;
		$phone_2       = $friends[0]->phone;
		$phone_3       = $friends[1]->phone;
		$hits          = $experiment->get_num_hits();
		$trials        = $experiment->get_trial_count();
		$total_trials_in_experiment = get_num_trials_from_experimentid($extension);

		// send feedback by text if necessary
		if ($trials == 6 || $total_trials_in_experiment == 12) {
			$score = round(100*$hits/(3*$trials));
			send_feedback($participant_1, $phone_1, $score);
			send_feedback($participant_2, $phone_2, $score);
			send_feedback($participant_3, $phone_3, $score);
		}
		
		if ($trials == 6 || $total_trials_in_experiment == 12) // 6 trials max
		{
			print "---------------------------------- $dateval\r\n";
			print "Number of completed trials (6) or total trials (12) reached for experiment $extension, experiment complete\r\n";
            // NOT_STARTED->1, IN_PROGRESS->2, COMPLETE->3, ABORTED->4
			$experiment->set_status(COMPLETE);
			$experiment->db_update(UPDATE);
			set_informed_consent($phone_2, 'needreset',1);
			set_informed_consent($phone_3, 'needreset',1);
			//$db = new database();$db->dblink();$db->db_update("experiments","status='".COMPLETE."'", "experiment_id='{$extension}'"); // equivalent to above
		}
		else
		{
			// check if the 2 numbers have informed consent
			$res_2 = check_informed_consent($phone_2);
			$res_3 = check_informed_consent($phone_3);
			print "---------------------------------- $dateval\r\n";
			print "Experiment $extension\r\n";
			print "Status of participant 2, $participant_2 at $phone_2 is $res_2\r\n";
			print "Status of participant 3, $participant_3 at $phone_3 is $res_3\r\n";
			
			if ($res_2 != 'ok' || $res_3 != 'ok') {
				// one user will not want to do the experiment
				if ($res_2 == 'never' || $res_3 == 'never') {
					$experiment->set_status(ABORTED);
					$experiment->db_update(UPDATE);
					if ($res_2 == 'ok') set_informed_consent($phone_2, 'needreset',1);
					if ($res_3 == 'ok') set_informed_consent($phone_3, 'needreset',1);
					if ($res_2 != 'never') send_cancelparticipant($participant_2, $phone_2);
					if ($res_3 != 'never') send_cancelparticipant($participant_3, $phone_3);
					exit(0);
				}
				
				// one user refuse to do the experiment now or failed to answer 
				if ($res_2 == 'later' || $res_3 == 'later' || $res_2 == 'timeout' || $res_3 == 'timeout') {
					$experiment->set_status(ABORTED);
					$experiment->db_update(UPDATE);
					send_canceluser($participant_1, $phone_1);
					send_cancelparticipant($participant_2, $phone_2);
					send_cancelparticipant($participant_3, $phone_3);
					if ($res_2 == 'ok') set_informed_consent($phone_2, 'needreset',1);
					if ($res_3 == 'ok') set_informed_consent($phone_3, 'needreset',1);
					if ($res_2 == 'later' || $res_2 == 'timeout') set_informed_consent($phone_2, 'notset',1);
					if ($res_3 == 'later' || $res_3 == 'timeout') set_informed_consent($phone_3, 'notset',1);
					exit(0);
				}
				
				// status not set
				$client = new Services_Twilio(SID, TOKEN);
				$commonUrlInformedConsent = "http://54.186.177.103/telepathyexperiment/ions/ions_agree_experiment.php?participant=$participant_1&phone=";
				if ($res_2 == 'notset') {
					$numcalls_2 = inc_call_informed_consent($phone_2);
					if ($numcalls_2 > 3) { set_informed_consent($phone_2, 'timeout');	exit(0); }
					if (test_not_in_progress($phone_2)) $client->account->calls->create(PHONE, $phone_2, $commonUrlInformedConsent.urlencode($phone_2)."&mode=1"); //, array("IfMachine" => "hangup"));
				}
				if ($res_3 == 'notset') {
					$numcalls_3 = inc_call_informed_consent($phone_3);
					if ($numcalls_3 > 3) { set_informed_consent($phone_3, 'timeout');	exit(0); }
					if (test_not_in_progress($phone_3)) $client->account->calls->create(PHONE, $phone_3, $commonUrlInformedConsent.urlencode($phone_3)."&mode=1"); //, array("IfMachine" => "hangup"));
				}
				if ($res_2 == 'needreset') {
					$numcalls_2 = inc_call_informed_consent($phone_2);
					if ($numcalls_2 > 3) { set_informed_consent($phone_2, 'timeout');	exit(0); }
					if (test_not_in_progress($phone_2)) $client->account->calls->create(PHONE, $phone_2, $commonUrlInformedConsent.urlencode($phone_2)); //, array("IfMachine" => "hangup"));
				}
				if ($res_3 == 'needreset') {
					$numcalls_3 = inc_call_informed_consent($phone_3);
					if ($numcalls_3 > 3) { set_informed_consent($phone_3, 'timeout');	exit(0); }
					if (test_not_in_progress($phone_3)) $client->account->calls->create(PHONE, $phone_3, $commonUrlInformedConsent.urlencode($phone_3)); //, array("IfMachine" => "hangup"));
				}
				$tmp = "http://54.186.177.103/telepathyexperiment/ions/ions_agree_experiment.php?participant=$participant_1&phone=".urlencode($phone_2);
				print "$tmp\r\n";
			}
			elseif (test_not_in_progress($phone_1,$phone_2,$phone_3)) {
				
				// do not run every trials
				$randomnum = round(rand(0.51,10.49));
				if ($randomnum >= 5) { 
					print "Random number for experiment $extension is $randomnum -> process experiment\r\n";
		
					// create new trial
					$count = $experiment->get_trial_count();
					insert_tele_trial($extension,$count,$participant_1,$participant_2,$participant_3,$phone_1,$phone_2,$phone_3,"","","");
					$trial_id = mysql_insert_id(); // this line is not compatible with Postgress

					print "Creating new trial for experiment $extension, trial reference (id) is $trial_id\r\n";
					print "Calling participants\r\n";
								
					// call people
					$client = new Services_Twilio(SID, TOKEN);
					$commonUrl = "http://54.186.177.103/telepathyexperiment/ions/ions_connect.php?trial_id=$trial_id";
					$call_1 = $client->account->calls->create(PHONE, $phone_1, $commonUrl."&participant=$participant_1"); //, array("IfMachine" => "continue"));
					$call_2 = $client->account->calls->create(PHONE, $phone_2, $commonUrl."&participant=$participant_2"); // , array("IfMachine" => "continue"));
					$call_3 = $client->account->calls->create(PHONE, $phone_3, $commonUrl."&participant=$participant_3"); // , array("IfMachine" => "continue"));
				}
				else { 
					print "Experiment $extension - random number too low, do nothing\r\n";
				}
			}
			else { 
				print "Experiment $extension - some participants already in a call\r\n";
			}
		}
	}
}
?>