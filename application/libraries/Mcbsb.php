<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mcbsb {
	
	private $CI;
	
	protected $error;
	protected $success;
	protected $warning;
	protected $system_messages;
	
	public function __construct() {
		$this->CI = &get_instance();
		
		$this->error = '';
		$this->success = '';
		$this->warning = '';
		$this->system_messages = array();

	}
	
	public function __destruct() {
		//this is executed after the controller has been unloaded
		
		//TODO I think I can load the header from here
	}
	
	public function __set($attribute, $value) {
		if($attribute == 'system_messages') return false;
		
		$system_message_types = array('success','warning','error');
		
		if (!isset($this->$attribute)) return false;
		
		if(in_array($attribute, $system_message_types)) {
			$this->set_system_message($attribute, $value);
		}
		return true;
	}
	
	public function __get($attribute) {
		if($attribute == 'system_messages') 
			return $this->get_system_messages();
		
		return isset($this->$attribute) ? $this->$attribute : null;
	}
	
	private function set_system_message($type, $text) {
		
		if(!is_string($text)) return false;
		
		//retrieve messages from session
		$tmp = $this->CI->session->flashdata('system_messages');
		if(isset($tmp[$type])) $this->system_messages[$type] = $tmp[$type];
		
		//update with the new message
		$this->system_messages[$type][] = $text;
		$this->CI->session->set_flashdata('system_messages',$this->system_messages);
	}

	private function get_system_messages() {
	
		//retrieve messages from session
		$tmp = $this->CI->session->flashdata('system_messages');
		if(is_array($tmp)) {
			$this->system_messages = $tmp;
		} else {
			return array();
		}
		
		//translate if possible otherwise show as it is
		foreach ($this->system_messages as $type => $messages) {
			foreach ($messages as $key => $message) {
				if($translation = $this->CI->lang->line($message)) {
					$this->system_messages[$type][$key] = $translation;
				} 
			}	
		}
		
		//join all the messages in one line by group
		foreach ($this->system_messages as $type => $messages) {			
			$this->system_messages[$type] = implode(' - ', $messages);
		}
		
		return $this->system_messages;
	}
	
	public function display_menu() {
		$plenty_parser = new Plenty_parser();
		$data = array();
		//$menu = $plenty_parser->parse('menu.tpl', $data, true, 'smarty', 'mcbsbmanager');
		$menu = $plenty_parser->parse('menu.tpl', $data, true, 'smarty');
		return $menu;
	}
	
}