<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Person configuration file
 * Created on Oct 25, 2011 by Damiano Venturin @ squadrainformatica.com
 */

$config['person_show_fields'] = array(  'anniversary', 'assistantName', 'assistantPhone',
							 			'birthDate', 'businessRole', 
										'codiceFiscale',
										'enabled', 'entryUpdatedBy', 'entryCreatedBy', 
										'givenName', 
										'homeFacsimileTelephoneNumber',
										'mail', 'mobile', 'mozillaHomeCountryName', 'mozillaHomeLocalityName', 'mozillaHomePostalCode', 'mozillaHomeState',
										'note', 
										'o', 
										'preferredLanguage',
										'sn',
										'title',
									);

$config['person_hidden_fields'] = array( 'uid','client_id');

/* End of person.php */