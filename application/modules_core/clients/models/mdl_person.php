<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//modified by Damiano Venturin @ squadrainformatica.com

class Mdl_Person extends Mdl_Contact {

	public $uid;	//mandatory
	public $cn;		//mandatory
		
    public function __construct() {

        parent::__construct();
        
        $this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/person/'));        
    	
    }

    public function arrayToObject(array $person)
    {
    	$return = parent::arrayToObject($person, 'person');
    	
    	return (!empty($this->uid) && !empty($this->cn)) ? $return : false;    
    }
	
}

?>