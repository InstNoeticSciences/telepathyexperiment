<?
include("../inc/config.php");
include("../lib/functions.php");
include("../lib/forms.php");
include("../lib/database.php");
include("../lib/images.php");

$base_href = str_replace("rss/", "", dirname("http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'])."/");
$url = $base_href."profile/".$_GET['user'];
echo "<?xml version='1.0' encoding='utf-8' ?>";
echo "<rss version='2.0'>\n<channel>\n";
echo "<title>".$_GET['user']."'s Feeds</title>\n";
echo "<link>$url</link>\n";
echo "<description>".$_GET['user']."'s messages</description>\n";
echo "<language>en</language>\n";
echo "<pubDate>".date("r")."</pubDate>\n";

$db = new database;
$db->dblink();
$result = $db->get_recs("messages", "*", "direct=0 and user='{$_GET['user']}'");
$recs = $db->fetch_objects($result);
if(is_array($recs)) foreach($recs as $rec){
	$usermessage = urldecode($rec->msg);
	$usermessage = str_replace("&", "&amp;", $usermessage);
	$usermessage = str_replace("&amp;amp;", "&amp;", $usermessage);
	$usermessage = str_replace("%26", "&amp;", $usermessage);
	echo "<item>\n";
	echo "<title>".$_GET['user']." wrote:</title>\n";
	echo "<link>$url</link>\n";
	echo "<description>".$usermessage."</description>\n";
	echo "<pubDate>".date("r", $rec->time)."</pubDate>\n";
	echo "<guid>".rss_guid_prefix.$rec->id."</guid>\n";
	echo "</item>\n";
}
echo "</channel>\n</rss>";
?>