{if !$logged_user}
{else}
    <p class="tabs">
    <input type="hidden" name="current_tab" id="current_tab" value="{$tab}" />
    <input type="hidden" name="current_user" id="current_user" value="{$smarty.session.user_id}" />
    {if $user->username == $logged_user->username}
    	{if $profile_file=="profile_customize.tpl"}
    	<a href="profile/{$user->username}/customize" class="profile current rlink" id="profile_customize" rel="{$smarty.session.user_id}">{$profile_tab_customize}</a>
    	{else}
    	<a href="profile/{$user->username}/customize" class="profile rlink" id="profile_customize" rel="{$smarty.session.user_id}">{$profile_tab_customize}</a>
    	{/if}
    {/if}
    {if $profile_file=="messages_previous.tpl"}
    <a href="profile/{$user->username}/previous" class="profile current" id="profile_my_msg" rel="{$smarty.session.user_id}___1___{$logged_user->username}___{$timestamp}">{$my_results}</a>
    {else}
    <a href="profile/{$user->username}/previous" class="profile" id="profile_my_msg" rel="{$smarty.session.user_id}___1___{$logged_user->username}___{$timestamp}___{$timestamp}">{$my_results}</a>
    {/if}
    {if $profile_file=="messages_with_friends.tpl"}
    <a href="profile/{$user->username}/with_friends" class="profile current" id="profile_friends" rel="{$smarty.session.user_id}___1___{$logged_user->username}">{$all_results}</a>
    {else}
    <a href="profile/{$user->username}/with_friends" class="profile" id="profile_friends" rel="{$smarty.session.user_id}___1___{$logged_user->username}">{$all_results}</a>
    {/if}
    </p>
    <div id="tab_content">
    </div>
{/if}