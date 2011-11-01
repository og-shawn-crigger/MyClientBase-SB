<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Person configuration file
 * Created on Oct 25, 2011 by Damiano Venturin @ squadrainformatica.com
 */

$config['organization_show_fields'] = array( 
										 'businessActivity', 
										 'c','codiceFiscale',
										 'description', 
										 'enabled', 'entryUpdatedBy', 'entryCreatedBy',
										 'facsimileTelephoneNumber',
										 'l',
										 'o', 'oURL', 'omail',
										 'postalAddress', 'postalCode',
										 'st', 'telephoneNumber',
										 'vatNumber',
									);

$config['organization_hidden_fields'] = array( 'oid','client_id');

/* End of person.php */