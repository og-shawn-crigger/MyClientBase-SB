<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config = array(
	'module_name'	=>	$this->lang->line('contact'),
	'module_path'	=>	'contact',
	'module_order'	=>	5,
	'module_config'	=>	array(
		'settings_view'	=>	'contact/display_settings',
		'settings_save'	=>	'contact/save_settings'
	)
);

?>