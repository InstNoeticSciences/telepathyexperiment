<h1>Admin password</h1>
<?
if(isset($_POST['ok'])){
	if(!isset($_POST['newpass1']) || !isset($_POST['newpass2'])) $error = "Please enter the new password and confirm it";
	if($_POST['newpass1'] != $_POST['newpass2']) $error = "Password confirmation does not match the given password";

	if(!isset($error)){
		$lines = file("../inc/config.php");
		$p = fopen("../inc/config.php", "w");
		flock($p, LOCK_EX);
		foreach($lines as $l){
			if(eregi("admin_pass", $l)) fwrite($p, "define(\"admin_pass\", \"".$_POST['newpass1']."\");\n");
			else fwrite($p, $l."\n");
		}
		flock($p, LOCK_UN);
		fclose($p);
		echo "<p class='ok'>The password was changed successfully</p>";
	}
}

form_begin();
form_password('newpass1', 'New password:');
form_password('newpass2', 'Confirm password:');
form_submit('ok', 'Change');
form_end();
?>
