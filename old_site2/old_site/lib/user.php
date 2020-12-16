<?
class user {
    var $id, $time, $username, $carrier_id, $name, $pass, $email, $visible, $phone, $im_type, $im_id, $age, $interests, $interests_tags, $bio, $location, $www, $notify_friend, $notify_direct, $notify_way, $avatar, $api_key, $dob, $sms_credits, $sms_limit, $used_sms, $lang_id, $is_coordinator;
    var $twitter_com_username;
    var $twitter_com_pass;
    var $twitter_com_send_message;
	var $groups=array();

    function user($rec){
        $this->id = $rec->id;
        $this->time = $rec->time;
        $this->username = $rec->username;
        $this->carrier_id = $rec->carrier_id;
        $this->is_coordinator = $rec->is_coordinator;
        $this->name = $rec->name;
        $this->pass = $rec->pass;
        $this->email = $rec->email;
        $this->visible = $rec->visible;
        $this->phone = $rec->phone;
        $this->im_type = $rec->im_type;
        $this->im_id = $rec->im_id;
        $this->dob = $rec->age;
        $this->age = floor((time() - $this->dob) / (365 * 86400));
        $int = explode(",", urldecode($rec->interests));
        if(is_array($int)) {
            foreach($int as $k=>$v) $int[$k] = "<a href='tag/".trim($v)."/{$this->username}'>".trim($v)."</a>";
            $interests = implode(", ", $int);
            $this->interests_tags = $interests;
        }
        $this->interests = urldecode($rec->interests);
        $this->bio = urldecode($rec->bio);
        $this->location = urldecode($rec->location);
        if($rec->www && !eregi("http://", $rec->www)) $this->www = "http://".$rec->www;
        else $this->www = $rec->www;
        $this->notify_friend = $rec->notify_friend;
        $this->notify_direct = $rec->notify_direct;
        $this->notify_way = $rec->notify_way;
        $this->avatar = $rec->avatar;
        if($rec->api_key) $this->api_key = $rec->api_key;
        else $this->generate_api_key();
        $this->sms_credits = $rec->sms_credits;
        $this->sms_limit = $rec->sms_limit;
        $this->used_sms = $rec->used_sms;
        $this->lang_id = $rec->lang_id;
        
        $this->twitter_com_username = $rec->twitter_com_username;
        $this->twitter_com_pass = $rec->twitter_com_pass;
        $this->twitter_com_send_message = $rec->twitter_com_send_message;
	// Get groups
        $db = new database;
        $db->dblink();
	$this->groups=array();
	$tmp = $db->fselect('SELECT group_id FROM groups_links WHERE user_id=\''.$this->id.'\' ',1);
	if ($tmp) foreach ($tmp as $g) $this->groups[]=$g->group_id;
    }

    function refresh(){
        $db = new database;
        $db->dblink();
        $rec = $db->get_rec("users", "*", "id={$this->id}");
        $this->time = $rec->time;
        $this->username = $rec->username;
        $this->carrier_id = $rec->carrier_id;
        $this->is_coordinator = $rec->is_coordinator;
        $this->name = $rec->name;
        $this->pass = $rec->pass;
        $this->email = $rec->email;
        $this->visible = $rec->visible;
        $this->phone = $rec->phone;
        $this->im_type = $rec->im_type;
        $this->im_id = $rec->im_id;
        $this->dob = $rec->age;
        $this->age = floor((time() - $this->dob) / (365 * 86400));
        $int = explode(",", urldecode($rec->interests));
        if(is_array($int)) {
            foreach($int as $k=>$v) $int[$k] = "<a href='tag/".trim($v)."/{$this->username}'>".trim($v)."</a>";
            $interests = implode(", ", $int);
            $this->interests_tags = $interests;
        }
        $this->interests = urldecode($rec->interests);
        $this->bio = urldecode($rec->bio);
        $this->location = urldecode($rec->location);
        if($rec->www && !eregi("http://", $rec->www)) $this->www = "http://".$rec->www;
        else $this->www = $rec->www;
        $this->notify_friend = $rec->notify_friend;
        $this->notify_direct = $rec->notify_direct;
        $this->notify_way = $rec->notify_way;
        $this->avatar = $rec->avatar;
        if($rec->api_key) $this->api_key = $rec->api_key;
        else $this->generate_api_key();
        $this->sms_credits = $rec->sms_credits;
        $this->sms_limit = $rec->sms_limit;
        $this->used_sms = $rec->used_sms;
        $this->lang_id = $rec->lang_id;

        $this->twitter_com_username = $rec->twitter_com_username;
        $this->twitter_com_pass = $rec->twitter_com_pass;
        $this->twitter_com_send_message = $rec->twitter_com_send_message;
	// Get groups
        $db = new database;
        $db->dblink();
	$this->groups=array();
	$tmp = $db->fselect('SELECT group_id FROM groups_links WHERE user_id=\''.$this->id.'\' ',1);
	if ($tmp) foreach ($tmp as $g) $this->groups[]=$g->group_id;
    }
    function load_pagelist($array, $limit) {
    $j=0;
    for($i=0; $i<count($array); $i++) {

            if($i%$limit==0)
                $j++;

            $page[$j][$i] = $array[$i];

    }
    return $page;
    }
    function make_page_list($ile, $cur_page, $module, $user) {
        $res = "<p class=\"mid\">";
        $prev = $cur_page-1;
        $next = $cur_page+1;
        if($user!='')
            $user .="/";

        if($cur_page>1)
            $res .= "<a href=\"".$module."/".$user."".$prev."\" class=\"pagination\">&#171;</a>";
        for($i=1; $i<=$ile; $i++) {
            if($i!=$cur_page)
            $res .= "<a href=\"".$module."/".$user."".$i."\" class=\"pagination\">".$i."</a>, ";
            else{
            $res .= $i.", </li>";
            }

        }
        if($cur_page<$ile)
            $res .= "<a href=\"".$module."/".$user."".$next."\" class=\"pagination\">&raquo;</a>";
        $res .= "</ul>";
    return $res;
    }

