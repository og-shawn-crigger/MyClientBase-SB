<script type="text/javascript">
	$(function() {        			
		//updates the order of the items
    	$( "#PersonOrderVisibleAttributes" ).sortable({
        	opacity: 0.6,
        	cursor: 'move',
        	containment: 'parent',
        	axis: 'y',
        	placeholder: "ui-state-highlight",
        	update: function() {
        		var input = $(this).sortable("serialize") + '&action=person_sort';
        			$.post("/index.php/contact/update_settings", input, function(theResponse){
					$("#person_order_accordion").html(theResponse); 
				});
			}
		}).disableSelection();        
	});
</script>
        
<p style="background-color: #fffdd0; border: 1px dotted gray;">Sort the visible attributes as you like by dragging them up or down. All the changes are automatically saved.
</p>		
<ul id="PersonOrderVisibleAttributes">
	{foreach $person_visible_attributes as $key => $attribute_name}
		<li id="PersonVisibleAttributes_{$attribute_name}" style="margin-top: 3px; padding-bottom: 1px; margin-bottom: 5px; margin-left: 3px; margin-right: 3px; background-color: #FFF; border: 1px solid #e8e8e8;">
			{if $person_all_attributes[$attribute_name]['required'] == 1}
				{$color="red"}
			{else}
				{$color="black"}
			{/if}

			<p style="color:{$color}; margin-bottom: 4px; margin-left: 5px;"><b>{$attribute_name}</b>
			{if isset($person_aliases) and isset($attribute_name) and isset($person_aliases.$attribute_name)}
				<span style="font-size: 13px; color: green"> {t}Alias{/t}: {$person_aliases.$attribute_name}</span>
			{/if}	
			</p>

			<p style="margin-left: 15px; margin-bottom: 0px;"><i>
			{if $person_all_attributes[$attribute_name]['desc'] != ""}
				{t}{$person_all_attributes[$attribute_name]['desc']}{/t}
			{else}
				{t}No description available{/t}.
			{/if}
			</i></p>
		</li>
	{/foreach}		
</ul>          