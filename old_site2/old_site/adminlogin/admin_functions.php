<?
function login($user, $pass){
	if($user == admin_user && $pass == admin_pass) $_SESSION['logged_in'] = true;
	else {
		$_SESSION['logged_in'] = false;
		$error = "Login incorrect. Please make sure you entered a valid user name and password.";
	}
}

function logout(){
	session_destroy();
	$_SESSION['logged_in'] = false;
	header("Location: index.php");
}




//==============================================================
//	database functions
//==============================================================
function get_sponsor_field($id, $field){
	dblink();
	$q = "select $field f from sponsors where id=$id";
	$result = mysql_query($q);
	$rec = @mysql_fetch_object($result);
	return $rec->f;
}

function get_user_field($id, $field){
	dblink();
	$q = "select $field f from users where id=$id";
	$result = mysql_query($q);
	$rec = @mysql_fetch_object($result);
	return $rec->f;
}

function get_product_field($id, $field){
	dblink();
	$q = "select $field f from products where id=$id";
	$result = mysql_query($q);
	$rec = @mysql_fetch_object($result);
	return $rec->f;
}

function debug($stuff){
	echo "<pre>";
	print_r($stuff);
	echo "</pre>";
}

?>