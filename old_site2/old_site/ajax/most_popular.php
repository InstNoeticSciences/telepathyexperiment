<?
include("../inc/config.php");
include("../inc/text.php");
include("../lib/functions.php");
include("../lib/forms.php");
include("../lib/database.php");
include("../lib/images.php");
include("../lib/messages.php");
include("../lib/user.php");

$most_popular = get_most_popular_users();
foreach($most_popular as $i){
	echo "<a href='profile/{$i->username}'><img src='avatars25/{$i->avatar}' alt='{$i->username}' class='mini_friend show_msg_tooltip' /></a>";
}
?>