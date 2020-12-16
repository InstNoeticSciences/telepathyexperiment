{if !$logged_user}
<h1 class='header'>{$main_header}</h1>
{else}
<h1>{$main_header}</h1>
{/if}
<div id="messages_all">
{if $messages}
	{foreach from=$messages item=m key=k}
		{if $k%2==0}<div class='msg yellow'>{else}<div class='msg'>{/if}
		{$m->post_image()}
		<a href='profile/{$m->user}'><img src='avatars_mini/{$m->get_avatar()}' class='avatar' alt='{$m->user}' /></a>
		{if $m->get_text_color()}
			<div class='when'>{$m->how_long_ago()} {$from} {$m->from}</div>
			<div class='msg_content'>
			<a href='profile/{$m->user}' class='username'>{$m->user}</a>
		{else}
			<div class='when'>{$m->how_long_ago()} {$from} {$m->from}</div>
			<div class="msg_content">
			<a href='profile/{$m->user}' class='username'>{$m->user}</a>
		{/if}
{if $m->group_id>0 } @<a href="groups/profile/{$m->group_furl}">{$m->group_title}</a>{else}:{/if}<br />

				{if $m->reply} {$in_reply_to}<a href="message/{$m->reply}">{$m->reply_get_username()}</a>: {/if}
				{$m->parse_links()}
			</div>
		<div class="msg_controls">
			{if $m->user == $logged_user->username || $logged_user->username == "admin"}
				<form method="post" action="">
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
{/if}
</div>
{if $page_count != 1}
	<p class="mid">{$pagination_page} {$page_num} / {$page_count}</p>
	<p class="mid">
	{if $page_num!=1}
		<a href="home/{$prev}" class="pagination">{$pagination_prev}</a>
	{/if}
	{foreach from=$page_numbers item=i key=k}
		{if $i==$page_num}
			{$i}
		{else}
			{if $i < $page_num - $treshold || $i > $page_num + $treshold}
				{if $i != $page_num}
					{if $dots==0}  ... {assign var='dots' value=1} {/if}
				{/if}
			{else}
				<a href="home/{$i}" class="pagination">{$i}</a>
				{assign var='dots' value=0}
			{/if}
		{/if}
	{/foreach}
	{if $page_num < $page_count}
		<a href="home/{$next}" class="pagination">{$pagination_next}</a>
	{/if}
	</p>
{/if}
<p class="mid"><a href="rss/main_timeline.php">RSS feed</a></p>