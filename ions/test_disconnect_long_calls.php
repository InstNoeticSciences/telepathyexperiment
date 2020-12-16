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
    print "$call->from to $call->to (duration $call->duration)\r\n";
}
?>