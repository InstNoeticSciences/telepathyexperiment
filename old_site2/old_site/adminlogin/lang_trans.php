<?php



if ( $_POST['btn_trans_update_all'] && is_numeric($_POST['lang_id']) ){

	// INSERT NEW TRANSLATIONS

	if ( is_array($_POST['trans_value_new']) && sizeof($_POST['trans_value_new']) ){

		foreach($_POST['trans_value_new'] as $var_id=>$trans_value){

			$trans_value = mysql_real_escape_string($trans_value);

			$result = $db->db_insert("lang_trans", "lang_id,var_id,value", "'{$_POST['lang_id']}','{$var_id}','{$trans_value}'");

			if (!$result) err("An error occured while trying to create a new Translation");

		}

	}

	// UPDATE OLD TRANSLATIONS

	if ( is_array($_POST['trans_value']) && sizeof($_POST['trans_value']) ){

		foreach($_POST['trans_value'] as $trans_id=>$trans_value){

			$trans_value = mysql_real_escape_string($trans_value);
			$result = $db->db_update("lang_trans", "value='{$trans_value}'", "id={$trans_id}");
			 if (!$result) err("An error occured while trying to update Translation");

		}

	}

	update_langfile();

}



// get list of vars

$var_list = $db->fetch_objects( $db->get_recs("lang_vars", "*", "", "name ASC" ) );



// get REAL trans list

$tmp = $db->fetch_objects( $db->get_recs("lang_trans", "*",  "lang_id='".$lang_data->lang_id."'", "" ) );

$trans_list=array();

if (sizeof($tmp)){

	foreach($tmp as $o){

		$trans_list[$o->var_id] = array('id'=>$o->id, 'lang_id'=>$o->lang_id, 'var_id'=>$o->var_id, 'value'=>$o->value );

	}

}

unset($tmp);

//pr($trans_list,1);

?>

<style>.trans_ta{width:500px;height:50px;border:1px solid #000;}</style>

<form action="<?php echo $self; ?>" method="POST">

<input type="hidden" name="lang_id" value="<?php echo $lang_data->lang_id;?>" >

<table>

<tr>

<td colspan="2" align="right"><input type="submit" name="btn_trans_update_all"  value="UPDATE ALL" style="color:#090;"></td>

</tr>

<?php foreach($var_list as $v): ?>

<tr>

<td><?php echo $v->name?></td>

<td>

<?php 

if ($trans_list[$v->var_id]['id']){

	echo $lang_data->lang_short_name.': <textarea name="trans_value['.$trans_list[$v->var_id]['id'].']" class="trans_ta">'.$trans_list[$v->var_id]['value'].'</textarea>';

}

else{

	echo $lang_data->lang_short_name.': <textarea name="trans_value_new['.$v->var_id.']" class="trans_ta"></textarea>';

}

?>

</td></tr>

<?php endforeach; ?>

<tr>

<td colspan="2" align="right"><input type="submit" name="btn_trans_update_all"  value="UPDATE ALL" style="color:#090;"></td>

</tr>

</table>

</form>