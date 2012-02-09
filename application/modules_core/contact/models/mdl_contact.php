<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * This class performs all the needed methods to interact with Contact Engine and
 * describes a contact as an unique object even if its made by two different 
 * objects with different meanings and methods: Person and Organization.
 * Note: in Contact Engine API there is no "Contact Object". This is just a "strategy" to make MCB to match with CE
 * 
 * Created by Damiano Venturin @ squadrainformatica.com
 */

class Mdl_Contact extends MY_Model {

	public $possibleObjects;
	public $mandatoryAttributes;
	public $objClass;
	public $objName;
	public $properties;
	public $client_id;
	public $show_fields;
	public $hidden_fields;
	public $aliases;
	public $total_invoice = '0.0';
	public $total_payment = '0.0';
	public $total_balance = '0.0';	
	
    public function __construct() {
        parent::__construct();
        
        //some self-references
        $this->objClass = get_class($this); //one of these (Mdl_Person, Mdl_Organization, Mdl_Contact)
        $this->objName = 'contact'; //one of these ('person','organization', 'contact')
        $this->mandatoryAttributes = array();
        
        $this->possibleObjects = array('person','organization', 'contact'); //lists all the possible children for this obj
        
        // Load curl
        $this->load->spark('curl/1.2.0');
        
        // Load the configuration file
        $this->load->config('rest');
         
        // Load the rest client
        $this->load->spark('restclient/2.0.0');
    }

    private function checkObjName($objName)
    {
    	return in_array($objName, $this->possibleObjects) ? true : false;    	
    }
    
    public function get(array $input = null, $return_rest = true)
    {
    	//I need at least somethig to send as input to retrieve the contact
    	if((is_null($input) || (count($input)==0)) && is_null($this->client_id)) return false;
    	
    	if(empty($input['filter'])) $input['filter'] = '(|(uid='.$this->client_id.')(oid='.$this->client_id.'))';
    	
    	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/'.$this->objName));
    	
    	//performing the query to contact engine
    	//TODO this should be $this->rest->get
    	$rest_return = $this->rest->post('read', $input, 'serialize');

    	if($return_rest) return $rest_return;
    	
		//populate the current object	
    	if($rest_return['status']['status_code'] == 200) {
    		if($rest_return['status']['results_number']==1)
    		{
    			//is this a Person or an Organization?
    			if(!empty($rest_return['data']['0']['uid'])){
    				$obj = new Mdl_Person();
//    				$obj->uid = $rest_return['data']['0']['uid'];
    				return $obj->arrayToObject($rest_return['data']['0'],true);
    			}
    			
    			if(!empty($rest_return['data']['0']['uid'])){
    				$obj = new Mdl_Organization();
//    				$obj->oid = $rest_return['data']['0']['oid'];
    				return $obj->arrayToObject($rest_return['data']['0'],true);
       			}    			
    		}
    	}
    	return false;
    }
    
    public function getProperties($objName = null)
    {
    	//checks
    	if(is_null($objName)) $objName = $this->objName;
    	if(!$this->checkObjName($objName)) return false;
    	
    	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/'.$objName.'/'));
    	$rest_result = $this->rest->post('getProperties', null, 'serialize');
    	if($rest_result['status']['status_code'] == 200) 
    	{
    		$this->properties = $rest_result['data'];
    		return true;
    	} else {
    		return false;
    	}
    }
    
	protected function arrayToObject(array $data, $objName = null)
	{
    	//checks
    	if(is_null($objName)) $objName = $this->objName;
    	if(!$this->checkObjName($objName)) return false;				
		if(!is_array($data)) return false;
		
		if(!$this->getProperties($objName)) return false;
		
		$properties = array_keys($this->properties);
		
		foreach ($data as $property => $value) {
			if(in_array($property, $properties))
			{
				$this->$property = $this->getReturnValue($property, $data);
			}
		}
		
		return true;
	}
	
	private function getReturnValue($attribute,$data)
	{
		if(isset($data[$attribute]))
		{
			if(is_array($data[$attribute]))
			{
				$value = implode(',',$data[$attribute]);
			} else {
				$value = $data[$attribute];
			}
			return $value;
		}
	
		return '';
	}

	public function update($input)
	{
		//$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/person/')); //TODO is this necessary?
		$rest_return = $this->rest->post('update', $input, 'serialize');
	
		return $rest_return;
	}
	
	public function create($input)
	{
		//$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/person/')); //TODO is this necessary?
		$rest_return = $this->rest->post('create', $input, 'serialize');
	
		return $rest_return;
	}	
}

?>