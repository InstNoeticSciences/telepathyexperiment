{if $page!="profile.tpl" && $page!="groups.tpl" && $page!="followers.tpl" && $page!="friends.tpl" && $logged_user && $page!="home.tpl" && $page!="tag.tpl" && $page!="favorites.tpl"}

	<div class="side_top"><div class="decorative_bar">&nbsp;</div></div>

	<div class="side_middle"><div class="side_stuff">

		<h1>{$hello} {$logged_user->username}!</h1>

		<p><img src="avatars_mini/{$logged_user->avatar}" alt="{$logged_user->username}" class="avatar show_msg_tooltip" /></p>

		<div class='clear'>&nbsp;</div>

		<p>

			<strong>{$u_fullname}</strong> {$logged_user->name}<br />

			<strong>{$u_age}</strong> {$logged_user->display_age()}<br />

			<strong>{$u_location}</strong> {$logged_user->location}<br />

			<strong>{$u_bio}</strong> {$logged_user->bio}<br />

			<strong>{$u_interests}</strong> {$logged_user->interests_tags}<br />

			<strong>{$u_www}</strong> <a href="{$logged_user->www}" target="_blank">{$logged_user->www}</a>

		</p>



		{if $user->username != "admin" || $logged_user->username == "admin"}

			<p class="user_stats">

			<a href="direct_messages" class='link_direct_msg'>{$logged_user->count_direct_messages()} {$qty_direct_msg}</a><br />

			<a href="followers/{$logged_user->username}" class='link_followers'>{$logged_user->count_followers()} {$qty_followers}</a><br />

			<a href="friends/{$logged_user->username}" class='link_friends'>{$logged_user->count_friends()} {$qty_friends}</a><br />

			<a href="favorites/{$logged_user->username}" class='link_favorites'>{$logged_user->count_favorites()} {$qty_favorites}</a><br />

			{if ($user->username == $logged_user->username && $user) || ($logged_user->username && !$user)}

			<a href="sms_credits" class='link_sms_credits'>{$logged_user->sms_credits} {$qty_sms_credits}</a><br />

			{/if}

			{$logged_user->count_updates()} {$qty_updates}

			</p>

		{/if}

		<p><strong>{$new_msg_notify}</strong><br />

		{if $logged_user->notify_direct == "0"}

			<input type="radio" id="notify_direct1" name="notify_direct" value="0_{$logged_user->id}" checked="checked" />{$notify_any_type}<br />

			<input type="radio" id="notify_direct2" name="notify_direct" value="1_{$logged_user->id}" /> {$notify_direct_only}

		{else}

			<input type="radio" id="notify_direct1" name="notify_direct" value="0_{$logged_user->id}" /> {$notify_any_type} <br />

			<input type="radio" id="notify_direct2" name="notify_direct" value="1_{$logged_user->id}" checked="checked" /> {$notify_direct_only}

		{/if}<br />

		<span id="mnr"></span></p>

		<p><strong>{$notify_by}</strong><br />

		{if $logged_user->notify_way == "im" && $logged_user->im_id}

			<input type="radio" id="notify_mode1" name="notify_mode" value="im_{$logged_user->id}" checked="checked" /> {$n_im} <br />

			<input type="radio" id="notify_mode2" name="notify_mode" value="email_{$logged_user->id}" /> {$n_email}<br />

			<input type="radio" id="notify_mode3" name="notify_mode" value="web_{$logged_user->id}" /> {$n_web}<br />

			<input type="radio" id="notify_mode4" name="notify_mode" value="sms_{$logged_user->id}" /> {$n_sms}

		{elseif $logged_user->notify_way == "email"}

			{if $logged_user->im_id}

				<input type="radio" id="notify_mode1" name="notify_mode" value="im_{$logged_user->id}" /> {$n_im} <br />

			{else}

				<a href="settings/my_im">{$activate_im}</a><br />

			{/if}

			<input type="radio" id="notify_mode2" name="notify_mode" value="email_{$logged_user->id}" checked="checked" /> {$n_email}<br />

			<input type="radio" id="notify_mode3" name="notify_mode" value="web_{$logged_user->id}" /> {$n_web}<br />

			<input type="radio" id="notify_mode4" name="notify_mode" value="sms_{$logged_user->id}" /> {$n_sms}

		{elseif $logged_user->notify_way == "sms"}

			{if $logged_user->im_id}

				<input type="radio" id="notify_mode1" name="notify_mode" value="im_{$logged_user->id}" /> {$n_im} <br />

			{else}

				<a href="settings/my_im">{$activate_im}</a><br />

			{/if}

			<input type="radio" id="notify_mode2" name="notify_mode" value="email_{$logged_user->id}" /> {$n_email}<br />

			<input type="radio" id="notify_mode3" name="notify_mode" value="web_{$logged_user->id}" /> {$n_web}<br />

			<input type="radio" id="notify_mode4" name="notify_mode" value="sms_{$logged_user->id}" checked="checked" /> {$n_sms}

		{else}

			{if $logged_user->im_id}

				<input type="radio" id="notify_mode1" name="notify_mode" value="im_{$logged_user->id}" /> {$n_im} <br />

			{else}

				<a href="settings/my_im">{$activate_im}</a><br />

			{/if}

			<input type="radio" id="notify_mode2" name="notify_mode" value="email_{$logged_user->id}" /> {$n_email}<br />

			<input type="radio" id="notify_mode3" name="notify_mode" value="web_{$logged_user->id}" checked="checked" /> {$n_web}<br />

			<input type="radio" id="notify_mode4" name="notify_mode" value="sms_{$logged_user->id}" /> {$n_sms}

		{/if}<br />

		<span id="ncmr"></span></p>

		<p><a href="rss/user_updates.php?user={$logged_user->username}" class="rss_feed">RSS feed</a></p>

	</div></div>

 <div class="side_bottom"><div class="decorative_bar">&nbsp;</div></div>

