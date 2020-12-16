{if $logged_user}
	<h1>{$contact_us}</h1>
{else}
	<h1 class='header'>{$contact_us}</h1>
{/if}
<form action="contact" method="post">
<label for="subject">{$label_subject}</label><input type="text" name="subject" id="subject" class="wpisz_cos" value="{$smarty.post.subject}" /><br />
<label for="message_content">{$label_message}</label><textarea name="message_content" id="message_content" class="wpisz_cos" cols="30" rows="5">{$smarty.post.message_content}</textarea><br />
<label for="your_name">{$label_your_name}</label><input type="text" name="your_name" id="your_name" class="wpisz_cos" value="{$logged_user->username}" /><br />
<label for="email">{$label_your_email}</label><input type="text" name="email" id="email" class="wpisz_cos" value="{$logged_user->email}" /><br />
<label>&nbsp;</label><input type="submit" name="send" class="submit" value="{$label_send}" />
</form>
{if $ok}<p class="ok">{$ok}</p>{/if}
{if $error}<p class="error">{$error}</p>{/if}
