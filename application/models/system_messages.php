<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class System_Messages extends CI_Model {
	
	private $CI;
	
	protected $error = '';
	protected $success = '';
	protected $warning = '';
	protected $all = array();
	
	private $system_message_types = array('success','warning','error');
	
	public function __construct() {
		parent::__construct();
		$this->CI = &get_instance();
	}
	
	public function __destruct() {

	}
	
	public function __set($attribute, $value) {
		if($attribute == 'all') return false;
		
		if (!isset($this->$attribute)) return false;
		
		if(in_array($attribute, $this->system_message_types)) {
			$this->set_system_message($attribute, $value);
		}
		return true;
	}
	
	public function __get($attribute) {
		if($attribute == 'all') 
			return $this->get_system_messages();
		
		return isset($this->$attribute) ? $this->$attribute : null;
	}
	
	private function set_system_message($type, $text) {
		
		if(!is_string($text)) return false;
		
		//retrieve messages from session
 		$tmp = $this->CI->session->flashdata('system_messages');
	
		if(isset($tmp[$type])) $this->all[$type] = $tmp[$type];
		
		//update with the new message
		$this->all[$type][] = $text;
		$this->CI->session->set_flashdata('system_messages',$this->all);
	}

	private function get_system_messages() {
	
		//retrieve messages from session
		$tmp = $this->CI->session->flashdata('system_messages');
		if(is_array($tmp)) {
			$this->all = $tmp;
		} else {
			return array();
		}
		
		//translate if possible otherwise show as it is
		foreach ($this->all as $type => $messages) {
			foreach ($messages as $key => $message) {
				if($translation = $this->CI->lang->line($message)) {
					$this->all[$type][$key] = $translation;
				} 
			}	
		}
		
		//join all the messages in one line by group
		foreach ($this->all as $type => $messages) {			
			$this->all[$type] = implode(' - ', $messages);
		}
		
		return $this->all;
	}
	
	public function display_menu() {
		$plenty_parser = new Plenty_parser();
		$data = array();
		//$menu = $plenty_parser->parse('menu.tpl', $data, true, 'smarty', 'mcbsbmanager');
		$menu = $plenty_parser->parse('menu.tpl', $data, true, 'smarty');
		return $menu;
	}
	
}