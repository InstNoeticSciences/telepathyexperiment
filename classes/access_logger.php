<?php
class access_logger {
    private $ip;
    private $max_attempts;

    /**
     * create an access logger object
     * @param   string  $ip             ip for log entry
     * @param   int     $max_attempts   maximum login attempts allowed
     */
    function access_logger($ip, $max_attempts) {
        $this->ip = $ip;
        $this->max_attempts = $max_attempts;
    } // end access_logger()

    /**
     * create an access log entry
     * @return  boolean true|false
     */
    function create_entry() {
        $db = new database();
        $db->dblink();

        $result = $db->db_insert("login_attempts",
                                 "ip,
                                  attempts",
                                 "'{$this->ip}',
                                  '0'");

        if($result == 0) {
            return true;
        } // end if

        return false;
    } // end create_entry()

    /**
     * increment the number of accesses for a user
     * @return  boolean true|false
     */
    function increment_attempts() {
        $attempts = 0;

        $db = new database();
        $db->dblink();

        $result = $db->get_recs("login_attempts",
                                "ip,
                                 attempts",
                                "ip='{$this->ip}'");

        $recs = $db->fetch_objects($result);

        if(is_array($recs)) {
            $attempts = $recs[0]->attempts;
        } else {
            $attempts = $recs->attempts;
        } // end if

        if($attempts >= $this->max_attempts) {
            // number of attempts exceeded
            return false;
        } // end if

        $attempts++;

        $result = $db->db_update("login_attempts",
                                 "attempts='{$attempts}'",
                                 "ip='{$this->ip}'");
        return true;
    } // end increment_attempts()

    /**
     * delete an access log entry
     * @return  boolean true|false
     */
    function delete_entry() {
        $db = new database();
        $db->dblink();

        $result = $db->db_delete("login_attempts",
                                 "ip='{$this->ip}'");

        if($result == 0) {
            return true;
        } // end if

        return false;
    } // end delete_entry()

    /**
     * get the ip
     * @return  string  ip
     */
    function get_ip() {
        return $this->ip;
    } // end get_ip()

    /**
     * get the maximum number of attempts
     * @return  int maximum number of attempts
     */
    function get_max_attempts() {
        return $this->max_attempts;
    } // end get_max_attempts()
} // end access_logger
?>
