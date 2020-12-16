{if $logged_user}
	<h1>{$results_for} '{$search_word}' {$num_of_results}</h1>
{else}
	<h1 class='header'>{$results_for} '{$search_word}' {$num_of_results}</h1>
{/if}
{$pages}

{if sizeof($found_users)}
{foreach from=$found_users item=i key=k}
	{if $k%2==0} <div class="person gray"> {else} <div class="person"> {/if}
		<a href="profile/{$i->username}"><img src="avatars_mini/{$i->avatar}" alt="{$i->username}" class="avatar show_msg_tooltip" /></a>
		<h2><a href="profile/{$i->username}">{$i->name} ({$i->username})</a></h2>
		{if $logged_user && $logged_user->id!=$i->id}
			<form action="search" method="post">
				<input type="hidden" name="friend_id" value="{$i->id}" />
				<input type="hidden" name="search_words" value="{$smarty.post.search_words}" />
				{if $logged_user->has_friend($i->id)}
					<input type="submit" name="remove_friend" value="{$a_remove} {$i->username}" class="follow_button" />
				{else}
					{if !$logged_user->i_am_blocked($i->id)}<input type="submit" name="follow" value="{$a_add} {$i->username}" class="follow_button" />{/if}
				{/if}
			</form>
		{/if}
	</div>
{/foreach}
{/if}


{if $group_search_flag}

<div id="profile_header">
<form action="search" method="post">
	<label for="search_title">Search by Title</label><input type="text" id="search_title" name="search_title" value="{$search_title}" /><br/>
	<label for="search_descr">Search by Description</label><input type="text" id="search_descr" name="search_descr" value="{$search_descr}" /><br/>
	<label for="search_tags">Search by Tags</label><input type="text" id="search_tags" name="search_tags" value="{$search_tags}" /><br/>
	<label for="search_group">&nbsp;</label><input type="submit" class="submit" name="search_group" id="search" value="{$label_search}"/>
</form>
</div>

<br />
{if sizeof($found_groups)}
{foreach from=$found_groups item=i key=k}
	{if $k%2==0} <div class="person gray"> {else} <div class="person"> {/if}
		<a href="groups/{$i->group_id}"><img src="avatars_group/{$i->group_id}_{$i->group_image}" alt="{$i->group_title}" class="avatar show_msg_tooltip" /></a>
		<h2><a href="groups/{$i->group_id}">{$i->group_title}</a></h2>
		{$i->group_descr}
	</div>
	<br />
{/foreach}
{/if}

{/if}


{$pages}
