<h2>{$my_im_header}</h2>
<p>{$my_im_explanation}</p>
<form method="post" action="settings/my_im">
<label for="im_type">{$label_im_type}</label><select id="im_type" name="im_type" class="wpisz_cos">
{foreach from=$im_types item=i}
	{if $i==$logged_user->im_type}
		<option value="{$i}" selected="selected">{$i}</option>
	{else}
		<option value="{$i}">{$i}</option>
	{/if}
{/foreach}
</select><br />
<label for="im_id">{$label_im_id}</label><input type="text" name="im_id" id="im_id" class="wpisz_cos" value="{$logged_user->im_id}" /><br />
<label>&nbsp;</label><input type="submit" name="save_im" class="submit" value="{$label_save_changes}" />
</form>
{if $ok}<p class="ok">{$ok}</p>{/if}
{if $error}<p class="error">{$error}</p>{/if}
{if !$smarty.post.save_im && $contact}<p>{$contact}</p>{/if}
<p class="mid"><img src="logos/aim.jpg" width="32" height="20" class="im_logo" alt="AIM" /><img src="logos/gtalk.jpg" width="42" height="20" class="im_logo" alt="GTalk" /><img src="logos/icq.jpg" width="47" height="20" class="im_logo" alt="ICQ" /><img src="logos/jabber.jpg" width="48" height="20" class="im_logo" alt="Jabber" /><img src="logos/msn.jpg" width="52" height="20" class="im_logo" alt="MSN" /><img src="logos/yahoo.jpg" width="64" height="20" class="im_logo" alt="Yahoo Messenger" /></p>

{if $logged_user->im_id && $logged_user->im_type}
<form method="post" action="settings/my_im">
<input type="hidden" value="{$logged_user->id}" name="uid" />
<p class="mid"><input type="submit" class="submit" name="deactivate_im" id="deactivate_im" value="{$label_deactivate}" /></p>
</form>
	{if $deactivated} <p class="ok">{$deactivated}</p>{/if}
{/if}
