<h2>{$photo_header}</h2>
<p class="tabs">
<a href="index.php/settings/my_photo" id="upload" class="profile">{$tab_upload}</a>
<a href="settings/my_photo" id="photo_set" class="profile">{$tab_picture_set}</a>
</p>
<div id="upload_photo">
<div id="avatar_preview">
<img src='avatars_mini/{$logged_user->avatar}' alt='{$logged_user->username}' class='avatar_preview' />
<p class="comment">{$refresh_warning}</p>
</div>
<h3>{$upload_your_photo}</h3>
<p>{$upload_comment}</p>
<div class="clear">&nbsp;</div>
<p class="comment">{$upload_hint}</p>
<form action="settings/my_photo" method="post" enctype="multipart/form-data">
<input type="file" name="picture" id="picture" class="wpisz_cos" /><br />
<input type="submit" class="submit" name="upload_photo" value="{$label_upload_now}" />
</form>
</div>
<div id="choose_photo">
<h3>{$choose_your_photo}</h3>
<form action="settings/my_photo" method="post" enctype="multipart/form-data">
<div id="picture_set">
{foreach from=$files item=f}
	<img src="sample_photos/{$f}" alt="{$f}" class="avatar choose" />
{/foreach}
</div>
<div class="clear">&nbsp;</div>
<input type='hidden' name='chosen_photo' id='chosen_photo' value='' />
<input type="submit" class="submit" name="choose" value="{$label_choose_this_photo}" />
</form>
</div>

{if $ok}<p class="ok">{$ok}</p>{/if}
{if $error}<p class="error">{$error}</p>{/if}
