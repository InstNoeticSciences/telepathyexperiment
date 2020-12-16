<?php

$groups=array();
$groups_l1 = $db->fetch_objects( $db->get_recs("groups_mes", "*", "level_id=1", "order_id ASC" ) );
$groups_l2 = $db->fetch_objects( $db->get_recs("groups_mes", "*", "level_id=2", "order_id ASC" ) );
if ($groups_l1) foreach($groups_l1 as $k=>$v) $groups[$v->group_id]=$v;
if ($groups_l2) foreach($groups_l2 as $k=>$v) $groups[$v->parent_id]->subgroups[] = $v;
unset($groups_l1);
unset($groups_l2);
//pr($groups);

?>
<style>
.b{font-weight:bold}
.re{background:#f00}
.table_group, .table_group td,th {border:1px solid #eee;
border-bottom:1px solid #ccc;
}

</style>

<table class="table_group">
<tr>
<th>&nbsp;</th>
<th>Title of Group</th>
<th>Img</th>
<th>Descr</th>
<th>Tags</th>
<th>Order</th>
<th colspan="2">Tools</th>
</tr>
<?php foreach($groups as $k=>$v): ?>
<tr class="gray">
<td><a href="<?php echo $self;?>&act=add&parent_id=<?php echo $v->group_id; ?>">+</a></td>
<td class="b"><?php echo $v->group_title; ?></td>
<td><?php
$fnimg = DIR_AVATAR_GROUP.$v->group_id.'_25'.$v->group_image; 
echo (file_exists($fnimg))? '<a href="../avatars_group/'.$v->group_id.'_'.$v->group_image.'" target="_blank"><img src="../avatars_group/'.$v->group_id.'_25'.$v->group_image.'"></a>' : '-';
?></td>
<td><?php echo $v->group_descr; ?></td>
<td><?php echo $v->group_tags; ?></td>
<td align="right"><?php echo $v->order_id; ?></td>
<td><input type="button" value="edit" OnClick="document.location='<?php echo $self;?>&act=edit&group_id=<?php echo $v->group_id;?>';"></td>
<td><input type="button" value="x" class="re" OnClick="if(confirm('Delete group?'))document.location='<?php echo $self;?>&delete_group_id=<?php echo $v->group_id;?>';"></td>
</tr>
<?php
if ($v->subgroups):
foreach($v->subgroups as $kk=>$vv):
?>
<tr>
<td>&nbsp;</td>
<td class="b">&nbsp;&nbsp;&nbsp;<?php echo $vv->group_title; ?></td>
<td><?php
$fnimg = DIR_AVATAR_GROUP.$vv->group_id.'_25'.$vv->group_image; 
echo (file_exists($fnimg))? '<a href="../avatars_group/'.$vv->group_id.'_'.$vv->group_image.'" target="_blank"><img src="../avatars_group/'.$vv->group_id.'_25'.$vv->group_image.'"></a>' : '-';
?></td>
<td><?php echo $vv->group_descr; ?></td>
<td><?php echo $vv->group_tags; ?></td>
<td align="right"><?php echo $vv->order_id; ?></td>
<td><input type="button" value="edit" OnClick="document.location='<?php echo $self;?>&act=edit&group_id=<?php echo $vv->group_id;?>';"></td>
<td><input type="button" value="x" class="re" OnClick="if(confirm('Delete group?'))document.location='<?php echo $self;?>&delete_group_id=<?php echo $vv->group_id;?>';"></td>
</tr>
<?php
endforeach;
endif;
?>

<?php endforeach; ?>
</table>
