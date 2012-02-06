<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Extends Class Mdl_Contact
 * 
 * Created by Damiano Venturin @ squadrainformatica.com
 */


class Mdl_Person extends Mdl_Contact {

	public $uid;	//mandatory
	public $cn;		//mandatory
	
    public function __construct() {
		
    	//@todo another marker check
        parent::__construct();
        
        $this->objName = 'person';
        
        $this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/person/'));        
    	
    }

    public function arrayToObject(array $person)
    {
    	$return = parent::arrayToObject($person, 'person');
    	
    	//this is for MCB compatibility
    	$this->client_id = $this->uid;
    	$this->client_name = $this->cn;
    	
    	return (!empty($this->uid) && !empty($this->cn)) ? $return : false;    
    }
    
    public function prepareShow()
    {
    	$this->load->config('person');
    	$this->show_fields = $this->config->item('person_show_fields');
    	$this->aliases = $this->config->item('person_attributes_aliases');
    	$this->hidden_fields = $this->config->item('person_hidden_fields');
    }
    
//     public function update($input)
//     {	
//     	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/person/')); //TODO is this necessary?
//     	$rest_return = $this->rest->get('update', $input, 'serialize');
    
//     	return $rest_return;
//     }

//     public function create($input)
//     {
//     	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/person/')); //TODO is this necessary?
//     	$rest_return = $this->rest->get('create', $input, 'serialize');
    
//     	return $rest_return;
//     }    
}

?>