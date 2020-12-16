<?
function form_begin($action='', $upload=0){
	echo "<form action='$action' method='post'";
	if($upload) echo " enctype='multipart/form-data'";
	echo ">";
}
function form_end(){
	echo "</form>";
}

function form_text($name, $label, $value='', $class='wpisz_cos'){
	if($label) echo "<label for='$name'>$label</label>";
	echo "<input type='text' name='$name' id='$name' maxlength='140'";
	if($class) echo " class='$class'";
	if($value) echo " value='$value'";
	echo " /><br />";
}
function form_text_nobr($name, $label, $value='', $class='wpisz_cos'){
	if($label) echo "<label for='$name'>$label</label>";
	echo "<input type='text' name='$name' id='$name' maxlength='140'";
	if($class) echo " class='$class'";
	if($value) echo " value='$value'";
	echo " />";
}

function form_password($name, $label, $value='', $class='wpisz_cos'){
	if($label) echo "<label for='$name'>$label</label>";
	echo "<input type='password' name='$name' id='$name' maxlength='140'";
	if($class) echo " class='$class'";
	if($value) echo " value='$value'";
	echo " /><br />";
}
function form_password_nobr($name, $label, $value='', $class='wpisz_cos'){
	if($label) echo "<label for='$name'>$label</label>";
	echo "<input type='password' name='$name' id='$name' maxlength='140'";
	if($class) echo " class='$class'";
	if($value) echo " value='$value'";
	echo " />";
}

function form_hidden($name, $value){
	echo "<input type='hidden' name='$name' value='$value' />\n";
}

function form_textarea($name, $label, $value='', $class='wpisz_cos'){
	if($label) echo "<label for='$name'>$label</label>";
	echo "<textarea name='$name' id='$name' cols='30' rows='5'";
	if($class) echo " class='$class'";
	echo ">";
	if($value) echo $value;
	echo "</textarea><br />";
}
function form_textarea_nobr($name, $label, $value='', $class='wpisz_cos'){
	if($label) echo "<label for='$name'>$label</label>";
	echo "<textarea name='$name' id='$name' cols='30' rows='5'";
	if($class) echo " class='$class'";
	echo ">";
	if($value) echo $value;
	echo "</textarea>";
}

function form_checkbox($name, $label, $chk='', $value=1, $class=''){
	if($label) echo "<label for='$name'>$label</label>";
	echo "<input type='checkbox' name='$name' id='$name' value='$value' ";
	if($chk!='' && $chk!=0) echo "checked='checked'";
	if($class != '') echo " class='$class chk'";
	else echo " class='chk'";
	echo " /><br />";
}
function form_checkbox_nobr($name, $label, $chk='', $value=1, $class=''){
	if($label) echo "<label for='$name'>$label</label>";
	echo "<input type='checkbox' name='$name' id='$name' value='$value' ";
	if($chk!='' && $chk!=0) echo "checked='checked'";
	if($class != '') echo " class='$class chk'";
	else echo " class='chk'";
	echo " /><br />";
}
function form_checkbox_inside($name, $label, $chk='', $value=1){
	if($label) {
		echo "<label for='$name'>$label ";
		echo "<input type='checkbox' name='$name' id='$name' value='$value' ";
		if($chk!='' && $chk!=0) echo "checked='checked'";
		echo " /></label>";
	}
	else {
		echo "<input type='checkbox' name='$name' id='$name' value='$value' ";
		if($chk!='' && $chk!=0) echo "checked='checked'";
		echo " />";
	}
	echo "<br />";
}

function form_file($name, $label, $class='wpisz_cos'){
	if($label) echo "<label for='$name'>$label</label>";
	echo "<input type='file' name='$name' id='$name'";
	if($class) echo " class='$class'";
	echo " /><br />";
}
function form_file_nobr($name, $label, $class='wpisz_cos'){
	if($label) echo "<label for='$name'>$label</label>";
	echo "<input type='file' name='$name' id='$name'";
	if($class) echo " class='$class'";
	echo " />";
}

function form_submit($name, $value='', $class=''){
	echo "<label>&nbsp;</label>";
	echo "<input type='submit' name='$name' value='$value'";
	if($class) echo " class='$class'";
	echo " />";
}

function form_submit_nl($name, $value='', $class=''){
	echo "<input type='submit' name='$name' value='$value'";
	if($class) echo " class='$class'";
	echo " />";
}



function form_select($name, $label, $captions, $values, $default=0, $class="wpisz_cos"){
	if($label) echo "<label for='$name'>$label</label>";
	echo "<select name='$name' id='$name'";
	if($class!='') echo " class='$class'";
	echo " >";
	if(is_array($values) && is_array($captions)) {
		foreach($values as $k=>$v) {
			if($v == $default) $s = "selected='selected'";
			else $s = "";
			echo "<option value='$v' $s>".$captions[$k]."</option>";
		}
	}
	echo "</select><br />";
}
function form_select_nobr($name, $label, $captions, $values, $default=0, $class="wpisz_cos"){
	if($label) echo "<label for='$name'>$label</label>";
	echo "<select name='$name' id='$name'";
	if($class!='') echo " class='$class'";
	echo ">";
	if(is_array($values) && is_array($captions)) {
		foreach($values as $k=>$v) {
			if($v == $default) $s = "selected='selected'";
			else $s = "";
			echo "<option value='$v' $s>".$captions[$k]."</option>";
		}
	}
	echo "</select>";
}

function form_button($name, $value, $js='', $class=''){
	echo "<input type='button' name='$name' id='$name' value='$value'";
	if($js) echo " onclick=\"$js\"";
	if($class) echo " class='$class'";
	echo " />";
}

//admin panel forms
function del_form($id){
	echo "<td class='watch_out'>";
	form_begin();
	form_hidden('dw', $id);
	form_submit_nl('del', 'X', 'delete');
	form_end();
	echo "</td>";
}
function mod_form($action, $id){
	echo "<td class='do_it'>";
	form_begin("index.php?id=$action");
	form_hidden('mod', $id);
	form_submit_nl('edit', 'Edit', 'edit');
	form_end();
	echo "</td>";
}
?>