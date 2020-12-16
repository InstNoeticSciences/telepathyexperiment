<h1>Upload logo</h1>
<h2>Main logo</h2>
<p>This is the logo displayed on the front end website. Needs to be a png, based on the image below:</p>
<p class="mid"><img src="../logo/logo_base.png" alt="" /></p>
<?
form_begin("", 1);
form_file("logo1", "File to upload:");
form_submit("upload1", "Upload");
form_end();

if($_POST['upload1']){
	if(is_uploaded_file($_FILES['logo1']['tmp_name'])){
		$size = getimagesize($_FILES['logo1']['tmp_name']);
		if($size['mime'] != "image/png") $error = "Invalid file format";
		else move_uploaded_file($_FILES['logo1']['tmp_name'], "../logo/logo.png");
	} else $error = "No file to upload";
	if($error) err($error);
	else ok("File uploaded successfully");
}
?>

<h2>Favorite icon</h2>
<p>This is the icon (16x16 ico) that shows in the address bar of a browser or on the bookmark list</p>
<?
form_begin("", 1);
form_file("logo2", "File to upload:");
form_submit("upload2", "Upload");
form_end();

if($_POST['upload2']){
	if(is_uploaded_file($_FILES['logo2']['tmp_name'])){
		move_uploaded_file($_FILES['logo2']['tmp_name'], "../logo/favicon.ico");
	} else $error = "No file to upload";
	if($error) err($error);
	else ok("File uploaded successfully");
}
?>

<h2>Vision page logos</h2>
<p>Small logo visible on the bottom.</p>
<?
form_begin("", 1);
form_file("logo3", "File to upload:");
form_submit("upload3", "Upload");
form_end();

if($_POST['upload3']){
	if(is_uploaded_file($_FILES['logo3']['tmp_name'])){
		$size = getimagesize($_FILES['logo3']['tmp_name']);
		if($size['mime'] != "image/png") $error = "Invalid file format";
		else move_uploaded_file($_FILES['logo3']['tmp_name'], "../logo/map_logo_small.png");
	} else $error = "No file to upload";
	if($error) err($error);
	else ok("File uploaded successfully");
}
?>
<p>Big logo visible while the map is loading. </p>
<?
form_begin("", 1);
form_file("logo4", "File to upload:");
form_submit("upload4", "Upload");
form_end();

if($_POST['upload4']){
	if(is_uploaded_file($_FILES['logo4']['tmp_name'])){
		$size = getimagesize($_FILES['logo4']['tmp_name']);
		if($size['mime'] != "image/png") $error = "Invalid file format";
		else move_uploaded_file($_FILES['logo4']['tmp_name'], "../logo/map_logo.png");
	} else $error = "No file to upload";
	if($error) err($error);
	else ok("File uploaded successfully");
}
?>

<p>If you uploaded the logos and don't see any changes on the site, please clear your browser's cache and refresh the page.</p>