    function add_friend($friend_id){
        $db = new database;
        $db->dblink();
        if(!$this->i_am_blocked($friend_id)){
            $result = $db->db_insert("followed", "user, followed", "{$this->id}, $friend_id");
            //notification
            $u = $db->get_rec("users", "*", "id=$friend_id");
            $user = new user($u);
            if($user->notify_way == 'email'){
                $msg = str_replace("#recipient_name", $user->username, added_as_friend_mail);
                $msg = str_replace("#username", $this->username, $msg);
                $to = $user->email;
                $subject = str_replace("#username", $this->username, added_as_friend_subject);
                $headers = "From: <".CONTACT_MAIL.">";
                $ok = mail($to, $subject, $msg, $headers);
                $db->db_delete("followed", "user={$this->id} and followed={$friend_id} and friend_only=1");
            } else if($user->notify_way == 'sms'){
                if($user->phone && $user->sms_credits > 0){
                    $to = $user->phone;
                    $msg = added_as_friend_sms;
                    $msg = str_replace("#recipient_name", $user->username, $msg);
                    $msg = str_replace("#username", $this->username, $msg);
                    send_sms($to, $msg);
                }
            }
        }
        if($result) return true;
        else return false;
    }

    function remove_friend($friend_id){
        $db = new database;
        $db->dblink();
        $result = $db->db_delete("followed", "user={$this->id} and followed=$friend_id");
        if($result) return true;
        else return false;
    }

    function leave_friend($friend_id){
        $db = new database;
        $db->dblink();
        $result = $db->db_update("followed", "friend_only=1", "user={$this->id} and followed=$friend_id");
        if($result) return true;
        else return false;
    }

    function has_friend($id){
        $db = new database;
        $db->dblink();
        $result = $db->get_recs("followed", "*", "user={$this->id} and followed=$id");
        if($db->count_recs($result)) return true;
        else return false;
    }

    function has_friend_nf($id){
        $db = new database;
        $db->dblink();
        $result = $db->get_recs("followed", "*", "user={$this->id} and followed=$id and friend_only=1");
        if($db->count_recs($result)) return true;
        else return false;
    }

    function block_user($id){
        $db = new database;
        $db->dblink();
        $result = $db->db_insert("blocked_users", "user, blocked_user", "{$this->id}, $id");
        $u = $db->get_rec("users", "*", "id=$id");
        $user = new user($u);
        $user->remove_friend($this->id);
        if($result) return true;
        else return false;
    }

    function unblock_user($id){
        $db = new database;
        $db->dblink();
        $result = $db->db_delete("blocked_users", "user={$this->id} and blocked_user=$id");
        if($result) return true;
        else return false;
    }

    function is_blocked($id){
        $db = new database;
        $db->dblink();
        $rec = $db->get_rec("blocked_users", "count(*) as ile", "user={$this->id} and blocked_user=$id");
        if($rec->ile) return true;
        else return false;
    }

