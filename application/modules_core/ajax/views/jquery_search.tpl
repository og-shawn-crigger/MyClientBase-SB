{*
<pre>
{$object|print_r}
</pre>
*}
<script type="text/javascript">
$(function() {
    $('[type=radio]').live("click", function() {
    	selected_radio = $(this).val(); 
        //console.log('click' + selected_radio);
    });
});
</script>

<div id="{$div_id}" title="Form">
	
	<form name="{$form_name}">
	<fieldset style="padding-right: 5px;">			
		{if count($orgs) gt 0}
			<p>{t}Displaying{/t} {$results_got_number} {t}organizations on{/t} {$results_number} {t}found{/t}.</p>    
			<table class="table-orgs">
			<tr style="font-weight: bold; background-color: gray;">
				<td>&nbsp;</td>
				<td>{t}Name{/t}</td>
				<td>{t}City{/t}</td>
				<td>{t}Telephone{/t}</td>
				<td>{t}Mobile{/t}</td>
				<td>{t}Vat Number{/t}</td>
				<td>{t}Email{/t}</td>
			</tr>	
			{foreach $orgs as $key => $organization}
		    <tr class="hoverall">
		    	<td class="counter" valign="middle"><input type="radio" name="radiogroup" id="radio_{$organization->oid}" value="{$organization->oid}"></td>
		    	{assign 'url' value="$baseurl/contact/details/oid/{$organization->oid}"}
		    	{assign 'urltitle' value="See {$organization->o}'s profile"}
		    	<td >{a url=$url title=$urltitle target="_blank" text=$organization->o|truncate:20:" [...]":true}</td>
		    	<td>{$organization->l|truncate:18:" [...]":true|default:'-'}</td>
		    	<td>{$organization->telephoneNumber|truncate:12:" [...]":true|default:'-'}</td>
		    	<td>{$organization->oMobile|truncate:12:" [...]":true|default:'-'}</td>
		    	<td>{$organization->vatNumber|truncate:12:" [...]":true|default:'-'}</td>
		    	<td><a href="mailto:{$organization->omail}" title="{t}Sends an email{/t}">{$organization->omail|truncate:20:" [...]":true|default:'-'}</a></td>
		    </tr>
		    {/foreach}
		    </table>    
		{else}
			{if $made_search}
			<p>{t}No organization found{/t}</p>
			{/if}
		{/if}
	</fieldset>	
	</form>
	
	{if $results_number > $results_got_number}
		<p style="margin-top: 10px; margin-bottom: 0px;">{t}Your research produced too many results: refine your search or click{/t} <a title="{t}Get more results{/t}" href="/contact/search/{$searched_value}" style="background-color: green; color: white;">{t}HERE{/t}</a> {t}to see all the results.{/t}</p>
	{/if}	
</div>	
