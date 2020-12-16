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
		print "Could not find experiment\r\n";
	}
	else
	{
		$experiment->set_experiment_id($extension);
		$user = $experiment->get_experimenter();
		$username  = $user->get_first_name();
		$userphone = $user->get_phone();
		$friends = $user->get_friends();
		$friend1 = $friends[0]->friend_name;
		$friend2 = $friends[1]->friend_name;
		$friend1phone = $friends[0]->phone;
		$friend2phone = $friends[1]->phone;
		$hits         = $experiment->get_num_hits();
		$trials       = $experiment->get_trial_count();
		$total_trials_in_experiment = get_num_trials_from_experimentid($extension);

		// send feedback by text if necessary
		if ($trials == 6 || $total_trials_in_experiment == 12) {
			$score = round(100*$hits/(3*$trials));
			send_feedback($username, $userphone, $score);
			send_feedback($friend1, $friend1phone, $score);
			send_feedback($friend2, $friend2phone, $score);
		}
		
		if ($trials == 6) // 6 trials max
		{
			print "Number of completed trials (6) reached for experiment $extension, experiment complete\r\n";
			$experiment->set_status(COMPLETE);
			$experiment->db_update(UPDATE);
			//$db = new database();$db->dblink();$db->db_update("experiments","status='".COMPLETE."'", "experiment_id='{$extension}'"); // equivalent to above
		}
		else
		  	if ($total_trials_in_experiment == 12)
			{
				print "Number of total trials (12) reached for experiment $extension, experiment marked as aborded\r\n";
				$experiment->set_status(ABORTED);
				$experiment->db_update(UPDATE);
				//$db = new database();$db->dblink();$db->db_update("experiments","status='".ABORTED."'", "experiment_id='{$extension}'");
			}
			else
			{
				// create new trial
				print "Creating new trial for experiment $extension\r\n";
				$experiment->increment_trial_count();
				$experiment->db_update(UPDATE);
				insert_tele_trial($extension,$experiment->get_trial_count(),$username,$friend1,$friend2,$userphone,$friend1phone,$friend2phone,$username,$friend1,"");
				$trial_id = get_trial_id($extension,$experiment->get_trial_count());

				print "Calling $userphone, $friend1phone, $friend2phone, trial reference (id) is $trial_id\r\n";
				
				// call people
				$client = new Services_Twilio(SID, TOKEN);
				$commonUrl = "http://54.186.177.103/telepathyexperiment/ions/ions_connect.php?trial_id=$trial_id";
				$client->account->calls->create(PHONE, $userphone,    $commonUrl."&participant=$username", array());
				$client->account->calls->create(PHONE, $friend1phone, $commonUrl."&participant=$friend1" , array());
				$client->account->calls->create(PHONE, $friend2phone, $commonUrl."&participant=$friend2" , array());
			}
	}
}
?>