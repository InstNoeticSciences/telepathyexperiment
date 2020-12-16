<?php
class user {
    private $friends = array();
    private $results = array();

    private $first_name;
    private $last_name;
    private $username;
    private $password;
    private $gender;
    private $phone;
    private $email;
    private $admin;
    private $age;
    private $pin;

    /**
     * construct a user
     * @param   string  $first_name first name
     * @param   string  $last_name  last name
     * @param   string  $username   username
     * @param   string  $password   password
     * @param   string  $gender     gender
     * @param   string  $phone      phone number
     * @param   string  $email      email address
     * @param   char    $admin      administrator flag
     * @param   string  $age        age
     * @param   string  $pin        pin number for twilio access
     */
    function user($first_name,
                  $last_name,
                  $username,
                  $password,
                  $gender,
                  $phone,
                  $email,
                  $admin,
                  $age,
                  $pin) {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->username = $username;
        $this->gender = $gender;
        $this->phone = $phone;
        $this->email = $email;
        $this->admin = $admin;
        $this->age = $age;
        $this->pin = $pin;

        // encrypt the password
        $this->password = new password($password);
        $this->password->encrypt($username);

        // read the friends from the database
        $this->read_friends();
    } // end user()
    
    /**
     * lock a user record if it exists
     * @param   string  $username   username
     * @return  boolean true|false
     */
    public static function lock($username) {
        $db = new database();
        $db->dblink();

        $result = $db->db_update("users",
                                 "locked='Y'",
                                 "username='{$username}'");

        if($result == 0) {
            return true;
        } // end if

        return false;
    } // end lock()

    /**
     * unlock a user record if it exists
     * @param   string  $username   username
     * @return  boolean true|false
     */
    public static function unlock($username) {
        $db = new database();
        $db->dblink();

        $result = $db->db_update("users",
                                 "locked='N'",
                                 "username='{$username}'");

        if($result == 0) {
            return true;
        } // end if

        return false;
    } // end unlock()

    /**
     * check whether or not a user is locked
     * @param   string  $username   username
     * @return  boolean true|false
     */
    public static function is_locked($username) {
        $locked = 'N';

        $db = new database();
        $db->dblink();

        $result = $db->get_recs("users",
                                "locked",
                                "username='{$username}'");

        $recs = $db->fetch_objects($result);

        if(is_array($recs)) {
            $locked = $recs[0]->locked;
        } else {
            $locked = $recs->locked;
        } // end if

        if($locked == 'Y') {
            return true;
        } // end if

        return false;
    } // end is_locked()

    /**
     * add a validation code to the user record
     * @param   string  $username   username
     * @param   string  $code       validation code
     * @return  boolean true|false
     */
    public static function add_validation($username, $code) {
        $db = new database();
        $db->dblink();

        $result = $db->db_update("users",
                                 "code='{$code}'",
                                 "username='{$username}'");

        if($result == 0) {
            return true;
        } // end if

        return false;
    } // end add_validation()

    /**
     * remove a validation code to the user record
     * @param   string  $username   username
     * @return  boolean true|false
     */
    public static function remove_validation($username) {
        $db = new database();
        $db->dblink();

        $result = $db->db_update("users",
                                 "code=''",
                                 "username='{$username}'");

        if($result == 0) {
            return true;
        } // end if

        return false;
    } // end remove_validation()

    /**
     * reset the user object (excluding username and password)
     * @return  boolean true|false
     */
    function reset() {
        $db = new database();
        $db->dblink();

        $result = $db->get_recs("users", "*", "username='{$this->username}'");
        $recs = $db->fetch_objects($result);

        if(!is_array($recs)) {
            return false;
        } // end if

        $this->first_name = $recs[0]->first_name;
        $this->last_name = $recs[0]->last_name;
        $this->gender = $recs[0]->gender;
        $this->phone = $recs[0]->phone;
        $this->email = $recs[0]->email;
        $this->admin = $recs[0]->admin;
        $this->age = $recs[0]->age;
        $this->pin = $recs[0]->pin;

        return true;
    } // end reset()

