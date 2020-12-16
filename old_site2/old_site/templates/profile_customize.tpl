<h1>{$profile_tab_customize}</h1>
<form method="post" action="profile/{$user->username}/customize" enctype="multipart/form-data">
<h3>{$my_lay_background}</h3>
<label for="back_color">{$label_back_color}</label><input type="text" name="back_color" id="back_color" class="wpisz_cos" value="{$layout->back_color}" /><br />
<label for="back_image">{$label_back_image}</label><input type="file" name="back_image" id="back_image" class="wpisz_cos" /><br />
<label for="use_image">{$label_use_image}</label>
{if $layout->use_image}
	<input type="checkbox" name="use_image" id="use_image" value="1" class="chk" checked="checked" />
{else}
	<input type="checkbox" name="use_image" id="use_image" value="1" class="chk" />
{/if}
<br />
<label for="back_tile">{$label_tile_image}</label>
{if $layout->back_tile}
	<input type="checkbox" name="back_tile" id="back_tile" value="1" class="chk" checked="checked" />
{else}
	<input type="checkbox" name="back_tile" id="back_tile" value="1" class="chk" />
{/if}
<br />
<label for="back_fixed">{$label_back_fixed}</label>
{if $layout->back_fixed}
	<input type="checkbox" name="back_fixed" id="back_fixed" value="1" class="chk" checked="checked" />
{else}
	<input type="checkbox" name="back_fixed" id="back_fixed" value="1" class="chk" />
{/if}
<br />
<h3>{$my_lay_bubble}</h3>
<label for="bubble_text_color">{$label_bubble_text}</label><input type="text" name="bubble_text_color" id="bubble_text_color" class="wpisz_cos" value="{$layout->bubble_text_color}" /><br />
<label for="bubble_fill_color">{$label_bubble_fill}</label><input type="text" name="bubble_fill_color" id="bubble_fill_color" class="wpisz_cos" value="{$layout->bubble_fill_color}" /><br />
<h3>{$my_lay_boxes}</h3>
<label for="side_border_color">{$label_border_color}</label><input type="text" name="side_border_color" id="side_border_color" class="wpisz_cos" value="{$layout->side_border_color}" /><br />
<label for="side_fill_color">{$label_side_fill}</label><input type="text" name="side_fill_color" id="side_fill_color" class="wpisz_cos" value="{$layout->side_fill_color}" /><br />
<h3>{$my_lay_other}</h3>
<label for="text_color">{$label_text_color}</label><input type="text" name="text_color" id="text_color" class="wpisz_cos" value="{$layout->text_color}" /><br />
<label for="link_color">{$label_link_color}</label><input type="text" name="link_color" id="link_color" class="wpisz_cos" value="{$layout->link_color}" /><br />
<label for="top_area_color">{$label_msg_area}</label><input type="text" name="top_area_color" id="top_area_color" class="wpisz_cos" value="{$layout->top_area_color}" /><br />

<label>&nbsp;</label><input type="submit" name="save_layout" value="{$label_save_changes}" class="submit" />
<p class="mid"><input type="submit" class="log_reg" value="{$label_back_default}" name="reset_layout" /></p>
</form>
{if $ok}<p class="ok">{$ok}</p>{/if}
{if $error}<p class="error">{$error}</p>{/if}
