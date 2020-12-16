<h1>Database export</h1>
<p>What would you like to export?</p>
<?
form_begin();
form_checkbox("structure", "Table structure");
form_checkbox("data", "Data");
form_submit("go", "Export");
form_end();


if($_POST['go']){
	echo "<p>";
	$db = new database;
	$db->dblink();
	echo "Getting table list...<br />";
	$result = mysql_query("show tables");
	$tables = array();
	while($rec = mysql_fetch_object($result)) {
		$field = "Tables_in_".db_name;
		$tables[] = $rec->$field;
	}


	$parts = array();
	foreach($tables as $t){
		if($_POST['structure']){
			echo "Dumping table structure for table $t...<br />";
			$result = mysql_query("show create table $t");
			$rec = mysql_fetch_object($result);
			$parts[] = "\n\n--- Table structure for table: $t\n\n";
			$field_name = "Create Table";
			$parts[] = $rec->$field_name.";\n\n";
		}
		if($_POST['data']){
			echo "Dumping data for table $t...<br />";
			$parts[] = "\n\n--- Data for table: $t\n\n";

			$result = mysql_query("describe $t");
			while($rec = mysql_fetch_object($result)) $types[$rec->Field] = $rec->Type;

			$result = mysql_query("select * from $t");
			while($rec = mysql_fetch_array($result, MYSQL_ASSOC)){
				$data = array();
				foreach($rec as $k=>$v){
					if(!eregi("int", $types[$k])) $data[] = "'$v'";
					else $data[] = $v;
				}
				$values = implode(", ", $data);
				unset($data);
				$parts[] = "insert into $t values ($values);\n";
			}
		}
	}

	echo "Creating dump file... ";
	$time = time();
	$file = fopen("../db_backup/dump_$time.sql", "w+");
	flock($file, LOCK_EX);
	foreach($parts as $p) fwrite($file, $p);
	flock($file, LOCK_UN);
	fclose($file);
	if(is_file("../db_backup/dump_$time.sql")) {
		$size = filesize("../db_backup/dump_$time.sql");
		if($size > 1048576) $size = ceil($size / 1048576)." MB";
		else if($size > 1024) $size = ceil($size / 1024)." kB";
		else $size .= " B";
		echo "OK. <a href='../db_backup/dump_$time.sql'>dump_$time.sql</a> ready to download ($size)<br />";
	} else echo "ERROR CREATING DUMP FILE<br />";
	echo "</p>";

	$sql = implode("", $parts);
	echo "<textarea name='sql' id='sql' class='sql' wrap='off'>$sql</textarea>";
}

?>