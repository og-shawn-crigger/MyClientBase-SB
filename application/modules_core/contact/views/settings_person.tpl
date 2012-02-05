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

<p style="background-color: #fffdd0; border: 1px dotted gray;">Drag attributes from the left to the right to make them visible or from the right to the left to make them invisible. 
All the changes are automatically saved.
</p>
<div id="PersonVisibleAttributes" style="float:right; display:inline; width: 48%; border: 1px solid gray; padding: 3px;">
	<h3>{t}Visible Attributes{/t}<span style="font-size: 13px;"> (found {$person_visible_attributes|@count})</span></h3>
	<ul id="PersonVisibleAttributes" class="connectedSortable">
	{foreach $person_visible_attributes as $key => $attribute_name}
		<li id="PersonVisibleAttributes_{$attribute_name}" style="margin-top: 3px; padding-bottom: 1px; margin-bottom: 5px; margin-left: 3px; margin-right: 3px; background-color: #FFF; width: 390px; border: 1px solid #e8e8e8;">
			{if $person_all_attributes[$attribute_name]['required'] == 1}
				{$color="red"}
			{else}
				{$color="black"}
			{/if}
			<p style="color:{$color}; margin-bottom: 4px; margin-left: 5px;"><b>{$attribute_name}</b></p> 
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

<div style="width: 48%; border: 1px solid gray; padding: 3px;">
	<h3>{t}Available Attributes{/t}<span style="font-size: 13px;"> (found {$person_available_attributes|@count})</span></h3>
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

<!-- 
<script type="text/javascript">
$(function() {


    
	$("#visibleAttributes").sortable({ 
		opacity: 0.6, 
		cursor: 'move',
		delay: 500, 
		update: function() {
			var order = $(this).sortable("serialize") + '&action=sortVisible'; 
			$.post("http://myclientbase-sb/index.php/contact/test", order, function(theResponse){
				$("#all").html(theResponse);
			}); 															 
		}								  
	});        
    
    
    $("#visibleAttributes li").draggable({ 
        axis: "x",
        cursor: 'crosshair',
        distance: 1,
        containment: 'document',
        grid: [400, 0],
        opacity: 0.80,
        revert: true,
        delay: 500,
       	stop: function(event, ui){
            // When dragging stops, revert the draggable to its
            // original starting position.		
			var input = '&item=' + $(this).attr('id') + '&action=removeFromVisible'; 
			$.post("http://myclientbase-sb/index.php/contact/test", input, function(theResponse){
				$("#all").html(theResponse);
			}); 	
        }, 
    });

    $( "ul, li" ).disableSelection();   
});
</script>


<script type="text/javascript">
$(function() {
    $("#contentLeft div").draggable({
        // Can't use revert, as we animate the original object
        //revert: true,

        axis: "x",
        helper: function(){
            // Create an invisible div as the helper. It will move and
            // follow the cursor as usual.
            return $('<div></div>').css('opacity',0);
        },
        create: function(){
            // When the draggable is created, save its starting
            // position into a data attribute, so we know where we
            // need to revert to.
            var $this = $(this);
            $this.data('startleft',$this.position().left);
        },
        stop: function(event, ui){
            // When dragging stops, revert the draggable to its
            // original starting position.		
			var order = '&item=' + $(this).attr('id') + '&action=addToVisible'; 
			$.post("http://myclientbase-sb/index.php/contact/test", order, function(theResponse){
				$("#all").html(theResponse);
			}); 													 
        },
        drag: function(event, ui){
            // During dragging, animate the original object to
            // follow the invisible helper with custom easing.
            $(this).stop().animate({
                left: ui.helper.position().left
            },1000,'easeOutCirc');           
        }
    });
});
</script>
-->
<!-- 
<script type="text/javascript">
$(function() {
    $("#shownAttributes ul").draggable({
        // Can't use revert, as we animate the original object
        //revert: true,

        axis: "x",
        helper: function(){
            // Create an invisible div as the helper. It will move and
            // follow the cursor as usual.
            return $('<div></div>').css('opacity',0);
        },
        create: function(){
            // When the draggable is created, save its starting
            // position into a data attribute, so we know where we
            // need to revert to.
            var $this = $(this);
            $this.data('startright',$this.position().right);
        },
        stop: function(event, ui){
            // When dragging stops, revert the draggable to its
            // original starting position.		
			var order = '&item=' + $(this).attr('id') + '&action=removeFromVisible'; 
			$.post("http://myclientbase-sb/index.php/contact/test", order, function(theResponse){
				$("#all").html(theResponse);
			}); 													 
        },
        drag: function(event, ui){
            // During dragging, animate the original object to
            // follow the invisible helper with custom easing.
            $(this).stop().animate({
                right: ui.helper.position().right
            },1000,'easeOutCirc');           
        }
    });
});
</script>

 
<script type="text/javascript">
$(document).ready(function(){ 
						   
	$(function() {
		$("#shownAttributes ul").sortable({ 
			opacity: 0.6, 
			cursor: 'move', 
			update: function() {
				var order = $(this).sortable("serialize") + '&action=sortVisible'; 
				$.post("http://myclientbase-sb/index.php/contact/test", order, function(theResponse){
					$("#all").html(theResponse);
				}); 															 
			}								  
		});
	});

});	
</script>
--> 

