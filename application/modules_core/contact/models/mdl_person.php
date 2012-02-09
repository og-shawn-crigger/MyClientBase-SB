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
		
        parent::__construct();
        
        $this->objName = 'person';
        
        $this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/person/'));        
    	
    }

    //TODO shouldn't be at least protected?
    public function arrayToObject(array $data, $returnMe = false)
    {
    	if(!$return = parent::arrayToObject($data, $this->objName)) return false;
    	
    	//this is for MCB compatibility
    	$this->client_id = $this->uid;
    	$this->client_name = $this->cn;
    	
    	if($returnMe) return $this; //for details, check $contact->get
    	
    	return (!empty($this->uid) && !empty($this->cn)) ? $return : false;    
    }

    public function get(array $input = null)
    {
    	if((is_null($input) || (is_array($input) && count($input)==0)) && is_null($this->uid)) return false;
    	$input = array();
    	$input['filter'] = '(uid='.$this->uid.')';
    	
    	$rest_return = parent::__construct($input,true);
    	
    }   
     
    public function prepareShow()
    {
    	$this->load->config('person');
    	$this->show_fields = $this->config->item('person_show_fields');
    	$this->aliases = $this->config->item('person_attributes_aliases');
    	$this->hidden_fields = $this->config->item('person_hidden_fields');
    }
    
    public function getMandatoryAttributes()
    {
    	if(empty($this->properties))
    	{
    		if(!$this->getProperties()) return false;
    	}	
    	$this->mandatoryAttributes = array();
    	foreach ($this->properties as $attribute => $settings) {
    		if($settings['required']) $this->mandatoryAttributes[] = $attribute;
    	}
    	return true;
    }
    
    public function save()
    { 		
    	//rules //TODO necessary?
    	if(empty($this->sn)) return false;
    	if(empty($this->givenName)) return false;
    	($this->enabled) ? $this->enabled = 'TRUE' : $this->enabled = 'FALSE';
    	
    	if($this->getMandatoryAttributes())
    	{
    		foreach ($this->mandatoryAttributes as $mandatoryAttribute) {
    			if(empty($this->$mandatoryAttribute)) {
	    			switch ($mandatoryAttribute) {
	    				case 'entryUpdatedBy':
	    					$this->$mandatoryAttribute = $this->session->userdata('last_name').' '.$this->session->userdata('first_name');
	    				break;
	    				
	    				case 'category':
	    					$this->$mandatoryAttribute = 'mycategory';
	    				break;
	    				
	    				case 'cn':
	    					$this->$mandatoryAttribute = $this->sn.' '.$this->givenName;
	    				break;
	    					 
	    				case 'displayName':
	    					$this->$mandatoryAttribute = $this->givenName.' '.$this->sn;
	    				break;

	    				case 'fileAs':
	    					$this->$mandatoryAttribute = $this->sn.' '.$this->givenName;
	    				break;

	    				case 'userPassword':
	    					$this->$mandatoryAttribute = 'password'; //FIXME 
	    				break;

	    				default:
	    					$left = $left.', '.$mandatoryAttribute;
	    				break;
	    			}
    			}
    		}
    	}
    	
    	$data = array();
    	$properties = array_keys($this->properties);
    	foreach ($properties as $property)
    	{
    		//$this->$property might be:
    		//- false: this means that the attribute wasn't in the form
    		//- empty: this means that the form field wasn't filled
    		//- !empty: this means that the form field was filled
    		//if($property == "jpegPhoto") continue;	
    		if($property == "objectClass") continue;
			if($this->$property) {
    			$data[$property] = $this->$property;
			}	
    	}
    	
       	if(!empty($this->uid)) 
        {
        	$this->update($data); //TODO refactoring
        } else {
        	//TODO fixme
        	//if(!isset($data['entryCreatedBy'])) $data['entryCreatedBy'] = $this->session->userdata('last_name').' '.$this->session->userdata('first_name');
        	$this->create();
        }       	
    }
    
//TODO this must be private
// public function update()
// {	
//    	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/person/')); //TODO is this necessary?
//    	$rest_return = $this->rest->get('update', $input, 'serialize');
    
//     	return $rest_return;
//}

//TODO this must be private
//public function create()
//{
//     	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/person/')); //TODO is this necessary?
//     	$rest_return = $this->rest->get('create', $input, 'serialize');
    
//     	return $rest_return;
//}    
}

?>