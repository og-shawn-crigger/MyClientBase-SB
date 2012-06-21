 
	{if $contact->client_id}
		<input type="hidden" name="client_id" id="client_id" value="{$contact->client_id}" />
	{/if}

	{foreach $contact->properties as $property => $details}
		
		{$type = ''}
		
		<!-- don't show fields that are not specified as visible in the configuration file  -->
		{if in_array($property, $contact->show_fields)} 
			<!-- but print out the hidden fields even if they are not in the visible fields array  -->
			{if in_array($property, $contact->hidden_fields)}
			
				$type = 'hidden';
			{else}
				continue;
			{/if}
		{/if}
		
		<!-- don't show fields which are not supposed to be modified by the customer  (it's stored in LDAP)  -->
		{if $details['no-user-modification'] == 1} {continue}
		
		{$value = $mdl_contacts->form_value($property)}
		
		<!-- show an asterisk when a field is required  (it's stored in LDAP) -->
		$required = $details['required'] == '1' ? '<em style="color: red;">*</em> ' : '';
		
		<!-- get the max lenght of the field (it's stored in LDAP) -->
		$max_length = $details['max-length'] != '' ? $details['max-lenght'] : '40';
		
		<!-- TODO we need something about the boolean value in the details  -->
		<!-- check if it's a boolean field  (it's stored in LDAP) -->
		{if $type != 'hidden' && preg_match('/boolean/', $details['desc']))
		{
			$type = 'checkbox';
			if($value === 'TRUE') $checked = "checked";
		} else {
			if(empty($type)) $type = 'input';
		}
		
		{if $type == 'hidden'}
			echo '<input type="'.$type.'" name="'.$property.'" id="'.$property.'" value="'.$value.'" '.$checked.' />';
		{else}
			<dl>
				<dt>{$required}{t}$property{/t}:</dt>
				<dd><input maxlength="{$max_length}" size="{$max_length}" type="{$type}" name="{$property}" id="{$property}" value="{$value}" {$checked} /></dd>
			</dl>
		{/if}
	{/foreach}


<div style="clear: both;">&nbsp;</div>

<input type="submit" id="btn_submit" name="btn_submit" value="{t}submit{/t}" />
<input type="submit" id="btn_cancel" name="btn_cancel" value="{t}cancel{/t}" />

<div style="clear: both;">&nbsp;</div>