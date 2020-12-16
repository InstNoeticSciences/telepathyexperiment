<?
include("../inc/config.php");
include("../inc/text.php");
include("../lib/functions.php");
include("../lib/forms.php");
include("../lib/database.php");
include("../lib/images.php");
include("../lib/messages.php");
include("../lib/user.php");

$db = new database;
$db->dblink();

$uid = $_GET['user'];

$rec = $db->get_rec("users", "*", "id=$uid");
$user = new user($rec);

$layout = $db->get_rec("layouts", "*", "user={$user->id}");
if(!$layout) {
	$db->db_insert("layouts", "user", "{$user->id}");
	$layout = $db->get_rec("layouts", "*", "user={$user->id}");
}

echo "<h1 class='first'>".profile_tab_customize."</h1>";
form_begin("profile/{$user->username}/customize", 1);
echo "<h3>".my_lay_background."</h3>";

echo "<label for='back_color'>".label_back_color."</label><input type='text' name='back_color' id='back_color' class='wpisz_cos' value='{$layout->back_color}' /><br />";
echo "<label for='back_image'>".label_back_image."</label><input type='file' name='back_image' id='back_image' class='' />";
echo "<label>&nbsp;</label><p class='comment'>".max500.refresh_warning."</p>";
echo "<div id='bglib'>";
	echo "<a href='#' class='back_lib'>".choose_bglib."</a>";
	echo "<div id='backs'>";
		echo "<input type='hidden' id='background_name' name='background_name' value='' />";
		$files = get_files("../bglib");
		if(is_array($files)){
			foreach($files as $file){
				$size = getimagesize("../bglib/$file");
				$mime = $size['mime'];
				if($mime == 'image/gif' || $mime == 'image/jpeg' || $mime == 'image/png') echo "<a href='#' class='bglib_link' rel='$file'><img src='../bglib/$file' alt='$file' class='bglib_pic' /></a>";
			}
		} else echo "<p>No files found</p>";
		echo "<div class='clear'>&nbsp;</div>";
	echo "</div>";
echo "</div>";
echo "<label for='use_image'>".label_use_image."</label>";
if($layout->use_image) echo "<input type='checkbox' name='use_image' id='use_image' value='1' class='chk' checked='checked' /><br />";
else echo "<input type='checkbox' name='use_image' id='use_image' value='1' class='chk' /><br />";
echo "<label for='back_tile'>".label_tile_image."</label>";
echo "<select id='back_tile' name='back_tile' class='wpisz_cos'>";
if(!$layout->back_tile) echo "<option value='0' selected='selected'>".tile_no."</option>";
else echo "<option value='0'>".tile_no."</option>";
if($layout->back_tile==1) echo "<option value='1' selected='selected'>".tile_h."</option>";
else echo "<option value='1'>".tile_h."</option>";
if($layout->back_tile==2) echo "<option value='2' selected='selected'>".tile_v."</option>";
else echo "<option value='2'>".tile_v."</option>";
if($layout->back_tile==3) echo "<option value='3' selected='selected'>".tile_both."</option>";
else echo "<option value='3'>".tile_both."</option>";
echo "</select><br />";
echo "<label for='back_fixed'>".label_back_fixed."</label>";
if($layout->back_fixed) echo "<input type='checkbox' name='back_fixed' id='back_fixed' value='1' class='chk' checked='checked' /><br />";
else echo "<input type='checkbox' name='back_fixed' id='back_fixed' value='1' class='chk' /><br />";
echo "<div class-'clear'>&nbsp;</div>";
// echo "<h3>".my_lay_bubble."</h3>";
// echo "<label for='bubble_text_color'>".label_bubble_text."</label><input type='text' name='bubble_text_color' id='bubble_text_color' class='wpisz_cos' value='{$layout->bubble_text_color}' /><br />";
// echo "<label for='bubble_fill_color'>".label_bubble_fill."</label><input type='text' name='bubble_fill_color' id='bubble_fill_color' class='wpisz_cos' value='{$layout->bubble_fill_color}' /><br />";
// echo "<div class-'clear'>&nbsp;</div>";
echo "<h3>".my_lay_boxes."</h3>";
echo "<label for='side_border_color'>".label_border_color."</label><input type='text' name='side_border_color' id='side_border_color' class='wpisz_cos' value='{$layout->side_border_color}' /><br />";
echo "<label for='side_fill_color'>".label_side_fill."</label><input type='text' name='side_fill_color' id='side_fill_color' class='wpisz_cos' value='{$layout->side_fill_color}' /><br />";
echo "<div class-'clear'>&nbsp;</div>";
echo "<h3>".my_lay_other."</h3>";
echo "<label for='text_color'>".label_text_color."</label><input type='text' name='text_color' id='text_color' class='wpisz_cos' value='{$layout->text_color}' /><br />";
echo "<label for='link_color'>".label_link_color."</label><input type='text' name='link_color' id='link_color' class='wpisz_cos' value='{$layout->link_color}' /><br />";
echo "<label for='top_area_color'>".label_msg_area."</label><input type='text' name='top_area_color' id='top_area_color' class='wpisz_cos' value='{$layout->top_area_color}' /><br />";
echo "<div class-'clear'>&nbsp;</div>";
echo "<label>&nbsp;</label><input type='submit' name='save_layout' value='".label_save_changes."' class='submit' />";
echo "<p class='mid'><input type='submit' class='log_reg' value='".label_back_default."' name='reset_layout' /></p>";
form_end();

if($ok) ok($ok);
if($error) err($error);
?>