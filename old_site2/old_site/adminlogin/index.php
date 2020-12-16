<?
include("../inc/config.php");
include("../lib/functions.php");
include("../lib/forms.php");
include("../lib/images.php");
include("../lib/database.php");
include("../lib/messages.php");
include("admin_functions.php");
session_start();
if($_POST['enter']) login($_POST['login'], $_POST['pass']); //user login
if($_GET['out']) logout(); //user logout
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><? echo title ?> - Admin Panel</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="css/tags.css" type="text/css" />
  <link rel="stylesheet" href="css/divs.css" type="text/css" />
  <link rel="stylesheet" href="css/links.css" type="text/css" />
  <link rel="stylesheet" href="css/text.css" type="text/css" />
  <link rel="stylesheet" href="css/forms.css" type="text/css" />
  <link rel="stylesheet" href="css/tables.css" type="text/css" />

	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="script.js"></script>
</head>
<body>
<div id="top">
	<a name="topofpage"></a>
	<h1><? echo title ?> - Administration</h1>
	<div id="menu"><? if($_SESSION['logged_in']) include("menu.php") ?></div>
</div>

<div id="main_content"><?
	//including content other than login form only if login is correct
	if($_SESSION['logged_in']){
		$db = new database;
		$db->dblink();
		if(!$_GET['id']) $_GET['id'] = 'home';
		if(preg_match("#^[a-z0-9_]+$#i",$_GET['id']) ){
			$db = new database;
			$db->dblink();
			$filename = $_GET['id'].".php";
			if(is_file($filename)) include($filename);
		}
	} else include("login.php");
?></div>
<div id="bottom"><a href="#topofpage">Top</a> | <a href="logout.php">Logout</a> | <a href="<? echo root_domain ?>">Home page</a></div>
</body>
</html>
