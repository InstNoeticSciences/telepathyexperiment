<h1>{$sms_credits_title}</h1>
<p>{$you_have} {$logged_user->sms_credits} {$qty_sms_credits}.</p>
<p>{$buying_instruction}</p>
<form action="buy_credits" method="post">
<label for="qty">{$choose_sms_plan}:</label>
<select id="qty" name="qty" class='wpisz_cos'>
{foreach from=$credit_qty item=i key=k}<option value="{$i}-{$plan_price[$k]}">{$i} {$credits_for} ${$plan_price[$k]}</option>{/foreach}
</select><br />
<label>&nbsp;</label><input type="submit" class="submit" id="buy" value="{$buy}" name="buy" />
</form>

<h1>{$sms_limit_header}</h1>
<p>{$sms_limit_text}</p>
<p class='mid'>
	{$used_credits_below_limit}: <strong>{$logged_user->used_sms}</strong><br />
	{$your_limit_is}: <strong>{$logged_user->sms_limit}</strong><br />
</p>
<form action='' method='post'>
<p class='mid'><input type='submit' name='reset_limit' id='reset_limit' value='{$reset_sms_limit}' class='submit' /></p>
</form>

<h2>{$set_sms_limit}</h2>
<form action='' method='post'>
<label for='limit'>{$set_limit_to}:</label><input type='text' name='limit' id='limit' class='wpisz_cos' />
<input type='submit' name='set_limit' value='{$set_limit_button}' class='submit' />
</form>
{if $smarty.post.set_limit}
	{if $sms_limit_error} <p class='error'>{$sms_limit_error}</p>
	{else}<p class='ok'>{$ok_sms_limit_set}</p>{/if}
{/if}

{if $transactions}
<h1>{$your_sms_transactions}</h1>
<table><tr><th>{$trans_date}</th><th>{$trans_time}</th><th>{$trans_credits}</th><th>{$trans_value}</th></tr>
{foreach from=$transactions item=i}
<tr>
<td class='mid'>{$i->time|date_format:"%D"}</td><td class='mid'>{$i->time|date_format:"%H:%M"}</td>
<td class='mid'>{$i->credits}</td><td class='mid'>${$i->value}</td></tr>
{/foreach}
</table>
{/if}