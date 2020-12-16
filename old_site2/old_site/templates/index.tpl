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
{if $page=="profile.tpl"}
<script src="js/experiment.js" type="text/javascript"></script>
{/if}
{$main_css}
{$side_css}
</head>

<body>
<a name="up"></a>
<div id="top">
	<form method="post" action="search" class="form_search" id="top_form">
	<input type="text" id="search_words" name="search_words" value="{$friend_search}" />
	<input type="submit" class="submit" name="search" id="search" value="{$label_search}" />
	<br/>
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
			<a href="home">{$menu_results}</a>
            <a href="profile/{$logged_user->username}">{$menu_conduct_experiment}</a>
            {if $is_coordinator == "Y"}
                <a href="invite">{$menu_invite_participants}</a>
            {/if}
			<a href="settings">{$menu_settings}</a>
			<a href="about_the_test">{$about_the_test}</a>
			<a href="groups/">{$menu_groups}</a>
			<a href="logout.php">{$menu_logout}</a>
			</div>
		{/if}
		{include file=$page}
	</div>

	<div id="footer">
		<a href="contact">{$bm_contact}</a>
		<p>Powered By <a target="_blank" href="http://www.revou.com">ReVou Software</a></p>

	</div>
</div>
</body>
</html>

