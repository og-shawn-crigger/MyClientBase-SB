<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//modified by Damiano Venturin @ squadrainformatica.com

class Contact extends Admin_Controller {

	public $enabled_modules;
	
    function __construct() {

        parent::__construct();
                
        $this->_post_handler();

        $this->load->model('mdl_contacts');
        
        $this->enabled_modules = $this->mdl_mcb_modules->get_enabled();
    }

    /**
     * work around to make CodeIgniter pagination fit ContactEngine pagination 
     * 
     * @access		private
     * @param		
     * @var			
     * @return		string
     * @example
     * @see
     * 
     * @author 		Damiano Venturin
     * @copyright 	2V S.r.l.
     * @license		GPL
     * @link		http://www.squadrainformatica.com/en/development#mcbsb  MCB-SB official page
     * @since		Feb 6, 2012
     * 	
     */
    private function get_wanted_page()
    {
    	$results_per_page = $this->mdl_mcb_data->setting('results_per_page');
    	
    	if($results_per_page == 0) return 0;
    	
    	$uripage = uri_assoc('page');
    	$page = ceil(uri_assoc('page') / $results_per_page);
    	
    	if($page <= 0) return 0;
    	
    	return $page; 
    }

    /**
     * This function sets the new configuration values for the objects Person, Organization and Location using the common Code Igniter method
     * $this->config->set_item. Afterwards, writes the values in the config file using the function write_config
     * 
     * @access		private
     * @param		
     * @var			
     * @return		boolean
     * @example
     * @see
     * 
     * @author 		Damiano Venturin
     * @copyright 	2V S.r.l.
     * @license		GPL
     * @link		http://www.squadrainformatica.com/en/development#mcbsb  MCB-SB official page
     * @since		Feb 6, 2012
     * 	
     */
    private function update_config(&$obj, $configfile)
    {
    	if(!is_object($obj)) return false;
    	
    	//update the configuration file
    	$this->load->helper('configfile');
    	
    	$this->config->set_item($obj->objName.'_show_fields', $obj->show_fields);
    	$this->config->set_item($obj->objName.'_attributes_aliases', $obj->aliases);
    	if(write_config($configfile, array($obj->objName.'_show_fields', $obj->objName.'_attributes_aliases', $obj->objName.'_hidden_fields'),true))
    	{
    		$obj->prepareShow();  //refreshes the object with the new values
    		return true;
    	}
    	return false;
	}
    
    /**
     * This controller method (function) is defined as calledback in the config.php and is called by MCB when the System Settings Panel is displayed.
     * MCB provides, so, a specific "settings tab" for the module Contact. This function is called only once, when the System Settings is loaded.
     * After that, during the accordion operations etc, this function is no more called. 
     * The aim of the function is to get, populate and return the html of several tpl files. The html returned will populate the "setting tab" 
     * 
     * @access		public
     * @param		array | null	An array containing the data to send to the tpl files or nothing
     * @return		string | false
     * @example
     * @see
     * 
     * @author 		Damiano Venturin
     * @copyright 	2V S.r.l.
     * @license		GPL
     * @link		http://www.squadrainformatica.com/en/development#mcbsb  MCB-SB official page
     * @since		Feb 6, 2012
     * 
     */
    public function display_settings()
    {
    	$data = array();
    	
    	$obj = new Mdl_Person();
    	$obj->getProperties();
    	$obj->prepareShow();
    	$data['settings_person'] = $this->display_object_settings($obj);

    	$obj = new Mdl_Organization();
    	$obj->getProperties();
    	$obj->prepareShow();
    	$data['settings_organization'] = $this->display_object_settings($obj);
    	
    	$obj = new Mdl_Location();
    	$obj->getProperties();
    	$obj->prepareShow();
    	$data['settings_location'] = $this->display_object_settings($obj);
    	
    	$this->plenty_parser->parse('settings.tpl', $data, false, 'smarty', 'contact');
    }
      
