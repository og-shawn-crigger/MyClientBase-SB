{* invoice action panel *}

<script type="text/javascript">
$(document).ready(function() {
				
		$("#show_search").click(function() {
			$('#invoice_search').toggle();

		});

		/*
		$("#btn_close_search").click(function() {
			console.log('hide');
			//$('#invoice_search').hide();

		});
		*/
});
</script>

{* MAIN ACTIONS *}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: gray;">
	<h3 class="title_black">{t}Main Actions{/t}</h3>

	<ul class="quicklinks content toggle" >
{* siteurl:{$site_url} *} 
		{* global items: these items are always visible except the page destination of the link *}
			{if !{preg_match pattern="\/invoices\/index$" subject=$site_url}}
				<li><a href="/invoices/index">{t}Show Invoices{/t}</a></li>
			{/if}
			
			{if !{preg_match pattern="\/invoices\/index\/is_quote\/1$" subject=$site_url}}
				<li><a href="/invoices/index/is_quote/1">{t}Show Quotes{/t}</a></li>
			{/if}
			
			{if !{preg_match pattern="\/payments\/index$" subject=$site_url}}
				<li><a href="/payments/index">{t}Show Payments{/t}</a></li>	
			{/if}
			
			{if !{preg_match pattern="\/calendar" subject=$site_url}}
				<li><a id="btn_calendar_view" href="{$site_url}?btn_calendar_view=true">{t}Calendar View{/t}</a></li>
			{/if}
		{* end global items *}
		
		{* Go back to customer profile *}
		{if {preg_match pattern="\/uid\/[0-9]." subject=$site_url}}
			{if {preg_match pattern="([0-9]+)" subject=$site_url}}
				{* debugging {$matches|print_r} *}
				{$contact_id = $matches[0]}
				{if {preg_match pattern="\/uid\/" subject=$site_url}}	
					<li><a href="/contact/details/uid/{$contact_id}">{t}Go back to contact's profile{/t}</a></li>
				{/if}
				{if {preg_match pattern="\/oid\/" subject=$site_url}}	
					<li><a href="/contact/details/oid/{$contact_id}">{t}Go back to contact's profile{/t}</a></li>
				{/if}				
			{/if}
		{else}
			{if isset($contact_id) and isset($contact_id_key)}
				<li><a href="/contact/details/{$contact_id_key}/{$contact_id}">{t}Go back to contact's profile{/t}</a></li>
			{else}
				{if isset($invoice) and isset($invoice->client_id) and isset($invoice->client_id_key)}
					<li><a href="/contact/details/{$invoice->client_id_key}/{$invoice->client_id}">{t}Contact's profile{/t}</a></li>
				{/if}
			{/if}
		{/if}
		
		{* Go back to invoice *}
		{if {preg_match pattern="\/invoice_id\/[0-9]." subject=$site_url}}
			{if isset($invoice)}
				{if !{preg_match pattern="\/invoices\/edit\/invoice_id\/" subject=$site_url}}
					<li><a href="/invoices/edit/invoice_id/{$invoice->invoice_id}">{t}Go back to invoice{/t}</a></li>
				{/if}
			{else}
				{* there are some cases in which the $invoice obj is not passed to the view. So let's get the ID matching the url *}
				{if {preg_match pattern="[0-9].$" subject=$site_url}}
					{* debugging {$matches|print_r} *}
					{$invoice_id = $matches[0]}
					<li><a href="/invoices/edit/invoice_id/{$invoice_id}">{t}Go back to invoice{/t}</a></li>
				{/if}
			{/if}
		{/if}
		
		{if {preg_match pattern="\/payments\/payment_methods\/form" subject=$site_url}}
			<li><a href="/payments/payment_methods">{t}Show Payment Methods{/t}</a></li>
		{/if}

		{if {preg_match pattern="\/templates\/form\/type\/payment_receipts\/template_name" subject=$site_url}}
			<li><a href="/templates/index/type/payment_receipts">{t}Show Receipts Templates{/t}</a></li>
		{/if}
		
		{if {preg_match pattern="\/invoice_statuses\/form" subject=$site_url}}
			<li><a href="/invoice_statuses/index">{t}Show Invoice Statuses{/t}</a></li>
		{/if}

		{if {preg_match pattern="\/invoices\/invoice_groups\/form" subject=$site_url}}
			<li><a href="/invoices/invoice_groups/index">{t}Show Invoice Groups{/t}</a></li>
		{/if}		

		{if {preg_match pattern="\/templates\/form\/type\/invoices\/template_name" subject=$site_url}}
			<li><a href="/templates/index/type/invoices">{t}Show Invoice Templates{/t}</a></li>
		{/if}		
		
	</ul>
</div>

{if isset($invoice) and !{preg_match pattern="\/items\/form\/" subject=$site_url} and !{preg_match pattern="\/invoices\/quote_to_invoice\/invoice_id\/" subject=$site_url}}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: #ff9c00;">
	<h3 class="title_black">{t}Invoice Actions{/t}</h3>

	<ul class="quicklinks content toggle" >
		<li><a id="btn_add_new_item" href="{$site_url}?btn_add_new_item=true">
			{if $invoice->invoice_is_quote}
				{t}Add quote item{/t}
			{else}
				{t}Add invoice item{/t}
			{/if}
			</a>
		</li>
		{if $invoice->invoice_is_quote}
			<li><a id="btn_quote_to_invoice" href="/invoices/quote_to_invoice/invoice_id/{$invoice->invoice_id}">{t}Convert into invoice{/t}</a></li>
		{else}		
			<li><a id="btn_add_payment" href="/invoices/edit/invoice_id/{$invoice->invoice_id}?btn_add_payment=true">{t}Add payment{/t}</a></li>
		{/if}
		<li>
			<a href="javascript:void(0)" class="output_link" id="{$invoice->invoice_id}:{$invoice->client_id}:{$invoice->invoice_is_quote}">{t}Generate document{/t}</a>
		</li>
	
		{* //TODO this is a very good feature but to work correctly it needs the possibility to change the customer issue: #67 *}
		{*   
			<li><a id="btn_copy_invoice" href="{$site_url}?btn_copy_invoice=true">{t}Copy{/t}</a></li>
		*}		
	</ul>

</div>
{/if}

{if {preg_match pattern="\/calendar" subject=$site_url}}
	{if isset($base_url)}
		<div class="quicklinks content toggle" style="clear:right; float:right; display:inline; width: 235px;">
			<h3 style="margin-left: -25px;">{t}Calendar Legend{/t}</h3>
			<div style="" ><img src="{$base_url}assets/style/img/red.png" style="margin-top: 3px;"/> {t}overdue{/t}</div>
			<div style="" ><img src="{$base_url}assets/style/img/blue.png" style="margin-top: 3px;"/> {t}open{/t}</div>
			<div style="" ><img src="{$base_url}assets/style/img/green.png" style="margin-top: 3px;"/> {t}quotes{/t}</div> 
		</div>
	{/if}
{/if}

{* Payments *}
{if {preg_match pattern="\/payments\/index$" subject=$site_url}}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: #ff9c00;">
	<h3 class="title_black">{t}Payment Actions{/t}</h3>

	<ul class="quicklinks content toggle" >
		<li><a href="/payments/form/invoice_id/">{t}Add a Payment{/t}</a></li>		
	</ul>
</div>
{/if}

{* Payment methods *}
{if {preg_match pattern="\/payments\/payment_methods$" subject=$site_url}}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: #ff9c00;">

	<h3 class="title_black">{t}Payment Method Actions{/t}</h3>

	<ul class="quicklinks content toggle" >
		<li><a href="/payments/payment_methods/form">{t}Add Payment Method{/t}</a></li>		
	</ul>

</div>
{/if}

{* Payment Receipts *}
{if {preg_match pattern="\/templates\/index\/type\/payment_receipts" subject=$site_url}}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: #ff9c00;">

	<h3 class="title_black">{t}Payment Method Actions{/t}</h3>

	<ul class="quicklinks content toggle" >
		<li><a href="/templates/form/type/payment_receipts/template_name">{t}Add Payment Receipt Template{/t}</a></li>		
	</ul>
</div>
{/if}

{* Invoice Statuses *}
{if {preg_match pattern="\/invoice_statuses\/index$" subject=$site_url} or {preg_match pattern="\/invoice_statuses$" subject=$site_url}}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: #ff9c00;">

	<h3 class="title_black">{t}Invoice Statuses Actions{/t}</h3>

	<ul class="quicklinks content toggle" >
		<li><a href="/invoice_statuses/form">{t}Add Invoice Status{/t}</a></li>		
	</ul>
</div>
{/if}

{* Invoice Groups *}
{if {preg_match pattern="\/invoices\/invoice_groups$" subject=$site_url} or {preg_match pattern="\/invoices\/invoice_groups\/index$" subject=$site_url}}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: #ff9c00;">

	<h3 class="title_black">{t}Invoice Groups Actions{/t}</h3>

	<ul class="quicklinks content toggle" >
		<li><a href="/invoices/invoice_groups/form">{t}Add Invoice Group{/t}</a></li>		
	</ul>
</div>
{/if}

{* Invoice Templates *}
{if {preg_match pattern="\/templates\/index\/type\/invoices$" subject=$site_url}}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: #ff9c00;">

	<h3 class="title_black">{t}Invoice Templates Actions{/t}</h3>

	<ul class="quicklinks content toggle" >
		<li><a href="/templates/form/type/invoices/template_name/">{t}Add Invoice Template{/t}</a></li>		
	</ul>
</div>
{/if}