<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * This class performs all the needed methods to interact with Contact Engine and
 * describes a contact as an unique object even if its made by two different 
 * objects with different meanings and methods: Person and Organization
 * 
 * Created by Damiano Venturin @ squadrainformatica.com
 */

class Mdl_Contact extends MY_Model {

	public $possibleObjects;
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
    
    public function get($input)
    {
    	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/contact/')); //TODO is this hardcoded "contact" right?
    	
    	//performing the query to contact engine
    	//TODO this should be $this->rest->get
    	$rest_return = $this->rest->post('read', $input, 'serialize');

    	return $rest_return;
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
    	}
    }
    
	protected function arrayToObject(array $contact, $objName)
	{
    	//checks
    	if(is_null($objName)) $objName = $this->objName;
    	if(!$this->checkObjName($objName)) return false;				
		if(!is_array($contact)) return false;
		
		$this->getProperties($objName);
		
		foreach ($contact as $property => $value) {
			if(in_array($property, array_keys($this->properties)))
			{
				$this->$property = $this->getReturnValue($property, $contact);
			}
		}
		
		return true;
	}
	
	private function getReturnValue($attribute,$contact)
	{
		if(isset($contact[$attribute]))
		{
			if(is_array($contact[$attribute]))
			{
				$value = implode(',',$contact[$attribute]);
			} else {
				$value = $contact[$attribute];
			}
			return $value;
		}
	
		return '';
	}

	public function update($input)
	{
		//$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/person/')); //TODO is this necessary?
		$rest_return = $this->rest->get('update', $input, 'serialize');
	
		return $rest_return;
	}
	
	public function create($input)
	{
		//$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/person/')); //TODO is this necessary?
		$rest_return = $this->rest->get('create', $input, 'serialize');
	
		return $rest_return;
	}	
}

?>