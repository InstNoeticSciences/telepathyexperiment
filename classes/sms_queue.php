<?php
class sms_queue {
    private $queue;
    private $sid;

    /**
     * constructor
     * @param   string  $sid    session id of the call
     */
    function sms_queue($sid) {
        $this->sid = $sid;
        $this->queue = array();
    } // end sms_queue()

    /**
     * read the queue for this call sid from the database
     * @return  boolean true|false
     */
    function fetch() {
        $db = new database();
        $db->dblink();

        $result = $db->get_recs("sms_queue", "*", "callsid='{$this->sid}'");
        $recs = $db->fetch_objects($result);

        if(is_array($recs)) {
            $num_recs = count($recs);
            
            for($i = 0; $i < $num_recs; $i++) {
                $sms = new twilio_sms($recs[$i]->receiver,
                                      $recs[$i]->sender,
                                      $recs[$i]->message,
                                      $recs[$i]->callsid,
                                      $recs[$i]->delay,
                                      $recs[$i]->id);

                $this->queue[] = $sms;
            } // end for
        } // end if

        return is_array($this->queue);
    } // end fetch()

    /**
     * add an entry to the end of the sms queue
     * @param   object  $twilio_sms a twilio_sms object
     * @return  boolean true|false
     */
    function append($twilio_sms) {
        $before = count($this->queue);
        $this->queue[] = $twilio_sms;
        $after = count($this->queue);
        
        if($after > $before) {
            return true;
        } // end if

        return false;
    } // end append()

    /**
     * update the database with the latest version of the queue
     * @param   int  $operation  type of update (INSERT|UPDATE|DELETE)
     * @return  boolean true|false
     */
    function db_update($operation) {
        $db = new database();
        $db->dblink();

        $num_recs = count($this->queue);

        switch($operation) {
            case INSERT:
                for($i = 0; $i < $num_recs; $i++) {
                    $db->db_insert("sms_queue",
                                   "receiver,
                                    sender,
                                    message,
                                    callsid,
                                    delay",
                                   "'{$this->queue[$i]->get_receiver()}',
                                    '{$this->queue[$i]->get_sender()}',
                                    '{$this->queue[$i]->get_message()}',
                                    '{$this->queue[$i]->get_sid()}',
                                    '{$this->queue[$i]->get_delay()}'");
                } // end for

                return true;
                break;
            case UPDATE:
                break;
            case DELETE:
                $result = $db->db_delete("sms_queue",
                                         "callsid='{$this->sid}'");

                return true;
                break;
            default:
                break;
        } // end switch
    } // end db_update()

    /**
     * process each message in the queue
     * @param   boolean     $use_rest   use the REST API to send messages
     * @param   Response    $r          twilio response object
     * @return  boolean true|false
     */
    function process($use_rest, $r = null) {
        $db = new database();
        $db->dblink();

        $num_recs = count($this->queue);

        if($num_recs > 0) {
            for($i = 0; $i < $num_recs; $i++) {
                // remove the database copy of the message before sending
                $db->db_delete("sms_queue", "id='{$this->queue[$i]->get_id()}'");

                // send the message using the prescribed method
                if($use_rest) {
                    $this->queue[$i]->rest(REST_SID, REST_API, REST_TOKEN);
                } else {
                    $this->queue[$i]->twiml($r);
                } // end if
            } // end for

            // empty the queue
            unset($this->queue);
            return true;
        } // end if

        return false;
    } // end process()
} // end sms_queue
?>
