{if !$logged_user}
<h1 class='header'>{$user->username}{$users_profile}</h1>
{else}
<h1>{$user->username}{$users_profile}</h1>
{/if}

{if $logged_user->username==$user->username}
	<div id="profile_header">
	<h1>{$what_are_you_doing}</h1>
	<form method="post" action="profile/{$user->username}" enctype="multipart/form-data"  >
	<img src='avatars_mini/{$user->avatar}' class='avatar' alt='{$user->username}' />
	<textarea id="message" name="message" cols="30" rows="3"></textarea><br />
	<label for="add_photo">{$upload_picture}</label><input type="file" name="add_photo" id="add_photo" />
	<input type="hidden" name="user" value="{$logged_user->username}" />
	<div class='mid'>
	<span id='chars_left'>140</span> {$chars_left}<br />
	<input type="submit" name="add_message" value="{$label_send}" class="submit" />
	</div>
	</form>
	</div>
{else}
	{if $user->visible}
	<div id="profile_header">
		<img src='avatars_mini/{$user->avatar}' class='avatar' alt='{$user->username}' />
		{if $first_msg}
			<p>{$user->username}:<br />{$first_msg->msg}</p>
			<div class='when'>{$first_msg->how_long_ago()} {$from} {$first_msg->from}</div>
		{else}
			<p>{$no_msg}</p>
		{/if}
			<div class="clear">&nbsp;</div>
	</div>
	{/if}
{/if}
<p class="tabs">
<input type="hidden" name="current_tab" id="current_tab" value="{$tab}" />
<input type="hidden" name="current_user" id="current_user" value="{$user->id}" />
{if $user->username == $logged_user->username}
	{if $profile_file=="profile_customize.tpl"}
	<a href="profile/{$user->username}/customize" class="profile current rlink" id="profile_customize" rel="{$user->id}">{$profile_tab_customize}</a>
	{else}
	<a href="profile/{$user->username}/customize" class="profile rlink" id="profile_customize" rel="{$user->id}">{$profile_tab_customize}</a>
	{/if}
{/if}
{if $profile_file=="messages_previous.tpl"}
<a href="profile/{$user->username}/previous" class="profile current" id="profile_my_msg" rel="{$user->id}___1___{$logged_user->username}___{$timestamp}">{$profile_tab_mine}</a>
{else}
<a href="profile/{$user->username}/previous" class="profile" id="profile_my_msg" rel="{$user->id}___1___{$logged_user->username}___{$timestamp}___{$timestamp}">{$profile_tab_mine}</a>
{/if}
{if $profile_file=="messages_with_friends.tpl"}
<a href="profile/{$user->username}/with_friends" class="profile current" id="profile_friends" rel="{$user->id}___1___{$logged_user->username}">{$profile_tab_with_friends}</a>
{else}
<a href="profile/{$user->username}/with_friends" class="profile" id="profile_friends" rel="{$user->id}___1___{$logged_user->username}">{$profile_tab_with_friends}</a>
{/if}
{if $profile_file=="messages_replys.tpl"}
<a href="profile/{$user->username}/replys" class="profile current" id="profile_replys" rel="{$user->id}___1___{$logged_user->username}">{$profile_tab_replys}</a>
{else}
<a href="profile/{$user->username}/replys" class="profile" id="profile_replys" rel="{$user->id}___1___{$logged_user->username}">{$profile_tab_replys}</a>
{/if}
</p>
<div id="tab_content"></div>
