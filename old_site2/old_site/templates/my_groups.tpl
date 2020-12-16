<h2>{$my_groups_header}</h2>
<div class="clear">&nbsp;</div>
<form method="post" action="settings/my_groups">

<table>
<tr valign="top">
<td><label for="groups">{$label_groups}</label></td>
<td>
<div style="border:1px solid #ccc;width:350px;height:300px;overflow:auto;">

<table>
{foreach from=$user_groups item=i}
<tr>
<td><a href="groups/profile/{$i->group_furl}">{$i->group_title}</a></td>
<td align="right"><a href="groups/unjoin/{$i->group_furl}/{$logged_user->id}" OnClick="return confirm('{$you_are_sure}')">{$group_leave_label}</a></td>
</tr>

{*
{if $i->subgroups}
{foreach from=$i->subgroups item=ii}
<tr>
<td>&nbsp;&nbsp;&nbsp;
<input type="checkbox" name="groups[{$ii->group_id}]" value="1" id="groups[{$ii->group_id}]" 
{if in_array($ii->group_id, $user_groups) } checked="checked" {/if}/>
</td>

<td align="left">{$ii->group_title}</td>
</tr>

{/foreach}
{/if}
*}
{/foreach}
</table>

</div>
</td>
</tr>
</table>

<br />

<label>&nbsp;</label><input type="submit" class="submit" name="save_groups" value="{$label_save_changes}" />
</form>
{if $ok}<p class="ok">{$ok}</p>{/if}
{if $error}<p class="error">{$error}</p>{/if}
