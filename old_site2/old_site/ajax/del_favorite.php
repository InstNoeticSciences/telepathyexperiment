<?
include("../inc/config.php");
include("../lib/functions.php");
include("../lib/forms.php");
include("../lib/database.php");
include("../lib/images.php");
include("../lib/messages.php");
include("../lib/user.php");

$stuff = explode("_", $_GET['stuff']);
$uid = $stuff[0];
$mid = $stuff[1];

$db = new database;
$db->dblink();

$rec = $db->get_rec("users", "*", "id=$uid");
$user = new user($rec);

$user->del_favorite($mid);
echo "OK";
?>