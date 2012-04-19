{*
<pre>
{$object|print_r}
</pre>
*}

{if count($orgs) gt 0}
	<p>
	{t}Maybe the contact you are trying to add is already present in the system{/t}.
	</p>
{/if}
<div id="{$div_id}" title="Form">
	
	<form action="{$url}" method="post" name="{$form_name}" id="{$form_name}">
	{* these are the submitted values *}
	<fieldset style="padding-right: 5px;">
	
	<input type="hidden" name="organization_name" value="{$searched_value}" />
	
	{*
	<input type="submit" name="button" value="" id="fakesubmit" style="background-color: transparent; border: 0px; width: 0px; height: 0px;">
	*}
		{if count($orgs) gt 0}
			<p>{t}Displaying{/t} {$results_got_number} {if $results_got_number == 1}{t}organization{/t}{else}{t}organizations{/t}{/if} {t}on{/t} {$results_number} {t}found{/t}.</p>    
			<table class="table-orgs">
			<tr style="font-weight: bold; background-color: gray;">
				<td>{t}Name{/t}</td>
				<td>{t}City{/t}</td>
				<td>{t}Telephone{/t}</td>
				<td>{t}Mobile{/t}</td>
				<td>{t}Email{/t}</td>
			</tr>	
			{foreach $orgs as $key => $org}
		    <tr class="hoverall">
		    	{assign 'url' value="$baseurl/contact/details/oid/{$org->oid}"}
		    	{assign 'urltitle' value="See {$org->o}'s profile"}
		    	<td >{a url=$url title=$urltitle target="_blank" text=$org->o|truncate:20:" [...]":true}</td>
		    	<td>{$org->l|truncate:18:" [...]":true|default:'-'}</td>
		    	<td>{$org->telephoneNumber|truncate:12:" [...]":true|default:'-'}</td>
		    	<td>{$org->oMobile|truncate:12:" [...]":true|default:'-'}</td>
		    	<td><a href="mailto:{$org->omail}" title="{t}Sends an email{/t}">{$org->omail|truncate:20:" [...]":true|default:'-'}</a></td>
		    </tr>
		    {/foreach}
		    </table>    
		{else}
			<p>{t}No organization found{/t}. {t}You can safely add this contact{/t}.</p>
		{/if}
	</fieldset>
	</form>
	
	{if $results_number > 0}
		<p style="margin-top: 10px; margin-bottom: 0px;">
			{t}If you are sure that this contact is not yet in the system, please click{/t} OK
		</p>
	{/if}
	
	{if $results_number > $results_got_number}
		<p style="margin-top: 10px; margin-bottom: 0px;">{t}Your research produced too many results: refine your search or click{/t} <a title="{t}Get more results{/t}" href="/contact/search/{$searched_value}" style="background-color: green; color: white;">{t}HERE{/t}</a> {t}to see all the results.{/t}</p>
	{/if}	
</div>	