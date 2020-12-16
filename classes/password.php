<?php
class password {
    private $ev;
    private $dv;

    /**
     * construct a one-way encrypted password
     * @param   string  $value  value for password
     */
    function password($value) {
        $this->dv = $value;
    } // end password()

    /**
     * encrypt a password one-way
     * @param   string  $salt  encryption salt
     */
    function encrypt($salt) {
        $this->ev = sha1($salt.$this->dv);
    } // end encrypt()

    /**
     * compare the encrypted value to another encrypted password
     * @param   string  $pwd  encrypted password
     * @return  boolean true|false
     */
    function compare($pwd) {
        if(strcmp($this->ev, $pwd) == 0) {
            return true;
        } // end if

        return false;
    } // end compare()

    /**
     * get the encrypted value
     * @return  string  encrypted password
     */
    function get_ev() {
        return $this->ev;
    } // end get_ev()

    /**
     * get the decrypted value
     * @return  string  decrypted password
     */
    function get_dv() {
        return $this->dv;
    } // end get_dv()
} // end password
?>
