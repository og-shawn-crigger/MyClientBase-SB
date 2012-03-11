<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['location_show_fields'] = array('locDescription','locStreet','locZip','locCity','locState','locCountry','locLatitude','locLongitude','locPhone');
$config['location_attributes_aliases'] = array(
				'locDescription' => 'description',
				'locStreet' => 'address',
				'locZip' => 'zip',
				'locCity' => 'city',
				'locState' => 'state',
				'locCountry' => 'country',
				'locLatitude' => 'latitude',
				'locLongitude' => 'longitudine',
				'locPhone' => 'land-line',
);
$config['location_hidden_fields'] = array('locId');
