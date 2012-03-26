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


<div class="section_wrapper" style="clear:right; float:right; display:inline; width: 280px;">

	<h3 class="title_black">{t}Actions Panel{/t}</h3>

	<ul class="quicklinks content toggle" >
		{if !$profile_view}<li><a id="back_to_profile" href="/index.php/contact/details/{$contact_id_key}/{$contact_id}">{t}Back to profile{/t}</a></li>{/if}
		{if $profile_view}<li> <a id="edit_profile" href="/index.php/contact/form/{$contact_id_key}/{$contact_id}">{t}Edit profile{/t}</a></li>{/if}
		{if $contact_id_key == 'uid'}
			{$related_object_name = 'person'}
		{/if}
		{if $contact_id_key == 'oid'}
			{$related_object_name = 'organization'}
		{/if}
		
		<li><a id="add-location" href="#" onClick="jqueryForm({ 'object_name':'location','related_object_name':'{$related_object_name}','related_object_id':'{$contact_id}','hash':'set_here_the_hash'})">{t}Add location{/t}</a></li>
		<!-- 
		<li>Add to an organization</li>
		<li>Add a location</li>
		
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