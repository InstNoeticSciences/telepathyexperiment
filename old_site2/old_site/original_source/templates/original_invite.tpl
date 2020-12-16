<h1>{$invite_header}</h1>
<p>{$invite_paragraph}</p>
<form method="post" action="invite">
<label for="check_type">{$import_contacts_from}</label><select class="wpisz_cos" id="check_type" name="check_type">
{if $smarty.post.check_type=="msn"}
	<option value="msn" selected="selected">MSN</option>
{else}
	<option value="msn">MSN</option>
{/if}

{if $smarty.post.check_type=="aol"}
	<option value="aol" selected="selected">AOL</option>
{else}
	<option value="aol">AOL</option>
{/if}

{if $smarty.post.check_type=="gmail"}
	<option value="gmail" selected="selected">GMail</option>
{else}
	<option value="gmail">GMail</option>
{/if}

{if $smarty.post.check_type=="hotmail" || !$smarty.post.check_type}
	<option value="hotmail" selected="selected">Hotmail</option>
{else}
	<option value="hotmail">Hotmail</option>
{/if}

{if $smarty.post.check_type=="yahoo"}
	<option value="yahoo" selected="selected">Yahoo</option>
{else}
	<option value="yahoo">Yahoo</option>
{/if}
</select><br />
<label for="check_user">{$label_user_name}</label><input type="text" name="check_user" id="check_user" class="wpisz_cos" /><br />
<label for="check_pass">{$label_password}</label><input type="password" name="check_pass" id="check_pass" class="wpisz_cos" /><br />
<label>&nbsp;</label><p class="comment">{$pass_not_stored}</p>
<label>&nbsp;</label><input type="submit" name="check_friends" value="{$label_check_now}" class="submit" />
</form>
{if $error1}<p class="error">{$error1}</p>
{else}
	{if $names}
		<p>{$invite_explanation}</p>
		<p>{$we_found1} <strong>{$user_count}</strong> {$we_found2}</p>
		<p><a href="#" id="check_all">{$check_all}</a> | <a href="#" id="uncheck_all">{$uncheck_all}</a></p>
		<form method="post" action="invite">
		<div id="contact_list">
		<table>
		<tr><th></th><th>{$name_and_mail}</th><th>{$member_of}</th></tr>
		{foreach from=$names item=i key=k}
			{if $k % 2 != 0}
				{if $usernames[$k]}<tr class="gray member">{else}<tr class="gray">{/if}
			{else}
				{if $usernames[$k]}<tr class="member">{else}<tr>{/if}
			{/if}
			<td class="mid"><input type="checkbox" name="user[{$k}]" id="user_{$k}" class="chk" value="{$i}___{$emails[$k]}___{$uids[$k]}" /></td>
			<td>{$i}<br />({$emails[$k]})</td>
			<td class="mid">
				{if $usernames[$k]}{$yes} ({$usernames[$k]}) {else} {$no} {/if}
			</td>
			</tr>
		{/foreach}
		</table>
		</div>
		<p class="invitation_message">
{$invitation_preview1|nl2br}<br /><br />
{$message->msg|nl2br}<br />
({$base_href}message/{$message->id})<br /><br />
{$invitation_preview2|nl2br}<br />
{if $logged_user->name}{$logged_user->name}{else}{$logged_user->username}{/if}
		</p>
		<p class="mid"><input type="submit" name="send_invitations" value="{$label_invite}" class="submit" /></p>
		</form>
	{else}
		{if $smarty.post.check_friends}<p>{$no_contacts_found}</p>{/if}
	{/if}
{/if}
{if $error2}<p class="error">{$error2}</p>{/if}


{if !$smarty.post.check_friends && !$smarty.post.send_invitations}
<h1 class="mid">{$big_or}</h1>
<p>{$enter_some_emails}</p>
	<form method="post" action="invite">
	<label for="addresses">{$label_emails}<br /><span class="comment">{$separate_commas}</span></label><textarea id="addresses" name="addresses" cols="30" rows="4" class="wpisz_cos"></textarea><br />
	<p class="invitation_message">
{$invitation_preview1}<br /><br />
{$message->msg|nl2br}<br />
({$base_href}message/{$message->id})<br /><br />
{$invitation_preview2|nl2br}<br />
{if $logged_user->name}{$logged_user->name}{else}{$logged_user->username}{/if}
	</p>
	<div class="clear">&nbsp;</div>
	<label>&nbsp;</label><input type="submit" class="submit" name="invite" value="{$label_invite}" />
	</form>
{/if}
{if $error3}<p class="error">{$error3}</p>{/if}

{if $ok}<p class="ok">{$ok}</p>{/if}
