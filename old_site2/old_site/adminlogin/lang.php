<h1>Languages settings</h1>

<?php

//Update lang file - synchronisation File with DB
function update_langfile(){
	global $db;
	// Work with Current language settings
	$dir = dirname(FILE_LANG);
	if (!is_dir($dir) || !preg_match('/langs$/i', $dir)){
		err('Invalid Language Folder');
		return false;
	}
	// Get All VARS
	$out_data = array();
	$var_list = $db->fetch_objects( $db->get_recs("lang_vars", "*", "", "name ASC") );
	foreach($var_list as $o){
		// Get Translation for Var for current Lang
		$trans_data = $db->get_rec("lang_trans", "*", "var_id='".$o->var_id."' AND lang_id='".lang_current_id."'" );
		$out_data[$o->name] = ($trans_data->id ? $trans_data->value : $o->name );
	}

	if (!sizeof($out_data)){
		err('Data for save is empty');
		return false;
	}

	if ($fh = @fopen(FILE_LANG, 'w')){
		// Prepare vars
		$tmp=array();
		foreach($out_data as $k=>$v) $tmp[] = "define(".$k.", \"".str_replace('"',"\\\"",$v)."\");";
		unset($out_data);
		$str = "<?php\n// Warning! NO EDIT THIS FILE!!!\n// For Edit use Admin-Control-Panel Interface\n// File Created:".date("H:i:s d.m.Y")."\n\n";
		$str .= implode("\n", $tmp);
		$str .= "\n\n?>";
		unset($tmp);
		fwrite($fh, $str );
		fclose($fh);
		@chmod(FILE_LANG, 0777);
	}
	else{
		err('Can\'t WRITE to Language file. See write permission settings for "Langs" folder');
		return false;
	}
}

// SET LANG DEF
$lang_default = lang_default;
if ($_POST['btn_lang_def'] && !empty($_POST['lang_def_id']) ){
	$_POST['lang_def_id'] = substr($_POST['lang_def_id'], 0, 3);
	$_POST['lang_def_id'] = strtoupper($_POST['lang_def_id']);
	$lang_default = $_POST['lang_def_id'];
	$lines = file("../inc/config.php");
	$p = fopen("../inc/config.php", "w");
	flock($p, LOCK_EX);
	foreach($lines as $l){
		if(eregi("lang_default", $l)) fwrite($p, "define(\"lang_default\", \"".$_POST['lang_def_id']."\");\n");
		else fwrite($p, $l);
	}
	flock($p, LOCK_UN);
	fclose($p);
	ok("Default Language save successfully. Reload the page to see new configuration.");

}
// INSERT LANGUAGE
if ($_POST['btn_lang_insert']){
	if (!empty($_POST['lang_new_short']) && !empty($_POST['lang_new_full']) && !empty($_POST['lang_new_charset'])){
		$_POST['lang_new_short'] = mysql_real_escape_string($_POST['lang_new_short']);
		$_POST['lang_new_short'] = strtoupper($_POST['lang_new_short']);
		$_POST['lang_new_full']  = mysql_real_escape_string($_POST['lang_new_full']);
		$_POST['lang_new_charset']  = mysql_real_escape_string($_POST['lang_new_charset']);

		$result = $db->db_insert("lang_list", "lang_short_name, lang_full_name, lang_charset", "'{$_POST['lang_new_short']}', '{$_POST['lang_new_full']}', '{$_POST['lang_new_charset']}' ");
		if($result) ok("New Language has been created successfully");
		else err("An error occured while trying to create a new Language");
	}
	else{
		err("An error occured while trying to create a new Language");
	}
}
// UPDATE LANGUAGE
if ($_POST['btn_lang_update'] && is_numeric($_POST['lang_cur_id'])){
	if (!empty($_POST['lang_cur_short']) && !empty($_POST['lang_cur_full']) && !empty($_POST['lang_cur_charset']) ){
		$_POST['lang_cur_short'] = mysql_real_escape_string($_POST['lang_cur_short']);
		$_POST['lang_cur_short'] = strtoupper($_POST['lang_cur_short']);
		$_POST['lang_cur_full']  = mysql_real_escape_string($_POST['lang_cur_full']);
		$_POST['lang_cur_charset']  = mysql_real_escape_string($_POST['lang_cur_charset']);
		$result = $db->db_update("lang_list", "lang_short_name='{$_POST['lang_cur_short']}', lang_full_name='{$_POST['lang_cur_full']}', lang_charset='{$_POST['lang_cur_charset']}' ", "lang_id={$_POST['lang_cur_id']}");
		if($result) ok("Changes saved successfully");
		else err("An error occured while trying to save Language data");
	}
	else{
		err("An error occured while trying to save new Language data");
	}
}
// DELETE LANGUAGE
if ($_GET['lang_cur_delete_id'] && is_numeric($_GET['lang_cur_delete_id'])){
	$result = $db->db_delete("lang_list", "lang_id=".$_GET['lang_cur_delete_id'] );
	if($result) ok("Delete Language successfully");
	else err("An error occured while trying to delete Language");
}


// COMMON DATA
$langs_def=array();
$recs = $db->fetch_objects( $db->get_recs("lang_list", "*", "", "lang_short_name ASC") );
foreach($recs as $o) {
	$langs_def[$o->lang_short_name] = $o->lang_full_name;
}
define('lang_current', array_key_exists($_GET['lang_current'],$langs_def )?$_GET['lang_current']:lang_default );
define('DIR_LANG',  dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'langs'.DIRECTORY_SEPARATOR);
define('FILE_LANG', DIR_LANG.get_langfilename(lang_current) );

$lang_data = $db->get_rec("lang_list", "*", "lang_short_name='".lang_current."'", "lang_short_name ASC");
define('lang_current_id', $lang_data->lang_id);
$self = '?id='.$_GET['id'].'&lang_current='.lang_current; 

// SUBMENU
$submenu=array('list'=>'Languages', 'vars'=>'Variables','trans'=>'Translations','upload'=>'Upload');
$submenu_id = $_GET['sub_id']?$_GET['sub_id']:'list';
foreach($submenu as $k=>$v){
	if ($submenu_id==$k) echo '<a href="'.$self.'&sub_id='.$k.'"><div style="float:left;text-align:center;padding:7px;background:#00f;color:#fff;border-bottom:1px solid #00f;">'.$v.'</div></a>';
	else echo '<a href="'.$self.'&sub_id='.$k.'"><div style="float:left;text-align:center;padding:7px;border-bottom:1px solid #00f">'.$v.'</div></a>';
}

?>
<div style="float:left;margin-left:20px;padding:2px;border-left:1px solid #00f;background:#ccc;">
Language: <select name="lang_current" size="1" OnChange="document.location='?id=<?php echo $_GET['id'];?>&sub_id=<?php echo $submenu_id;?>&lang_current='+this.value" >
<?php
foreach($langs_def as $k=>$v){
	echo '<option value="'.$k.'" '.($k==lang_current?'selected="selected"':'').' >'.$v.'</option>';
}
?>
</select>
</div>

<br />
<br />

<?php
$self .= '&sub_id='.$submenu_id;
$tmp_filename = $_GET['id']."_".$submenu_id.".php";
if(is_file($tmp_filename)) include($tmp_filename);

?>