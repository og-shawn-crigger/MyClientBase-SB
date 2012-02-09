<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['person_show_fields'] = array('title','sn','givenName','codiceFiscale','homePostalAddress','mozillaHomePostalCode','mozillaHomeLocalityName','mozillaHomeState','mozillaHomeCountryName','mobile','homePhone','homeFacsimileTelephoneNumber','companyPhone','facsimileTelephoneNumber','managerName','assistantName','assistantPhone','labeledURI','calendarURI','freeBusyURI','preferredLanguage','birthDate','enabled','jpegPhoto');
$config['person_attributes_aliases'] = array(
				'title' => 'title',
				'sn' => 'last_name',
				'givenName' => 'first_name',
				'homePostalAddress' => 'address',
				'mozillaHomePostalCode' => 'zip',
				'mozillaHomeLocalityName' => 'city',
				'mozillaHomeState' => 'state',
				'mozillaHomeCountryName' => 'country',
);
$config['person_hidden_fields'] = array('uid','client_id');
