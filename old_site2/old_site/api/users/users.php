<?
include("../../inc/config.php");
include("../../lib/functions.php");
include("../../lib/user.php");
include("../../lib/database.php");
include("../../lib/images.php");
include("../../lib/messages.php");

$db = new database;
$db->dblink();

$x = explode("/", str_replace(dirname($_SERVER['SCRIPT_NAME']), "", $_SERVER['REQUEST_URI']));
$action = $x[1];
$y = explode(".", $x[2]);
$u = $y[0];
$format = $y[1];
$api_key = $y[2];

if(!api_key_ok($api_key)) exit();

if(is_numeric($u)) $rec = $db->get_rec("users", "*", "id=$u");
else $rec = $db->get_rec("users", "*", "username='$u'");
if($rec) $user = new user($rec);
else $error = "This user does not exist.";



if(!$error){
	switch($action){
		case "friends":
			$friends = $user->get_friends();
			if(is_array($friends)) foreach($friends as $f) $data .= $f->api_get_data($format, 1);
			switch($format){
				case "xml": $data = "<friends user_id='{$user->id}'>".$data."</friends>"; break;
				case "json": $data = "{\"friends\": {
					\"user_id\": \"{$user->id}\",
					\"users\": [".$data."]}}";
				break;
			}
			break;
		case "followers":
			$followers = $user->get_followers();
			if(is_array($followers)) foreach($followers as $f) $data .= $f->api_get_data($format, 1);
			switch($format){
				case "xml": $data = "<followers user_id='{$user->id}'>".$data."</followers>"; break;
				case "json": $data = "{\"followers\": {
					\"user_id\": \"{$user->id}\",
					\"users\": [".$data."]}}";
				break;
			}
			break;
		case "details":
			$data = $user->api_get_data($format);
			break;
	}
}

if($format == "xml") {
	header("Content-Type: text/xml; charset=utf-8");
	if($error) echo "<error>$error</error>";
} else if($format == "json"){
	header("Content-Type: text/javascript; charset=utf-8");
	if($error) echo "{\"error\": {\"msg\": \"$error\"}}";
}
if(!$error) echo $data;
?>