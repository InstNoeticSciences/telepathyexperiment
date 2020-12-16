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

// suppress long calls
suppress_long_calls(100);
//if (!test_not_in_progress("18584057952","","")) print "One call found in progress\r\n";
//exit(0);

// scan all experiments in database which are in progress
$db = new database();
$db->dblink();
$ex_select = $db->get_recs("experiments",
						   "*",
						   "status='".NOT_STARTED."' or
                            status='".IN_PROGRESS."'");
$ex_recs = $db->fetch_objects($ex_select);

// return null if nothing found
if(!is_array($ex_recs)) {
	//print "no record or error accessing the database\r\n";
	exit(-1);
} // end if

// scan database entries
for ($i=0; $i<count($ex_recs); $i++)
{
	$dataexpe  = $ex_recs[$i]->start_date;
	$expeid    = $ex_recs[$i]->experiment_id;
	newtrial($expeid); 
}
?>