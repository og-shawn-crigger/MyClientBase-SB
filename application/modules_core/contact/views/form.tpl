{* PREPARING THE FORM CONTENT *}

{foreach $contact->properties as $property => $details}
	
	{$type = ''}
	
	{* don't show fields that are not specified as visible in the configuration file  *}
	{if !in_array($property, $contact->show_fields)} 
		{* but print out the hidden fields even if they are not in the visible fields array  *}
		{if in_array($property, $contact->hidden_fields)}	
			{$type = 'hidden'}
		{else}
			{* skip this iteration *}
			{* Skip: {$property}<br/> *}
			{continue}
		{/if}
	{/if}
	
	{* don't show fields which are not supposed to be modified by the customer  (it's stored in LDAP)  *}
	{if $details['no-user-modification'] == 1} {continue} {/if}		
	
	{* VALUE *}
	{* TODO is this usefull? {$value = $mdl_contacts->form_value($property)} *}
	
	{* the array fields contains all the items of the form *}
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
	
	{* check if it's a boolean field and render it as a checkbox (it's stored in LDAP) *}
	{if {preg_match pattern="boolean" subject=$details['desc']} and $type != 'hidden'}							
		{$type = 'checkbox'}
		{if $value === 'TRUE'}
			{$fields[$property]["checked"] = "checked"}
		{/if}
	{else}
		{if !$type} 
			{$type = 'input'}
		{/if}
	{/if}
	
	{if $type == 'hidden'}
		<input type="{$type}" name="{$property}" id="{$property}" value="{$value}" {$checked} />
	{else}
		{$fields[$property]["type"] = $type}
	{/if}								
{/foreach}

{$settings[] = "category"}
{$settings[] = "enabled"}

<div class="container_10" id="center_wrapper">

	<div class="grid_7" id="content_wrapper">

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

				<form method="post" action="/index.php/contact/form">

					<div id="tabs">

						<ul>
							<li><a href="#tab_person">{t}Info{/t}</a></li>
                  			<li><a href="#tab_settings">{t}Settings{/t}</a></li>
						</ul>

						
						<span style="color: red;">*</span> <span style="text-size: 12px; margin-bottom: 5px;">{t}means mandatory field{/t}</span><br/><br/>						
							
						<div id="tab_person">
							{* <pre>{$fields|print_r}</pre> *} 
							{* outputs the fields according to the order provided in the settings *}
							{foreach $contact->show_fields as $key => $property}
								{if $fields[$property] and !in_array($property,$settings)}
									{* here some GUI filters *}
									
									<dl style="background-color: {cycle values="#FFF,#e8e8e8"}; float: left; width: 100%;">
										<dt style="float: left; text-align: left; width: 40%;">{t}{$property}{/t}{$fields[$property]["required"]}:</dt>
										<dd style="float: left;"><input maxlength="{$fields[$property]["max_length"]}" size="{$fields[$property]["max_length"]}" type="{$fields[$property]["type"]}" name="{$property}" id="{$property}" value="{$fields[$property]["value"]}" {$fields[$property]["checked"]} /></dd>
									</dl>								
								{/if}
							{/foreach}
							<input type="submit" id="btn_submit" name="btn_submit" value="{t}submit{/t}" />
							<input type="submit" id="btn_cancel" name="btn_cancel" value="{t}cancel{/t}" />	
						</div>
            								
            			<div id="tab_settings">
            				
                       		{* tab_settings.php *}
                       		 
							{* output the settings fields *}
							{foreach $contact->show_fields as $key => $property}
								{if $fields[$property] and in_array($property,$settings)}
									<dl style="background-color: {cycle values="#FFF,#e8e8e8"}; float: left; width: 100%;">
										<dt style="float: left; text-align: left; width: 40%;">{t}{$property}{/t}{$fields[$property]["required"]}:</dt>
										<dd style="float: left;"><input maxlength="{$fields[$property]["max_length"]}" size="{$fields[$property]["max_length"]}" type="{$fields[$property]["type"]}" name="{$property}" id="{$property}" value="{$fields[$property]["value"]}" {$fields[$property]["checked"]} /></dd>
									</dl>								
								{/if}
							{/foreach}
							<input type="submit" id="btn_submit" name="btn_submit" value="{t}submit{/t}" />
							<input type="submit" id="btn_cancel" name="btn_cancel" value="{t}cancel{/t}" />			                       		 
            			</div>

					</div>				
				</form>
							
			</div>
			
		</div>

	</div>
</div>			