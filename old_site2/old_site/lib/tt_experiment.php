<?php
class experiment {
    private $experiment_id;
    private $participants = array();
    private $maximum_part;
    private $initiator_name;
    private $initiator_phone;
    private $caller_name;
    private $caller_phone;
    private $chosen_user;
    private $trial;
    private $hit;

    private $start_date_time;
    private $end_date_time;

    // NAME         -> experiment()
    // PARAMETERS   -> $user_names: array of revou user names
    //              -> $max_paricipants: maximum number of participants
    //              -> $init_user_name: user name of the initiator
    //              -> $init_user_phone: phone number of the initiator
    //              -> $experiment_id: id of the experiment
    //              -> $trial: trial number
    // DESCRIPTION  -> constructor for the experiment object
    // RETURNS      -> true: object created, false: object not created
    function experiment($user_names,
                        $max_participants,
                        $init_user_name,
                        $init_user_phone) {

        if(is_array($user_names) && count($user_names) == $max_participants) {
            $this->set_experiment_id();
            $this->initiator_name = $init_user_name;
            $this->initiator_phone = $init_user_phone;
            $this->participants = $user_names;
            $this->maximum_part = $max_participants;
            $this->start_date_time = date("Y-m-d H:i:s", time());
            $this->trial = 1;
            $this->hit = 0;
            return true;
        } else {
            return false;
        } // end if
    } // end experiment()

    // NAME         -> set_experiment_id()
    // DESCRIPTION  -> set the next experiment id
    private function set_experiment_id() {
        $db = new database();
        $db->dblink();

        // get the most recent experiment id
        $result = $db->get_recs("experiments", "*", "experiment_id > 0", "experiment_id asc");
        $recs = $db->fetch_objects($result);
        
        if(is_array($recs)) {
            // increment the latest experiment id to derive this one
            $this->experiment_id = $recs[count($recs)-1]->experiment_id;
            $this->experiment_id++;
        } else {
            // this is the first experiment
            $this->experiment_id = 1;
        } // end if
    } // end set_experiment_id()

    // NAME         -> get_experiment_id()
    // DESCRIPTION  -> get the experiment id
    // RETURNS      -> the experiment id
    public function get_experiment_id() {
        return $this->experiment_id;
    } // end get_experiment_id()

    // NAME         -> select_caller()
    // DESCRIPTION  -> randomly select a caller from the participants list
    // RETURNS      -> the user name of the selected caller
    public function select_caller() {
        $i = rand(0, count($this->participants) - 1);
        return($this->participants[$i]);
    } // end select_caller()

    // NAME         -> set_caller()
    // DESCRIPTION  -> sets the caller details
    // PARAMETERS   -> $call_user_name: caller's user name
    //              -> $call_user_phone: caller's phone number
    public function set_caller($call_user_name, $call_user_phone) {
        $this->caller_name = $call_user_name;
        $this->caller_phone = $call_user_phone;
    } // end set_caller()

    // NAME         -> set_chosen_user()
    // DESCRIPTION  -> sets the chosen user name
    // PARAMETERS   -> $chosen_user_name: chosen user name
    public function set_chosen_user($chosen_user_name) {
        $this->chosen_user = $chosen_user_name;
    } // end set_chosen_user()

    // NAME         -> get_chosen_user()
    // DESCRIPTION  -> get the chosen user name
    // RETURNS      -> the chosen user name
    public function get_chosen_user() {
        return($this->chosen_user);
    } // end get_chosen_user()

    // NAME         -> get_caller_name()
    // DESCRIPTION  -> get the user name of the caller
    // RETURNS      -> the user name of the caller
    public function get_caller_name() {
        return($this->caller_name);
    } // end get_caller()