	private function display_object_settings(&$obj, $tpl = null)
	{	
		if(!is_object($obj)) return false;
		if(is_array($tpl)) return false;
		
		//collects data for the tpl files
		$data = array(
		    	    	$obj->objName.'_all_attributes' => $obj->properties,
		    	    	$obj->objName.'_visible_attributes' => $obj->show_fields,
		    	    	$obj->objName.'_aliases' => $obj->aliases,
		);
		 
		if(is_array($obj->properties) and is_array($obj->show_fields))
		{
			$data[$obj->objName.'_available_attributes'] = array_diff_key($obj->properties, array_flip($obj->show_fields));
		} else {
			$data[$obj->objName.'_available_attributes'] = array();
		}
		 
		//feeds and loads the right tpl file and returns the html output
		$tpls = array('order','aliases');
		if(in_array($tpl, $tpls))
		{
			return $this->plenty_parser->parse('settings_'.$obj->objName.'_'.$tpl.'.tpl', $data, true, 'smarty', 'contact');
		} else {
			return $this->plenty_parser->parse('settings_'.$obj->objName.'.tpl', $data, true, 'smarty', 'contact');
		}
	}
    
	/**
	 * This function adds or removes an attribute to the "visible-attributes-set" for the given object and writes it in the configuration file.
	 * 
	 * @access		private
	 * @param		$obj	object	The given object (person, organization, location)
	 * @param		$action	string	The action to perform (add | remove)
	 * @param		$attribute	string	The attribute to add to the "visible-attributes-set" 		
	 * @return		boolean
	 * @example
	 * @see
	 * 
	 * @author 		Damiano Venturin
	 * @copyright 	2V S.r.l.
	 * @license		GPL
	 * @link		http://www.squadrainformatica.com/en/development#mcbsb  MCB-SB official page
	 * @since		Feb 6, 2012
	 * 
	 */ 
	private function toVisible(&$obj, $action, $attribute)
	{	
		if(!is_object($obj)) return false;
		if(is_array($attribute)) return false;
		if(is_array($action)) return false;
		
		switch ($action) {
			case 'add':
				if(in_array($attribute, $obj->show_fields))
				{
					return true; //no changes: there is nothing to do here
				} else {
					array_push($obj->show_fields, $attribute);;
				}
			break;
			
			case 'remove':
				if(count($obj->show_fields)>1)  //you can not remove all the fields
				{
					//temporary array
					$tmp = array_flip($obj->show_fields);
					if(isset($tmp[$attribute]))
					{
						unset($tmp[$attribute]);
						$obj->show_fields = array_flip($tmp);
					} else {
						return true; //no changes: there is nothing to do here
					}
				}				
			break;
			
			default:
				return false;
			break;
		}
		
		return $this->update_config($obj,$obj->objName);			
	}
    
    private function display_settings_location($input=null)
    {
    	$data = array();
    	return $this->plenty_parser->parse('settings_location.tpl', $data, true, 'smarty', 'contact');
    	
    }
    
    private function getAjaxItem()
    {
    	if($this->input->post('item')!= "")
    	{
    		$split = preg_split('/_/', $this->input->post('item'));
    		if(isset($split['1'])) return $split['1'];
    	}
    	return false;
    }
    
    
    public function save_settings()
    {
    	return true;
    }
    
