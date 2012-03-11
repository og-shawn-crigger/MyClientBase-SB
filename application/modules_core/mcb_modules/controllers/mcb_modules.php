<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/*
 * Class handling MCB custom modules install/uninstall and core modules enable/disable
 */
class Mcb_Modules extends Admin_Controller {

	function __construct() {

		parent::__construct();
		
	}

	function index() {

		$this->mdl_mcb_modules->refresh();

		$data = array(
			'modules'	=>	$this->mdl_mcb_modules->custom_modules
		);

		$this->load->view('index', $data);

	}

	//DAM: lists core modules so that they can be enabled or disabled
	public function core() {
	
		$this->mdl_mcb_modules->refresh();
	
		$data = array(
				'modules'	=>	$this->mdl_mcb_modules->core_modules
		);
	
		$this->load->view('core', $data);
	
	}
	
	//DAM: it runs common code for several methods. Check below
	private function getDbItem()
	{
		$module_path = $this->uri->segment(3);
		
		$this->db->where('module_path', $module_path);
		
		return $module_path;
	}
	
	//DAM checks in the database to see if the core module can be enabled/disabled
	private function status_can_change()
	{
		$module_path = $this->uri->segment(3);
		$this->mdl_mcb_modules->refresh();
		$modules = $this->mdl_mcb_modules->core_modules;
		if($modules[$module_path]->module_change_status != "1")
		{
			$this->session->set_flashdata('custom_error', $this->lang->line('module_cant_change_status'));
			return false;
		} else {
			return true;
		}
			
	}
	
	//DAM: enables a core module
	public function enable() {
		
		if (!$this->status_can_change()) {
			redirect('mcb_modules/core');
		}
		
		$this->getDbItem();
			
		$db_array = array(
					'module_enabled'	=>	1
		);
		
		$this->db->update('mcb_modules', $db_array);
		
		$this->session->set_flashdata('custom_success', $this->lang->line('module_successfully_enabled'));
		
		redirect('mcb_modules/core');
	}

	//DAM: disables a core module
	public function disable() {
		if (!$this->status_can_change()) redirect('mcb_modules/core'); 
			
		$this->getDbItem();
		
		$db_array = array(
							'module_enabled'	=>	0
		);
		
		$return = $this->db->update('mcb_modules', $db_array);
		
		$this->session->set_flashdata('custom_success', $this->lang->line('module_successfully_disabled'));
		
		redirect('mcb_modules/core');
		
	}
	
	//DAM: installs a custom module
	public function install() {
		
		$module_path = $this->getDbItem();
		
		$this->load->module($module_path . '/setup');
		$this->setup->install();

		$db_array = array(
			'module_enabled'	=>	1
		);

		$this->db->update('mcb_modules', $db_array);

		redirect('mcb_modules');

	}

	/*
	 * DAM: uninstalls a custom module
	 * 
	 * 1. Runs the setup/uninstall function of the custom module.
	 * 2. Changes the status of the module to disabled.
	 * 
	*/
	public function uninstall() {
				
		$module_path = $this->getDbItem();
				
		$this->load->module($module_path . '/setup');
		$this->setup->uninstall();

		$this->db->delete('mcb_modules');	

		redirect('mcb_modules');

	}

	/*
	 * Runs the setup/upgrade function of the custom module.
	*/
	public function upgrade() {

		$module_path = $this->getDbItem();

		$this->load->module($module_path . '/setup');

		$this->setup->upgrade();

		redirect('mcb_modules');
	}

}

?>