    function i_am_blocked($id){
        $db = new database;
        $db->dblink();
        $rec = $db->get_rec("blocked_users", "count(*) as ile", "user={$id} and blocked_user={$this->id}");
        if($rec->ile) return true;
        else return false;
    }

    function get_friends(){
        $db = new database;
        $db->dblink();
        $result = $db->get_recs("users", "*", "id in (select followed from followed where user={$this->id})", "username asc");
        $recs = $db->fetch_objects($result);
        if(is_array($recs)) foreach($recs as $rec) $friends[] = new user($rec);
        return $friends;
    }

    function get_followers(){
        $db = new database;
        $db->dblink();
        $result = $db->get_recs("users", "*", "id in (select user from followed where followed={$this->id})", "username asc");
        $recs = $db->fetch_objects($result);
        if(is_array($recs)) foreach($recs as $rec) $followers[] = new user($rec);
        return $followers;
    }

    function is_my_follower($id){
        $db = new database;
        $db->dblink();
        $rec = $db->get_rec("followed", "count(*) as ile", "user=$id and followed={$this->id}");
        if($rec->ile) return true;
        else return false;
    }

    function get_favorites(){
        $db = new database;
        $db->dblink();
        $result = $db->get_recs("messages", "*", "id in (select message from favorites where user={$this->id})", "id desc");
        $recs = $db->fetch_objects($result);
        if(is_array($recs)) foreach($recs as $rec) $favs[] = new message($rec);
        if(is_array($favs)) return $favs;
        else return false;
    }

    function count_friends(){
        return count($this->get_friends());
    }

    function count_freq(){
        $db = new database;
        $db->dblink();
        $rec = $db->get_rec("friends", "count(*) as ile", "user2={$this->id} and req=1");
        return $rec->ile;
    }

    function count_followers(){
        return count($this->get_followers());
    }

    function count_updates(){
        $db = new database;
        $db->dblink();
        $rec = $db->get_rec("messages", "count(*) as ile", "user='{$this->username}'");
        return $rec->ile;
    }

    function count_direct_messages(){
        $db = new database;
        $db->dblink();
        $rec = $db->get_rec("messages", "count(*) as ile", "direct={$this->id}");
        return $rec->ile;
    }

    function count_favorites(){
        $db = new database;
        $db->dblink();
        $rec = $db->get_rec("favorites", "count(*) as ile", "user={$this->id}");
        return $rec->ile;
    }

    function add_favorite($msg){
        $db = new database;
        $db->dblink();
        $result = $db->db_delete("favorites", "user={$this->id} and message=$msg");
        $result = $db->db_insert("favorites", "user, message", "{$this->id}, $msg");
    }

    function del_favorite($msg){
        $db = new database;
        $db->dblink();
        $db->db_delete("favorites", "user={$this->id} and message=$msg");
    }

    function side_css(){
        $db = new database;
        $db->dblink();
        $lay = $db->get_rec("layouts", "*", "user={$this->id}");
        $css = "<style type='text/css'>\n";
        if($lay->text_color) $css .= ".side_middle * {color: #{$lay->text_color};}\n";
        if($lay->side_border_color) $css .= ".decorative_bar {background: #{$lay->side_border_color};}\n";
        if($lay->side_fill_color) $css .= ".side_stuff {background: #{$lay->side_fill_color};} ";
        if($lay->link_color) {
            $css .= ".side_stuff a.current, .side_stuff a.current:hover, .side_stuff a.current:visited {color: #{$lay->link_color};}\n";
            $css .= ".side_stuff a, .side_stuff a:visited {color: #{$lay->link_color};}\n";
        }
        $css .= "</style>\n";
        return $css;
    }