    /**
     * This function is called by the javascript (System Settings) everytime there is an event (drag, sort, submit)
     * It updates the config file for the specific object and returns the updated html to the javascript which
     * replaces the old content with the new one   
     * 
     * @access		private
     * @param
     * @return		string		
     * @example
     * @see
     * 
     * @author 		Damiano Venturin
     * @copyright 	2V S.r.l.
     * @license		GPL
     * @link		http://www.squadrainformatica.com/en/development#mcbsb  MCB-SB official page
     * @since		Feb 6, 2012
  	 *
     */
    public function update_settings()
    {
    	$output = '';
    	$data = array();
    	
    	$split = preg_split('/_/', $this->input->post('action'));
    	if(isset($split['0'])) $objName = $split['0'];
    	if(isset($split['1'])) $action = $split['1'];
    	
		switch ($objName) {
			case 'person':
		    	$obj = new Mdl_Person();
			break;

			case 'organization':
				$obj = new Mdl_Organization();
			break;

			case 'location':
				$obj = new Mdl_Location();
			break;			
			
			default:
				return false;
			break;
		}

		$obj->getProperties();
		$obj->prepareShow();
		
		$tpl = null;
		
    	switch ($action) {
    		
    		case 'addToVisible':
    			if($attribute = $this->getAjaxItem()) $this->toVisible($obj, 'add', $attribute);		
    		break;

    		case 'removeFromVisible':
    			if($attribute = $this->getAjaxItem()) $this->toVisible($obj, 'remove', $attribute);
    		break;
    			    		    		
    		case 'sort':
    			//show_fields is the ordered array of fields coming from the accordion
    			$show_fields = $this->input->post(ucfirst(strtolower($objName)).'VisibleAttributes');

    			if(is_array($show_fields)) {
    				if(is_array($obj->show_fields))
    				{
    					//let's check if the given array (show_fields) is not fake
    					if(count(array_diff($obj->show_fields, $show_fields)) == 0) {
    						//there are no differences so I can assume that the POST wasn't manipulated
    						$obj->show_fields = $show_fields;
    						$this->update_config($obj, $obj->objName);
    					}
    				}
    			}
    			$tpl = 'order';
    		break;

    		case 'aliases':
    			if($this->input->post('save')){
    				if(is_array($this->input->post('form'))) { 
    					$aliases = array();
    					$form = $this->input->post('form');
    					foreach ($form as $key => $item) {
    						if(!empty($item['value']) and $item['type'] == 'TEXT') $aliases[$item['field']] = strtolower(only_chars_nums_underscore(($item['value'])));
    					}
    					$obj->aliases = $aliases;
    					$this->update_config($obj,$obj->objName);
    				}
    			}
    			$tpl = 'aliases';
    		break;
    	}
       	
    	echo $this->display_object_settings($obj, $tpl);
    }
    
    public function by_location(){
    	
    	$city = urldecode(trim($this->input->post('city')));
    	$state = urldecode(trim($this->input->post('state')));
    	
    	if($state)
    	{
	    	$params = array(
	    			'paginate'		=>	TRUE,
	    			'items_page'	=>	$this->mdl_mcb_data->setting('results_per_page'),
	    			'wanted_page'	=>	$this->get_wanted_page(),
	    			'search'		=>  array('city' => $city, 'state' => $state),
	    	);    	
	    	
	    	$search = ($city) ? $city.','.$state : $state;
	    	
	    	$contacts = $this->mdl_contacts->get($params,true);
	    	
	    	if($contacts){
	    		$statistics = array();
	    		foreach ($contacts['people'] as $key => $person) {
	    			if(!isset($statistics[$person->mozillaHomeLocalityName])) {
	    				$statistics[$person->mozillaHomeLocalityName] = array('people' => 1, 'organizations' => 0);
	    			} else {
	    				$statistics[$person->mozillaHomeLocalityName]['people']++;
	    			} 
	    		}
	    		
	    		foreach ($contacts['orgs'] as $key => $org) {
	    			if(!isset($statistics[$org->l])) {
	    				$statistics[$org->l] = array('people' => 0, 'organizations' => 1);
	    			} else {
	    				$statistics[$org->l]['organizations']++;
	    			}
	    		}	    		
	    	}
	    	
	    	foreach ($statistics as $city => $values) {
	    		$statistics[$city]['total'] = ($values['people'] + $values['organizations'] );
	    	}
	    	
	    	$data = array(
	    			'contacts'	=>	$contacts,
	    			'statistics' => $statistics,
	    	);
	    	
    	} else {
    		$data = array();
    		$search = '';
    	}
    	    	
    	//template workaround to allow the usage of Smarty
    	$data['baseurl'] = site_url();
    	$data['pager'] = $this->mdl_contacts->page_links;
    	if($search) {
    		$data['searched_string'] = $search;
    		$data['made_search'] = true;
    	}
    	
    	//loading Smarty template
    	$data['js_autofocus'] = $this->load->view('jquery_set_focus', array('id'=>'search-box'), true);
    	$data['middle'] = $this->plenty_parser->parse('by_location.tpl', $data, true, 'smarty', 'contact');
    	
    	$this->load->view('index_ce', $data);
    	 
    }
    
