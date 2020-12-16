	<div id="profile_header">
	<h1>{$message_for_group}: {$group_data->group_title}:</h1>
	<form method="post" action="profile/{$logged_user->username}" enctype="multipart/form-data"  >
	<img src='avatars_mini/{$logged_user->avatar}' class='avatar' alt='{$logged_user->username}' />
	<textarea id="message" name="message" cols="30" rows="3"></textarea><br />
	<label for="add_photo">{$upload_picture}</label><input type="file" name="add_photo" id="add_photo" />
	<input type="hidden" name="user" value="{$logged_user->username}" />
	<input type="hidden" name="message_group" value="{$group_data->group_id}" />
	<input type="hidden" name="user_from_group_page" value="1" />
	<div class='mid'>
	<span id='chars_left'>140</span> {$chars_left}<br />
	<input type="submit" name="add_message" value="{$label_send}" class="submit" />
	</div>
	</form>
	</div>

<p class="tabs">
<input type="hidden" name="current_tab" id="current_tab" value="group_messages" />
<input type="hidden" name="current_user" id="current_user" value="{$logged_user->id}" />
<input type="hidden" name="current_group" id="current_group" value="{$group_data->group_id}" />



<a href="groups/profile/{$group_data->group_furl}" class="profile current" id="profile_friends" rel="">Group messages</a>

</p>
<div id="tab_content"></div>
