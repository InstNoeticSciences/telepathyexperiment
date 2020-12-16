<h1>Processing transaction...</h1>
<form action="{$paypal_addr}" method="post">
<input type="hidden" name="business" value="{$paypal_business}" />
<input type="hidden" name="cmd" value="_xclick" />
<input type="hidden" name="item_name" id="item_name" value="{$paypal_item}" />
<input type="hidden" name="amount" value="{$paypal_amount}" />
<input type="hidden" name="notify_url" value="" />
<input type="hidden" name="return" value="{$base_href}{$paypal_success}" />
<input type="hidden" name="cancel_return" value="{$base_href}{$paypal_failure}" />
</form>