<?

//
//
//configuration of gateway
//
//primiary gateway
define(gateway1, "http://application.responsfabrikken.com.br/gatewayservice");
//secondary gateway
define(gateway2, "gateway2.wifact.com");
//customer id
define(customerid, "0000115AF9ADD81");
//Refers to the operator of a given country
define(smsc,"tdc");
//service id
define(serviceid, 2000146);
//channel
define(channel, 5511119);
class sms {
	var $number, $text,$errors;
	function sms($user, $message) {
		$this->number = $this->get_number($user);
		$this->text = $message;
		if($this->send())
			return true;
		else
			return false;
	}

	function send() {
		$str = "/smspush?customerid=".customerid."&mobile={$this->number}&smsc=tdc&serviceid=".serviceid."&text=".urlencode($this->text)."&channel=".channel; 
		$header = "POST {$str} HTTP 1.1\r\n"; 
		$header .= "Content-type: text/plain\r\n"; 
		$header .= "Content-length: " . strlen($str) . "\r\n"; 
		$fp1 = fsockopen(gateway1,80,$errno,$errstr,10); 
		if ($fp1) { 
			fputs($fp1,$header); 
			return true;
		}else{ 
            /*
            $fp2 = fsockopen(gateway2,80,$errno,$errstr,10);
			if ($fp2) { 
				fputs($fp2,$header); 
				return true;
			}else{
				return false;
			}

			$this->errors = $errno." :: ".$errstr;
			return false;
            */
		}
	}
	function get_number($user) {
		return $user->phone;

	}

}



?>
