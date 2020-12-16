<?php
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

$x = explode("___", $_GET['stuff']);
$uid = $x[0];
$page_num = $x[1];
$logged_user = $x[2];

$rec = $db->get_rec("users", "*", "id=$uid");
$user = new user($rec);

$rec = $db->get_rec("users", "*", "username='$logged_user'");
$lu = new user($rec);

$count = $db->get_rec("messages", "count(*) as ile", "user='{$user->username}' and direct=0", "time desc");	//all messages by this user
$msg_count = $count->ile;
$page_count = ceil($msg_count/mpp);
if(!$page_num) $page_num = 1;
$limit = mpp;
$offset = ($page_num - 1) * $limit;
$next = $page_num + 1;
$prev = $page_num - 1;
$dots = 0;

for($i = 1; $i<=$page_count; $i++){
	$page_numbers[$i] = $i;
}
$result = $db->get_recs("messages m LEFT JOIN groups_mes gm ON gm.group_id=m.group_id", "*,gm.group_title,gm.group_furl", "user='{$user->username}' and direct=0", "time desc limit $limit offset $offset");	//all messages by this user
if($result) $recs = $db->fetch_objects($result);
if(is_array($recs) && ($user->visible || $user->has_friend($lu->id) || $user->username == $lu->username)) foreach($recs as $rec) $messages[] = new message($rec);

if(is_array($messages)){
	foreach($messages as $k=>$m){
		if($k%2==0) echo "<div class='msg yellow'>";
		else echo "<div class='msg'>";
		echo $m->post_image("../post_img/");
		echo "<a href='profile/{$m->user}'><img src='avatars_mini/{$m->get_avatar()}' class='avatar' alt='{$m->user}' /></a>";

		if($m->get_text_color()){
			echo "<div class='when'>{$m->how_long_ago()} ".from." {$m->from}</div>";
			echo "<div class='msg_content'>";
			echo "<a href='profile/{$m->user}' class='username'>{$m->user}</a>";
		} else {
			echo "<div class='when'>{$m->how_long_ago()} ".from." {$m->from}</div>";
			echo "<div class='msg_content'>";
			echo "<a href='profile/{$m->user}' class='username'>{$m->user}</a>";
		}
		if ($m->group_id>0 && (!$group_id || ($group_id && $m->group_id!=$group_id)) ){
			// get group data
			echo ' @<a href="groups/profile/'.$m->group_furl.'">'.$m->group_title.'</a>';
		}
		else
			echo ':';
		echo "<br />";

		if($m->reply) echo in_reply_to."<a href='message/{$m->reply}'>".$m->reply_get_username()."</a>: ";
		echo $m->parse_links();
		echo "</div>";
		echo "<div class='msg_controls'>";
			if($m->user == $lu->username || $lu->username == "admin"){
				form_begin();
				form_hidden("dw", $m->id);
				form_submit_nl("delete", " ", "delete_msg");
				form_end();
			}
			if($user){
				form_begin("reply");
				form_hidden("msg_id", $m->id);
				form_submit_nl("reply", " ", "reply");
				form_end();
				if($m->is_favorite($lu->id)) {
					echo "<img src='grafika/heart_delete.png' class='fav_del' width='16' height='16' alt='{$user->id}_{$m->id}' title='".title_fav_del."' />";
				} else {
					echo "<img src='grafika/heart_add.png' class='fav_add' width='16' height='16' alt='{$user->id}_{$m->id}' title='".title_fav_add."' />";
				}
			}
			echo "</div>";
		echo "</div>";
	}

	if($page_count != 1){
		echo "<p class='mid'>".pagination_page." $page_num / $page_count</p>";
		echo "<p class='mid'>";
		if($page_num!=1) echo "<a href='#' class='pagination mine' rel='{$user->id}___{$prev}___{$logged_user}'>".pagination_prev."</a>";
		foreach($page_numbers as $k=>$i){
			if($i==$page_num) echo $i;
			else if($i == 1) echo "<a href='#' class='pagination mine' rel='{$user->id}___{$i}___{$logged_user}'>$i</a>";
			else {
				if($i < $page_num - treshold || $i > $page_num + treshold){
					if($i != $page_num) {
						if($dots == 0) {
							echo "...";
							$dots = 1;
						}
					}
				} else {
					echo "<a href='#' class='pagination mine' rel='{$user->id}___{$i}___{$logged_user}'>$i</a>";
					$dots = 0;
				}
			}
		}
		if($page_num < $page_count) echo "<a href='#' class='pagination mine' rel='{$user->id}___{$next}___{$logged_user}'>".pagination_next."</a>";
		echo "</p>";
	}
} else {
	if(!$user->visible && $user->username != $lu->username) {
		echo "<p>".updates_protected;
		if($logged_user && !$lu->has_friend($user->id) && !$user->has_friend($lu->id)) echo "<a href='profile/{$user->username}/follow'>".a_add." {$user->username} ".a_as_friend."</a>";
		else if($logged_user && $lu->has_friend($user->id) && !$user->has_friend($lu->id)) echo "<strong>".ok_added_as_friend."</strong>";
		echo "</p>";
	} else echo "<p>".no_msg."</p>";
}
?>