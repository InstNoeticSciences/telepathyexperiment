<?php
include_once("../inc/config.php");
include_once("../lib/twilio.php");
include_once("../classes/database.php");

function get_trial_id($expe_id, $trial) {
    $db = new database();
    $db->dblink();

    $result = $db->get_recs("tele_trials", "id", "experiment_id='{$expe_id}' AND trial_num='{$trial}'");
    $recs = $db->fetch_objects($result);
	return $recs[0]->id;
}

// add to the list of phone currently being called
function add_currently_called_phone($p1, $call) {
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

// test if all phones are available	
function test_if_phones_available($p1,$p2,$p3) {
	$resultValue = 1;
    $db = new database();
    $db->dblink();
    $result = $db->get_recs("tele_currentlycalled", "*", "phone='{$p1}' OR phone='{$p2}' OR phone='{$p3}'");
    $recs = $db->fetch_objects($result);
	
	if (count($recs) > 0) $resultValue = 0;
	return $resultValue;
}

//check_informed_consent
function set_informed_consent($phone, $status) {
	$resultValue = 1;
    $db = new database();
    $db->dblink();
    $db->db_update("tele_informedconsent", "status='{$status}'", "phone='{$phone}'");
}

// increment call informed consent
function inc_call_informed_consent($phone) {
	print "Incrementing call for $phone\r\n";
    $db = new database();
    $db->dblink();
    $db->db_update("tele_informedconsent", "number_of_calls=number_of_calls+1", "phone='{$phone}'");
    $result = $db->get_recs("tele_informedconsent", "status", "phone='{$phone}'");
    $recs = $db->fetch_objects($result);	
	$numcalls = $recs[0]->number_of_calls;
	return $numcalls;
}

// check informed consent
function check_informed_consent($phone) {
    $db = new database();
    $db->dblink();
    $result = $db->get_recs("tele_informedconsent", "status", "phone='{$phone}'");
    $recs = $db->fetch_objects($result);	
	if (count($recs) > 0)
		$status = $recs[0]->status;
	else {
		$status = 'notset';
	    $db->db_insert("tele_informedconsent", "phone,status,number_of_calls", "'{$phone}','{$status}',0");
	}
	return $status;
}

function insert_tele_trial($expe_id,$trial,$p1,$p2,$p3,$ph1,$ph2,$ph3,$a1,$a2,$a3) {
    $db = new database();
    $db->dblink();
	$dateValue = date("Y-m-d G:i:s");
	$result = $db->db_insert("tele_trials",
							 "trial_num,
							  experiment_id,
							  start_date,
							  end_date,
							  participant_1,
							  participant_2,
							  participant_3,
							  phone_1,
							  phone_2,
							  phone_3,
							  guess_1,
							  guess_2,
							  guess_3,
							  actual_1,
							  actual_2,
							  actual_3,
							  status,
							  hit",
							 "'{$trial}',
							  '{$expe_id}',
							  '{$dateValue}',
							  '',
							  '{$p1}',
							  '{$p2}',
							  '{$p3}',
							  '{$ph1}',
							  '{$ph2}',
							  '{$ph3}',
							  '',
							  '',
							  '',
							  '{$a1}',
							  '{$a2}',
							  '{$a3}',
							  '{0}',
							  ''");
}

function get_user_username($username) {
    $db = new database();
    $db->dblink();

    $result = $db->get_recs("users", "*", "username='{$username}'");
    $recs = $db->fetch_objects($result);

    // return null if no user found with that pin
    if(!is_array($recs)) {
        return null;
    } // end if

    // create and return the user object
    return(new user($recs[0]->first_name,
                    $recs[0]->last_name,
                    $recs[0]->username,
                    $recs[0]->password,
                    $recs[0]->gender,
                    $recs[0]->phone,
                    $recs[0]->email,
                    $recs[0]->admin,
                    $recs[0]->age,
                    $recs[0]->pin,
                    $recs[0]->group_name));
} // end get_user_pin()

function get_num_trials_from_experimentid($extension) {
    $db = new database();
    $db->dblink();

    // read the experiment data
    $ex_select = $db->get_recs("tele_trials", "*", "experiment_id='$extension'");
	print_r($ex_select);
	if (empty($ex_select)) { return 0; }
							   
    $ex_recs = $db->fetch_objects($ex_select);

    // return null if nothing found
    if(!is_array($ex_recs)) {
        return 0;
    } // end if
	
	return count($ex_recs);
}

function get_experiment_fromid($extension) {
    $db = new database();
    $db->dblink();

    // read the experiment data
    $ex_select = $db->get_recs("experiments",
                               "*",
                               "experiment_id='$extension'");
    $ex_recs = $db->fetch_objects($ex_select);

    // return null if nothing found
    if(!is_array($ex_recs)) {
        return null;
    } // end if

    // create the experimenter
    $us_select = $db->get_recs("users",
                               "*",
                               "username='{$ex_recs[0]->experimenter}'");
    $us_recs = $db->fetch_objects($us_select);

    // return null if no user found
    if(!is_array($us_recs)) {
        return null;
    } // end if

    // create the user
    $user = new user($us_recs[0]->first_name,
                     $us_recs[0]->last_name,
                     $us_recs[0]->username,
                     $us_recs[0]->password,
                     $us_recs[0]->gender,
                     $us_recs[0]->phone,
                     $us_recs[0]->email,
                     $us_recs[0]->admin,
                     $us_recs[0]->age,
                     $us_recs[0]->pin,
                     $us_recs[0]->group_name);

    // create the experiment object
    $experiment = new experiment($user,
                                 $ex_recs[0]->start_date,
                                 $ex_recs[0]->start_time,
                                 $ex_recs[0]->end_date,
                                 $ex_recs[0]->end_time,
                                 $ex_recs[0]->trial_count,
                                 $ex_recs[0]->num_hits,
                                 $ex_recs[0]->status);
    
    // return the experiment object
    return $experiment;
} // end get_experimenter()

?>