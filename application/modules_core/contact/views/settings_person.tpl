<script type="text/javascript">
	$(function() {        		
		$("#PersonAvailableAttributes li").draggable({
			axis: "x",
			cursor: 'move',
			distance: 30,
			containment: 'document',
			grid: [400, 0],
			opacity: 0.6,
			revert: true,
			revertDuration: 300,
			delay: 1,			 
			stop: function(event, ui){	
				var input = '&item=' + $(this).attr('id') + '&action=person_addToVisible'; 
				$.post("/index.php/contact/update_settings", input, function(theResponse){
        			$("#person_accordion").html(theResponse);  
        		});            		 	
            },
		}).disableSelection();

		$("#PersonVisibleAttributes li").draggable({
			axis: "x",
			cursor: 'move',
			distance: 30,
			containment: 'document',
			grid: [400, 0],
			opacity: 0.6,
			revert: true,
			revertDuration: 300,
			delay: 1,			 		 
			stop: function(event, ui){	
        		var input = '&item=' + $(this).attr('id') + '&action=person_removeFromVisible'; 
        		$.post("/index.php/contact/update_settings", input, function(theResponse){
        			$("#person_accordion").html(theResponse); 
        		});                		 	        		 		             	
        	},
		}).disableSelection();                	     
	});
</script>  

<p style="background-color: #fffdd0; border: 1px dotted gray;">{t}Drag attributes from the left to the right to make them visible or from the right to the left to hide them{/t}. 
{t}All the changes are automatically saved{/t}.
</p>

{* list of all the visible attributes *}
<div id="PersonVisibleAttributes" style="float:right; display:inline; width: 48%; border: 1px solid gray; padding: 3px;">
	<h3>{t}Visible Attributes{/t}<span style="font-size: 13px;"> ({t}found{/t} {$person_visible_attributes|@count})</span></h3>
	<ul id="PersonVisibleAttributes" class="connectedSortable">
	{foreach $person_visible_attributes as $key => $attribute_name}
		<li id="PersonVisibleAttributes_{$attribute_name}" style="margin-top: 3px; padding-bottom: 1px; margin-bottom: 5px; margin-left: 3px; margin-right: 3px; background-color: #FFF; width: 390px; border: 1px solid #e8e8e8;">
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
</div>

{* list of all the available attributes *}
<div style="width: 48%; border: 1px solid gray; padding: 3px;">
	<h3>{t}Available Attributes{/t}<span style="font-size: 13px;"> ({t}found{/t} {$person_available_attributes|@count})</span></h3>
	<ul id="PersonAvailableAttributes" class="connectedSortable">
	{foreach $person_available_attributes as $attribute_name => $attribute_features}
		<li id="PersonAvailableAttributes_{$attribute_name}" style="position: relative; margin-top: 3px; padding-bottom: 1px; margin-bottom: 5px; margin-left: 3px; margin-right: 3px; background-color: #FFF; width: 390px; border: 1px solid #e8e8e8;">
		
			{if $attribute_features['required'] == 1}
				{$color="red"}
			{else}
				{$color="black"}
			{/if}
			<p style="color:{$color}; margin-bottom: 4px; margin-left: 5px;"><b>{$attribute_name}</b></p> 
			<p style="margin-left: 15px; margin-bottom: 0px;"><i>
			{if $attribute_features['desc'] != ""}
				{t}{$attribute_features['desc']}{/t}
			{else}
				{t}No description available{/t}.
			{/if}
			</i></p>	
		</li>
	{/foreach}
	</ul>
</div>