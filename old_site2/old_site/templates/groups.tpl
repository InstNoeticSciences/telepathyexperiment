{if !$logged_user}<br /><br />{/if}

{if $group_id}

	{if $group_data->group_id}

	<h1><a href="groups/">{$groups_label}</a>: 
	{if sizeof($g_parent_data)}
	<a href="groups/list/{$g_parent_data->group_furl}">{$g_parent_data->group_title}</a>:
	{/if}
	{if ($groups_tree==2)}
		<a href="groups/list/{$group_data->group_furl}">
	{elseif ($groups_tree==3)}
		<a href="groups/profile/{$group_data->group_furl}">
 	{/if}
	{$group_data->group_title}</a> 
	{if ($groups_tree==3)}&nbsp;<a href="rss/main_timeline.php?group={$group_data->group_furl}" class="rss_feed"><img src="grafika/feed.png" height="16" width="16" alt="0"/></a>{/if}
	</h1>

	<table>
	<tr valign="top">
	<td width="70px"><a href='avatars_group/{$group_data->group_id}_{$group_data->group_image}' class='thickbox' title='{$group_data->group_title}'>
	<img src='avatars_group/{$group_data->group_id}_60{$group_data->group_image}' alt='{$group_data->group_title}' /></a>
	</td>
	<td>{$group_data->group_descr}
	{if sizeof($group_data->group_tags)}
	<br /><br />
	{foreach from=$group_data->group_tags item=i name=iii}
		<a href="groups/search/{$i}">{$i}</a>{if !$smarty.foreach.iii.last}, {/if}
	{/foreach}
	{/if}
	</td>
	</tr>
	</table>

	{else}
	<h1><a href="groups/">{$group_label}</a>:</h1>
	{/if}

	<br />

	{if sizeof($group_members_list) && $group_data->level_id>1}
	{$group_members}:
	{foreach from=$group_members_list item=i name=ii}
		<a href="profile/{$i->username}">{$i->username}</a>{if !$smarty.foreach.ii.last}, {/if}
	{/foreach}
	{/if}

	<p>
	{if $group_data->level_id>1}
	{if sizeof($logged_user->groups)}
		{if in_array($group_data->group_id, $logged_user->groups)}
			<a href="groups/unjoin/{$group_data->group_furl}/{$logged_user->id}" OnClick="return confirm('{$you_are_sure}')">{$group_leave_label}</a>
		{else}
			<a href="groups/join/{$group_data->group_furl}/{$logged_user->id}">{$group_join_label}: {$group_data->group_title}</a>
		{/if}
	{else}
		<a href="groups/join/{$group_data->group_furl}/{$logged_user->id}">{$group_join_label}: {$group_data->group_title}</a>
	{/if}
	{/if}
	</p>

	{if $logged_user && $group_data->level_id>1}
		{include file="groups_profile.tpl"}
	{/if}

{*
	<div id="profile_header">
	<h1>{$message_for_group}: {$group_data->group_title}</h1>
	<form method="post" action="profile/{$logged_user->username}" enctype="multipart/form-data" >
	<img src='avatars_mini/{$logged_user->avatar}' class='avatar' alt='{$logged_user->username}' />
	<textarea id="message" name="message" cols="30" rows="3"></textarea><br />
	<label for="add_photo">{$upload_picture}</label><input type="file" name="add_photo" id="add_photo" />
	<input type="hidden" name="user" value="{$logged_user->username}" />
	<input type="hidden" name="user_from_group_page" value="1" />
	<div class='mid'>
	<span id='chars_left'>140</span> {$chars_left}<br />
	<input type="submit" name="add_message" value="{$label_send}" class="submit" />
	</div>
	</form>
	</div>
*}

{/if}

	{if ($groups_tree==1)}

	<h1><a href="groups/">{$groups_label}</a>:</h1>
	<table>
	{foreach from=$list_groups item=i}
	<tr bgcolor="#eeeeee" valign="top">
	<td width="30px" height="30px">{if $i->group_image}<a class="thickbox" href="avatars_group/{$i->group_id}_{$i->group_image}"><img src="avatars_group/{$i->group_id}_25{$i->group_image}">{/if}</td>
	<td colspan="2"><a href="groups/list/{$i->group_furl}">{$i->group_title}</a></td>
	</tr>
	{/foreach}
	</table>

	{else} {* LIST *}

	{if sizeof($group_subgroups)}
	<table>
	{foreach from=$group_subgroups item=i}
		<tr valign="top">
		<td width="30" height="30">{if $i->group_image}<a class="thickbox" href="avatars_group/{$i->group_id}_{$i->group_image}"><img src="avatars_group/{$i->group_id}_25{$i->group_image}">{/if}</td>
		<td colspan="2"><a href="groups/profile/{$i->group_furl}">{$i->group_title}</a></td>
		</tr>
	{/foreach}
	</table>
	{/if}


	{/if}

{if $group_search_flag}



<div id="profile_header">

{$pages}

<form action="groups/search" method="post">
<table cellpadding="0" cellspacing="0">
<tr><td>
	<label for="search_title">Search by Title</label><input type="text" id="search_title" name="search_title" value="{$search_title}" /><br/>
</td></tr>
<tr><td>
	<label for="search_descr">Search by Description</label><input type="text" id="search_descr" name="search_descr" value="{$search_descr}" /><br/>
</td></tr>
<tr><td>
	<label for="search_tags">Search by Tags</label><input type="text" id="search_tags" name="search_tags" value="{$search_tags}" /><br/>
</td></tr>
<tr><td>
	<label for="search_group">&nbsp;</label><input type="submit" class="submit" name="search_group" id="search" value="{$label_search}"/>
</td></tr></table>
</form>
</div>

<br />
{if sizeof($found_groups)}
{foreach from=$found_groups item=i key=k}
	<div class="person">
	<a href="groups/{if $i->level_id==1}list{else}profile{/if}/{$i->group_furl}"><img src="avatars_group/{$i->group_id}_60{$i->group_image}" alt="{$i->group_title}"  class="avatar" /></a>
	<h2><a href="groups/{if $i->level_id==1}list{else}profile{/if}/{$i->group_furl}">{$i->group_title}</a></h2>
	</div>
{/foreach}
{/if}

{$pages}

{/if}
