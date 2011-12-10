<?php 
	//THIS THE POWER OF LDAP!! :)
	
	if(isset($contact->client_id))
	{
		echo '<input type="hidden" name="client_id" id="client_id" value="'.$contact->client_id.'" />';
	}

	foreach ($contact->properties as $property => $details) {
		
		$type = '';
		
		//don't show fields that are not specified as visible in the configuration file
		if(!in_array($property, $contact->show_fields)) 
		{
			//but print out the hidden fields even if they are not in the visible fields array
			if(in_array($property, $contact->hidden_fields))
			{
				$type = 'hidden';
			} else {
				continue;
			}
		}
		
		//don't show fields which are not supposed to be modified by the customer  (it's stored in LDAP)
		if($details['no-user-modification'] == '1') continue;
		
		$value = $this->mdl_clients->form_value($property);
		
		//show an asterisk when a field is required  (it's stored in LDAP)
		$required = $details['required'] == '1' ? '<em style="color: red;">*</em> ' : '';
		
		//get the max lenght of the field (it's stored in LDAP)
		$max_length = $details['max-length'] != '' ? $details['max-lenght'] : '40';
		
		//TODO we need something about the boolean value in the details
		//check if it's a boolean field  (it's stored in LDAP)
		if($type != 'hidden' && preg_match('/boolean/', $details['desc']))
		{
			$type = 'checkbox';
			if($value === 'TRUE') $checked = "checked";
		} else {
			if(empty($type)) $type = 'input';
		}
		
		if($type == 'hidden')
		{
			echo '<input type="'.$type.'" name="'.$property.'" id="'.$property.'" value="'.$value.'" '.$checked.' />';
		} else {
			echo '<dl>';
			//echo '<dt>'.$property.'</dt>';
			echo '<dt>'.$required.$this->lang->line($property).' :</dt>';
			echo '<dd><input maxlength="'.$max_length.'" size="'.$max_length.'" type="'.$type.'" name="'.$property.'" id="'.$property.'" value="'.$value.'" '.$checked.' /></dd>';
			echo '</dl>';
		}
	}
?>

<div style="clear: both;">&nbsp;</div>

<input type="submit" id="btn_submit" name="btn_submit" value="<?php echo $this->lang->line('submit'); ?>" />
<input type="submit" id="btn_cancel" name="btn_cancel" value="<?php echo $this->lang->line('cancel'); ?>" />

<div style="clear: both;">&nbsp;</div>