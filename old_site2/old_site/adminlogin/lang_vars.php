<?php

function sync_var_and_trans(){
/*
	global $db;

	// Get count of VARs
	$count_vars = $db->fetch_objects( $db->get_recs("lang_vars", "count(*) as c_c", "", "") );
	$count_vars = $count_vars[0]->c_c;

	// Get All Langs
	$lang_list = $db->fetch_objects( $db->get_recs("lang_list", "*", "", "lang_short_name ASC") );
	foreach($lang_list as $lang){
		$count_trans = $db->fetch_objects( $db->get_recs("lang_trans", "count(*) as c_c", "lang_id='".$lang->lang_id."'", "") );
		$count_trans = $count_trans[0]->c_c;
		if ($count_trans < $count_vars){ // NEED SYNC


		}
	}
*/

/*
	// Get All VARS
	$out_data = array();
	$var_list = $db->fetch_objects( $db->get_recs("lang_vars", "*", "", "name ASC") );
	foreach($var_list as $o){
		// FOREACH LANGS
		foreach($lang_list as $lang){
			// Get Translation list for Var for current Lang
			$trans_data = $db->get_rec("lang_trans", "*", "var_id='".$o->var_id."' AND lang_id='".$lang->lang_id."'" );
			if (!$trans_data->id){ // Need CREATE TRANS-var
				pr($trans_data);
			}
		}
	}
*/ 
}

if ($_POST['btn_var_insert'] && !empty($_POST['name'])){
	$_POST['name'] = mysql_real_escape_string($_POST['name']);
	$var_data = $db->get_rec("lang_vars", "*", "name='".$_POST['name']."'", "");
	if ($var_data->var_id){
		err('Variable exists!');
	}
	else{
		$result = $db->db_insert("lang_vars", "name", "'{$_POST['name']}'");
		if($result) {
			ok("New variable has been created successfully");
			update_langfile();
		}
		else err("An error occured while trying to create a new Variable");
	}
}

if ( $_POST['btn_var_update'] && is_numeric($_POST['var_id']) && !empty($_POST['name']) ){
	$_POST['name'] = mysql_real_escape_string($_POST['name']);
	$result = $db->db_update("lang_vars", "name='{$_POST['name']}'", "var_id={$_POST['var_id']}");
	if($result) {
		ok("Changes saved successfully");
		update_langfile();
	}
	else err("An error occured while trying to save Variable");
}

if ( is_numeric($_GET['var_delete_id']) ){
	$result = $db->db_delete("lang_vars", "var_id=".$_GET['var_delete_id'] );
	if($result) {
		ok("Variable delete successfully");
		update_langfile();
	}
	else err("An error occured while trying to delete Variable");
}

// get list of vars
$var_list = $db->fetch_objects( $db->get_recs("lang_vars", "*", "", "name ASC") );

?>
<table>
<tr><td>
<form action="<?php echo $self; ?>" method="POST">
<input type="text"   name="name" value="<?php $_POST['name'];?>" size="30" >
<input type="submit" name="btn_var_insert" value="ADD NEW" >
</form>
</td></tr>
<?php foreach($var_list as $v): ?>
<tr><td>
<form action="<?php echo $self; ?>" method="POST">
<input type="text"   name="name" value="<?php echo $v->name?>" size="50" >
<input type="hidden" name="var_id" value="<?php echo $v->var_id?>" >
<input type="submit" name="btn_var_update"  value="update" >
<a href="<?php echo $self.'&var_delete_id='.$v->var_id;?>" OnClick="return confirm('Delete Variable?')" style="color:#f00;">X</a>
</form>
</td></tr>
<?php endforeach; ?>
</table>