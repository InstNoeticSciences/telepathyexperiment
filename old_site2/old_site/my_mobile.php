<h2>My IM and mobile phone</h2>
<?
$db = new database;
$db->dblink();
if($_POST['save']){
	if(!$error){
		$result = $db->db_update("users", "phone='{$_POST['phone']}', im_type='{$_POST['im_type']}', im_id='{$_POST['im_id']}'", "id={$_SESSION['user_id']}");
		if($result) ok("Profile data saved");
		else $error = "Could not modify user data";
	}
	if($error) err($error);
}
$rec = $db->get_rec("users", "phone, im_type, im_id", "id={$_SESSION['user_id']}");

$im = array("ICQ", "MSN", "GTalk", "AIM", ".Mac", "Yahoo Messenger", "Jabber");

form_begin();
form_text('phone', 'My mobile number:', $rec->phone);
form_select('im_type', 'Instant messenger:', $im, $im, $rec->im_type);
form_text('im_id', 'IM ID or number:', $rec->im_id);
form_submit('save', 'Save', 'save');
form_end();
?>
<p class="srodek"><img src="logos/aim.jpg" width="32" height="20" class="im_logo" alt="AIM" /><img src="logos/gtalk.jpg" width="42" height="20" class="im_logo" alt="GTalk" /><img src="logos/icq.jpg" width="47" height="20" class="im_logo" alt="ICQ" /><img src="logos/jabber.jpg" width="48" height="20" class="im_logo" alt="Jabber" /><img src="logos/mac.jpg" width="17" height="20" class="im_logo" alt=".Mac" /><img src="logos/msn.jpg" width="52" height="20" class="im_logo" alt="MSN" /><img src="logos/yahoo.jpg" width="64" height="20" class="im_logo" alt="Yahoo Messenger" /></p>
