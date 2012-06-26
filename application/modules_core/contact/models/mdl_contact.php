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
	public $binary_properties;
	public $client_id;
	public $show_fields;
	public $hidden_fields;
	public $aliases;
	public $total_invoice = '0.0';
	public $total_payment = '0.0';
	public $total_balance = '0.0';
	public $crr; //ContactEngine Rest Return	
	
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

        $this->load->model('contact/rest_return_object');
        $this->crr = new Rest_Return_Object();
    }
    
    public function __destruct() {
    	
    }

    public function validateForm()
    {
    	return parent::validate($this);
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
    	$this->crr->importCeReturnObject($this->rest->post('read', $input, 'serialize'));
    	
    	if($return_rest){
    		if($this->crr->has_no_errors) {
    			return $this->crr->returnAsArray();
    		} else { 
    			return false;
    		}
    	} 
    		 
    	if($this->crr->has_no_errors) {
    		if($this->crr->results_got_number == '1') {
    			/*
    			if(!empty($this->crr->data['0']['uid'])) {
    				$this->person->arrayToObject($this->crr->data['0']);
    				//$this->person->prepareShow(); //TODO maybe this can come in handy
    			}

    			if(!empty($this->crr->data['0']['oid'])) {
    				$this->organization->arrayToObject($this->crr->data['0']);
    				//$this->organization->prepareShow();  //TODO maybe this can come in handy
    			}
    			*/
    			
    			return $this->arrayToObject($this->crr->data['0']);
    			    			
    			//return true;
    		} else {
    			
    			return false;
    		}
    		    		
    		//if($this->crr->results_got_number == '0') return false;
    	}
    	
    	return false;
	}
    
    public function getProperties($objName = null)
    {
    	//checks
    	if(is_null($objName)) $objName = $this->objName;
    	if(!$this->checkObjName($objName)) return false;
    	
    	if(!isset($this->properties) || count($this->properties)==0)
    	{    		
	    	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/'.$objName.'/'));
	    	
	    	//performing the REST request
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
    
    public function getBinaryProperties(){
    	if(!isset($this->properties) || count($this->properties)==0)
    	{
    		$this->getProperties();	 
    	}
    	
    	$this->binary_properties = array();
    	
    	foreach ($this->properties as $property_name => $property_features) {
    		if($property_features['binary'] == 1) {
    			$this->binary_properties[] = $property_name; 
    		}
    	}
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
        
	protected function arrayToObject(array $data, $objName = null)
	{
    	//checks
    	if(is_null($objName)) $objName = $this->objName;
    	if(!$this->checkObjName($objName)) return false;				
		if(!is_array($data)) return false;
		
		if(!$this->getProperties($objName)) return false;
		
		$this->cleanAttributes();
		
		$properties = array_keys($this->properties);
		
		foreach ($data as $property => $value) {
			if(in_array($property, $properties))
			{
				$this->$property = $this->getReturnValue($property, $data);
			}
		}
		
		return true;
	}
	
	protected function objectToArray() {

		$properties = array_keys($this->properties);
		
		$output = array();
		foreach ($properties as $key => $property) {
			
			//special fields
			if($property == 'uid' || $property == 'oid')
			{
				$output[$property] = $this->$property;
				continue;
			}
						
			//do not mess with LDAP system fields
			if($this->properties[$property]['no-user-modification'] == 1) continue;
	
			if($property == 'locRDN') {
				$a= '';
			}
			
			if($this->properties[$property]['single-value'] == 1) {
				if(!is_array($this->$property)) {
					$output[$property] = $this->$property; 
				} else {
					//just in case the property has been mistakenly set as an array I force it to be a string
					$output[$property] = implode(',', $this->$property);
				}
			} else {
				if(is_array($this->$property)) {
					$output[$property] = $this->$property;
				} else {
					if(empty($this->$property))
					{
						$output[$property] = array();
					} else {
						$output[$property] = explode(',', $this->$property);
					}
				}
			}
			
			//TODO add here the validation on lenght, field type ... or call the validation method before loading this method
		}
		
		return $output;
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
	
	public function setFormRules() {
		 
		//gets aliases and stuff
		$this->prepareShow();
		 
		if($this->getMandatoryAttributes())
		{
			foreach ($this->mandatoryAttributes as $mandatoryAttribute) {
				//adds to the form rules only the mandatory fields that are shown in the form.
				//all the other mandatory fields (like cn, objectClass ...  will be set in the validate method
	
				//FIXME figure out what to do with the enabled field: it's currently in the "settings" form, so it's not included in the "info form"
				if(in_array($mandatoryAttribute, $this->show_fields) && $mandatoryAttribute != 'enabled')
				{
					//gets the alias for the mandatory field
					//TODO I still have to fix the localization
					if(isset($this->aliases[$mandatoryAttribute]))
					{
						$field = $this->aliases[$mandatoryAttribute];
					} else {
						$field = $mandatoryAttribute;
					}
	
					$this->form_validation->set_rules($mandatoryAttribute, $field, 'required');
				}
			}
		}
	}	

	public function save($creation, $with_form = true)
	{
		if(!is_bool($creation)) return false;
		
		//TODO add here the notification messages for each case of failure
		 
		//($this->enabled) ? $this->enabled = 'TRUE' : $this->enabled = 'FALSE';
		$this->enabled = 'TRUE'; //FIXME		
		
		//did the user fill all the mandatory fields present in the form ?
		if($with_form) {
			if(!$this->validateForm()) return false;
		}
		 
		//let's bind the values filled in the form with the obj. MY_Model stores form's values in $this->form_values.
 		if($with_form) {
 			
 			//in case of update of a person I have to protect the previously set values for the attributes o,locRDN,oRDN,oAdminRDN
 			if($this->objName == 'person') {
 				if(!empty($this->uid)) {
 					$original_values = array();
 					if(!empty($this->o)) $original_values['o'] = $this->o;
 					if(!empty($this->oRDN)) $original_values['oRDN'] = $this->oRDN;
 					if(!empty($this->oAdminRDN)) $original_values['oAdminRDN'] = $this->oAdminRDN;
 					if(!empty($this->locRDN)) $original_values['locRDN'] = $this->locRDN;
 				}
 			} 			

 			$data = $this->form_values;
 			
 			//binary files are not sent through POST, so I have to add them to the form values
 			$this->getBinaryProperties();
 			if(count($this->binary_properties)>0){
 				foreach ($this->binary_properties as $binary_attribute){
 					if(!empty($this->$binary_attribute) && !isset($data[$binary_attribute])){
 						$data[$binary_attribute] = $this->$binary_attribute;
 					}
 				}	
 			}
 			
 			if(!$this->arrayToObject($data, $creation)) return false;
 			
 			//let's put the protected values back
 			if($this->objName == 'person') {
 				if(!empty($this->uid)) {
 					if(!empty($original_values['o'])) $this->o = $original_values['o'];
 					if(!empty($original_values['oRDN'])) $this->oRDN = $original_values['oRDN'];
 					if(!empty($original_values['oAdminRDN'])) $this->oAdminRDN = $original_values['oAdminRDN'];
 					if(!empty($original_values['locRDN'])) $this->locRDN = $original_values['locRDN'];
 				}
 			}
 		}
		 
		//validates the object before sending data to Contact Engine
		$left = $this->validateObj($creation);
		if(!$left || is_array($left)) {
		//TODO add the content of left to the notification message
			return false;
		}
		 	 
		if($creation){
			return $this->create($this->objectToArray());
		} else {
			return $this->update($this->objectToArray());
		}
					
	}	
	
	protected function update($input)
	{
		$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/'.$this->objName));		

		$this->crr->importCeReturnObject($this->rest->post('update', $input, 'serialize'));
	
		if($this->crr->has_no_errors) {
			$this->mcbsb->system_messages->success = 'contact_updated';
			return true; 
		}
		$this->mcbsb->system_messages->error = 'contact_not_updated';
		return false;
	}
	
	protected function create($input)
	{	
		$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/'.$this->objName));
		
		$this->crr->importCeReturnObject($this->rest->post('create', $input, 'serialize'));
		
		if($this->crr->has_no_errors) {
			$this->mcbsb->system_messages->success = 'contact_created';
			return true;
		}

		$this->mcbsb->system_messages->error = 'contact_not_created';
		return false;
	}	
	
	protected function updateDefaultLocation($creation)
	{
		if(!is_bool($creation)) return false;
		
		//refresh contact
		$this->get(null, false);
		
		$input = array();
		
		if($this->objName == 'organization') 
		{ 
			$input['locDescription'] = 'Registered Address';
			$input['locStreet'] = $this->street;
			$input['locZip'] = $this->postalCode;
			$input['locCity'] = $this->l;
			$input['locState'] = $this->st;
			$input['locCountry'] = $this->c;
			$input['locPhone'] = $this->telephoneNumber;
				
		}
		
		if($this->objName == 'person'){
			$input['locDescription'] = 'Home';
			$input['locStreet'] = $this->homePostalAddress;
			$input['locZip'] = $this->mozillaHomePostalCode;
			$input['locCity'] = $this->mozillaHomeLocalityName;
			$input['locState'] = $this->mozillaHomeState;
			$input['locCountry'] = $this->mozillaHomeCountryName;
			$input['locPhone'] = $this->homePhone;
		}
		
		//TODO maybe this can be replaced by a Contact Engine method which retrieves the right Location automatically
		//if it's an update gets the locId of the right location
		if(!$creation) {
			
			if(!empty($this->locRDN)) {			
				
				$locs = explode(',', $this->locRDN);
			
				if(is_array($locs))
				{
					if(count($locs) == 1)
					{
						if($this->objName == 'organization') $filter = '(&(locDescription=Registered Address) (locId='.$locs[0].')';
						if($this->objName == 'person') $filter = '(&(locDescription=Home) (locId='.$locs[0].'))';
					} else {
						foreach ($locs as $key => $locId) {
							if($key == 0) $filter = '(locId='.$locId.')';
							if($key > 0)  $filter = $filter.' (locId='.$locId.')';
						}
						if($this->objName == 'organization') $filter = '(&(locDescription=Registered Address) (|'.$filter.'))';
						if($this->objName == 'person') $filter = '(&(locDescription=Home) (|'.$filter.'))';
					}
					
					$find_location = array('filter' => $filter);
					
					if($rest_return = $this->location->get($find_location, true)) {
						$default_location = $rest_return['data'];
						if(count($default_location) == 0) $creation = true;
						if(count($default_location) == 1)
						{
							$creation = false;
							$this->location->locId = $input['locId'] = $default_location[0]['locId'];
						}
						if(count($default_location) > 1) {
							unset($creation);
							//TODO send a notification
						}
					} else {
						return false;
					}		
				}
			} else {
				$creation = true;
			}
		}
		
		if(isset($creation))
		{
			if($this->location->save($creation, false, $input))
			{
				if($creation)
				{
					//update the contact
					if(is_array($this->locRDN) and count($this->locRDN) > 0)
					{
						$this->locRDN[] = $this->location->locId;
					} else {
						if(empty($this->locRDN))
						{
							$this->locRDN = array($this->location->locId);
						} else {
							$locs = (array) $this->locRDN;
							$locs[] = $this->location->locId;
							$this->locRDN = $locs;
						}
					}
					return $this->update($this->objectToArray());
				}
		
				return true;
			}
			 
			//TODO send a notification
		}
		
		//TODO send a notification
		return true;
	}		
}

?>