<h1>Database import</h1>
<?
form_begin("", 1);
form_textarea("sql", "SQL code (thic code is executed when you don't upload any sql files):", "", "big_text");
form_file("sql_file", "Upload sql file:");
form_submit("go", "Import");
form_end();

if($_POST['go']){
	if(is_uploaded_file($_FILES['sql_file']['tmp_name'])){
		$filename = "../db_backup/".$_FILES['sql_file']['name'];
		move_uploaded_file($_FILES['sql_file']['tmp_name'], $filename);
		$lines = file($filename);
		foreach($lines as $k=>$l){
			if(substr($l, 0, 3) == "---") unset($lines[$k]);
		}
		$code = implode("", $lines);
		$db = new database;
		$db->dblink();
		$queries = array();
		$queries = explode(";", $code);
		foreach($queries as $q){
			echo "$q... ";
			$result = mysql_query($code);
			if($result) echo "OK<br />";
			else echo "ERROR<br />";
		}
	} else {
		$db = new database;
		$db->dblink();
		$queries = array();
		$queries = explode(";", $_POST['sql']);
		foreach($queries as $q){
			$q = stripslashes($q);
			echo "$q... ";
			$result = mysql_query($q);
			if($result) echo "OK<br />";
			else echo "ERROR<br />";
		}
	}
}
?>