{/if}





{if $page=="profile.tpl" || $page=="followers.tpl" || $page=="friends.tpl" || $page=="tag.tpl" || $page=="favorites.tpl"}

 <div class="side_top"><div class="decorative_bar">&nbsp;</div></div>

 	<div class="side_middle"><div class="side_stuff">

		{if $user->username == $logged_user->username}

			<h1>{$hello} {$user->username}!</h1>

			<p><img src="avatars_mini/{$logged_user->avatar}" alt="{$logged_user->username}" class="avatar show_msg_tooltip" /></p>

			<div class='clear'>&nbsp;</div>

			<p>

			<strong>{$u_fullname}</strong> {$logged_user->name}<br />

			<strong>{$u_age}</strong> {$logged_user->display_age()}<br />

			<strong>{$u_location}</strong> {$logged_user->location}<br />

			<strong>{$u_bio}</strong> {$logged_user->bio}<br />

			<strong>{$u_interests}</strong> {$logged_user->interests_tags}<br />

			<strong>{$u_www}</strong> <a href="{$logged_user->www}" target="_blank">{$logged_user->www}</a>

			</p>

		{else}

			<h1>{$u_about} {$user->username}</h1>

			<p><img src="avatars_mini/{$user->avatar}" alt="{$i->username}" class="avatar show_msg_tooltip" /></p>

			<div class='clear'>&nbsp;</div>

			<p><a href='{$base_href}{$user->username}'>{$base_href}{$user->username}</a></p>

			<p>

			<strong>{$u_fullname}</strong> {$user->name}<br />

			<strong>{$u_age}</strong> {$user->display_age()}<br />

			<strong>{$u_location}</strong> {$user->location}<br />

			<strong>{$u_bio}</strong> {$user->bio}<br />

			<strong>{$u_interests}</strong> {$user->interests_tags}<br />

			<strong>{$u_www}</strong> <a href="{$user->www}" target="_blank">{$user->www}</a>

			</p>

		{/if}

		{if $user->username != "admin"}

			<p class="user_stats">

			{if $user->username == $logged_user->username}

				<a href="direct_messages" class='link_direct_msg'>{$user->count_direct_messages()} {$qty_direct_msg}</a><br />

			{/if}

			<a href="followers/{$user->username}" class='link_followers'>{$user->count_followers()} {$qty_followers}</a><br />

			<a href="friends/{$user->username}" class='link_friends'>{$user->count_friends()} {$qty_friends}</a><br />

			<a href="favorites/{$user->username}" class='link_favorites'>{$user->count_favorites()} {$qty_favorites}</a><br />

			{if $user->username == $logged_user->username}

			<a href="sms_credits" class='link_sms_credits'>{$user->sms_credits} {$qty_sms_credits}</a><br />

			{/if}

			{$user->count_updates()} {$qty_updates}

			</p>

		{/if}

		{if $user->username != $logged_user->username && $logged_user}

		<p><strong>{$actions}</strong>:<br />

			<strong><a href="direct_message/{$user->username}">{$a_send_msg} {$user->username} </a></strong><br />

			{if $user->username != "admin"}<strong><a href="profile/{$user->username}/nudge">{$a_nudge} {$user->username}</a></strong><br />{/if}

			{if $logged_user->has_friend($user->id)}

				{if $logged_user->has_friend_nf($user->id)}

					<strong><a href="profile/{$user->username}/follow">{$a_follow} {$user->username}</a></strong><br />

				{else}

					<strong><a href="profile/{$user->username}/leave">{$a_leave} {$user->username}</a></strong><br />

				{/if}



				<strong><a href="profile/{$user->username}/remove">{$a_remove} {$user->username}</a></strong><br />

			{else}

				{if !$logged_user->i_am_blocked($user->id)} <strong><a href="profile/{$user->username}/follow">{$a_add} {$user->username} {$a_as_friend}</a></strong><br /> {/if}

			{/if}

			{if $user->username != "admin"}

				{if $logged_user->is_blocked($user->id)}

					<strong><a href="profile/{$user->username}/unblock">{$unblock} {$user->username}</a></strong><br />

				{else}

					<strong><a href="profile/{$user->username}/block" class='block_user'>{$a_block} {$user->username}</a></strong><br />

				{/if}

			{/if}

		</p>

		{else}

			{if $user->username == $logged_user->username}

				<p><strong>{$new_msg_notify}</strong><br />

				{if $logged_user->notify_direct == "0"}

					<input type="radio" id="notify_direct1" name="notify_direct" value="0_{$logged_user->id}" checked="checked" /> {$notify_any_type} <br />

					<input type="radio" id="notify_direct2" name="notify_direct" value="1_{$logged_user->id}" /> {$notify_direct_only}

				{else}

					<input type="radio" id="notify_direct1" name="notify_direct" value="0_{$logged_user->id}" /> {$notify_any_type} <br />

					<input type="radio" id="notify_direct2" name="notify_direct" value="1_{$logged_user->id}" checked="checked" /> {$notify_direct_only}

				{/if}<br />

				<span id="mnr"></span></p>



				<p><strong>{$notify_by}</strong><br />

				{if $logged_user->notify_way == "im"}

					<input type="radio" id="notify_mode1" name="notify_mode" value="im_{$logged_user->id}" checked="checked" /> {$n_im} <br />

					<input type="radio" id="notify_mode2" name="notify_mode" value="email_{$logged_user->id}" /> {$n_email}<br />

					<input type="radio" id="notify_mode3" name="notify_mode" value="web_{$logged_user->id}" /> {$n_web}<br />

					<input type="radio" id="notify_mode4" name="notify_mode" value="sms_{$logged_user->id}" /> {$n_sms}

				{elseif $logged_user->notify_way == "email"}

					{if $logged_user->im_id}

						<input type="radio" id="notify_mode1" name="notify_mode" value="im_{$logged_user->id}" /> {$n_im} <br />

					{else}

						<a href="settings/my_im">{$activate_im}</a><br />

					{/if}

					<input type="radio" id="notify_mode2" name="notify_mode" value="email_{$logged_user->id}" checked="checked" /> {$n_email}<br />

					<input type="radio" id="notify_mode3" name="notify_mode" value="web_{$logged_user->id}" /> {$n_web}<br />

					<input type="radio" id="notify_mode4" name="notify_mode" value="sms_{$logged_user->id}" /> {$n_sms}

				{elseif $logged_user->notify_way == "sms"}

					{if $logged_user->im_id}

						<input type="radio" id="notify_mode1" name="notify_mode" value="im_{$logged_user->id}" /> {$n_im} <br />

					{else}

						<a href="settings/my_im">{$activate_im}</a><br />

					{/if}

					<input type="radio" id="notify_mode2" name="notify_mode" value="email_{$logged_user->id}" /> {$n_email}<br />

					<input type="radio" id="notify_mode3" name="notify_mode" value="web_{$logged_user->id}" /> {$n_web}<br />

					<input type="radio" id="notify_mode4" name="notify_mode" value="sms_{$logged_user->id}" checked="checked" /> {$n_sms}

				{else}

					{if $logged_user->im_id}

						<input type="radio" id="notify_mode1" name="notify_mode" value="im_{$logged_user->id}" /> {$n_im} <br />

					{else}

						<a href="settings/my_im">{$activate_im}</a><br />

					{/if}

					<input type="radio" id="notify_mode2" name="notify_mode" value="email_{$logged_user->id}" /> {$n_email}<br />

					<input type="radio" id="notify_mode3" name="notify_mode" value="web_{$logged_user->id}" checked="checked" /> {$n_web}<br />

					<input type="radio" id="notify_mode4" name="notify_mode" value="sms_{$logged_user->id}" /> {$n_sms}

				{/if}<br />

				<span id="ncmr"></span></p>

			{/if}

		{/if}

		{if $nudge_ok}<p>{$nudge_ok}</p>{/if}

		{if $leave_ok}<p>{$leave_ok}</p>{/if}

		{if $remove_ok}<p>{$remove_ok}</p>{/if}

		{if $follow_ok}<p>{$follow_ok}</p>{/if}

		{if $block_ok}<p>{$block_ok}</p>{/if}

		{if $unblock_ok}<p>{$unblock_ok}</p>{/if}

		<p><a href="rss/user_updates.php?user={$user->username}" class="rss_feed">RSS feed</a></p>

	</div></div>

 <div class="side_bottom"><div class="decorative_bar">&nbsp;</div></div>

	{if $user->username != "admin"}

 <div class="side_top"><div class="decorative_bar">&nbsp;</div></div>

 <div class="side_middle"><div class="side_stuff">

		<h1>{$friends1} {$user->username} {$friends2}</h1>

		{foreach from=$friends item=i key=k}{if $k<50}<a href="profile/{$i->username}"><img class="mini_friend show_msg_tooltip" src="avatars25/{$i->avatar}" alt="{$i->username}" /></a>{/if}{/foreach}

		{if $k >= 50}<div class="clear">&nbsp;</div><a href="friends/{$user->username}">{$all_friends}</a>{/if}

		<div class="clear">&nbsp;</div>

	</div></div>

 <div class="side_bottom"><div class="decorative_bar">&nbsp;</div></div>

	{/if}

