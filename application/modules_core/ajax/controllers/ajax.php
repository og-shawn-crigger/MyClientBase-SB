<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//modified by Damiano Venturin @ squadrainformatica.com

class Ajax extends Admin_Controller {

	private $callback;
	
    function __construct() {
		
        parent::__construct();

        global $callback;
        $this->callback = urldecode(trim($this->input->get('callback')));
        
        $this->securityChecks();
        //$this->_post_handler();

        //add html tidy parser for codeigniter
        
        //TODO Ideally you would autoload the parser
        $this->load->driver('plenty_parser');
        
        $this->load->model('contact/mdl_contacts');
    }
    
    private function securityChecks(){
		//TODO there are plenty of security checks to perform here
    	//if($_SERVER['REMOTE_ADDR'] != '127.0.0.1') die('Failure: '.$_SERVER['REMOTE_ADDR']);
    }
    
    
    /**
     * Outputs to js a given array as a json array
     *
     * @access		private
     * @param		$to_js		array	The values to output
     * @param		$callback	string	Jquery callback string
     * @var
     * @return		nothing
     * @example
     * @see
     *
     * @author 		Damiano Venturin
     * @copyright 	2V S.r.l.
     * @link		http://www.mcbsb.com
     * @since		Feb 22, 2012
     *
     */
    private function output(array $to_js)
    {
    	$output = json_encode($to_js);
    	if(!is_null($this->callback) && $this->callback){
    		echo $this->callback .'('.$output.');';
    	} else {
    		echo $output;
    	}
    	exit();
    }
    
    private function returnError($message) {
    	$to_js = array();
    	$to_js['error'] = urlencode(trim($message));
    	$this->output($to_js);
    }
    
    public function getForm(){
    	$params = $this->input->post('params');
    	if(!is_array($params) || count($params) == 0) $this->returnError('Some information are missing'); //TODO translate with CI standard way
    	 
    	if(isset($params['form_type'])) $form_type = urldecode(trim($params['form_type']));
    	if(!isset($form_type) || !$form_type) $this->returnError('Form type is missing.'); //TODO translate with CI standard way
    	
    	switch ($form_type){
    		case 'form': //retrieves and returns a form html
    			$this->getClassicForm($params);
    		break;
    		
    		case 'search': //performs a search
    			$this->getSearchResults($params);
    		break;
    		
    		default :
    			$this->returnError('Unknown form type.');
    		break;
    	}
    }

