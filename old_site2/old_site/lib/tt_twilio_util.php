<?php
    // constant definitions used by this file
    define('TWILIO_PHONE', '(415) 599-2671');
    define('TWILIO_PIN', '9901-9240');
    define('FIRST_EXTENSION', 1000);
    define('LAST_EXTENSION', 9999);
    
    // NAME         -> call_queue_create()
    // DESCRIPTION  -> create an entry in the call queue
    // PARAMETERS   -> $experiment_id: the experiment number
    //              -> $trial: the trial number
    //              -> $username: the name of the receiver
    //              -> $phone: the phone number of the receiver
    //              -> $caller: the user id of the caller
    // RETURNS      -> the receiver's extension
    function call_queue_create($experiment_id, 
                               $trial,
                               $username,
                               $phone,
                               $caller) {
        $extension = extension_generate();

        // exit if an extension couldn't be generated
        if($extension <= 0) {
            return 0;
        } // end if

        $db = new database();
        $db->dblink();

        // generate the call queue entry
        $result = $db->db_insert("call_queue",
                                 "extension,
                                  experiment_id,
                                  trial,
                                  phone,
                                  username,
                                  caller",
                                 "'{$extension}',
                                  '{$experiment_id}',
                                  '{$trial}',
                                  '{$phone}',
                                  '{$username}',
                                  '{$caller}'");

        // return the extension or 0 if there was a problem
        return $extension;
    } // end call_queue_create()

    // NAME         -> call_queue_poll()
    // DESCRIPTION  -> polls the call queue for an experiment/trial
    // PARAMETERS   -> $extension: the extension number
    // RETURNS      -> the receiver's phone number or 0
    function call_queue_poll($extension) {
        $db = new database();
        $db->dblink();

        $result = $db->get_recs("call_queue",
                                "phone",
                                "extension='{$extension}'");

        $recs = $db->fetch_objects($result);

        if(is_array($recs)) {
            return $recs[0]->phone;
        } else {
            return $recs->phone;
        } // end if

        return 0;
    } // end call_queue_poll()

    // NAME         -> call_queue_delete()
    // DESCRIPTION  -> deletes a record from the call queue
    // PARAMETERS   -> $extension: the extension number
    // RETURNS      -> true: delete ok, false: delete failed
    function call_queue_delete($extension) {
        $db = new database();
        $db->dblink();

        $result = $db->db_delete("call_queue", "extension='{$extension}'");

        if($result) {
            return true;
        } // end if

        return false;
    } // end call_queue_delete()

    // NAME         -> extension_generate()
    // DESCRIPTION  -> generate an extension number for the receiver
    // RETURNS      -> an extension number or 0 if there are none available
    function extension_generate() {
        $extension = FIRST_EXTENSION;

        $db = new database();
        $db->dblink();

        $result = $db->get_recs("call_queue", "extension");
        $calls = $db->fetch_objects($result);

        if(is_array($calls)) {
            // several extensions are active
            sort($calls);
            $size = count($calls);

            if($calls[0]->extension < LAST_EXTENSION) {
                $extension = $calls[$size - 1]->extension + 1;
            } // end if
        } // end if

        return $extension;
    } // end extension_generate()

    // NAME         -> caller_chosen()
    // DESCRIPTION  -> indicate that a caller has been chosen in the queue
    // PARAMETERS   -> $extension: the extension number
    // RETURNS      -> true: update ok, false: update failed
    function caller_chosen($extension) {
        $db = new database();
        $db->dblink();

        $result = $db->db_update("call_queue",
                                 "guessed='Y'",
                                 "extension='{$extension}'");

        if(!$result) {
            return false;
        } // end if

        return true;
    } // end caller_chosen()

    // NAME         -> is_caller_chosen()
    // DESCRIPTION  -> checks the call queue to see if a guess has been made
    // PARAMETERS   -> $extension: the extension number
    // RETURNS      -> true: guess made, false: guess not made
    function is_caller_chosen($extension) {
        $db = new database();
        $db->dblink();

        $result = $db->get_recs("call_queue",
                                "guessed",
                                "extension='{$extension}'");

        $recs = $db->fetch_objects($result);

        if(is_array($recs)) {
            return($recs[0]->guessed == 'Y' ? true : false);
        } else {
            return($recs->guessed == 'Y' ? true : false);
        } // end if
    } // end is_caller_chosen()

    // NAME         -> send_call_reminder
    // DESCRIPTION  -> remind the caller to call the experimenter again
    // PARAMETERS   -> $extension: the extension number
    // RETURNS      -> true: message sent, false: message not sent
    function send_call_reminder($extension) {
        $db = new database();
        $db->dblink();

        $carrier_id = null;
        $caller = null;
        $phone = null;

        // get the user name of the caller
        $result = $db->get_recs("call_queue",
                                "caller",
                                "extension='{$extension}'");

        $recs = $db->fetch_objects($result);

        if(is_array($recs)) {
            $caller = $recs[0]->caller;
        } else {
            $caller = $recs->caller;
        } // end if

        // get the phone number and provider of the caller
        $result = $db->get_recs("users",
                                "phone, carrier_id",
                                "username='{$caller}'");

        $recs = $db->fetch_objects($result);

        if(is_array($recs)) {
            $phone = $recs[0]->phone;
            $carrier_id = $recs[0]->carrier_id;
        } else {
            $phone = $recs->phone;
            $carrier_id = $recs->carrier_id;
        } // end if

        $subject = telepathy_experiment_call_was_not_connected;
        $message = please_call.TWILIO_PHONE.extension.$extension;
        $message.= again_as_no_guess_was_entered_prior_to_connection;
        $message.= of_the_previous_call;
        
        // send the reminder
        return(new sms($carrier_id, $phone, $subject, $message));
    } // end send_call_reminder()
?>
