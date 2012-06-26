{* task action panel *}

{* MAIN ACTIONS *}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: gray;">
	<h3 class="title_black">{t}Main Actions{/t}</h3>

	<ul class="quicklinks content toggle" >

		{* global items: these items are always visible except the page destination of the link *}
			{if !{preg_match pattern="\/tasks$" subject=$site_url}}
				<li><a href="/tasks/index">{t}Show Tasks{/t}</a></li>
			{/if}
			<li><a href="#">{t}Show Calendar View{/t}</a></li>			
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
			{if isset($task->client_id) and isset($task->client_id_key)}
				<li><a href="/contact/details/{$task->client_id_key}/{$task->client_id}">{t}Go back to contact's profile{/t}</a></li>
			{/if}
		{/if}
	</ul>
</div>

{if isset($task) and {preg_match pattern="\/tasks\/form\/" subject=$site_url}}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: #ff9c00;">
	<h3 class="title_black">{t}Task Actions{/t}</h3>

	<ul class="quicklinks content toggle" >
		{if !empty($task->task_id) and !empty($task->complete_date)}
		<li><a id="btn_create_mti" href="/tasks/create_invoice/task_id/{$task->task_id}">{t}Create Invoice{/t}</a></li>
		{/if}
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


