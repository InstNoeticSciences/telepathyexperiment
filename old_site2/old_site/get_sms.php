<?
include("inc/config.php");
include("lib/functions.php");
include("lib/database.php");
include("lib/user.php");
include("lib/link_catcher.class.php");
include("lib/messages.php");
require_once("lib/twitter.php");

foreach($_GET as $k=>$v) $_GET[$k] = trim(urldecode($v));

$db = new database;
$db->dblink();
$rec = $db->get_rec("users", "*", "phone='+{$_GET['from']}'");

if($rec) $user = new user($rec);
if($user) {
    //echo "<pre>"; print_r($user); echo "</pre>";
    $message = new link_catcher($_GET['text']);
    $msg = $message->message;
    if(strlen($msg) <= 140){
        $id = $db->db_insert("messages", "user, time, msg, `from`", "'{$user->username}', ".time().", '$msg', 'mobile'");
        if($id) {
            //odjebać sms credit
            $user->sms_credits--;
            $db->db_update("users", "sms_credits={$user->sms_credits}", "id={$user->id}");
            //notification
            $followers = $user->get_followers();
            if(is_array($followers)) foreach($followers as $f){
                if(!$f->notify_direct){
                    if($f->notify_way == 'email'){
                        $mail = notification_mail;
                        $mail = str_replace("#recipient_name", $f->username, notification_mail);
                        $mail = str_replace("#author_name", $user->username, $mail);
                        $mail = str_replace("#author_link", $base_href."profile/".$user->username, $mail);
                        $mail = str_replace("#message", $msg, $mail);
                        $to = $f->email;
                        $subject = str_replace("#username", $user->username, notification_subject);
                        $headers = "From: <".CONTACT_MAIL.">";
                        mail($to, $subject, $msg, $headers);
                    } if($f->notify_way == 'sms'){
                        if($f->phone && $f->sms_credits > 0){
                            $to = $f->phone;
                            $sms = notification_sms;
                            $sms = str_replace("#recipient_name", $f->username, $sms);
                            $sms = str_replace("#author_name", $_SESSION['user']->username, $sms);
                            send_sms($to, $sms);
                        }
                    } else if($f->notify_way == 'im'){
                        $db->db_insert("nudges", "user, txt", "{$f->id}, '{$user->username}:\n$msg'");
                    }
                }
            }
        }
    } else {
        $msg = substr($msg, 0, 140);
        $words = explode(" ", $msg);
        $id = $db->db_insert("messages", "user, time, msg, `from`", "'{$user->username}', ".time().", '$msg', '{$_POST['from']}'");
        if(is_array($words) && count($words) > 5){
            $start = count($words) - 6;
            $w = array_slice($words, $start);
            $msg = implode(" ", $w)."...";
        }
        $db->db_insert("nudges", "user, txt", "{$user->id}, '".im_msg_too_long." $msg'");
    }
    //if flag twitter_com_send_message is set then send message on the twitter.com
    if($user->twitter_com_send_message)
    {
        $tw = new Twitter($user->twitter_com_username,$user->twitter_com_pass);
        $tw->updateStatus($msg);    
    }

   // echo "<pre>"; print_r($user); echo "</pre>";
}
?>