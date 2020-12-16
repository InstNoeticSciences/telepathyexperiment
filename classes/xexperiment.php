<?php
class experiment {
    private $experiment_id;
    private $experimenter;
    private $start_date;
    private $start_time;
    private $end_date;
    private $end_time;
    private $trial_count;
    private $num_hits;
    private $status;

    private $trials = array();

    /**
     * construct an experiment
     * @param   string  $experimenter   user object of experimenter
     * @param   date    $start_date     experiment start date
     * @param   time    $start_time     experiment start time
     * @param   date    $end_date       experiment end date
     * @param   time    $end_time       experiment end time
     * @param   int     $trial_count    number of completed trials
     * @param   int     $num_hits       number of hits
     * @param   int     $status         status indicator
     */
    function experiment($experimenter,
                        $start_date,
                        $start_time,
                        $end_date,
                        $end_time,
                        $trial_count,
                        $num_hits,
                        $status) {
        $this->experimenter = $experimenter;
        $this->start_date = $start_date;
        $this->start_time = $start_time;
        $this->end_date = $end_date;
        $this->end_time = $end_time;
        $this->trial_count = $trial_count;
        $this->num_hits = $num_hits;
        $this->status = $status;
    } // end experiment()

    /*
     * read all trial data for the experiment
     */
    function read_trials() {
        $db = new database();
        $db->dblink();

        $result = $db->get_recs("trials",
                                "*",
                                "experiment_id='{$this->experiment_id}'");

        $recs = $db->fetch_objects($result);

        if(is_array($recs)) {
            $count = count($recs);

            for($i = 0; $i < $count; $i++) {
                $this->trials[] = new trial($recs[$i]->experiment_id,
                                            $recs[$i]->trial_num,
                                            $this->experimenter,
                                            $recs[$i]->caller_guess,
                                            $recs[$i]->caller_actual,
                                            $recs[$i]->caller_phone,
                                            $recs[$i]->start_date,
                                            $recs[$i]->start_time,
                                            $recs[$i]->end_date,
                                            $recs[$i]->end_time,
                                            $recs[$i]->sms_date,
                                            $recs[$i]->sms_time,
                                            $recs[$i]->guess_date,
                                            $recs[$i]->guess_time,
                                            $recs[$i]->call_date,
                                            $recs[$i]->call_time,
                                            $recs[$i]->status,
                                            $recs[$i]->extension,
                                            $recs[$i]->hit);
            } // end for
        } // end if
    } // end read_trials()

    /*
     * Get a trial record given an extension
     * @return  object|null trial object or null
     */
    function get_trial_extension($extension) {
        // if no trials then return null
        if(!is_array($this->trials)) {
            return null;
        } // end if

        $num_trials = count($this->trials);

        for($i = 0; $i < $num_trials; $i++) {
            if($this->trials[$i]->get_extension() == $extension && (
               $this->trials[$i]->get_status() != COMPLETE &&
               $this->trials[$i]->get_status() != ABORTED)) {
                return $this->trials[$i];
            } // end if
        } // end for

        return null;
    } // end get_trial_extension()

    /*
     * check for incomplete experiments
     * @return  boolean true|false
     */
    function has_incomplete_experiments() {
        $db = new database();
        $db->dblink();

        $result = $db->get_recs("experiments",
                                "experiment_id",
                                "experimenter='{$this->experimenter->get_username()}' and ".
                                "(status='".NOT_STARTED."' or ".
                                "status='".IN_PROGRESS."')");

        $recs = $db->fetch_objects($result);
        return(is_array($recs));
    } // end has_incomplete_experiments()

    /**
     * cancel this experiment
     * @return  boolean true|false
     */
    function cancel() {
        $result = false;

        // cancel the experiment
        $this->status = ABORTED;
        $result = $this->db_update(UPDATE);

        // cancel all of the trials
        if(is_array($this->trials)) {
            $count = count($this->trials);

            for($i = 0; $i < $count; $i++) {
                $this->trials[$i]->set_status(ABORTED);
                $this->trials[$i]->db_update(UPDATE);
            } // end for
        } // end if

        return $result;
    } // end cancel()
    
