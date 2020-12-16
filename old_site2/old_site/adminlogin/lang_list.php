<?php

// EDIT LANG
echo "<br/><b>Edit current language:</b><br/>";
form_begin();
form_text("lang_cur_short",	"Short name *:",		$lang_data->lang_short_name);
form_text("lang_cur_full",	"Full name *:",		$lang_data->lang_full_name);
form_text("lang_cur_charset",	"HTML Character set *:",	$lang_data->lang_charset);
form_hidden("lang_cur_id", $lang_data->lang_id);
form_submit("btn_lang_update", "Save changes");
echo '<br/>';
echo '<a style="color:#f00;" OnClick="return confirm(\'Delete this language and all its Variables?\');" href="'.$self.'&lang_cur_delete_id='.$lang_data->lang_id.'">Delete current Language</a>';
form_end();

// ADD NEW LANG
echo '<br/><hr>';
echo "<br/><b>Add new language:</b><br/>";
form_begin();
form_text("lang_new_short",	"Short name *:",		stripslashes($_POST['lang_new_short']) );
form_text("lang_new_full",	"Full name *:",		stripslashes($_POST['lang_new_full']) );
form_text("lang_new_charset",	"HTML Character set *:",	stripslashes($_POST['lang_new_charset']) );
form_submit("btn_lang_insert", "ADD");
form_end();

// SELECT DEFAULT LANGUAGE
echo '<br/><hr>';
echo "<br/><b>Select Default Language:</b><br/>";
form_begin();
form_select("lang_def_id", "Default language:", array_values($langs_def), array_keys($langs_def), $lang_default , '' );
form_submit("btn_lang_def", "Save");
form_end();

?>