{/if}

{if !$logged_user}

<input type="hidden" id="show_register_form" value="{$reg}" />

	<div class="side_top"><div class="decorative_bar">&nbsp;</div></div>

 	<div class="side_middle"><div class="side_stuff">

		<h1><a href="#" id="link_register">{$link_register}</a><a href="#" id="link_login">{$link_login}</a></h1>

		<div id="login_stuff">

			<form action="home" method="post">

			<label for="user">{$label_uname_email}</label><input type="text" name="user" id="user" class="wpisz_cos" /><br />

			<label for="pass">{$label_password}</label><input type="password" name="pass" id="pass" class="wpisz_cos" /><br />

			<p><input type="checkbox" name="remember_me" id="remember_me" value="1" class="chk" /> {$label_remember}</p>

			<div class='clear'>&nbsp;</div>

			<input type="submit" name="login" value="{$label_login}" id="login_button" class="log_reg" />

			<a class="thickbox" href="{$RPX_API_URL}openid/embed?token_url={$RPX_TOKEN_URL}" style="text-decoration:none; padding-left:54px;"><input type="button" value="Sign In with OpenID" class="log_reg" /></a>



			{if $login_error} <p class="error">{$login_error}</p>{/if}

			<p class="right"></p>

			<div class='clear'>&nbsp;</div>

			<p class="mid"><a href="forgot_password">{$forgot_header}</a></p>

