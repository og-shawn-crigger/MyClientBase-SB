<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MCB_Menu extends Admin_Controller {

	function __construct() {

		parent::__construct();

	}

	//Get core modules status
	public function get_core_modules_status()
	{
		$this->mdl_mcb_modules->refresh();
		$modules = $this->mdl_mcb_modules->core_modules;
		$status_items = array();
		foreach ($modules as $key => $module)
		{
			if($module->module_enabled == "1")
			{
				$status_items[$key] = 'enabled';
			} else {
				$status_items[$key] = 'disabled';
			}
		}
		return $status_items;
	}
	
	function generate() {

        $menu_items = $this->config->item('mcb_menu');
		
        //DAM
        $status_items = $this->get_core_modules_status();
        //$status_items = $this->config->item('mcb_module');
        
        foreach ($menu_items as $key => $menu_item) {
			//DAM
        	if(isset($status_items[$key]) && $status_items[$key]=="disabled") {
        		unset($menu_items[$key]); 
        	}
        	        	
            if (!$this->session->userdata('global_admin')) {

                if (isset($menu_item['global_admin'])) {

                    unset($menu_items[$key]);

                }

                elseif (isset($menu_item['submenu'])) {

                    foreach ($menu_item['submenu'] as $sub_key=>$sub_item) {

                        if (isset($sub_item['global_admin'])) {

                            unset($menu_items[$key]['submenu'][$sub_key]);

                        }

                    }

                }
            }
        }

        //TODO is the items position handled somewhere?
        
        
        //TODO this is a quick and dirty. It requires some adjustments: 
        //for instance submenus are not taken in consideration
         
        //adding custom modules items
        foreach ($this->mdl_mcb_modules->custom_modules as $module) {
        	if ($module->module_enabled) { 
        		//do not override core modules
        		if(!isset($menu_items[$module->module_name])){
        			$menu_items[$module->module_name]['title'] = $module->module_name;
        			$menu_items[$module->module_name]['href'] = $module->module_path;
        		}
        	}
        }
        			        
        return $menu_items;
    }

    /*
    //TODO I'm commenting this on purpose to see what breaks
	function generate_control_center() {

		$control_center = $this->config->item('control_center');

		foreach ($control_center as $key=>$item) {

			if (!$this->session->userdata('global_admin')) {

				if (isset($item['global_admin'])) {

					unset($control_center[$key]);

				}

			}

		}

		return $control_center;

	}
	*/
    
	function display($params = null) {
 
		$a = '';
		
		$data = array(
			'menu_items'    =>  $this->generate()
		);
		
		//gets the html menu from the template
		$plenty_parser = new Plenty_parser();	
		$plenty_parser->parse('menu.tpl', $data, false, 'smarty');
		
		//$this->load->view($params['view'], $data);
		
	}

	/*
	 //TODO I'm commenting this on purpose to see what breaks
	function display_control_center($params) {

		if ($this->uri->segment(1) == 'dashboard') {

			$data = array(
				'menu_items'    =>  $this->generate_control_center()
			);

			$this->load->view($params['view'], $data);

		}

	}
	*/
	
	function check_permission($uri_string, $global_admin) {

		foreach ($this->config->item('mcb_menu') as $menu_item) {

        	//DAM
			$status_items = $this->get_core_modules_status();
			
        	//if the requested page is part of a disabled module, than redirects to the dashboard
        	foreach ($status_items as $key => $status)        
        	if(preg_match_all('/^'.$key.'/', $uri_string, $matches) && $status=="disabled")	
        	{
        		redirect('dashboard');
        		break;
        	}
			//\DAM
        	
			if (strpos($menu_item['href'], $uri_string) === 0) {

				if (isset($menu_item['global_admin']) and !$global_admin) {

					redirect('dashboard');

				}

			}

			if (isset($menu_item['submenu'])) {

				foreach ($menu_item['submenu'] as $sub_item) {

					if (strpos($sub_item['href'], $uri_string) === 0) {

						if (isset($sub_item['global_admin']) and !$global_admin) {

							redirect('dashboard');

						}

					}


				}

			}

		}

		foreach ($this->config->item('control_center') as $menu_item) {

			if (strpos($menu_item['href'], $uri_string) === 0) {

				if (isset($menu_item['global_admin']) and !$global_admin) {

					redirect('dashboard');

				}

			}

		}

	}

}

?>
