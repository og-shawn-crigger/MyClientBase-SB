{* users action panel *}

{* MAIN ACTIONS *}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: gray;">
	<h3 class="title_black">{t}Main Actions{/t}</h3>

	<ul class="quicklinks content toggle" >

		{* global items: these items are always visible except the page destination of the link *}
		
			<li><a href="/settings">{t}System Settings{/t}</a></li>
			
			{if !{preg_match pattern="\/users$" subject=$site_url} and !{preg_match pattern="\/users\/index$" subject=$site_url}}
				<li><a href="/users/index">{t}Show User Accounts{/t}</a></li>
			{/if}			
		{* end global items *}
		
	</ul>
</div>

{if !{preg_match pattern="\/users\/form" subject=$site_url}}
<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: #ff9c00;">
	<h3 class="title_black">{t}User Actions{/t}</h3>

	<ul class="quicklinks content toggle" >
		{if {preg_match pattern="\/users$" subject=$site_url} or {preg_match pattern="\/users\/index$" subject=$site_url}}
			<li><a href="/users/form">{t}Create User Account{/t}</a></li>
		{/if}
	</ul>

</div>
{/if}
