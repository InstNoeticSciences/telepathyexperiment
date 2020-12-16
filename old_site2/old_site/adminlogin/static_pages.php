<h1>Static pages</h1>
<p><a href="index.php?id=static_pages_add">Add a static page</a></p>
<?
if($_POST['del']) $db->db_delete("static_pages", "id={$_POST['dw']}");
$result = $db->get_recs("static_pages", "*", "", "title");
$recs = $db->fetch_objects($result);
if(is_array($recs)){
	echo "<table><tr><th>ID</th><th>Page title</th><th>Active</th><th>Content</th><th>Edit</th><th>X</th></tr>";
	foreach($recs as $rec){
		if($gray) echo "<tr class='gray'>";
		else echo "<tr>";
		$gray = !$gray;
		echo "<td>{$rec->id}</td>";
		echo "<td>{$rec->title}</td>";
		if($rec->active) echo "<td class='mid'>YES</td>";
		else echo "<td class='mid'>NO</td>";
		$content = strip_tags(stripslashes($rec->content));
		$content = substr($content, 0, 250);
		echo "<td>".nl2br($content)."</td>";
		mod_form("static_pages_mod", $rec->id);
		del_form($rec->id);
		echo "</tr>";
	}
	echo "</table>";
}
?>