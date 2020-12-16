{if !$logged_user}
<h1 class='header'>{$favorites_header} ({$user->username})</h1>
{else}
<h1>{$favorites_header} ({$user->username})</h1>
{/if}

{if $favorites}
	{$pages}
	{foreach from=$favorites item=m key=k}
		{if $k%2==0}<div class='msg yellow'>{else}<div class='msg'>{/if}

		<a href='profile/{$m->user}'><img src='avatars_mini/{$m->get_avatar()}' class='avatar' alt='{$m->user}' /></a>

		{if $m->get_text_color()}
			<div class='when'>{$m->how_long_ago()} {$from} {$m->from}</div>
			<div class='msg_content'>
			<a href='profile/{$m->user}' class='username'>{$m->user}</a>:<br />
		{else}
			<div class='when'>{$m->how_long_ago()} {$from} {$m->from}</div>
			<div class="msg_content">
			<a href='profile/{$m->user}' class='username'>{$m->user}</a>:<br />
		{/if}
				{if $m->reply} {$in_reply_to}<a href="message/{$m->reply}">{$m->reply_get_username()}</a>: {/if}
				{$m->parse_links()}
			</div>
		<div class="msg_controls">
			{if $m->user == $logged_user->username || $m->user == "admin"}
				<form method="post" action="favorites/{$user->username}">
				<input type="hidden" name="dw" value="{$m->id}" />
				<input type="submit" value=" " name="delete" class="delete_msg" title="{$title_delete}" />
				</form>
			{/if}
			{if $logged_user}
				<form method="post" action="reply">
				<input type="hidden" name="msg_id" value="{$m->id}" />
				<input type="submit" name="reply" class="reply" value=" " />
				</form>
				{if $m->is_favorite($logged_user->id)}
					<img src="grafika/heart_delete.png" class="fav_del" width="16" height="16" alt="{$logged_user->id}_{$m->id}" title="{$title_fav_del}" />
				{else}
					<img src="grafika/heart_add.png" class="fav_add" width="16" height="16" alt="{$logged_user->id}_{$m->id}" title="{$title_fav_add}" />
				{/if}
			{/if}
			</div>
		</div>
	{/foreach}
	{$pages}
{else}
<p>No messages</p>
{/if}
