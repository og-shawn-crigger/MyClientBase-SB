{* this creates the menu on the top of the page *}
{if is_array($menu_items)}
	{foreach $menu_items as $parent => $item}
		<li>
			{if isset($item['href'])}
				{$url = $item['href']}
			{else}
				{$url = "#"}
			{/if}
			
			{* writes the parent item *}
			<a href="/{$url}">{t}{$parent|ucwords}{/t}</a>
			
			{if isset($item['submenu'])}
				{$submenu = $item['submenu']}
				{if $submenu|count > 0}
					<ul>
					{foreach $submenu as $key => $subitem}
						{if isset($subitem['href'])} 
							{$url = $subitem['href']} 
						{else} 
							{$url = "#"}
						{/if}
						{* writes the parent item *}
						<li><a href="/{$url}">{t}{$subitem['title']|ucwords}{/t}</a></li>
					{/foreach}
					</ul>
				{/if}
			{/if}
		</li>
	{/foreach}	
{/if}