    protected function getSearchResults(array $params){
    	$procedure = urlencode(trim($params['procedure']));
    	
    	if(isset($params['object_name'])) $searched_object = urldecode(trim($params['object_name']));
    	if(isset($params['searched_value'])) $searched_value = urldecode(trim($params['searched_value']));
    	
    	if(isset($params['related_object_name'])) $related_object_name = urldecode(trim($params['related_object_name']));
    	if(isset($params['related_object_id'])) $related_object_id = urldecode(trim($params['related_object_id']));
    	
    	if(isset($params['url'])) $url = urldecode(trim($params['url']));
    	if(!isset($url)) $this->returnError('No url specified');
    	
    	if(empty($searched_object) || empty($searched_value)) $this->returnError("Nothing to search.");

    	$possible_object_names = array('person','organization');
    	 
    	if(!in_array($searched_object, $possible_object_names)) $this->returnError('The specified object '.$searched_object.' is not valid.');    	
    	
    	$input = array();
    	$input['method'] = 'POST';
    	if($searched_object == 'person') {
    		$input['sort_by'] = array('sn');
    		//TODO make a search also switching name and surname?
    		$input['filter'] = '(|(cn=*'.$searched_value.'*)(mail=*'.$searched_value.'*)(mobile=*'.$searched_value.'*)(homePhone=*'.$searched_value.'*)(o=*'.$searched_value.'*))';
    	}
    	if($searched_object == 'organization') {
    		$input['sort_by'] = array('o');
    		$input['filter'] = '(|(o=*'.$searched_value.'*)(omail=*'.$searched_value.'*)(vatNumber=*'.$searched_value.'*)(oMobile=*'.$searched_value.'*)(telephoneNumber=*'.$searched_value.'*))';
    	}
    	$input['flow_order'] = 'asc';
    	$input['wanted_page'] = '0';
    	$input['items_page'] = '15';
    	
    	$class = 'Mdl_'.$searched_object;
    	$contact = new $class();
    	$rest_return = $contact->get($input);    	

    	$people = array();
    	$orgs = array();
    	if (is_array($rest_return['data'])) {
    		foreach ($rest_return['data'] as $item => $contact_item) {

    			if(in_array('dueviPerson', $contact_item['objectClass'])) {
    				if($contact->arrayToObject($contact_item)) $people[] = clone $contact;
    			}
    			
    			if(in_array('dueviOrganization', $contact_item['objectClass'])) {
    				if($contact->arrayToObject($contact_item)) $orgs[] = clone $contact;
    			}
    		}
    	}    	
    	
    	$data = array('people' => $people, 'orgs' => $orgs);
    	$data['searched_value'] = $searched_value;
    	$data['div_id'] = 'jquery-div-'.$searched_object;
    	$data['form_name'] = 'jquery-form-'.$searched_object;
    	$data['results_number'] = $rest_return['status']['results_number'];
    	$data['results_got_number'] = $rest_return['status']['results_got_number'];
    	
    	//gets the html
    	switch ($params['procedure']) {
    		case 'personToOrganizationMembership':
    			$template = 'jquery_search.tpl';
    		break;

    		case 'searchPersonToAdd':
    			if(isset($params['first_name'])) $data['first_name'] = $params['first_name'];
    			if(isset($params['last_name'])) $data['last_name'] = $params['last_name'];
    			if(isset($params['url'])) $data['url'] = $params['url'];
    			$template = 'jquery_search_person_to_add.tpl';
    		break;

    		case 'searchOrganizationToAdd':
    			if(isset($params['url'])) $data['url'] = $params['url'];
    			$template = 'jquery_search_organization_to_add.tpl';
    		break;
    			    		
    		default:
    			$template = 'jquery_search.tpl';
    		break;
    	}
    	
    	$html_form = $this->plenty_parser->parse($template, $data, true, 'smarty', 'ajax');
    	
    	//returns the html to js
    	$to_js = array();
    	if(!empty($html_form)){
    		$to_js['html'] = urlencode($html_form);
    		if(isset($data['div_id'])) $to_js['div_id'] = urlencode(trim($data['div_id']));
    		if(isset($data['form_name'])) $to_js['form_name'] = urlencode(trim($data['form_name']));
    		if(isset($params['procedure'])) $to_js['procedure'] = urlencode(trim($params['procedure']));
    		 
    		//these information are used by js to submit the form back to php
    		if(isset($params['url'])) $to_js['url'] = $url;
    		if(isset($params['object_name'])) $to_js['object_name'] = $params['object_name'];
    		if(isset($params['related_object_name'])) $to_js['related_object_name'] = $params['related_object_name'];
    		if(isset($params['related_object_id'])) $to_js['related_object_id'] = $params['related_object_id'];
    		 
    		$this->output($to_js);
    	} else {
    		$this->returnError($searched_object.'-form can not be loaded.');
    	}    	
    }
    
    protected function getClassicForm(array $params){
    	
    	if(isset($params['object_name'])) $object_name = urlencode(trim($params['object_name']));
    	if(isset($params['object_id'])) $object_id = urlencode(trim($params['object_id']));
    	if(isset($params['related_object_name'])) $related_object_name = urlencode(trim($params['related_object_name']));
    	if(isset($params['related_object_id'])) $related_object_id = urlencode(trim($params['related_object_id']));
    	if(isset($params['hash'])) $hash = urlencode(trim($params['hash']));
    	
//     	$this->load->library('session');
//     	$a = $this->session->all_userdata();
    	
    	$data =array();
    	
    	$possible_object_names = array('person','organization','location');
    	
    	if(!in_array($object_name, $possible_object_names)) $this->returnError('The specified '.$object_name.'-form can not be loaded.');

    	$this->$object_name->prepareShow();
    	if(isset($object_id)) {
    		switch ($object_name) {
    			case 'location':
    				$this->$object_name->locId = $object_id;
    				
    				$this->$object_name->get();
    				
    				$locDescription = strtolower($this->$object_name->locDescription);
    				if($locDescription == 'home' || $locDescription == 'registered address') {
    					$this->returnError('This location can not be modified from here.');
    				}    				
    			break;
    			
    			case 'person':
    				$this->$object_name->uid = $object_id;
    				$this->$object_name->get();
    			break;

    			case 'organization':
    				$this->$object_name->locId = $object_id;
    				$this->$object_name->get();
    			break;
    				 
       		}
       		
    	}
    	$data['object'] = clone $this->$object_name;
    	$data['object_name'] = $object_name;
    	$data['div_id'] = 'jquery-div-'.$object_name;
    	$data['form_name'] = 'jquery-form-'.$object_name;

    	//gets the html
    	$html_form = $this->plenty_parser->parse('jquery_form.tpl', $data, true, 'smarty', 'ajax');
    	
    	//returns the html to js
    	$to_js = array();
    	if(!empty($html_form)){
    		$to_js['html'] = urlencode($html_form);
    		$to_js['div_id'] = urlencode(trim($data['div_id']));
    		$to_js['form_name'] = urlencode(trim($data['form_name']));
    		 
    		//these information are used by js to submit the form back to php
    		$to_js['url'] = urlencode('/ajax/update'.ucwords(urlencode(trim($object_name))));
    		$to_js['related_object_name'] = $params['related_object_name'];
    		$to_js['related_object_id'] = $params['related_object_id'];
    		 
    		$this->output($to_js);
    	} else {
    		$this->returnError($object_name.'-form can not be loaded.');
    	}    	
    }
    
