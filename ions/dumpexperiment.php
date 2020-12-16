<?php
include_once("../twilio-twilio-php-f676f16/Services/Twilio.php");
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
include("ions_db_utils.php");
include("ions_call_utils.php");
include("ions_newtrial.php");

error_reporting(-1);
ini_set('display_errors', 'On');

// scan all experiments in database which are in progress
$db = new database();
$db->dblink();
$ex_select = $db->get_recs("experiments",
						   "*",
						   "");
$ex_recs = $db->fetch_objects($ex_select);

// scan database entries
printf('<html><body><table border=1>');
printf('<tr><th>experiment_id</th><th>experimenter</th><th>status</th><th>start_date</th><th>start_time</th><th>trial_count</th><th>num_hits</th></tr>');
for ($i=0; $i<count($ex_recs); $i++)
{
	$expeid       = $ex_recs[$i]->experiment_id;
	$experimenter = $ex_recs[$i]->experimenter;
    $start_date   = $ex_recs[$i]->start_date;
    $start_time   = $ex_recs[$i]->start_time;
    $trial_count  = $ex_recs[$i]->trial_count;
    $num_hits     = $ex_recs[$i]->num_hits;
    $status       = $ex_recs[$i]->status;
	printf("<tr><td>$expeid</td><td>$experimenter</td><td>$status</td><td>$start_date</td><td>$start_time</td><td>$trial_count</td><td>$num_hits</td></tr>"); 
}
printf('</table></body></html>');
?>