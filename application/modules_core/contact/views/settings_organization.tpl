        <script type="text/javascript">
        $(function() {

        	$("#OrgAvailableAttributes li, #OrgVisibleAttributes li").draggable({ 
                axis: "x",
                cursor: 'move',
                distance: 30,
                containment: 'document',
                grid: [400, 0],
                opacity: 0.6,
                revert: true,
                revertDuration: 300,
                delay: 1,
        	}).disableSelection();
        		
            $("#OrgAvailableAttributes li").draggable({ 
               	stop: function(event, ui){
                    // When dragging stops, call the php function	
        			var input = '&item=' + $(this).attr('id') + '&action=org_addToVisible'; 
        			$.post("http://myclientbase-sb/index.php/contact/update_settings", input, function(theResponse){
        				$("#org_accordion").html(theResponse);  
        			}); 	
                },
            }).disableSelection();

            $("#OrgVisibleAttributes li").draggable({ 
               	stop: function(event, ui){
                    // When dragging stops, call the php function	
        			var input = '&item=' + $(this).attr('id') + '&action=org_removeFromVisible'; 
        			$.post("http://myclientbase-sb/index.php/contact/update_settings", input, function(theResponse){
        				$("#org_accordion").html(theResponse); 
        			}); 	
                },
            }).disableSelection();
                    	
        	$( "#OrgOrderVisibleAttributes" ).sortable({
        		opacity: 0.6,
        		cursor: 'move',
        		containment: 'parent',
        		axis: 'y',
        		placeholder: "ui-state-highlight",
        		update: function() {
        			var input = $(this).sortable("serialize") + '&action=org_sort';
        			$.post("http://myclientbase-sb/index.php/contact/update_settings", input, function(theResponse){
        				$(".org_order_accordion").html(theResponse); 
        			});
        		}
        	}).disableSelection();        
        });
        </script>  

<p style="background-color: #fffdd0; border: 1px dotted gray;">Drag attributes from the left to the right to make them visible or from the right to the left to make them invisible. 
All the changes are automatically saved.
</p>
<div id="OrgVisibleAttributes" style="float:right; display:inline; width: 48%; border: 1px solid gray; padding: 3px;">
	<h3>{t}Visible Attributes{/t}<span style="font-size: 13px;"> (found {$org_visible_attributes|@count})</span></h3>
	<ul id="OrgVisibleAttributes" class="connectedSortable">
	{foreach $org_visible_attributes as $key => $attribute_name}
		<li id="OrgVisibleAttributes_{$attribute_name}" style="margin-top: 3px; padding-bottom: 1px; margin-bottom: 5px; margin-left: 3px; margin-right: 3px; background-color: #FFF; width: 390px; border: 1px solid #e8e8e8;">
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
</div>

<div style="width: 48%; border: 1px solid gray; padding: 3px;">
	<h3>{t}Available Attributes{/t}<span style="font-size: 13px;"> (found {$org_available_attributes|@count})</span></h3>
	<ul id="OrgAvailableAttributes" class="connectedSortable">
	{foreach $org_available_attributes as $attribute_name => $attribute_features}
		<li id="OrgAvailableAttributes_{$attribute_name}" style="position: relative; margin-top: 3px; padding-bottom: 1px; margin-bottom: 5px; margin-left: 3px; margin-right: 3px; background-color: #FFF; width: 390px; border: 1px solid #e8e8e8;">
		
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
