<?php
include_once("../inc/config.php");
include_once("../lib/twilio.php");
include_once("../classes/database.php");

// test if a phone number is currently been called
function test_not_in_progress($p1,$p2="",$p3="") {
	$client = new Services_Twilio(SID, TOKEN);
	$resultValue = 1;
	$p1 = reformat_phone_number($p1);
	$p2 = reformat_phone_number($p2);
	$p3 = reformat_phone_number($p3);
	
	// Loop over the list of calls and echo a property for each one
	foreach($client->account->calls->getIterator(0,50,array("Status" => "in-progress")) as $call) {
		$phone = $call->to;
		if ($phone == $p1) $resultValue = 0;
		if ($phone == $p2) $resultValue = 0;
		if ($phone == $p3) $resultValue = 0;
	}
	return $resultValue;
}

// This function suppress long calls
// this function is simpler than the one below and does not require a database
function suppress_long_calls($maxDuration = 105) {
	
	$client = new Services_Twilio(SID, TOKEN);
	// Loop over the list of calls and echo a property for each one
	$breakinloop = 0;
	foreach($client->account->calls->getIterator(0,50,array("Status" => "in-progress")) as $call) {
		$start_time = $call->start_time;
		$start_time = strtotime($start_time);
		$timeNow    = strtotime(date("Y-m-d G:i:s"));
		$differenceInSeconds = $timeNow - $start_time;
		$callto     = $call->to;
		
		if ($differenceInSeconds > $maxDuration) {
			print "Caller to $callto time limit reached $differenceInSeconds seconds\r\n";
			$call->update(array("Url" => "http://54.186.177.103/telepathyexperiment/ions/ions_duration_exit.php","Method" => "POST"));
			$breakinloop = 1;
			break;
		}
		
		if ($breakinloop) suppress_long_calls($maxDuration);
		
		/*print "***************************************\r\n";
		print "From:     $call->from\r\n";
		print "To:       $call->to\r\n";
		print "Start:    $call->start_time ($start_time)\r\n";
		print "Now:      $timeNow\r\n";
		print "Diff:     $differenceInSeconds\r\n";
		print "End:      $call->end_time\r\n";
		print "Answered: $call->answered_by\r\n"; */
		//$call->hangup();
	}
}	

// this function suppress long calls by keeping track of them in a database
// this function works by itself (no need to have other function modify the database)
function suppress_long_calls_database($maxDuration = 105) {
	
	// To create the database
	// CREATE TABLE tele_currentlycalled (phone CHAR(20), start_time CHAR(20), callid CHAR(40));

	// No other function modify this table of the database
	
	// create database
	$db = new database();
	$db->dblink();
		
	// update calls in the database - end all calls longer than 105 seconds
	$client = new Services_Twilio(SID, TOKEN);
	$deleteCallId = "";
	foreach($client->account->calls->getIterator(0,50,array("Status" => "in-progress")) as $call) {
		$callid    = $call->sid;
		$deleteCallId = $deleteCallId."callid<>'{$callid} AND ";
		$result    = $db->get_recs("tele_currentlycalled", "start_time", "callid='{$callid}'");
		$recs = $db->fetch_objects($result);
		if (count($recs) > 0) {
			$timeStart = strtotime($recs[0]->start_time);
			$timeNow   = strtotime(date("Y-m-d G:i:s"));
			$differenceInSeconds = $timeNow - $timeStart;
			if ($differenceInSeconds > $maxDuration) {
				$db->db_delete("tele_currentlycalled", "callid='{$callid}'");
				print "Caller ID $callid time limit reached $differenceInSeconds seconds\r\n";
				$call->update(array("Url" => "http://54.186.177.103/telepathyexperiment/ions/ions_duration_exit.php","Method" => "POST"));
			}
			else print "Caller ID $callid time limit not reached $differenceInSeconds seconds\r\n";
		}
		else {
			$dateValue = date("Y-m-d G:i:s");
			$phoneNumber = $call->phone_number_sid;
			$db->db_insert("tele_currentlycalled", "phone,callid,start_time","'{$phoneNumber}','{$callid}','{$dateValue}'");
			print "Caller ID $callid not found in currently called database, now adding it\r\n";
		}
	}
	if (!empty($deleteCallId)) $db->db_delete("tele_currentlycalled", substr($deleteCallId, 1, strlen($deleteCallId-4)));
	else                       $db->db_delete("tele_currentlycalled", "phone<>''"); // delete all
}

// add to the list of phone currently being called
/*function add_currently_called_phone($p1, $call) {
	$dateValue = date("Y-m-d G:i:s");
    $db = new database();
    $db->dblink();
	$result = $db->db_insert("tele_currentlycalled",
							 "phone,
							  start_time,
							  callid",
							 "'{$p1}',
							  '{$dateValue}',
							  '{$call}'");
}

function test_if_phones_available($p1,$p2,$p3) {
	$resultValue = 1;
    $db = new database();
    $db->dblink();
    $result = $db->get_recs("tele_currentlycalled", "*", "phone='{$p1}' OR phone='{$p2}' OR phone='{$p3}'");
    $recs = $db->fetch_objects($result);
	
	if (count($recs) > 0) $resultValue = 0;
	return $resultValue;
}*/

?>