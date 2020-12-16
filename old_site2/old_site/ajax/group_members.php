<?
include("../inc/config.php");
include("../inc/text.php");
include("../lib/functions.php");
include("../lib/forms.php");
include("../lib/database.php");
include("../lib/images.php");
include("../lib/messages.php");
include("../lib/user.php");


$db = new database;
$db->dblink();

$result = $db->get_recs('groups_links, users', 'groups_links.user_id, users.*', "group_id = ${_GET['group_id']} AND id=user_id");
$recs = $db->fetch_objects($result);
					
if(is_array($recs))
foreach($recs as $i){
    echo "<a href='profile/{$i->username}'><img src='avatars25/{$i->avatar}' alt='{$i->username}' class='mini_friend show_msg_tooltip' /></a>";
}
?>