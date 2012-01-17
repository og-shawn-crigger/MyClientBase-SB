<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//created by Damiano Venturin @ squadrainformatica.com

class Mdl_Location extends MY_Model {

	public $properties;
	public $locId;
	public $show_fields;
	public $hidden_fields;	
	
    public function __construct() {

        parent::__construct();
        
        // Load curl
        $this->load->spark('curl/1.2.0');
        
        // Load the configuration file
        $this->load->config('rest');
         
        // Load the rest client
        $this->load->spark('restclient/2.0.0');
        
        $this->getProperties();
    }

    public function get($params)
    {	
    	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/location/'));
    	    	
    	if(!is_array($params)) return false;
    	
    	$input = array();
    	
    	if(empty($params['locId']))
    	{
    		return false;	
    	} else {
    		$input['filter'] = '(locId='.$params['locId'].')';
    	}    	
    	
    	//performing the query to contact engine
    	$rest_result = $this->rest->post('read', $input, 'serialize');

    	if($rest_result['status']['status_code'] == 200) 
    	{
    		$this->arrayToObject($rest_result['data']['0']);
    	}
    	
    	return $rest_result;
    }
    
    public function getProperties()
    {
    	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/location/'));
    	
    	$rest_result = $this->rest->post('getProperties', null, 'serialize');
    	if($rest_result['status']['status_code'] == 200) 
    	{
    		$this->properties = $rest_result['data'];
    	}
    }
    
    
	protected function arrayToObject(array $location)
	{	
		if(!is_array($location)) return false;
		
		//$this->getProperties();
		
		//clean up
		foreach ($this->properties as $property => $settings) {
			unset($this->$property);
		}
		
		foreach ($location as $property => $value) {
			if(in_array($property, array_keys($this->properties)))
			{
				$this->$property = $this->getReturnValue($property, $location);
			}
		}
		
		//TODO add a config parameter
		//check to see if I can get the geoCoordinates
		if(empty($this->locLatitude) || empty($this->locLongitude))
		{
			$input = array();
			//$input['filter'] = '(locId='.$params['locId']['0'].')';
			$location['entryUpdatedBy'] = 'MCB-SB';
			$this->update($location);
		}
		
		return true;
	}
	
	private function getReturnValue($attribute,$location)
	{
		if(isset($location[$attribute]))
		{
			if(is_array($location[$attribute]))
			{
				$value = implode(',',$location[$attribute]);
			} else {
				$value = $location[$attribute];
			}
			return $value;
		}
	
		return '';
	}

	public function update($input)
	{
		$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/location/'));
				
		$rest_return = $this->rest->get('update', $input, 'serialize');
	
		return $rest_return;
	}
	
	public function create($input)
	{
		$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/location/'));
		
		$rest_return = $this->rest->get('create', $input, 'serialize');
	
		return $rest_return;
	}	
	
	public function prepareShow()
	{
		$this->load->config('location');
		$this->show_fields = $this->config->item('location_show_fields');
		$this->hidden_fields = $this->config->item('location_hidden_fields');
	}	
}

?>