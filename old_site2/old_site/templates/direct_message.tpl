<h1>{$direct_msg}</h1>
<div id="profile_header">
	<h1>{$send_msg_to} {$user->username}</h1>
	<form method="post" action="direct_message/{$user->username}">
	<input type="hidden" name="direct" value="{$user->id}" />
	<a href="profile/{$user->username}"><img src='avatars_mini/{$user->avatar}' class='avatar' alt='{$user->username}' /></a>
	<textarea id="message" name="message" cols="30" rows="3"></textarea><br />
	<input type="hidden" name="user" value="{$logged_user->username}" />
	<div class='srodek'>
	<span id='chars_left'></span>{$chars_left}<br />
	<input type="submit" name="add_message" value="{$label_send}" class="submit" />
	</div>
	</form>
</div>
{if $ok}<p class="ok">{$ok}</p>{/if}
{if $error}<p class="error">{$error}</p>{/if}
