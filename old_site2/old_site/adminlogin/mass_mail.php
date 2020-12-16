<h1>Mass mailing</h1>
<?
form_begin();
form_text("subject", "Email subject:");
form_textarea("message", "Message:", "", "big_text");
form_submit("send", "Send now");
form_end();

if($_POST['send']){
	$result = $db->get_recs("users", "email", "new=0", "username");
	$recs = $db->fetch_objects($result);
	if(is_array($recs)) foreach($recs as $rec) {
		echo "Sending to {$rec->email}...";
		$ok = mail($rec->email, $_POST['subject'], $_POST['message'], "From: Admin<".contact_mail.">");
		if($ok) echo "OK"; else echo "ERROR";
		echo "<br />";
	}
}
?>