<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['person_show_fields'] = array('title','sn','givenName','codiceFiscale','homePostalAddress','mozillaHomePostalCode','mozillaHomeLocalityName','mozillaHomeState','mozillaHomeCountryName','mobile','homePhone','homeFacsimileTelephoneNumber','companyPhone','facsimileTelephoneNumber','mail','managerName','assistantName','assistantPhone','labeledURI','calendarURI','freeBusyURI','preferredLanguage','birthDate','jpegPhoto','enabled','acceptsCommercialCommunications','category','categories','businessCategory','facebookURI','githubURI','googleplusURI','linkedinURI','twitterURI');
$config['person_attributes_aliases'] = array(
				'title' => 'title',
				'sn' => 'last_name',
				'givenName' => 'first_name',
				'codiceFiscale' => 'codice_fiscale',
				'homePostalAddress' => 'address',
				'mozillaHomePostalCode' => 'zip',
				'mozillaHomeLocalityName' => 'city',
				'mozillaHomeState' => 'state',
				'mozillaHomeCountryName' => 'country',
				'mobile' => 'mobile',
				'homePhone' => 'home_phone',
				'homeFacsimileTelephoneNumber' => 'home_fax',
				'companyPhone' => 'organization_phone',
				'facsimileTelephoneNumber' => 'organization_fax',
				'mail' => 'e-mail',
				'managerName' => 'manager_name',
				'assistantName' => 'assistant_name',
				'assistantPhone' => 'assistant_phone',
				'labeledURI' => 'blog',
				'calendarURI' => 'online_calendar',
				'freeBusyURI' => 'online_free-busy',
				'preferredLanguage' => 'spoken_language',
				'birthDate' => 'birthdate',
				'jpegPhoto' => 'photo',
				'acceptsCommercialCommunications' => 'we_can_send_communications',
);
$config['person_hidden_fields'] = array('uid','client_id');
