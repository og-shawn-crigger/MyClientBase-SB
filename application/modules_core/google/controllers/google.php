<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//modified by Damiano Venturin @ squadrainformatica.com

class Google extends Admin_Controller {

	public $enabled_modules;
	public $config_items;
	
    function __construct() {

        parent::__construct();

        $this->config->load('google',false,true);
        
        $this->enabled_modules = $this->mdl_mcb_modules->get_enabled();
        
        $this->config_items = array('google_contact_sync','google_domain','google_admin_email','google_admin_password');
    }

    public function index() {
    	return true;
    }
    
    public function display_settings() {
    	
     	$data = array();
     	foreach ($this->config_items as $item_name) {
     		$data[$item_name] = $this->config->item($item_name);
     	}
    	$this->plenty_parser->parse('settings.tpl', $data, false, 'smarty', 'google');
    }
    
   
    public function save_settings()
    {	 
    	//update the configuration file
    	$this->load->helper('configfile');
    	 
    	$configfile = "google";
    	
     	foreach ($this->config_items as $item_name) {
     		
     		switch ($item_name) {

     			case 'google_contact_sync':
     				
     				if($this->input->post($item_name)) {
     					$this->config->set_item($item_name, "true");
     				} else {
     					$this->config->set_item($item_name, "false");
     				}
     				
     			break;

     			case 'google_admin_password':
     				
     				//do both the pass fields match in content?
     				if($this->input->post($item_name) != $this->input->post('google_admin_confirm_password')) return false;
     				
     				//is the password changed?
     				if($this->input->post($item_name) != $this->config->item($item_name)) {
     					$submitted_pwd = $this->input->post($item_name);
     					$pwd = system('/usr/local/bin/gads_pwd.sh '.escapeshellarg($submitted_pwd), $retval);
     					$this->config->set_item($item_name, $pwd);
     				}
     				
     			break;

     			default:
     				
     				$this->config->set_item($item_name, $this->input->post($item_name));
     				
     			break;
       		}
     	}
    	 
    	return write_config($configfile, $this->config_items, true) ? true : false;
    }    
}