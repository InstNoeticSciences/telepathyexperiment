<?
//header("Content-Type: text/xml; charset=utf-8");
include("../inc/config.php");
include("../lib/functions.php");
include("../lib/forms.php");
include("../lib/database.php");
include("../lib/images.php");
include("../lib/user.php");
include("../lib/messages.php");

$base_href = str_replace("rss/", "", dirname("http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'])."/");
$url = $base_href."profile/".$_GET['user']."/with_friends";
echo "<?xml version='1.0' encoding='utf-8' ?>";
echo "<rss version='2.0'>";
echo "<channel>\n";
echo "<title>".$_GET['user']."'s Friends</title>\n";
echo "<link>$url</link>\n";
echo "<description>".$_GET['user']."'s friends' messages</description>\n";
echo "<language>en</language>\n";
echo "<pubDate>".date("r")."</pubDate>\n";

$db = new database;
$db->dblink();

$rec = $db->get_rec("users", "*", "username='{$_GET['user']}'");
$user = new user($rec);

$friends = $user->get_friends();
if(is_array($friends)) {
	if(is_array($friends)) foreach($friends as $rec) $user_names[] = "user='".$rec->username."'";
	if(is_array($user_names)) $user_list = implode(" or ", $user_names);

	$result = $db->get_recs("messages", "*", "direct=0 and
	($user_list or user='{$user->username}') and
	user in (select username from users where visible=1)", "time desc limit 100");
	$recs = $db->fetch_objects($result);
	if(is_array($recs)) foreach($recs as $rec) {
		$usermessage = urldecode($rec->msg);
		$usermessage = str_replace("&", "&amp;", $usermessage);
		$usermessage = str_replace("&amp;amp;", "&amp;", $usermessage);
		$usermessage = str_replace("%26", "&amp;", $usermessage);
		echo "<item>\n";
		echo "<title>".$rec->user." wrote:</title>\n";
		echo "<link>$url</link>\n";
		echo "<description>".$usermessage."</description>\n";
		echo "<pubDate>".date("r", $rec->time)."</pubDate>\n";
		echo "<guid>".rss_guid_prefix.$rec->id."</guid>\n";
		echo "</item>\n";
	}
}
echo "</channel></rss>";
?>