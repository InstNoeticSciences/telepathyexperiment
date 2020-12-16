<?
header("Content-Type: text/xml; charset=utf-8");
include("inc/config.php");
include("inc/text.php");
include("lib/functions.php");
include("lib/forms.php");
include("lib/database.php");
include("lib/images.php");
include("lib/messages.php");

$db = new database;
$db->dblink();

$rec1 = $db->get_rec("layouts", "sticker_color", "user={$_GET['user']}");
$rec2 = $db->get_rec("users", "username", "id={$_GET['user']}");
$result = $db->get_recs("messages", "id, time, msg", "user='{$rec2->username}'", "time desc limit 5");
$recs = $db->fetch_objects($result);

if(is_array($recs)) foreach($recs as $rec) $messages[] = new message($rec);

echo "<sticker_data color='{$rec1->sticker_color}' username='{$rec2->username}'>";
echo "<messages>";
if(is_array($messages)) foreach($messages as $m){
	echo "<message id='{$m->id}'>";
	echo "<content when='".$m->how_long_ago()."'>{$m->msg}</content>";
	echo "</message>";
}
echo "</messages>";
echo "</sticker_data>";
?>