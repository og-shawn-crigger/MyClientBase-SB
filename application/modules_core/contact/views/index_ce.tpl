{assign 'people' $contacts.people}
{assign 'orgs' $contacts.orgs}
{assign 'total_number' $contacts.total_number}
{assign 'language' 'en'}

<div class="contact_search">
<form method="post" action="">
	<div>
		{"{t}search{/t}"|capitalize}: <input class="contact_search" type="text" name="search" id="search-box" value="">
		{if $searched_string != ""}
		<em style="font-size: 0.7em; margin-left: 15px;">{t}last search{/t}: "{$searched_string}" {t}produced{/t} {$total_number|default:0} {t}results{/t}</em>
		{/if}
	</div>
</form>
</div>

<div class="content toggle no_padding">

<div class="left-block">

	<div style="width: 100%;">
		<form method="post" action="{$baseurl}/contact/form/add/person">
		{if $made_search}
		<h3 class="title_black">{"{t}people{/t}"|capitalize} 
			<input type="submit" class="mcbsb-regular-Button" name="" value="{t}Add{/t}" style="float: right; margin-top: 10px; margin-right: 10px;" />
		</h3>
		{/if}
		</form>
	</div>
	
{if count($people) gt 0}
	<table class="table-clients">
	<tr class="columns_header">
		<td class="counter" style="background-color: black;">&nbsp;</td>
		<td class="columns_header">{t}Name{/t}</td>
		<td class="columns_header">{t}City{/t}</td>
		<td class="columns_header">{t}Telephone{/t}</td>
		<td class="columns_header">{t}Mobile{/t}</td>
	</tr>
	{foreach $people as $key => $person}
    <tr class="hoverall">
    	{assign 'url' value="$baseurl/contact/details/uid/{$person->uid}"}
    	<td class="counter">{counter}</td>
    	<td class="name">{a url=$url text=$person->cn|truncate:25:" [...]":true}</td>
		<td class="city">{$person->mozillaHomeLocalityName|truncate:25:" [...]":true|default:'-'}</td>
		<td class="tel">{$person->mobile|default:'-'}</td>		
		<td class="tel">{$person->homePhone|default:'-'}</td>
		
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
	<div style="width: 100%;">
		<form method="post" action="{$baseurl}/contact/form/add/organization">
		{if $made_search}
		<h3 class="title_black">{"{t}organizations{/t}"|capitalize}
			<input type="submit" class="mcbsb-regular-Button" name="" value="{t}Add{/t}" style="float: right; margin-top: 10px; margin-right: 10px;" />
		</h3>
		{/if}
		</form>
	</div>

{if count($orgs) gt 0}    
	<table class="table-orgs">
	<tr class="columns_header">
		<td class="counter" style="background-color: black;">&nbsp;</td>
		<td class="columns_header">{t}Name{/t}</td>
		<td class="columns_header">{t}City{/t}</td>
		<td class="columns_header">{t}Telephone{/t}</td>
		<td class="columns_header">{t}Mobile{/t}</td>
	</tr>	
	{foreach $orgs as $key => $organization}
    <tr class="hoverall">
    	{assign 'url' value="$baseurl/contact/details/oid/{$organization->oid}"}
    	<td class="counter">{counter}</td>
    	<td class="name">{a url=$url text=$organization->o|truncate:30:" [...]":true}</td>
    	<td class="city">{$organization->l|truncate:25:" [...]":true|default:'-'}</td>
    	<td class="tel">{$organization->telephoneNumber|default:'-'}</td>
    	<td class="tel">{$organization->oMobile|default:'-'}</td>
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
