<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config = array(
	'module_path'			=>	'tasks',
	'module_name'			=>	'Tasks',
	'module_description'	=>	'A simple task manager which allows task based invoice creation.',
	'module_author'			=>	'Damiano Venturin',
	'module_homepage'		=>	'http://www.mcbsb.com',
	'module_version'		=>	'0.12.0',
	'module_config'			=>	array(
		'dashboard_widget'	=>	'tasks/dashboard_widget',
		'settings_view'		=>	'tasks/task_settings/display',
		'settings_save'		=>	'tasks/task_settings/save',
		'dashboard_menu'	=>	'tasks/header_menu'
	)
);
/* End of file config.php */
/* Location: ./application/modules_custom/tasks/config/config.php */
?>