    public function index($search = null) {
    	
        $search = urldecode(trim($this->input->post('search')));
        
        //let's look in the URL
        if(!$search){
        	$segs = $this->uri->segment_array();
        	if(isset($segs['2']) && isset($segs['3']) && $segs['2'] == 'search') {
        		$search = urldecode(trim($segs['3']));
        	}
        }
        
        if(!$search)
        {
        	$reset = $this->input->post('reset');
        	if($reset) {
        		$this->session->set_userdata('search', '');
        		$search = '';
        		$wanted_page = 0;
        	} else {
	        	//try to retrieve from session
	        	$search = $this->session->userdata('search');
	        	$wanted_page = $this->get_wanted_page();
        	}
        } else {
        	//the user just hit the ENTER in the input box
        	//set into session
        	$this->session->set_userdata('search', $search);
        	$this->redir->set_last_index(site_url('contact/index'));
        	$wanted_page = '0';
        }
        
        $params = array(
                   		'paginate'		=>	TRUE,
                        'items_page'	=>	$this->mdl_mcb_data->setting('results_per_page'),
                        'wanted_page'	=>	$wanted_page,
                        'search'		=>  $search,
        				);
        if(isset($uid)) $params['uid'] = $uid;
        if(isset($oid)) $params['oid'] = $oid;
        
        //TODO I think this is not necessary ... we go with the  search only right?
//         if(!$search)
//         {
//         	$uid = uri_assoc('uid');
//         	$oid = uri_assoc('oid');
//         }
        
//        $segs = $this->uri->segments;
        //if the user clicked on the top of the table column to change the display order ...
//         if($segs[count($segs) - 1] == 'order_by') {
//         	$params['order_by'] = $segs[count($segs)];
//         }
//         $user_order = explode('_', uri_assoc('order_by')); 
//         if(count($user_order) == 2) 
//         {
//         	$params['order_by'] = $user_order['0'];
//         	$params['flow_order'] = $user_order['1'];
//         }
		
        $data = array(
            'contacts'	=>	$this->mdl_contacts->get($params),
        );
        
        $data['baseurl'] = site_url();
        $data['pager'] = $this->mdl_contacts->page_links;
        if($search) {
        	$data['searched_string'] = $search;
        	$data['made_search'] = true;
        }
        
        //loading Smarty template
        $data['js_autofocus'] = $this->load->view('jquery_set_focus', array('id'=>'search-box'), true);
        $data['site_url'] = site_url($this->uri->uri_string());
        $data['actions_panel'] = $this->plenty_parser->parse('actions_panel.tpl', $data, true, 'smarty', 'contact');        
        $data['middle'] = $this->plenty_parser->parse('index_ce.tpl', $data, true, 'smarty', 'contact');
        
        $this->load->view('index_ce', $data);
    }

	private function getContactById()
	{
		//I can get the $contact_id in 4 possible ways: by uid, by oid, by POST and GET
		$uid = $this->input->post('uid');
		if(!$uid) unset($uid);
		
		if($uid_value = uri_assoc('uid')) //retrieving uid from GET
		if($uid_value) $uid = $uid_value;
		
		$oid = $this->input->post('oid');
		if(!$oid) unset($oid);
		
		if($oid_value = uri_assoc('oid')) //retrieving oid from GET
		if($oid_value) $oid = $oid_value;
				
		if(isset($uid) && isset($oid)) 
		{
			return false; //I can't understand if it's a person or an organization
		} else {
			if(!isset($uid) && !isset($oid))
			{		
				//let's look for the contact_id
				$contact_id = uri_assoc('client_id');
				
			    if(empty($contact_id)) 
	    		{
	    			if($this->input->post('client_id')) $contact_id = $this->input->post('client_id'); //retrieving client_id from POST
	    		}
				if(empty($contact_id)) return false; //there is no other way to get the object
			}
		}
		
		//retrieve the exact object (person or organization)
		if(isset($contact_id)) {

			$this->contact->client_id = $contact_id;
			if(! $this->contact->get(null,false)) return false;
			
			//TODO something better
			if(isset($this->contact->uid) && !empty($this->contact->uid))
			{
				$uid = $this->contact->uid;
			}

			if(isset($this->contact->oid) && !empty($this->contact->oid))
			{
				$oid = $this->contact->oid;
			}
			
		}
		
		if(isset($uid)) {
			$this->person->uid = $uid;
			if(! $this->person->get(null,false)) return false;
			$this->person->prepareShow();
			return $this->person->objName;
		}
		
		if(isset($oid)) {
			$this->organization->oid = $oid;
			if(! $this->organization->get(null,false)) return false;
			$this->organization->prepareShow();
			return $this->organization->objName;
		}
		
		return false;
	}
    
