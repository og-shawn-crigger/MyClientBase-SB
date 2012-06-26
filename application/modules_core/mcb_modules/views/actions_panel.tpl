{* tax_rates action panel *}

{* MAIN ACTIONS *}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: gray;">
	<h3 class="title_black">{t}Main Actions{/t}</h3>

	<ul class="quicklinks content toggle" >

		{* global items: these items are always visible except the page destination of the link *}
		
			<li><a href="/settings">{t}System Settings{/t}</a></li>
			
			{if !{preg_match pattern="\/mcb_modules\/custom$" subject=$site_url} and !{preg_match pattern="\/mcb_modules\/custom\/index$" subject=$site_url}}
				<li><a href="/mcb_modules/custom">{t}Show Custom Modules{/t}</a></li>
			{/if}

			{if !{preg_match pattern="\/mcb_modules\/core$" subject=$site_url} and !{preg_match pattern="\/mcb_modules\/core\/index$" subject=$site_url}}
				<li><a href="/mcb_modules/core">{t}Show Core Modules{/t}</a></li>
			{/if}			
		{* end global items *}

	</ul>
</div>