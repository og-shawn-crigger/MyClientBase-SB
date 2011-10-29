{assign 'people' $contacts.people}
{assign 'orgs' $contacts.orgs}
{assign 'language' 'it'}

<div class="contact_search">
<form method="post" action="">
	<div>
		
		{"{t}search{/t}"|capitalize}: <input class="contact_search" type="text" name="search" value="">
		{if $searched_string != ""}
		<em style="font-size: 0.7em; margin-left: 15px;">{t}last search{/t}: "{$searched_string}"</em>
		{/if}
	</div>
</form>
</div>

<div class="content toggle no_padding">

<div class="left-block">

	<div style="width: 100%;">
		<form method="post" action="">
		{if $made_search}
		<h3 class="title_black">{"{t}people{/t}"|capitalize} 
			<!-- 
			<input type="submit" class="myButton" name="" value="Add" style="float: right; margin-top: 10px; margin-right: 10px;" />
			 -->
		</h3>
		{/if}
		</form>
	</div>
	
{if count($people) gt 0}
	<table class="table-clients">
	{foreach $people as $key => $person}
    <tr class="hoverall">
    	{assign 'url' value="$baseurl/clients/details/uid/{$person->uid}"}
    	<td class="counter" rowspan="2">{counter}</td>
    	<td class="name">{a url=$url text=$person->cn|truncate:25:" [...]":true}</td>
    	<td class="city">{$person->mozillaHomeLocalityName|truncate:25:" [...]":true|default:'n.d.'}</td>
    	<td class="tel">{$person->mobile|default:'n.d.'}</td>
    </tr>
    <tr class="actions">
    	<td></td>
    	<td class="actions" colspan="4">{a url=$url text="{"{t}view{/t}"|capitalize}"} | {a url="$baseurl/clients/form/uid/{$person->uid}" text="{"{t}edit{/t}"|capitalize}"} | {a url="$baseurl/invoices/create/uid/{$person->uid}" text="{"{t}invoice{/t}"|capitalize}"} | {a url="$baseurl/invoices/create/quote/uid/{$person->uid}" text="{"{t}quote{/t}"|capitalize}"}</td>
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
		<form method="post" action="">
		{if $made_search}
		<h3 class="title_black">{"{t}organizations{/t}"|capitalize}
			<!--
			<input type="submit" class="myButton" name="" value="Add" style="float: right; margin-top: 10px; margin-right: 10px;" />
			-->
		</h3>
		{/if}
		</form>
	</div>

{if count($orgs) gt 0}    
	<table class="table-orgs">
	{foreach $orgs as $key => $organization}
    <tr class="hoverall">
    	{assign 'url' value="$baseurl/clients/details/oid/{$organization->oid}"}
    	<td class="counter" rowspan="2">{counter}</td>
    	<td class="name">{a url=$url text=$organization->o|truncate:32:" [...]":true}</td>
    	<td class="city">{$organization->l|truncate:25:" [...]":true|default:'n.d.'}</td>
    	<td class="tel">{$organization->telephoneNumber|default:'n.d.'}</td>
    </tr>
    <tr class="actions">
    	<td></td>
    	<td class="actions" colspan="3">{a url=$url text="{"{t}view{/t}"|capitalize}"} | {a url="$baseurl/clients/form/oid/{$organization->oid}" text="{"{t}edit{/t}"|capitalize}"} | {a url="$baseurl/invoices/create/oid/{$organization->oid}" text="{"{t}invoice{/t}"|capitalize}"} | {a url="$baseurl/invoices/create/quote/oid/{$organization->oid}" text="{"{t}quote{/t}"|capitalize}"}</td>
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
