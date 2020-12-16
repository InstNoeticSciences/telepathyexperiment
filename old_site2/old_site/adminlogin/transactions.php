<h1>SMS credit transactions</h1>
<?
$db = new database;
$db->dblink();

if($_POST['del']) $db->db_delete("transactions", "id={$_POST['dw']}");

if($_POST['filter']){
	if($_POST['filter_user']){
		$rec = $db->get_rec("users", "id", "username='{$_POST['filter_user']}'");
		if(!$rec) err("The user you are looking for does not exist");
		else {
			$_SESSION['filter_user'] = $_POST['filter_user'];
			$_SESSION['filter_user_id'] = $rec->id;
			$conditions[] = "user_id={$rec->id}";
		}
	} else {
		unset($_SESSION['filter_user']);
		unset($_SESSION['filter_user_id']);
	}
	if($_POST['filter_date_from']){
		if(!preg_match('/^[0-9][0-9]\/[0-9][0-9]\/[0-9][0-9][0-9][0-9]$/', $_POST['filter_date_from'])) err("Invalid date format");
		else {
			$_SESSION['filter_date_from'] = $_POST['filter_date_from'];
			$p = explode("/", $_POST['filter_date_from']);
			$time_from = mktime(0, 0, 0, $p[0], $p[1], $p[2]);
			$_SESSION['time_from'] = $time_from;
			$conditions[] = "time>=$time_from";
		}
	} else {
		unset($_SESSION['filter_date_from']);
		unset($_SESSION['time_from']);
	}
	if($_POST['filter_date_to']){
		if(!preg_match('/^[0-9][0-9]\/[0-9][0-9]\/[0-9][0-9][0-9][0-9]$/', $_POST['filter_date_to'])) err("Invalid date format");
		else {
			$_SESSION['filter_date_to'] = $_POST['filter_date_to'];
			$p = explode("/", $_POST['filter_date_to']);
			$time_to = mktime(0, 0, 0, $p[0], $p[1], $p[2]);
			$_SESSION['time_to'] = $time_to;
			$conditions[] = "time<=$time_to";
		}
	} else {
		unset($_SESSION['filter_date_to']);
		unset($_SESSION['time_to']);
	}
} else {
	if($_SESSION['filter_user_id']) $conditions[] = "user_id={$_SESSION['filter_user_id']}";
	if($_SESSION['time_from']) $conditions[] = "time>={$_SESSION['time_from']}";
	if($_SESSION['time_to']) $conditions[] = "time>={$_SESSION['time_to']}";
}

$where = "";
if(is_array($conditions)) $where = implode(" and ", $conditions);

$now = time();
$db->db_delete("transactions", "status=0 and $now-time>86400");

$result = $db->get_recs("transactions", "*", $where, "time");
$recs = $db->fetch_objects($result);

form_begin();
form_text("filter_user", "Filter by user:", $_SESSION['filter_user']);
form_text("filter_date_from", "Not older than (mm/dd/yyyyy):", $_SESSION['filter_date_from']);
form_text("filter_date_to", "Not newer than (mm/dd/yyyyy):", $_SESSION['filter_date_to']);
form_submit("filter", "Filter transactions");
form_end();



if(!$_GET['page']) $page = 1;
else $page = $_GET['page'];
$offset = ($page - 1) * spp;
$limit = $offset + spp;

$page_num = ceil(count($recs)/spp);
for($i = 1; $i <= $page_num; $i++) {
	if($i == $page) $pages[] = $i;
	else $pages[] = "<a href='index.php?id=transactions&amp;page=$i'>$i</a>";
}
if(is_array($pages) && count($pages)>1) $pagination = implode(" | ", $pages);


if(is_array($recs)){
	echo $pagination;
	echo "<table><tr><th>No.</th><th>Time</th><th>User</th><th>Credits bought</th><th>Value</th><th>Status</th><th>X</th></tr>";
	for($i=$offset; $i<$limit; $i++){
		if($recs[$i]) $rec = $recs[$i];
		else break;
		if($gray) echo "<tr class='gray'>";
		else echo "<tr>";
		$gray = !$gray;
		echo "<td class='mid'>{$rec->id}</td>";
		echo "<td class='mid'>".date("m/d/Y H:i:s", $rec->time)."</td>";
		echo "<td class='mid'>";
		$u = $db->get_rec("users", "username", "id={$rec->user_id}");
		echo $u->username;
		echo "</td>";
		echo "<td class='mid'>{$rec->credits}</td>";
		echo "<td class='mid'>".sprintf('$%.2f', $rec->value)."</td>";
		echo "<td class='mid'>";
		switch($rec->status){
			case 0: echo "Payment in progress"; break;
			case 1: echo "OK"; break;
			case 2: echo "Cancelled"; break;
		}
		echo "</td>";
		del_form($rec->id);
		echo "</tr>";
	}
	echo "</table>";
	echo $pagination;
}
?>