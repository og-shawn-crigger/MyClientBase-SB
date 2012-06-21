<script type="text/javascript">
$(document).ready(function() {
		var url = "/index.php/contact/update_settings";

		//refreshes the content of div #person_accordion everytime the accordion is clicked
		jQuery('#contact_accordion').accordion({}).find('.pva').click(		
			function(ev){
				//alert('refresh');
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=person_refresh'; 
				$.post("/index.php/contact/update_settings", input, function(theResponse){
					$("#person_accordion").html(theResponse);
				});
        });
        		
		//refreshes the content of div #person_order_accordion everytime the accordion is clicked
		jQuery('#contact_accordion').accordion({}).find('.poa').click(		
			function(ev){
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=person_sort'; 
				$.post("/index.php/contact/update_settings", input, function(theResponse){
					$("#person_order_accordion").html(theResponse);
				});
        });

		//refreshes the content of div #person_aliases_accordion everytime the accordion is clicked
		jQuery('#contact_accordion').accordion({}).find('.paa').click(
			function(ev){
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=person_aliases'; 
				$.post("/index.php/contact/update_settings", input, function(theResponse){
					$("#person_aliases_accordion").html(theResponse);
				});          
        });

		//refreshes the content of div #organization_accordion everytime the accordion is clicked
		jQuery('#contact_accordion').accordion({}).find('.ova').click(		
			function(ev){
				//alert('refresh');
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=organization_refresh'; 
				$.post("/index.php/contact/update_settings", input, function(theResponse){
					$("#org_accordion").html(theResponse);
				});
        });
		
		//refreshes the content of div #org_order_accordion everytime the accordion is clicked
		jQuery('#contact_accordion').accordion({}).find('.ooa').click(
			function(ev){
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=organization_sort'; 
				$.post("/index.php/contact/update_settings", input, function(theResponse){
					$("#org_order_accordion").html(theResponse);
				});          
        });

		//refreshes the content of div #org_aliases_accordion everytime the accordion is clicked
		jQuery('#contact_accordion').accordion({}).find('.oaa').click(
			function(ev){
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=organization_aliases'; 
				$.post("/index.php/contact/update_settings", input, function(theResponse){
					$("#org_aliases_accordion").html(theResponse);
				});          
        });

		//refreshes the content of div #location_accordion everytime the accordion is clicked
		jQuery('#contact_accordion').accordion({}).find('.lva').click(		
			function(ev){
				//alert('refresh');
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=location_refresh'; 
				$.post("/index.php/contact/update_settings", input, function(theResponse){
					$("#location_accordion").html(theResponse);
				});
        });

		//refreshes the content of div #location_order_accordion everytime the accordion is clicked
		jQuery('#contact_accordion').accordion({}).find('.loa').click(
			function(ev){
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=location_sort'; 
				$.post("/index.php/contact/update_settings", input, function(theResponse){
					$("#location_order_accordion").html(theResponse);
				});          
        });     

		//refreshes the content of div #person_aliases_accordion everytime the accordion is clicked
		jQuery('#contact_accordion').accordion({}).find('.laa').click(
			function(ev){
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=location_aliases'; 
				$.post("/index.php/contact/update_settings", input, function(theResponse){
					$("#location_aliases_accordion").html(theResponse);
				});          
        });           
	});
</script>


<div id="contact_accordion">	

{* persons accordion items *}
	{$obj = "{t}person{/t}"}
	<h3 class="pva"><a href="#"><span style="font-size: 16px;">{$obj|capitalize}</span>: {t}set visible attributes{/t}</a></h3>
	<div id="person_accordion">	
		{$settings_person}
	</div>
	
	<h3 class="poa"><a href="#"><span style="font-size: 16px;">{$obj|capitalize}</span>: {t}set attributes order{/t}</a></h3>
	<div id="person_order_accordion">
		{$settings_person_order}
	</div>

	<h3 class="paa"><a href="#"><span style="font-size: 16px;">{$obj|capitalize}</span>: {t}set attributes aliases{/t}</a></h3>
	<div id="person_aliases_accordion">
		{$settings_person_aliases}
	</div>

{* organizations accordion items *}
	{$obj = "{t}organization{/t}"}		
	<h3 class="ova"><a href="#"><span style="font-size: 16px;">{$obj|capitalize}</span>: {t}set visible attributes{/t}</a></h3>
	<div id="org_accordion">
		{$settings_organization}
	</div>

	<h3 class="ooa"><a href="#"><span style="font-size: 16px;">{$obj|capitalize}</span>: {t}set attributes order{/t}</a></h3>
	<div id="org_order_accordion">
		{$settings_organization_order}
	</div>

	<h3 class="oaa"><a href="#"><span style="font-size: 16px;">{$obj|capitalize}</span>: {t}set attributes aliases{/t}</a></h3>
	<div id="org_aliases_accordion">
		{$settings_organization_aliases}
	</div>

{* locations accordion items *}
	{$obj = "{t}location{/t}"}				
	<h3 class="lva"><a href="#"><span style="font-size: 16px;">{$obj|capitalize}</span>: {t}set visible attributes{/t}</a></h3>
	<div id="location_accordion">
		{$settings_location}
	</div>
	
	<h3 class="loa"><a href="#"><span style="font-size: 16px;">{$obj|capitalize}</span>: {t}set attributes order{/t}</a></h3>
	<div id="location_order_accordion">
		{$settings_location_order}
	</div>

	<h3 class="laa"><a href="#"><span style="font-size: 16px;">{$obj|capitalize}</span>: {t}set attributes aliases{/t}</a></h3>
	<div id="location_aliases_accordion">
		{$settings_location_aliases}
	</div>
	
</div>
