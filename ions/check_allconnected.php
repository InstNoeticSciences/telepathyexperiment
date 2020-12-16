<?php
// Get the PHP helper library from twilio.com/docs/php/install
include_once("../twilio-twilio-php-f676f16/Services/Twilio.php");
include_once("../lib/twilio.php");

/* Ending all live calls

$client = new Services_Twilio('AC123', '123');
$calls = $client->account->calls->getIterator(0, 50, array('Status' => 'in-progress'));
foreach ($calls as $call) {
  $call->hangup();
}*/

function check_allconnected($client, $phone1, $phone2, $phone3) {
  $phone1 = reformat_phone_number($phone1);
  $phone2 = reformat_phone_number($phone2);
  $phone3 = reformat_phone_number($phone3);
  //print "reformated number $phone1\r\n";
  //print "reformated number $phone2\r\n";
  //print "reformated number $phone3\r\n";
  
  $count = 0;
  foreach($client->account->calls->getIterator(0,50,array("Status" => "in-progress")) as $call) {
	  if ($call->to == $phone1) $count++;
	  if ($call->to == $phone2) $count++;
	  if ($call->to == $phone3) $count++;
  }
  return $count;
}

function reformat_phone_number($str) {
	$str = str_replace(array("-","."," ","(",")"), "", $str);
	if (strlen($str) == 10) $str = "1".$str;
	if ($str[0] != '+') $str = "+".$str;
	return $str;
}

?>