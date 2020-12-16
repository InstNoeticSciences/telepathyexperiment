<?php
// header( "Content-Type: text/html; charset=UTF-8" );
// header( "Cache-Control: no-cache, max-age=0" );

include("inc/config.php");

include("lib/functions.php");
include("lib/user.php");
include("lib/groups.php");
include("lib/forms.php");
include("lib/database.php");
include("lib/images.php");
include("lib/messages.php");
include("lib/link_catcher.class.php");
include("lib/sms.class.php");
include('smarty/Smarty.class.php');
//require_once("lib/twitter.php");
session_start();
//print_r('<pre>');
//print_r($_SESSION);
//print_r('</pre>');
$path = dirname(__FILE__);
$smarty = new Smarty;
$smarty->template_dir = "$path/templates";
$smarty->compile_dir = "$path/compile";
$base_href = dirname("http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'])."/";
$smarty->assign('base_href', $base_href);
$smarty->assign('root_domain', $root_domain);
$smarty->assign('keywords', keywords);
$smarty->assign('description', description);
$smarty->assign('title', title);
$smarty->assign('RPX_API_URL', RPX_API_URL);
$smarty->assign('RPX_TOKEN_URL', RPX_TOKEN_URL);
$page_limit = 20;//max users per page
//assigning all texts

//decide which page to load
$v = explode("/", str_replace($_SERVER['SCRIPT_NAME'], "", $_SERVER['REQUEST_URI'])); 
$page = $v[1];

//echo 'page name: '.$page;

//print_r('<pre>');
//print_r($_GET);
//print_r('</pre>');

//database connection
$db = new database;
$db->dblink();

if ($page=='openIDLogin') {

	
	if(isset($_REQUEST['token']) && $_REQUEST['token'] ) { 
		
	
		
		require_once('openIDLoginCls.php');

		$token = $_REQUEST['token'];


		$rpx = new RPX(RPX_API_KEY, RPX_API_URL);

		$arr = $rpx->auth_info($token);


		include_once('lib/openID.class.php');

		include_once('lib/user.php');
	

		$objOpenID = new openID();

	

		$userID = $objOpenID->getLoginId($arr);

	

		if ($userID) {

	

			$selectQuery = "SELECT * FROM users WHERE id='{$userID}' ";

			

			
	

			$resource = mysql_query($selectQuery);

	

			$userObj = mysql_fetch_object($resource);

	

			$_SESSION['user'] = new user($userObj);

		    $_SESSION['user_id'] = $_SESSION['user']->id;

			

	

		    

		    unset($_SESSION['logged_out']);

	

	

		}

		

		if (isset($_SESSION['user']) && $_SESSION['user'] ) {

			header("Location: ".root_domain."/profile/{$_SESSION['user']->username}");

			exit();			

		}
	
	} else {
				
		
		header("Location: ".root_domain);
		
		
	}



}

if($page == 'profile') {
    $rec = $db->get_rec("users", "id, visible", "username='{$v[2]}'");
    $userid = $rec->id;
}
if(!$page) $page = "home";
if($page == "home" && $v[2] == "register") $smarty->assign("reg", 1);

//  OLD lang-logic
//  include("inc/text.php");
//  $lines = file("inc/text.php");

// CHANGE LANGUAGE!
if('change_lang'==$page && $_POST['lang'] ){
    if ($_SESSION['user']->id){
        $_POST['lang'] = addslashes($_POST['lang']);
        $rec = $db->get_rec("lang_list", "lang_id", "lang_short_name='".$_POST['lang']."'");
        if ($rec->lang_id)
            $db->db_update("users", "lang_id='{$_POST['lang']}' ", "id={$_SESSION['user']->id}");
    }
    else{
        setcookie('lang_id', $_POST['lang'], time() + 86400 * 60 );
    }

    if (!empty($_SERVER['HTTP_REFERER']))
        header('Location: '.$_SERVER['HTTP_REFERER']);
    else
        header('Location: '.root_domain);
    exit;
}


// START SELECT LANGUAGE -----------------------
// get user-lang
$user_lang = lang_default;
if ($_SESSION['user']->id){
    $rec = $db->get_rec("users", "lang_id", "id='".$_SESSION['user']->id."'");
    $user_lang = $rec->lang_id;
}
else if ($_COOKIE['lang_id']){
    $user_lang = $_COOKIE['lang_id'];
}
$smarty->assign("lang", $user_lang );

// get language list
$result = $db->get_recs("lang_list", "*", "", 'lang_short_name');
$recs = $db->fetch_objects($result);
$langs=array();
if(sizeof($recs)) foreach($recs as $rec) $langs[$rec->lang_short_name] = $rec->lang_full_name;
$smarty->assign("langs", $langs);

// define name of langFile
$lang_file = 'langs/'.get_langfilename( $user_lang );
if (!file_exists($lang_file))
    $lang_file = 'langs/'.get_langfilename( lang_default );

// load Language file
include($lang_file);
$lines = file($lang_file);
if(is_array($lines)) foreach($lines as $l){
    if(eregi("define", $l)){
        $start = strpos($l, '(') + 1;
        $end = strpos($l, ',', $start + 1);
        $const_name = substr($l, $start, $end-$start);

        $start = strpos($l, '"') + 1;
        $end = strrpos($l, '"');
        $const_text = substr($l, $start, $end-$start);
        if($const_name != 'nudge_ok' && $const_name != 'follow_ok' && $const_name != 'leave_ok' && $const_name != 'remove_ok' && $const_name != 'block_ok' && $const_name != 'unblock_ok'){
            $smarty->assign($const_name, stripslashes($const_text));
        }
    }
}

// END LANGUAGE -----------------------
if($_POST['create_account']){
    if(!$_POST['accept_terms']) $smarty->assign("error_terms", err_accept_terms);
    else if(!$_POST['username']) $smarty->assign("error_username", err_choose_username);
    else if(!preg_match('/^[a-zA-Z0-9]+$/', $_POST['username'])) $smarty->assign("error_username_chars", err_username_chars);
    else if(user_exists($_POST['username'])) $smarty->assign("error_username", err_account_exists);
    else if(!$_POST['pass1'] || $_POST['pass1'] != $_POST['pass2']) $smarty->assign("error_pass", err_password_mismatch);
    else if(!email_ok($_POST['email'])) $smarty->assign("error_email", err_email_incorrect);
    else if($_POST['code1'] != $_POST['code2']) $smarty->assign("error_code", err_invalid_code);
    else if(email_exists($_POST['email'])) $smarty->assign("error_email", err_email_exists);
    else {
        if($_POST['visible']) $visible = 1; else $visible = 0;
        $db = new database;
        $db->dblink();
        $id = $db->db_insert("users", "time, username, pass, email, visible", time().", '{$_POST['username']}', '{$_POST['pass1']}', '{$_POST['email']}', $visible");
        if($id) {
            $rec = $db->get_rec("users", "*", "id=$id");
            $_SESSION['user'] = new user($rec);

            //confirmation email
            $msg = str_replace("#username", $_SESSION['user']->username, confirmation_mail);
            $msg = str_replace("#link", $base_href."welcome/".encrypt($_SESSION['user']->username."###".$_SESSION['user']->pass), $msg);
            mail($_SESSION['user']->email, confirmation_subject, $msg, "From: Admin<".CONTACT_MAIL.">");

            $files = get_files('sample_photos');
            $file = $files[0];
            $old = "sample_photos/$file";

            $parts = explode(".", $file);
            $last = count($parts) - 1;
            $ext = $parts[$last];

            $filename = $_SESSION['user']->id.".".$ext;
            $new = "avatars_mini/$filename";
            if(copy($old, $new)) $db->db_update("users", "avatar='$filename'", "id={$_SESSION['user']->id}");
            copy($new, "avatars25/$filename");
            resize_picture(25, 25, "avatars25/$filename", $ext);

            //confirmation email
            //$msg = str_replace("#username", $_SESSION['user']->username, confirmation_mail);
            //$msg = str_replace("#link", $base_href."welcome/".encrypt($_SESSION['user']->username."###".$_SESSION['user']->pass), $msg);
            //mail($_SESSION['user']->email, confirmation_subject, $msg, "From: Admin<".CONTACT_MAIL.">");
            unset($_SESSION['user']);
            $page = "welcome_info";
        } else $error = err_create_account;
    }
    if($page != "welcome_info") {
        $page = "home";
        $smarty->assign("error", $error);
        $smarty->assign("reg", 1);
    }
} //login a user
if($_POST['login']){
    $result = $db->get_recs("users", "*", "(username='{$_POST['user']}' or email='{$_POST['user']}') and pass='{$_POST['pass']}' and new=0");
    $how_many = $db->count_recs($result);
    if($how_many != 0) {
        $rec = $db->fetch_objects($result);
        $_SESSION['user'] = new user($rec[0]);
        $_SESSION['user_id'] = $_SESSION['user']->id;
        if($_POST['remember_me']){
            setcookie("login_user", $_SESSION['user']->username, time() + 86400 * 60);
            setcookie("login_pass", $_SESSION['user']->pass, time() + 86400 * 60);
        }
        unset($_SESSION['logged_out']);
        header("Location: profile/{$_SESSION['user']->username}");
    } else {
        $result = $db->get_recs("users", "*", "(username='{$_POST['user']}' or email='{$_POST['user']}') and pass='{$_POST['pass']}' and new=1");
        $how_many = $db->count_recs($result);
        if($how_many != 0){
            //confirmation email
            $recs = $db->fetch_objects($result);
            if(is_array($recs)) foreach($recs as $rec){
                $u = new user($rec);
                $msg = str_replace("#username", $u->username, confirmation_mail);
                $msg = str_replace("#link", $base_href."welcome/".encrypt($u->username."###".$u->pass), $msg);
                mail($u->email, confirmation_subject, $msg, "From: Admin<".CONTACT_MAIL.">");
                $smarty->assign("login_error", err_account_inactive);
            } else $smarty->assign("login_error", err_login_incorrect);
        } else $smarty->assign("login_error", err_login_incorrect);
    }
}

//check if the user is logged in
if($_SESSION['user']){
    $rec = $db->get_rec("users", "id", "username='{$_SESSION['user']->username}'");
    if($rec->id != $_SESSION['user_id']) $_SESSION['user_id'] = $rec->id;
} else {
    //if not logged in but remembered in cookies
    if($_COOKIE['login_user'] && $_COOKIE['login_pass'] && !$_SESSION['logged_out']){
        $rec = $db->get_rec("users", "*", "(username='{$_COOKIE['login_user']}' or email='{$_COOKIE['login_user']}') and pass='{$_COOKIE['login_pass']}'");
        if($rec) $_SESSION['user'] = new user($rec);
        header("Location: profile/{$_SESSION['user']->username}");
    }
    $code = random_string(5);
    $smarty->assign("code", $code);
} //delete message
if($_POST['delete']) {
    $db->db_delete("messages", "id={$_POST['dw']}");
    if(is_file("post_img/{$_POST['dw']}.jpg")) unlink("post_img/{$_POST['dw']}.jpg");
    if(is_file("post_img/{$_POST['dw']}s.jpg")) unlink("post_img/{$_POST['dw']}s.jpg");
    if(is_file("post_img/{$_POST['dw']}.png")) unlink("post_img/{$_POST['dw']}.png");
    if(is_file("post_img/{$_POST['dw']}s.png")) unlink("post_img/{$_POST['dw']}s.png");
    if(is_file("post_img/{$_POST['dw']}.gif")) unlink("post_img/{$_POST['dw']}.gif");
    if(is_file("post_img/{$_POST['dw']}s.gif")) unlink("post_img/{$_POST['dw']}s.gif");
}
//follow user
if($_POST['follow']) $_SESSION['user']->add_friend($_POST['friend_id']);
if($_POST['remove_friend']) $_SESSION['user']->remove_friend($_POST['friend_id']);
if($_POST['stop_following']) $_SESSION['user']->leave_friend($_POST['friend_id']);
if($_POST['start_following']) $_SESSION['user']->add_friend($_POST['friend_id']);

//load static pages added by admin
$result = $db->get_recs("static_pages", "*", "active=1", "title");
$spages = $db->fetch_objects($result);
if(is_array($spages)){
    foreach($spages as $s) $static_pages_links[] = str_replace(" ", "-", $s->title)."-".$s->id;
    $smarty->assign("static_pages", $spages);
    $smarty->assign("static_pages_links", $static_pages_links);
}

//do all other stuff
switch($page){
    case "home":
        //getting the messages
        if($_SESSION['user']) $result = $db->get_recs("messages m LEFT JOIN groups_mes gm ON gm.group_id=m.group_id", "*,gm.group_title,gm.group_furl", "direct=0 and user in (select username from users where id not in (select user from blocked_users where blocked_user={$_SESSION['user']->id})) and (user in (select username from users where visible=1) or user in (select username from users where id in (select user from followed where followed={$_SESSION['user']->id}) and visible=0))", "time desc");
        else $result = $db->get_recs("messages m LEFT JOIN groups_mes gm ON gm.group_id=m.group_id", "*,gm.group_title,gm.group_furl", "direct=0 and user in (select username from users where visible=1)", "time desc");

        $recs = $db->fetch_objects($result);
        $msg_count = count($recs);
        $page_count = ceil($msg_count/mpp);
        $page_num = $v[2];

        if(!$page_num || !is_numeric($page_num)) $page_num = 1;
        $limit = mpp;
        $offset = ($page_num - 1) * $limit;
        $next = $page_num + 1;
        $prev = $page_num - 1;
        $dots = 0;
        $smarty->assign("dots", $dots);
        $smarty->assign("page_num", $page_num);
        $smarty->assign("page_count", $page_count);
        $smarty->assign("next", $next);
        $smarty->assign("prev", $prev);
        $smarty->assign("treshold", treshold);

        for($i = 1; $i<=$page_count; $i++){
            $page_numbers[$i] = $i;
        }
        $smarty->assign("page_numbers", $page_numbers);

        if(is_array($recs))
            foreach($recs as $k=>$rec)
                if($k >= $offset && $k < $limit+$offset) $messages[] = new message($rec);

        $smarty->assign("messages", $messages);

    break;
    case "groups":
	if ($v[2]=='list' && $v[3]){ // list group of Category
		$smarty->assign('groups_tree', 2 );
		$group_furl = $v[3];
		$group_furl_hash = md5($group_furl);
		if ($group_furl){ // Get Group data
			$g_data     = $db->fselect( 'SELECT * FROM groups_mes WHERE group_furl_hash=\''.$group_furl_hash.'\' ;' );
			if (sizeof($g_data->group_tags)) {
				$g_data->group_tags = explode(',', $g_data->group_tags);
			}
			if (sizeof($g_data)) {
				foreach($g_data->group_tags as $k=>$v) {
					$g_data->group_tags[$k] = trim($v);
				}
				$g_subgroups= $db->fselect( 'SELECT * FROM groups_mes WHERE parent_id=\''.$g_data->group_id.'\' ORDER BY order_id ASC ', 1);
			}
			$smarty->assign('group_data', $g_data);
//pr($g_data);
			$smarty->assign('group_subgroups', $g_subgroups);
			$smarty->assign('group_id', $g_data->group_id);
		}
	} elseif ($v[2]=='profile' && $v[3]){ // Profile of group
		$smarty->assign('groups_tree', 3 );
		$group_furl = $v[3];
		$group_furl_hash = md5($group_furl);
		if ($group_furl){ // Get Group data
			$g_data     = $db->fselect( 'SELECT * FROM groups_mes WHERE group_furl_hash=\''.$group_furl_hash.'\' ;' );
//pr($g_data);
			if (sizeof($g_data->group_tags)) $g_data->group_tags = explode(',', $g_data->group_tags);
			foreach($g_data->group_tags as $k=>$v) {
				$g_data->group_tags[$k] = trim($v);
			}
//			$g_subgroups= $db->fselect( 'SELECT * FROM groups_mes WHERE parent_id=\''.$group_id.'\' ORDER BY order_id ASC ', 1);
			if ($g_data->level_id>1){
				$g_data->created_n = date("d/m/Y", $g_data->created);
				// Get parent
				$g_parent_data     = $db->fselect( 'SELECT * FROM groups_mes WHERE group_id=\''.$g_data->parent_id.'\' ;' );
				$smarty->assign('g_parent_data', $g_parent_data);
				// Get Statistics
				$tmp= $db->fselect( 'SELECT count(*) as c_m FROM groups_links WHERE group_id=\''.$g_data->group_id.'\' ;' );
				$cnt['users']= $tmp->c_m;
				$tmp= $db->fselect( 'SELECT count(*) as c_c FROM messages WHERE group_id=\''.$g_data->group_id.'\' ;' );
				$cnt['mes']  = $tmp->c_c;
				$smarty->assign('group_stat', $cnt);
//pr($cnt);
			}
			$smarty->assign('group_data', $g_data);
			$smarty->assign('group_id', $g_data->group_id);
//pr($_SESSION['user']);
//pr($g_data);
//			$smarty->assign('group_subgroups', $g_subgroups);
		}
	} elseif ($v[2]=='search') {
		if (!empty($v[3])){
			$_POST['search_title'] = $v[3];
			$_POST['search_descr'] = $v[3];
			$_POST['search_tags']  = $v[3];
		}
//		else if (!empty($_POST['search_words'])) {
//			$_POST['search_title'] = $_POST['search_words'];
//			$_POST['search_descr'] = $_POST['search_words'];
//			$_POST['search_tags']  = $_POST['search_words'];
//		}

		$smarty->assign('group_search_flag', 1 );

		$search_ = array();
		if ($_POST['search_title']){
			$smarty->assign('search_title', htmlspecialchars($_POST['search_title']) );
			$search_[] = ' group_title LIKE \'%'.mysql_real_escape_string($_POST['search_title']).'%\' ';
		}
		if ($_POST['search_descr']){
			$smarty->assign('search_descr', htmlspecialchars($_POST['search_descr']) );
			$search_[] = ' group_descr LIKE \'%'.mysql_real_escape_string($_POST['search_descr']).'%\' ';
		}
		if ($_POST['search_tags']){
			$smarty->assign('search_tags', htmlspecialchars($_POST['search_tags']) );
			$search_[] = ' group_tags LIKE \'%'.mysql_real_escape_string($_POST['search_tags']).'%\' ';
		}

		$recs = $db->fselect("SELECT * FROM groups_mes WHERE ".(sizeof($search_)?implode(' OR ', $search_):"0"), 1);
	        if(is_array($recs)) foreach($recs as $rec) $found_groups[] = new group($rec);
	        if(sizeof($found_groups)<$page_limit) {
	            $smarty->assign("found_groups", $found_groups);
	        }else{
	            $pagelist = $group->load_pagelist($found_group, $page_limit);
	            if($v[2]&&$v[3]!=='') {
	                $pages = $group->make_page_list(count($pagelist), $v[3], "search/".$search_string, '');
	                $smarty->assign("pages", $pages);
	                $smarty->assign("found_groups", $pagelist[$v[3]]);
	            }else{
	                $pages = $group->make_page_list(count($pagelist), 1, "search/".$search_string, '');
	                $smarty->assign("pages", $pages);
	                $smarty->assign("found_groups", $pagelist[1]);
	            }
	        }
	        $smarty->assign("search_word",$search_word);
	        $smarty->assign("num_of_results","( ".sizeof($found_groups)." )");

		
	} elseif ($v[2]=='join' && $v[3] && is_numeric($v[4])){
		$group_furl = $v[3];
		$group_furl_hash = md5($group_furl);
		$join_user_id	= intval($v[4]);
		if ($group_furl && $join_user_id && $_SESSION['user']->id==$join_user_id){
			group::join_user($join_user_id, $group_furl_hash);
			if (!empty($_SERVER['HTTP_REFERER'])){
				header('Location: '.$_SERVER['HTTP_REFERER']);
			} else {
				header('Location: '.root_domain);
			}
			exit;
		}	
	} elseif ($v[2]=='unjoin' && $v[3] && is_numeric($v[4])){
		$group_furl = $v[3];
		$group_furl_hash = md5($group_furl);
		$unjoin_user_id	= intval($v[4]);
		if ($group_furl && $unjoin_user_id && $_SESSION['user']->id==$unjoin_user_id){
			group::unjoin_user($_SESSION['user']->id, $group_furl_hash);
			if (!empty($_SERVER['HTTP_REFERER'])){
				header('Location: '.$_SERVER['HTTP_REFERER']);
			} else {
				header('Location: '.root_domain);
			}
			exit;
		}
	} else{
		// Get All groups
		$list_groups = group::get_all_group_tree('level_id=1');
		$smarty->assign('list_groups', $list_groups );
		$smarty->assign('groups_tree', 1 );
	}

    break;
    case "profile":
        $timestamp = time();
        $smarty->assign("timestamp", $timestamp);

	// Get All groups
	$list_groups = group::get_all_group_tree();
	$smarty->assign('list_groups', $list_groups );

        //if user adds a message
        if($_POST['add_message'] && $_SESSION['user']){
            $message = new link_catcher($_POST['message']);
            $_POST['message'] = $message->message;
	    $_POST['message_group'] = intval($_POST['message_group'])<0 ? 0: intval($_POST['message_group']);

            if(strlen($_POST['message']) > max_length) $error = err_msg_too_long;
            else {
                //$_POST['message'] = urlencode(addslashes($_POST['message']));
                $id = $db->db_insert("messages", "user, time, group_id, msg", "'{$_POST['user']}', ".time().", '{$_POST['message_group']}', '{$_POST['message']}'");
                if($_FILES['add_photo']['tmp_name'] != '' && is_int($id)) {
                    $uploaddir = 'post_img/';
                    $ext = get_ext_from_mime($_FILES['add_photo']['type']);
                    $uploadfile = $uploaddir . $id.".".$ext;
                    $uploadfileS = $uploaddir.$id."s.".$ext;
                    if(($_FILES['add_photo']['size']<post_img_size)&&(($_FILES['add_photo']['type']=="image/jpeg")||($_FILES['add_photo']['type']=="image/gif")||($_FILES['add_photo']['type']=="image/png"))) {
                        if(move_uploaded_file($_FILES['add_photo']['tmp_name'], $uploadfile)) {
	                chmod($uploadfile, 0644);
                            copy($uploadfile, $uploadfileS);
                            $size = new_picture_size(post_img_max_width, post_img_max_height, $uploadfile);
                            resize_picture($size[0], $size[1], $uploadfileS, $ext);
                        }
                   } else $error = err_file_too_large;
                }
                //if flag twitter_com_send_message is set then send message on the twitter.com
                if($_SESSION['user']->twitter_com_send_message)
                {
                    $tw = new Twitter($_SESSION['user']->twitter_com_username,$_SESSION['user']->twitter_com_pass);
                    $tw->updateStatus($_POST['message']);    
                }
                //notification about new message
                $followers = $_SESSION['user']->get_followers();
                if(is_array($followers)) foreach($followers as $f){
                    if($f->notify_direct == 0 && !$f->has_friend_nf($_SESSION['user']->id)){
                        if($f->notify_way == 'email'){
                            $msg = notification_mail;
                            $msg = str_replace("#recipient_name", $f->username, notification_mail);
                            $msg = str_replace("#author_name", $_SESSION['user']->username, $msg);
                            $msg = str_replace("#author_link", $base_href.$_SESSION['user']->username, $msg);
                            $msg = str_replace("#message", urldecode($_POST['message']), $msg);
                            $to = $f->email;
                            $subject = str_replace("#username", $_SESSION['user']->username, notification_subject);
                            $headers = "From: Admin<".CONTACT_MAIL.">";
                            mail($to, $subject, $msg, $headers);
                        } else if($f->notify_way == 'sms'){
                            if($f->phone && $f->sms_credits > 0){
                                $to = $f->phone;
                                $msg = notification_sms;
                                $msg = str_replace("#recipient_name", $f->username, $msg);
                                $msg = str_replace("#author_name", $_SESSION['user']->username, $msg);
                                $msg = str_replace("#message", urldecode($_POST['message']), $msg);
                                send_sms($to, $msg);
                            }
                        } else if($f->notify_way == 'im' && !$f->has_friend_nf($_SESSION['user']->id)){
                            $db->db_insert("nudges", "user, txt", "{$f->id}, '{$_SESSION['user']->username}:\n{$_POST['message']}'");
                        }
                    }
                }
		// If Message from GROUP page
		if ($id && $_POST['user_from_group_page']==1){
			// redirect to Group-page url
			if (!empty($_SERVER['HTTP_REFERER'])){
				header('Location: '.$_SERVER['HTTP_REFERER']);
				exit;
			}
		}

            }
            if($error) $smarty->assign("error", $error);
	
        }

        $u = $db->get_rec("users", "*", "username='{$v[2]}'");  //the user whose profile we're loading
        $user = new user($u);
        $smarty->assign("user", $user);

        $friends = $user->get_friends();
        $smarty->assign("friends", $friends);

        //nudge user
        if($_SESSION['user']){
            switch($v[3]){
                case "nudge":

                    $result = $db->db_insert("nudges", "user, txt", $user->id.", '".mysql_escape_string("{$_SESSION['user']->username}: ".nudge)."'");
                    $ok = mail($user->email, nudge_subject, nudge."\n{$_SESSION['user']->username}", "Content-Type: text/plain; charset=iso8859-1\nFrom: {$_SESSION['user']->username}<{$_SESSION['user']->email}>");
                    if($ok) $smarty->assign("nudge_ok", str_replace("#username", $user->username, nudge_ok));
                    break;
                case "follow":
                    if($_SESSION['user']->has_friend_nf($user->id)){
                        $_SESSION['user']->remove_friend($user->id);
                        $_SESSION['user']->add_friend($user->id);
                    } else $_SESSION['user']->add_friend($user->id);

                    $smarty->assign("follow_ok", str_replace("#username", $user->username, follow_ok));
                    $smarty->assign("tab", "mine");
                    break;
                case "remove":
                    $_SESSION['user']->remove_friend($user->id);
                    $smarty->assign("remove_ok", str_replace("#username", $user->username, remove_ok));
                    $smarty->assign("tab", "mine");
                    break;
                case "leave":
                    $_SESSION['user']->leave_friend($user->id);
                    $smarty->assign("leave_ok", str_replace("#username", $user->username, leave_ok));
                    $smarty->assign("tab", "mine");
                    break;
                case "block":
                    $_SESSION['user']->block_user($user->id);
                    $smarty->assign("block_ok", str_replace("#username", $user->username, block_ok));
                    $smarty->assign("tab", "mine");
                    break;
                case "unblock":
                    $_SESSION['user']->unblock_user($user->id);
                    $smarty->assign("unblock_ok", str_replace("#username", $user->username, unblock_ok));
                    $smarty->assign("tab", "mine");
                    break;
                case "with_friends":
                    $smarty->assign("tab", "with_friends");
                    break;
                case "replys":
                    $smarty->assign("tab", "replys");
                    break;
                case "customize":
                    $smarty->assign("tab", "customize");
                    if($_POST['reset_layout']){
                        if(is_file("backgrounds/{$_SESSION['user']->id}.jpg")) unlink("backgrounds/{$_SESSION['user']->id}.jpg");
                        $db->db_delete("layouts", "user={$_SESSION['user']->id}");
                        $ok = ok_back_to_defaults;
                    }
                    if($_POST['save_layout']) {
                        if($_POST['use_image']) $use_image = 1; else $use_image = 0;
                        if($_POST['back_fixed']) $back_fixed = 1; else $back_fixed = 0;
                        $result = $db->db_update("layouts", "back_fixed=$back_fixed, use_image=$use_image, side_border_color='{$_POST['side_border_color']}', side_fill_color='{$_POST['side_fill_color']}', top_area_color='{$_POST['top_area_color']}', text_color='{$_POST['text_color']}', link_color='{$_POST['link_color']}', back_color='{$_POST['back_color']}', back_tile={$_POST['back_tile']}, bubble_text_color='{$_POST['bubble_text_color']}', bubble_fill_color='{$_POST['bubble_fill_color']}'", "user={$_SESSION['user']->id}");
                        if($result) {
                            $ok = ok_layout_changed;
                            if(is_uploaded_file($_FILES['back_image']['tmp_name'])){
                                if($_FILES['back_image']['size'] < 512000) move_uploaded_file($_FILES['back_image']['tmp_name'], "backgrounds/{$_SESSION['user']->id}.jpg");
                                else $error = err_file_too_large;
                            } else if($_POST['background_name']){
                                copy("bglib/{$_POST['background_name']}", "backgrounds/{$_SESSION['user']->id}.jpg");
                            }
                        } else $error = err_layout_change;
                    }
                    if($error) $smarty->assign("error", $error);
                    $rec = $db->get_rec("layouts", "*", "user={$_SESSION['user']->id}");
                    if(!$rec) {
                        $db->db_insert("layouts", "user", "{$_SESSION['user']->id}");
                        $rec = $db->get_rec("layouts", "*", "user={$_SESSION['user']->id}");
                    }
                    $smarty->assign("layout", $rec);
                    $smarty->assign("profile_file", "profile_customize.tpl");
                    break;
                default:
                    $smarty->assign("tab", "mine");
                    break;
            }
        } else {
            $smarty->assign("tab", "mine");
        }
        if(!$v[3] || ($v[3] != 'with_friends' && $v[3] != 'replys' && $v[3] != "customize")){
            $message = $db->get_rec("messages", "*", "user='{$v[2]}' and direct=0", "time desc limit 1");   //latest message
            if($message){
                $msg = new message($message);
                $smarty->assign("first_msg", $msg);
            }
        }
        break;
    case "url":
        if(isset($v[2])&&($v[2]!='')) {
            if(is_int($v[2])){
                $rec = $db->get_rec("tiny_url", "url", " id = '".$v[2]."'", "id desc limit 1");
                $url = $rec->url;
                if(!$url)
                    $url = root_domain;
                header("Location: ".$url);
            }else{
                $pattern = "[a-zA-Z0-9]{5}";
                if(ereg($pattern, $v[2])) {
                    $rec = $db->get_rec("tiny_url", "url", " tiny = '".$v[2]."'", "id desc limit 1");
                    $url = $rec->url;
                    if(!$url)
                            $url = root_domain;
                    header("Location: ".$url);
                }else{
                    $url = root_domain;
                    header("Location: ".$url);
                }
            }

        }else{
            header("Location: ".root_domain);
        }

        break;
    case "favorites":
        $u = $db->get_rec("users", "*", "username='{$v[2]}'");
        $user = new user($u);
        $friends = $user->get_friends();
        $smarty->assign("friends", $friends);

        $smarty->assign("user", $user);

        if($_POST['delete']) $_SESSION['user']->del_favorite($_POST['dw']);
        $u = $db->get_rec("users", "*", "username='{$v[2]}'");  //the user whose profile we're loading
        $user = new user($u);
        if(!$user->visible && !$user->has_friend($_SESSION['user']->id) && $_SESSION['user']->id != $user->id) header("Location: /home");
        $smarty->assign("user", $user);

        $favorites = $user->get_favorites();
        if(count($favorites)<$page_limit) {
            $smarty->assign("favorites", $favorites);
        }else{
            $pagelist = $user->load_pagelist($favorites, $page_limit);
            if($v[3]!='') {
                $pages = $user->make_page_list(count($pagelist), $v[3], "favorites", $v[2]);
                $smarty->assign("pages", $pages);
                $smarty->assign("favorites", $pagelist[$v[3]]);


            }else{
                $pages = $user->make_page_list(count($pagelist), 1, "favorites", $v[2]);
                $smarty->assign("pages", $pages);
                $smarty->assign("favorites", $pagelist[1]);
            }
        }
        break;
    case "direct_message":
        if(!$_SESSION['user']) header("Location: home");
        $u = $db->get_rec("users", "*", "username='{$v[2]}'");
        $user = new user($u);
        $smarty->assign("user", $user);
        if($_POST['add_message'] && $_SESSION['user']){
            if(strlen($_POST['message']) > max_length) $error = err_msg_too_long;
            else $db->db_insert("messages", "user, time, msg, direct", "'{$_POST['user']}', ".time().", '{$_POST['message']}', {$_POST['direct']}");
            $u2 = $db->get_rec("users", "*", "id={$_POST['direct']}");
            $recipient = new user($u2);
            if($_SESSION['user']->is_my_follower($recipient->id)){
                if($recipient->notify_way == 'email'){
                    $link = $base_href.$_SESSION['user']->username;
                    $msg = str_replace("#recipient_name", $recipient_username, direct_notification_mail);
                    $msg = str_replace("#author_name", $_SESSION['user']->username, $msg);
                    $msg = str_replace("#author_link", $link, $msg);
                    $msg = str_replace("#message", $_POST['message'], $msg);
                    $to = $recipient->email;
                    $subject = str_replace("#username", $_SESSION['user']->username, direct_notification_subject);
                    $headers = "Content-Type: text/plain; charset=iso8859-1\nFrom: <".CONTACT_MAIL.">";
                    mail($to, $subject, $msg, $headers);
                } else if($recipient->notify_way == 'sms'){
                    if($recipient->phone && $recipient->sms_credits > 0){
                        $to = $recipient->phone;
                        $msg = direct_notification_sms;
                        $msg = str_replace("#recipient_name", $recipient->username, $msg);
                        $msg = str_replace("#author_name", $_SESSION['user']->username, $msg);
                        send_sms($to, $msg);
                    }
                } else if($recipient->notify_way == 'im'){
                    $db->db_insert("nudges", "user, txt", "{$recipient->id}, '{$_SESSION['user']->username}:\n{$_POST['message']}'");
                }
            }
            if($error) $smarty->assign("error", $error);
            else $smarty->assign("ok", ok_msg_sent);
        }
        break;
    case "direct_messages":
        if(!$_SESSION['user']) header("Location: home");
        $result = $db->get_recs("messages", "*", "direct={$_SESSION['user']->id}", "time desc");    //all direct messages to this user
        $recs = $db->fetch_objects($result);
        if(is_array($recs)) foreach($recs as $rec) $messages[] = new message($rec);
        if(count($messages)<$page_limit) {
            $smarty->assign("messages", $messages);
        }else{
            $pagelist2 = $user->load_pagelist( $messages, $page_limit);
            if($v[3]!=='') {
                $pages = $user->make_page_list(count($pagelist2), $v[3], "direct_messages", $_SESSION['user']->username);
                $smarty->assign("pages", $pages);
                $smarty->assign("messages", $pagelist2[$v[3]]);


            }else{
                $pages = $user->make_page_list(count($pagelist2), 1, "direct_messages", $_SESSION['user']->username);
                $smarty->assign("pages", $pages);
                $smarty->assign("messages", $pagelist2[1]);

            }
        }


        $result = $db->get_recs("messages", "*", "direct<>0 and user='{$_SESSION['user']->username}'", "time desc");    //all direct messages from this user
        $recs = $db->fetch_objects($result);
        if(is_array($recs)) foreach($recs as $rec) $out_messages[] = new message($rec);
        if(count($out_messages)<$page_limit) {
            $smarty->assign("out_messages", $out_messages);
        }else{
            $pagelist = $user->load_pagelist( $out_messages, $page_limit);
            if($v[3]!=='') {
                $pages = $user->make_page_list(count($pagelist), $v[3], "direct_messages",  $_SESSION['user']->username);
                $smarty->assign("pages", $pages);
                $smarty->assign("out_messages", $pagelist[$v[3]]);


            }else{
                $pages = $user->make_page_list(count($pagelist), 1, "direct_messages",  $_SESSION['user']->username);
                $smarty->assign("pages", $pages);
                $smarty->assign("out_messages", $pagelist[1]);

            }
        }

        break;
    case "reply":
        if(!$_SESSION['user']) header("Location: home");
        $rec = $db->get_rec("messages", "*", "id={$_POST['msg_id']}");
        $message = new message($rec);
        $smarty->assign("m", $message);
        if($_POST['add_message'] && $_SESSION['user']){
            if(strlen($_POST['message']) > max_length) $error = err_msg_too_long;
            else $id = $db->db_insert("messages", "user, time, msg, reply", "'{$_POST['user']}', ".time().", '{$_POST['message']}', {$_POST['msg_id']}");
            if($_FILES['add_photo']['tmp_name'] != '' && is_int($id)) {
                $uploaddir = 'post_img/';
                $ext = get_ext_from_mime($_FILES['add_photo']['type']);
                $uploadfile = $uploaddir . $id.".".$ext;
                $uploadfileS = $uploaddir.$id."s.".$ext;
                if(($_FILES['add_photo']['size']<post_img_size)&&(($_FILES['add_photo']['type']=="image/jpeg")||($_FILES['add_photo']['type']=="image/gif")||($_FILES['add_photo']['type']=="image/png"))) {
                    if(move_uploaded_file($_FILES['add_photo']['tmp_name'], $uploadfile)) {
                        copy($uploadfile, $uploadfileS);
                        $size = new_picture_size(post_img_max_width, post_img_max_height, $uploadfile);
                        resize_picture($size[0], $size[1], $uploadfileS, $ext);
                    }
                } else $error = err_file_too_large;
            }
            if($error) $smarty->assign("error", $error);
            else $smarty->assign("ok", ok_reply_sent);
        }
        break;
    case "message":
        $rec = $db->get_rec("messages", "*", "id={$v[2]}");
        $message = new message($rec);
        $smarty->assign("m", $message);
        $rec = $db->get_rec("users", "*", "username='{$message->user}'");
        $u = new user($rec);
        $smarty->assign("user", $u);
        break;
    case "followers":
        $u = $v[2];
        if(!$u) $u = $_SESSION['user']->username;
        $rec = $db->get_rec("users", "*", "username='$u'");
        $user = new user($rec);
        $smarty->assign("user", $user);

        $followers = $user->get_followers();
        if(count($followers)<$page_limit) {
            $smarty->assign("followers", $followers);
        }else{
            $pagelist = $user->load_pagelist($followers, $page_limit);
            if($v[3]!='') {
                $pages = $user->make_page_list(count($pagelist), $v[3], "followers", $v[2]);
                $smarty->assign("pages", $pages);
                $smarty->assign("followers", $pagelist[$v[3]]);


            }else{
                $pages = $user->make_page_list(count($pagelist), 1, "followers", $v[2]);
                $smarty->assign("pages", $pages);
                $smarty->assign("followers", $pagelist[1]);

            }
        }

        $friends = $user->get_friends();
        $smarty->assign("friends", $friends);

        break;
    case "friends":
        $u = $v[2];
        if(!$u) $u = $_SESSION['user']->username;
        $rec = $db->get_rec("users", "*", "username='$u'");
        $user = new user($rec);
        $smarty->assign("user", $user);

        if($_POST['stop_following']) $_SESSION['user']->leave_friend($_POST['friend_id']);
        if($_POST['remove_friend']) $_SESSION['user']->remove_friend($_POST['friend_id']);
        if($_POST['start_following']){
            $_SESSION['user']->remove_friend($_POST['friend_id']);
            $_SESSION['user']->add_friend($_POST['friend_id']);
        }

        $followers = $user->get_followers();
        $smarty->assign("followers", $followers);
        $friends = $user->get_friends();
        if(count($friends)<$page_limit) {
            $smarty->assign("friends", $friends);
        }else{
            $pagelist = $user->load_pagelist($friends, $page_limit);
            if($v[3]!='') {
                $pages = $user->make_page_list(count($pagelist), $v[3], "friends", $v[2]);
                $smarty->assign("pages", $pages);
                $smarty->assign("friends", $pagelist[$v[3]]);


            }else{
                $pages = $user->make_page_list(count($pagelist), 1, "friends", $v[2]);
                $smarty->assign("pages", $pages);
                $smarty->assign("friends", $pagelist[1]);

            }
        }

        break;
    case "tag":
        $tag = urldecode($v[2]);
        $smarty->assign("tag", $tag);

        $u = $db->get_rec("users", "*", "username='{$v[3]}'");
        $user = new user($u);
        $friends = $user->get_friends();
        $smarty->assign("user", $user);

        $result = $db->get_recs("users", "*", "interests like '%$tag%'");
        $recs = $db->fetch_objects($result);
        if(is_array($recs)) foreach($recs as $rec) $people[] = new user($rec);
        $smarty->assign("people", $people);

        if($_POST['follow']) $_SESSION['user']->add_friend($_POST['friend_id']);

        break;
    case "settings":
        if(!$_SESSION['user']) header("Location: home");
        if(!$v[2]) $v[2] = "my_profile";
        switch($v[2]){
            case "my_profile":
                if($_POST['save_profile']){
                    if($_POST['pass1'] != $_POST['pass2']) $error = err_password_mismatch;
                    else if(!email_ok($_POST['email'])) $error = err_email_incorrect;
                    if(!$error){
                        if($_POST['visible']) $visible = 1; else $visible = 0;
                        if(!$_POST['pass1']) $result = $db->db_update("users", "name='{$_POST['name']}', email='{$_POST['email']}', visible=$visible ", "id={$_SESSION['user']->id}");
                        else $result = $db->db_update("users", "name='{$_POST['name']}', pass='{$_POST['pass1']}', email='{$_POST['email']}', visible=$visible ", "id={$_SESSION['user']->id}");

                        if($result) $smarty->assign("ok", ok_profile_saved);
                        else $error = "Could not modify user data";
                    }
                    if($error) $smarty->assign("error", $error);
                }
            
                break;
            case "my_life":
                if($_POST['save_life']){
                    $age = mktime(0, 0, 0, $_POST['month'], $_POST['day'], $_POST['year']);
                    if(strlen($_POST['bio']) > 200) $error = err_about_me_too_long;
                    if(strlen($_POST['interests']) > 200) $error = err_interests_too_long;
                    if($error)  $smarty->assign("error", $error);
                    else {
                        if(!eregi("http://", $_POST['www']) && $_POST['www']) $_POST['www'] = "http://".$_POST['www'];
                        if($_POST['location'] !='') {
                            // geolocalization by googlemaps
                            $fd = fopen("http://maps.google.com/maps/geo?q=".urlencode($_POST['location'])."&output=csv&key=ABQIAAAALzxZxZULX9-oXnRMvB1RvxS-ppMTo74UK5LP65eOUWuzYEClfBQGLwC_uVDcU5xIveNkvCVKbhGwCA", "r");
                            $data = fread($fd, 5000);
                            $data = explode(",", $data);
                            //print_r($data);
                            if($data[0] == 200) {
                                $y = $data[2];
                                $x = $data[3];
                           }
                           fclose($fd);
                        }
                        $result = $db->db_update("users", "age=$age, bio='".urlencode(htmlspecialchars($_POST['bio']))."', location='".urlencode($_POST['location'])."', www='{$_POST['www']}', interests='".urlencode(htmlspecialchars($_POST['interests']))."', x=$x, y=$y", "id={$_SESSION['user']->id}");
                        if($result) $smarty->assign("ok", ok_life_changed);
                        else $smarty->assign("error", err_life_change);
                        $_SESSION['user']->refresh();
                    }
                }
//'"
                for($i = 1920; $i < (date("Y") - 14); $i++)  $years[] = $i;
                $smarty->assign("years", $years);
                for($i = 1; $i <= 12; $i++) $months[] = $i;
                $smarty->assign("months", $months);
                for($i = 1; $i <= 31; $i++) $days[] = $i;
                $smarty->assign("days", $days);

                $parts = explode(".", date("Y.m.d", $_SESSION['user']->dob));
                $smarty->assign("year", $parts[0]);
                $smarty->assign("month", $parts[1]);
                $smarty->assign("day", $parts[2]);
                break;
            case "my_im":
                if($_POST['deactivate_im']){
                    $result = $db->db_update("users", "im_id='', im_type=''", "id={$_POST['uid']}");
                    if($result) $smarty->assign("deactivated", ok_im_deactivated);
                    $db->db_update("users", "notify_way='email'", "id={$_POST['uid']}");
                    $deactivated = true;
                }
                if($_POST['save_im']){
                    if(!$error){
                        $result = $db->db_update("users", "im_type='{$_POST['im_type']}', im_id='{$_POST['im_id']}', notify_way='im'", "id={$_SESSION['user']->id}");
                        if($result) {
                            switch($_POST['im_type']){
                                case "MSN": $contact = im_account_msn; break;
                                case "ICQ": $contact = im_account_icq; break;
                                case "GTalk/Jabber": $contact = im_account_jabber; break;
                                case "AIM": $contact = im_account_aim; break;
                                case "Yahoo Messenger": $contact = im_account_yahoo; break;
                            }
                            $smarty->assign("ok", str_replace("#contact", $contact, ok_im_set));
                        }
                        else $error = err_set_im;
                    }
                    if($error) $smart->assign("error", $error);
                }
                switch($_SESSION['user']->im_type){
                    case "MSN": $contact = im_account_msn; break;
                    case "ICQ": $contact = im_account_icq; break;
                    case "GTalk/Jabber": $contact = im_account_jabber; break;
                    case "AIM": $contact = im_account_aim; break;
                    case "Yahoo Messenger": $contact = im_account_yahoo; break;
                }
                if(!$deactivated){
                    $contact = str_replace("#contact", $contact, ok_im_set);
                    if($contact) $smarty->assign("contact", $contact);
                }

                $im_types = explode(",", im_list);
                $smarty->assign("im_types", $im_types);
                break;
            case "my_mobile":
                if($_POST['save_mobile']) {
                    $db->db_update("users", "phone='{$_POST['mobile_num']}'", "id={$_SESSION['user']->id}");
                    $_SESSION['user']->refresh();
                }
                $smarty->assign("phone", $_SESSION['user']->phone);
                break;
            case "my_photo":
                if($_POST['upload_photo']){
                    $filename = upload_avatar('picture', $_SESSION['user']->id);
                    if($filename) $smarty->assign("ok", ok_photo_uploaded);
                    else $smarty->assign("error", err_photo_upload);
                }
                if($_POST['choose']){
                    $file = $_POST['chosen_photo'];
                    $old = "sample_photos/$file";

                    $parts = explode(".", $file);
                    $last = count($parts) - 1;
                    $ext = $parts[$last];

                    $filename = $_SESSION['user']->id.".".$ext;
                    $new = "avatars_mini/$filename";
                    if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png'){
                        if(copy($old, $new)) {
                            resize_picture(60, 60, $new, $ext);
                            $db->db_update("users", "avatar='$filename'", "id={$_SESSION['user']->id}");
                            If(copy($old, "avatars25/$filename")){
                                resize_picture(25, 25, "avatars25/$filename", $ext);
                            }
                            $smarty->assign("ok", ok_photo_changed);
                        }
                    }
                }
                if(is_file("avatars_mini/{$_SESSION['user']->id}.jpg")) $smarty->assign("photo_exists", 1);

                $files = get_files('sample_photos');
                $smarty->assign("files", $files);
                break;
            case "notification":
                if($_POST['save_notification']){
                    if($_POST['notify_friend']) $nf = 1; else $nf = 0;
                    $result = $db->db_update("users", "notify_friend=$nf", "id={$_SESSION['user']->id}");
                    if($result) $smarty->assign("ok", ok_settings_saved);
                }
                break;
            case "my_delete":
                break;
            case "my_sticker":
                if($_POST['save_sticker']) $db->db_update("layouts", "sticker_color='{$_POST['sticker_color']}'", "user={$_SESSION['user']->id}");
                $rec = $db->get_rec("layouts", "*", "user={$_SESSION['user']->id}");
                if(!$rec) {
                    $db->db_insert("layouts", "user", "{$_SESSION['user']->id}");
                    $rec = $db->get_rec("layouts", "*", "user={$_SESSION['user']->id}");
                }
                $smarty->assign("sticker_color", $rec->sticker_color);
                break;
            case "my_layout":
                if($_POST['reset_layout']){
                    if(is_file("backgrounds/{$_SESSION['user']->id}.jpg")) unlink("backgrounds/{$_SESSION['user']->id}.jpg");
                    $db->db_delete("layouts", "user={$_SESSION['user']->id}");
                    $smarty->assign("ok", ok_back_to_defaults);
                }
                if($_POST['save_layout']) {
                    if($_POST['use_image']) $use_image = 1; else $use_image = 0;
                    if($_POST['back_fixed']) $back_fixed = 1; else $back_fixed = 0;
                    $result = $db->db_update("layouts", "back_fixed=$back_fixed, use_image=$use_image, side_border_color='{$_POST['side_border_color']}', side_fill_color='{$_POST['side_fill_color']}', top_area_color='{$_POST['top_area_color']}', text_color='{$_POST['text_color']}', link_color='{$_POST['link_color']}', back_color='{$_POST['back_color']}', back_tile={$_POST['back_tile']}, bubble_text_color='{$_POST['bubble_text_color']}', bubble_fill_color='{$_POST['bubble_fill_color']}'", "user={$_SESSION['user']->id}");
                    if($result) {
                        $smarty->assign("ok", ok_layout_changed);
                        if(is_uploaded_file($_FILES['back_image']['tmp_name'])) {
                            if($_FILES['back_image']['size'] < 512000) move_uploaded_file($_FILES['back_image']['tmp_name'], "backgrounds/{$_SESSION['user']->id}.jpg");
                            else $smarty->assign("error", err_file_too_large);
                        } else if($_POST['background_name']) copy("bglib/{$_POST['background_name']}", "backgrounds/{$_SESSION['user']->id}.jpg");
                    } else $smarty->assign("error", err_layout_change);
                }
                $rec = $db->get_rec("layouts", "*", "user={$_SESSION['user']->id}");
                if(!$rec) {
                    $db->db_insert("layouts", "user", "{$_SESSION['user']->id}");
                    $rec = $db->get_rec("layouts", "*", "user={$_SESSION['user']->id}");
                }
                $smarty->assign("layout", $rec);
                $files = get_files('bglib');
                $smarty->assign("files", $files);
                break;
            case "my_api_key":
                break;
            case "sms_credits":
                $result = $db->get_recs("sms_plans", "*", "", "credits asc");
                $recs = $db->fetch_objects($result);
                foreach($recs as $rec){
                    $qty[] = $rec->credits;
                    $prices[] = $rec->price;
                }
                $smarty->assign("credit_qty", $qty);
                $smarty->assign("plan_price", $prices);

                if($_POST['reset_limit']) $_SESSION['user']->reset_sms_limit();

                if($_POST['set_limit']){
                    if(!preg_match('/^[0-9]+$/', $_POST['limit'])) $smarty->assign("sms_limit_error", err_sms_limit_nan);
                    else {
                        $result = $db->db_update("users", "sms_limit={$_POST['limit']}", "id={$_SESSION['user']->id}");
                        if(!$result) $smarty->assign("sms_limit_error", err_sms_limit);
                    }
                }

                //get previous transactions
                $result = $db->get_recs("transactions", "*", "user_id={$_SESSION['user']->id} and status=1", "time desc");
                $recs = $db->fetch_objects($result);
                $smarty->assign("transactions", $recs);
                break;
           case "twitter_com_account":
                
                if($_POST['save_twitter_com_account']) 
                {                    
                    $flag_send_message = (isset($_POST['flag_send_message'])) ? 1 : 0;
                    $db->db_update("users", "twitter_com_username='{$_POST['username']}', twitter_com_pass='{$_POST['pass']}', twitter_com_send_message=$flag_send_message ", "id={$_SESSION['user']->id}");
                    $_SESSION['user']->refresh();
                }
                $smarty->assign("username", $_SESSION['user']->twitter_com_username);
                $smarty->assign("pass", $_SESSION['user']->twitter_com_pass);
                $smarty->assign("flag_send_message", $_SESSION['user']->twitter_com_send_message);
                
                break;

		case 'my_groups':

			if ($_POST['save_groups']){
				// delete old
				$result = $db->db_delete("groups_links", "user_id='".$_SESSION['user']->id."' " );
				// insert NEW
				if (isset($_POST['groups']) && sizeof($_POST['groups'])){
					foreach($_POST['groups'] as $k1=>$v1){
						if (!is_numeric($k1)) continue;
						$result = $db->db_insert("groups_links", "user_id, group_id", " '{$_SESSION['user']->id}','{$k1}' " );
					}
				}
			}

			// Get User Groups
			$groups_user = $db->fselect('SELECT gm.* FROM groups_links gl 
			LEFT JOIN groups_mes gm ON gm.group_id=gl.group_id 
			WHERE gl.user_id=\''.$_SESSION['user']->id.'\' ', 1);
			$smarty->assign("user_groups", $groups_user );

			// Get All groups
//			$list_groups = group::get_all_group_tree();
//			$smarty->assign('list_groups', $list_groups );

		break;

            default: $v[2] = "my_profile"; break;
        }

        if($_SESSION['user']) $_SESSION['user']->refresh();
        $smarty->assign("settings_page", $v[2].".tpl");
        break;
    case "search":
        if(isset($_POST['search_words'])) {
            $search_string = urlencode($_POST['search_words']);
            $search_word = $_POST['search_words'];
        }else{
            $search_word = urldecode($v[2]);
            $search_string = $v[2];
        }
//	else{
		$result = $db->get_recs("users", "*", "username like '%{$search_word}%' OR name like '%{$search_word}%'", "name");
	        $recs = $db->fetch_objects($result);
	        if(is_array($recs)) foreach($recs as $rec) $found_users[] = new user($rec);
	        $user = new user($rec);
	        if(count($found_users)<$page_limit) {
	            $smarty->assign("found_users", $found_users);
	        }else{
	            $pagelist = $user->load_pagelist($found_users, $page_limit);
	            if($v[2]&&$v[3]!=='') {
	                $pages = $user->make_page_list(count($pagelist), $v[3], "search/".$search_string, '');
	                $smarty->assign("pages", $pages);
	                $smarty->assign("found_users", $pagelist[$v[3]]);
	            }else{
	                $pages = $user->make_page_list(count($pagelist), 1, "search/".$search_string, '');
	                $smarty->assign("pages", $pages);
	                $smarty->assign("found_users", $pagelist[1]);
	
	            }
	        }
	        $smarty->assign("search_word",$search_word);
	        $smarty->assign("num_of_results","( ".count($found_users)." )");
//	}

        break;
    case "welcome":
        $stuff = explode("###", decrypt($v[2]));
        $username = $stuff[0];
        $pass = $stuff[1];
        $rec = $db->get_rec("users", "*", "username='$username' and pass='$pass'");
        if($rec) {
            $db->db_update("users", "new=0", "id={$rec->id}");
            $_SESSION['user'] = new user($rec);

            //direct message from admin
            $db->db_insert("messages", "msg, user, time, direct", "'".default_message_from_admin."', 'Gozub', ".time().", {$_SESSION['user']->id}");

            //admin as a default friend
            $rec = $db->get_rec("users", "id", "username='Gozub'");
            $_SESSION['user']->add_friend($rec->id);
            //first message by the user
            $db->db_insert("messages", "msg, user, time", "'".user_first_message."', '{$_SESSION['user']->username}', ".time());
            $db->db_insert("layouts", "user", "{$_SESSION['user']->id}");
        } else $page="home";
        break;
    case "welcome_info":

        break;
    case "start_add_photo":
        if(!$_SESSION['user']) header("Location: home");
        if($_POST['upload_photo']){
            $filename = upload_avatar('picture', $_SESSION['user']->id);
            if($filename) $smarty->assign("ok", ok_photo_uploaded);
            else $smarty->assign("error", err_photo_upload);
        }
        if($_POST['choose']){
            $file = $_POST['chosen_photo'];
            $old = "sample_photos/$file";
            $parts = explode(".", $file);
            $last = count($parts) - 1;
            $ext = $parts[$last];

            $filename = $_SESSION['user']->id.".".$ext;
            $new = "avatars_mini/$filename";
            if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png'){
                if(copy($old, $new)) {
                    resize_picture(60, 60, $new, $ext);
                    $db->db_update("users", "avatar='$filename'", "id={$_SESSION['user']->id}");
                    copy($new, "avatars25/$filename");
                    resize_picture(25, 25, "avatars25/$filename", $ext);
                }
                $smarty->assign("ok", ok_photo_changed);
            }
        }
        $files = get_files('sample_photos');
        $smarty->assign("files", $files);
        break;
    case "start_activate_im":
        if(!$_SESSION['user']) header("Location: home");
        if($_POST['save_im']){
            if(!$error){
                $result = $db->db_update("users", "im_type='{$_POST['im_type']}', im_id='{$_POST['im_id']}', notify_way='im'", "id={$_SESSION['user']->id}");
                if($result) {
                    switch($_POST['im_type']){
                        case "MSN": $contact = im_account_msn; break;
                        case "ICQ": $contact = im_account_icq; break;
                        case "GTalk/Jabber": $contact = im_account_jabber; break;
                        case "AIM": $contact = im_account_aim; break;
                        case "Yahoo Messenger": $contact = im_account_yahoo; break;
                    }
                    $contact = str_replace("#contact", $contact, ok_im_set);
                    $smarty->assign("ok", $contact);
                } else $error = err_set_im;
            }
            if($error) $smart->assign("error", $error);
        }
        $_SESSION['user']->refresh();
        $im_types = explode(",", im_list);
        $smarty->assign("im_types", $im_types);
        break;
    case "start_my_life":
        if(!$_SESSION['user']) header("Location: home");
        if($_POST['save_life']){
            $age = mktime(0, 0, 0, $_POST['month'], $_POST['day'], $_POST['year']);
            if(!eregi("http://", $_POST['www']) && $_POST['www']) $_POST['www'] = "http://".$_POST['www'];
            if($_POST['location'] !='') {
                // geolocalization by googlemaps
                $fd = fopen("http://maps.google.com/maps/geo?q=".urlencode($_POST['location'])."&output=csv&key=ABQIAAAALzxZxZULX9-oXnRMvB1RvxS-ppMTo74UK5LP65eOUWuzYEClfBQGLwC_uVDcU5xIveNkvCVKbhGwCA", "r");
                $data = fread($fd, 5000);
                $data = explode(",", $data);
                //print_r($data);
                if($data[0] == 200) {
                    $y = $data[2];
                    $x = $data[3];
                }
                fclose($fd);
            }
            $result = $db->db_update("users", "age=$age, bio='".urlencode(htmlspecialchars($_POST['bio']))."', location='".urlencode($_POST['location'])."', www='{$_POST['www']}', interests='".urlencode($_POST['interests'])."', x=$x, y=$y", "id={$_SESSION['user']->id}");
            if($result) $smarty->assign("ok", ok_life_saved);
            else $smarty->assign("error", err_life_change);
            $_SESSION['user']->refresh();
        }
        for($i = 1920; $i < (date("Y") - 14); $i++)  $years[] = $i;
        $smarty->assign("years", $years);
        for($i = 1; $i <= 12; $i++) $months[] = $i;
        $smarty->assign("months", $months);
        for($i = 1; $i <= 31; $i++) $days[] = $i;
        $smarty->assign("days", $days);

        if($_SESSION['user']->dob){
            $parts = explode(".", date("Y.m.d", $_SESSION['user']->dob));
            $smarty->assign("year", $parts[0]);
            $smarty->assign("month", $parts[1]);
            $smarty->assign("day", $parts[2]);
        }
        break;
    case "start_find_friends":
        if(!$_SESSION['user']) header("Location: home");
        $last_update = $_SESSION['user']->last_update();
        $smarty->assign("message", $last_update);

        if($_POST['check_friends']){
            include_once("lib/emails/{$_POST['check_type']}.php");
            $result = get_contacts($_POST['check_user'], $_POST['check_pass']);
            if(!is_array($result)) $smarty->assign("error1", err_login_incorrect);
            else {
                $user_count = 0;

                foreach($result[0] as $k=>$v) $results[$v] = $result[1][$k];
                ksort($results);
                $names = array_keys($results);
                foreach($results as $r) $emails[] = $r;

                $smarty->assign("names", $names);
                $smarty->assign("emails", $emails);
                foreach($emails as $e){
                    $rec = $db->get_rec("users", "username, id", "email='{$e}'");
                    if($rec->username) {
                        $usernames[] = $rec->username;
                        $uids[] = $rec->id;
                        $user_count++ ;
                    } else {
                        $usernames[] = '';
                        $uids[] = 0;
                    }
                }
                $smarty->assign("usernames", $usernames);
                $smarty->assign("uids", $uids);
                $smarty->assign("user_count", $user_count);
            }
        }
        if($_POST['send_invitations']){
            if(is_array($_POST['user'])) foreach($_POST['user'] as $u) {
                $data = explode("___", $u);
                if($data[2]!=0) {
                    $_SESSION['user']->add_friend($data[2]);
                    $u = $db->get_rec("users", "*", "id={$data[2]}");
                    $usr = new user($u);
                    $usr->add_friend($_SESSION['user']->id);
                    $ok = true;
                } else {

                    $msg = str_replace("#message_link", $base_href."message/".$last_update->id, invitation_mail);
                    //$msg = str_replace("#last_update_id", $last_update->id, invitation_mail);
                    $msg = str_replace("#last_update", $last_update->msg, $msg);
                    if($_SESSION['user']->name) $msg .= $_SESSION['user']->name;
                    else $msg .= $_SESSION['user']->username;
                    $ok = mail($addr, invitation_subject, $msg, "Content-Type: text/plain; charset=iso8859-1\nFrom: Admin<".CONTACT_MAIL.">");
                }
            }
            if($ok) $smarty->assign("ok", ok_invitations_sent);
            else $smarty->assign("error2", err_invitations);
        }
        if($_POST['invite']){
            if(!$_POST['addresses']) $error = err_email_needed;
            else {
                $a = explode(",", $_POST['addresses']);
                if(is_array($a)) foreach($a as $adr) $addresses[] = trim($adr);
                if(is_array($addresses)) foreach($addresses as $addr){
                    $u = $db->get_rec("users", "*", "email='$addr'");
                    if($u) {
                        $_SESSION['user']->add_friend($u->id);
                        $usr = new user($u);
                        $usr->add_friend($_SESSION['user']->id);
                        $ok = true;
                    } else {
                        $msg = str_replace("#message_link", $base_href."message/".$last_update->id, invitation_mail);
                        //$msg = str_replace("#last_update_id", $last_update->id, invitation_mail);
                        $msg = str_replace("#last_update", $last_update->msg, $msg);
                        if($_SESSION['user']->name) $msg .= $_SESSION['user']->name;
                        else $msg .= $_SESSION['user']->username;
                        $ok = mail($addr, invitation_subject, $msg, "Content-Type: text/plain; charset=iso8859-1\nFrom: Admin<".CONTACT_MAIL.">");
                    }
                }
                if($ok) $smarty->assign("ok", ok_invitations_sent_nolist);
                else $smarty->assign("error3", err_invitations);
            }
            if($error) $smarty->assign("error3", $error);
        }

        break;
    case "invite":
        if(!$_SESSION['user']) header("Location: home");
        $last_update = $_SESSION['user']->last_update();
        $smarty->assign("message", $last_update);

        if($_POST['check_friends']){
            include_once("lib/emails/{$_POST['check_type']}.php");
            $result = get_contacts($_POST['check_user'], $_POST['check_pass']);
            if(!is_array($result)) $smarty->assign("error1", err_login_incorrect);
            else {
                $user_count = 0;
                foreach($result[0] as $k=>$v) $results[$v] = $result[1][$k];
                ksort($results);
                $names = array_keys($results);
                foreach($results as $r) $emails[] = $r;

                $smarty->assign("names", $names);
                $smarty->assign("emails", $emails);
                foreach($emails as $e){
                    $rec = $db->get_rec("users", "username, id", "email='{$e}'");
                    if($rec->username) {
                        $usernames[] = $rec->username;
                        $uids[] = $rec->id;
                        $user_count++;
                    } else {
                        $usernames[] = '';
                        $uids[] = 0;
                    }
                }
                $smarty->assign("usernames", $usernames);
                $smarty->assign("uids", $uids);
                $smarty->assign("user_count", $user_count);
            }
        }
        if($_POST['send_invitations']){
            if(is_array($_POST['user'])) foreach($_POST['user'] as $u) {
                $data = explode("___", $u);
                if($data[2]) $_SESSION['user']->add_friend($data[2]);
                else {
                    $msg = str_replace("#message_link", $base_href."message/".$last_update->id, invitation_mail);
                    //$msg = str_replace("#last_update_id", $last_update->id, invitation_mail);
                    $msg = str_replace("#last_update", $last_update->msg, $msg);
                    if($_SESSION['user']->name) $msg .= $_SESSION['user']->name;
                    else $msg .= $_SESSION['user']->username;
                    $ok = mail($addr, invitation_subject, $msg, "Content-Type: text/plain; charset=iso8859-1\nFrom: Admin<".CONTACT_MAIL.">");
                }
            }
            if($ok) $smarty->assign("ok", ok_invitations_sent);
            else $smarty->assign("error2", err_invitations);
        }

        if($_POST['invite']){
            if(!$_POST['addresses']) $error = err_email_needed;
            else {
                $a = explode(",", $_POST['addresses']);
                if(is_array($a)) foreach($a as $adr) if(email_ok($adr)) $addresses[] = trim($adr);
                if(is_array($addresses)) foreach($addresses as $addr){
                    $u = $db->get_rec("users", "*", "email='$addr'");
                    if($u) {
                        $_SESSION['user']->add_friend($u->id);
                        $ok = true;
                    } else {
                        $msg = str_replace("#message_link", $base_href."message/".$last_update->id, invitation_mail);
                        //$msg = str_replace("#last_update_id", $last_update->id, invitation_mail);
                        $msg = str_replace("#last_update", $last_update->msg, $msg);
                        if($_SESSION['user']->name) $msg .= $_SESSION['user']->name;
                        else $msg .= $_SESSION['user']->username;
                        $ok = mail($addr, invitation_subject, $msg, "Content-Type: text/plain; charset=iso8859-1\nFrom: Admin<".CONTACT_MAIL.">");
                    }
                }
                if($ok) $smarty->assign("ok", ok_invitations_sent_nolist);
                else $smarty->assign("error3", err_invitations);
            }
            if($error) $smarty->assign("error3", $error);
        }
        break;
    case "api_docs": $smarty->assign("mpp", mpp); break;
    case "terms_and_conditions": break;
    case "contact":
        if($_POST['send']){
            $result = sendmail(CONTACT_MAIL, $_POST['subject'], $_POST['message_content'], $_POST['your_name'], $_POST['email']);
            if($result === true) $smarty->assign("ok", ok_email_sent);
            else $smarty->assign("error", $result);
        }
        break;
    case "privacy_policy":      break;
    case "about":       break;
    case "help":        break;
    case "forgot_password":
        if($_POST['remind']){
            $rec = $db->get_rec("users", "pass", "email='{$_POST['forgot_email']}'");
            if($rec->pass){
                $to = $rec->username."<".$_POST['forgot_email'].">";
                $msg = str_replace("#user", $_POST['forgot_user'], reminder_mail);
                $msg = str_replace("#pass", $rec->pass, $msg);
                if(mail($to, reminder_subject, $msg, "Content-Type: text/plain; charset=iso8859-1\nFrom: Admin<".CONTACT_MAIL.">")) $smarty->assign("ok", ok_reminder_sent);
                else $smarty->assign("error", err_reminder_error);
            } else $smarty->assign("error", err_email_not_found);
        }
        break;
    case "vision":
        break;
    case "page":
        $page_id = substr($v[2], strrpos($v[2], "-") + 1);
        $rec = $db->get_rec("static_pages", "title, content", "id={$page_id}");
        $smarty->assign("page_title", $rec->title);
        $smarty->assign("page_content", nl2br(stripslashes($rec->content)));
        break;
    case "sms_credits":
        $result = $db->get_recs("sms_plans", "*", "", "credits asc");
        $recs = $db->fetch_objects($result);
        foreach($recs as $rec){
            $qty[] = $rec->credits;
            $prices[] = $rec->price;
        }
        $smarty->assign("credit_qty", $qty);
        $smarty->assign("plan_price", $prices);

        if($_POST['reset_limit']) $_SESSION['user']->reset_sms_limit();

        if($_POST['set_limit']){
            if(!preg_match('/^[0-9]+$/', $_POST['limit'])) $smarty->assign("sms_limit_error", err_sms_limit_nan);
            else {
                $result = $db->db_update("users", "sms_limit={$_POST['limit']}", "id={$_SESSION['user']->id}");
                if(!$result) $smarty->assign("sms_limit_error", err_sms_limit);
            }
        }

        //get previous transactions
        $result = $db->get_recs("transactions", "*", "user_id={$_SESSION['user']->id} and status=1", "time desc");
        $recs = $db->fetch_objects($result);
        $smarty->assign("transactions", $recs);
        break;
    case "buy_credits":
        if($_POST['buy']){
            $stuff = explode("-", $_POST['qty']);
            $qty = $stuff[0];
            $price = $stuff[1];
            $tid = $db->db_insert("transactions", "time, user_id, credits, value", time().", {$_SESSION['user']->id}, $qty, $price");
            if($tid){
                $trans_data = encrypt("{$_SESSION['user']->id}###$tid");
                $smarty->assign("paypal_addr", paypal_addr);
                $smarty->assign("paypal_business", paypal_business);
                $smarty->assign("paypal_success", paypal_success."/$trans_data");
                $smarty->assign("paypal_failure", paypal_failure."/$trans_data");
                $smarty->assign("paypal_amount", $price);
                $smarty->assign("paypal_item", "Transaction no. $tid");
            }
        }
        break;
    case "buy_ok":
        if(eregi("\?", $v[2])) $v[2] = substr($v[2], 0, strpos($v[2], "?"));
        $stuff = explode("###", decrypt($v[2]));
        $rec = $db->get_rec("transactions", "*", "id={$stuff[1]} and status=0");
        if($rec){
            $db->db_update("transactions", "status=1", "id={$stuff[1]}");
            $smarty->assign("credits_qty", $rec->credits);
            $_SESSION['user']->sms_credits = $_SESSION['user']->sms_credits + $rec->credits;
            $db->db_update("users", "sms_credits={$_SESSION['user']->sms_credits}", "id={$stuff[0]}");
        } else {
            $smarty->assign("paypal_error", 1);
        }
        break;
    case "buy_fail":
        if(eregi("\?", $v[2])) $v[2] = substr($v[2], 0, strpos($v[2], "?"));
        $stuff = explode("###", decrypt($v[2]));
        $db->db_update("transactions", "status=2", "id={$stuff[1]}");
        break;
    default: header("Location: profile/".$v[1]); break;
}

