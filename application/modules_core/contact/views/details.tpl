{* focuses on the tab matching the hash and goes on the top of the page *}
<script type="text/javascript">
	$(document).ready(function() {

		var currentURL = window.location;
		url_hash = currentURL.hash;
		
		var $tabs = $('#tabs').tabs();
		$tabs.tabs('select', url_hash);
		
		window.location.hash='#top';
	});
</script>	

{assign 'contact' $contact}
{assign 'properties' $contact->properties}
{assign 'language' 'en'}
{assign 'baseurl' $baseurl}
{assign 'invoices_html' $invoices_html}

<div class="grid_8" id="content_wrapper">

	<div class="section_wrapper">
		<div>
			{if {preg_match pattern="dueviPerson" subject=$contact->objectClass}}
				{$contact_ref = $contact->cn}
				{$contact_id = $contact->uid}
				{$contact_id_key = "uid"}
				{$object_type = 'person'}
				<h3 class="title_black">{$contact->cn}</h3>
			{/if}		
			
			{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass}}
				{$contact_ref = $contact->o}
				{$contact_id = $contact->oid}
				{$contact_id_key = "oid"}
				{$object_type = 'organization'}
				<h3 class="title_black">{$contact->o}</h3>
			{/if}					
		</div>
	
		<div class="content toggle" >
			<div id="tabs" >
			
				<!-- TABS -->				
				<ul>
					<li><a href="#tab_client">{t}Info{/t}</a></li>

					{if $contact_locs}
					<li><a href="#tab_locations">{t}Locations{/t}</a></li>
					{/if}
															
					{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass} and $members}
						<li><a href="#tab_members">{t}Members{/t}</a></li>
					{/if}
					
					{if {preg_match pattern="dueviPerson" subject=$contact->objectClass} and {$contact_orgs|count} >0}
						<li><a href="#tab_memberOf">{t}Member of{/t}</a></li>
					{/if}					
					<!-- 
					<li><a href="#tab_invoices">{citranslate lang=$language text='invoices'}</a></li>
					 -->
				</ul>
				
				
				
				<div id="tab_client" >
					{if isset($contact->aliases)} {$aliases = $contact->aliases} {/if}
					
					<table border="1" class="none" style="border: 1px solid #e8e8e8; width: 98%; margin-bottom: 2px;">
						{$count = 0}
						{foreach $contact->show_fields as $key => $property_name}
						
							{if $contact->$property_name != ""}
							<tr valign="top" style="height: 5px; background-color: {cycle values="#FFF,#e8e8e8"};">
								
								<td class="field" style="valign: middle; width: 30%;">
								
								{if isset($aliases) && isset($aliases.$property_name)}
									{t}{$contact->aliases.$property_name|capitalize|regex_replace:"/_/":" "}{/t}
								{else}
									{t}{$property_name}{/t}
								{/if}
								</td>

								{if $count <= 4} 
									<td class="value" style="valign: middle;">
								{else}
									<td class="value" colspan="2"  style="valign: middle;">
								{/if}								
								
										{$already_wrote=0}
										<!-- particular cases -->
										{if $property_name == "mail" or $property_name == "omail"}
											<span class="marker">&gt;&gt;</span><a href="mailto:{$contact->$property_name}">{$contact->$property_name|wordwrap:60:"<br/>":true}</a>
											{$already_wrote=1}
										{/if}	

										{if $property_name == "labeledURI" or $property_name == "oURL"}
											<span class="marker">&gt;&gt;</span><a href="{$contact->$property_name}" target="_blank">{$contact->$property_name|wordwrap:60:"<br/>":true}</a>
											{$already_wrote=1}
										{/if}
										
										{if $property_name=="jpegPhoto"}
											{* skip the item. I take care of the photo later *}
											{$already_wrote=1}
										{/if}				

										{if $property_name=="managerName" && $contact->$property_name != ""}
											<span class="marker">&gt;&gt;</span><a href="/contact/search/{$contact->$property_name}">{$contact->$property_name}</a>
											{$already_wrote=1}
										{/if}
																				
										{if $property_name=="assistantName" && $contact->$property_name != ""}
											<span class="marker">&gt;&gt;</span><a href="/contact/search/{$contact->$property_name}">{$contact->$property_name}</a>
											{$already_wrote=1}
										{/if}
												
										<!-- default case -->
										{if $already_wrote==0} 
											{$contact->$property_name|wordwrap:60:"<br/>":true}
										{/if}
									</td>
									
								{if $count == 0} 
									<td rowspan="5" align="center" style="width: 100px; background: white;">
										<span style="font-size: 12px; align: center; margin-bottom: 0px;">
										{if isset($contact->uid)}
											{"{t}photo{/t}"|capitalize}
										{else}
											&nbsp;
										{/if}
										</span>
										
										{if isset($contact->uid)}
											{if $contact->jpegPhoto}
												{$src="data:image/jpeg;base64,{$contact->jpegPhoto}"}
											{else}
												{$src="/images/no-face-100.png"}
											{/if}
										{else}
											{$src="/images/no-org-100.png"}
										{/if}
											
										<img alt="jpegPhoto" style="border: 1px solid black; width: 100px; height: 100px; margin-top: 0px;" src="{$src}">
										
									</td>
								{/if}
									
							</tr>
							{$count = $count + 1}
							{/if}
						{/foreach}
					</table>
					<span style="font-size: 12px; margin-top: 5px; margin-left: 5px;  color: gray;">{t}ID{/t}: {$contact_id} | {t}Created by{/t}: {$contact->entryCreatedBy} @{$contact->entryCreationDate} 
					{if $contact->entryUpdatedBy != ""}
						| {t}Updated by{/t}: {$contact->entryUpdatedBy} @{$contact->entryUpdateDate}
					{/if}
					</span>
				</div>
				
				
				{if {preg_match pattern="dueviPerson" subject=$contact->objectClass} and {$contact_orgs|count} >0}
				<div id="tab_memberOf">
					{if $contact_orgs}
						{foreach $contact_orgs as $key => $org}
							{if isset($org->aliases)} {$aliases = $org->aliases} {/if}	
							<div id="memberOf_{$org->oid}" style="margin-bottom: 30px;">
								<div style="width: 100%; overflow:auto;">
									<div style="float: left;">
										<h3 style="margin-left: -20px;">
											<a href="index.php/contact/details/oid/{$org->oid}">{$org->o}</a><span class="marker_right">&lt;&lt;</span>
				
											{if {preg_match pattern=$org->oid subject=$contact->oAdminRDN}}
											<img src="/images/gold_star_20.jpg" style="width: 20px; margin-left: 10px;" />
											<span style="font-size: 12px; margin-left: 3px;">({t}manager{/t})</span>
											{/if}
		
										</h3>
									</div>
									<div style="float:right; display:inline; width: 300px; font-size: 12px; padding-top: 9px;">
										{if {preg_match pattern=$org->oid subject=$contact->oAdminRDN}} 
											<span class="marker">&gt;&gt;</span><a href="#" onClick="jqueryAssociate({ 'procedure':'personAdminOfOrganization','object_name':'organization','object_id':'{$org->oid}','related_object_name':'{$object_type}','related_object_id':'{$contact_id}','hash':'set_here_the_hash' })">{t}Remove administration{/t}</a>
										{else}
											<span class="marker">&gt;&gt;</span><a href="#" onClick="jqueryAssociate({ 'procedure':'personAdminOfOrganization','object_name':'organization','object_id':'{$org->oid}','related_object_name':'{$object_type}','related_object_id':'{$contact_id}','hash':'set_here_the_hash' })">{t}Make administrator{/t}</a>
										{/if}
											&nbsp;|&nbsp;
											<span class="marker">&gt;&gt;</span><a href="#" onClick="jqueryDelete({ 'procedure':'deleteOrganizationMembership','object_name':'organization','object_id':'{$org->oid}','related_object_name':'{$object_type}','related_object_id':'{$contact_id}','hash':'set_here_the_hash' })">{t}Delete Association{/t}</a>
									</div>
								</div>
								
								
								<table class="contact-details-left" style="border: 1px solid #e8e8e8; width: 98%; margin-left: 15px; margin-bottom: 3px; padding-bottom: 0px;">
									{foreach $org->show_fields as $index => $property_name}
										{if $org->$property_name != ""}
										<tr valign="top" style="background-color: {cycle values="#FFF,#e8e8e8"};">
											<td class="field" style="width: 30%;">
												{if isset($aliases) && isset($aliases.$property_name)}
													{t}{$org->aliases.$property_name|capitalize|regex_replace:"/_/":" "}{/t}
												{else}
													{t}{$property_name}{/t}
												{/if}
											</td>
											<td class="value">
												{$already_wrote=0}
												<!-- particular cases -->
												{if $property_name=="omail"}
													<a href="mailto:{$org->$property_name}">{$org->$property_name|wordwrap:60:"<br/>":true}</a>
													{$already_wrote=1}
												{/if}
													
												{if $propery_name=="oURL"}
													<a href="{$org->$property_name}" target="_blank">{$org->$property_name|wordwrap:60:"<br/>":true}</a>
													{$already_wrote=1}
												{/if}
													
												{* default case *}
												{if $already_wrote==0}
													{$org->$property_name|wordwrap:75:" ":true}
												{/if}
											</td>
										</tr>
										{/if}
									{/foreach}										
								</table>
								<span style="font-size: 12px; margin-top: 5px; margin-left: 15px; color: gray;">{t}ID{/t}: {$org->oid} | {t}Created by{/t}: {$org->entryCreatedBy} @{$contact->entryCreationDate}
									{if $org->entryUpdatedBy != ""}
										{t}updated by{/t}: {$org->entryUpdatedBy} @{$org->entryUpdateDate}
									{/if}
								</span>
							</div>
						{/foreach}
					{/if}
				</div>				
				{/if}
				
				{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass} and $members}
				<div id="tab_members">
				
					{foreach $members as $key => $member}
						
						{if isset($member->aliases)} {$aliases = $member->aliases} {/if}
						
						{if $key == 0}
							<h3 style="margin-left: -15px;">
						{else}
							<h3 style="margin-left: -15px; margin-top: 30px;">
						{/if}
					
						<span class="marker">&gt;&gt;</span><a href="/index.php/contact/details/uid/{$member->uid}">{$member->sn} {$member->givenName}</a>
						{if $member->oAdminRDN == $contact->oid}
						<img src="/images/gold_star_20.jpg" style="width: 20px; margin-left: 10px;" />
						<span style="font-size: 12px; margin-left: 3px;">({t}manager{/t})</span>
						{/if}						
						</h3>					
						
						<div style="background-color: white; margin-left: 7px; padding-top: 15px; padding-bottom: 0px; padding-left: 6px; padding-right: 6px; border: 1px solid gray;">
						<p>
						{foreach name="members" from=$member_fields key=key  item=property_name}
						
						{if $member->$property_name != ""}
						{* {if $member->enabled == "TRUE"} *}
							{if in_array($property_name, $member->show_fields)}
								<b style="padding-left: 15px; padding-right: 2px; padding-top: 2px; padding-bottom: 2px;">														
								{if isset($aliases) && isset($aliases.$property_name)}
									{t}{$member->aliases.$property_name|capitalize|regex_replace:"/_/":" "}{/t}
								{else}
									{t}{$property_name}{/t}
								{/if}
								:</b>

								{$already_wrote=0}
								<!-- particular cases -->
								{if $property_name=="mail"}
									<a href="mailto:{$member->$property_name}">{$member->$property_name|wordwrap:60:"<br/>":true}</a>
									{$already_wrote=1}
								{/if}								
								
								{* default case *}
								{if $already_wrote==0}
									{$member->$property_name|wordwrap:75:" ":true}
								{/if}
	
								{if $smarty.foreach.members.iteration % 3 == 0}</p><p>{/if}
							{/if}
						{/if}		
																								
						{* {/if} *}
						{/foreach}

						</p></div>
						<span style="font-size: 12px; margin-top: 5px; margin-left: 7px; color: gray;">{t}ID{/t}: {$member->uid} | {t}Created by{/t}: {$member->entryCreatedBy} @{$member->entryCreationDate} 
						{if $member->entryUpdatedBy != ""}
							{t}updated by{/t}: {$member->entryUpdatedBy} @{$member->entryUpdateDate}
						{/if}						
						</span>
					</table>				
					{/foreach}
				</div>
				{/if}				

				{if $contact_locs}
				<div id="tab_locations">
						 
						{foreach $contact_locs as $key => $loc}		
							{if isset($loc->aliases)} {$aliases = $loc->aliases} {/if}
							<div id="loc_{$loc->locId}" style="margin-bottom: 30px;">
								<div style="width: 100%; overflow:auto;">
									<div style="float: left;"><h3 style="margin-left: -15px;">{t}{$loc->locDescription}{/t}</h3></div>
									<div style="float:right; display:inline; width: 300px; font-size: 12px; padding-top: 9px;">
										{if $loc->locDescription|lower != 'home' && $loc->locDescription|lower != 'registered address'} 										
											<span class="marker">&gt;&gt;</span><a href="#" onClick="jqueryForm({ 'form_type':'form','object_name':'location','object_id':'{$loc->locId}','related_object_name':'{$object_type}','related_object_id':'{$contact_id}','hash':'set_here_the_hash' })">{t}Edit{/t}</a>
											&nbsp;|&nbsp;
											<span class="marker">&gt;&gt;</span><a href="#" onClick="jqueryDelete({ 'procedure':'deleteLocation','object_name':'location','object_id':'{$loc->locId}','hash':'set_here_the_hash' })">{t}Delete{/t}</a>

											{if $loc->locLatitude}&nbsp;|&nbsp;{/if}
										{/if}
										
										{if $loc->locLatitude}
										<span class="marker">&gt;&gt;</span><a href="http://maps.google.com/maps?q={$loc->locLatitude},+{$loc->locLongitude}+({$description})&amp;hl=en&amp;ie=UTF8&amp;t=h&amp;vpsrc=6&amp;ll={$loc->locLatitude},{$loc->locLongitude}&amp;spn=0.020352,0.025835&amp;z=14&amp;iwloc=A&amp;source=embed" target="_blank">{t}Larger Map{/t}</a>
										{/if}
									</div>
								</div>
																
								<div class="none" style="width: 100%; overflow:auto;">
									<div class="none" style="width: 380px; float: left; clear: left; margin-bottom: 20px;">
										<table style="border: 1px solid #e8e8e8; width: 95%; margin-left: 15px; margin-right: 5px; margin-bottom: 3px;">
											{foreach $loc->show_fields as $key => $property_name}
												{if $loc->$property_name != ""}
												<tr valign="top" style="background-color: {cycle values="#FFF,#e8e8e8"};">
													<td class="field" style="width: 30%;">
														{if isset($aliases) && isset($aliases.$property_name)}
															{t}{$loc->aliases.$property_name|capitalize|regex_replace:"/_/":" "}{/t}
														{else}
															{t}{$property_name}{/t}
														{/if}
													</td>
													<td class="value"> 
														{if $property_name == "locDescription"}
															{t}{$loc->$property_name|wordwrap:75:" ":true}{/t}
														{else}
															{$loc->$property_name|wordwrap:75:" ":true}
														{/if}													
													</td>
												</tr>
												{/if}
											{/foreach}										
										</table>
										<span style="font-size: 12px; margin-top: 5px; margin-left: 15px; color: gray;">{t}ID{/t}: {$loc->locId} | {t}Created by{/t}: {$loc->entryCreatedBy} | {t}Updated by{/t}: {$loc->entryUpdatedBy}</span>
									</div>
									
									{if $loc->locLatitude}
										{$desc = $loc->locDescription}
										{$description = "$contact_ref - $desc"}
										<div>
											<iframe style="border: 1px solid #e8e8e8;" width="300" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
											src="http://maps.google.com/maps?q={$loc->locLatitude},+{$loc->locLongitude}+({$description})&amp;hl=en&amp;ie=UTF8&amp;t=h&amp;vpsrc=6&amp;ll={$loc->locLatitude},{$loc->locLongitude}&amp;spn=0.020352,0.025835&amp;z=14&amp;iwloc=A&amp;output=embed">
											</iframe>
										</div>
									{else}
										<div style="overflow: hidden;">
											<img style="border: 1px solid #e8e8e8;" src="/images/empty_map.png" width="300px" height="300px"/>
										</div>
									{/if}
								</div>
							</div>						
						{/foreach}
					
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
{*
<pre>
{$contact_locs|print_r}
</pre>
*}
</div>
