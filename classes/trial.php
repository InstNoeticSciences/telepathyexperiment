<?php
class trial {
    private $experiment_id;
    private $trial_num;
    private $start_date;
    private $start_time;
    private $guess_date;
    private $guess_time;
    private $call_date;
    private $call_time;
    private $end_date;
    private $end_time;
    private $sms_date;
    private $sms_time;
    private $caller_guess;
    private $caller_actual;
    private $caller_phone;
    private $extension;
    private $status;
    private $hit;
    private $id;
    
    private $participants = array();
    
    /**
     * construct a trial
     * @param   int         $experiment_id  id of the experiment
     * @param   int         $trial_num      number of the trial
     * @param   user        $experimenter   user object of experimenter
     * @param   string      $caller_guess   guess for the caller name
     * @param   string      $caller_actual  actual caller name
     * @param   string      $caller_phone   actual caller phone
     * @param   date        $start_date     trial start date
     * @param   time        $start_time     trial start time
     * @param   date        $end_date       trial end date
     * @param   time        $end_time       trial end time
     * @param   date        $sms_date       trial sms date
     * @param   time        $sms_time       trial sms time
     * @param   date        $guess_date     trial guess date
     * @param   time        $guess_time     trial guess time
     * @param   date        $call_date      trial call date
     * @param   time        $call_time      trial call time
     * @param   int         $status         status indicator
     * @param   int         $extension      extension of experimenter
     * @param   char        $hit            hit indicator
     */
    function trial($experiment_id, 
                   $trial_num, 
                   $experimenter,
                   $caller_guess,
                   $caller_actual,
                   $caller_phone,
                   $start_date,
                   $start_time,
                   $end_date,
                   $end_time,
                   $sms_date,
                   $sms_time,
                   $guess_date,
                   $guess_time,
                   $call_date,
                   $call_time,
                   $status,
                   $extension,
                   $hit) {
        $this->experiment_id = $experiment_id;
        $this->trial_num = $trial_num;
        $this->participants = $experimenter->get_friends();
        $this->caller_guess = $caller_guess;
        $this->caller_actual = $caller_actual;
        $this->caller_phone = $caller_phone;
        $this->start_date = $start_date;
        $this->start_time = $start_time;
        $this->end_date = $end_date;
        $this->end_time = $end_time;
        $this->sms_date = $sms_date;
        $this->sms_time = $sms_time;
        $this->guess_date = $guess_date;
        $this->guess_time = $guess_time;
        $this->call_date = $call_date;
        $this->call_time = $call_time;
        $this->status = $status;
        $this->extension = $extension;
        $this->hit = $hit;
    } // end trial()

    /*
     * select the caller
     */
    function select_caller() {
        // select the caller
        $rand = mt_rand(0, MAX_FRIENDS - 1);
        $this->caller_actual = $this->participants[$rand]->friend_name;
        $this->caller_phone = $this->participants[$rand]->phone;
    } // end select_caller()

    /*
     * generate a sequential extension
     */
    function generate_seq_extension() {
        $extension = FIRST_EXTENSION;
        $used = array();

        $db = new database();
        $db->dblink();

        // get all trials
        $result = $db->get_recs("trials", "extension, status");

        $calls = $db->fetch_objects($result);
        $num_calls = count($calls);

        if(is_array($calls)) {
            // get the first available extension
            sort($calls);

            // build the list of used extensions
            for($i = 0; $i < $num_calls; $i++) {
                if($calls[$i]->status == IN_PROGRESS ||
                   $calls[$i]->status == NOT_STARTED) {
                    $used[] = $calls[$i];
                } // end if
            } // end for

            // allocate the first available
            sort($used);
            $num_unavail = count($used);

            // check if the first extension is available
            if($used[0]->extension > FIRST_EXTENSION) {
                $extension = FIRST_EXTENSION;
            } else {
                for($i = 0; $i < $num_unavail; $i++) {
                    $diff = $used[$i + 1]->extension - $used[$i]->extension;

                    // check for a gap in the extensions
                    if($diff > 1) {
                        $extension = $used[$i]->extension + 1;
                        break;
                    } // end if

                    $extension = $used[$i]->extension + 1;
                } // end for
            } // end if
        } // end if

        $this->extension = $extension;
    } // end generate_seq_extension()

