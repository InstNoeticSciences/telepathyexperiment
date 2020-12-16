<?php
    // constant definitions used by this file
    define('ERROR', '1');
    define('WARNING', '2');
    define('INFORMATION', '3');
    define('ACTION', '4');
    define('MAX_PARTICIPANTS', '4');
    define('MAX_MESSAGES', '40');
    define('MAX_TRIALS', '8');

    define('MOST_HITS', '1');
    define('LEAST_HITS', '2');

    define('GOOGLE_MAPS_KEY', "ABQIAAAALzxZxZULX9-oXnRMvB1RvxS-ppMTo74UK5LP65eOUWuzYEClfBQGLwC_uVDcU5xIveNkvCVKbhGwCA");

    // NAME         -> status_message()
    // DESCRIPTION  -> writes a message to the status[] element in $_POST
    // PARAMETERS   -> $message_type: '1' for error
    //                                '2' for warning
    //                                '3' for information (def)
    //              -> $show_timestamp: 'true' to show
    //                                  'false' to hide
    // RETURNS      -> nothing
    function status_message($message_type, $message_text, $show_timestamp = false) {
        // only show the time stamp if requested
        if($show_timestamp) {
            $stamp = date("D M d, Y G:i a");
        } // end if

        switch($message_type) {
            case ERROR:
                $stamp .= ' [Error]';
                break;
            case WARNING:
                $stamp .= ' [Warning]';
                break;
            case INFORMATION:
                $stamp .= ' [Information]';
                break;
            case ACTION:
                $stamp .= ' [Action]';
                break;
            default:
                $stamp .= ' [Information]';
                break;
        } // end switch
        
        // clear out old messages
        if($_POST['status_idx'] >= MAX_MESSAGES) {
            for($i = 0; $i < $_POST['status_idx']; $i++) {
                unset($_POST['status'][$i]);
            } // end for

            $_POST['status_idx'] = 0;
        } // end if

        // append the latest message
        $_POST['status'][$_POST['status_idx']] = $stamp.' '.$message_text;
        $_POST['status_idx']++;
    } // end status_message()

    // NAME         -> transfer_participants()
    // DESCRIPTION  -> transfers selected friends to the participants list
    // RETURNS      -> number of participants transferred
    function transfer_participants() {
        $num_selected = count($_POST['my_friends']);
        $num_participants = $_POST['part_idx'];
        $num_available = MAX_PARTICIPANTS - $num_participants;

        // cannot transfer if all available places are taken
        if($num_available < $num_selected) {
            status_message(ERROR, only.$num_available.places_remain);
            return 0;
        } // end if

        // move to the participants list
        for($i = 0; $i < $num_selected; $i++) {
            $_POST['part_list'][$_POST['part_idx']] = $_POST['my_friends'][$i];
            $_POST['friend_idx']--;
            $_POST['part_idx']++;

            // remove the participant from the pool
            $key = array_search($_POST['my_friends'][$i], $_POST['friend_list']);
            unset($_POST['friend_list'][$key]);
        } // end for

        // transfer complete
        return $num_selected;
    } // end transfer_participants()

    // NAME         -> reject_participants()
    // DESCRIPTION  -> rejects selected participants from the participants list
    // RETURNS      -> number of participants rejected
    function reject_participants() {
        $num_selected = count($_POST['sel_friends']);

        // move back to the friends list
        for ($i = 0; $i < $num_selected; $i++) {
            $_POST['friend_list'][$_POST['friend_idx']] = $_POST['sel_friends'][$i];
            $_POST['friend_idx']++;
            $_POST['part_idx']--;
            
            // remove the friend from the participant list
            $key = array_search($_POST['sel_friends'][$i], $_POST['part_list']);
            unset($_POST['part_list'][$key]);
        } // end for

        // transfer complete
        return $num_selected;
    } // end reject_participants()

    // NAME         -> return_participants()
    // DESCRIPTION  -> returns all participants to the friends list
    function return_participants() {
        $num_participants = count($_POST['part_list']);

        for($i = 0; $i < $num_participants; $i++) {
            $_POST['friend_list'][$_POST['friend_idx']] = $_POST['part_list'][$i];
            $_POST['friend_idx']++;
            $_POST['part_idx']--;

            // remove the friend from the participant list
            unset($_POST['part_list'][$i]);
        } // end for
    } // end return_participants()

    // NAME         -> clear_status()
    // DESCRIPTION  -> empties the status box
    function clear_status() {
        $num_status = count($_POST['status']);

        for($i = 0; $i < $num_status; $i++) {
            unset($_POST['status'][$i]);
            $_POST['status_idx']--;
        } // end for
    } // end clear_status()

    // NAME         -> button_status()
    // DESCRIPTION  -> disables or enables experiment-related pushbuttons
    // PARAMETERS   -> $smarty: smarty object
    //              -> $button: name of the button
    //              -> $disable: true or false
    // RETURNS      -> nothing
    function button_status($smarty, $button, $disable) {
        $smarty->assign($button, $disable);
    } // end button_status()

    // NAME         -> call_js()
    // DESCRIPTION  -> used to call a javascript function
    // PARAMETERS   -> $js_string: a javascript command/call
    function call_js($js_string) {
        echo "<script language='javascript'>$js_string</script>";
    } // end call_js()

    // NAME         -> set_experiment_texts()
    // DESCRIPTION  -> set up step-dependent experiment texts
    // PARAMETERS   -> $smarty: smarty object
    //              -> $step_number: step number
    // RETURNS      -> nothing
    function set_experiment_texts($smarty, $step_number) {
        switch($step_number) {
            case '1':
                // step 1: select participants
                $smarty->assign('experiment_step_title', step_1_select_participants);
                break;
            case '2':
                // step 2: run trials
                $smarty->assign('experiment_step_title', step_2_run_trials);
                break;
            default:
                break;
        } // end switch
    } // end set_experiment_texts()

    // NAME         -> count_my_hits()
    // DESCRIPTION  -> counts the number of hits in a list of experiments
    // PARAMETERS   -> $user: revou user name
    //              -> $experiments: an array of experiment records
    // RETURNS      -> the number of hits
    function count_my_hits($user = '', $experiments = null) {
        $hit_count = 0;

        if(is_array($experiments)) {
            foreach($experiments as $exp) {
                if($user != '' && strcmp($exp->initiator, $user) == 0) {
                    if($exp->hit == 1) {
                        $hit_count++;
                    } // end if
                } else {
                    if($exp->hit == 1) {
                        $hit_count++;
                    } // end if
                } // end if
            } // end foreach
        } // end if

        return $hit_count;
    } // end count_my_hits()

    // NAME         -> count_my_trials()
    // DESCRIPTION  -> counts the number of trials for a user
    // PARAMETERS   -> $user: revou user name
    //              -> $experiments: an array of experiment records
    // RETURNS      -> the number of trials
    function count_my_trials($user = '', $experiments = null) {
        $trial_count = 0;

        if(is_array($experiments)) {
            if($user != '') {
                foreach($experiments as $exp) {
                    if(strcmp($exp->initiator, $user) == 0) {
                        $trial_count++;
                    } // end if
                } // end foreach
            } else {
                $trial_count = count($experiments);
            } // end if
        } // end if

        return $trial_count;
    } // end count_my_trials()

    // NAME         -> percentage()
    // DESCRIPTION  -> calculates one number as a percentage of the other
    // PARAMETERS   -> $total: the total count
    //              -> $portion: the counted amount
    //              -> $precision: rounding precision for the result
    // RETURNS      -> portion as a rounded percentage of the total
    function percentage($total, $portion, $precision) {
        $percentage = 0;

        if($total > 0) {
            $percentage = ($portion / $total) * 100;
        } // end if

        return round($percentage, $precision);
    } // end percentage()

    // NAME         -> boundary_caller()
    // DESCRIPTION  -> find the user names of the callers with the least/most hits
    // PARAMETERS   -> $experiments: list of experimental results
    //              -> $friends: list of user names
    //              -> $boundary: flag indicating least/most hit callers
    //              -> $return: number of users to return
    // RETURNS      -> user names of the callers with the least/most hits
    function boundary_caller($experiments, $friends, $boundary, $return) {
        $all_hits = array();
        $callers = array();
        $hits = 0;

        // collect all of the hits per user
        if(is_array($friends) && is_array($experiments)) {
            foreach($friends as $frn) {
                foreach($experiments as $exp) {
                    if(strcmp($exp->caller_actual, $frn->username) == 0 && $exp->hit == 1) {
                        $hits++;
                    } // end if
                } // end foreach

                $all_hits[$frn->username] = $hits;
                $hits = 0;
            } // end foreach
        } // end if

        // determine the target hit count (least or most)
        switch($boundary) {
            case LEAST_HITS:
                asort($all_hits);
                break;
            case MOST_HITS:
                arsort($all_hits);
                break;
            default:
                arsort($all_hits);
                break;
        } // end switch
        
        // collect all users with the target hit count
        $count = count($all_hits);

        // return up to the specified number of callers
        for($i = 0; $i < $count && $i < $return; $i++) {
            $callers[] = key($all_hits).'('.current($all_hits).')';
            next($all_hits);
        } // end for
        
        return $callers;
    } // end boundary_caller()

    // NAME         -> clean_data()
    // DESCRIPTION  -> remove tabs and new line characters from a string
    // PARAMETERS   -> $&str: the string to be cleaned
    // RETURNS      -> the cleaned string
    function clean_data(&$str) {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
    } // end clean_data

    // NAME         -> download_results()
    // DESCRIPTION  -> download the contents of the experiments table to csv
    // PARAMETERS   -> $query: the selection criteria for experiment records
    // RETURNS      -> true: download successful, false: download failed
    function download_results($query = '') {
        // generate the file name
        $filename = "experiments".date('Ymd').".csv";

        $XML = "Experiment ID,".
               "Trial Number,".
               "Initiator,".
               "Participant 1,".
               "Participant 2,".
               "Participant 3,".
               "Participant 4,".
               "Start Date/Time,".
               "End Date/Time,".
               "Phone Number,".
               "Caller Guess,".
               "Caller Actual,".
               "Hit\n";

        // get a database instance
        $db = new database();
        $db->dblink();

        // read the records
        if($query != '') {
            $result = $db->get_recs("experiments",
                                    "*",
                                    $query,
                                    "experiment_id asc");
        } else {
            $result = $db->get_recs("experiments", "*");
        } // end if

        if($result) {
            $recs = $db->fetch_objects($result);

            foreach($recs as $rec) {
                $XML.=$rec->experiment_id.",";
                $XML.=$rec->trial.",";
                $XML.=$rec->initiator.",";
                $XML.=$rec->participant_1.",";
                $XML.=$rec->participant_2.",";
                $XML.=$rec->participant_2.",";
                $XML.=$rec->participant_4.",";
                $XML.=$rec->start_date_time.",";
                $XML.=$rec->end_date_time.",";
                $XML.=$rec->phone.",";
                $XML.=$rec->caller_guess.",";
                $XML.=$rec->caller_actual.",";
                $XML.=$rec->hit."\n";
            } // end foreach

            $filesize = strlen($XML);

            ob_clean_all();

            // Start sending headers
            safe_header("Pragma: public"); // required
            safe_header("Expires: 0");
            safe_header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            safe_header("Cache-Control: private", false);
            safe_header("Content-Transfer-Encoding: binary");
            safe_header("Content-Type: application/octet-stream");
            safe_header("Content-Length: ".$filesize);
            safe_header("Content-Disposition: attachment; filename=\"".$filename."\";" );

            // Send data
            echo $XML;
            die();
            
            return true;
        } else {
            return false; 
        } // end if
    } // end download_results()

    // NAME         -> debug_message()
    // DESCRIPTION  -> echoes a message to the browser
    // PARAMETERS   -> $message: the message to be displayed
    // RETURNS      -> nothing
    function debug_message($message) {
        echo "<p>$message</p><br>";
    } // end debug_message()

    // NAME         -> safe_header()
    // DESCRIPTION  -> sends a header command
    // PARAMETERS   -> $str: the command to be sent
    // RETURNS      -> true: command sent, false: command not sent
    function safe_header($str) {
        if(!headers_sent()) {
            header($str);
            return true;
        } // end if

        return false;
    } // end safe_header()

    // NAME         -> ob_clean_all()
    // DESCRIPTION  -> clear out the output buffer
    // RETURNS      -> true: buffer clean
    function ob_clean_all () {
        $ob_active = ob_get_length () !== false;

        while($ob_active) {
            ob_end_clean();
            $ob_active = ob_get_length () !== false;
        } // end while
        
        return true;
    } // end ob_clean_all()

    // NAME         -> reset_experiment()
    // DESCRIPTION  -> reset the experiment control variables
    // RETURNS      -> nothing
    function reset_experiment() {
        $_SESSION['new_experiment'] = "true";
        $_SESSION['new_trial'] = "false";
        $_SESSION['step_number'] = 1;
    } // end reset_experiment()

    // NAME         -> hits_for_experiment()
    // DESCRIPTION  -> returns the number of hits for an experiment
    // PARAMETERS   -> $experiment_id: the experiment number
    // RETURNS      -> the number of hits for this experiment
    function hits_for_experiment($experiment_id) {
        $hit_count = 0;

        $db = new database();
        $db->dblink();

        // get the results for this experiment
        $result = $db->get_recs("experiments",
                                "hit",
                                "experiment_id='{$experiment_id}'",
                                "start_date_time desc");

        if($result) {
            $experiments = $db->fetch_objects($result);

            // count the hits
            if(is_array($experiments)) {
                foreach($experiments as $exp) {
                    if($exp->hit == 1) {
                        $hit_count++;
                    } // end if
                } // end foreach
            } // end if
        } // end if

        return $hit_count;
    } // end hits_for_experiment()

    // NAME         -> is_invited()
    // DESCRIPTION  -> checks if an email address exists on the invited list
    // PARAMETERS   -> $email: the email address
    // RETURNS      -> true: email is invited, false: email is not invited
    function is_invited($email) {
        $db = new database();
        $db->dblink();
        
        $result = $db->get_rec("invited_users",
                               "invitee_email",
                               "invitee_email='{$email}'");

        if($result) {
            return true;
        } else {
            return false;
        } // end if
    } // end is_invited()

    // NAME         -> add_invited()
    // DESCRIPTION  -> adds an email to the invitee list
    // PARAMETERS   -> $email: the email address
    //              -> $inviter_username: the user who is sending the invite
    // RETURNS      -> true: email added, false: email not added
    function add_invited($email, $inviter_username) {
        $db = new database();
        $db->dblink();

        $result = $db->db_insert("invited_users",
                                 "invitee_email, inviter_username",
                                 "'{$email}', '{$inviter_username}'");

        if($result) {
            return true;
        } else {
            return false;
        } // end if
    } // end add_invited()

    // NAME         -> del_invited()
    // DESCRIPTION  -> deletes an email from the invitee list
    // PARAMETERS   -> $email: the email address
    // RETURNS      -> true: email removed, false: email not removed
    function del_invited($email) {
        $db = new database();
        $db->dblink();

        $result = $db->db_delete("invited_users", "invitee_email='{$email}'");

        if($result) {
            return true;
        } else {
            return false;
        } // end if
    } // end del_invited()

    // NAME         -> start_message()
    // DESCRIPTION  -> displays a start message for the experiment
    function start_message() {
        if($_POST['friend_idx'] <= 0 && $_POST['part_idx'] <= 0) {
            status_message(ACTION, please_invite_some_friends_to_participate);
        } else {
            status_message(ACTION, please_select_four_friends_to_participate);
        } // end if
    } // end start_message()

    // NAME         -> geocode_curl()
    // DESCRIPTION  -> geocode a location using curl and googlemaps
    // PARAMETERS   -> $key: api key for googlemaps
    //              -> $location: the location to be geocoded
    //              -> $header: 0 for no headers; 1 if header are required
    // RETURNS      -> the geocoded data
    function geocode_curl($key, $location, $header = 0) {
        $address = urlencode($location);
        $url = "http://maps.google.com/maps/geo?q=".$address."&output=csv&key=".$key;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    } // end geocode_curl()
?>