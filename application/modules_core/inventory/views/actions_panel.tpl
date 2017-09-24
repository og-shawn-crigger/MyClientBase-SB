{* inventory action panel *}

<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: gray;">

	<h3 class="title_black">{t}Main Actions{/t}</h3>

	<ul class="quicklinks content toggle" >
	
		{if !{preg_match pattern="\/inventory\/index$" subject=$site_url}}
			<li><a id="" href="/inventory/index">{t}Show Inventory Items{/t}</a></li>
		{/if}	
		{if !{preg_match pattern="\/inventory\/inventory_types$" subject=$site_url}}
			<li><a id="" href="/inventory/inventory_types">{t}Show Inventory Types{/t}</a></li>
		{/if}
		<li><a id="" href="/inventory/inventory_types/form">{t}Add Inventory Type{/t}</a></li>
		<li><a id="" href="/inventory/form">{t}Add Inventory Item{/t}</a></li>		
	</ul>

</div>