    /**
     * update the experiment record in the database
     * @param   string  $operation  type of update (INSERT|UPDATE|DELETE)
     * @return  boolean true|false
     */
    function db_update($operation) {
        $db = new database();
        $db->dblink();
        
        switch($operation) {
            case INSERT:
                $result = $db->db_insert("experiments",
                                         "experimenter,
                                          status,
                                          start_date,
                                          start_time,
                                          end_date,
                                          end_time,
                                          trial_count,
                                          num_hits",
                                         "'{$this->experimenter->get_username()}',
                                          '{$this->status}',
                                          '{$this->start_date}',
                                          '{$this->start_time}',
                                          '{$this->end_date}',
                                          '{$this->end_time}',
                                          '{$this->trial_count}',
                                          '{$this->num_hits}'");
                if($result != 0) {
                    $this->experiment_id = $result;
                    return true;
                } // end if
                break;
            case UPDATE:
                $result = $db->db_update("experiments",
                                         "experimenter='{$this->experimenter->get_username()}',
                                          start_date='{$this->start_date}',
                                          start_time='{$this->start_time}',
                                          end_date='{$this->end_date}',
                                          end_time='{$this->end_time}',
                                          trial_count='{$this->trial_count}',
                                          num_hits='{$this->num_hits}',
                                          status='{$this->status}'",
                                         "experiment_id='{$this->experiment_id}'");

                return $result;
                break;
            case DELETE:
                $result = $db->db_delete("experiments",
                                         "experiment_id='{$this->experiment_id}'");
                                         
                return $result;
                break;
            default:
                break;
        } // end switch

        return false;
    } // end db_update()

    /*
     * get the experiment id
     * @return  int  experiment id
     */
    function get_experiment_id() {
        return $this->experiment_id;
    } // end get_experiment_id()

    /*
     * set the experiment id
     * @param   int $experiment_id  experiment id
     */
    function set_experiment_id($experiment_id) {
        $this->experiment_id = $experiment_id;
    } // end set_experiment_id()

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
     * get the experimenter
     * @return  string  username of experimenter
     */
    function get_experimenter() {
        return $this->experimenter;
    } // end get_experimenter()

    /*
     * get the experiment status
     * @return   string  status
     */
    function get_status() {
        return $this->status;
    } // end get_status()

    /*
     * set the experiment status
     * @param   int $status status indicator
     */
    function set_status($status) {
        $this->status = $status;
    } // end set_status()

    /*
     * set the end date for an experiment
     * @param   date    $end_date   the end date
     */
    function set_end_date($end_date) {
        $this->end_date = $end_date;
    } // end set_end_date()

    /*
     * set the end time for an experiment
     * @param   time    $end_time   the end time
     */
    function set_end_time($end_time) {
        $this->end_time = $end_time;
    } // end set_end_time()

    /*
     * get the end date for an experiment
     * @return  date    the end date
     */
    function get_end_date() {
        return $this->end_date;
    } // end get_end_date()

    /*
     * get the end time for an experiment
     * @return  time    the end time
     */
    function get_end_time() {
        return $this->end_time;
    } // end get_end_time()

    /*
     * increment the trial count for an experiment
     */
    function increment_trial_count() {
        $this->trial_count++;
    } // end increment_trial_count()

    /*
     * get the trial count for an experiment
     * @return  int the trial count
     */
    function get_trial_count() {
        return $this->trial_count;
    } // end get_trial_count()

    /*
     * get the trial data for an experiment
     * @return  array   trial data
     */
    function get_trials() {
        return $this->trials;
    } // end get_trials()

    /*
     * increment the number of hits for an experiment
     */
    function increment_num_hits() {
        $this->num_hits++;
    } // end increment_num_hits();

    /*
     * get the number of hits for an experiment
     * @return  int the number of hits
     */
    function get_num_hits() {
        return $this->num_hits;
    } // end get_num_hits()

    /*
     * calculate the score
     * @return  double  percentage hits
     */
    function get_score() {
        return round(($this->num_hits / $this->trial_count * 100), 2);
    } // end get_score()
} // end experiment
?>
