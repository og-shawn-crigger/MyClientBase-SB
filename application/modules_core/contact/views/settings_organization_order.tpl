<script type="text/javascript">
	$(function() {        			
		//updates the order of the items
    	$( "#OrgOrderVisibleAttributes" ).sortable({
        	opacity: 0.6,
        	cursor: 'move',
        	containment: 'parent',
        	axis: 'y',
        	placeholder: "ui-state-highlight",
        	update: function() {
        		var input = $(this).sortable("serialize") + '&action=org_sort';
        			$.post("/index.php/contact/update_settings", input, function(theResponse){
					$("#org_order_accordion").html(theResponse); 
				});
			}
		}).disableSelection();        
	});
</script>
        
<p style="background-color: #fffdd0; border: 1px dotted gray;">Sort the visible attributes as you like by dragging them up or down. All the changes are automatically saved.
</p>		
<ul id="OrgOrderVisibleAttributes">
	{foreach $org_visible_attributes as $key => $attribute_name}
		<li id="OrgVisibleAttributes_{$attribute_name}" style="margin-top: 3px; padding-bottom: 1px; margin-bottom: 5px; margin-left: 3px; margin-right: 3px; background-color: #FFF; border: 1px solid #e8e8e8;">
			{if $org_all_attributes[$attribute_name]['required'] == 1}
				{$color="red"}
			{else}
				{$color="black"}
			{/if}
			<p style="color:{$color}; margin-bottom: 4px; margin-left: 5px;"><b>{$attribute_name}</b></p> 
			<p style="margin-left: 15px; margin-bottom: 0px;"><i>
			{if $org_all_attributes[$attribute_name]['desc'] != ""}
				{t}{$org_all_attributes[$attribute_name]['desc']}{/t}
			{else}
				{t}No description available{/t}.
			{/if}
			</i></p>
		</li>
	{/foreach}		
</ul>