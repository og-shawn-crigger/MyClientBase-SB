<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//created by Damiano Venturin @ squadrainformatica.com

class Mdl_Location extends MY_Model {

	public $objClass;
	public $objName;
	public $properties;
	public $locId;
	public $show_fields;
	public $hidden_fields;	
	public $aliases;
		
    public function __construct() {

        parent::__construct();
        
        //some self-references
        $this->objClass = get_class($this); //Mdl_Location
        $this->objName = 'location';
        
        // Load curl       
        $this->load->spark('curl/1.2.0');
        
        // Load the configuration file
        $this->load->config('rest');
         
        // Load the rest client
        $this->load->spark('restclient/2.0.0');

        $this->load->model('contact/rest_return_object');
        $this->crr = new Rest_Return_Object();
        
        $this->getProperties();
    }
        
    public function get(array $input = null, $return_rest = false)
    {	
    	if((is_null($input) || count($input) == 0) && is_null($this->locId)) return false;
    	
		//sets the contactengine key which allows to set the correct baseDN
    	if($this->config->item('ce_key')) $input['ce_key'] = $this->config->item('ce_key');
    	
    	if(empty($input['filter'])) $input['filter'] = '(locId='.$this->locId.')';
    	     	 
    	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/'.$this->objName));
    	 
    	//performing the query to contact engine
    	$this->crr->importCeReturnObject($this->rest->post('read', $input, 'serialize'));
    	 
    	if($return_rest){
    		if($this->crr->has_no_errors) {
    			return $this->crr->returnAsArray();
    		} else {
    			return false;
    		}
    	}
    	 
    	if($this->crr->has_no_errors)
    	{
    		if($this->crr->results_got_number == '1')
    		{
    			if(!empty($this->crr->data['0']['locId'])) {
    				//$obj = new Mdl_Location();
    				return $this->arrayToObject($this->crr->data['0'],true);
    			}    	
    		}
    		
    		if($this->crr->results_got_number == '0') return false;
    	}
    	 
    	return false;
    	 
    }
    
