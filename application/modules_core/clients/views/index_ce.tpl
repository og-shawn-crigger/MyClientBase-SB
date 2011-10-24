
{assign 'people' $contacts.people}
{assign 'orgs' $contacts.orgs}
{assign 'language' 'it'}
{assign 'button_add' $button_add}
{assign 'baseurl' $baseurl}
{assign 'pager' $pager}
{assign 'made_search' $made_search}

<div class="contact_search">
<form method="post" action="">
	<div>
		<p>{citranslate lang=$language text='Search'}: <input class="contact_search" type="text" name="search" value="">
	</div>
</form>
</div>

<div class="content toggle no_padding">

<div class="left-block">

	<div style="width: 100%;">
		<form method="post" action="">
		{if $made_search}
		<h3 class="title_black">{citranslate lang=$language text='people'} 		
			<input type="submit" class="myButton" name="" value="Add" style="float: right; margin-top: 10px; margin-right: 10px;" />
		</h3>
		{/if}
		</form>
	</div>
	
{if count($people) gt 0}
	<table class="table-clients">
	{foreach $people as $key => $person}
    <tr class="hoverall">
    	{assign 'url' value="$baseurl/clients/index/uid/{$person->uid}"}
    	<td class="counter" rowspan="2">{counter}</td>
    	<td class="name">{a url=$url text=$person->cn|truncate:25:" [...]":true}</td>
    	<td class="city">{$person->mozillaHomeLocalityName|truncate:25:" [...]":true|default:'n.d.'}</td>
    	<td class="tel">{$person->mobile|default:'n.d.'}</td>
    </tr>
    <tr class="actions">
    	<td></td>
    	<td class="actions" colspan="4">{a url=$url text="View"} | {a url=$url text="Edit"} | {a url=$url text="Invoice"} | {a url=$url text="Quote"}</td>
    </tr>
    {/foreach}
    </table>

{else}
	{if $made_search}
	<p>{citranslate lang=$languge text="No person found"}</p>
	{/if}
{/if}
</div>

<div class="right-block">

	<div style="width: 100%;">
		<form method="post" action="">
		{if $made_search}
		<h3 class="title_black">{citranslate lang=$language text='organizations'} 		
			<input type="submit" class="myButton" name="" value="Add" style="float: right; margin-top: 10px; margin-right: 10px;" />
		</h3>
		{/if}
		</form>
	</div>

{if count($orgs) gt 0}    
	<table class="table-orgs">
	{foreach $orgs as $key => $organization}
    <tr class="hoverall">
    	{assign 'url' value="$baseurl/clients/index/oid/{$organization->oid}"}
    	<td class="counter" rowspan="2">{counter}</td>
    	<td class="name">{a url=$url text=$organization->o|truncate:32:" [...]":true}</td>
    	<td class="city">{$organization->l|truncate:25:" [...]":true|default:'n.d.'}</td>
    	<td class="tel">{$organization->telephoneNumber|default:'n.d.'}</td>
    </tr>
    <tr class="actions">
    	<td></td>
    	<td class="actions" colspan="3">{a url=$url text="View"} | {a url=$url text="Edit"} | {a url=$url text="Invoice"} | {a url=$url text="Quote"}</td>
    </tr>    
    {/foreach}
    </table>    
{else}
	{if $made_search}
	<p>{citranslate lang=$languge text="No organization found"}</p>
	{/if}
{/if}
</div>

{if $pager != ""}
<div id="pagination">{$pager}</div>
{/if}

</div>