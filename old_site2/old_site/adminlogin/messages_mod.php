<h1>Message modification </h1>
<?
$db = new database;
$db->dblink();

if($_POST['save']) {
	$result = $db->db_update("messages", "msg='".urlencode($_POST['msg'])."'", "id={$_POST['mod']}");
	if($result) ok("Message has been changed");
	else err("An error occured while trying to change this message");
}
$rec = $db->get_rec("messages", "msg", "id={$_POST['mod']}");

echo "<p>Message id: {$_POST['mod']}</p>";

form_begin();
form_hidden("mod", $_POST['mod']);
form_textarea("msg", "Message content", urldecode($rec->msg));
form_submit("save", "Save message");
form_end();
?>
<p class="mid"><a href="index.php?id=messages">Back to message list</a></p>