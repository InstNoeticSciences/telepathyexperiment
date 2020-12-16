<?
include("../inc/config.php");
include("../lib/functions.php");
include("../lib/forms.php");
include("../lib/database.php");
include("../lib/images.php");
include("../lib/messages.php");

$db = new database;
$db->dblink();
$result = $db->get_recs("users", "interests");
$recs = $db->fetch_objects($result);
$keywords = array();
if(is_array($recs)) foreach($recs as $rec){
	$words = explode(",", $rec->interests);
	if(is_array($words)) foreach($words as $k=>$v) {
		if(!in_array(trim($v), $keywords)) $keywords[trim($v)] = trim($v);
	}
}

$q = strtolower($_GET["q"]);
if (!$q) return;
foreach ($keywords as $key=>$value) {
	if (strpos(strtolower($key), $q) === 0) {
		echo "$key|$value\n";
		//secho $value."\n";
	}
}
?>