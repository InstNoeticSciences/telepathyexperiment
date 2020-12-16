<h2>{$sticker_header}</h2>
<h3>{$sticker_flash}</h3>
<p class="mid">
<object data='{$base_href}sticker.swf?user={$logged_user->id}' type='application/x-shockwave-flash' width='234' height='250'>
	<param name='movie' value='{$base_href}sticker.swf?user={$logged_user->id}' />
	<param name='flashvars' value='baseurl={$base_href}' />
</object>
</p>
<p>{$paste_code_flash}</p>
<textarea name="paste_code" id="paste_code" cols="30" rows="5" class="paste_code">
&lt;object data='{$base_href}sticker.swf?user={$logged_user->id}' type='application/x-shockwave-flash' width='234' height='250'&gt;
	&lt;param name='movie' value='{$base_href}sticker.swf?user={$logged_user->id}' /&gt;
	&lt;param name='flashvars' value='baseurl={$base_href}' /&gt;
&lt;/object&gt;
</textarea>

<p>{$sticker_choose_color}</p>
<form method="post" action="settings/my_sticker">
<label for="sticker_color">{$label_sticker_color}</label><input type="text" name="sticker_color" id="sticker_color" class="wpisz_cos" value="{$sticker_color}" /><br />
<label>&nbsp;</label><input type="submit" name="save_sticker" value="{$label_save_changes}" class="submit" />
</form>

<h3>{$sticker_js}</h3>
<p>{$sticker_js_comment}</p>
 <p><textarea name="js_my_status" id="js_my_status" cols="30" rows="5" class="paste_code">{literal}<script type="text/javascript">
onload = function(){
	var status_script = document.createElement('script');
	status_script.setAttribute('src', '{/literal}{$base_href}{literal}my_status.php?user={/literal}{$logged_user->username}{literal}');
	status_script.setAttribute('type','text/javascript');
	document.getElementsByTagName('head')[0].appendChild(status_script);
	status_script.onload = function(){
		var div = document.getElementById("latest_msg");
		div.innerHTML = html;
	}
}
</script><div id="latest_msg"></div>{/literal}</textarea>
</p>
<p>{$sticker_friends_comment}</p>
<p><textarea name="js_my_friends" id="js_my_friends" cols="30" rows="5" class="paste_code">{literal}<script type="text/javascript">
onload = function(){
	var friends_script = document.createElement('script');
	friends_script.setAttribute('src', '{/literal}{$base_href}{literal}my_friends.php?user={/literal}{$logged_user->username}{literal}');
	friends_script.setAttribute('type','text/javascript');
	document.getElementsByTagName('head')[0].appendChild(friends_script);
	friends_script.onload = function(){
		var div = document.getElementById("my_friends");
		div.innerHTML = html;
	}
}
</script>
<div id="my_friends"></div>{/literal}</textarea>
</p>
