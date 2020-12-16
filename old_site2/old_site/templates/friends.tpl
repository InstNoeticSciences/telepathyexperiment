{if $logged_user}
	<h1>{$user->count_friends()} {$friends_of1} {$user->username}</h1>
{else}
	<h1 class='header'>{$user->count_friends()} {$friends_of1} {$user->username}</h1>
{/if}
{$pages}
{foreach from=$friends item=i key=k}
	{if $k%2==0} <div class="person gray"> {else} <div class="person"> {/if}
		<a href="profile/{$i->username}"><img src="avatars_mini/{$i->avatar}" alt="{$i->username}" class="avatar show_msg_tooltip" /></a>
		<h2><a href="profile/{$i->username}">{$i->name} ({$i->username})</a></h2>
		{if $logged_user && $logged_user->id!=$i->id}
			<form action="friends/{$user->username}" method="post">
				<input type="hidden" name="friend_id" value="{$i->id}" />
				{if $logged_user->has_friend($i->id)}
					<input type="submit" name="remove_friend" value="{$a_remove} {$i->username}" class="follow_button" />
				{else}
					{if !$logged_user->i_am_blocked($i->id)}<input type="submit" name="follow" value="{$a_add} {$i->username}" class="follow_button" />{/if}
				{/if}
			</form>
		{/if}
	</div>
{/foreach}
{$pages}
