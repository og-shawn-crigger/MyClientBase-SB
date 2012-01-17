{assign 'contact' $contact}
{assign 'properties' $contact->properties}
{assign 'language' 'it'}
{assign 'baseurl' $baseurl}
{assign 'invoices_html' $invoices_html}

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">
		<div>
			<h3 class="title_black">{$contact->cn}</h3>
		</div>
	
		<div class="content toggle" >

			<div id="tabs" >
				
				<ul>
					<li><a href="#tab_client">{t}Contact{/t}</a></li>
					{if $contact->objectClass == "dueviOrganization"}
					<li><a href="#tab_contacts">{t}Members{/t}</a></li>
					{/if}
					<!-- 
					<li><a href="#tab_invoices">{citranslate lang=$language text='invoices'}</a></li>
					 -->
				</ul>
				<div id="tab_client" >
					<table class="contact-details-left">
						{foreach $contact->show_fields as $key => $property_name}
							{if $contact->$property_name != ""}
							<tr valign="top" style="background-color: {cycle values="#FFF,#e8e8e8"};">
								<td class="field">{t}{$property_name}{/t}</td>
								<td class="value"> 
									{$contact->$property_name|wordwrap:60:"<br/>":true}
								</td>
							</tr>
							{/if}
						{/foreach}
					</table>
						<!-- 
						</td><td>							
						<table class="contact-details-right">
							<tr>
								<td class="field">{citranslate lang=$language text='invoices'}:</td>
								<td class="value">&nbsp;</td>
							</tr>
	
							<tr>
								<td class="field">{citranslate lang=$language text='total_paid'}: </td>
								<td>&nbsp;</td>
							</tr>
	
							<tr>
								<td class="field">{citranslate lang=$language text='total_balance'}: </td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class="field">{citranslate lang=$language text='tax_id_number'}: </td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class="field">{citranslate lang=$language text='notes'}: </td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</td>
					 -->
				</div>
				
				{if $contact->objectClass == "dueviOrganization"}
				<div id="tab_contacts">
					ORGANIZATION CONTACTS - to be finished -
				</div>
				{/if}
				<!-- 
						<div id="tab_invoices">
							{$invoices_html}
						</div>
	 					-->
			</div>
			
		</div>
	
	</div>

</div>