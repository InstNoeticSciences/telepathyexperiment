<h1 class="header">{$step3}</h1>
<form method="post" action="start_my_life">
<label for="location">{$label_location}</label><input type="text" name="location" id="location" class="wpisz_cos" value="{$smarty.post.location}" /><br />
<label for="age">{$label_dob}</label>
<select name="day" id="day">
<option value="0">{$option_day}</option>
{foreach from=$days item=i}
	{if $i==$day}
		<option value="{$i}" selected="selected">{$i}</option>
	{else}
		<option value="{$i}">{$i}</option>
	{/if}
{/foreach}
</select>

<select name="month" id="month">
<option value="0">{$option_month}</option>
{foreach from=$months item=i}
	{if $i==$month}
		<option value="{$i}" selected="selected">{$i}</option>
	{else}
		<option value="{$i}">{$i}</option>
	{/if}
{/foreach}
</select>

<select name="year" id="year">
<option value="0">{$option_year}</option>
{foreach from=$years item=i}
	{if $i==$year}
		<option value="{$i}" selected="selected">{$i}</option>
	{else}
		<option value="{$i}">{$i}</option>
	{/if}
{/foreach}
</select>
<br />
<label for="bio">{$label_about_me}<br/><span class="comment">{$limit_200chr}</span></label><textarea class="wpisz_cos" name="bio" id="bio" rows="3" cols="30">{$smarty.post.bio}</textarea><br />
<label for="www">{$label_more_info}</label><input type="text" name="www" id="www" class="wpisz_cos" value="{$smarty.post.www}" /><br />
<label>&nbsp;</label><span class="comment">{$label_url}</span><br />
<label for="interests">{$label_interests}<br /><span class="comment">{$comma_separated_200chr}</span></label><textarea class="wpisz_cos" name="interests" id="interests" rows="3" cols="30">{$smarty.post.interests}</textarea><br />
<label>&nbsp;</label><input type="submit" class="submit" name="save_life" value="{$label_save_changes}" />
</form>
{if $ok}<p class="ok">{$ok}</p>{/if}
{if $error}<p class="error">{$error}</p>{/if}
<p class="mid"><a href="start_find_friends" class="continue">{$link_continue} &gt;</a></p>
<p class="mid"><a href="profile/{$logged_user->username}">{$skip_setup}</a></p>
