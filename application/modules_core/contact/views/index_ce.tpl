{assign 'people' $contacts.people}
{assign 'orgs' $contacts.orgs}
{assign 'total_number' $contacts.total_number}
{assign 'language' 'en'}

<script type="text/javascript">

	
	$(document).ready(function() {
		$("#show_add_person_link").click(function(){
			toggle_animate('add_person','first_name', '-68');
		});

		$("#show_add_organization_link").click(function(){
			toggle_animate('add_organization','organization_name', '-41');
		});

		$('#add_organization_form').submit(function() {
		
			organization_name = $('#organization_name').val();
			searched_value = organization_name;
			
			search({ 
					'searched_value': searched_value,
					'procedure':'searchOrganizationToAdd',
					'form_name':'add_organization_form',
					'form_type':'search',
					'object_name':'organization',
					'url':'/contact/form/add/organization',
					'hash':'set_here_the_hash' 
					});				
		    return false;			
		});	
				
		$('#last_name').keypress(function(event){
			
			//this intercepts the press enter on the second input box of the person form and performs a submit
			if (event.which == 13)
			{
				first_name = $('#first_name').val();
				if(first_name == '') return false;
				last_name = $('#last_name').val();
				if(last_name == '') return false;
				searched_value = first_name + ' ' + last_name;
				 
				search({ 
						'searched_value': searched_value,
						'first_name': first_name,
						'last_name': last_name,
						'procedure':'searchPersonToAdd',
						'form_name':'add_person_form',
						'form_type':'search',
						'object_name':'person',
						'url':'/contact/form/add/person',
						'hash':'set_here_the_hash' 
						});				
			    return false;
			}
			else
			   return true;
		});
			
	});
</script>

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
	
		{* add new person form *}
		<div style="width: 100%;">
			<h3 class="title_black">{"{t}people{/t}"|capitalize} 
				<span class="marker">&gt;&gt;</span><a id="show_add_person_link" href="#" style="font-size: 13px; color: white;">{t}add one{/t}</a>
				<div id="add_person" title="Form" style="display: none;">
					<form id="add_person_form" style="background-color: transparent;">
						<input title="{t}first name{/t}" style="margin-left: 160px; margin-right: 0px; width: 155px;"type="text" name="firstname" id="first_name" />
						<input title="{t}last name{/t}" style="margin-right: 2px; margin-left: 0px; width: 155px;"type="text" name="lastname" id="last_name"/>
					</form>
				</div>	
			</h3>
		</div>
		
	{if count($people) gt 0}
		<table class="table-clients">
		<tr class="columns_header">
			<td class="counter" style="background-color: black;">&nbsp;</td>
			<td class="columns_header">{t}Name{/t}</a></td>
			<td class="columns_header">{t}City{/t}</a></td>
			<td class="columns_header">{t}Telephone{/t}</td>
			<td class="columns_header">{t}Mobile{/t}</td>
		</tr>
		{foreach $people as $key => $person}
	    <tr class="hoverall">
	    	{assign 'url' value="$baseurl/contact/details/uid/{$person->uid}"}
	    	<td class="counter">{counter}</td>
	    	<td class="name">{a url=$url text=$person->cn|truncate:25:" [...]":true}</td>
			<td class="city">{$person->mozillaHomeLocalityName|truncate:24:" [...]":true|default:'-'}</td>
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
			<h3 class="title_black">{"{t}organizations{/t}"|capitalize}
				<!-- <input type="submit" class="mcbsb-regular-Button" name="" value="{t}Add{/t}" style="float: right; margin-top: 10px; margin-right: 10px;" />  -->
				<span class="marker">&gt;&gt;</span><a id="show_add_organization_link" href="#" style="font-size: 13px; color: white;">{t}add one{/t}</a>
				<div id="add_organization" title="Form" style="display: none;">
					<form id="add_organization_form" style="background-color: transparent;">
						<input title="{t}organization name{/t}" style="margin-left: 220px; margin-right: 0px; width: 280px;"type="text" name="organizationname" id="organization_name" />
					</form>
				</div>				
			</h3>
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
	    	<td class="city">{$organization->l|truncate:24:" [...]":true|default:'-'}</td>
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
