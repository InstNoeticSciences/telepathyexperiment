<?php
class sms {
    private $subject;
    private $message;
    private $to_address;
    private $error_message;

    // NAME         -> sms()
    // PARAMETERS   -> $carrier: name of U.S. cell phone carrier
    //              -> $to: cell number of recipient
    //              -> $subject: subject of the message
    //              -> $message: content of the message
    // DESCRIPTION  -> constructor for the sms object
    // RETURNS      -> true: message sent, false: message not sent
    function sms($carrier, $to, $subject, $message) {
        // return true for testing purposes
        return true;
        
        // populate the message parameters
        $this->to_address = $this->build_address($carrier, $to);
        $this->message = $message;
        $this->subject = $subject;

        // send the message
        return($this->send_message());
    } // end sms()

    // NAME         -> build_address()
    // PARAMETERS   -> $carrier: name of U.S. cell phone carrier
    //              -> $to: cell number of recipient
    // DESCRIPTION  -> builds an email address for the sms message
    // RETURNS      -> the address for the sms message or death
    private function build_address($carrier, $to) {
        if(!$to) {
            $this->exit_error("ERROR: no mobile phone number for recipient.");
        } // end if

        $db = new database();
        $db->dblink();

        // get the address for this carrier
        $result = $db->get_recs("carriers", "*", "carrier_id = '{$carrier}'");

        if(!$result) {
            $this->exit_error("ERROR: $carrier is not a valid carrier.");
        } // end if

        $recs = $db->fetch_objects($result);

        if(is_array($recs)) {
            return $to.$recs[0]->carrier_address;
        } else {
            $this->exit_error("ERROR: $carrier is not a valid carrier.");
        } // end if
    } // end build_address()

    // NAME         -> send_message()
    // DESCRIPTION  -> send the sms message via email
    // RETURNS      -> true: message sent, false: message not sent
    private function send_message() {
        return(mail($this->to_address, $this->subject, $this->message));
    } // end send_message()

    // NAME         -> exit_error()
    // PARAMETERS   -> $message_text: the error message
    // DESCRIPTION  -> die with given message
    // RETURNS      -> nothing
    private function exit_error($message_text) {
        $this->error_message = $message_text;
        die($this->error_message);
    } // end exit_error()

    // NAME         -> get_error()
    // DESCRIPTION  -> get the error message for this sms instance
    // RETURNS      -> the error message for this sms instance
    public function get_error() {
        return $this->error_message;
    } // end get_error()

    // NAME         -> get_message()
    // DESCRIPTION  -> get the message for this sms instance
    // RETURNS      -> the message for this sms instance
    public function get_message() {
        return $this->error_message;
    } // end get_message()

    // NAME         -> get_subject()
    // DESCRIPTION  -> get the subject of this sms instance
    // RETURNS      -> the subject of this sms instance
    public function get_subject() {
        return $this->error_message;
    } // end get_sender()

    // NAME         -> get_address()
    // DESCRIPTION  -> get the to address for this sms instance
    // RETURNS      -> the to address for this sms instance
    public function get_address() {
        return $this->to_address;
    } // end get_message()
} // end sms
?>