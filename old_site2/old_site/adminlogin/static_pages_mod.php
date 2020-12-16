<h1>Edit a static page</h1>
<?

if($_POST['save']){
	if($_POST['active']) $active = 1; else $active = 0;
	$result = $db->db_update("static_pages", "title='{$_POST['title']}', content='".addslashes($_POST['page_content'])."', active=$active", "id={$_POST['mod']}");
	if($result) ok("Page has been changed successfully");
	else err("An error occured while trying to change the page");
}

$rec = $db->get_rec("static_pages", "*", "id={$_POST['mod']}");

form_begin();
form_hidden("mod", $_POST['mod']);
form_text("title", "Page title:", $rec->title);
form_textarea("page_content", "Page content (text or HTML):", stripslashes($rec->content), "big_text");
form_checkbox("active", "Page is active", $rec->active);
form_submit("save", "Save the page");
form_end();
?>
<p class='mid'><a href="index.php?id=static_pages">Back to static page list</a></p>