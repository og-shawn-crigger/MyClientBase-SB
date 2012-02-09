<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//modified by Damiano Venturin @ squadrainformatica.com

class Contact extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->_post_handler();

        //TODO Ideally you would autoload the parser
        $this->load->driver('plenty_parser');

        $this->load->model('mdl_contacts');
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
     * @todo		
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
     * @todo		
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
        
    public function index() {

        $this->load->helper('text');
        
        $search = $this->input->post('search');
        
        if(!$search)
        {
        	//try to retrieve from session
        	$search = $this->session->userdata('search');
        	$wanted_page = $this->get_wanted_page();
        } else {
        	//the user just hit the ENTER in the input box
        	//set into session
        	$this->session->set_userdata('search', $search);
        	$this->redir->set_last_index(site_url('contact/index'));
        	$wanted_page = 0;
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
        if(!$search)
        {
        	$uid = uri_assoc('uid');
        	$oid = uri_assoc('oid');
        }
        
        //if the user clicked on the top of the table column to change the display order ...
        $user_order = explode('_', uri_assoc('order_by')); 
        if(count($user_order) == 2) 
        {
        	$params['order_by'] = $user_order['0'];
        	$params['flow_order'] = $user_order['1'];
        }
		
        $data = array(
            'contacts'	=>	$this->mdl_contacts->get($params),
        );
        
        //template workaround to allow the usage of Smarty
        $data['button_add'] = $this->load->view('dashboard/btn_add', array('btn_name'=>'btn_add_client', 'btn_value'=>$this->lang->line('add_client')),true);
        $data['baseurl'] = site_url();
        $data['pager'] = $this->mdl_contacts->page_links;
        if($search) $data['searched_string'] = $search;
        if($search || $uid || $oid) $data['made_search'] = true;
        
        //loading Smarty template
        $data['js_autofocus'] = $this->load->view('dashboard/jquery_set_focus', array('id'=>'search-box'), true);
        $data['middle'] = $this->plenty_parser->parse('index_ce.tpl', $data, true, 'smarty', 'contact');
        
        $this->load->view('index_ce', $data);
    }

	private function getContactById()
	{
		//I can get the $contact_id in 4 possible ways: by uid, by oid, by POST and GET
		$uid = $this->input->post('uid');
		if(empty($uid)) unset($uid);

		$oid = $this->input->post('oid');
		if(empty($oid)) unset($oid);

		if(isset($uid) && isset($oid)) 
		{
			return false; //I can't understand if it's a person or an organization
		} else {
			if(!isset($uid) && !isset($oid))
			{
				//let's look for the contact_id
			    if(uri_assoc('client_id'))
	    		{
	    			$contact_id = uri_assoc('client_id');   //retrieving client_id from GET
	    		} else {
	    			if($this->input->post('client_id')) $contact_id = $this->input->post('client_id'); //retrieving client_id from POST
	    		}
				if(empty($contact_id)) return false; //there is no other way to get the object
			}
		}
		
		if(isset($uid)) $contact_id = $uid;
		if(isset($oid)) $contact_id = $oid;
		
		//retrieve the exact object (person or organization)
		$obj = new Mdl_Contact();
		$obj->client_id = $contact_id;
		if(! $obj = $obj->get(null,false)) return false;
		$obj->prepareShow();
		return $obj;
	}
    
    public function form() {

    	$this->load->helper(array('form', 'url'));
    	
    	//let's see with which kind of object we are dealing
    	if(! $obj = $this->getContactById()) return false;
    	
    	if ($this->mdl_contacts->validate($obj)) {
    		
    		$submit = true;
    		//the form has been validated. Let's check if there is any binary file uploaded
    		$upload_info = saveUploadedFile();
    		
    		//TODO error handling
    		if(is_array($upload_info['data'])) {
    			foreach ($upload_info['data'] as $element => $element_status)
    			{
    				//reads the file and converts it in base64 and stores it in $obj
    				$this->load->helper('file');
    				
    				$binary_file = base64_encode(read_file($element_status['full_path']));
    				
    				unlink($element_status['full_path']);
    				
    				if($binary_file) $obj->$element = $binary_file;
    			}
    		}
    		    		
    		$properties = array_keys($obj->properties);
    		//$data = array();
    		foreach ($this->mdl_contacts->form_values as $property => $value) {
    			if(in_array($property, $properties))
    			{
    				$obj->$property = $value;
    			}
    		}
    		
    		//ready to save in ldap
    		$obj->save();
    		
    		//this brings back to the previous page
    		redirect($this->session->userdata('last_index'));    		
    	}
    	
    	$contact_id = $obj->uid ? $obj->uid : $obj->oid;
    	
    	$client_settings = $this->input->post('client_settings');
    	if(is_array($client_settings))
    	{    	 
	    	foreach ($client_settings as $key=>$value) {
	    		if ($value) {
	    			$this->mdl_mcb_client_data->save($contact_id, $key, $value);
	    		}
	    		else {
	    			$this->mdl_mcb_client_data->delete($contact_id, $key);
	    		}
	    	}    	
    	}
    	    	
    	//this retrieves other info about the contact that have nothing to do with the contact itself
        $this->load->model(
            array(
            'mcb_data/mdl_mcb_client_data',
            'invoices/mdl_invoice_groups'
            )
        );
        
        //it's not a submit so let's fill the form with customer's data
        $this->load->model('templates/mdl_templates');

        //$this->mdl_contacts->prep_validation($contact_id);
        
        //preparing the form
        foreach ($obj->properties as $key => $property) {
        	$this->mdl_contacts->form_values[$key] = $obj->$key;
        }        
        
        $data = array(
        				//'custom_fields'     =>	$this->mdl_contacts->custom_fields,
                    	'contact'			=>  $obj,
                        'invoice_templates' =>  $this->mdl_templates->get('invoices'),
                        'invoice_groups'    =>  $this->mdl_invoice_groups->get()
        );
        
        $data['form'] = $this->plenty_parser->parse('form.tpl', $data, true, 'smarty', 'contact');
        
        $data['actions_panel'] = $this->plenty_parser->parse('actions_panel.tpl', $data, true, 'smarty', 'contact');
        
        $this->load->view('form', $data);
        
    }

    public function details() {
    	
        $this->redir->set_last_index();

        $this->load->helper('text');  //TODO why not autoload?

        $this->load->model(
            array(
            'invoices/mdl_invoices',
            'templates/mdl_templates'
            )
        );

		$contact = $this->retrieve_contact();

        $invoice_params = array(
            'where'	=>	array(
                'mcb_invoices.client_id'        =>	$contact->client_id,
                'mcb_invoices.invoice_is_quote' =>  0
            )
        );

        if (!$this->session->userdata('global_admin')) {

            $invoice_params['where']['mcb_invoices.user_id'] = $this->session->userdata('user_id');

        }

        $invoices = $this->mdl_invoices->get($invoice_params);

        if ($this->session->flashdata('tab_index')) {

            $tab_index = $this->session->flashdata('tab_index');

        } else {

            $tab_index = 0;

        }

        //getting Locations
        $locs = explode(",", $contact->locRDN);
        if(!empty($locs))
        {
        	$contact_locs = array();
        	 
        	foreach( $locs as $locId)
        	{
        		$params = array('locId' => $locId);
        		$loc = $this->location->get($params);  //FIXME this step takes too long
        		if($loc['status']['status_code'] == 200)
        		{
        			$this->location->prepareShow();
        			$contact_locs[] = clone $this->location;
        		}
        	}
        } 
                
        //getting Organizations of which the contact is member
        $orgs = explode(",", $contact->oRDN);
        if(is_array($orgs))
        {
        	$contact_orgs = array();
        	
	        foreach( $orgs as $oid)
	        {
	        	$params = array('oid' => $oid);
	        	$org = $this->mdl_contacts->get($params);
	        	if($org)
	        	{
	        		$org->prepareShow();
	        		$contact_orgs[] = $org;
	        	}
	        }
        }
         
        //preparing output for views
        $data = array(
            'contact'	=>	$contact,
            'contact_orgs' => $contact_orgs,
        	'contact_locs' => $contact_locs,
            'invoices'	=>	$invoices,
            'tab_index'	=>	$tab_index,
            'baseurl'	=>	site_url(),
        );
        
        //loading Smarty template
        //$data['invoices_html'] = $this->load->view('invoices/invoice_table',$data,true);
        
        $data['actions_panel'] = $this->plenty_parser->parse('actions_panel.tpl', $data, true, 'smarty', 'contact');
        $data['details']	= $this->plenty_parser->parse('details.tpl', $data, true, 'smarty', 'contact');
        
        //loading CI template
        $this->load->view('details', $data);

    }
    
    //TODO this function is called only in this file. I think I can refactor code so that I can get the rid of it
    public function retrieve_contact()
    {
    	$uid = uri_assoc('uid');
    	$oid = uri_assoc('oid');
    	if(empty($uid) && empty($oid)) 
    	{
    		if(uri_assoc('client_id'))
    		{
    			$client_id = uri_assoc('client_id');   //retrieving client_id from GET
    		} else {
    			if($this->input->post('client_id')) $client_id = $this->input->post('client_id'); //retrieving client_id from POST
    		}
    	}
    	
    	if($uid) $params = array('uid' => $uid);
    	if($oid) $params = array('oid' => $oid);    		 
    	if(isset($client_id) && $client_id) $params = array('client_id' => $client_id);
    	
    	//check: I need at least one of the 3 parameters uid,oid,client_id
    	if(!isset($params) || count($params)==0) return false;
    	
    	//perform the request to Contact Engine
    	$rest_return = $this->mdl_contacts->get($params);      	//TODO add check on rest_status
    	
    	//when the request is performed using client_id || uid || oid as input I get an object in return, not an array
    	if(is_object($rest_return))
    	{
    		$obj = $rest_return;
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