    /*
     * generate a random unusued extension number
     */
    function generate_extension() {
        $all = array();
        $used = array();
        $unused = array();

        $db = new database();
        $db->dblink();

        // get all trials
        $result = $db->get_recs("trials", "extension, status");
        $calls = $db->fetch_objects($result);
        $num_calls = count($calls);

        if(is_array($calls)) {
            // build the list of used extensions
            for($i = 0; $i < $num_calls; $i++) {
                if($calls[$i]->status == IN_PROGRESS ||
                   $calls[$i]->status == NOT_STARTED) {
                    $used[] = $calls[$i]->extension;
                } // end if
            } // end for
        } // end if

        if(count($used) <= 0) {
            // there are no used extensions 
            $this->extension = mt_rand(FIRST_EXTENSION, LAST_EXTENSION);
        } else {
            // build a list of all unused extensions
            $n = count($used);

            for($i = FIRST_EXTENSION; $i <= LAST_EXTENSION; $i++) {
                $found = false;

                for($j = 0; $j < $n; $j++) {
                    if($i == $used[$j]) {
                        $found = true;
                        break;
                    } // end if
                } // end for
                
                if(!$found) {
                    $unused[] = $i;
                } // end if
            } // end for
            
            // allocate a random extension
            $this->extension = $unused[mt_rand(0, count($unused))];
        } // end if
    } // end generate_extension()