<script type="text/javascript"

   src="https://rpxnow.com/openid/v2/widget"></script>

<script type="text/javascript">

  RPXNOW.overlay = true;

  RPXNOW.language_preference = 'en';

</script>



			</form>



		</div>

		<div id="register_stuff">

			<form method="post" action="">

			<label for="username">{$label_your_uname}</label><input type="text" name="username" id="username" class="wpisz_cos" value="{$smarty.post.username}" /><br />

			{if $error_username} <p class="error">{$error_username}</p>{/if}

			<label for="email">{$label_email}</label><input type="text" name="email" id="email" class="wpisz_cos" value="{$smarty.post.email}" /><br />

			{if $error_email} <p class="error">{$error_email}</p>{/if}

			<label for="pass1">{$label_password}</label><input type="password" name="pass1" id="pass1" class="wpisz_cos" value="{$smarty.post.pass1}" /><br />

			<label for="pass2">{$label_repeat_pass}</label><input type="password" name="pass2" id="pass2" class="wpisz_cos" value="{$smarty.post.pass2}" /><br />

			{if $error_pass} <p class="error">{$error_pass}</p>{/if}

			<p class="label"><input type="checkbox" name="visible" id="visible" class="chk" checked="checked" />{$visibility}</p>

			<p class="label"><input type="checkbox" name="accept_terms" id="accept_terms" class="chk" />{$i_accept} <a href="terms_and_conditions" target="_blank">{$bm_terms}</a>{$i_accept2}</p>

			{if $error_terms} <p class="error">{$error_terms}</p>{/if}

			<input type="hidden" name="code1" value={$code} />

			<label>{$label_captcha1}</label><img src='randompic.php?napis={$code}' width='183' height='50' alt='' id="sec_code" /><br />

			<label for="code2">{$label_captcha2}</label><input type="text" name="code2" id="code2" class="wpisz_cos" /><br />

			{if $error_code} <p class="error">{$error_code}</p>{/if}

			<p class="right"><input type="submit" name="create_account" value="{$label_create_account}" class="log_reg" /></p>

			<div class="clear">&nbsp;</div>

			</form>



			{if $error} <p class="error">{$error}</p>{/if}

		</div>

	</div></div>

 <div class="side_bottom"><div class="decorative_bar">&nbsp;</div></div>

