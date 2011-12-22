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

    //work around to make CI pagination to fit CE pagination
    private function get_wanted_page()
    {
    	$results_per_page = $this->mdl_mcb_data->setting('results_per_page');
    	
    	if($results_per_page == 0) return 0;
    	
    	$uripage = uri_assoc('page');
    	$page = ceil(uri_assoc('page') / $results_per_page);
    	
    	if($page <= 0) return 0;
    	
    	return $page; 
    }
    
    function index() {

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
                  			'uid' 			=>  $uid,
                  			'oid' 			=>  $oid,
        				);
        
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


    
    function form() {

        $this->load->model(
            array(
            'mcb_data/mdl_mcb_client_data',
            'invoices/mdl_invoice_groups'
            )
        );

        $obj = uri_assoc('add'); //new contact request => prepare empty form
        
        $uid = $this->input->post('uid');
        $oid = $this->input->post('oid');
        if(isset($uid) and $uid !== false) $obj = 'person';  //the empty form has been submitted and now it's ready for save
        if(isset($oid) and $oid !== false) $obj = 'organization';
        
        $contact = $this->retrieve_contact();  
        if(isset($contact->uid)) $obj = 'person'; // a contact has been selected to be edited
        if(isset($contact->oid)) $obj = 'organization';
        
        if ($this->mdl_contacts->validate($obj)) {

        	//it's a submit
            $this->mdl_contacts->save($obj);
            
            if($uid)
            {
            	$contact_id = $uid;
            } else {
            	$contact_id = $oid;
            }
             
            foreach ($this->input->post('client_settings') as $key=>$value) {
                if ($value) {
                    $this->mdl_mcb_client_data->save($contact_id, $key, $value);
                }
                else {
                    $this->mdl_mcb_client_data->delete($contact_id, $key);
                }
            }

            redirect($this->session->userdata('last_index'));  //TODO what is this?

        }

        else {

        	//it's not a submit so let's fill the form with customer's data
            $this->load->model('templates/mdl_templates');

            $this->load->helper('form');
			
 			//preparing the form
            if (!$_POST) {
                //$this->mdl_contacts->prep_validation($contact_id);
                if(!is_object($contact)) {
                	//preparing empty form to add a new com
                	$this->contact->getProperties($obj);
                	//creating an empty contact
                	$contact = new Mdl_Person();
                	$properties = $this->contact->properties;
                	
                	//empty every attribute
                	foreach ($properties as $key => $value) {
                		$contact->$key = '';
                	}
                	
					$contact = $this->prepareShow($obj,$contact);
					$contact->properties = $properties;
                	
                	//$contact = array_keys($properties);
                } else {
	            	foreach ($contact as $key => $value) {
	            		if($key != "properties") $this->mdl_contacts->form_values[$key] = $value;
	            	}
                }
            }

            $data = array(
                //'custom_fields'     =>	$this->mdl_contacts->custom_fields,
            	'contact'			=>  $contact,
                'invoice_templates' =>  $this->mdl_templates->get('invoices'),
                'invoice_groups'    =>  $this->mdl_invoice_groups->get()
            );

            $this->load->view('form', $data);

        }

    }

    function details() {
    	
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

        //preparing output for views
        $data = array(
            'contact'	=>	$contact,
            'invoices'	=>	$invoices,
            'tab_index'	=>	$tab_index,
            'baseurl'	=>	site_url(),
        );
        
        //loading Smarty template
        $data['invoices_html'] = $this->load->view('invoices/invoice_table',$data,true);
        $data['middle']	= $this->plenty_parser->parse('details.tpl', $data, true, 'smarty', 'contact');
         
        //loading CI template
        $this->load->view('details', $data);

    }
    
    function retrieve_contact()
    {
    	$uid = uri_assoc('uid');
    	$oid = uri_assoc('oid');
    	if(empty($uid) && empty($oid)) 
    	{
    		if(uri_assoc('client_id'))
    		{
    			$client_id = uri_assoc('client_id');
    		} else {
    			if($this->input->post('client_id')) $client_id = $this->input->post('client_id');
    		}
    	}
    	
    	if($uid) $params = array('uid' => $uid);
    	if($oid) $params = array('oid' => $oid);    		 
    	if($client_id) $params = array('client_id' => $client_id);
    	
    	if(!$oid && !$uid && !$client_id) return false;
    	
    	//perform the request to Contact Engine
    	$rest_return = $this->mdl_contacts->get($params);      	//TODO add check on rest_status
    	
    	//when the request is performed using client_id || uid || oid as input I get an object in return, not an array
    	if(is_object($rest_return))
    	{
    		$contact = $rest_return;
    		
    		if($rest_return->uid) $obj = 'person';
    		if($rest_return->oid) $obj = 'organization';
    		$contact = $this->prepareShow($obj,$contact);
    	}
    	
    	return $contact;
    }

    function prepareShow($obj, &$contact)
    {
    	$this->$obj->prepareShow();
    	$contact->show_fields = $this->$obj->show_fields;
    	$contact->hidden_fields = $this->$obj->hidden_fields;
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