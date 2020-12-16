<?php
include("../twilio-twilio-php-f676f16/Services/Twilio.php");
include("../lib/twilio.php");

$API_VERSION = '2010-04-01';
$sid = "AC0add840bb3a10cfa05b1bc6ba673d458";
$token = "6e8161deb8882445bc4a7a46970d8a35";
$client2 = new Services_Twilio($sid, $token);

$client = new TwilioRestClient($sid, $token);
$vars = array('Status' => 'in-progress');
$response = $client->request("/$API_VERSION/Accounts/$sid/Conferences", "GET", $vars);

// https://www.twilio.com/blog/2011/07/easy-conference-calling-twilio.html
if ($response->ResponseXml->Conferences['total'] == 0)
	  echo 'There are no active conferences.';
else { 
	echo '<ul>';
	foreach ($response->ResponseXml->Conferences->Conference as $conference)
	{
	   	print "Conference: $conference->FriendlyName\r\n";
		$response2 = $client->request("/$API_VERSION/Accounts/$sid/Conferences/{$conference->Sid}/Participants", "GET");
		foreach ($response2->ResponseXml->Participants->Participant as $participant)
		{
			$caller_id = $client2->account->outgoing_caller_ids->get($participant->CallSid);
			print "This is the caller id: $caller_id\r\n";
			print_r($participant);
		}
	}
}
?>