    /**
     * update the trial record in the database
     * @param   string  $operation  type of update (INSERT|UPDATE|DELETE)
     * @return  boolean true|false
     */
    function db_update($operation) {
        $db = new database();
        $db->dblink();

        switch($operation) {
            case INSERT:
                $result = $db->db_insert("trials",
                                         "experiment_id,
                                          trial_num,
                                          start_date,
                                          start_time,
                                          end_date,
                                          end_time,
                                          sms_date,
                                          sms_time,
                                          guess_date,
                                          guess_time,
                                          call_date,
                                          call_time,
                                          caller_guess,
                                          caller_actual,
                                          caller_phone,
                                          extension,
                                          participant_1,
                                          participant_2,
                                          status,
                                          hit",
                                         "'{$this->experiment_id}',
                                          '{$this->trial_num}',
                                          '{$this->start_date}',
                                          '{$this->start_time}',
                                          '{$this->end_date}',
                                          '{$this->end_time}',
                                          '{$this->sms_date}',
                                          '{$this->sms_time}',
                                          '{$this->guess_date}',
                                          '{$this->guess_time}',
                                          '{$this->call_date}',
                                          '{$this->call_time}',
                                          '{$this->caller_guess}',
                                          '{$this->caller_actual}',
                                          '{$this->caller_phone}',
                                          '{$this->extension}',
                                          '{$this->participants[0]->friend_name}',
                                          '{$this->participants[1]->friend_name}',
                                          '{$this->status}',
                                          '{$this->hit}'");

                if($result != 0) {
                    $this->id = $result;
                    return true;
                } // end if
                break;
            case UPDATE:
                $result = $db->db_update("trials",
                                         "experiment_id='{$this->experiment_id}',
                                          trial_num='{$this->trial_num}',
                                          start_date='{$this->start_date}',
                                          start_time='{$this->start_time}',
                                          end_date='{$this->end_date}',
                                          end_time='{$this->end_time}',
                                          sms_date='{$this->sms_date}',
                                          sms_time='{$this->sms_time}',
                                          guess_date='{$this->guess_date}',
                                          guess_time='{$this->guess_time}',
                                          call_date='{$this->call_date}',
                                          call_time='{$this->call_time}',
                                          caller_actual='{$this->caller_actual}',
                                          caller_phone='{$this->caller_phone}',
                                          caller_guess='{$this->caller_guess}',
                                          extension='{$this->extension}',
                                          participant_1='{$this->participants[0]->friend_name}',
                                          participant_2='{$this->participants[1]->friend_name}',
                                          status='{$this->status}',
                                          hit='{$this->hit}'",
                                         "experiment_id='{$this->experiment_id}' and
                                          trial_num='{$this->trial_num}'");
                                         
                return $result;
                break;
            case DELETE:
                $result = $db->db_delete("trials", "id='{$this->id}'");

                return $result;
                break;
            default:
                break;
        } // end switch

        return false;
    } // end db_update()

    /*
     * determine whether or not a hit has occurred
     * @return  boolean true|false
     */
    function is_hit() {
        if(strcmp($this->caller_actual, $this->caller_guess) == 0) {
            $this->hit = HIT;
            return true;
        } else {
            $this->hit = MISS;
        } // end if

        return false;
    } // end is_hit()

    /*
     * get the experiment id
     * @return  int  experiment id
     */
    function get_experiment_id() {
        return $this->experiment_id;
    } // end get_experiment_id()

    /*
     * get the trial number
     * @return  int  trial number
     */
    function get_trial_num() {
        return $this->trial_num;
    } // end get_trial_num()

    /* 
     * get the extension number 
     * @return  int  extension
     */
    function get_extension() {
        return $this->extension;
    } // end get_extension()

    /*
     * get the start date
     * @return  date    start date
     */
    function get_start_date() {
        return $this->start_date;
    } // end get_start_date()

    /*
     * get the start time
     * @return  date    start time
     */
    function get_start_time() {
        return $this->start_time;
    } // end get_start_time()

    /*
     * get the participants
     * @return  array   participant names
     */
    function get_participants() {
        return $this->participants;
    } // end get_participants()

    /*
     * set the guess for the caller
     * @param   string  $caller_guess   guess for the caller
     */
    function set_caller_guess($caller_guess) {
        $this->caller_guess = $caller_guess;
    } // end set_caller_guess()

    /*
     * get the trial status
     * @return   string  status
     */
    function get_status() {
        return $this->status;
    } // end get_status()

    /*
     * set the trial status
     * @param   int $status status indicator
     */
    function set_status($status) {
        $this->status = $status;
    } // end set_status()

    /*
     * get the actual caller name
     * @return  string  caller name
     */
    function get_caller_actual() {
        return $this->caller_actual;
    } // end get_caller_actual()

    /*
     * get the actual caller phone
     * @return  string  caller phone
     */
    function get_caller_phone() {
        return $this->caller_phone;
    } // end get_caller_phone()
    
    /*
     * set the end date for a trial
     * @param   date    $end_date   the end date
     */
    function set_end_date($end_date) {
        $this->end_date = $end_date;
    } // end set_end_date()

    /*
     * set the end time for a trial
     * @param   time    $end_time   the end time
     */
    function set_end_time($end_time) {
        $this->end_time = $end_time;
    } // end set_end_time()

    /*
     * get the end date for a trial
     * @return  date    the end date
     */
    function get_end_date() {
        return $this->end_date;
    } // end get_end_date()

    /*
     * get the end time for a trial
     * @return  time    the end time
     */
    function get_end_time() {
        return $this->end_time;
    } // end get_end_time()

    /*
     * set the sms time for a trial
     * @param   time    $sms_time   the sms time
     */
    function set_sms_time($sms_time) {
        $this->sms_time = $sms_time;
    } // end set_sms_time()

    /*
     * get the sms time for a trial
     * @return  time    the sms time
     */
    function get_sms_time($sms_time) {
        return $this->sms_time;
    } // end get_sms_time()

    /*
     * set the guess time for a trial
     * @param   time    $guess_time   the guess time
     */
    function set_guess_time($guess_time) {
        $this->guess_time = $guess_time;
    } // end set_guess_time()

    /*
     * get the guess time for a trial
     * @return  time    the guess time
     */
    function get_guess_time($guess_time) {
        return $this->guess_time;
    } // end get_guess_time()

    /*
     * set the call time for a trial
     * @param   time    $call_time   the call time
     */
    function set_call_time($call_time) {
        $this->call_time = $call_time;
    } // end set_call_time()

    /*
     * get the call time for a trial
     * @return  time    the call time
     */
    function get_call_time($call_time) {
        return $this->call_time;
    } // end get_call_time()

    /*
     * set the sms date for a trial
     * @param   date    $sms_date   the sms date
     */
    function set_sms_date($sms_date) {
        $this->sms_date = $sms_date;
    } // end set_sms_date()

    /*
     * get the sms date for a trial
     * @return  date    the sms date
     */
    function get_sms_date($sms_date) {
        return $this->sms_date;
    } // end get_sms_date()

    /*
     * set the guess date for a trial
     * @param   date    $guess_date   the guess date
     */
    function set_guess_date($guess_date) {
        $this->guess_date = $guess_date;
    } // end set_guess_date()

    /*
     * get the guess date for a trial
     * @return  date    the guess date
     */
    function get_guess_date($guess_date) {
        return $this->guess_date;
    } // end get_guess_date()

    /*
     * set the call date for a trial
     * @param   date    $call_date   the call date
     */
    function set_call_date($call_date) {
        $this->call_date = $call_date;
    } // end set_call_date()

    /*
     * get the call date for a trial
     * @return  date    the call date
     */
    function get_call_date($call_date) {
        return $this->call_date;
    } // end get_call_date()
} // end trial
?>