    public function form() {
    	    	
    	//let's see with which kind of object we are dealing with
    	$obj = $this->getContactById();
    	
    	if(!$obj) {
    		if($add_value = uri_assoc('add')) //retrieving uid from GET
    		if($add_value) $obj = $add_value;	 
    		//$this->$obj->setFormRules();    		
    	}

    	if($obj) $this->$obj->setFormRules();
    	
    	if (!empty($obj) && $this->$obj->validateForm()) {
    		//it's a submit and the form has been validated. Let's check if there is any binary file uploaded
     		$upload_info = saveUploadedFile();
    		
    		//TODO error handling
    		if(is_array($upload_info['data'])) {
    			
    			$this->load->helper('file');
    			
    			foreach ($upload_info['data'] as $element => $element_status)
    			{
    				//reads the file and converts it in base64 and stores it in $obj	
    				if($element_status['full_path']){
    					$binary_file = base64_encode(read_file($element_status['full_path']));
    					if($binary_file) $this->$obj->$element = $binary_file;
    					unlink($element_status['full_path']);
    				}    				
    			}
    		}    		
    		
    		//ready to save in ldap
    		if($this->$obj->save()) {
    			
    			if(isset($this->$obj->uid))  redirect(site_url()."/contact/details/uid/".$this->$obj->uid);

    			if(isset($this->$obj->oid))  redirect(site_url()."/contact/details/oid/".$this->$obj->oid);

    			//this brings back to the previous page
    			//redirect($this->session->userdata('last_index'));
    		}    		
    	}
    	
    	
    	//it's not a form submit 
    	if($obj) { 		
    		
    		//unset the errors found during the validation step, otherwise when a new contact is being created it gets errors
    		$form_validation_obj =& _get_validation_object();
    		$form_validation_obj->reset_errors();
    		
    		//the contact is set so it's an early stage update and it needs to fill the form with the contact's data
    		$contact_id = $this->$obj->uid ? $this->$obj->uid : $this->$obj->oid;

    		if(!$contact_id){
    			//it's not an update but a new contact creation
    			$form = $this->input->post('form');

    			switch ($this->$obj->objName) {
    				case 'person':
    					if(empty($form)) {
    						//this means that the form has been submitted automatically by js => no contacts found in the search
    						$first_name = $this->input->post('first_name');
    						$last_name = $this->input->post('last_name');
    					} else {
    						foreach ($form as $item) {
    							if($item['field'] == 'first_name') $first_name = $item['value'];
    							if($item['field'] == 'last_name') $last_name = $item['value'];
    						}
    					}
    					
    					if(isset($first_name) && $first_name) $this->$obj->givenName = $first_name;
    					if(isset($last_name) && $last_name) $this->$obj->sn = $last_name;
    				break;
    				
    				case 'organization':
    					if(empty($form)) {
    						$organization_name = $this->input->post('organization_name');
    					}
    					if(isset($organization_name) && $organization_name) $this->$obj->o = $organization_name;
    				break;
    			}
    		}
    		
    		//preparing the form
    		foreach ($this->$obj->properties as $key => $property) {
    			$this->mdl_contacts->form_values[$key] = $this->$obj->$key;
    		} 
    		
    		//for test purposes
    		$o = $this->$obj;    		
    		
    		//sets form submit url
    		if(isset($this->$obj->uid) && !empty($this->$obj->uid)) $form_url = site_url()."/contact/form/uid/".$this->$obj->uid;
    		
    		if(isset($this->$obj->oid) && !empty($this->$obj->oid)) $form_url = site_url()."/contact/form/oid/".$this->$obj->oid;
    		
    		if(!isset($form_url)) $form_url = site_url()."/contact/form/add/".$obj;
    		
    	} else {
    		//the contact is not set. So it provides an empty form to add a new contact
    		if($add_value = uri_assoc('add')) //retrieving uid from GET
    			if($add_value) $obj = $add_value;
    			
    			$this->$obj->setFormRules();
    			
    			$form_url = site_url()."/contact/form/add/".$obj;
    	}

    	
    	//TODO what is this?
//     	$client_settings = $this->input->post('client_settings');
    	
//     	if(is_array($client_settings))
//     	{    	 
// 	    	foreach ($client_settings as $key=>$value) {
// 	    		if ($value) {
// 	    			$this->mdl_mcb_client_data->save($contact_id, $key, $value);
// 	    		}
// 	    		else {
// 	    			$this->mdl_mcb_client_data->delete($contact_id, $key);
// 	    		}
// 	    	}    	
//     	}
    	    	
    	//this retrieves other info about the contact that have nothing to do with the contact itself
    	//TODO later. MCB stuff
//         $this->load->model(
//             array(
//             'mcb_data/mdl_mcb_client_data',
//             'invoices/mdl_invoice_groups'
//             )
//         );
        
        //it's not a submit so let's fill the form with customer's data
    	//TODO later. MCB stuff
        //$this->load->model('templates/mdl_templates');

        //$this->mdl_contacts->prep_validation($contact_id);
                
        
        $data = array(
        				//'custom_fields'     =>	$this->mdl_contacts->custom_fields,
                    	'contact'			=>  $this->$obj,
        				'form_url'			=> 	$form_url,
                        //'invoice_templates' =>  $this->mdl_templates->get('invoices'),
                        //'invoice_groups'    =>  $this->mdl_invoice_groups->get()
        );
    	
        $data['form'] = $this->plenty_parser->parse('form.tpl', $data, true, 'smarty', 'contact');
        
        $data['actions_panel'] = $this->plenty_parser->parse('actions_panel.tpl', $data, true, 'smarty', 'contact');
        
        $this->load->view('form', $data);
        
    }

