<h1>Groups</h1>

<?php

$self = '?id='.$_GET['id'];
//#if ($_GET['act']=='add')
//#	$self .= '&act='.$_GET['act'];

define('DIR_AVATAR_GROUP', str_replace('adminlogin','',dirname(__FILE__)).'avatars_group'.DIRECTORY_SEPARATOR) ;

if ($_POST['btn_submit']){
	$d=array();
	$d['parent_id']	= intval($_POST['parent_id']);
	$d['group_title']= mysql_real_escape_string($_POST['group_title']);

	$d['group_furl'] = $_POST['group_furl']?$_POST['group_furl']:($_POST['group_title']?strtolower($_POST['group_title']):'');
	$d['group_furl'] = str_replace(' ', '_',$d['group_furl']);
	$d['group_furl'] = mysql_real_escape_string($d['group_furl']);
	$d['group_furl_hash'] = md5($d['group_furl']);

	$d['group_descr']= mysql_real_escape_string($_POST['group_descr']);
	$d['group_tags'] = mysql_real_escape_string($_POST['group_tags']);
	$d['order_id']	 = intval($_POST['order_id']);
	// Set LEVEL_ID
	$d['level_id']	= 1;
	if ($d['parent_id']){
		$parent_data = $db->fetch_objects( $db->get_recs("groups_mes", "*", "group_id=".$d['parent_id'], "order_id ASC" ) );
		$parent_data=$parent_data[0];
		$d['level_id']   = $parent_data->level_id+1;
	}
	$data_valid = $d['group_title']?1:0;

	if ($data_valid) {
		if ($_POST['group_id']){
			$result = $db->db_update("groups_mes", "parent_id='{$d[parent_id]}',level_id='{$d[level_id]}',order_id='{$d[order_id]}',group_title='{$d[group_title]}',group_furl='{$d[group_furl]}',group_furl_hash='{$d[group_furl_hash]}' ,group_descr='{$d[group_descr]}' ,group_tags='{$d[group_tags]}' ", "group_id={$_POST['group_id']}");
			if (!$result) err("An error occured while trying to update Group");
			else ok("Group Update successfully");
		}
		else{
			// get MAX order
			$max_order = $db->fetch_objects( $db->get_recs("groups_mes", "max(order_id) as c_c", "parent_id=".$d['parent_id'], "" ) );
			$d['order_id'] = $max_order[0]->c_c+1;
			$d['created'] = time();
			$lastInsertId = $db->db_insert("groups_mes", "parent_id,level_id,order_id,group_title,group_furl,group_furl_hash,group_descr,group_tags,created", " '{$d['parent_id']}','{$d['level_id']}','{$d['order_id']}','{$d['group_title']}','{$d['group_furl']}','{$d['group_furl_hash']}' ,'{$d['group_descr']}' ,'{$d['group_tags']}','{$d['created']}' " );
			if (!$lastInsertId) err("An error occured while trying to Insert Group");
			else ok("Group Insert successfully");
			$_POST['group_id'] = $lastInsertId;
		}

		// IMAGE
		if ($_POST['group_id'] && is_uploaded_file($_FILES['group_image']['tmp_name'])){
			preg_match("/\.([a-z]+)$/i", $_FILES['group_image']['name'], $ext);
			$ext = strtolower($ext[0]);
			$fn_dest   = DIR_AVATAR_GROUP.$_POST['group_id'].'_'.$ext;
			$fn_dest_25= DIR_AVATAR_GROUP.$_POST['group_id'].'_25'.$ext;
			$fn_dest_60= DIR_AVATAR_GROUP.$_POST['group_id'].'_60'.$ext;
			// delete old
			$oldimages = glob(DIR_AVATAR_GROUP.$_POST['group_id'].'_*');
			foreach($oldimages as $fn_old) if (file_exists($fn_old)) unlink($fn_old);				
			// Copy
			copy($_FILES['group_image']['tmp_name'], $fn_dest);
			copy($_FILES['group_image']['tmp_name'], $fn_dest_25);
			move_uploaded_file($_FILES['group_image']['tmp_name'], $fn_dest_60);
			resize_picture(25, 25, $fn_dest_25, $ext);
			resize_picture(60, 60, $fn_dest_60, $ext);
			// Save in DB
			if (is_file($fn_dest)){
				$d=array();
				$d['group_image'] = $ext;
				$result = $db->db_update("groups_mes", "group_image='{$d[group_image]}' ", "group_id={$_POST['group_id']}");
			}
		}


	}
	else{
		err("Title is empty");
	}
}

if (is_numeric($_GET['delete_group_id']) && $_GET['delete_group_id']>0){
	$result  = $db->db_delete("groups_mes", "group_id=".$_GET['delete_group_id'] );
//	$result1 = $db->db_delete("groups_mes", "parent_id=".$_GET['delete_group_id'] );
	if($result) ok("Group delete successfully");
	else err("An error occured while trying to delete Group");
}

?>

<a href="<?php echo $self; ?>&act=add">+ Add new group</a><br />

<?php

if ( in_array($_GET['act'], array('add','edit')) ){
	if ($_GET['group_id']){
		$group_data = $db->fetch_objects( $db->get_recs("groups_mes", "*", "group_id=".intval($_GET['group_id']), "" ) );
		$group_data=$group_data[0];
	}
	if ($_GET['parent_id']){
		$group_data->parent_id=$_GET['parent_id'];
	}

	$groups_l1 = $db->fetch_objects( $db->get_recs("groups_mes", "*", "level_id=1", "order_id ASC" ) );
	$parent_ids=array(0=>'ROOT of groups');
	if (sizeof($groups_l1)) foreach($groups_l1 as $k=>$v) $parent_ids[$v->group_id] = '- '.$v->group_title;

	form_begin($self, 1);
	form_hidden('group_id', $group_data->group_id );
	form_select("parent_id", "Parent:", array_values($parent_ids), array_keys($parent_ids), $_GET['parent_id']?$_GET['parent_id']:$group_data->parent_id);
	form_text('group_title', 'Title:', htmlspecialchars($group_data->group_title) );
	form_text('group_furl', 'http://root_domain/groups/profile/', htmlspecialchars($group_data->group_furl), '' );
	form_textarea('group_descr', 'Description:', $group_data->group_descr);
	form_text('group_tags', 'Tags:', $group_data->group_tags);
	form_file('group_image', 'Image:' );
	form_text('order_id', 'Order:', $group_data->order_id);
	form_submit('btn_submit', $_REQUEST['group_id']?'Change':'Add');
	form_end();
}

include( 'groups_list.php' );

?>