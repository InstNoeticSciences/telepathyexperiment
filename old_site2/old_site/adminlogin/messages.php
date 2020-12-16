<h1>Message moderation</h1>
<?
$db = new database;
$db->dblink();

if($_POST['del']) {
	if(is_array($_POST['del'])) foreach ($_POST['del'] as $dw=>$v){
		$db->db_delete("messages", "id=$dw");
		if(is_file("../post_img/$dw.jpg")) unlink("../post_img/$dw.jpg");
		if(is_file("../post_img/{$dw}s.jpg")) unlink("../post_img/{$dw}s.jpg");
		if(is_file("../post_img/$dw.png")) unlink("../post_img/$dw.png");
		if(is_file("../post_img/{$dw}s.png")) unlink("../post_img/{$dw}s.png");
		if(is_file("../post_img/$dw.gif")) unlink("../post_img/$dw.gif");
		if(is_file("../post_img/{$dw}s.gif")) unlink("../post_img/{$dw}s.gif");
	}
}

if($_POST['del_pic']){
	if(is_file($_POST['pic_mini'])) unlink($_POST['pic_mini']);
	if(is_file($_POST['pic_big'])) unlink($_POST['pic_big']);
}


$where = '';
if($_GET['search_go']) {
	if(!preg_match('/^[a-zA-Z0-9]+$/', $_GET['search'])) $_GET['search'] = '';
	else if(!preg_match('/^[a-zA-Z0-9]+$/', $_GET['user'])) $_GET['user'] = '';
	if($_GET['user']) $cond[] = "user='{$_GET['user']}'";
	if($_GET['search']) $cond[] = "msg like '%{$_GET['search']}%'";
	if(is_array($cond)) $where = implode(" and ", $cond);
	if($where) $where = "direct=0 and ($where)";
	else $where = "direct=0";
}

echo "<form action='' method='get'>";
form_hidden("id", $_GET['id']);
form_text("user", "User:", $_GET['user']);
form_text("search", "Phrase in content:", $_GET['search']);
form_submit("search_go", "Search");
form_end();

$result = $db->get_recs("messages", "*", $where, "id desc");
$recs = $db->fetch_objects($result);

if(!$_GET['page']) $page = 1;
else $page = $_GET['page'];
$offset = ($page - 1) * 50;
$limit = $offset + 50;

$page_num = ceil(count($recs)/50);
for($i = 1; $i <= $page_num; $i++) {
	if($i == $page) $pages[] = $i;
	else $pages[] = "<a href='index.php?id=messages&amp;page=$i'>$i</a>";
}
if(is_array($pages) && count($pages)>1) $pagination = implode(" | ", $pages);

if(is_array($recs)) {
	echo $pagination;
	form_begin();
	echo "<p class='mid'>";
	form_button("select_all", "Select all");
	form_button("deselect_all", "Deselect all");
	form_submit_nl("delete", "Delete selected", "delete");
	echo "</p>";
	echo "<table><tr><th>Date and time</th><th>User</th><th>Message</th><th>Image</th><th>Posted from</th><th>Edit</th><th>X</th></tr>\n";
	for($i=$offset; $i<$limit; $i++){
		if($recs[$i]){
			$m = new message($recs[$i]);
			if($gray) echo "\n<tr class='gray'>\n";
			else echo "\n<tr>\n";
			$gray = !$gray;
			echo "<td class='mid'>".date("d.m.Y H:i:s", $m->time)."</td>\n";
			echo "<td>{$m->user}</td>\n";
			echo "<td>{$m->msg}</td>\n";
			echo "<td class='mid'>";
			if(is_file("../post_img/{$m->id}s.jpg")) {
				$imagename_mini = "../post_img/{$m->id}s.jpg";
				$imagename = "../post_img/{$m->id}.jpg";
			} else if(is_file("../post_img/{$m->id}s.png")) {
				$imagename_mini = "../post_img/{$m->id}s.png";
				$imagename = "../post_img/{$m->id}.png";
			} else if(is_file("../post_img/{$m->id}s.gif")) {
				$imagename_mini = "../post_img/{$m->id}s.gif";
				$imagename = "../post_img/{$m->id}.gif";
			} else $imagename = '';
			if($imagename != '') {
				echo "<a href='$imagename'><img src='$imagename_mini' alt='' /></a><br />";
				form_begin();
				form_hidden("pic_mini", $imagename_mini);
				form_hidden("pic_big", $imagename);
				form_submit_nl("del_pic", "Remove image", "delete");
				form_end();
			}
			echo "</td>";
			echo "<td class='mid'>{$m->from}</td>\n";
			mod_form("messages_mod", $m->id);
			echo "<td class='watch_out'>";
			form_checkbox_nobr("del[{$m->id}]", "");
			echo "</td>\n";
			echo "\n</tr>\n";
		}
	}
	echo "</table>";
	form_end();
	echo $pagination;
}
?>