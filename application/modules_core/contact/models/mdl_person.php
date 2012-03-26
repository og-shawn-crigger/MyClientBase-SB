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
        
        $this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/'.$this->objName.'/'));        
    	
    }

    //TODO shouldn't be at least protected?
    public function arrayToObject(array $data, $ignore_uid = false)
    {
    	if(!$return = parent::arrayToObject($data, $this->objName)) return false;
    	
    	//this is for MCB compatibility
    	if(isset($this->uid)) $this->client_id = $this->uid;
    	if(isset($this->cn)) $this->client_name = $this->cn;
    	
    	if($ignore_uid)
    	{
    		return true; //this is used only for contact creation, case in which the uid is unknown
    	} else {
    		if(!empty($this->uid))	return true;
    	}
    	    
    	
    	return false;
    }

    public function get(array $input = null, $return_rest = true)
    {
    	//$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/'.$this->objName.'/'));
    	
    	if((is_null($input) || (is_array($input) && count($input)==0)) && is_null($this->uid)) return false;
    	
    	if(empty($input['filter']) && isset($this->uid)) $input['filter'] = '(uid='.$this->uid.')';
    	
    	if(empty($input)) return false;
    	
    	return parent::get($input, $return_rest);
    }   
     
    public function prepareShow()
    {
    	$this->load->config('person');
    	$this->show_fields = $this->config->item('person_show_fields');
    	$this->aliases = $this->config->item('person_attributes_aliases');
    	$this->hidden_fields = $this->config->item('person_hidden_fields');
    }
    
    protected function hasProperAddress()
    {
    	//some very basic validation of an address. If the address is validated we can try to save the "Residence" location.
    	if(!isset($this->homePostalAddress)) return false;
    	if(!isset($this->mozillaHomePostalCode)) return false;
    	if(!isset($this->mozillaHomeLocalityName)) return false;
    	if(!isset($this->mozillaHomeState)) return false;
    	if(!isset($this->mozillaHomeCountryName)) return false;
    	
    	//there is no way to know how long it should be
    	if(mb_strlen($this->homePostalAddress) < 3) return false;
    	
    	//Postal Codes are between 3 and 8 digits. They can be numeric and alphanumeric
    	if(mb_strlen($this->mozillaHomePostalCode) < 3 || mb_strlen($this->mozillaHomePostalCode) > 8 ) return false;
    	
    	if(mb_strlen($this->mozillaHomeLocalityName) < 3) return false;
    	
    	//here I could use a list but then there is the mess with the languages    	
    	if(mb_strlen($this->mozillaHomeState) < 3) return false;
    	
    	//here I could use a list but then there is the mess with the languages
    	if(mb_strlen($this->mozillaHomeCountryName) < 3) return false;
    	
    	return true;
    }
    
    
    public function validateObj($ignore_uid = false)
    {
    	
    	if( !$ignore_uid && (!isset($this->uid) || empty($this->uid))) return false;
    	
    	//rules
		if(empty($this->sn)) return false;
    	if(empty($this->givenName)) return false;
    	 
    	if($this->getMandatoryAttributes())
    	{
    		foreach ($this->mandatoryAttributes as $mandatoryAttribute) {
    	
    			if($ignore_uid && $mandatoryAttribute == 'uid') continue; //this is the case for the creation of a new person
    				
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
    						    					
    					case 'category':
    						$this->$mandatoryAttribute = 'unknown';
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

    public function save($with_form = true)
    {
    	$creation = empty($this->uid) ? true : false; //if uid is not set than it's a creation otherwise an update
    	$return = parent::save($creation, $with_form);
    	
    	if($return)
    	{
    		if($creation) $this->uid = $uid = $this->crr->data['uid'];
    		$update_return = $this->updateDefaultLocation($creation);
    		
    		return $return;
    	}    	
    	
    	return false;
    }    
}

?>