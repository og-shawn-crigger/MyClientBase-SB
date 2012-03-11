<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['person_show_fields'] = array('title','sn','givenName','codiceFiscale','homePostalAddress','mozillaHomePostalCode','mozillaHomeLocalityName','mozillaHomeState','mozillaHomeCountryName','mobile','homePhone','homeFacsimileTelephoneNumber','companyPhone','facsimileTelephoneNumber','mail','managerName','assistantName','assistantPhone','labeledURI','calendarURI','freeBusyURI','preferredLanguage','birthDate','enabled','jpegPhoto');
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
				'managerName' => 'manager',
				'assistantName' => 'assistant',
				'assistantPhone' => 'assistant_phone',
				'labeledURI' => 'blog',
				'calendarURI' => 'online_calendar',
				'freeBusyURI' => 'online_free-busy',
				'preferredLanguage' => 'spoken_language',
				'birthDate' => 'birthdate',
				'jpegPhoto' => 'photo',
);
$config['person_hidden_fields'] = array('uid','client_id');
