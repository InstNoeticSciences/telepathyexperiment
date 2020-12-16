<?
echo("inlcuding config files\n");
//include("inc/config.php");
//include("lib/database.php");
error_reporting(0);

if(isset($_GET['mobile'])&&isset($_GET['channel'])&&isset($_GET['text'])&&$_GET['text']!=''&&isset($_GET['serviceid'])&&$_GET['serviceid']==2000146) {
	print_r($_GET);
	mysql_connect("localhost", "gozub", "go-zub123");
	mysql_select_db("gozub");
	$mobile = ltrim($_GET['mobile']);
	echo($mobile);
	$sql = "select username, phone from users where phone like '{$mobile}';";
	echo("<br /><br />".$sql."<br /><br />");
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	//$db = new database;
	//$db->dblink();
	//$rec = $db->get_rec("users", "username", "phone = '{$_GET['mobile']}'");
	echo(mysql_error());
	echo($row[1]);
	echo(mysql_affected_rows ($res));
	if($res&&$row[1]!='') {
		echo("adding message");
		mysql_connect("localhost", "gozub", "go-zub123");
		mysql_select_db("gozub");
		$sql2 = "insert into messages(`user`, `time`, `msg`, `from`) values('{$row[0]}', '".time()."',  '{$_GET['text']}', 'sms');";
		echo("<br /><br />".$sql2."<br /><br />");
		$res2 = mysql_query($sql2);
		//$id = $db->db_insert("messages", "`user`, `time`, `msg`, `from`", "'{$rec->username}', '".time()."', '{$_GET['text']}', 'email'");
		if($res2)
			echo("message added");
		else
			echo("message ignored");
	}else{
		echo("user does not exist");
	}
}else{
	echo("unknown params");
}
?>
