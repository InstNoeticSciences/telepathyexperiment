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
$ex_select = $db->get_recs("users",
						   "*",
						   "");
$ex_recs = $db->fetch_objects($ex_select);

// scan database entries
printf('<html><body><table border=1>');
printf('<tr><th>username</th><th>phone</th><th>email</th><th>first_name</th></tr>');
for ($i=0; $i<count($ex_recs); $i++)
{
	$username     = $ex_recs[$i]->username;
	$phone        = $ex_recs[$i]->phone;
    $email        = $ex_recs[$i]->email;
    $firstname    = $ex_recs[$i]->first_name;
	printf("<tr><td>$username</td><td>$phone</td><td>$email</td><td>$firstname</td></tr>"); 
}
printf('</table></body></html>');
?>