    public function details() {
    	
        $this->redir->set_last_index();

        $this->load->model('templates/mdl_templates');
        
        //array sent to the view
        $data = array();
        
        //set the focus of the tab
        if ($this->session->flashdata('tab_index')) {
        	        
        	$tab_index = $this->session->flashdata('tab_index');
        
        } else {
        
        	$tab_index = 0;
        
        }

        $data['tab_index']	=	$tab_index;
        $data['baseurl'] = site_url();
        $data['profile_view'] = true;
        $data['enabled_modules'] = $this->enabled_modules;

		if($contact = $this->retrieve_contact()) {
			$data['contact'] =	$contact;
		} else {
			//TODO redirect somewhere
		}
			
        //getting Locations
        if(isset($contact->locRDN)) $locs = explode(",", $contact->locRDN);
        if(isset($locs) && is_array($locs))
        {
        	$contact_locs = array();
        	 
        	foreach( $locs as $locId)
        	{
        		$this->location->locId = $locId;
        		if($this->location->get())
        		{
        			$this->location->prepareShow();
        			$contact_locs[] = clone $this->location;
        		}
        	}
        } 
                
        //getting Organizations of which the contact is member
        if(isset($contact->oRDN)) $orgs = explode(",", $contact->oRDN);
        if(isset($orgs) && is_array($orgs))
        {
        	$contact_orgs = array();
        	
	        foreach( $orgs as $oid)
	        {
	        	
	        	$this->organization->oid = $oid;
	        	
	        	if($this->organization->get(null, false))
	        	{
	        		$this->organization->prepareShow();
	        		$contact_orgs[] = clone $this->organization;
	        	}
	        }
        }
        
        //in case it's an organization I retrieve the members
        if(isset($contact->oid))
        {
        	$members = array();
        	
	    	$input = array('filter' => '(oRDN='.$contact->oid.')',
	    					'wanted_attributes' => array('uid'));
	    	if($crr = $this->person->get($input, true))
	    	{
	    		$uids = $crr['data'];
	    		foreach ($uids as $item)
	    		{
	    			$this->person->uid = $item['uid']['0'];
	    			if($this->person->get(null, false))
	    			{
		    			$this->person->prepareShow();
		    			$members[] = clone $this->person;
	    			}
	    		}
	    	}
        }
        
        /*
        //sparkleshare plugin
        //retrieves documents for the contact
        //user: git
        $ss_ident =	'z0S9ZSya';
        $ss_authCode = '1AFDm30dwMXkL0pdHTZmAGQATgJ1AV1Yi50clqm0RUV_EbRxaN4EiDO8c59NB662p4AVWUeBHkihrKArK0L_RqF3ugs7U3Cf9lqmr_1XbynEdVaJbevKAVvQiuWDusdMskSQewFf1Gya4ZIVqbniTiJiYtl-wm45En1txvWtKfBfcrj7iL77hGtCfCZWq_o4Ivr4lXHd';        
		$ss_host = 'tooljardev';
		$ss_port = '3000';
        
		// Load the configuration file
		$this->load->config('rest');
		 
		// Load the rest client
		$this->load->spark('restclient/2.0.0');
		
		$this->rest->initialize(array('server' => 'http://'.$ss_host.':'.$ss_port.'/api/'));
		$this->rest->api_key($ss_ident, 'X-SPARKLE-IDENT');
		$this->rest->api_key($ss_authCode, 'X-SPARKLE-AUTH');
        $result = $this->rest->get('getFolderList', null, 'json');
        if($result)
        {
        	foreach ($result as $key => $folder) {
        		if($folder->name = 'Contacts documents') {
        			$ss_contacts_doc_folder_id = $folder->id;
        			$ss_contacts_doc_folder_url = 'http://'.$ss_host.':'.$ss_port.'/folder/'.$ss_contacts_doc_folder_id;
        		}
        	}
        }
        
        if($ss_contacts_doc_folder_id) {
        	//list folder content	
        	$this->rest->api_key($ss_ident, 'X-SPARKLE-IDENT');
        	$this->rest->api_key($ss_authCode, 'X-SPARKLE-AUTH');
        	$ss_contacts_doc_folder_content = $this->rest->get('getFolderContent/'.$ss_contacts_doc_folder_id, null, 'json');
        	
        	//look for the contact's folder
        	$contact_folder = 'coyote_willy';
        	if($ss_contacts_doc_folder_content) {
        		foreach ($ss_contacts_doc_folder_content as $key => $item) {
        			if($item->type == 'dir' && $item->name == $contact_folder) {
        				$ss_contact_folder_id = $item->id;
        				$ss_contact_folder_url = $item->url;
        			}
        		}
        	}
        	
        	if($ss_contact_folder_id) {
				//get contact's content
        		$this->rest->api_key($ss_ident, 'X-SPARKLE-IDENT');
        		$this->rest->api_key($ss_authCode, 'X-SPARKLE-AUTH');
        		$ss_contact_folder_content = $this->rest->get('getFolderContent/'.$ss_contacts_doc_folder_id.'?'.$ss_contact_folder_url, null, 'json');
        	}
        	
        	if($ss_contact_folder_content) {
        		
        		//I don't want to list directories for now => I remove the folder items
        		//I also count the items
        		foreach ($ss_contact_folder_content as $key => $item) {
        			if($item->type == 'dir') {
        				unset($ss_contact_folder_content[$key]);
        			} else {
        				//if it's a hidden file I do not show it
        				$match = preg_match('/^\./', $item->name);
        				if($match == 1)	unset($ss_contact_folder_content[$key]);
        			}        			 
        		}
        		        		
         		$ss_contact_folder_num_items = count($ss_contact_folder_content);
        	}
        }
        */
        
        $location_model = clone $this->location;
        $data['location_model'] = $location_model;
        
        
        //getting invoices and quotes
        if(in_arrayi('invoices',$this->enabled_modules['all'])) {
        	$this->load->model('invoices/mdl_invoices');
        		
        	$data['invoice_module_is_enabled'] = true;
        		
        	$tmpdata = array('invoices/mdl_invoices');  //TODO is this necessary?
        		
        	$invoice_params = array(
        			'where'	=>	array(
        					'mcb_invoices.client_id'        =>	$contact->client_id,
        					'mcb_invoices.invoice_is_quote' =>  0
        			)
        	);
        
        	//TODO is this necessary?
        	//prevents common useres from seeing the invoices made by someonelse
        	// 	        if (!$this->session->userdata('global_admin')) {
        	// 	            $invoice_params['where']['mcb_invoices.user_id'] = $this->session->userdata('user_id');
        	// 	        }
        
        	$invoices = $this->mdl_invoices->get($invoice_params);
        	$tmpdata['invoices'] = $invoices;
        	$data['invoices'] = $invoices;
        	$data['invoices_html'] = $this->load->view('invoices/invoice_table',$tmpdata,true);
        
        	$quote_params = array(
        			'where'	=>	array(
        					'mcb_invoices.client_id'        =>	$contact->client_id,
        					'mcb_invoices.invoice_is_quote' =>  1
        			)
        	);
        
        	$quotes = $this->mdl_invoices->get($quote_params);
        	$tmpdata['invoices'] = $quotes;
        	$data['quotes'] = $quotes;
        	$data['quotes_html'] = $this->load->view('invoices/invoice_table',$tmpdata,true);
        
        }         
        
        //allows creations of tasks for the contact
        if(in_arrayi('tasks',$this->enabled_modules['all'])) {
        		
        	$this->mcbsb->load('tasks/task','task');
        
        	//TODO add a filter to get only the tasks of this contact
        	$params = array();
        	$params['where']['client_id'] = $contact->client_id;
        	if(strtolower($contact->objName) == 'person') $params['where']['client_id_key'] = 'uid';
        	if(strtolower($contact->objName) == 'organization') $params['where']['client_id_key'] = 'oid';
        	
        	if($tasks = $this->mcbsb->task->readAll($params)) {
        		$data['tasks'] = $tasks;
        		$data['tasks_html'] = $this->plenty_parser->parse('tasks_table.tpl', array('tasks' => $tasks), true, 'smarty', 'contact');
        	}
        }
                
        if(isset($contact_locs)) $data['contact_locs'] = $contact_locs;
        if(isset($contact_orgs)) $data['contact_orgs'] = $contact_orgs;
        if(isset($members))
        {	
        	$data['members'] = $members;
        	$data['member_fields'] = array('mobile', 'homePhone', 'companyPhone', 'facsimileTelephoneNumber',  'mail');
        }
        	         
        if(isset($ss_contact_folder_num_items) && $ss_contact_folder_num_items > 0) {
        	$data['ss_contacts_doc_folder_url'] = $ss_contacts_doc_folder_url;
	        $data['ss_contact_folder_content'] = $ss_contact_folder_content;
	        $data['ss_contact_folder_num_items'] = $ss_contact_folder_num_items;
        }
        
        //loading Smarty templates
        $data['site_url'] = site_url($this->uri->uri_string());
        $data['actions_panel'] = $this->plenty_parser->parse('actions_panel.tpl', $data, true, 'smarty', 'contact');
        $data['details']	= $this->plenty_parser->parse('details.tpl', $data, true, 'smarty', 'contact');
        
        //loading CI template
        $this->load->view('details', $data);

    }
    
