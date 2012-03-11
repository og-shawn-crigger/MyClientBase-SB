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
        
        $this->objName = 'organization';
        
        $this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/organization/'));        
                
    }
        
    public function arrayToObject(array $organization, $ignore_oid = false)
    {
    	if(!$return = parent::arrayToObject($organization, $this->objName)) return false;

    	//this is for MCB compatibility
    	if(isset($this->oid)) $this->client_id = $this->oid;
    	if(isset($this->o)) $this->client_name = $this->o;
    	 
    	if($ignore_oid)
    	{
    		return true; //this is used only for contact creation, case in which the uid is unknown
    	} else {
    		if(!empty($this->oid))	return true;
    	}
    	 
    	return false;    	
    }
    
    public function prepareShow()
    {
    	$this->load->config('organization');
    	$this->show_fields = $this->config->item('organization_show_fields');
    	$this->aliases = $this->config->item('organization_attributes_aliases');
    	$this->hidden_fields = $this->config->item('organization_hidden_fields');    	
    }    

	public function get(array $input = null, $return_rest = true)
    {
    	if((is_null($input) || (is_array($input) && count($input)==0)) && is_null($this->oid)) return false;
    	 
    	if(empty($input['filter']) && isset($this->oid)) $input['filter'] = '(oid='.$this->oid.')';
    	 
    	if(empty($input)) return false;
    	     
    	return parent::get($input, $return_rest);
    }    
    
    protected function hasProperAddress()
    {
    	//some very basic validation of an address. If the address is validated we can try to save the "Residence" location.
    	if(!isset($this->street)) return false;
    	if(!isset($this->postalCode)) return false;
    	if(!isset($this->l)) return false;
    	if(!isset($this->st)) return false;
    	if(!isset($this->c)) return false;
    	
    	//there is no way to know how long it should be
    	if(mb_strlen($this->street) < 3) return false;
    	
    	//Postal Codes are between 3 and 8 digits. They can be numeric and alphanumeric
    	if(mb_strlen($this->postalCode) < 3 || mb_strlen($this->postalCode) > 8 ) return false;
    	
    	if(mb_strlen($this->l) < 3) return false;
    	
    	//here I could use a list but then there is the mess with the languages    	
    	if(mb_strlen($this->st) < 3) return false;
    	
    	//here I could use a list but then there is the mess with the languages
    	if(mb_strlen($this->c) < 3) return false;
    	
    	return true;
    }
    
    
    public function validateObj($ignore_oid = false)
    {
    	
    	if( !$ignore_oid && (!isset($this->oid) || empty($this->oid))) return false;
    	
    	//rules
		if(empty($this->o)) return false;
    	 
    	if($this->getMandatoryAttributes())
    	{
    		foreach ($this->mandatoryAttributes as $mandatoryAttribute) {
    	
    			if($ignore_oid && $mandatoryAttribute == 'oid') continue; //this is the case for the creation of a new organization
    				
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
    
    public function save()
    {
    	$creation = empty($this->oid) ? true : false; //if uid is not set than it's a creation otherwise an update
        $return = parent::save($creation);
    	if($return)
    	{
    		if($creation) $this->oid = $this->crr->data['oid'];
    		return $this->updateDefaultLocation($creation);
    	}
    	
    	
    	return false;
    }    
}

?>