    function main_css(){
        $db = new database;
        $db->dblink();
        $lay = $db->get_rec("layouts", "*", "user={$this->id}");
        $css = "<style type='text/css'>\n";
        if($lay->text_color) $css .= "*, h1, h2, h3, h4, #footer, #copyright {color: #{$lay->text_color};}\n";
        if($lay->link_color) {
            $css .= "a, a:visited, #footer a, #footer a:visited, a.username, a.username:visited {color: #{$lay->link_color};}\n";
            $css .= ".msg {border-left: 5px solid #{$lay->link_color};}\n";
            $css .= "input.submit, #profile_header input.submit {background-color: #{$lay->link_color};}\n";
        }
        if($lay->top_area_color) {
            $css .= "div#profile_header {background-color: #{$lay->top_area_color};}\n";
            $css .= ".msg.yellow {border-left: 5px solid #{$lay->top_area_color};}\n";
        }
        if($lay->side_border_color) $css .= ".decorative_bar {background: #{$lay->side_border_color};}\n";
        $css .= "html {";
        if($lay->back_color) $css .= "background-color: #{$lay->back_color}; ";
        if($lay->use_image) {
            if(is_file("backgrounds/".$this->id.".jpg")) $css .= "background-image: url(backgrounds/{$this->id}.jpg); ";
            else $css .= "background-image: url(grafika/back.png); ";
        } else $css .= "background-image: none; ";
        switch($lay->back_tile){
            case "0": $css .= "background-repeat: no-repeat; "; break;
            case "1": $css .= "background-repeat: repeat-x; "; break;
            case "2": $css .= "background-repeat: repeat-y; "; break;
            case "3": $css .= "background-repeat: repeat; "; break;
        }
        if($lay->back_fixed) $css .= "background-attachment: fixed; ";
        $css .= "}";
        $css .= "</style>\n";
        return $css;
    }

    function api_get_data($format, $data_only=0){
        switch($format){
            case "xml":
                $data = "<user>";
                $data .= "<id>{$this->id}</id>";
                $data .= "<username>{$this->username}</username>";
                $data .= "<name>{$this->name}</name>";
                $data .= "<email>{$this->email}</email>";
                $data .= "<phone>{$this->phone}</phone>";
                $data .= "<carrier>{$this->carrier_id}</carrier>";
                $data .= "<im_type>{$this->im_type}</im_type>";
                $data .= "<im_id>{$this->im_id}</im_id>";
                $data .= "<age>{$this->age}</age>";
                $data .= "<interests>{$this->interests}</interests>";
                $data .= "<bio>{$this->bio}</bio>";
                $data .= "<location>{$this->location}</location>";
                $data .= "<www>{$this->www}</www>";
                $data .= "</user>";
            break;
            case "json":
                if($data_only){
                    $data = "{
                    \"id\": \"{$this->id}\",
                    \"username\": \"{$this->username}\",
                    \"name\": \"{$this->name}\",
                    \"email\": \"{$this->email}\",
                    \"phone\": \"{$this->phone}\",
                    \"carrier\": \"{$this->carrier_id}\",
                    \"im_type\": \"{$this->im_type}\",
                    \"im_id\": \"{$this->im_id}\",
                    \"age\": \"{$this->age}\",
                    \"interests\": \"{$this->interests}\",
                    \"bio\": \"{$this->bio}\",
                    \"location\": \"{$this->location}\",
                    \"www\": \"{$this->www}\"
                    }";
                } else $data = "{\"user\": {
                    \"id\": \"{$this->id}\",
                    \"username\": \"{$this->username}\",
                    \"name\": \"{$this->name}\",
                    \"email\": \"{$this->email}\",
                    \"phone\": \"{$this->phone}\",
                    \"carrier\": \"{$this->carrier}\",
                    \"im_type\": \"{$this->im_type}\",
                    \"im_id\": \"{$this->im_id}\",
                    \"age\": \"{$this->age}\",
                    \"interests\": \"{$this->interests}\",
                    \"bio\": \"{$this->bio}\",
                    \"location\": \"{$this->location}\",
                    \"www\": \"{$this->www}\"}
                    }";
            break;
            default: return false;
        }
        return $data;
    }

	function get_groups(){
		$db = new database;
		$db->dblink();
		$result = $db->get_recs("groups_links", "group_id", "user_id='{$this->id}' ", "");
		$recs = $db->fetch_objects($result);
		$o=array();
		if (sizeof($recs)) foreach($recs as $k) $o[]=$k->group_id;
		return $o;
	}

    function generate_api_key(){
        if(!$this->api_key){
            $this->api_key = md5($this->username);
            $db = new database;
            $db->dblink();
            $db->db_update("users", "api_key='{$this->api_key}'", "id={$this->id}");
        }
    }

    function last_update(){
        $db = new database;
        $db->dblink();
        $rec = $db->get_rec("messages", "*", "user='{$this->username}' and direct=0", "time desc limit 1");
        $message = new message($rec);
        return $message;
    }

    function display_age(){
        if($this->dob) return $this->age;
        else return "";
    }

    function reset_sms_limit(){
        $db = new database;
        $db->dblink();
        $db->db_update("users", "used_sms=0", "id={$this->id}");
        $this->used_sms = 0;
    }
}

?>