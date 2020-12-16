<?     
include("../../inc/config.php");
include("../../lib/functions.php");
include("../../lib/user.php");
include("../../lib/database.php");
include("../../lib/images.php");
include("../../lib/messages.php");
include("../../lib/link_catcher.class.php");
include("../../inc/text.php");
require_once("../../lib/twitter.php");
       
$db = new database;
$db->dblink();
$x = explode("/", str_replace(dirname($_SERVER['SCRIPT_NAME']), "", $_SERVER['REQUEST_URI']));
if(count($x) == 2) {
    $y = explode(".", $x[1]);
    $action = $y[0];
    $format = $y[1];
} else {
    $action = $x[1];
    $y = explode(".", $x[2]);
    $u = $y[0];
    $format = $y[1];
}      
$api_key = $y[2];

if(!api_key_ok($api_key)) exit();
   
if($u) {
    if(is_numeric($u)) $rec = $db->get_rec("users", "*", "id=$u and visible=1");
    else $rec = $db->get_rec("users", "*", "username='$u' and visible=1");
    $user = new user($rec);
}

switch($action){
    case "public":
        $result = $db->get_recs("messages", "*", "direct=0 and user in (select username from users where visible=1)", "time desc limit ".mpp);
        $recs = $db->fetch_objects($result);
        if(is_array($recs)) foreach($recs as $rec){
            $m = new message($rec);
            $data .= $m->api_get_data($format, 1);
        }
        switch ($format){
            case "xml":
                header("Content-Type: text/xml; charset=utf-8");
                echo "<messages>".$data."</messages>";
                break;
            case "json":
                header("Content-Type: text/javascript; charset=utf-8");
                echo "{\"messages\": [".$data."]}";
                break;
            case "rss":
                $data = "<?xml version='1.0' encoding='utf-8' ?><rss version='2.0'><channel><title>Gozub.com public messages</title><link>http://gozub.com</link>
                    <description>Latest ".mpp." messages at gozub.com</description><language>en</language><pubDate>".date("r")."</pubDate>".$data."</channel></rss>";
            break;
        }
    break;
    case "friends":
        $result = $db->get_recs("messages", "*", "direct=0 and user in (select username from users where id in (select followed from followed where user={$user->id}) and visible=1)", "time desc");
        $recs = $db->fetch_objects($result);
        if(is_array($recs)) foreach($recs as $rec){
            $m = new message($rec);
            $data .= $m->api_get_data($format, 1);
        }
        switch ($format){
            case "xml":
                header("Content-Type: text/xml; charset=utf-8");
                echo "<messages>".$data."</messages>";
                break;
            case "json":
                header("Content-Type: text/javascript; charset=utf-8");
                echo "{\"messages\": [".$data."]}";
                break;
            case "rss":
                $data = "<?xml version='1.0' encoding='utf-8' ?><rss version='2.0'><channel><title>{$user->username}'s friends' messages</title><link>http://gozub.com</link>
                    <description>{$user->username}'s friends' messages at gozub.com</description><language>en</language><pubDate>".date("r")."</pubDate>".$data."</channel></rss>";
            break;
        }
    break;
    case "user":
        $result = $db->get_recs("messages", "*", "user='{$user->username}' and direct=0", "time desc");
        $recs = $db->fetch_objects($result);
        if(is_array($recs)) foreach($recs as $rec){
            $m = new message($rec);
            $data .= $m->api_get_data($format, 1);
        }
        switch ($format){
            case "xml":
                header("Content-Type: text/xml; charset=utf-8");
                echo "<messages>".$data."</messages>";
                break;
            case "json":
                header("Content-Type: text/javascript; charset=utf-8");
                echo "{\"messages\": [".$data."]}";
                break;
            case "rss":
                $data = "<?xml version='1.0' encoding='utf-8' ?><rss version='2.0'><channel><title>{$user->username}'s messages</title><link>http://gozub.com</link>
                    <description>{$user->username}'s messages at gozub.com</description><language>en</language><pubDate>".date("r")."</pubDate>".$data."</channel></rss>";
            break;
        }
    break;
    case "send":

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
        $message = new link_catcher($_POST['message']);
        $_POST['message'] = $message->message;
        if(strlen($_POST['message']) <= 140){
            if(!$_POST['from']) $_POST['from'] = 'web';

            $id = $db->db_insert("messages", "user, time, msg, `from`", "'{$user->username}', ".time().", '{$_POST['message']}', '{$_POST['from']}'");
            if(!$id) $error = "Database error occured while trying to post a message";
            else {
                
                //notification
                $followers = $user->get_followers();
                if(is_array($followers)) foreach($followers as $f){
                    if(!$f->notify_direct){
                        if($f->notify_way == 'email'){
                            $msg = notification_mail;
                            $msg = str_replace("#recipient_name", $f->username, notification_mail);
                            $msg = str_replace("#author_name", $user->username, $msg);
                            $msg = str_replace("#author_link", $base_href."profile/".$user->username, $msg);
                            $msg = str_replace("#message", urldecode($_POST['message']), $msg);
                            $to = $f->email;
                            $subject = str_replace("#username", $user->username, notification_subject);
                            $headers = "From: <".CONTACT_MAIL.">";
                            mail($to, $subject, $msg, $headers);
                        } if($f->notify_way == 'sms'){
                            if($f->phone && $f->sms_credits > 0){
                                $to = $f->phone;
                                $msg = notification_sms;
                                $msg = str_replace("#recipient_name", $f->username, $msg);
                                //$msg = str_replace("#author_name", $_SESSION['user']->username, $msg);
                                $msg = str_replace("#author_name", $user->username, $msg);
                                $msg = str_replace("#message", urldecode($_POST['message']), $msg);
                                send_sms($to, $msg);
                            }
                        } else if($f->notify_way == 'im'){
                            $db->db_insert("nudges", "user, txt", "{$f->id}, '{$user->username}:\n{$_POST['message']}'");
                        }
                    }
                }

                $m = $db->get_rec("messages", "*", "id=$id");
                $message = new message($m);
            }
        } else {
            $msg = substr($_POST['message'], 0, 140);
            $words = explode(" ", $msg);
            $id = $db->db_insert("messages", "user, time, msg, `from`", "'{$user->username}', ".time().", '$msg', '{$_POST['from']}'");
            if(is_array($words) && count($words) > 5){
                $start = count($words) - 6;
                $w = array_slice($words, $start);
                $msg = implode(" ", $w)."...";
            }
            //echo "the message is: $msg";
            $db->db_insert("nudges", "user, txt", "{$user->id}, '".im_msg_too_long." $msg'");
            $error = err_msg_too_long;
        }
        
        //if flag twitter_com_send_message is set then send message on the twitter.com
        if($user->twitter_com_send_message)
        {
            $tw = new Twitter($user->twitter_com_username,$user->twitter_com_pass);
            $tw->updateStatus(substr($_POST['message'], 0, 140));    
        }

        if($format == "xml") {
            header("Content-Type: text/xml; charset=utf-8");
            if($error) echo "<error>$error</error>";
        } else if($format == "json"){
            header("Content-Type: text/javascript; charset=utf-8");
            if($error) echo "{\"error\": {\"msg\": \"$error\"}}";
        }
        if(!$error) echo $message->api_get_data($format);
    break;
    case "delete":
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

        $m = $db->get_rec("messages", "*", "id={$_POST['message_id']}");
        $message = new message($m);
        $data = $message->api_get_data($format);
        $result = $db->db_delete("messages", "user='{$user->username}' and id={$_POST['message_id']}");
        if(!$result) $error = "Database error occured while trying to remove a message";

        if($format == "xml") {
            header("Content-Type: text/xml; charset=utf-8");
            if($error) echo "<error>$error</error>";
        } else if($format == "json"){
            header("Content-Type: text/javascript; charset=utf-8");
            if($error) echo "{\"error\": {\"msg\": \"$error\"}}";
        }
        if(!$error) echo $data;
    break;
    case "latest":
        $rec = $db->get_rec("messages", "*", "user='{$user->username}'", "time desc limit 1");
        $m = new message($rec);
        switch ($format){
            case "xml":
                header("Content-Type: text/xml; charset=utf-8");
                echo $m->api_get_data($format);
                break;
            case "json":
                header("Content-Type: text/javascript; charset=utf-8");
                echo $m->api_get_data($format);
                break;
            case "rss":
                $data = "<?xml version='1.0' encoding='utf-8' ?><rss version='2.0'><channel><title>{$user->username}'s messages</title><link>http://gozub.com</link>
                    <description>{$user->username}'s messages at gozub.com</description><language>en</language><pubDate>".date("r")."</pubDate>".$data."</channel></rss>";
            break;
        }
    break;

}
?>
