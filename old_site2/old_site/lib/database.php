<?
class database {
	var $dbhost, $dbname, $dbuser, $dbpass, $dbtype;

	function database(){
		$this->dbhost = db_host;
		$this->dbname = db_name;
		$this->dbuser = db_user;
		$this->dbpass = db_pass;
		$this->dbtype = db_type;
	}

	function dblink() {
		if($this->dbtype == "MySQL"){
			$link = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass);
			mysql_select_db($this->dbname);
			mysql_query("set names 'utf8'");

			// Causes problems with encoding, do not uncoment 
			// mysql_query("set character set utf8");

		} else if($this->dbtype == "PostgreSQL"){
			$link = pg_connect("host={$this->dbhost} dbname={$this->dbname} user={$this->dbuser} password={$this->dbpass}");
		}
		return $link;
	}

	function fselect($q, $mode=0){
		$recs = array();
		if ($this->dbtype == 'MySQL') {
			if ($res = mysql_query($q));
				while($rec = mysql_fetch_object($res)) $recs[] = $rec;
		}
		else if ($this->dbtype == 'PostgreSQL'){
			if ($res = pg_query($q))
				while($rec = pg_fetch_object($res)) $recs[] = $rec;
		}
		return sizeof($recs)==1&&!$mode ? $recs[0] : $recs;
	}

	function get_recs($table, $fields, $where="", $order=""){
		$q = "select $fields from $table";
		if($where) $q .= " where $where";
		if($order) $q .= " order by $order";
		if($this->dbtype == 'MySQL') $result = mysql_query($q);
		else if($this->dbtype == 'PostgreSQL') $result = pg_query($q);
		return $result;
	}

	function get_rec($table, $fields, $where="", $order=""){
		$q = "select $fields from $table";
		if($where) $q .= " where $where";
		if($order) $q .= " order by $order";

		if($this->dbtype == 'MySQL') {
			$result = mysql_query($q);
			if($result) $rec = mysql_fetch_object($result);
			else return false;
		} else if($this->dbtype == 'PostgreSQL') {
			$result = pg_query($q);
			if($result) $rec = pg_fetch_object($result);
			else return false;
		}
		return $rec;
	}

	function fetch_objects($result){
		if(!$result) return false;
		if($this->dbtype == 'MySQL') while($rec = mysql_fetch_object($result)) $recs[] = $rec;
		else if($this->dbtype == 'PostgreSQL') while($rec = pg_fetch_object($result)) $recs[] = $rec;
		return $recs;
	}

	function count_recs($result){
		if(!$result) return false;
		if($this->dbtype == 'MySQL') $rec_count = mysql_num_rows($result);
		else if($this->dbtype == 'PostgreSQL') $rec_count = pg_num_rows($result);
		return $rec_count;
	}

	function db_update($table, $pairs, $where){
		if(is_array($pairs)) $fields = implode(", ", $pairs);
		else $fields = $pairs;
		$q = "update $table set $fields where $where";
		if($this->dbtype == 'MySQL') { 
		$result = mysql_query($q);
		}
		else if($this->dbtype == 'PostgreSQL') $result = pg_query($q);
		if($result) return true;
		else return false;
	}

	function db_replace($table, $fields, $values){
		$q = "replace into $table ($fields) values ($values)";
		if($this->dbtype == 'MySQL') {
			$result = mysql_query($q);
			//$id = mysql_insert_id();
		} else if($this->dbtype == 'PostgreSQL') {
			$result = pg_query($q);
			$r = $this->get_rec($table, "id", pg_last_oid());
			$id = $r->id;
		}
		if($result) return true;
		else return false;
	}

	function db_insert($table, $fields, $values){
		$q = "insert into $table ($fields) values ($values)";
		if($this->dbtype == 'MySQL') {
			$result = mysql_query($q);
			$id = mysql_insert_id();
		} else if($this->dbtype == 'PostgreSQL') {
			$result = pg_query($q);
			$r = $this->get_rec($table, "id", pg_last_oid());
			$id = $r->id;
		}
		if($result) return $id;
		else return false;
	}

	function db_delete($table, $where){
		$q = "delete from $table where $where";
		if($this->dbtype == 'MySQL') $result = mysql_query($q);
		else if($this->dbtype == 'PostgreSQL') $result = pg_query($q);
		if($result) return true;
		else return false;
	}
}
?>
