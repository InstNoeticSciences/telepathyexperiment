<?php
include("../inc/config.php");
include("../inc/text.php");
include("../lib/functions.php");
include("../lib/forms.php");
include("../lib/database.php");
include("../lib/images.php");
include("../lib/messages.php");
include("../lib/user.php");
include("../lib/groups.php");

if ($_GET['act']=='new')
	$tmp = group::get_top10_new();
else
	$tmp = group::get_top10_popular();

if (!sizeof($tmp))
	exit;

//pr($tmp);

$o = '<table>';
foreach($tmp as $k=>$i){
	$group_title = htmlspecialchars($i->group_title);
	$o .= '<tr><td width="30">';
	if ($i->group_image)
		$o .= '<img src="avatars_group/'.$i->group_id.'_25'.$i->group_image.'">';
	else
		$o .= ($k+1).'.';
	$o .= '</td><td><a href="groups/profile/'.$i->group_furl.'">'.$i->group_title.'</a>';
	if ($_GET['act']!='new')
		$o .= ' ('.$i->c_c.')';
	$o .= '</td></tr>';
}
$o .= '</table>';
echo $o;

?>