<?
include("../../inc/config.php");
include("../../lib/functions.php");
include("../../lib/user.php");
include("../../lib/database.php");
include("../../lib/images.php");
include("../../lib/messages.php");
include("../../lib/link_catcher.class.php");

$db = new database;
$db->dblink();

$x = explode("/", str_replace(dirname($_SERVER['SCRIPT_NAME']), "", $_SERVER['REQUEST_URI']));
$y = explode(".", $x[1]);
$action = $y[0];
$format = $y[1];
$api_key = $y[2];

if(!api_key_ok($api_key)) exit();

//user authentication
if(is_numeric($_POST['username'])) $rec = $db->get_rec("users", "*", "id={$_POST['username']} and pass='{$_POST['password']}'");
else $rec = $db->get_rec("users", "*", "username='{$_POST['username']}' and pass='{$_POST['password']}'");
if($rec) $user = new user($rec);
else {
	if($format == "xml") {
		header("Content-Type: text/xml; charset=utf-8");
		echo "<error>User authentication failed</error>";
	} else if($format == "json"){
		header("Content-Type: text/javascript; charset=utf-8");
		echo "{\"error\": {\"msg\": \"User authentication failed\"}}";
	}
	exit();
}

switch($action){
	case "messages":
		$result = $db->get_recs("messages", "*", "direct={$user->id}", "id desc");
		$recs = $db->fetch_objects($result);
		if(is_array($recs)) foreach($recs as $rec) {
			$m = new message($rec);
			$data .= $m->api_get_data($format, 1);
		}
		switch($format){
			case "xml":
				header("Content-Type: text/xml; charset=utf-8");
				$data = "<direct_messages user='{$user->id}'>$data</direct_messages>"; break;
			case "json":
				header("Content-Type: text/javascript; charset=utf-8");
				$data = "{\"direct_messages\": {\"user\": {$user->id}, \"messages\": [$data]}}"; break;
			case "rss":
				$data = "<?xml version='1.0' encoding='utf-8' ?><rss version='2.0'><channel><title>{$user->username}'s Direct messages</title><link>http://gozub.com</link>
					<description>".$_GET['user']."'s messages at Gozub</description><language>en</language><pubDate>".date("r")."</pubDate>".$data."</channel></rss>";
			break;
		}
		echo $data;
		break;
	case "send":
		if(!is_numeric($_POST['direct'])) {
			$rec = $db->get_rec("users", "id", "username='{$_POST['direct']}'");
			$_POST['direct'] = $rec->id;
		}
		$message = new link_catcher($_POST['message']);
		$_POST['message'] = $message->message;
		if(strlen($_POST['message']) <= 140){
			if(!$_POST['from']) $_POST['from'] = 'web';
			if(!$_POST['direct']) $_POST['direct'] = 0;
			if(!$_POST['reply']) $_POST['reply'] = 0;
			$id = $db->db_insert("messages", "user, time, msg, direct, reply, `from`", "'{$user->username}', ".time().", '{$_POST['message']}', {$_POST['direct']}, {$_POST['reply']}, '{$_POST['from']}'");
			if(!$id) $error = "Database error occured while trying to post a message";
			else {
				$u2 = $db->get_rec("users", "*", "id={$_POST['direct']}");
				$recipient = new user($u2);
				if($_SESSION['user']->is_my_follower($recipient->id)){
					if($recipient->notify_way == 'email'){
						$link = $base_href."profile/".$user->username;
						$msg = str_replace("#recipient_name", $recipient_username, direct_notification_mail);
						$msg = str_replace("#author_name", $user->username, $msg);
						$msg = str_replace("#author_link", $link, $msg);
						$msg = str_replace("#message", urldecode($_POST['message']), $msg);
						$to = $recipient->email;
						$subject = str_replace("#username", $user->username, direct_notification_subject);
						$headers = "From: <".CONTACT_MAIL.">";
						mail($to, $subject, $msg, $headers);
					} if($recipient->notify_way == 'sms'){
						if($recipient->phone && $recipient->sms_credits > 0){
							$to = $recipient->phone;
							$msg = direct_notification_sms;
							$msg = str_replace("#recipient_name", $recipient->username, $msg);
							//$msg = str_replace("#author_name", $_SESSION['user']->username, $msg);
							$msg = str_replace("#author_name", $user->username, $msg);
							$msg = str_replace("#message", urldecode($_POST['message']), $msg);
							send_sms($to, $msg);
						}
					} else if($recipient->notify_way == 'im'){
						$db->db_insert("nudges", "user, txt", "{$recipient->id}, '{$user->username}:\n{$_POST['message']}'");
					}
				}


				$m = $db->get_rec("messages", "*", "id=$id");
				$message = new message($m);
			}
		} else {
			$db->db_insert("nudges", "user, txt", "{$user->id}, '".err_msg_too_long.": {$_POST['message']}'");
			$error = err_msg_too_long;
		}

		switch($format){
			case "xml":
				header("Content-Type: text/xml; charset=utf-8");
				if($error) {
					echo "<error>$error</error>";
					exit();
				}
				break;
			case "json":
				header("Content-Type: text/javascript; charset=utf-8");
				if($error){
					echo "{\"error\": {\"msg\": \"User authentication failed\"}}";
					exit();
				}
				break;
		}
		if(!$error) echo $message->api_get_data($format);
		break;
	case "delete":
		$m = $db->get_rec("messages", "*", "direct={$user->id} and id={$_POST['message_id']}");
		$message = new message($m);
		$db->db_delete("messages", "id={$message->id}");
		switch($format){
			case "xml":
				header("Content-Type: text/xml; charset=utf-8");
				if($error) {
					echo "<error>$error</error>";
					exit();
				}
				break;
			case "json":
				header("Content-Type: text/javascript; charset=utf-8");
				if($error){
					echo "{\"error\": {\"msg\": \"User authentication failed\"}}";
					exit();
				}
				break;
		}
		if(!$error) echo $message->api_get_data($format);
		break;
}
?>