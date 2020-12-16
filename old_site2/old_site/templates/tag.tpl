{if $user->username == $logged_user->username}
	<h1>{$people_interested_in} {$tag}</h1>
{else}
	<h1 class="header">{$people_interested_in} {$tag}</h1>
{/if}


{foreach from=$people item=i key=k}
	{if $k%2==0} <div class="person gray"> {else} <div class="person"> {/if}
		<a href="profile/{$i->username}"><img src="avatars_mini/{$i->avatar}" alt="{$i->username}" class="avatar" /></a>
		<h2><a href="profile/{$i->username}">{$i->name} ({$i->username})</a></h2>
		{if $logged_user && !$logged_user->has_friend($i->id) && $logged_user->id!=$i->id}
			<form action="" method="post">
				<input type="hidden" name="friend_id" value="{$i->id}" />
				{if $logged_user->has_friend($i->id)}
					{if $logged_user->has_friend_nf($i->id)}
						<input type="submit" name="start_following" value="{$a_follow} {$i->username}" class="follow_button" />
					{else}
						<input type="submit" name="stop_following" value="{$a_leave} {$i->username}" class="follow_button" />
					{/if}
					<input type="submit" name="remove_friend" value="{$a_remove} {$i->username}" class="follow_button" />
				{else}
					{if !$logged_user->i_am_blocked($i->id)}<input type="submit" name="follow" value="{$a_add} {$i->username}" class="follow_button" />{/if}
				{/if}
			</form>
		{/if}
	</div>
{/foreach}