    private function getOrganization(Mdl_Organization $organization, $object_id) {
    	$organization->oid = $object_id;
    	$result = $organization->get();
    	if($result['status']['status_code']=='200' && $result['status']['results_number']=='1') {
    		$organization->arrayToObject($result['data']['0']);
    		return true;
    	}
    	$this->returnError('The selected organization can not be found');
    }

    private function getPerson(Mdl_Person $person, $object_id) {
    	$person->uid = $object_id;
    	$result = $person->get();
    	if($result['status']['status_code']=='200' && $result['status']['results_number']=='1') {
    		$person->arrayToObject($result['data']['0']);
    		return true;
    	}
    	$this->returnError('The selected person can not be found');
    }
    
    
    public function associate() {
    	$post = $this->input->post();
    	if(isset($post['params']) && is_array($post['params'])) {
    		foreach ($post['params'] as $key => $value) {
    			$post[$key] = $value;
    		}
    		unset($post['params']);
    	}
    	foreach ($post as $key => $value){
    		if(!is_array($value)) {
    			$post[$key] = urldecode(trim($value));
    		} 
    	}
 
    	$howmany = extract($post);

    	$organization = new Mdl_Organization();
    	$person = new Mdl_Person();
    	 
    	switch ($procedure) {
    		case 'personToOrganizationMembership':
    			if(empty($object_name) || empty($selected_radio) || empty($related_object_name) || empty($related_object_id)) $this->returnError('Some information are missing');
    			 
    			$selected_object_name = $object_name;
    			$selected_object_id = $selected_radio;
    			
    			if($selected_object_name=='organization' && $related_object_name=='person') {

    				$this->getOrganization($organization, $selected_object_id);
    				$organization_name = $organization->o;
    				
    				//update person
    				$this->getPerson($person, $related_object_id);
    					
    				if(empty($person->oRDN)){
    					$person->oRDN = $selected_object_id;
    				} else {
    					if(!is_array($person->oRDN)) {
    						$ordn = explode(',', $person->oRDN);
    					} else {
    						$ordn = $person->oRDN;
    					}
    					if(!in_array($selected_object_id, $ordn)) {
    						$ordn[] = $selected_object_id;
    						$person->oRDN = $ordn;
    					} else {
    						$this->returnError($person->cn.' is already associated to '.$organization_name);
    					}
    				}
    				 

    				if(empty($person->o)){
    					$person->o = $organization_name;
    				} else {
    					if(!is_array($person->o)) {
    						$o = explode(',', $person->o);
    					} else {
    						$o = $person->o;
    					}
    					if(!in_array($selected_object_id, $o)) {
    						$o[] = $organization_name;
    						$person->o = $o;
    					}
    				}
    				 
    				if($person->save(false)){
    					$message = $person->cn." has been associated to ".$organization_name;
    					$tab = "#tab_memberOf";
    						
    				} else {
    					$this->returnError('The association process failed.');
    				}
    				
    				 
    				if(isset($message)) {
    					$to_js = array();
    					$to_js['message'] = $message;
    					$to_js['focus_tab'] = $tab;
    				}
    			} else {
    				$this->returnError('Unknown association has been requested.');
    			}
    		break;
    		
    		case 'personAdminOfOrganization':
    			if(empty($object_name) || empty($object_id) || empty($related_object_name) || empty($related_object_id)) $this->returnError('Some information are missing');
    			
    			$selected_object_name = $object_name;
    			$selected_object_id = $object_id;
    			 
    			if($selected_object_name=='organization' && $related_object_name=='person') {
    				
	    			$this->getOrganization($organization, $selected_object_id);
	    			$organization_name = $organization->o;
	    			
	    			$this->getPerson($person, $related_object_id);
	    			
	    			if(empty($person->oAdminRDN)){
	    				$person->oAdminRDN = $selected_object_id;
	    				$message = $person->cn." is now administrator of ".$organization_name;
	    			} else {
	    				if(!is_array($person->oAdminRDN)) {
	    					$oAdminRDN = explode(',', $person->oAdminRDN);
	    				} else {
	    					$oAdminRDN = $person->oAdminRDN;
	    				}
	    				if(!in_array($selected_object_id, $oAdminRDN)) {
	    					$oAdminRDN[] = $selected_object_id;
	    					$message = $person->cn." is now administrator of ".$organization_name;
	    				} else {
	    					//remove the administration (toggle effect)
	    					foreach ($oAdminRDN as $key => $value) {
	    						if($value == $selected_object_id) unset($oAdminRDN[$key]);
	    					}
	    					$message = $person->cn." is no more administrator of ".$organization_name;
	    				}
	    				if(count($oAdminRDN)>0) {
	    					$person->oAdminRDN = $oAdminRDN;
	    				} else {
	    					$person->oAdminRDN = '';
	    				}
	    			}
	    			
	    			if($person->save(false)){
	    				$to_js = array();
	    				$to_js['message'] = $message;
	    				$to_js['focus_tab'] = '#tab_memberOf';	    				 
	    			} else {
	    				$this->returnError('The process failed.');
	    			}	    			
    			}    			 
    		break;
    		
    		default:
    			$this->returnError('An invalid procedure has been requested');
    		break;
    	}

    	if(isset($to_js)) {
    		$this->output($to_js);
    	} else {
    		$this->returnError('Something went wrong');
    	}
    	
    }
    