//preparing the custom css

if(($page == 'profile' || $page == 'friends' || $page == 'followers' || $page == 'favorites') && $v[2] != $_SESSION['user']->username){	
    $u = $db->get_rec("users", "*", "username='{$v[2]}'");
    if (!$u) {
    	header("Location: ".root_domain);
    }
    //print_r('<pre>');    
    //print_r($u);
    //print_r('</pre>');
    $current_user = new user($u);
    $side_css = $current_user->side_css();
    $main_css = $current_user->main_css();
    //layout
    $lay = $db->get_rec("layouts", "*", "user={$current_user->id}");
} else if($page=='tag'){
    $u = $db->get_rec("users", "*", "username='{$v[3]}'");
    $current_user = new user($u);
    $side_css = $current_user->side_css();
    $main_css = $current_user->main_css();
    //layout
    $lay = $db->get_rec("layouts", "*", "user={$current_user->id}");
} else {
    if($_SESSION['user']){
        $side_css = $_SESSION['user']->side_css();
        $main_css = $_SESSION['user']->main_css();
        $lay = $db->get_rec("layouts", "*", "user={$_SESSION['user']->id}");
    }
}
$smarty->assign("side_fill", $lay->side_fill_color);

//assigning values to smarty
$smarty->assign("side_css", $side_css);
$smarty->assign("main_css", $main_css);
if($_SESSION['user']) $_SESSION['user']->refresh();
$smarty->assign("logged_user", $_SESSION['user']);
$page = $page.".tpl";
$smarty->assign('page', $page);
$smarty->display('index.tpl');

?>