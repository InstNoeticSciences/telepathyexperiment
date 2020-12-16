<h2>{$my_profile_header}</h2>
<img src='avatars_mini/{$logged_user->avatar}' alt='{$logged_user->username}' class='avatar' />
<div class="clear">&nbsp;</div>
<form method="post" action="settings/my_profile">
<label for="name">{$label_full_name}</label><input type="text" name="name" id="name" class="wpisz_cos" value="{$logged_user->name}" /><br />
<label for="pass1">{$label_password}</label><input type="password" name="pass1" id="pass1" class="wpisz_cos" /><br />
<label for="pass2">{$label_repeat_pass}</label><input type="password" name="pass2" id="pass2" class="wpisz_cos" /><br />
<label for="email">{$label_your_email}</label><input type="text" name="email" id="email" class="wpisz_cos" value="{$logged_user->email}" /><br />
{*
<label for="lang">{$label_lang}</label>
<select name="lang" id="lang">
{foreach from=$langs item=i key=k}
	<option value="{$k}" {if $k==$lang}selected="selected"{/if}>{$i}</option>
{/foreach}
</select>
<br/>
*}
{if $logged_user->visible}
	<p><input type="checkbox" name="visible" id="visible" value="1" checked="checked" class="chk" />{$visibility}</p>
{else}
	<p><input type="checkbox" name="visible" id="visible" value="1" class="chk" />{$visibility}</p>
{/if}
<label>&nbsp;</label><input type="submit" class="submit" name="save_profile" value="{$label_save_changes}" />
</form>
{if $ok}<p class="ok">{$ok}</p>{/if}
{if $error}<p class="error">{$error}</p>{/if}
