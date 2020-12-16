<?
include("inc/config.php");
include("lib/functions.php");
include("lib/user.php");
include("lib/forms.php");
include("lib/database.php");
include("lib/images.php");
include("lib/messages.php");
session_start();
if($_POST['delete_me']){
	$db = new database;
	$db->dblink();
	$rec = $db->get_rec("users", "*", "id={$_POST['user']} and pass='{$_POST['pass']}'");
	if($rec) $user = new user($rec);
	else {
		header("Location: home");
		echo "<script type='text/javascript'>alert('This password is incorrect');</script>";
		exit();
	}

	//LET THE DELETING BEGIN!

	//avatars
	if(is_file("avatars_mini/{$user->avatar}")) unlink("avatars_mini/{$user->avatar}");
	if(is_file("avatars25/{$user->avatar}")) unlink("avatars25/{$user->avatar}");
	//background
	if(is_file("backgrounds/{$user->id}.jpg")) unlink("backgrounds/{$user->id}.jpg");
	//layout
	$db->db_delete("layouts", "user={$user->id}");
	//messages
	$db->db_delete("messages", "user='{$user->username}'");
	//blocks
	$db->db_delete("blocked_users", "user={$user->id} or blocked_user={$user->id}");
	//friendships
	$db->db_delete("followed", "user={$user->id} or followed={$user->id}");
        //extension
        $db->db_delete("extensions", "username='{$user->username}'");
	//the user himself
	$db->db_delete("users", "id={$user->id}");
	unset($user);
}
session_destroy();
header("Location: home");
?>