<?php
include("../inc/config.php");
include("../lib/functions.php");
include("../lib/forms.php");
include("../lib/database.php");
include("../lib/images.php");

$base_href = str_replace("rss/", "", dirname("http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'])."/");
$url = $base_href;
$db = new database;
$db->dblink();

if (isset($_GET['group']) && !empty($_GET['group'])){

	$g_furl_hash = md5($_GET['group']);
	$g_result = $db->get_recs("groups_mes", "group_id,group_title,group_furl", "group_furl_hash='{$g_furl_hash}'", "");
	$g_recs = $db->fetch_objects($g_result);

	echo "<?xml version='1.0' encoding='utf-8' ?>";
	echo "<rss version='2.0'>\n<channel>\n";
	echo "<title>{$g_recs[0]->group_title} group timeline</title>\n";
	echo "<link>$url</link>\n";
	echo "<description>Public messages for {$g_recs[0]->group_title}</description>\n";
	echo "<language>en</language>\n";
	echo "<pubDate>".date("r")."</pubDate>\n";

	if(is_array($g_recs)){
		$result = $db->get_recs("messages", "*", "direct=0 and user in (select username from users where visible=1) AND group_id='{$g_recs[0]->group_id}' ", "time desc limit 50");
		$recs = $db->fetch_objects($result);
		if(is_array($recs)) foreach($recs as $rec){
			$usermessage = urldecode($rec->msg);
			$usermessage = str_replace("&", "&amp;", $usermessage);
			$usermessage = str_replace("&amp;amp;", "&amp;", $usermessage);
			$usermessage = str_replace("%26", "&amp;", $usermessage);
			echo "<item>\n";
			echo "<title>".$rec->user." wrote:</title>\n";
			echo "<link>{$url}profile/{$rec->user}</link>\n";
			echo "<description>".$usermessage."</description>\n";
			echo "<pubDate>".date("r", $rec->time)."</pubDate>\n";
			echo "<guid>".rss_guid_prefix.$rec->id."</guid>\n";
			echo "</item>\n";
		}
	}
}
else {

	echo "<?xml version='1.0' encoding='utf-8' ?>";
	echo "<rss version='2.0'>\n<channel>\n";
	echo "<title>Main timeline</title>\n";
	echo "<link>$url</link>\n";
	echo "<description>Public messages</description>\n";
	echo "<language>en</language>\n";
	echo "<pubDate>".date("r")."</pubDate>\n";

	$result = $db->get_recs("messages", "*", "direct=0 and user in (select username from users where visible=1)", "time desc limit 50");
	$recs = $db->fetch_objects($result);
	if(is_array($recs)) foreach($recs as $rec){
		$usermessage = urldecode($rec->msg);
		$usermessage = str_replace("&", "&amp;", $usermessage);
		$usermessage = str_replace("&amp;amp;", "&amp;", $usermessage);
		$usermessage = str_replace("%26", "&amp;", $usermessage);
		echo "<item>\n";
		echo "<title>".$rec->user." wrote:</title>\n";
		echo "<link>{$url}profile/{$rec->user}</link>\n";
		echo "<description>".$usermessage."</description>\n";
		echo "<pubDate>".date("r", $rec->time)."</pubDate>\n";
		echo "<guid>".rss_guid_prefix.$rec->id."</guid>\n";
		echo "</item>\n";
	}
}

echo "</channel>\n</rss>";
//$usermessage = urldecode($rec->location);
?>