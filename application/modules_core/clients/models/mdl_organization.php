<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//modified by Damiano Venturin @ squadrainformatica.com

class Mdl_Organization extends Mdl_Contact {

	public $oid;	//mandatory
	public $o;		//mandatory
	
    public function __construct() {

        parent::__construct();
        
        $this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/organization/'));        
                
    }
        
    public function arrayToObject(array $organization)
    {
    	$return = parent::arrayToObject($organization,'organization');
    	
    	return (!empty($this->oid) && !empty($this->o)) ? $return : false;    
    }

}

?>