<div id="profile_header">
	<h1>{$reply_to_msg}</h1>
	<img src='avatars_mini/{$m->get_avatar()}' class='avatar' alt='{$m->user}' />
	<div class='when'>{$m->how_long_ago()}<br />{$from} {$m->from}</div>
	<p>{$m->user}:<br />{$m->msg}</p>
	<div class="clear">&nbsp;</div>

	<h1>{$your_reply}</h1>
	<form method="post" action="reply" enctype="multipart/form-data">
	<img src='avatars_mini/{$logged_user->avatar}' class='avatar' alt='{$logged_user->username}' />
	<input type="hidden" name="msg_id" value="{$m->id}" />
	<textarea id="message" name="message" cols="30" rows="3"></textarea><br />
	<label for="add_photo">{$upload_picture}</label><input type="file" name="add_photo" id="add_photo" /><br />
	<input type="hidden" name="user" value="{$logged_user->username}" />
	<div class='mid'>
	<span id='chars_left'>140</span> {$chars_left}<br />
	<input type="submit" name="add_message" value="{$label_send}" class="submit" />
	</div>
	</form>
</div>
{if $ok}<p class="ok">{$ok}</p>{/if}
{if $error}<p class="error">{$error}</p>{/if}

