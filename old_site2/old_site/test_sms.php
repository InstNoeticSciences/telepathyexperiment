<?
include("lib/sms.class.php");
include("lib/user.php");

$u = new user($rec);
$u->phone = 551192339888;
$s = new sms($u, "test");
if($s){
	echo("ok");
	echo($s->errors);
}else{
	echo("fubard");
echo($s->errors);
}
