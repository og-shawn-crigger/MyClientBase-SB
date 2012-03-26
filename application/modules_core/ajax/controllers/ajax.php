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
    	global $callback;

    	$params = $this->input->post('params');
    	if(!is_array($params) || count($params) == 0) $this->returnError('Some information are missing'); //TODO translate with CI standard way
    	
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
    	 
    	if(isset($params['object_name'])) $object_name = urlencode(trim($params['object_name']));
    	if(isset($params['object_id'])) $object_id = urlencode(trim($params['object_id']));
    	 
    	$possible_object_names = array('location');    	
    	
    	if(!in_array($object_name, $possible_object_names)) $this->returnError('The specified '.$object_name.' can not be deleted.');
    	
    	switch ($object_name) {
    		case 'location':
    			$location = new Mdl_Location();
    			$location->locId = $object_id;
				$input = array();
				$input['locId'] = $object_id;    		
    		break;    		
    	}
    	
    	if($location->delete($input)) {
    		$to_js = array();
    		$to_js['message'] = 'The location has been deleted.';
    		$to_js['focus_tab'] = '#tab_locations';
    		$this->output($to_js);
    	} else {
    		$this->returnError('The location has not been deleted');
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