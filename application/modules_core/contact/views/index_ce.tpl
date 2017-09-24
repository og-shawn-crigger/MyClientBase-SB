{assign 'people' $contacts.people}
{assign 'orgs' $contacts.orgs}
{assign 'total_number' $contacts.total_number}
{assign 'language' 'en'}


<div class="grid_8" id="content_wrapper">
	<div class="contact_search" style="background-color: red;">
	<form method="post" action="">
		<div>
			{"{t}search{/t}"|capitalize}: <input title="{t}Search for name, organization name, vat number, phone, email, website{/t}" class="contact_search" type="text" name="search" id="search-box" value="">
			<input type="submit" class="mcbsb-regular-Button" name="reset" value="{t}Reset{/t}" style="margin-top: 10px; margin-right: 10px;" />
			{if $searched_string != ""}
			<span style="font-size: 0.6em; margin-left: 15px;">{t}last search{/t} "{$searched_string}"</span>
			<em  style="font-size: 0.6em; margin-left: 5px; color: green;">{t}produced{/t} {$total_number|default:0} {t}results{/t}</em>
			{/if}
		</div>
	</form>
	</div>
	
	<div class="content toggle no_padding">
	
		<div class="left-block">
			{if count($people) gt 0}
			
			{* add new person form *}
			<div style="width: 100%;">
				<h3 class="title_black">{"{t}people{/t}"|capitalize}</h3>
			</div>
			
			<table class="table-clients">
			<tr class="columns_header">
				<td class="counter" style="background-color: black;">&nbsp;</td>
				<td class="columns_header">{t}Name{/t}</a></td>
				<td class="columns_header">{t}City{/t}</a></td>
				{* <td class="columns_header">{t}Telephone{/t}</td> *}
				<td class="columns_header">{t}Mobile{/t}</td>
			</tr>
			{foreach $people as $key => $person}
		    <tr class="hoverall">
		    	{assign 'url' value="$baseurl/contact/details/uid/{$person->uid}"}
		    	<td class="counter">{counter}</td>
		    	<td class="name"><b>{a url=$url text=$person->cn|ucwords|truncate:25:" [...]":true}</b></td>
				<td class="city">{$person->mozillaHomeLocalityName|truncate:24:" [...]":true|default:'-'}</td>
				<td class="tel">{$person->mobile|default:'-'}</td>		
				{* <td class="tel">{$person->homePhone|default:'-'}</td> *}
		    </tr> 
		    {/foreach}
		    </table>
		
		{else}
			{if $made_search}
			<p>{t}No person found{/t}</p>
			{/if}
		{/if}
		</div>
		
		<div class="right-block">
			{if count($orgs) gt 0}    
			<div style="width: 100%;">
				<h3 class="title_black">{"{t}organizations{/t}"|capitalize}</h3>
			</div>
		
			<table class="table-orgs">
			
			<tr class="columns_header">
				<td class="counter" style="background-color: black;">&nbsp;</td>
				<td class="columns_header">{t}Name{/t}</td>
				<td class="columns_header">{t}City{/t}</td>
				<td class="columns_header">{t}Telephone{/t}</td>
				{* <td class="columns_header">{t}Mobile{/t}</td> *}
			</tr>
				
			{foreach $orgs as $key => $organization}
		    <tr class="hoverall">
		    	{assign 'url' value="$baseurl/contact/details/oid/{$organization->oid}"}
		    	<td class="counter">{counter}</td>
		    	<td class="name"><b>{a url=$url text=$organization->o|ucwords|truncate:30:" [...]":true}</b></td>
		    	<td class="city">{$organization->l|truncate:24:" [...]":true|default:'-'}</td>
		    	<td class="tel">{$organization->telephoneNumber|default:'-'}</td>
		    	{*<td class="tel">{$organization->oMobile|default:'-'}</td>*}
		    </tr>
		    {/foreach}
		    
		    </table>    
		{else}
			{if $made_search}
			<p>{t}No organization found{/t}</p>
			{/if}
		{/if}
		</div>
		
		{if $pager != ""}
		<div id="pagination">{$pager}</div>
		{/if}
	
	</div>
</div>