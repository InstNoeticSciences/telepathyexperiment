<?php

if ($_POST['btn_lang_upload']){
	if ($_FILES['file']['error']==0 && $_FILES['file']['size']>0){
		$tmp = file($_FILES['file']['tmp_name']);
		foreach($tmp as $v){
			if (!preg_match("/define/i", $v))
				continue;

			$v = trim($v);
			$v = preg_replace("/^define\(/i",'', $v);
			$v = preg_replace("/\);$/i",'', $v);
			$vv = explode(', ', $v);
			$oo=array();
			if (sizeof($vv)>2){
				$oo[0]=$vv[0];
				$vvv = $vv;
				unset($vvv[0]);
				$oo[1] = implode(', ', $vvv);
			}
			else{
				$oo[0]=$vv[0];
				$oo[1]=$vv[1];
			}
			$var_data   = $db->get_rec("lang_vars", "*", "name='".$oo[0]."' " );
			if ($var_data->var_id){
				$trans_data = $db->get_rec("lang_trans", "*", "var_id='".$var_data->var_id."' AND lang_id='".$lang_data->lang_id."'" );
				$oo[1] = preg_replace('/^"/i', '', $oo[1]);
				$oo[1] = preg_replace('/"$/i', '', $oo[1]);
				$oo[1] = str_replace('\\"', '"', $oo[1]);
				$oo[1] = mysql_real_escape_string($oo[1]);
				if ($trans_data->id){ // Update
					$result = $db->db_update("lang_trans", " value='{$oo[1]}' ", " id='{$trans_data->id}' " );
					if($result) ok("UPDATED Translation (".$var_data->name.") successfully");
					else err("UPDATED Translation (".$var_data->name.") Error!");
				}
				else{ // Insert
					$result = $db->db_insert("lang_trans", "lang_id,var_id,value ", "{$lang_data->lang_id}, {$var_data->var_id} ,'{$oo[1]}' " );
					if($result) ok("INSERT Translation (".$var_data->name.") successfully.");
					else err("INSERT Translation (".$var_data->name.") Error!");
				}
			}
		}
		update_langfile();
	}
	else err('Error - invalid uploaded file!');
}

echo '<br>';
form_begin(null, 1);
form_file("file", "Select file (".$lang_data->lang_short_name.") :");
form_submit("btn_lang_upload", "UPLOAD' OnClick='return confirm(\"Warning! All [".$lang_data->lang_short_name."] translations will be CHANGED!\")");
form_end();

/*
// Manual - Convert file to DB
$tmp = file('../inc/text.php');
foreach($tmp as $v){
	if (!preg_match("/define/i", $v))
		continue;

	$v=trim($v);
	$v = preg_replace("/^define\(/i",'', $v);
	$v = preg_replace("/\);$/i",'', $v);
	$vv = explode(', ', $v);
	$oo=array();
	if (sizeof($vv)>2){
		$oo[0]=$vv[0];
		$vvv = $vv;
		unset($vvv[0]);
		$oo[1] = implode(', ', $vvv);
	}
	else{
		$oo[0]=$vv[0];
		$oo[1]=$vv[1];
	}
	$var_data = $db->get_rec("lang_vars", "*", "name='".$oo[0]."'" );
	if ($var_data->var_id){
		$oo[1] = str_replace('"','', $oo[1]);
//		$result = $db->db_insert("lang_trans", "lang_id,var_id,value", "1,".$var_data->var_id.", '".$oo[1]."'");
	}
}
exit;
//*/

?>