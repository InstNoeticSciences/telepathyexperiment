<h1 class='header'>{$forgot_header}</h1>
<p>{$forgot_paragraph}</p>
<form action="forgot_password" method="post">
	<label for="forgot_email">{$label_your_email}</label><input type="text" id="forgot_email" name="forgot_email" class="wpisz_cos" /><br />
	<label>&nbsp;</label><input type="submit" name="remind" value="{$label_remind}" class="submit" />
</form>
{if $ok}<p class="ok">{$ok}</p>{/if}
{if $error}<p class="error">{$error}</p>{/if}
