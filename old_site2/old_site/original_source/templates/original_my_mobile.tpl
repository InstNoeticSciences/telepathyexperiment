<h2>{$my_mobile_header}</h2>
<p>{$my_mobile_text}</p>
<form action="settings/my_mobile" method="post">
<label for="mobile_num">{$my_mobile_label}</label><input type="text" name="mobile_num" id="mobile_num" class="wpisz_cos" value="{$phone}" /><br />
<label>&nbsp;</label><input type="submit" value="{$save}" name="save_mobile" class="submit" />
{if $ok}<p class="ok">{$ok} <span id="im_contact">{$gateway_phone}</span></p>{else}
<p>{$gateway_info}<span id="im_contact">{$gateway_phone}</span></p>{/if}
{if $error}<p class="error">{$error}</p>{/if}
</form>
