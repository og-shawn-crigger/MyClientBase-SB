<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['person_show_fields'] = array('anniversary','audio','assistantName','businessCategory','callbackPhone','uid','acceptsCommercialAgreement','acceptsCommercialCommunications','acceptsPrivacy');
$config['person_attributes_aliases'] = array(
				'businessCategory' => 'cat_lavorativa',
				'callbackPhone' => 'tel_da_richiamare',
				'acceptsCommercialAgreement' => 'accetta_le_condizioni',
);
$config['person_hidden_fields'] = array('uid');
