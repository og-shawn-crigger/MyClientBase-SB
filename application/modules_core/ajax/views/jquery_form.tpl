{*
<pre>
{$object|print_r}
</pre>
*}

{if isset($object->aliases)} {$aliases = $object->aliases} {/if}

<div id="{$div_id}" title="Form">
	<p>* {t}means mandatory field{/t}</p>

	<form name="{$form_name}">
	<fieldset>
		{* hidden fields *}
		{foreach $object->hidden_fields as $key => $property_name}
			<input type="hidden" name="{$property_name}" id="{$property_name}" value="{$object->$property_name}" />
		{/foreach}
		
		{foreach $object->show_fields as $key => $property_name}
		{if {preg_match pattern="^loc" subject=$property_name} and {$property_name != "locId"}}
			<label for="{$property_name}">
			{if isset($aliases) && isset($aliases.$property_name)}
				{t}{$object->aliases.$property_name|capitalize|regex_replace:"/_/":" "}{/t}
			{else}
				{t}{$property_name}{/t}
			{/if}
			</label>
			
			<input style="margin-right: 5px; width: 250px;" type="text" name="{$property_name}" id="{$property_name}" 
			{if $object->$property_name != ''}
				value="{$object->$property_name}"
			{/if}
			/>
			
		{/if}
		{/foreach}		
	</fieldset>
	</form>
</div>	
