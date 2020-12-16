<h1>Users</h1>
<?
$db = new database;
$db->dblink();

if($_POST['del']) {
	if(is_array($_POST['del'])) foreach ($_POST['del'] as $dw=>$v){
		$u = $db->get_rec("users", "avatar", "id=$dw");
		if(is_file("../avatars/{$u->avatar}")) unlink("../avatars/{$u->avatar}");
		if(is_file("../avatars_mini/{$u->avatar}")) unlink("../avatars_mini/{$u->avatar}");
		if(is_file("../avatars25/{$u->avatar}")) unlink("../avatars25/{$u->avatar}");
		$db->db_delete("users", "id=$dw");
	}
}

if(!$_GET['sort']) $_GET['sort'] = "username";

$where = '';
if($_GET['search']) {
	if(!preg_match('/^[a-zA-Z0-9]+$/', $_GET['search'])) $_GET['search'] = '';
	else $where = "username like '%{$_GET['search']}%' or name like '%{$_GET['search']}%' or bio like '%{$_GET['search']}%' or interests like '%{$_GET['search']}%'";
}

echo "<form action='' method='get'>";
form_hidden("id", $_GET['id']);
form_text_nobr("search", "Search for phrase:", $_GET['search']);
form_submit_nl("search_go", "Search");
form_end();

$result = $db->get_recs("users", "*", $where, $_GET['sort']);
$recs = $db->fetch_objects($result);

if(!$_GET['page']) $page = 1;
else $page = $_GET['page'];
$offset = ($page - 1) * spp;
$limit = $offset + spp;

$page_num = ceil(count($recs)/spp);
for($i = 1; $i <= $page_num; $i++) {
	if($i == $page) $pages[] = $i;
	else $pages[] = "<a href='index.php?id=users&amp;sort={$_GET['sort']}&amp;page=$i&search={$_GET['search']}'>$i</a>";
}
if(is_array($pages) && count($pages)>1) $pagination = implode(" | ", $pages);

if(is_array($recs)){
	echo $pagination;
	form_begin();
	echo "<p class='mid'>";
	form_button("select_all", "Select all");
	form_button("deselect_all", "Deselect all");
	form_submit_nl("delete", "Delete selected", "delete");
	form_end();
	echo "</p>";
	echo "<table><tr>
	<th>Avatar</th>
	<th><a href='index.php?id=users&amp;sort=username&amp;page=$page'>User name</a>/password</th>
	<th><a href='index.php?id=users&amp;sort=name&amp;page=$page'>Name</a></th>
	<th><a href='index.php?id=users&amp;sort=email&amp;page=$page'>Contact</a></th>
	<th>Public</th>
	<th>Active</th>
	<th><a href='index.php?id=users&amp;sort=location&amp;page=$page'>Location</a></th>
	<th><a href='index.php?id=users&amp;sort=bio&amp;page=$page'>About</a></th>
	<th><a href='index.php?id=users&amp;sort=interests&amp;page=$page'>Interests</a></th>
	<th><a href='index.php?id=users&amp;sort=age&amp;page=$page'>DoB</a></th>
	<th><a href='index.php?id=users&amp;sort=sms_credits&amp;page=$page'>SMS credits</a></th>
	<th>Messages posted</th>
<th>Edit</th>
<th>X</th></tr>";
	for($i=$offset; $i<$limit; $i++){
		if($recs[$i]) $rec = $recs[$i];
		else break;
		if($gray) echo "<tr class='gray'>";
		else echo "<tr>";
		$gray = !$gray;
		echo "<td class='mid'><img src='../avatars_mini/{$rec->avatar}' alt='' /></td>";
		echo "<td>{$rec->username}/{$rec->pass}</td>";
		echo "<td>{$rec->name}</td>";
		echo "<td>Email: <a href='mailto:{$rec->email}'>{$rec->email}</a><br />Phone: {$rec->phone}<br />IM: {$rec->im_type} ({$rec->im_id})<br />WWW: <a href='{$rec->www}' target='_blank'>{$rec->www}</a></td>";
		echo "<td class='mid'>";
		if($rec->visible) echo "YES"; else echo "NO";
		echo "</td>";
		echo "<td class='mid'>";
		if(!$rec->new) echo "YES"; else echo "NO";
		echo "</td>";
		echo "<td>".urldecode($rec->location)."</td>";
		echo "<td>".urldecode($rec->bio)."</td>";
		echo "<td>".urldecode($rec->interests)."</td>";
		echo "<td class='mid'>".date("m/d/Y", $rec->age)."</td>";
		echo "<td class='mid'>{$rec->sms_credits}</td>";
		echo "<td class='mid'>";
		$c = $db->get_rec("messages", "count(*) as qty", "user='{$rec->username}'");
		echo $c->qty;
		echo "</td>";
		mod_form("users_mod", $rec->id);
		echo "<td class='watch_out'>";
		form_checkbox_nobr("del[{$rec->id}]", "");
		echo "</td>\n";
		echo "</tr>";
	}
	echo "</table>";
	form_end();
	echo $pagination;
}
?>