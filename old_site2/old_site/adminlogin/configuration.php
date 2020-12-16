<h1>Configuration</h1>
<?
if($_POST['ok']){

	if(!$error){
		$lines = file("../inc/config.php");
		$p = fopen("../inc/config.php", "w");
		flock($p, LOCK_EX);
		foreach($lines as $l){
			if(eregi("thsize", $l)) fwrite($p, "define(\"thsize\", ".$_POST['thsize'].");\n");
			else if(eregi("post_img_size", $l)) fwrite($p, "define(\"post_img_size\", ".$_POST['post_img_size'].");\n");
			else if(eregi("post_img_max_width", $l)) fwrite($p, "define(\"post_img_max_width\", ".$_POST['post_img_max_width'].");\n");
			else if(eregi("post_img_max_height", $l)) fwrite($p, "define(\"post_img_max_height\", ".$_POST['post_img_max_height'].");\n");
			else if(eregi("CONTACT_MAIL", $l)) fwrite($p, "define(\"CONTACT_MAIL\", \"".$_POST['contact_mail']."\");\n");
			else if(eregi("title", $l)) fwrite($p, "define(\"title\", \"".$_POST['title']."\");\n");
			else if(eregi("keywords", $l)) fwrite($p, "define(\"keywords\", \"".$_POST['keywords']."\");\n");
			else if(eregi("description", $l)) fwrite($p, "define(\"description\", \"".$_POST['description']."\");\n");
			else if(eregi("im_account_jabber", $l)) fwrite($p, "define(\"im_account_jabber\", \"".$_POST['im_account_jabber']."\");\n");
			else if(eregi("im_account_msn", $l)) fwrite($p, "define(\"im_account_msn\", \"".$_POST['im_account_msn']."\");\n");
			else if(eregi("im_account_icq", $l)) fwrite($p, "define(\"im_account_icq\", \"".$_POST['im_account_icq']."\");\n");
			else if(eregi("im_account_aim", $l)) fwrite($p, "define(\"im_account_aim\", \"".$_POST['im_account_aim']."\");\n");
			else if(eregi("im_account_yahoo", $l)) fwrite($p, "define(\"im_account_yahoo\", \"".$_POST['im_account_yahoo']."\");\n");
			else if(eregi("sms_user", $l)) fwrite($p, "define(\"sms_user\", \"".$_POST['sms_user']."\");\n");
			else if(eregi("sms_pass", $l)) fwrite($p, "define(\"sms_pass\", \"".$_POST['sms_pass']."\");\n");
			else if(eregi("sms_api_id", $l)) fwrite($p, "define(\"sms_api_id\", \"".$_POST['sms_api_id']."\");\n");
			else if(eregi("gateway_phone", $l)) fwrite($p, "define(\"gateway_phone\", \"".$_POST['gateway_phone']."\");\n");
			else if(eregi("paypal_business", $l)) fwrite($p, "define(\"paypal_business\", \"".$_POST['paypal_business']."\");\n");
			else fwrite($p, $l);
		}
		flock($p, LOCK_UN);
		fclose($p);
		echo "<p class='ok'>Configuration settings changed successfully. Reload the page to see new configuration.</p>";
	}
}

form_begin();
form_text("thsize", "Avatar size [pixels]:", thsize);
form_text("post_img_size", "Max. post image size [bytes]:", post_img_size);
form_text("post_img_max_width", "Max. post image width [pixels]:", post_img_max_width);
form_text("post_img_max_height", "Max. post image height [pixels]:", post_img_max_height);
form_text("contact_mail", "Contact email address:", CONTACT_MAIL);
form_text("title", "Website title:", title);
form_textarea("keywords", "Keywords:", keywords);
form_textarea("description", "Description:", description);
echo "<p>IM accounts:</p>";
form_text("im_account_jabber", "Jabber:", im_account_jabber);
form_text("im_account_msn", "MSN:", im_account_msn);
form_text("im_account_icq", "ICQ:", im_account_icq);
form_text("im_account_aim", "AIM:", im_account_aim);
form_text("im_account_yahoo", "Yahoo:", im_account_yahoo);
echo "<p>Clickatell details:</p>";
form_text("sms_user", "SMS gateway username:", sms_user);
form_text("sms_pass", "SMS gateway password:", sms_pass);
form_text("sms_api_id", "SMS gateway API ID:", sms_api_id);
form_text("gateway_phone", "SMS gateway phone number:", gateway_phone);
echo "<p>Paypal:</p>";
form_text("paypal_business", "Paypal email address:", paypal_business);
form_submit("ok", "Save");
form_end();

?>
