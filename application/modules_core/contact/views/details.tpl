
{assign 'contact' $contact}
{assign 'properties' $contact->properties}
{assign 'language' 'it'}
{assign 'baseurl' $baseurl}
{assign 'invoices_html' $invoices_html}

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">
		<div>
			{if {preg_match pattern="dueviPerson" subject=$contact->objectClass}}
				{$contact_ref = $contact->cn}
				{$contact_id = $contact->uid}
				{$contact_id_key = "uid"}
				<h3 class="title_black"><span style="font-size: 12px;">{t}Person{/t}: </span>{$contact->cn}</h3>
			{/if}		
			
			{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass}}
				{$contact_ref = $contact->o}
				{$contact_id = $contact->oid}
				{$contact_id_key = "oid"}
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

					<li><a href="#tab_locations">{t}Locations{/t}</a></li>
										
					{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass}}
						<li><a href="#tab_contacts">{t}Members{/t}</a></li>
					{/if}
					
					<!-- 
					<li><a href="#tab_invoices">{citranslate lang=$language text='invoices'}</a></li>
					 -->
				</ul>
				
				
				
				<div id="tab_client" >

					<table border="1" class="none" style="border: 1px solid #e8e8e8; width: 98%; margin-bottom: 2px;">
						{counter start=0 skip=1 assign="count"}
						{foreach $contact->show_fields as $key => $property_name}
						
							{if $contact->$property_name != ""}
							<tr valign="top" style="height: 5px; background-color: {cycle values="#FFF,#e8e8e8"};">
								
								<td class="field" style="valign: middle; width: 30%;">{t}{$property_name}{/t}</td>

								{if $count <= 4} 
									<td class="value" style="valign: middle;">
								{else}
									<td class="value" colspan="2"  style="valign: middle;">
								{/if}								
								
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

								{if $count == 1} 
									<td rowspan="4" align="center" style="width: 100px; background: white;">
										<span style="font-size: 12px; align: center; margin-bottom: 0px;">&nbsp;</span>
										{if $contact->jpegPhoto}
											<img alt="thumbnail" src="data:image/jpeg;base64,{$contact->jpegPhoto|base64_decode}">
										{else}
											<img style="border: 1px solid black; width: 100px; height: 100px; margin-top: 0px;" src="/images/no-face-100.png"/>
										{/if}
										
									</td>
								{/if}
									
							</tr>
							{/if}
							
							{counter}
						{/foreach}
					</table>
					<span style="font-size: 12px; margin-top: 5px; margin-left: 5px;">{t}ID{/t}: {$contact_id} | {t}created by{/t}: {$contact->entryCreatedBy} @{$contact->entryCreationDate} | {t}updated by{/t}: {$contact->entryUpdatedBy} @{$contact->entryUpdateDate}</span>
				</div>
				
				
				{if {preg_match pattern="dueviPerson" subject=$contact->objectClass} and {$contact_orgs|count} >0}
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

				<div id="tab_locations">
					{if $contact_locs}	 
						{foreach $contact_locs as $key => $loc}						
						<h3 style="margin-left: -15px; clear: left;">{$loc->locDescription}</h3>								
						<div class="none">
							<div class="none" style="width: 380px; float: left; clear: left; margin-bottom: 20px;">
								<table style="border: 1px solid #e8e8e8; width: 95%; margin-left: 15px; margin-right: 5px; margin-bottom: 3px;">
									{foreach $loc->show_fields as $key => $property_name}
										{if $loc->$property_name != ""}
										<tr valign="top" style="background-color: {cycle values="#FFF,#e8e8e8"};">
											<td class="field" style="width: 30%;">{t}{$property_name}{/t}</td>
											<td class="value"> 
												{$loc->$property_name|wordwrap:75:" ":true}
											</td>
										</tr>
										{/if}
									{/foreach}										
								</table>
								<span style="font-size: 12px; margin-top: 5px; margin-left: 15px;">{t}ID{/t}: {$loc->locId} | {t}Created by{/t}: {$loc->entryCreatedBy} | {t}Updated by{/t}: {$loc->entryUpdatedBy}</span>
							</div>
							{if $loc->locLatitude}
								{$desc = $loc->locDescription}
								{$description = "$contact_ref - $desc"}
								<iframe width="300" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
								src="http://maps.google.com/maps?q={$loc->locLatitude},+{$loc->locLongitude}+({$description})&amp;hl=en&amp;ie=UTF8&amp;t=h&amp;vpsrc=6&amp;ll={$loc->locLatitude},{$loc->locLongitude}&amp;spn=0.020352,0.025835&amp;z=14&amp;iwloc=A&amp;output=embed">
								</iframe>
								<div style="margin-left: 5px;">
								<a href="http://maps.google.com/maps?q={$loc->locLatitude},+{$loc->locLongitude}+({$description})&amp;hl=en&amp;ie=UTF8&amp;t=h&amp;vpsrc=6&amp;ll={$loc->locLatitude},{$loc->locLongitude}&amp;spn=0.020352,0.025835&amp;z=14&amp;iwloc=A&amp;source=embed" target="_blank" style="font-size: 12px; margin-left: 380px; margin-top: 3px;">View Larger Map</a>
								</div>
							{else}
								<div style="margin-left: 5px;">
								<img src="/images/empty_map.png" width="300px"/><br/><span style="font-size: 12px; margin-left: 380px;">{t}The address provided can not be displayed{/t}.</span>
								</div>
							{/if}
						</div>
				
						<br/>						
						{/foreach}
					{/if}
				</div>
				
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
