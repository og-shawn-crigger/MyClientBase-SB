<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['organization_show_fields'] = array('o','oType','description','businessCategory','businessActivity','businessAudience','vatNumber','codiceFiscale','street','postalCode','l','st','c','postOfficeBox','oMobile','telephoneNumber','facsimileTelephoneNumber','oURL','omail','enabled');
$config['organization_attributes_aliases'] = array(
				'o' => 'organization',
				'oType' => 'organization_type',
				'description' => 'description',
				'businessCategory' => 'category',
				'businessActivity' => 'activity',
				'businessAudience' => 'client_base',
				'vatNumber' => 'vat_number',
				'codiceFiscale' => 'codice_fiscale',
				'street' => 'address',
				'l' => 'city',
				'postalCode' => 'zip',
				'st' => 'state_-_province',
				'c' => 'country',
				'postOfficeBox' => 'po-box',
				'oMobile' => 'mobile',
				'telephoneNumber' => 'telephone',
				'facsimileTelephoneNumber' => 'fax',
				'oURL' => 'website',
				'omail' => 'e-mail',
);
$config['organization_hidden_fields'] = array('oid');
