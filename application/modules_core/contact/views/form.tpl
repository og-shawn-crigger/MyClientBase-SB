{* <pre>{$contact|print_r}</pre> *}

{* //TODO: remember to add all the MUST fields that are not automatically populated (like cn, displayAs ..) *}

{* PREPARING THE FORM CONTENT *}
{foreach $contact->properties as $property => $details}
	
	{$type = ''}
	
	{* don't show fields that are not specified as visible in the configuration file  *}
	{if !in_array($property, $contact->show_fields)} {continue}	{/if}
	
	{* If the field is in the "visible fields" but it's also in the "hidden fields" than do not consider it a form field. Hidden fields are treated later. *}
	{if in_array($property, $contact->hidden_fields)} {continue} {/if}	
	
	{* don't show fields which are not supposed to be modified by the customer  (it's stored in LDAP)  *}
	{if $details['no-user-modification'] == 1} {continue} {/if}		
	
	{* VALUE *}
	{* //TODO is this usefull? {$value = $mdl_contacts->form_value($property)} *}

	{*
	<h1>{$property}</h1>
	<pre>{$details|print_r}</pre>
	*}
	
	{* at the end of the loop the array "fields" will contain all the items and values for the form *}
	{$fields[$property]["value"] = $contact->$property}
	
	{* show an asterisk when a field is required  (it's stored in LDAP) *}
	{if $details["required"]}
		{$fields[$property]["required"] = '<em style="color: red;">*</em> '}
	{else}
		{$fields[$property]["required"] = ' '}
	{/if}
	
	{* get the max lenght of the field (it's stored in LDAP) *}
	{if $details["max-length"]}
		{* avoid fields too long *}
		{$max_width = "40"}
		{if $details["max-length"] > $max_width}
			{$fields[$property]["max_length"] = $max_width}
		{else}
			{$fields[$property]["max_length"] = $details["max-length"]}
		{/if}								
	{else}
		{$fields[$property]["max_length"] = "25"} {* default value *}
	{/if}
	
	{* FOCUS ON FORM ITEM TYPE *}
	{* possible types: button,checkbox,file,hidden,image,password,radio,reset,submit,text *}

	{* check if it's a boolean field and render it as a checkbox *}
	{* {if {preg_match pattern="boolean" subject=$details['desc']}} *}
	{if $details['boolean'] == 1}							
		{$type = 'checkbox'}
		{if $value === 'TRUE'}
			{$fields[$property]["checked"] = "checked"}
		{else}
			{$fields[$property]["checked"] = ""}
		{/if}
	{/if}

	{* check if it's a binary field and render it as a checkbox *}
	{if $details['binary'] == 1}							
		{$type = 'file'}
		{$fields[$property]["value"] = "{t}browse{/t}"}
		{$form_addon = 'enctype="multipart/form-data"'}
	{/if}
	
	{* //TODO consider also textareas *}
	{if !$type} 
		{$type = 'text'}
	{/if}	
	
	{$fields[$property]["type"] = $type}
								
{/foreach}

{$settings[] = "category"}
{$settings[] = "enabled"}

<div class="container_10" id="center_wrapper">

	<div class="grid_8" id="content_wrapper">

		<div class="section_wrapper">
						
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
			
			{* <pre>{$contact|print_r}</pre> *} 
			
			<div class="content toggle">
					<div id="tabs">

						<ul>
							<li><a href="#tab_contact">{t}Info{/t}</a></li>
                  			<li><a href="#tab_settings">{t}Settings{/t}</a></li>
						</ul>

						<br/>
						<span style="color: red;">*</span> <span style="text-size: 12px; margin-bottom: 5px;">{t}means mandatory field{/t}</span><br/><br/>						
							
						<div id="tab_contact" style="margin-bottom: 20px;">
						
							<form method="post" action={$form_url} {$form_addon}>
							
								{* <pre>{$fields|print_r}</pre> *} 
								
								{* print out hidden fields regardless they are visible or not *}
								{if is_array($contact->hidden_fields)}
									{foreach $contact->hidden_fields as $key => $property}
										<input type="hidden" name="{$property}" id="{$property}" value="{$contact->$property}" /> 
									{/foreach}
								{/if}							
								
								{* outputs the visible fields accordingly to the order provided in the settings *}
								{foreach $contact->show_fields as $key => $property name="foreach_property"}
									{* output the "tab info" fields => all the fields except the ones specified in $settings *}
									{if $fields[$property] and !in_array($property,$settings)}
										{* here some GUI filters *}
										
										{* <dl style="float: left; width: 100%; background-color: {cycle values="#FFF,#e8e8e8"};"> *}
										<dl style="float: left; width: 100%; height: 35px; margin-top: 0px;">
											{* aliases substitution *}
											{if isset($contact->aliases) and isset($property) and isset($contact->aliases.$property)}
												{$fieldname = $contact->aliases.$property}
											{else}
												{$fieldname = $property}
											{/if}					
			
											<dt style="margin-top: 2px; margin-bottom: 2px; float: left; text-align: left; width: 40%;">{t}{$fieldname|capitalize|regex_replace:"/_/":" "}{/t}{$fields[$property]["required"]}:</dt>
											
											{$checked = ""}
											{if isset($fields[$property]["checked"])} 
												{$checked = $fields[$property]["checked"]}
											{/if} 
											
											{if $property == 'sn' || $property == 'givenName' || $property == 'o'}
												{$disabled = 'disabled'}
												<input type="hidden" name="{$property}" id="{$property}" value="{$fields[$property]["value"]}" />
											{else}
												{$disabled = ''}
											{/if}
											
											{if $fields[$property]["type"] == "file"}
												<dd style="margin-top: 5px; vertical-align: middle; float: left;  background-color: transparent;">
													<input type="{$fields[$property]["type"]}" name="{$property}" />
												</dd>
											{else}
												<dd style="margin-top: 5px; float: left; background-color: transparent;">
													<input maxlength="{$fields[$property]["max_length"]}" size="{$fields[$property]["max_length"]}" type="{$fields[$property]["type"]}" name="{$property}" id="{$property}" value="{$fields[$property]["value"]}" {$checked} {$disabled} />

													{* set the first not disabled field as the focused one *}
													{if $disabled == ''}
														{if !isset($focus_set)}
															<script type="text/javascript"> $("#{$property}").focus(); </script>
															{$focus_set = true}
														{/if}
													{/if}
												</dd>
											{/if}
										</dl>										
									{/if}
								{/foreach}

								<dl>
									<input class="uibutton" style="float: right; margin-top: 10px; margin-right: 10px;" type="submit" id="btn_submit"  name="btn_submit" value="{t}submit{/t}" />
								</dl>
								{* <input class="uibutton" style="margin-top: 10px; margin-right: 10px;" type="reset" id="btn_cancel"  name="btn_cancel" value="{t}cancel{/t}" /> *}
									
							</form>
							
							<div style="font-size: 12px; margin-top: 25px; margin-left: 5px;  color: gray;">
								{if $contact_id != ""}
									{t}ID{/t}: {$contact_id} | 
								{/if}
								
								{if $contact->entryCreatedBy != ""}			
									{t}Created by{/t}: {$contact->entryCreatedBy} @{$contact->entryCreationDate}
								{/if}
								 
								{if $contact->entryUpdatedBy != ""}
									| {t}Updated by{/t}: {$contact->entryUpdatedBy} @{$contact->entryUpdateDate}
								{/if}
							</div>							
						</div>	
																
							

            			
            			<div id="tab_settings" style="margin-bottom: 30px;">
            				
                       		<form method="post" action={$form_url} {$form_addon}>
                       		
								{* print out hidden fields regardless they are visible or not *}
								{if is_array($contact->hidden_fields)}
									{foreach $contact->hidden_fields as $key => $property}
										<input type="hidden" name="{$property}" id="{$property}" value="{$contact->$property}" /> 
									{/foreach}
								{/if}							
                       		 
								{* output the "tab settings" fields *}
								{foreach $contact->show_fields as $key => $property}
									{if $fields[$property] and in_array($property,$settings)}
										<dl style="float: left; width: 100%; height: 35px; margin-top: 0px;">
											
											{* aliases substitution *}
											{if isset($contact->aliases) and isset($property) and isset($contact->aliases.$property)}
												{$fieldname = $contact->aliases.$property}
											{else}
												{$fieldname = $property}
											{/if}		
											
											<dt style="float: left; text-align: left; width: 40%;">{t}{{$fieldname}|capitalize|regex_replace:"/_/":" "}{/t}{$fields[$property]["required"]}:</dt>
											
											{$checked = ""}
											{if isset($fields[$property]["checked"])} 
												{$checked = $fields[$property]["checked"]}
											{/if} 
											<dd style="float: left; background-color: transparent;"><input maxlength="{$fields[$property]["max_length"]}" size="{$fields[$property]["max_length"]}" type="{$fields[$property]["type"]}" name="{$property}" id="{$property}" value="{$fields[$property]["value"]}" {$checked} /></dd>
										</dl>								
									{/if}
								{/foreach}
								<dl>
									<input class="uibutton" style="float: right; margin-top: 10px; margin-right: 10px;" type="submit" id="btn_submit"  name="btn_submit" value="{t}submit{/t}" />
								</dl>
							</form>	
		    			</div>
					</div>				
				</form>
							
			</div>
			
		</div>

	</div>
</div>			