{if !$paypal_error}
<h1>{$buy_success_title}</h1>
<p>{$buy_success1} <strong>{$credits_qty}</strong> {$buy_success2}</p>
{else}
<h1>{$buy_success_error_title}</h1>
<p>{$buy_success_error}</p>
{/if}