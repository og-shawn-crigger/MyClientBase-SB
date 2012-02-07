<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['location_show_fields'] = array('locZip','locStreet','locState','locPhone','locDescription','locCountry','locCity','locLatitude','locLongitude');
$config['location_attributes_aliases'] = array(
				'locLongitude' => 'longitudine',
);
$config['location_hidden_fields'] = array('locId');
