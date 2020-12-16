<?
include("../../inc/config.php");
include("../../lib/functions.php");
include("../../lib/user.php");
include("../../lib/database.php");
include("../../lib/images.php");
include("../../lib/messages.php");

$db = new database;
$db->dblink();

//user authentication
if(is_numeric($_POST['username'])) $rec = $db->get_rec("users", "*", "id={$_POST['username']} and pass='{$_POST['password']}'");
else $rec = $db->get_rec("users", "*", "username='{$_POST['username']}' and pass='{$_POST['password']}'");

$x = explode("/", str_replace(dirname($_SERVER['SCRIPT_NAME']), "", $_SERVER['REQUEST_URI']));
$y = explode(".", $x[1]);
$action = $y[0];
$format = $y[1];
$api_key = $y[2];

if(!api_key_ok($api_key)) exit();

if($rec) $user = new user($rec);
else {
	if($format == "xml") {
		header("Content-Type: text/xml; charset=utf-8");
		echo "<error>User authentication failed</error>";
	} else if($format == "json"){
		header("Content-Type: text/javascript; charset=utf-8");
		echo "{\"error\": {\"msg\": \"User authentication failed\"}}";
	}
	exit();
}

//checking if friend exists

//action
if(is_numeric($_POST['friend'])) $fc = $db->get_rec("users", "*", "id={$_POST['friend']}");
else $f = $db->get_rec("users", "*", "username='{$_POST['friend']}'");

if($action == "create"){
	if(!$f) $error = "The user you want to add as a friend does not exist.";
	else $friend = new user($f);
	if($user->has_friend($friend->id)) $error = "This user already is your friend";
	else $ok = $user->add_friend($friend->id);
	if($ok) $msg = "{$friend->username} has been added as your friend";
} else {
	if(!$f) $error = "The user you want to remove  from your friends does not exist.";
	else $friend = new user($f);
	if($user->has_friend($friend->id)) $ok = $user->remove_friend($friend->id);
	else $error = "This user is not your friend anyway";
	if($ok) $msg = "{$friend->username} is not your friend any more";
}

if($format == "xml") {
	header("Content-Type: text/xml; charset=utf-8");
	if($error) echo "<error>$error</error>";
} else if($format == "json"){
	header("Content-Type: text/javascript; charset=utf-8");
	if($error) echo "{\"error\": {\"msg\": \"$error\"}}";
}
if(!$error) echo $friend->api_get_data($format);
?>