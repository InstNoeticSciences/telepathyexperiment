<?php
// Get the PHP helper library from twilio.com/docs/php/install
include("../twilio-twilio-php-f676f16/Services/Twilio.php");
include("../lib/twilio.php");
include("check_allconnected.php");

// Your Account Sid and Auth Token from twilio.com/user/account
$sid = "AC0add840bb3a10cfa05b1bc6ba673d458";
$token = "6e8161deb8882445bc4a7a46970d8a35";
$client = new Services_Twilio($sid, $token);

$count = 0;
while ($count < 2) {
	$count = check_allconnected($client, "858-405-7952", "1(707)779 8277", "+17073642466");
	print "Count is $count\r\n";
	sleep(1);
}
?>