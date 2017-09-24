<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config = array(
	'module_name'	=>	$this->lang->line('google'),
	'module_path'	=>	'google',
	'module_order'	=>	2,
	'module_config'	=>	array(
		'settings_view'	=>	'google/display_settings',
		'settings_save'	=>	'google/save_settings'
	)
);

?>