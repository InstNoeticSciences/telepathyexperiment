{if $logged_user->username==$user->username}
    <form method="post" action="profile/{$user->username}" enctype="multipart/form-data"  >
        <div class="experiment_wrapper">
            <div class="experiment_header">
                <h3>{$experiment_step_title} {$experiment_number}{$trial_number}</h3>
                <input type="hidden" id="page_load" name="page_load" value="{$smarty.post.page_load}" />
            </div>

            <div class="experiment_left">
                <p class="tt_section_header">{$my_friends}</p>
                <input type="hidden" name="friend_idx" value="{$smarty.post.friend_idx}" />
                {foreach from=$smarty.post.friend_list item=i name=friend_line}
                    <input type="hidden" id="friend_list[{$smarty.foreach.friend_line.index}]" name="friend_list[{$smarty.foreach.friend_line.index}]" value="{$i}" />
                {/foreach}

                <div class="scroll_box">
                    <p class="tt_comment">
                        {foreach from=$smarty.post.friend_list item=i key=k name="friend_line"}
                            <input type="checkbox" name="my_friends[]" value="{$i}" {if $transfer != "true"}disabled="disabled"{/if}> {$i}<br>
                        {/foreach}
                    </p>
                </div>
  
                {if $smarty.session.step_number == '1' && $transfer == "true"}
                    <input type="submit" id="transfer" name="transfer" value="{$transfer_participants}" class="tt_submit" />
                {/if}
            </div>

            <div class="experiment_right">
                <p class="tt_section_header">{$selected_participants}</p>
                <input type="hidden" name="part_idx" value="{$smarty.post.part_idx}" />
                {foreach from=$smarty.post.part_list item=i name=part_line}
                    <input type="hidden" id="part_list[{$smarty.foreach.part_line.index}]" name="part_list[{$smarty.foreach.part_line.index}]" value="{$i}" />
                {/foreach}

                <div class="scroll_box">
                    <input type="hidden" name="chosen_caller" value="{$smarty.post.chosen_caller}" />
                    <input type="hidden" name="actual_caller" value="{$smarty.post.actual_caller}" />
                    <p class="tt_comment">
                        {foreach from=$smarty.post.part_list item=i key=k name="part_line"}
                            <input type={if $smarty.session.step_number == '1'}"checkbox"{else}"radio" {if $i==$smarty.post.chosen_caller}checked{/if}{/if} name="sel_friends[]" value="{$i}" {if $choose != "true" && $rejected != "true"}disabled="disabled"{/if} /> {$i}  {if $i==$smarty.post.actual_caller}<img src="/grafika/arrow_left.png" alt="<" />{/if}<br>
                        {/foreach}
                    </p>
                </div>

                {if $smarty.session.step_number == '1'}
                    {if $rejected == "true"}
                        <input type="submit" id="reject" name="reject" value="{$reject_participants}" class="tt_submit" />
                    {/if}
                    {if $start == "true"}
                        <input type="submit" id="start" name="start" value="{$start_the_trial}" class="tt_submit" />
                    {/if}
                {/if}
                {if $smarty.session.step_number == '2'}
                    {if $start == "true"}
                        <input type="submit" id="start" name="start" value="{$start_the_trial}" class="tt_submit" />
                    {/if}
                    {if $choose == "true"}
                        <input type="submit" id="choose" name="choose" value="{$choose_caller}" class="tt_submit" />
                    {/if}
                    {if $choose != "true" && $start != "true"}
                        <input type="text" id="dummy" name="dummy" class="tt_dummy" disabled="disabled" />
                    {/if}
                {/if}
            </div>

            <div class="experiment_footer">
                <input type="hidden" name="status_idx" id="status_idx" value="{$smarty.post.status_idx}" />
                {foreach from=$smarty.post.status item=i name=status_line}
                    <input type="hidden" id="status[{$smarty.foreach.status_line.index}]" name="status[{$smarty.foreach.status_line.index}]" value="{$i}" />
                {/foreach}

                <div class="status_box" id="status_msg" name="status_msg" {* style="display:none" *}>
                    <p class="tt_comment">
                        {foreach from=$smarty.post.status item=i}
                            {$i}<br>
                        {/foreach}
                    </p>
                </div>
                {literal}
                    <script type="text/javascript">
                        scrollToBottom("status_msg");
                    </script>
                {/literal}
                {if $next == "true"}
                    <input type="submit" id="next" name="next" value="{$next_trial}" class="tt_submit" />
                {/if}
                {if $restart == "true"}
                    <input type="submit" id="restart" name="restart" value="{$new_experiment}" class="tt_submit" />
                {/if}
            </div>
        </div>
    </form>
{else}
	{if $user->visible}
	<div id="experiment_header">
		<div class="clear">&nbsp;</div>
	</div>
	{/if}
{/if}