    // NAME         -> get_caller_phone()
    // DESCRIPTION  -> get the phone number of the caller
    // RETURNS      -> the phone number of the caller
    public function get_caller_phone() {
        return $this->caller_phone;
    } // end get_caller()
    //
    // NAME         -> get_initiator_name()
    // DESCRIPTION  -> get the user name of the initiator
    // RETURNS      -> the user name of the initiator
    public function get_initiator_name() {
        return $this->initiator_name;
    } // end get_caller()

    // NAME         -> get_initiator_phone()
    // DESCRIPTION  -> get the phone number of the initiator
    // RETURNS      -> the phone number of the initiator
    public function get_initiator_phone() {
        return $this->initiator_phone;
    } // end get_caller()

    // NAME         -> notify_caller()
    // PARAMETERS   -> $call_user: recipient of the notification message
    //              -> $message: text of the notification message
    // DESCRIPTION  -> notify the caller via sms that they must call the initiator
    // RETURNS      -> true: message sent; false: message not sent
    public function notify_caller($call_user, $message) {
        return(new sms($call_user->carrier_id, $call_user->phone, 'Telephone Telepathy', $message));
    } // end notify_caller()

    // NAME         -> caller_match()
    // PARAMETERS   -> $chosen_username: user name of the chosen caller
    // DESCRIPTION  -> check if the chosen caller matches the actual caller
    // RETURNS      -> true: caller matches, false: caller does not match
    public function caller_match() {
        if(strcmp($this->get_chosen_user(), $this->get_caller_name()) == 0) {
            $this->hit = 1;
            return true;
        } else {
            $this->hit = 0;
            return false;
        } // end if
    } // end caller_match()

    // NAME         -> close_experiment()
    // PARAMETERS   -> $db: database object for writing results
    //              -> $message: error message text (if applicable)
    // DESCRIPTION  -> write the experiment results to the database and clean up
    // RETURNS      -> true: close successful, false: close failed
    public function close_trial() {
        // get a database instance
        $db = new database();
        $db->dblink();

        // insert the data for this trial
        if($db) {
            $this->end_date_time = date("Y-m-d H:i:s", time());
            $db_insert = $db->db_insert("experiments",
                                        "experiment_id,
                                         trial,
                                         initiator,
                                         participant_1,
                                         participant_2,
                                         participant_3,
                                         participant_4,
                                         start_date_time,
                                         end_date_time,
                                         phone,
                                         caller_guess,
                                         caller_actual,
                                         hit",
                                        "'{$this->experiment_id}',
                                         '{$this->trial}',
                                         '{$this->initiator_name}',
                                         '{$this->participants[0]}',
                                         '{$this->participants[1]}',
                                         '{$this->participants[2]}',
                                         '{$this->participants[3]}',
                                         '{$this->start_date_time}',
                                         '{$this->end_date_time}',
                                         '{$this->initiator_phone}',
                                         '{$this->chosen_user}',
                                         '{$this->caller_name}',
                                         '{$this->hit}'");
            if(!$db_insert) {
                return false;
            } else {
                return true;
            } // end if
        } else {
            return false;
        } // end if
    } // end close_experiment()

    // NAME         -> next_trial()
    // DESCRIPTION  -> increment the trial number
    public function next_trial() {
        $this->trial++;
    } // end next_trial()

    // NAME         -> get_trial()
    // DESCRIPTION  -> get the trial number
    // RETURNS      -> the trial number
    public function get_trial() {
        return $this->trial;
    } // end get_trial()

    // NAME         -> get_participants()
    // DESCRIPTION  -> get the participants
    // RETURNS      -> the participants array
    public function get_participants() {
        return $this->participants;
    } // end get_participants()

    // NAME         -> get_start_time()
    // DESCRIPTION  -> get the start date/time
    // RETURNS      -> the start date/time
    public function get_start_time() {
        return $this->start_date_time;
    } // end get_start_time()

    // NAME         -> get_finish_time()
    // DESCRIPTION  -> get the finish date/time
    // RETURNS      -> the finish date/time
    public function get_finish_time() {
        return $this->end_date_time;
    } // end get_finish_time()
} // end experiment
?>
