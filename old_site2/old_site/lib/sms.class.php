<?php
define(gateway, "http://api.clickatell.com/http/");
define(api_id, "3229041");
define(user, "telepathy");
define(password, "BFPntv35");
define(sender, "TelepathyExperiment");

class sms {
	var $number,
        $text,
        $errors;

    // NAME         -> sms()
    // PARAMETERS   -> $user: user object for recipient
    //              -> $message: message text to send
    // DESCRIPTION  -> sends and sms to the specified user
    // RETURNS      -> true: message sent, false: message not sent
	function sms($user, $message) {
		$this->number = $this->get_number($user);
		$this->text = $message;
		if($this->send())
			return true;
		else
			return false;
	} // end sms()

    // NAME         -> send()
    // DESCRIPTION  -> sends the sms message to the gateway
    // RETURNS      -> true: message sent, false: message not sent
	function send() {
        // just return true for now
        return true;
        
		$str = "/sendmsg?api_id=".api_id.
                         "&user=".user.
                     "&password=".password.
                           "&to=".$this->number.
                         "&text=".$this->text.
                         "&from=".sender;

		$header = "POST {$str} HTTP 1.1\r\n"; 
		$header .= "Content-type: text/plain\r\n"; 
		$header .= "Content-length: ".strlen($str)."\r\n"; 
		$fp1 = fsockopen(gateway,80,$errno,$errstr,10);
        
		if ($fp1) { 
			fputs($fp1,$header); 
			return true;
		}else{ 
			$this->errors = $errno." :: ".$errstr;
			return false;
		}
	} // end send()

    // NAME         -> get_number()
    // PARAMETERS   -> $user: user object for recipient
    // DESCRIPTION  -> gets the user's mobile phone number
    // RETURNS      -> the user's mobile phone number
	function get_number($user) {
		return $user->phone;
	} // end get_number()
} // end sms
?>
