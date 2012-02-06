<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['organization_show_fields'] = array('enabled','businessAudience','oURL','facsimileTelephoneNumber','adminUid','c','codiceFiscale','description','o','l','businessActivity','businessCategory');
$config['organization_attributes_aliases'] = array(
				'businessAudience' => 'clientela',
				'oURL' => 'website',
				'facsimileTelephoneNumber' => 'fax',
);
$config['organization_hidden_fields'] = array('oid');
