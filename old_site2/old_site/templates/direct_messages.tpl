<h1>{$direct_msgs_to} {$logged_user->username}</h1>
<p class="tabs"><a href="#" id="link_inbox" class="profile">{$inbox}</a> <a href="#" id="link_outbox" class="profile">{$outbox}</a></p>
<div id="dm_inbox">
{if $messages}
	{foreach from=$messages item=m key=k}
		{if $m->get_back_color()}
			{if $k%2==0} <div class='msg yellow' style="background-color: {$m->get_back_color()}"> {else} <div class='msg' style="background-color: {$m->get_back_color()}"> {/if}
		{else}
			{if $k%2==0} <div class='msg yellow'> {else} <div class='msg'> {/if}
		{/if}
			<a href='profile/{$m->user}'><img src='avatars_mini/{$m->get_avatar()}' class='avatar' alt='{$m->user}' /></a>
			{if $m->get_text_color()}
				<div class='when' style="color: {$m->get_text_color()}">{$m->how_long_ago()}<br />{$from} {$m->from}</div>
				<div class='msg_content' style="color: {$m->get_text_color()}">
			{else}
				<div class='when'>{$m->how_long_ago()}<br />{$from} {$m->from}</div>
				<div class="msg_content">
			{/if}
			<span class='name'><a href='profile/{$m->user}'>{$m->user}</a></span>: {$m->msg}
			{if $logged_user->username}
				<form method="post" action="direct_messages">
				<input type="hidden" name="dw" value="{$m->id}" />
				<input type="submit" value="{$label_delete}" name="delete" class="delete_msg" title="{$title_delete}" />
				</form>

				<form method="post" action="direct_message/{$m->user}">
				<input type="hidden" name="sender" value="{$m->user}" />
				<input type="submit" value="{$label_reply}" name="reply" class="reply" title="{$title_reply}" />
				</form>
			{/if}
			{if $m->is_favorite($logged_user->id)}
				<img src='grafika/heart_delete.png' class='fav_del' width='16' height='16' alt='{$user->id}_{$m->id}' title='{$title_fav_del}' />
			{else}
				<img src='grafika/heart_add.png' class='fav_add' width='16' height='16' alt='{$user->id}_{$m->id}' title='{$title_fav_add}' />
			{/if}
			</div>
		</div>
	{/foreach}
{else}
<p>{$no_msg}</p>
{/if}
</div>
<div id="dm_outbox">
{if $out_messages}
	{foreach from=$out_messages item=m key=k}
		{if $k%2==0} <div class='mini_msg gray'> {else} <div class='mini_msg'> {/if}
		<div class='when_nopic'>{$m->how_long_ago()}<br />{$from} {$m->from}</div>
		<a href="profile/{$m->get_user_name($m->direct)}"><strong>{$to} {$m->get_user_name($m->direct)}:</strong></a> {$m->parse_links()|nl2br}
		{if $m->reply} ({$in_reply_to} <a href="message/{$m->reply}">{$m->reply_get_username()}</a>){/if}
		{if $m->user == $logged_user->username}
			<form method="post" action="direct_messages">
			<input type="hidden" name="dw" value="{$m->id}" />
			<input type="submit" value="{$label_delete}" name="delete" class="delete_msg" title="{$title_delete}" />
			</form>
		{/if}
		{if $m->is_favorite($logged_user->id)}
			<img src='grafika/heart_delete.png' class='fav_del' width='16' height='16' alt='{$logged_user->id}_{$m->id}' title='{$title_fav_del}' />
		{else}
			<img src='grafika/heart_add.png' class='fav_add' width='16' height='16' alt='{$logged_userser->id}_{$m->id}' title='{$title_fav_add}' />
		{/if}
		</div>
	{/foreach}
{else}
<p>{$no_msg}</p>
{/if}
</div>
