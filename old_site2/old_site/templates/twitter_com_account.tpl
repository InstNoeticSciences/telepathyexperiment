<h2>{$twitter_com_account_header}</h2>
<div class="clear">&nbsp;</div>
<form method="post" action="settings/twitter_com_account">
<label for="name">{$twitter_com_account_username}</label><input type="text" name="username" id="name" class="wpisz_cos" value="{$username}" /><br />
<label for="pass">{$twitter_com_account_password}</label><input type="password" name="pass" id="pass" class="wpisz_cos" value="{$pass}" /><br />
{if $flag_send_message}
    <p><input type="checkbox" name="flag_send_message" id="flag_send_message" value="1" checked="checked" class="chk" />{$label_flag_send_message}</p>
{else}
    <p><input type="checkbox" name="flag_send_message" id="flag_send_message" value="1" class="chk" />{$label_flag_send_message}</p>
{/if}
<label>&nbsp;</label><input type="submit" class="submit" name="save_twitter_com_account" value="{$label_save_changes}" />
