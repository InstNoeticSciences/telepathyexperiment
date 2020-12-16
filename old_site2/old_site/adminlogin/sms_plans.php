<h1>SMS plans</h1>
<h2>Add a new plan</h2>
<?
form_begin();
form_text("credits", "How many sms credits:");
form_text("price", "Plan price (format: 0.00):");
form_submit("add", "Add plan");
form_end();

if($_POST['add']){
	if(!preg_match('/^[0-9]+$/', $_POST['credits'])) $error = "Credit amount must be a number";
	else if(!preg_match('/^[0-9]+\.*[0-9]*$/', $_POST['price'])) $error = "Invalid price format. Use a dot as a decimal separator and no currency signs or spaces.";
	else {
		$db = new database;
		$db->dblink();
		$result = $db->db_insert("sms_plans", "credits, price", "{$_POST['credits']}, {$_POST['price']}");
		if($result) ok("New sms plan has been created");
		else $error = "An error occured while trying to create a sms plan";
	}
	if($error) err($error);
}
?>
<h2>Existing plans</h2>
<?
$db = new database;
$db->dblink();

if($_POST['del']) $db->db_delete("sms_plans", "id={$_POST['dw']}");

$result = $db->get_recs("sms_plans", "*", "", "credits");
$recs = $db->fetch_objects($result);
if(is_array($recs)){
	echo "<table><tr><th>Number of credits</th><th>Plan price</th><th>X</th></tr>";
	foreach($recs as $rec){
		if($gray) echo "<tr class='gray'>"; else echo "<tr>";
		$gray = !$gray;
		echo "<td class='mid'>{$rec->credits}</td>";
		echo "<td class='mid'>\${$rec->price}</td>";
		del_form($rec->id);
		echo "</tr>";
	}
	echo "</table>";
}
?>