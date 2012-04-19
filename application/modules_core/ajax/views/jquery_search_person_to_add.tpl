{*
<pre>
{$object|print_r}
</pre>
*}

{if count($people) gt 0}
	<p>
	{t}Maybe the contact you are trying to add is already present in the system{/t}.
	</p>
{/if}
<div id="{$div_id}" title="Form">
	
	<form action="{$url}" method="post" name="{$form_name}" id="{$form_name}">
	{* these are the submitted values *}
	<fieldset style="padding-right: 5px;">
	
	<input type="hidden" name="first_name" value="{$first_name}" />
	<input type="hidden" name="last_name" value="{$last_name}" />
	<input type="submit" name="button" value="" id="fakesubmit" style="background-color: transparent; border: 0px; width: 0px; height: 0px;">
	
		{if count($people) gt 0}
			<p>{t}Displaying{/t} {$results_got_number} {if $results_got_number == 1}{t}person{/t}{else}{t}persons{/t}{/if} {t}on{/t} {$results_number} {t}found{/t}.</p>    
			<table class="table-orgs">
			<tr style="font-weight: bold; background-color: gray;">
				<td>{t}Name{/t}</td>
				<td>{t}City{/t}</td>
				<td>{t}Telephone{/t}</td>
				<td>{t}Mobile{/t}</td>
				<td>{t}Email{/t}</td>
			</tr>	
			{foreach $people as $key => $person}
		    <tr class="hoverall">
		    	{assign 'url' value="$baseurl/contact/details/uid/{$person->uid}"}
		    	{assign 'urltitle' value="See {$person->cn}'s profile"}
		    	<td >{a url=$url title=$urltitle target="_blank" text=$person->cn|truncate:20:" [...]":true}</td>
		    	<td>{$person->mozillaHomeLocalityName|truncate:18:" [...]":true|default:'-'}</td>
		    	<td>{$person->homePhone|truncate:12:" [...]":true|default:'-'}</td>
		    	<td>{$person->mobile|truncate:12:" [...]":true|default:'-'}</td>
		    	<td><a href="mailto:{$person->mail}" title="{t}Sends an email{/t}">{$person->mail|truncate:20:" [...]":true|default:'-'}</a></td>
		    </tr>
		    {/foreach}
		    </table>    
		{else}
			<p>{t}No person found{/t}. {t}You can safely add this contact{/t}.</p>
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

{*
{if $results_number == 0}
	{$form_id = "#$form_name"}
	
	<script type="text/javascript">
		$(document).ready(function(){
			console.log('submit: ' + "{$form_id}" + "{$form_name}");	
//			$("{$form_id}").submit();
//			$('#fakesubmit').submit();
//			postFormToAjax("{$url}",'jsonp','POST',"{$form_name}","person","","","","");
		});
	</script>
{/if}
*}