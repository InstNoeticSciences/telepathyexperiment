{if !$logged_user}
<h1 class='header'>{$m->user}{$users_message}</h1>
{else}
<h1>{$m->user}{$users_message}</h1>
{/if}
<div id="profile_header">
	<img src='avatars_mini/{$m->get_avatar()}' class='avatar' alt='{$m->user}' />
	{if $user->visible}
		<div class='when'>{$m->how_long_ago()}<br />{$from} {$m->from}</div>
		<p>{$m->user}:<br />{$m->msg}</p>
		{if $logged_user && $m->user!=$logged_user->username}
			<form method="post" action="reply">
			<input type="hidden" name="msg_id" value="{$m->id}" />
			<input type="submit" name="reply" class="reply" value="{$label_reply}" />
			</form>
		{/if}
	{else}
		<p>{$updates_protected}</p>
	{/if}
	<div class="clear">&nbsp;</div>
</div>