<?php
class twilio_sms {
    private $receiver;
    private $sender;
    private $message;
    private $delay;
    private $sid;
    private $id;

    /**
     * create a twilio_sms object
     * @param   string  $receiver       phone number of recipient
     * @param   string  $sender         phone number of sender
     * @param   string  $message        content of message
     * @param   string  $sid            session id of call
     * @param   int     $delay          delay (minutes) before sending
     * @param   int     $id             id of message
     */
    function twilio_sms($receiver,
                        $sender,
                        $message,
                        $sid = null,
                        $delay = 0,
                        $id = 0) {
        $this->receiver = $receiver;
        $this->sender = $sender;
        $this->message = $message;
        $this->delay = $delay;
        $this->sid = $sid;
        $this->id = $id;
    } // end twilio_sms()

    /**
     * generate the twiml to send the sms message
     * @param   Response    $r twilio response object
     * @return  boolean true|false
     */
    function twiml($r) {
        if($r instanceof Response) {
            // append a delay if one has been specified
            if($this->delay > 0) {
                $seconds = $this->delay * 60;
                $r->append(new Pause(array("length" => $seconds)));
            } // end if

            // generate the message twiml
            $r->append(new Sms($this->message,
                               array("to" => $this->receiver,
                                     "from" => $this->sender)));
        } else {
            return false;
        } // end i

        return true;
    } // end twiml()

    /**
     * send the sms message via the twilio REST API
     * @param   string  $sid    REST SID
     * @param   string  $api    REST API
     * @param   string  $token  REST token
     * @return  boolean true|false
     */
    function rest($sid, $api, $token) {
        $client = new TwilioRestClient($sid, $token);
        
        $data = array("From" => $this->sender,
                      "To" => $this->receiver,
                      "Body" => $this->message);

        // process the delay if one has been specified
        if($this->delay > 0) {
            $seconds = $this->delay * 60;
            sleep($seconds);
        } // end if

        // process the message
        $response = $client->request("/$api/Accounts/$sid/SMS/Messages/",
                                     "POST",
                                     $data);

        if($response->IsError) {
            return false;
        } // end if

        return true;
    } // end rest()

    /**
     * get the to number
     * @return  string  recipient number
     */
    function get_receiver() {
        return $this->receiver;
    } // end get_receiver()

    /**
     * get the from number
     * @return  string  sender number
     */
    function get_sender() {
        return $this->sender;
    } // end get_sender()

    /**
     * get the message
     * @return  string  message
     */
    function get_message() {
        return $this->message;
    } // end get_message()

    /**
     * get the session id of the call
     * @return  string  session id
     */
    function get_sid() {
        return $this->sid;
    } // end get_sid()

    /**
     * get the delay for this message
     * @return  int delay
     */
    function get_delay() {
        return $this->delay;
    } // end get_delay()

    /**
     * get the id for this message
     * @return  int id
     */
    function get_id() {
        return $this->id;
    } // end get_id()
} // end twilio_sms
?>
