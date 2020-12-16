<?php
// menu options
define('MENU_MAIN', '1');
define('MENU_EXPERIMENTER', '2');
define('MENU_PARTICIPANT', '3');
define('MENU_EXPERIMENT', '4');
define('MENU_CANCEL', '5');
define('START_EXPERIMENT', '1');
define('RETURN', '2');

// REST API access
define('REST_API', '2010-04-01');
define('REST_SID', 'AC0add840bb3a10cfa05b1bc6ba673d458');
define('REST_TOKEN', '6e8161deb8882445bc4a7a46970d8a35');

// call statuses
define('CALL_COMPLETED', "completed");

/**
 * get the user details from their pin number
 * @param   string  $pin        the entered pin number
 * @return  object  user|null
 */
function get_user_pin($pin) {
    $db = new database();
    $db->dblink();

    $result = $db->get_recs("users", "*", "pin='{$pin}'");
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
                    $recs[0]->pin));
} // end get_user_pin()

/*
 * get the experiment object for an extension
 * @param   int $extension  extension number for a trial
 * @return  object|null experiment or null
 */
function get_experiment_extension($extension) {
    $db = new database();
    $db->dblink();

    // get the trial record
    $tr_select = $db->get_recs("trials",
                               "experiment_id",
                               "extension='{$extension}' and (
                                status='".NOT_STARTED."' or
                                status='".IN_PROGRESS."')");

    $tr_recs = $db->fetch_objects($tr_select);

    // return null if nothing found
    if(!is_array($tr_recs)) {
        return null;
    } // end if

    // read the experiment data
    $ex_select = $db->get_recs("experiments",
                               "*",
                               "experiment_id='{$tr_recs[0]->experiment_id}'");
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
                     $us_recs[0]->pin);

    // create the experiment object
    $experiment = new experiment($user,
                                 $ex_recs[0]->start_date,
                                 $ex_recs[0]->start_time,
                                 $ex_recs[0]->end_date,
                                 $ex_recs[0]->end_time,
                                 $ex_recs[0]->trial_count,
                                 $ex_recs[0]->num_hits,
                                 $ex_recs[0]->status);

    // set the experiment id and read its trials
    $experiment->set_experiment_id($tr_recs[0]->experiment_id);
    $experiment->read_trials();
    
    // return the experiment object
    return $experiment;
} // end get_experimenter()
?>