    /**
     * update the user record in the database
     * @param   string  $operation  type of update (INSERT|UPDATE|DELETE)
     * @return  boolean true|false
     */
    function db_update($operation) {
        $db = new database();
        $db->dblink();

        switch($operation) {
            case INSERT:
                $result = $db->db_insert("users",
                                         "username,
                                          password,
                                          gender,
                                          phone,
                                          email,
                                          first_name,
                                          last_name,
                                          age,
                                          pin",
                                         "'{$this->username}',
                                          '{$this->password->get_ev()}',
                                          '{$this->gender}',
                                          '{$this->phone}',
                                          '{$this->email}',
                                          '{$this->first_name}',
                                          '{$this->last_name}',
                                          '{$this->age}',
                                          '{$this->pin}'");

                if($result == 0) {
                    return true;
                } // end if
                break;
            case UPDATE:
                $result = $db->db_update("users",
                                         "password='{$this->password->get_ev()}',
                                          gender='{$this->gender}',
                                          phone='{$this->phone}',
                                          email='{$this->email}',
                                          first_name='{$this->first_name}',
                                          last_name='{$this->last_name}',
                                          age='{$this->age}',
                                          admin='{$this->admin}',
                                          pin='{$this->pin}'",
                                         "username='{$this->username}'");

                return $result;
                break;
            case DELETE:
                $result = $db->db_delete("users",
                                         "username='{$this->username}'");
                return $result;
                break;
            default:
                break;
        } // end switch

        return false;
    } // end db_update()

    /**
     * read the friends of this user from the database
     * @return  array   $friends    friend list
     */
    private function read_friends() {
        $db = new database();
        $db->dblink();

        $result = $db->get_recs("friends",
                                "id,
                                 friend_name,
                                 phone,
                                 username",
                                "username='{$this->username}'");

        $this->friends = $db->fetch_objects($result);
    } // end read_friends()

    /**
     * insert friends into the database
     * @return  object|null  result of insert
     */
    function insert_friends() {
        if(!$this->has_friends()) {
            return null;
        } // end if

        $db = new database();
        $db->dblink();

        $num_friends = count($this->friends);

        for($i = 0; $i < $num_friends; $i++) {
            $result = $db->db_insert("friends",
                                     "username,
                                      friend_name,
                                      phone",
                                    "'{$this->friends[$i]->username}',
                                     '{$this->friends[$i]->friend_name}',
                                     '{$this->friends[$i]->phone}'");
            
            // update the id field
            $this->friends[$i]->id = $result;
        } // end for

        // return the last result
        return $result;
    } // end insert_friends()

    /**
     * update the friends list on the database
     * @return  object|null  result of update
     */
    function update_friends() {
        if(!is_array($this->friends)) {
            return null;
        } // end if

        $num_friends = count($this->friends);

        $db = new database();
        $db->dblink();

        for($i = 0; $i < $num_friends; $i++) {
            $result = $db->db_update("friends",
                                     "friend_name='{$this->friends[$i]->friend_name}',
                                      phone='{$this->friends[$i]->phone}',
                                      username='{$this->username}'",
                                     "id='{$this->friends[$i]->id}'");
        } // end for

        // return the last result
        return $result;
    } // end update_friends()

    /**
     * delete the friends list on the database
     * @return  boolean  true|false
     */
    function delete_friends() {
        if(is_array($this->friends)) {
            $db = new database();
            $db->dblink();

            $result = $db->db_delete("friends",
                                     "username='{$this->username}'");
            return $result;
        } // end if

        return false;
    } // end delete_friends()

    /**
     * check if the user has any friends
     * @return  boolean true|false
     */
    function has_friends() {
        return(is_array($this->friends));
    } // end has_friends()

    /**
     * set the friend list
     * @param   array   $friends
     */
    function set_friends($friends) {
        if(is_array($friends)) {
            $num_friends = count($friends);

            for($i = 0; $i < $num_friends; $i++) {
                $this->friends[$i]->username = $this->username;
                $this->friends[$i]->friend_name = $friends[$i]->friend_name;
                $this->friends[$i]->phone = $friends[$i]->phone;
            } // end for
        } // end if
    } // end set_friends()

    /**
     * read the experimental results for this user
     */
    function set_results() {
        $this->results = array();

        $db = new database();
        $db->dblink();

        // first get all the id numbers for completed experiments
        $result = $db->get_recs("experiments",
                                "experiment_id",
                                "experimenter='{$this->username}' and
                                 status='".COMPLETE."'");

        $e = $db->fetch_objects($result);
        
        if(is_array($e)) {
            // now get the trial results
            $num_exps = count($e);
            $t = array();

            for($i = 0; $i < $num_exps; $i++) {
                $result = $db->get_recs("trials",
                                        "*",
                                        "experiment_id='{$e[$i]->experiment_id}'");

                $t = $db->fetch_objects($result);
                $num_trials = count($t);
                
                // append each trial to the results array
                for($j = 0; $j < $num_trials; $j++) {
                    $this->results[] = $t[$j];
                } // end for
            } // end for
        } // end if
    } // end set_results()

    /**
     * get the current in-progress experiment for the user
     * @return  mixed  experiment object | false
     */
    function get_current_experiment() {
        $db = new database();
        $db->dblink();

        // first get the id of the current experiment
        $result = $db->get_recs("experiments",
                                "*",
                                "experimenter='{$this->username}' and
                                 (status='".NOT_STARTED."' or
                                  status='".IN_PROGRESS."')");

        $e = $db->fetch_objects($result);

        if(is_array($e)) {
            $experiment = new experiment($this,
                                         $e[0]->start_date,
                                         $e[0]->start_time,
                                         $e[0]->end_date,
                                         $e[0]->end_time,
                                         $e[0]->trial_count,
                                         $e[0]->num_hits,
                                         $e[0]->status);
                                         
            $experiment->set_experiment_id($e[0]->experiment_id);
            $experiment->read_trials();

            return $experiment;
        } else {
            return false;
        } // end if
    } // end get_current_experiment()

    /**
     * get the experimental results for this user
     * @return  array   experimental results
     */
    function get_results() {
        return $this->results;
    } // end get_results()

    /**
     * get the username
     * @return  string  username
     */
    function get_username() {
        return $this->username;
    } // end get_username()

    /**
     * get the first name
     * @return  string  username
     */
    function get_first_name() {
        return $this->first_name;
    } // end get_first_name()

    /**
     * set the first name
     * @param  string  $first_name
     */
    function set_first_name($first_name) {
        $this->first_name = $first_name;
    } // end set_first_name()

    /**
     * get the last name
     * @return  string  last name
     */
    function get_last_name() {
        return $this->last_name;
    } // end get_last_name()

    /**
     * set the last name
     * @param  string  $last_name
     */
    function set_last_name($last_name) {
        $this->last_name = $last_name;
    } // end set_last_name()

    /**
     * get the phone number
     * @return  string  phone number
     */
    function get_phone() {
        return $this->phone;
    } // end get_phone()

    /**
     * set the phone number
     * @param  string  $phone
     */
    function set_phone($phone) {
        $this->phone = $phone;
    } // end set_phone()

    /**
     * get the email address
     * @return  string  email address
     */
    function get_email() {
        return $this->email;
    } // end get_email()

    /**
     * set the email address
     * @param  string  $email
     */
    function set_email($email) {
        $this->email = $email;
    } // end set_first_name()

    /**
     * get the friend list
     * @return  array   friends list
     */
    function get_friends() {
        return $this->friends;
    } // end get_friends()

    /**
     * get the pin
     * @return  string  pin
     */
    function get_pin() {
        return $this->pin;
    } // end get_pin()

    /**
     * set the age
     * @param   string  $age    age
     */
    function set_age($age) {
        $this->age = $age;
    } // end set_age()

    /**
     * get the age
     * @return  string  age
     */
    function get_age() {
        return $this->age;
    } // end get_age()

    /**
     * set the gender
     * @param   string  $gender    gender
     */
    function set_gender($gender) {
        $this->gender = $gender;
    } // end set_gender()

    /**
     * get the gender
     * @return  string  gender
     */
    function get_gender() {
        return $this->gender;
    } // end get_gender()

    /**
     * check if the user is an administrator
     */
    function is_admin() {
        if($this->admin == 'X' || $this->admin == 'x') {
            return true;
        } // end if

        return false;
    } // end is_admin()
} // end user
?>