{/if}

{if $page=="home.tpl"}

	<div class="side_top"><div class="decorative_bar">&nbsp;</div></div>

 	<div class="side_middle"><div class="side_stuff">

		<h1><a href="#" id="link_popular">{$tab_popular}</a><a href="#" id="link_recent">{$tab_recent}</a></h1>

		<div id="most_popular_stuff"></div>

		<div id="most_recent_stuff"></div>

	</div></div>

	<div class="side_bottom"><div class="decorative_bar">&nbsp;</div></div>



	<div class="side_top"><div class="decorative_bar">&nbsp;</div></div>

	<div class="side_middle"><div class="side_stuff">

		<h1>{$groups_label}:</h1>

		<h1><a href="#" id="link_popular_groups">{$tab_popular}</a>

	<a href="#" id="link_new_groups">{$tab_most_new}</a></h1>

		<div id="most_popular_groups_stuff"></div>

		<div id="most_new_groups_stuff"></div>

	<br />

	<a href="groups/">{$groups_all_label}</a>

	</div></div>

	<div class="side_bottom"><div class="decorative_bar">&nbsp;</div></div>

{/if}



{if $page=="groups.tpl"}

	<div class="side_top"><div class="decorative_bar">&nbsp;</div></div>

 	<div class="side_middle"><div class="side_stuff">

		<h1>{$groups_label}:</h1>

		<h1><a href="#" id="link_popular_groups">{$tab_popular}</a>

	<a href="#" id="link_new_groups">{$tab_most_new}</a></h1>

		<div id="most_popular_groups_stuff"></div>

		<div id="most_new_groups_stuff"></div>

	<br />

	<a href="groups/">{$groups_all_label}</a>

	</div></div>

	<div class="side_bottom"><div class="decorative_bar">&nbsp;</div></div>



{if $group_stat}



	<div class="side_top"><div class="decorative_bar">&nbsp;</div></div>

 	<div class="side_middle"><div class="side_stuff">

	<h1>{$group_menu}:</h1>

	<div>

	<b>{$group_stat.users}</b> {$members}<br />

	<b>{$group_stat.mes}</b> {$qty_updates}<br />

	Est. <b>{$group_data->created_n}</b><br />

	</div>

	</div></div>

	<div class="side_bottom"><div class="decorative_bar">&nbsp;</div></div>


        <div class="side_top"><div class="decorative_bar">&nbsp;</div></div>

	<div class="side_middle"><div class="side_stuff">

		<h1><a href="#" id="all_group_members" class="current">{$group_members}</a>
		<a href="#" id="new_group_members">{$tab_most_new}</a></h1>
		<div id="group_members"></div>

		<div id="all_group_members_stuff"></div>
		<div id="new_group_members_stuff"></div>
	</div></div>

	<script type="text/javascript">
	<!--//--><![CDATA[//><!--
	//load_group_members({$group_data->group_id});
	//--><!]]>
	</script>
        <div class="side_bottom"><div class="decorative_bar">&nbsp;</div></div>

{/if}


{/if}

