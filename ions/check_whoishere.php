<?php
// Get the PHP helper library from twilio.com/docs/php/install
include("../twilio-twilio-php-f676f16/Services/Twilio.php");
include("../lib/twilio.php");

// Your Account Sid and Auth Token from twilio.com/user/account
$sid = "AC0add840bb3a10cfa05b1bc6ba673d458";
$token = "6e8161deb8882445bc4a7a46970d8a35";
$client = new Services_Twilio($sid, $token);
// Loop over the list of calls and echo a property for each one
foreach($client->account->calls->getIterator(0,50,array("Status" => "in-progress")) as $call) {
	
	$start_time = $call->start_time;
	$start_time = strtotime($start_time);
	$timeNow    = strtotime(date("Y-m-d G:i:s"));
	$differenceInSeconds = $timeNow - $start_time;
	print "***************************************\r\n";
    print "From:     $call->from\r\n";
    print "To:       $call->to\r\n";
    print "Start:    $call->start_time ($start_time)\r\n";
    print "Now:      $timeNow\r\n";
    print "Diff:     $differenceInSeconds\r\n";
    print "End:      $call->end_time\r\n";
    print "Answered: $call->answered_by\r\n";
	//$call->hangup();
}
// Mon, 21 Oct 2013 21:39:30 +0000


/*    <tr>
    <td><?=$call->start_time?></td>
    <td><?=$call->duration?></td>
    <td><?=$call->from?></td>
    <td><?=$call->to?></td>
    <td>
        <form action="actions/queue.php" method="post">
        <input type="hidden" name="caller" value="<?=$call->parent_call_sid?>" />
        <input type="submit" value="Return Call to Queue" style="color: #555;" />
        </form>
    </td>
    </tr> */
    
/*<?php
include("../twilio-twilio-php-f676f16/Services/Twilio.php");
include("../lib/twilio.php");

$API_VERSION = '2010-04-01';
$sid = "AC0add840bb3a10cfa05b1bc6ba673d458";
$token = "6e8161deb8882445bc4a7a46970d8a35";
//$client = new Services_Twilio($sid, $token);

$client = new TwilioRestClient($sid, $token);
$vars = array('Status' => 'in-progress');
$response = $client->request("/2010-04-01/Accounts/$sid/OutgoingCallerId", "GET", $vars);

print_r($response);*/

// https://www.twilio.com/blog/2011/07/easy-conference-calling-twilio.html
/*if ($response->ResponseXml->Conferences['total'] == 0)
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
}*/
?>