    public function validateForm() {
		//TODO implement validation
		$to_js = array();
		$this->output($to_js);
    	//$this->returnError('The form has not been validated.');
    }
    
    public function updateLocation() {
    	$form = $this->input->post('form');
    	$related_object_name = urldecode(trim($this->input->post('related_object_name')));
    	$related_object_id = urldecode(trim($this->input->post('related_object_id')));
    	
    	if(!$form || !is_array($form)) $this->returnError('The form can not be processed.');
    	if(!$related_object_name || is_array($related_object_name)) $this->returnError('Missing or wrong related object name.');
    	if(!$related_object_id || is_array($related_object_id)) $this->returnError('Missing or wrong related object id.');
    	
    	$possible_object_names = array('person','organization','location');

    	if(!in_array($related_object_name, $possible_object_names)) $this->returnError('The specified object '.$related_object_name.' is not a valid object.');
    	
    	switch ($related_object_name) {
    		case 'person':
    			$contact = new Mdl_Person();
    			$contact->uid = $related_object_id;
    		break;
    		
    		case 'organization':
    			$contact = new Mdl_Organization();
    			$contact->oid = $related_object_id;
    		break;
    		
    		default:
    			$this->returnError('The specified object'.$related_object_name.' can not be a related object.');
    		break;
    	}
    	
    	$result = $contact->get(null,true);
    	if($result['status']['status_code'] != 200) $this->returnError('The specified related contact with id '.$related_object_id.' can not be found.');

    	$contact_result = $result['data']['0'];
    	
    	$location = new Mdl_Location();
    	
    	$input=array();
    	foreach ($form as $key => $item) {
    		if(!empty($item['field']) && isset($item['value'])) {
    			$input[$item['field']] = $item['value'];
    		}
    	}
    	
    	//let's check if the user set one of the two reserved descriptions
    	if(strtolower($input['locDescription']) == 'home' || strtolower($input['locDescription']) == 'registered address' ) {
    		$this->returnError('The description "'.$input['locDescription'].'" is reserved. Please choose another description.');
    	}
    	
    	$creation = ($input['locId']=='') ? true : false;
    	
    	$return = $location->save($creation,false,$input);
    	
    	if($return) { 
    		if(empty($location->locId)) $this->returnError('Something went wrong during the location save process.');
    		
    		if($creation){
	    		//associate the contact with the new location
	    		if(!empty($contact_result['locRDN'])) {
	    		
	    			$locs = implode(',', $contact_result['locRDN']);
	    			$locs .= ','.$location->locId;
	    		} else {
	    			$locs = $location->locId;  
	    		}			
	    		$contact_result['locRDN'] = explode(',', $locs);
	    		
	    		if($contact->arrayToObject($contact_result)) {
	    			//TODO add cases
	    			if($contact->save(false)) {
	    				$message = 'The location has been created.';
	    			} else {
	    				$message = 'The location has been created but it has not been associated to the contact.';
	    			}
	    		}
	    	} else {
				$message = 'The location has been updated.';
	    	}     		
    	} else {
    		//do something
    		$this->returnError('The location has not been created');
    	}
    	
    	if(isset($message)) { 	    				
    		$to_js = array();
	    	$to_js['message'] = $message;
	    	$to_js['focus_tab'] = '#tab_locations';
	    	$this->output($to_js);
    	}
    }
    
