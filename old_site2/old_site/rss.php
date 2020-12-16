<?
include("inc/config.php");
include("lib/functions.php");
include("lib/forms.php");
include("lib/database.php");
include("lib/images.php");

echo "<?xml version='1.0' encoding='utf-8' ?>";
echo "<rss version='2.0'>\n<channel>\n";
echo "<title>".$_GET['user']."'s TwitterClone</title>\n";
echo "<link>".rss_link.$_GET['user']."</link>\n";
echo "<description>".$_GET['user']."'s messages at TwitterClone</description>\n";
echo "<language>en</language>\n";
echo "<pubDate>".date("r")."</pubDate>\n";

$db = new database;
$db->dblink();
$result = $db->get_recs("messages", "*", "user='{$_GET['user']}'");
$recs = $db->fetch_objects($result);
if(is_array($recs)) foreach($recs as $rec){
	echo "<item>\n";
	echo "<title>".$_GET['user']." wrote:</title>\n";
	echo "<link>".rss_link.$_GET['user']."</link>\n";
	echo "<description>".$rec->msg."</description>\n";
	echo "<pubDate>".date("r", $rec->time)."</pubDate>\n";
	echo "<guid>".rss_guid_prefix.$rec->id."</guid>\n";
	echo "</item>\n";
}
echo "</channel>\n</rss>";
?>