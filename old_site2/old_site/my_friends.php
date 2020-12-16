<?
header( "Content-Type: text/javascript; charset=UTF-8" );

include("inc/config.php");
include("lib/functions.php");
include("lib/database.php");
include("lib/user.php");

//$base_href = dirname("http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'])."/";

$db = new database;
$db->dblink();
$rec = $db->get_rec("users", "*", "username='{$_GET['user']}'");
$user = new user($rec);
$friends = $user->get_friends();
if(is_array($friends)) foreach($friends as $f){
	$html .= "<a href='http://gozub.com/profile/{$f->username}' title='{$f->username}' target='_blank' class='friend_link'><img src='http://gozub.com/avatars25/{$f->id}.jpg' class='friend_img' /></a>";
}

echo "var html = \"$html\";";
?>