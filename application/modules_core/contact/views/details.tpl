
{assign 'contact' $contact}
{assign 'properties' $contact->properties}
{assign 'language' 'it'}
{assign 'baseurl' $baseurl}
{assign 'invoices_html' $invoices_html}

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">
		<div>
			{if {preg_match pattern="dueviPerson" subject=$contact->objectClass}}
				<h3 class="title_black"><span style="font-size: 12px;">{t}Person{/t}: </span>{$contact->cn}</h3>
			{/if}		
			
			{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass}}
				<h3 class="title_black"><span style="font-size: 12px;">{t}Organization{/t}: </span>{$contact->o}</h3>
			{/if}					
		</div>
	
		<div class="content toggle" >
<!-- 
<pre>
{$contact|print_r}
</pre>
 -->
			<div id="tabs" >
			
				<!-- TABS -->				
				<ul>
					<li><a href="#tab_client">{t}Contact{/t}</a></li>

					{if {preg_match pattern="dueviPerson" subject=$contact->objectClass} and {$contact_orgs|count} >0}
						<li><a href="#tab_memberOf">{t}Member of{/t}</a></li>
					{/if}
					
					{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass}}
						<li><a href="#tab_contacts">{t}Members{/t}</a></li>
					{/if}
					
					<!-- 
					<li><a href="#tab_invoices">{citranslate lang=$language text='invoices'}</a></li>
					 -->
				</ul>
				
				
				
				<div id="tab_client" >
					<table class="contact-details-left" style="border: 1px solid #e8e8e8; width: 98%;">
					<column width>
						{foreach $contact->show_fields as $key => $property_name}
							{if $contact->$property_name != ""}
							<tr valign="top" style="background-color: {cycle values="#FFF,#e8e8e8"};">
								<td class="field" style="width: 30%;">{t}{$property_name}{/t}</td>
								<td class="value">
									{$already_wrote=0}
									<!-- particular cases -->
									{if $property_name=="mail" || $propery_name=="omail"}
										<a href="mailto:{$contact->$property_name}">{$contact->$property_name|wordwrap:60:"<br/>":true}</a>
										{$already_wrote=1}
									{/if}
									
									<!-- default case -->
									{if $already_wrote==0} 
										{$contact->$property_name|wordwrap:60:"<br/>":true}
									{/if}
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
				{if {preg_match pattern="dueviPerson" subject=$contact->objectClass}}
				<div id="tab_memberOf">
					{if $contact_orgs}
						<!-- 
							<pre>
							{$contact_orgs|print_r}
							</pre>
						 -->
						 
						{foreach $contact_orgs as $key => $org}
						<h3 style="margin-left: -15px;">
						<a href="index.php/contact/details/oid/{$org->oid}" target="_blank">{$org->o}</a>
						{if $contact->oAdminRDN==$org->oid}
						<img src="/images/gold_star_20.jpg" style="width: 20px; margin-left: 10px;" />
						<span style="font-size: 12px; margin-left: 3px;">({t}with role of manager{/t})</span>
						{/if}
						</h3>
						<table class="contact-details-left" style="border: 1px solid #e8e8e8; width: 98%; margin-left: 15px;">
							{foreach $org->show_fields as $key => $property_name}
								{if $org->$property_name != ""}
								<tr valign="top" style="background-color: {cycle values="#FFF,#e8e8e8"};">
									<td class="field" style="width: 30%;">{t}{$property_name}{/t}</td>
									<td class="value"> 
										{$org->$property_name|wordwrap:75:" ":true}
									</td>
								</tr>
								{/if}
							{/foreach}										
						</table><br/>
						{/foreach}
					{/if}
				</div>				
				{/if}
				
				{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass}}
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
