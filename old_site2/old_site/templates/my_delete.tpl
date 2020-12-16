<h1>{$profile_del_header}</h1>
<p>{$profile_del_explanation}</p>
<form action="delete_me.php" method="post">
<p class="mid"><input type="button" class="submit" name="del_pass" id="del_pass" value="{$label_remove_account}" /></p>
</form>

<div id="del_pass_form">
<p>{$profile_del_u_sure}</p>
<form action="delete_me.php" method="post">
	<label for="pass">{$label_password}</label><input type="password" name="pass" id="pass" class="wpisz_cos" /><br />
	<input type="hidden" name="user" value="{$logged_user->id}" />
	<p class="mid"><input type="submit" class="submit" name="delete_me" value="{$label_delete_account}" /></p>
</form>
</div>