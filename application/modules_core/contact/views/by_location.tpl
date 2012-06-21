{assign 'people' $contacts.people}
{assign 'orgs' $contacts.orgs}
{assign 'total_number' $contacts.total_number}
{assign 'language' 'en'}


<div class="contact_search">
<form id="search-by-location" method="post" action="{$baseurl}/contact/by_location">
	<div>
		{"{t}city{/t}"|capitalize}: 
		<input title="{t}Set a city{/t}" class="contact_search" type="text" name="city" id="search-box" value="" style="width: 180px">
		&nbsp;{"{t}state{/t}"|capitalize}: 
		<input title="{t}Set a state{/t}" class="contact_search" type="text" name="state" id="search-state" value="" style="width: 180px">
		<input type="submit" class="mcbsb-regular-Button" name="" value="{t}Search{/t}" />
		{if $searched_string != ""}
		<span style="font-size: 0.6em; margin-left: 15px;">"{$searched_string}"</span>
		<em  style="font-size: 0.6em; margin-left: 5px; color: green;">{t}produced{/t} {$total_number|default:0} {t}results{/t}</em>
		{/if}
	</div>
</form>
</div>
<div style="margin-bottom: 15px;">The State field is mandatory. <strong>Note</strong>: this page is in early stage development</div>
{if $searched_string}
<div class="content toggle no_padding">
	{$width=350}
	<div class="left-block" style="width: 585px; background-color: transparent;">
		<div style="overflow: auto; width: 100%">
			<div style="width: {$width}px; float: left;">
				<iframe width="{$width}px" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
					src="http://maps.google.com/maps?q={$searched_string}&amp;ie=UTF8&amp;output=embed">
				</iframe>
			</div>
			<div style="width: 230px; float: right;">
				<h3 class="title_black">{"{t}statistics{/t}"|capitalize} </h3>
				<table class="table-clients" style="background-color: white;">
					<tr style="border-left: 1px solid #d8d8d8;">
					    	<td>{t}City{/t}</td>
					    	<td>P</td>
					    	<td>O</td>
					    	<td>T</td>		
					</tr>						
				{foreach $statistics as $city => $stats}
				    <tr class="hoverall" style="border-left: 1px solid #d8d8d8;">
				    	<td style="width: 100%;">{$city|truncate:23:" [...]":true}</td>
				    	<td>{$stats.people}</td>
				    	<td>{$stats.organizations}</td>
				    	<td>{$stats.total}</td>		
				    </tr> 
				{/foreach}
				</table>
			</div>
		</div>
	</div>
	
	<div class="right-block" style="width: 505px; background-color: transparent;">
		<div style="overflow: auto; width: 100%">
			<div style="width: 250px; float: left;">
				{if $made_search}<h3 class="title_black">{"{t}people{/t}"|capitalize} </h3>{/if}
		
			
				{if count($people) gt 0}
					<table class="table-clients" style="background-color: white;">
					{foreach $people as $key => $person}
				    <tr class="hoverall" style="border-left: 1px solid #d8d8d8;">
				    	{assign 'url' value="$baseurl/contact/details/uid/{$person->uid}"}
				    	<td class="name">{a url=$url text=$person->cn|truncate:25:" [...]":true}</td>		
				    </tr> 
				    {/foreach}
				    </table>
				
				{else}
					{if $made_search}
					<p>{t}No person found{/t}</p>
					{/if}
				{/if}
			</div>
		
		
			<div style="width: 250px; float: right;">
				{if $made_search}<h3 class="title_black">{"{t}organizations{/t}"|capitalize}</h3>{/if}
			
		
				{if count($orgs) gt 0}    
					<table class="table-orgs" style="background-color: white;">
					{foreach $orgs as $key => $organization}
				    <tr class="hoverall" style="border-left: 1px solid #d8d8d8;">
				    	{assign 'url' value="$baseurl/contact/details/oid/{$organization->oid}"}
				    	<td class="name">{a url=$url text=$organization->o|truncate:30:" [...]":true}</td>
				    </tr>
				    {/foreach}
				    </table>    
				{else}
					{if $made_search}
					<p>{t}No organization found{/t}</p>
					{/if}
				{/if}
			</div>
		</div>
		{if $pager != ""}
		<div id="pagination">{$pager}</div>
		{/if}
	</div>
</div>
{/if}