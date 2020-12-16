<h1>User account modification</h1>
<?
$db = new database;
$db->dblink();

if($_POST['save']){
	if(!email_ok($_POST['email'])) $error = "Email incorrect";
	else if(!preg_match('/^[0-9][0-9]\/[0-9][0-9]\/[0-9][0-9][0-9][0-9]$/', $_POST['age'])) $error = "Invalid date format. Correct date format is mm/dd/yyyy, eg. 04/26/1981";
	else {
		if($_POST['visible']) $visible = 1; else $visible = 0;
		if($_POST['new']) $new = 1; else $new = 0;
		$x = 0; $y = 0;
		if($_POST['location']) {
			// geolocalization by googlemaps
			$fd = fopen("http://maps.google.com/maps/geo?q=".urlencode($_POST['location'])."&output=csv&key=ABQIAAAALzxZxZULX9-oXnRMvB1RvxS-ppMTo74UK5LP65eOUWuzYEClfBQGLwC_uVDcU5xIveNkvCVKbhGwCA", "r");
			$data = fread($fd, 5000);
			$data = explode(",", $data);
			//print_r($data);
			if($data[0] == 200) {
				$y = $data[2];
				$x = $data[3];
			}
			fclose($fd);
		}
		if(!eregi("http://", $_POST['www']) && $_POST['www']) $_POST['www'] = "http://".$_POST['www'];
		if(!$_POST['sms_credits']) $_POST['sms_credits'] = 0;
		if($_POST['im_type'] == "None") $_POST['im_type'] = '';
		if(!$_POST['age']) $_POST['age'] = 0;
		else {
			$pieces = explode("/", $_POST['age']);
			$_POST['age'] = mktime(0, 0, 0, $pieces[0], $pieces[1], $pieces[2]);
		}
		$result = $db->db_update("users", "name='{$_POST['name']}', pass='{$_POST['pass']}', email='{$_POST['email']}', phone='{$_POST['phone']}', im_type='{$_POST['im_type']}', im_id='{$_POST['im_id']}', age='{$_POST['age']}', location='".urlencode($_POST['location'])."', bio='".urlencode($_POST['bio'])."', interests='".urlencode($_POST['interests'])."', www='{$_POST['www']}', sms_credits={$_POST['sms_credits']}, visible=$visible, new=$new, x=$x, y=$y", "id={$_POST['mod']}");
		if($result) ok("Changes saved successfully");
		else $error = "An error occured while trying to save user data";
	}
	if($error) err($error);
}

$rec = $db->get_rec("users", "*", "id={$_POST['mod']}");

$im_types_none[] = "None";
$im_types = explode(",", im_list);
$im_types = array_merge($im_types_none, $im_types);

form_begin();
form_hidden("mod", $_POST['mod']);
form_text("name", "Name:", $rec->name);
form_text("pass", "Password:", $rec->pass);
form_text("email", "Email:", $rec->email);
form_text("phone", "Phone:", $rec->phone);
form_select("im_type", "IM type:", $im_types, $im_types, $rec->im_type);
form_text("im_id", "IM id/username:", $rec->im_id);
form_text("age", "Date of birth (mm/dd/yyyy):", date("m/d/Y", $rec->age));
form_text("location", "Location:", urldecode($rec->location));
form_textarea("interests", "Interests:", urldecode($rec->interests));
form_textarea("bio", "About me:", urldecode($rec->bio));
form_text("www", "WWW:", $rec->www);
form_text("sms_credits", "SMS credits:", $rec->sms_credits);
form_checkbox("visible", "Public:", $rec->visible);
form_checkbox("new", "Needs activation:", $rec->new);
form_submit("save", "Save changes");
form_end();
?>
<p class='mid'><a href="index.php?id=users">Back to user list</a></p>