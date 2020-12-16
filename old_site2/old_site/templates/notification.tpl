<h2>{$notification_header}</h2>
<form method="post" action="settings/notification">
<label form="notify_friend">{$notification_label}</label>
{if $logged_user->notify_friend}
	<input type="checkbox" name="notify_friend" id="notify_friend" class="chk" value="1" checked="checked" /><br />
{else}
	<input type="checkbox" name="notify_friend" id="notify_friend" class="chk" value="1" /><br />
{/if}
<div class="clear">&nbsp;</div>
<p class="comment">{$notification_comment}</p>

<label>&nbsp;</label><input type="submit" name="save_notification" value="{$label_save_changes}" class="submit" />
</form>
{if $ok} <p class="ok">{$ok}</p>{/if}
