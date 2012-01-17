<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Extends Class Mdl_Contact
 * 
 * Created by Damiano Venturin @ squadrainformatica.com
 */

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
    
    	//this is for MCB compatibility
    	$this->client_id = $this->oid;
    	$this->client_name = $this->o;	 
    	
    	return (!empty($this->oid) && !empty($this->o)) ? $return : false;    
    }
    
    public function prepareShow()
    {
    	$this->load->config('organization');
    	$this->show_fields = $this->config->item('organization_show_fields');
    	$this->hidden_fields = $this->config->item('organization_hidden_fields');
    }    

//     public function update($input)
//     {
//     	$rest_return = $this->rest->post('update', $input, 'serialize');
    
//     	return $rest_return;
//     }
    
    //
    
    public function create($input)
    {
    	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/organization/')); //TODO is this necessary?
    	return parent::create($input);
    }
}

?>