    public function delete(){
    	$params = $this->input->post('params');
    	if(!is_array($params) || count($params) == 0) $this->returnError('Some information are missing'); //TODO translate with CI standard way
    	
    	extract($params);
    	
//     	
//     	if(isset($params['procedure'])) $procedure = urlencode(trim($params['procedure']));
//     	if(isset($params['object_name'])) $object_name = urlencode(trim($params['object_name']));
//     	if(isset($params['object_id'])) $object_id = urlencode(trim($params['object_id']));

    	switch ($procedure) {
    		case 'deleteLocation':
    			if(!isset($object_name) || !isset($object_id)) $this->returnError('Some information are missing');
    			
    			if(strtolower($object_name) != 'location') $this->returnError('The specified '.$object_name.' can not be deleted.');
    			$location = new Mdl_Location();
    			$location->locId = $object_id;
    			$input = array();
    			$input['locId'] = $object_id;
    			
    			if($location->delete($input)) {
    				$to_js = array();
    				$to_js['message'] = 'The location has been deleted.';
    				$to_js['focus_tab'] = '#tab_locations';
    			} else {
    				$this->returnError('The location has not been deleted');
    			}    			 
    		break;
    		
    		case 'deleteOrganizationMembership':
    			if(!isset($object_name) || !isset($object_id) || !isset($related_object_name) || !isset($related_object_id)) $this->returnError('Some information are missing');    			
    			if(strtolower($object_name) != 'organization') $this->returnError('The specified object_name '.$object_name.' can not be used in this procedure.');
    			//get the person
    			$person = new Mdl_Person();
    			$person->uid = $related_object_id;
    	    	$result = $person->get(null);
    	    	
	    		if($result['status']['status_code']=='200' && $result['status']['results_number']=='1') {
	    			//get the organization
	    			$organization = new Mdl_Organization();
	    			$organization->oid = $object_id;
	    			$res = $organization->get();
	    			if($res['status']['status_code']=='200' && $result['status']['results_number']=='1') {
	    				$organization->arrayToObject($res['data']['0']);
	    				$organization_name = $organization->o;
	    			} else {
	    				$this->returnError('The selected organization can not be found');
	    			}	    			
	    			
	    			//update person's attributes
	    			$person->arrayToObject($result['data']['0']);
	    			
	    			$ordn = explode(',', $person->oRDN);
	    			foreach ($ordn as $key => $item) {
	    				if($item == $object_id) {
	    					 unset($ordn[$key]);
	    				}
	    			}
	    			if(count($ordn) > 0) {
	    				$person->oRDN = implode(',', $ordn);
	    			} else {
	    				$person->oRDN = '';
	    			}
	    			
	    			$oAdminRDN = explode(',', $person->oAdminRDN);
	    			foreach ($oAdminRDN as $key => $item) {
	    				if($item == $object_id) {
	    					unset($oAdminRDN[$key]);
	    				}
	    			}
	    			if(count($oAdminRDN) > 0) {
	    				$person->oAdminRDN = implode(',', $oAdminRDN);
	    			} else {
	    				$person->oAdminRDN = '';
	    			}
	    			
	    			$o = explode(',', $person->o);
	    			foreach ($o as $key => $item) {
	    				if($item == $organization_name) {
	    					unset($o[$key]);
	    				}
	    			}
	    			if(count($o) > 0) {
	    				$person->o = implode(',', $o);
	    			} else {
	    				$person->o = '';
	    			}	
		    		 
	    			if($person->save(false)){
	    				$to_js = array();
	    				$to_js['message'] = $person->cn." has been disassociated from ".$organization_name;
	    				$to_js['focus_tab'] = '#tab_memberOf';	    				
		    		} else {
		    			$this->returnError('The association process failed.');
		    		}
	    		}
    		break;
    		
    		default:
    			$this->returnError('An invalid procedure has been requested');
    		break;
    	}
    	
    	if(isset($to_js)) {
    		$this->output($to_js);
    	} else {
    		$this->returnError('Something went wrong');
    	}	
    }
    
    public function t(){
    	$this->load->helper('security');
    	$str = rand(100000000000, 9000000000000);
    	$str2 = do_hash($str); // SHA1
    	echo $str.' lenght: '. strlen($str).' -> '.$str2 .' lenght: '. strlen($str2).'<br>';
    	
    	$str2 = do_hash($str); // SHA1
    	echo $str.' lenght: '. strlen($str).' -> '.$str2 .' lenght: '. strlen($str2);
    	 
    }
}