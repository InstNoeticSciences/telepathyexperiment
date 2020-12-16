<?
function load_stuff($page){
	if(!$page) $page = 'home';
	if(preg_match("#^[a-z0-9_-]+$#i",$page) ){
		$filename = $page.".php";
		if(is_file($filename)) include($filename);
	}
}

function load_settings($page){
	if(!$page) $page = 'my_profil';
	if(preg_match("#^[a-z0-9_-]+$#i",$page) ){
		$filename = $page.".php";
		if(is_file($filename)) include($filename);
	}
}

function sendmail($address, $subject, $message, $name, $sender_addr) {
	if(!$subject || !$message || !$sender_addr || !$name) return err_fill_all;
	else if(email_ok($sender_addr)){
		$subject = str_replace("@", "", $subject);
		$subject = str_replace("\n", "", $subject);
		$name = str_replace("@", "", $name);
		$name = str_replace("\n", "", $name);
		if(mail($address, $subject, $message, "Content-Type: text/plain; charset=iso8859-1\nFrom: $name<$sender_addr>")) return true;
		else return err_email;
	} else return err_email_incorrect;
}

function seed() {
   list($usec,$sec) = explode(" ", microtime());
   return ((float)$sec+(float)$usec) * 100000;
}

function random($min, $max){
	srand(seed());
	return rand($min, $max);
}

function email_ok($email) {
	$pattern = "/^[a-zA-Z0-9][a-zA-Z0-9\.-_]+\@([a-zA-Z0-9_-]+\.)+[a-zA-Z]+$/";
	if(preg_match($pattern, $email)) return true;
	else return false;
}

function clear(){
	echo "<div class='clear'>&nbsp;</div>";
}

function ok($msg){
	echo "<p class='ok'>$msg</p>";
}
function err($msg){
	echo "<p class='error'>$msg</p>";
}

function random_string($length)  {
   $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
   srand(seed());
   for($i=0;$i<$length;$i++) $key.= $pattern{rand(0,35)};
   return $key;
}

function user_exists($name){
	$db = new database;
	$db->dblink();
	$result = $db->get_recs("users", "*", "username='$name'");
	$ile = $db->count_recs($result);
	if($ile != 0) return true;
	else return false;
}

function email_exists($email){
	$db = new database;
	$db->dblink();
	$result = $db->get_recs("users", "*", "email='$email'");
	$ile = $db->count_recs($result);
	if($ile != 0) return true;
	else return false;
}

function get_files($directory){
	if ($handle = opendir($directory)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") $files[] = $file;
		}
		closedir($handle);
	}
	if(is_array($files)) sort($files);
	return $files;
}

function simpleXor($InString, $Key) {
  $KeyList = array();
  $output = "";
  for($i = 0; $i < strlen($Key); $i++){
    $KeyList[$i] = ord(substr($Key, $i, 1));
  }
  for($i = 0; $i < strlen($InString); $i++) {
    $output.= chr(ord(substr($InString, $i, 1)) ^ ($KeyList[$i % strlen($Key)]));
  }
  return $output;
}
function encrypt($plain) {
  $output = "";
  $output = base64_encode(simpleXor($plain, encryption_key));
  return $output;
}

function decrypt($scrambled) {
  $output = "";
  $scrambled = str_replace(" ","+",$scrambled);
  $output = simpleXor(base64_decode($scrambled), encryption_key);
  return $output;
}

function api_key_ok($api_key){
	$db = new database;
	$db->dblink();
	$rec = $db->get_rec("users", "username", "api_key='$api_key'");
	if(md5($rec->username) == $api_key) return true;
	else return false;
}

function get_most_recent_users($count=20){
	$db = new database;
	$db->dblink();
	$result = $db->get_recs("messages", "distinct user", "direct=0", "time desc limit $count");
	$recs = $db->fetch_objects($result);
	if(is_array($recs)) foreach($recs as $rec) {
		$r = $db->get_rec("users", "*", "username='{$rec->user}'");
		$most_recent[] = new user($r);
	}
	return $most_recent;
}
function get_most_popular_users($count=20){
	$db = new database;
	$db->dblink();
	$result = $db->get_recs("followed", "followed, count(*) how_many", "id>0 group by followed", "how_many desc limit $count");
	$recs = $db->fetch_objects($result);
	if(is_array($recs)) foreach($recs as $rec){
		$r = $db->get_rec("users", "*", "id={$rec->followed}");
		$most_popular[] = new user($r);
	}
	return $most_popular;
}

function send_sms($to, $sms){
	if($to){
		$user = sms_user;
		$password = sms_pass;
		$api_id = sms_api_id;
		$baseurl ="http://api.clickatell.com";
		$text = urlencode($sms);
		//$to = "0123456789";
		// auth call
		$url = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";
		// do auth call
		$ret = file($url);
		// split our response. return string is on first line of the data returned
		$sess = split(":",$ret[0]);
		if ($sess[0] == "OK") {
			$sess_id = trim($sess[1]); // remove any whitespace
			$url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text";
			// do sendmsg call
			$ret = file($url);
			$send = split(":",$ret[0]);
			if ($send[0] == "ID"){
		//odjebać 1 credit userowi:
				$db = new database;
				$db->dblink();
				$rec = $db->get_rec("users", "id, sms_credits, sms_limit, used_sms", "phone=$to");
				if($rec->used_sms < $rec->sms_limit){
					$new_used_sms = $rec->used_sms + 1;
					$sms_credits_new = $rec->sms_credits - 1;
					$db->db_update("users", "sms_credits=$sms_credits_new, used_sms=$new_used_sms", "id={$rec->id}");
					$db->db_insert("transactions", "user, time, credits, status", "{$rec->id}, ".time().", -1, 1");
					if(is_array($send)) $response = implode(";\n", $send);
					//mail("support@revou.com", "sms sent", "to: $to\nsms: $sms\n\n$response");
					return true;
				}
			} else {
				if(is_array($send)) $response = implode(";\n", $send);
				//mail("support@revou.com", "sms not sent", "to: $to\nsms: $sms\n\n$response");
				return false;
			}
		} else {
			if(is_array($sess)) $response = implode(";\n", $sess);
			//mail("support@revou.com", "error", "to: $to\nsms: $sms\n\n$response");
		}
	}
}

function pr($a,$f=0){echo '<pre>';print_r($a);echo '</pre>';if($f)exit;}
function get_langfilename($lang_current){
	return strtolower($lang_current).'_lang.php';
}

?>