{if {preg_match pattern="dueviPerson" subject=$contact->objectClass}}
	{$contact_ref = $contact->cn}
	{$contact_id = $contact->uid}
	{$contact_id_key = "uid"}
{/if}		

{if {preg_match pattern="dueviOrganization" subject=$contact->objectClass}}
	{$contact_ref = $contact->o}
	{$contact_id = $contact->oid}
	{$contact_id_key = "oid"}
{/if}					

<script type="text/javascript">
	$(document).ready(function() {
		$("#show_organization_link").click(function(){
			$("#search_organization").toggle();
			$("#search_organization").animate({
				width: "100%",
				//opacity: 0.4,
				marginTop: "-5px",
				//marginLeft: "0.6in",
				fontSize: "3em",
				borderWidth: "10px"
			}, 0 );
			$("#input_search").focus();
		});
	});
</script>

<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px;">

	<h3 class="title_black">{t}Actions Panel{/t}</h3>

	<ul class="quicklinks content toggle" >
		<li><a id="back_to_profile" href="/contact/">{t}Search contact{/t}</a></li>
		{if !$profile_view}<li><a id="back_to_profile" href="/index.php/contact/details/{$contact_id_key}/{$contact_id}">{t}Back to profile{/t}</a></li>{/if}
		{if $profile_view}<li> <a id="edit_profile" href="/index.php/contact/form/{$contact_id_key}/{$contact_id}">{t}Edit profile{/t}</a></li>{/if}
		{if $contact_id_key == 'uid'}
			{$object_type = 'person'}
		{/if}
		{if $contact_id_key == 'oid'}
			{$object_type = 'organization'}
		{/if}
		
		<li><a href="#" onClick="jqueryForm({ 'form_type':'form','object_name':'location','related_object_name':'{$object_type}','related_object_id':'{$contact_id}','hash':'set_here_the_hash' })">{t}Add location{/t}</a></li>
		<li><a id="show_organization_link" href="#">{t}Associate Organization{/t}</a></li>
		<div id="search_organization" title="Form" style="display: none;">
			<form style="background-color: white;" onsubmit="search({ 'form_type':'search','object_name':'organization','related_object_name':'{$object_type}','related_object_id':'{$contact_id}','hash':'set_here_the_hash' })"">
				<input style="margin-right: 5px; width: 225px;"type="text" name="input_search" id="input_search" />
				<p style="font-size: 10px; color: gray; font-style: italic;">{t}search for name, vat, phone, email, website{/t}</p>
			</form>
		</div>
		<!--
		<li>
				<form method="post" action="" style="display: inline;">
				<input type="submit" name="btn_edit_client" style="float: right; margin-top: 10px; margin-right: 10px;" value="{citranslate lang=$language text='edit_client'}" />
                <input type="submit" name="btn_add_invoice" style="float: right; margin-top: 10px; margin-right: 10px;" value="{citranslate lang=$language text='create_invoice'}" />
				<input type="submit" name="btn_add_quote" style="float: right; margin-top: 10px; margin-right: 10px;" value="{citranslate lang=$language text='create_quote'}" />
				</form>
		</li>
		 -->						
	</ul>

</div>