<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//modified by Damiano Venturin @ squadrainformatica.com

class Mdl_Contact extends MY_Model {

	public $properties;
	public $client_id;
	public $show_fields;
	public $hidden_fields;	
	public $total_invoice = '0.0';
	public $total_payment = '0.0';
	public $total_balance = '0.0';	
	
    public function __construct() {

        parent::__construct();
        
        // Load curl
        $this->load->spark('curl/1.2.0');
        
        // Load the configuration file
        $this->load->config('rest');
         
        // Load the rest client
        $this->load->spark('restclient/2.0.0');
        
    }

    public function get($input)
    {
    	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/contact/'));
    	
    	//performing the query to contact engine
    	//TODO this should be $this->rest->get
    	$rest_return = $this->rest->post('read', $input, 'serialize');

    	return $rest_return;
    }
    
    public function getProperties($obj)
    {
    	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/'.$obj.'/'));
    	$rest_result = $this->rest->post('getProperties', null, 'serialize');
    	if($rest_result['status']['status_code'] == 200) 
    	{
    		$this->properties = $rest_result['data'];
    	}
    }
    
    
	protected function arrayToObject(array $contact, $obj)
	{
		if(!is_array($contact)) return false;
		
		$this->getProperties($obj);
		
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

}

?>