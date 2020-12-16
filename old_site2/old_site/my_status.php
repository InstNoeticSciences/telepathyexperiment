<?
include("inc/config.php");
include("lib/functions.php");
include("lib/forms.php");
include("lib/database.php");
include("lib/images.php");
include("lib/messages.php");
include("lib/user.php");
include("inc/text.php");

$db = new database;
$db->dblink();

$u = $db->get_rec("users", "*", "username='{$_GET['user']}'");
$user = new user($u);

$m = $user->last_update();
header( "Content-Type: text/javascript; charset=UTF-8" );
$html = $m->parse_links()." (".$m->how_long_ago().")";
echo "var html = \"$html\";";

?>