    public function getProperties()
    {
    	if(!isset($this->properties) || count($this->properties) == 0)
    	{
	    	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/'.$this->objName.'/'));
	    	
	    	$this->crr->importCeReturnObject($this->rest->post('getProperties', null, 'serialize'));
	    	if($this->crr->has_no_errors)
	    	{
	    		$this->properties = $this->crr->data;
	    	    return true;
    		} else {
    			return false;
    		}
    	}	
    	
    	return true;    	 
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
    
	protected function arrayToObject(array $location)
	{	
		if(!is_array($location)) return false;
		
		if(!$this->getProperties()) return false;
		
		$this->cleanAttributes();
		
		$properties = array_keys($this->properties);
		
		foreach ($location as $property => $value) {
			if(in_array($property, $properties))
			{
				$this->$property = $this->getReturnValue($property, $location);
			}
		}
		
		//TODO not sure about this
		//check to see if I can get the geoCoordinates
// 		if(empty($this->locLatitude) || empty($this->locLongitude))
// 		{
// 			$input = array();
// 			//$input['filter'] = '(locId='.$params['locId']['0'].')';
// 			$location['entryUpdatedBy'] = 'MCB-SB';
// 			$this->update($location);
// 		}
		
		return true;
	}
		
	
	protected function objectToArray() {
	
		$properties = array_keys($this->properties);
	
		$output = array();
		foreach ($properties as $key => $property) {
				
			//do not mess with LDAP system fields
			if($this->properties[$property]['no-user-modification'] === '1') continue;
				
			if($this->properties[$property]['single-value'] === '1') {
				if(!is_array($this->$property)) {
					$output[$property] = $this->$property;
				} else {
					$output[$property] = implode(',', $this->$property); //TODO not sure about this. If it's single it should be never an array
				}
			} else {
				if(is_array($this->$property)) {
					if(count($this->$property) > 1) {
						$output[$property] = $this->$property; 
					} else {
						$output[$property] = $this->$property[0]; 
					}
				} else {
					if(!empty($this->$property))
					{
						$output[$property] = $this->$property;
					} else {
						$output[$property] = array();
					}
				}
			}
				
			//TODO add here the validation on lenght, field type ... or call the validation method before loading this method
		}
	
		return $output;
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

	protected function cleanAttributes()
	{
		$this->getProperties();
		$properties = array_keys($this->properties);
		foreach ($properties as $property)
		{
			$this->$property = null;
		}
	}
		
	public function validateForm()
	{
		return parent::validate($this);
	}
		
	public function validateObj($ignore_locId = false)
	{
		 
		if( !$ignore_locId && (!isset($this->locId) || empty($this->locId))) return false;
		 
		//rules
		if(empty($this->locDescription)) return false;
	
		if($this->getMandatoryAttributes())
		{
			foreach ($this->mandatoryAttributes as $mandatoryAttribute) {
				 
				if($ignore_locId && $mandatoryAttribute == 'locId') continue; //this is the case for the creation of a new location
	
				//sets default values for mandatory fields
				if(empty($this->$mandatoryAttribute)) {
					switch ($mandatoryAttribute) {
						case 'entryCreatedBy':
							$this->$mandatoryAttribute = $this->session->userdata('last_name').' '.$this->session->userdata('first_name');
						break;
	
						case 'entryCreationDate':
							$this->$mandatoryAttribute = date('Y-m-d');
						break;
								
						case 'entryUpdatedBy':
							$this->$mandatoryAttribute = $this->session->userdata('last_name').' '.$this->session->userdata('first_name');
						break;
	
						case 'entryUpdateDate':
							$this->$mandatoryAttribute = date('Y-m-d');
						break;
	
						case 'enabled':
							$this->$mandatoryAttribute = 'TRUE';  //FIXME
						break;
	
						default:
							if(!isset($left)) $left = array();
							$left[] = $mandatoryAttribute;
						break;
					}
				}
			}
		}
		 
		if(isset($left)) return $left;
		 
		return true;
	}
	
 	public function save($creation, $with_form = true, array $input = null)
	{
		if(!is_bool($creation)) return false;
	
		//TODO add here the notification messages for each case of failure
	
		//did the user fill all the mandatory fields present in the form ?
		if($with_form)
		{
			if(!$this->validateForm()) return false;
		}
			
		if($with_form) 
		{
			//let's bind the values filled in the form with the obj. MY_Model stores form's values in $this->form_values.
			if(!$this->arrayToObject($this->form_values, $creation)) return false;
		} else {
			//let's use the data provided by the obj Person or Organization
			if(is_null($input)) return false;
			if(!$this->arrayToObject($input, $creation)) return false;
		}
		
			
		//validates the object before sending data to Contact Engine
		$left = $this->validateObj($creation);
		if(is_array($left)) {
			//TODO add the content of left to the notification message
			return false;
		}
			
		//$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/'.$this->objName.'/'));
		 
		if($creation){
			return $this->create($this->objectToArray());
		} else {
			return $this->update($this->objectToArray());
		}
			
	}
	 	
	public function update(array $input)
	{
		//sets the contactengine key which allows to set the correct baseDN
    	if($this->config->item('ce_key')) $input['ce_key'] = $this->config->item('ce_key');
		
		$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/'.$this->objName));		

		$this->crr->importCeReturnObject($this->rest->post('update', $input, 'serialize'));
	
		if($this->crr->has_no_errors) {
			$this->mcbsb->system_messages->success = 'location_updated';
			return true; 
		}
		
		$this->mcbsb->system_messages->success = 'location_not_updated';
		
		return false;
	}
	
	public function create(array $input)
	{
		//sets the contactengine key which allows to set the correct baseDN
    	if($this->config->item('ce_key')) $input['ce_key'] = $this->config->item('ce_key');
		
		$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/'.$this->objName));
		
		$this->crr->importCeReturnObject($this->rest->post('create', $input, 'serialize'));
		
		if($this->crr->has_no_errors) {
			$this->locId = $this->crr->data['locId'];
			$this->mcbsb->system_messages->success = 'location_created';
			return true;
		}
		
		$this->mcbsb->system_messages->error = 'location_not_created';
		
		return false;		
	}	

	public function delete(array $input)
	{
		//sets the contactengine key which allows to set the correct baseDN
    	if($this->config->item('ce_key')) $input['ce_key'] = $this->config->item('ce_key');
		
		$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/'.$this->objName));
	
		$this->crr->importCeReturnObject($this->rest->post('delete', $input, 'serialize'));
	
		if($this->crr->has_no_errors) {
			return true;
		}
	
		return false;
	}	
	public function prepareShow()
	{
		$this->load->config('location');
		$this->show_fields = $this->config->item('location_show_fields');
		$this->hidden_fields = $this->config->item('location_hidden_fields');
		$this->aliases = $this->config->item('location_attributes_aliases');
	}	
}

?>