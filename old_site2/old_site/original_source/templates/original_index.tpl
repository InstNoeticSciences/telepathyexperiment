<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>{$title}</title>
<meta name="keywords" content="{$keywords}" />
<meta name="description" content="{$description}" />
<base href="{$base_href}" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="logo/favicon.ico" type="image/x-icon" />
<link href="css/divs.css" rel="stylesheet" type="text/css" />
<link href="css/forms.css" rel="stylesheet" type="text/css" />
<link href="css/images.css" rel="stylesheet" type="text/css" />
<link href="css/links.css" rel="stylesheet" type="text/css" />
<link href="css/tables.css" rel="stylesheet" type="text/css" />
<link href="css/tags.css" rel="stylesheet" type="text/css" />
<link href="css/text.css" rel="stylesheet" type="text/css" />
<link href="css/thickbox.css" rel="stylesheet" type="text/css" />


<script src="js/jquery.js" type="text/javascript"></script>
{if $page=="profile.tpl" || $page=="settings.tpl"}
<link href="css/farbtastic.css" rel="stylesheet" type="text/css" />
<script src="js/farbtastic.js" type="text/javascript"></script>
{/if}
<script src="js/script.js" type="text/javascript"></script>
<script src="js/thickbox.js" type="text/javascript"></script>

{$main_css}
{$side_css}
</head>

<body>

<a name="up"></a>
<div id="top">
	<form method="post" action="search" class="form_search" id="top_form">
	<input type="text" id="search_words" name="search_words" value="{$friend_search}" />
	<input type="submit" class="submit" name="search" id="search" value="{$label_search}" />
	<input type="submit" class="submit" name="search_group" id="search" value="{$group_label}" style="width:80px;" OnClick="document.forms['top_form'].action='groups/search'" />
	<br/>
	</form>

	<form method="post" action="{$base_href}change_lang" id="cl_form" class="form_lang">
	{$label_lang}: <select name="lang" id="lang" OnChange="o=document.getElementById('cl_form');o.submit();">
	{foreach from=$langs item=i key=k}
		<option value="{$k}" {if $k==$lang}selected="selected"{/if}>{$i}</option>
	{/foreach}
	</select>
	</form>

	<a href="home" id="logo">{$title}</a>
</div>
<div id="middle">
	<div id="right">
		{if $page != "welcome.tpl" && $page != "welcome_info.tpl" && $page != "start_activate_im.tpl" && $page != "start_add_photo.tpl" && $page != "start_find_friends.tpl" && $page != "start_my_life.tpl"}
			{include file="right.tpl"}
		{/if}
	</div>
	<div id="main">
		{if $logged_user && $page!="welcome.tpl" && $page!="start_activate_im.tpl" && $page!="start_add_photo.tpl" && $page!="start_find_friends.tpl" && $page!="start_my_life.tpl"}
			<div id="mainmenu">
			<a href="home">{$menu_home}</a>
			<a href="profile/{$logged_user->username}">{$menu_my_panel}</a>
			<a href="invite">{$menu_invite}</a>
			<a href="vision/maps.html">{$menu_vision}</a>
			<a href="settings">{$menu_settings}</a>
			{foreach from=$static_pages item=i key=k}
				<a href='page/{$static_pages_links[$k]}'>{$i->title}</a>
			{/foreach}
			<a href="groups/">{$menu_groups}</a>
			<a href="logout.php">{$menu_logout}</a>
			</div>
		{/if}
		{include file=$page}
	</div>

	<div id="footer">
		<a href="about">{$bm_about}</a> |
		<a href="contact">{$bm_contact}</a> |
		<a href="api_docs">{$bm_api}</a> |
		<a href="help">{$bm_help}</a> |
		<a href="terms_and_conditions">{$bm_terms}</a> |
		<a href="privacy_policy">{$bm_privacy}</a>

		<p>Powered By <a target="_blank" href="http://www.revou.com">ReVou Software</a></p>

	</div>
</div>
</body>
</html>

