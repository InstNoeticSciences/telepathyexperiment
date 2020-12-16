<?
header( "Content-Type: text/html; charset=UTF-8" );

include("../inc/config.php");
include("../lib/functions.php");
include("../lib/database.php");
include("../lib/messages.php");
include("../inc/text.php");

$db = new database;
$db->dblink();
$rec = $db->get_rec("messages", "*", "user='{$_GET['user']}' and direct=0", "time desc limit 1");
$msg = new message($rec);
$message = preg_replace("(\r\n|\n|\r)", " ", $msg->parse_links());
echo "<span class='latest_message'>$message</span><span class='hint'> (".$msg->how_long_ago().")</span>";
?>