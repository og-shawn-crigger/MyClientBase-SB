<script type="text/javascript">
	$(function() {
		//refreshes the content of div #person_order_accordion everytime the accordion is clicked
		jQuery('#contact_accordion').accordion({}).find('.poa').click(
			function(ev){
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=person_sort'; 
				$.post("http://myclientbase-sb/index.php/contact/update_settings", input, function(theResponse){
					$("#person_order_accordion").html(theResponse);
				});          
        });
        
		//refreshes the content of div #org_order_accordion everytime the accordion is clicked
		jQuery('#contact_accordion').accordion({}).find('.ooa').click(
			function(ev){
				ev.preventDefault();
			    ev.stopPropagation();
				var input = '&action=org_sort'; 
				$.post("http://myclientbase-sb/index.php/contact/update_settings", input, function(theResponse){
					$("#org_order_accordion").html(theResponse);
				});          
        });        
	});
</script>

<div id="contact_accordion">	
	<h3><a href="#">{t}Person{/t}: {t}set visible attributes{/t}</a></h3>
	<div id="person_accordion">	
		{$settings_person}
	</div>
	
	<h3 class="poa"><a href="#">{t}Person{/t}: {t}set attributes order{/t}</a></h3>
	<div id="person_order_accordion">
		{$settings_person_order}
	</div>
	
	<h3><a href="#">{t}Organization{/t}: {t}set visible attributes{/t}</a></h3>
	<div id="org_accordion">
		{$settings_organization}
	</div>

	<h3 class="ooa"><a href="#">{t}Organization{/t}: {t}set attributes order{/t}</a></h3>
	<div id="org_order_accordion">
		{$settings_organization_order}
	</div>

		
	<h3><a href="#">{t}Location{/t}: {t}set visible attributes{/t}</a></h3>
	<div id="location_accordion">
		<!-- {$settings_location}  -->
		<p>{t}Not ready yet{/t}</p>
	</div>
	
	<h3><a href="#">{t}Location{/t}: {t}set attributes order{/t}</a></h3>
	<div id="location_order_accordion">
		<p>{t}Not ready yet{/t}</p>
	</div>
</div>