    public function retrieve_contact()
    {	
    	$params = retrieve_uid_oid();
    	
    	//check: I need at least one of the 3 parameters uid,oid,client_id
    	if(is_null($params) || count($params)==0) return false;
    	
    	//when the request is performed using client_id || uid || oid as input I get an object in return, not an array
    	if(is_object($obj = $this->get($params)))
    	{
    		//$obj = $rest_return;
    		$obj->prepareShow();
    		return $obj;
    	}
    	
    	return false;
    }

    function prepareShow($obj, &$contact)
    {
    	$this->$obj->prepareShow();
    	$contact->show_fields = $this->$obj->show_fields;
    	$contact->hidden_fields = $this->$obj->hidden_fields;
    	$contact->aliases = $this->$obj->aliases;
    	return $contact;
    }
    
    function delete() {

        $client_id = uri_assoc('client_id');

        if ($client_id) $deleted = $this->mdl_contacts->delete($client_id);

        $this->redir->redirect('contact');

    }

    function get($params = NULL) {

        return $this->mdl_contacts->get($params);

    }

    function _post_handler() {

        if ($this->input->post('btn_add_client')) {

            redirect('contact/form');

        }

        elseif ($this->input->post('btn_edit_client')) {

            redirect('contact/form/client_id/' . uri_assoc('client_id'));

        }

        elseif ($this->input->post('btn_cancel')) {

            redirect($this->session->userdata('last_index'));

        }

        elseif ($this->input->post('btn_add_contact')) {

            redirect('contact/contacts/form/client_id/' . uri_assoc('client_id'));

        }

        elseif ($this->input->post('btn_add_invoice')) {

            redirect('invoices/create/client_id/' . uri_assoc('client_id'));

        }

        elseif ($this->input->post('btn_add_quote')) {

            redirect('invoices/create/quote/client_id/' . uri_assoc('client_id'));

        }

    }

}

?>