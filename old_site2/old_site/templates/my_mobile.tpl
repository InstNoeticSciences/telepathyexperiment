<h2>{$my_cell_header}</h2>
<p>{$my_cell_text}</p>
<form action="settings/my_mobile" method="post">
<label for="mobile_num">{$my_cell_label}</label><input type="text" name="mobile_num" id="mobile_num" class="wpisz_cos" value="{$phone}" /><br />
<label for="mobile_car">{$my_carrier_label}</label>
<select name="mobile_car">
    {html_options values=$carriers output=$carriers selected=$my_carrier}
</select>
<br />
<label>&nbsp;</label><input type="submit" value="{$save}" name="save_mobile" class="submit" />
{if $error}<p class="error">{$error}</p>{/if}
</form>
