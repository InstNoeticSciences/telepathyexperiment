<?
header("Content-Type: text/plain");
include("../inc/config.php");
include("../lib/functions.php");
include("../lib/forms.php");
include("../lib/database.php");
include("../lib/images.php");
include("../lib/messages.php");
include("../lib/user.php");

$db = new database;
$db->dblink();
$stuff = explode("_", $_GET['stuff']);
$mode = $stuff[0];
$uid = $stuff[1];
$result = $db->db_update("users", "notify_way='$mode'", "id=$uid");
if($result) echo "Ok, notification mode changed to $mode";
else echo "Couldn't change notification mode to $mode";
?>