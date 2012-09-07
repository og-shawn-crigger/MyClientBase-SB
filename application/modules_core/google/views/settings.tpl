<p>{t}By enabling Google Contacts synchronization (GADS), all the contacts set in MCBSB will be synchronized with you Google Account{/t}</p>
<p>{t}This will provide{/t}:
<ul style="margin-left: 15px; margin-top: 0px;">
	<li>- Email autocomplete when you write a new email</li>
	<li>- Possibility to import any business contact into your personal contacts. Consequently you'll find all your personal contacts inside you mobile phone address book</li>
	<li>- Possibility to import any business contact into your Google+ account </li>
	<li>- Possibility to import any business contact into your LinkedIn account</li>
	<li>- Capability to look for any business contact from the mobile phone even if it's not in your personal contacts</li>
	<li>- Possibility to use <a href="http://support.google.com/a/bin/answer.py?hl=en&answer=115739&ctx=cb&src=cb&cbid=-ye89m9c89gvz&cbrank=2" target="_blank">Google Secure Data Connector</a> (SDC) to connect gadgets, applications, and spreadsheets to your data</li>
</ul>
</p>

<dl>
    <dt>{t}Enable Google contacts synchronization?{/t}:</dt>
    {if $google_contact_sync == "true"}
    	{$checked='checked="checked"'}
    {else}
		{$checked=''}
    {/if}
    <dd><input type="checkbox" name="google_contact_sync" {$checked}></dd>
</dl>

<dl>
    <dt>{t}Google business domain{/t}:</dt>
    <dd><input type="text" name="google_domain" value="{$google_domain}" size="100" maxlenght="100" style="width: 500px;"></dd>
</dl>

<dl>
    <dt>{t}Admin email{/t}:</dt>
    <dd><input type="text" name="google_admin_email" value="{$google_admin_email}" size="100" maxlenght="100" style="width: 500px;"></dd>
</dl>

<dl>
    <dt>{t}Password{/t}:</dt>
    <dd><input type="password" name="google_admin_password" value="{$google_admin_password}"  size="100" maxlenght="100" style="width: 500px;"></dd>
</dl>

<dl>
    <dt>{t}Confirm password{/t}:</dt>
    <dd><input type="password" name="google_admin_confirm_password" value="{$google_admin_password}"  size="100" maxlenght="100" style="width: 500px;"></dd>
</dl>