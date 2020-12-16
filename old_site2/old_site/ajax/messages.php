<?
header( "Content-Type: text/html; charset=UTF-8" );

include("../inc/config.php");
include("../lib/functions.php");
include("../lib/forms.php");
include("../lib/database.php");
include("../lib/images.php");
include("../lib/messages.php");


$db = new database;
$db->dblink();
if($_SESSION['user']) $result = $db->get_recs("messages", "*", "direct=0 and user in (select username from users where visible=1) or user in (select username from users where id in (select user from followed where followed={$_SESSION['user']->id}) and visible=0)", "time desc limit ".mpp);
else $result = $db->get_recs("messages", "*", "direct=0 and user in (select username from users where visible=1)", "time desc limit ".mpp);

$recs = $db->fetch_objects($result);
if(is_array($recs)) foreach($recs as $rec) $messages[] = new message($rec);

if(is_array($messages)) {
	$x = 1;
	foreach($messages as $m){
		if($m->get_back_color()) {
			echo "<div class='msg' style='background-color: ".$m->get_back_color()."'>";
		} else {
			echo "<div class='msg'>";
		}
		echo "<a href='profile/{$m->user}'><img src='avatars_mini/{$m->get_avatar()}' class='avatar' alt='{$m->user}' /></a>";
		echo "<div class='when'>".$m->how_long_ago()."<br />from {$m->from}</div>";
		echo "<div class='msg_content'><span class='name'><a href='profile/{$m->user}'>{$m->user}</a></span>: {$m->msg}";
		if($m->reply) echo " (in reply to <a href='message/{$m->reply}'>".$m->reply_get_username()."</a>)";
		if($m->user == $_SESSION['user']->username){
			echo "<form method='post' action=''><input type='hidden' name='dw' value='{$m->id}' /><input type='submit' value=' ' name='delete' class='no' /></form>";
		}
		if($_SESSION['user'] && $_SESSION['user']->username != $m->user){
				echo "<form method='post' action='reply'><input type='hidden' name='msg_id' value='{$m->id}' /><input type='submit' name='reply' class='reply' value='Reply' /></form>";
		}
		echo "</div>";
		echo "</div>";
		$x = $x * -1;
	}
}
?>