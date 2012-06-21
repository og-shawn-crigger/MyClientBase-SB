{* templates actions panel *}

<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: gray;">
	<h3 class="title_black">{t}Main Actions{/t}</h3>

	<ul class="quicklinks content toggle" >
	
		<li><a href="/invoices/index">{t}View Invoices{/t}</a></li>
		{if {preg_match pattern="templates\/index\/type\/invoices" subject=$site_url}}
			<li><a id="btn_create_template" href="{$site_url}?btn_create_template=true">{t}Add template{/t}</a></li>
		{/if}
	</ul>
</div>