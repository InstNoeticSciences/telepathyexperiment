<h1>Statistics</h1>
<?
$db = new database;
$db->dblink();

//messages
$rec0 = $db->get_rec("messages", "count(*) as qty", "direct=0");
$rec1 = $db->get_rec("messages", "count(*) as qty", "direct=0 and `from`='web'");
$rec2 = $db->get_rec("messages", "count(*) as qty", "direct=0 and `from`='im'");
$rec3 = $db->get_rec("messages", "count(*) as qty", "direct=0 and `from`='mobile'");
echo "<h2>Public messages</h2>";
echo "<table><tr><th>Message count</th><th>Sent from</th></tr>";
echo "<tr><td class='mid'>{$rec1->qty}</td><td>web</td></tr>";
echo "<tr><td class='mid'>{$rec2->qty}</td><td>instant messenger</td></tr>";
echo "<tr><td class='mid'>{$rec3->qty}</td><td>mobile</td></tr>";
echo "<tr><td class='mid'><strong>{$rec0->qty}</strong></td><td><strong>total</strong></td></tr>";
echo "</table>";

//users
$rec = $db->get_rec("users", "count(*) as qty");
echo "<h2>Users</h2>";
echo "<p>Total users count: {$rec->qty}</p>";
echo "<h3>Top ten most active users</h3>";
$result = $db->get_recs("messages", "user, count(*) as qty", "id<>0 group by user", "qty desc limit 10");
$recs = $db->fetch_objects($result);
if(is_array($recs)) {
	echo "<table><tr><th>User</th><th>Number of messages</th></tr>";
	foreach($recs as $rec) echo "<tr><td>{$rec->user}</td><td class='mid'>{$rec->qty}</td></tr>";
	echo "</table>";
}
echo "<h3>Top ten least active users</h3>";
$result = $db->get_recs("messages", "user, count(*) as qty", "id<>0 group by user", "qty asc limit 10");
$recs = $db->fetch_objects($result);
if(is_array($recs)) {
	echo "<table><tr><th>User</th><th>Number of messages</th></tr>";
	foreach($recs as $rec) echo "<tr><td>{$rec->user}</td><td class='mid'>{$rec->qty}</td></tr>";
	echo "</table>";
}
?>
