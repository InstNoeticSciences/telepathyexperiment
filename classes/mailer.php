<?php
define('SUSPECT_VALUES', '/Content-Type:|Bcc:|Cc:/i');

class mailer {
    private $to;
    private $from;
    private $subject;
    private $message;

    /**
     * construct an email message
     * @param   string  $to         recipient of the message
     * @param   string  $from       sender of the message
     * @param   string  $subject    subject line of the message
     * @param   string  $message    the message content
     */
    function mailer($to, $from, $subject, $message) {
        $this->to = $to;
        $this->from = $from;
        $this->subject = $subject;
        $this->message = $message;
    } // end mailer()

    /**
     * send an email message
     * @return  boolean true|false
     */
    function send() {
        // set up additional headers
        $headers = "From: $this->from"."\r\n".
                   "Reply-To: $this->from"."\r\n".
                   "MIME-Version: 1.0"."\r\n".
                   "Content-type: text/html; charset=iso-8859-1"."\r\n";
        
        // fix any bare linefeeds in the message to make it RFC821 Compliant.
        $this->message = preg_replace("#(?<!\r)\n#si", "\r\n", $this->message);
        $this->message = wordwrap($this->message, 70);

        // check for suspicious values
        if($this->is_suspect($this->to) ||
           $this->is_suspect($this->from) ||
           $this->is_suspect($this->subject) ||
           $this->is_suspect($this->message) ||
           $this->is_suspect($this->headers)) {

            // possible security issue
            return false;
        } // end if

        return(mail($this->to, $this->subject, $this->message, $headers));
    } // end send()

    /**
     * check for suspicious values
     * @param   string  $value      string to check
     * @param   string  $pattern    pattern to look for
     * @param   string  &$suspect   flag for indicating suspect value
     */
    private function is_suspect($value) {
        // check for a suspect value
        if(preg_match(SUSPECT_VALUES, $value)) {
            return true;
        } // end if

        return false;
    } // end is_suspect()
} // end mailer
?>
