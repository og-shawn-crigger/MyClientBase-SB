<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mcbsb  extends CI_Model {
	
	protected $_error;
	protected $_success;
	protected $_warning;
	public $system_messages;
	
	public $_enabled_modules;
	public $_total_rows;
	public $_user;
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load->model('record_descriptor');
		$this->load->model('field_descriptor');
		$this->load->model('db_obj');
		//$this->load->model('system_messages');
		
		$this->initialize();
		
		//$tmp = $this->session->all_userdata();
		$this->_user->id = $this->session->userdata('user_id');
		$this->_user->name = $this->session->userdata('first_name') . ' ' . $this->session->all_userdata('last_name');
		$this->_user->first_name = $this->session->userdata('first_name');
		$this->_user->last_name = $this->session->userdata('last_name');
		$this->_user->is_admin = $this->session->userdata('is_admin');
	}
	
	private function initialize() {
		$CI = get_instance();
	
		//reflects this object
		$reflection = new ReflectionClass($this);
	
		//gets object properties
		$properties = $reflection->getProperties();
	
		if(!empty($properties))
		{
			foreach ($properties as $property) {
	
				$property_name = (string) $property->name;
				//$property_value = $property->getvalue($this);
	
				//if(is_null($property_value) and $property->isPublic()) {
				if($property->isPublic()) {
					if(!preg_match('/^_/', $property_name, $matches))
					{
						//loads the class
						$this->load->model($property_name);
	
						//references the loaded object
						$this->$property_name =& $CI->$property_name;
					}
				}
			}
		}
	}

	//loads an object into $this->$objname
	public function load($obj_name, $alias = null){
		if(!is_string($obj_name)) return false;
		
		$obj_name = strtolower($obj_name);
		if(is_null($alias)) {
			$this->$obj_name = $this->load->model($obj_name);
		} else {
			$this->$alias = $this->load->model($obj_name);
		}
	}
	
	public function __destruct() {

		//this is executed after the controller has been unloaded
		
		//TODO I think I can load the header from here
	}
	
	
	public function get_enabled_modules() {
		$this->load->model('mcb_modules/mdl_mcb_modules');
		return $this->_enabled_modules = $this->mdl_mcb_modules->get_enabled();		
	}
	
	private function set_system_message($type, $text) {
		
// 		if(!is_string($text)) return false;
		
// 		//retrieve messages from session
//  		$tmp = $this->CI->session->flashdata('system_messages');
// 		//$tmp = $this->session->flashdata('system_messages');
// 		if(isset($tmp[$type])) $this->system_messages[$type] = $tmp[$type];
		
// 		//update with the new message
// 		$this->system_messages[$type][] = $text;
// 		$this->CI->session->set_flashdata('system_messages',$this->system_messages);
	}

	private function get_system_messages() {
	
// 		//retrieve messages from session
// 		$tmp = $this->CI->session->flashdata('system_messages');
// 		if(is_array($tmp)) {
// 			$this->system_messages = $tmp;
// 		} else {
// 			return array();
// 		}
		
// 		//translate if possible otherwise show as it is
// 		foreach ($this->system_messages as $type => $messages) {
// 			foreach ($messages as $key => $message) {
// 				if($translation = $this->CI->lang->line($message)) {
// 					$this->system_messages[$type][$key] = $translation;
// 				} 
// 			}	
// 		}
		
// 		//join all the messages in one line by group
// 		foreach ($this->system_messages as $type => $messages) {			
// 			$this->system_messages[$type] = implode(' - ', $messages);
// 		}
		
		return $this->system_messages;
	}
	
	public function display_menu() {
		$plenty_parser = new Plenty_parser();
		$data = array();
		$menu = $plenty_parser->parse('menu.tpl', $data, true, 'smarty');
		return $menu;
	}
	
}