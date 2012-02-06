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
	});
</script>


<div id="contact_accordion">	

{* persons accordion items *}
	<h3 class="pva"><a href="#">{t}Person{/t}: {t}set visible attributes{/t}</a></h3>
	<div id="person_accordion">	
		{$settings_person}
	</div>
	
	<h3 class="poa"><a href="#">{t}Person{/t}: {t}set attributes order{/t}</a></h3>
	<div id="person_order_accordion">
		{$settings_person_order}
	</div>

	<h3 class="paa"><a href="#">{t}Person{/t}: {t}set attributes aliases{/t}</a></h3>
	<div id="person_aliases_accordion">
		{$settings_person_aliases}
	</div>

{* organizations accordion items *}		
	<h3 class="ova"><a href="#">{t}Organization{/t}: {t}set visible attributes{/t}</a></h3>
	<div id="org_accordion">
		{$settings_organization}
	</div>

	<h3 class="ooa"><a href="#">{t}Organization{/t}: {t}set attributes order{/t}</a></h3>
	<div id="org_order_accordion">
		{$settings_organization_order}
	</div>

	<h3 class="oaa"><a href="#">{t}Organization{/t}: {t}set attributes aliases{/t}</a></h3>
	<div id="org_aliases_accordion">
		{$settings_organization_aliases}
	</div>

{* locations accordion items *}		
	<h3 class="lva"><a href="#">{t}Location{/t}: {t}set visible attributes{/t}</a></h3>
	<div id="location_accordion">
		<!-- {$settings_location}  -->
		<p>{t}Not ready yet{/t}</p>
	</div>
	
	<h3 class="loa"><a href="#">{t}Location{/t}: {t}set attributes order{/t}</a></h3>
	<div id="location_order_accordion">
		<p>{t}Not ready yet{/t}</p>
	</div>
	
</div>
