<?
include("../inc/config.php");
include("../inc/text.php");
include("../lib/functions.php");
include("../lib/forms.php");
include("../lib/database.php");
include("../lib/images.php");
include("../lib/messages.php");
include("../lib/user.php");

$most_recent = get_most_recent_users();
foreach($most_recent as $i){
	echo "<a href='profile/{$i->username}'><img src='avatars25/{$i->avatar}' alt='{$i->username}' class='mini_friend show_msg_tooltip' /></a>";
}
?>