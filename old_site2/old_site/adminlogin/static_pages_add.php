<h1>Add a static page</h1>
<?
form_begin();
form_text("title", "Page title:");
form_textarea("page_content", "Page content (text or HTML):", "", "big_text");
form_checkbox("active", "Page is active", 1);
form_submit("save", "Save the page");
form_end();

if($_POST['save']){
	if($_POST['active']) $active = 1; else $active = 0;
	$result = $db->db_insert("static_pages", "title, content, active", "'{$_POST['title']}', '".addslashes($_POST['page_content'])."', $active");
	if($result) ok("New page has been created successfully");
	else err("An error occured while trying to create a new page");
}
?>
<p class='mid'><a href="index.php?id=static_pages">Back to static page list</a></p>