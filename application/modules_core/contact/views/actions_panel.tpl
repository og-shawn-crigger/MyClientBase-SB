{if isset($contact)}
	{if {preg_match pattern="dueviPerson" subject=$contact->objectClass}}
		{$contact_ref = $contact->cn}
		{$contact_id = $contact->uid}
		{$contact_id_key = "uid"}
		{$object_type = 'person'}
	{/if}		
	
	{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass}}
		{$contact_ref = $contact->o}
		{$contact_id = $contact->oid}
		{$contact_id_key = "oid"}
		{$object_type = 'organization'}
	{/if}					
{/if}

<script type="text/javascript">
	$(document).ready(function() {

		
		$("#show_organization_link").click(function(){
			toggle_animate('search_organization','input_search', '-5');
		});

		$('#search_organization_form').submit(function() {
			search({ 'procedure':'personToOrganizationMembership', 'form_name': 'search_organization_form', 'form_type':'search','object_name':'organization','related_object_name':'{$object_type}','related_object_id':'{$contact_id}','url':'/ajax/associate/','hash':'set_here_the_hash' });
			return false;
		});
		
		$("#show_add_person_link").click(function(){
			console.log('pd');
			toggle_animate('add_person','first_name', '-10');
		});

		$("#show_add_organization_link").click(function(){
			toggle_animate('add_organization','organization_name', '5');
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
				last_name = $('#last_name').val();
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

<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: gray;">
	
	<h3 class="title_black">{t}Main Actions{/t}</h3>
	
	<ul class="quicklinks content toggle" >
		{if !{preg_match pattern="\/contact\/index$" subject=$site_url} and !{preg_match pattern="\/contact$" subject=$site_url}}
			<li><a id="back_to_profile" href="/contact/">{t}Search contact{/t}</a></li>
		{/if}
		<li>
			<a id="show_add_person_link" href="#">{t}Add a person{/t}</a>
			<div id="add_person" title="Form" style="display: none;">		
				<form id="add_person_form" style="background-color: transparent; margin-top: 0px; margin-bottom: 0px;">
					<input title="{t}first name{/t}" style="margin-left: 0px; margin-right: 0px; width: 100px;"type="text" name="firstname" id="first_name" />
					<input title="{t}last name{/t}" style="margin-right: 5px; margin-left: 0px; width: 100px;"type="text" name="lastname" id="last_name"/>
				</form>
				<p style="margin-top: -20px; margin-bottom: 0px;">
				<span style="font-size: 10px; color: gray; font-style: italic; margin-left: 5px;">{t}First Name{/t}</span>
				<span style="font-size: 10px; color: gray; font-style: italic; margin-left: 65px;">{t}Last Name{/t}</span>
				</p>
			</div>
		</li>

		<li>
			<a id="show_add_organization_link" href="#" >{t}Add an organization{/t}</a>
			<div id="add_organization" title="Form" style="display: none;">
				<form id="add_organization_form" style="background-color: transparent;">
					<input title="{t}organization name{/t}" style="margin-left: 0px; margin-right: 0px; width: 220px;"type="text" name="organizationname" id="organization_name" />
				</form>
			</div>				
		</li>		
	</ul>
</div>

{if isset($contact_id)}
	<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px; background-color: #ff9c00;">
		<h3 class="title_black">{t}Contact Actions{/t}</h3>
	
		<ul class="quicklinks content toggle" >
			
			{if !$profile_view}<li><a id="back_to_profile" href="/index.php/contact/details/{$contact_id_key}/{$contact_id}">{t}Back to profile{/t}</a></li>{/if}
			
			{if $profile_view && $contact_id != ""}
				<li> <a id="edit_profile" href="/index.php/contact/form/{$contact_id_key}/{$contact_id}">{t}Edit profile{/t}</a></li>
			{/if}
			
			{if $profile_view && $contact_id != ""}
				<li><a href="#" onClick="jqueryForm({ 'form_type':'form','object_name':'location','related_object_name':'{$object_type}','related_object_id':'{$contact_id}','hash':'set_here_the_hash' })">{t}Add location{/t}</a></li>
			{/if}
			
			{if $object_type == 'person'}
				<li><a id="show_organization_link" href="#">{t}Associate Organization{/t}</a></li>
				<div id="search_organization" title="Form" style="display: none;">
					<form id="search_organization_form" style="background-color: white;">
						<input title="{t}search for name, vat, phone, email, website{/t}" style="margin-right: 5px; width: 225px;"type="text" name="input_search" id="input_search" />
						<p style="font-size: 10px; color: gray; font-style: italic;">{t}search for name, vat, phone, email, website{/t}</p>
					</form>
				</div>
			{/if}
			
			{if $invoice_module_is_enabled}
				<li><a href="/tasks/form/{$contact_id_key}/{$contact_id}?btn_add=true">{t}Create a task{/t}</a></li>
			{/if}
			
			{if $invoice_module_is_enabled}
				<li><a href="/invoices/create/{$contact_id_key}/{$contact_id}/quote/">{t}Create freehand quote{/t}</a></li>
				<li><a href="/invoices/create/{$contact_id_key}/{$contact_id}">{t}Create freehand invoice{/t}</a></li>
			{/if}
			<!--
			<li>
					<form method="post" action="" style="display: inline;">
					<input type="submit" name="btn_edit_client" style="float: right; margin-top: 10px; margin-right: 10px;" value="{citranslate lang=$language text='edit_client'}" />
	                <input type="submit" name="btn_add_invoice" style="float: right; margin-top: 10px; margin-right: 10px;" value="{citranslate lang=$language text='create_invoice'}" />
					<input type="submit" name="btn_add_quote" style="float: right; margin-top: 10px; margin-right: 10px;" value="{citranslate lang=$language text='create_quote'}" />
					</form>
			</li>
			 -->						
		</ul>
		
	</div>
{/if}