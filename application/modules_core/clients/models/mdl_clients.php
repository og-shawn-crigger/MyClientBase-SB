<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//modified by Damiano Venturin @ squadrainformatica.com

class Mdl_Clients extends MY_Model {

    public function __construct() {

        parent::__construct();
        
        // Load curl
        $this->load->spark('curl/1.2.0');
        
        // Load the configuration file
        $this->load->config('rest');
         
        // Load the rest client
        $this->load->spark('restclient/2.0.0');
        
        $this->load->helper('url');
    }

    private function getReturnValue($attribute,$person)
    {
    	if(isset($person[$attribute]))
    	{
    		return is_array($person[$attribute]) ? implode(',',$person[$attribute]) : $person[$attribute];
    	}
    	 
    	return 'n.d.';
    }

    //overriding _prep_pagination function
    private function _prep_pagination($params) {
    
    	if (isset($params['paginate']) AND $params['paginate'] == TRUE) {
    
    		$this->load->library('pagination');
    
    		$config = array(
    		   					'base_url'			=>	base_url(),
    		   					'total_rows'		=>	$params['total_rows'],
    		   					'per_page'			=>	$params['items_page'],
    		   					'next_link'			=>	$this->lang->line('next') . ' >',
    		   					'prev_link'			=>	'< ' . $this->lang->line('prev'),
    		   					'cur_tag_open'		=>	'<span class="active_link">',
    		   					'cur_tag_close'		=>	'</span>',
    		   					'num_links'			=>	3,
    		   					'cur_page'			=>  uri_assoc('page'), //$this->offset;
    		);
    		
    		$this->pagination->initialize($config);
    		$this->page_links = $this->pagination->create_links();
    		$params['items_page'] > 0 ? $this->current_page = (uri_assoc('page') / $params['items_page']) + 1 : $this->current_page = 0;
    		$params['items_page'] > 0 ? $this->num_pages = ceil($params['total_rows'] / $params['items_page']) : $this->num_pages = 0;
    	}
    }
    
    public function get($params = NULL) {
    	
    	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/person/'));
    	
    	$input=array();
    	
    	//TODO add oid/uid distinction
    	if(empty($params['uid']))
    	{
    		//looking for all contacts
    		$input['filter'] = '(objectClass=*)';
    	} else {
    		//looking for a specific contact
    		$input['filter'] = '(uid='.$params['uid'].')';
    	}
    	$input['attributes'] = array(	'uid','sn', 'givenName','homePostalAddress','mozillaHomeLocalityName',
    									'mozillaHomeState','mozillaHomeCountryName','mozillaHomePostalCode',
    	        						'companyPhone','facsimileTelephoneNumber','mobile','mail','labeledURI','note','enabled');
    	
    	//defaults
    	isset($params['method']) ? $input['method'] = $params['method'] : $input['method'] = 'POST';
    	isset($params['sort_by']) ? $input['sort_by'] = $params['sort_by'] : $input['sort_by'] = array('sn');
    	isset($params['flow_order']) ? $input['flow_order'] = $params['flow_order'] : $input['flow_order'] = 'asc';
    	isset($params['wanted_page']) ? $input['wanted_page'] = $params['wanted_page'] : $input['wanted_page'] = '0';
    	isset($params['items_page']) ? $input['items_page'] = $params['items_page'] :$input['items_page'] = '12';

		//performing the query to contact engine    	
    	//$url = 'api/exposeObj/person/read/';
    	$rest_return = $this->rest->post('read', $input, 'serialize'); //TODO this should be $this->rest->get
    	
    	//retrieving query info  
    	//$rest_info = array_pop($rest_return); //last item in the returned array contains info about the query
    	$rest_info = $rest_return['status'];										
    	
    	//pagination
    	isset($rest_info['results_number']) ? $params['total_rows'] = $rest_info['results_number'] : $params['total_rows'] = 0; 
    	$this->_prep_pagination($params);
    	
    	//prepare return for output
    	$clients = array();
    	$num = count($rest_return['data']);
    	
    	//TODO what happens when I get 0 contacts? or went I get an error?
    	if (is_array($rest_return['data'])) {
	    	foreach ($rest_return['data'] as $item => $person) {
	    		//TODO this association shouldn't be hardcode and stay somewhere else so that the user can modify it, not mentioning the fact that I can get
	    		//the person attributes throught the getProperties method
	    		$this->client_id = $this->getReturnValue('uid', $person); //$person['uid']['0'];
	    		$this->client_name = $this->getReturnValue('sn', $person).' '.$this->getReturnValue('givenName', $person); //$person['cn']['0'];
	    		$this->client_address = $this->getReturnValue('homePostalAddress', $person); //$person['homePostalAddress']['0'];
	    		$this->client_address_2 = ''; //there is not such a thing is LDAP
	    		$this->client_city = $this->getReturnValue('mozillaHomeLocalityName', $person); //$person['mozillaHomeLocalityName'];
	    		$this->client_state = $this->getReturnValue('mozillaHomeState', $person); //$person['mozillaHomeState'];
	    		$this->client_country = $this->getReturnValue('mozillaHomeCountryName', $person); //$person['mozillaHomeCountryName'];
	    		$this->client_zip = $this->getReturnValue('mozillaHomePostalCode', $person); //$person['mozillaHomePostalCode'];
	    		$this->client_phone_number = $this->getReturnValue('companyPhone', $person); //$person['companyPhone']['0'];
	    		$this->client_fax_number = $this->getReturnValue('facsimileTelephoneNumber', $person); //$person['facsimileTelephoneNumber']['0'];
	    		$this->client_mobile_number = $this->getReturnValue('mobile', $person); //$person['mobile']['0'];
	    		$this->client_email_address = $this->getReturnValue('mail', $person); //$person['mail']['0'];
	    		$this->client_web_address = $this->getReturnValue('labeledURI', $person); //$person['labeledURI']['0'];
	    		$this->client_notes = $this->getReturnValue('note', $person); //$person['note']['0'];
	    		$this->client_tax_id = '0';
	    		$this->client_active = $this->getReturnValue('enabled', $person); //$person['enabled'];
	    		//$this->join_client_id = '';
	    		$this->client_total_invoice = '0.0';
	    		$this->client_total_payment = '0.0';
	    		$this->client_total_balance = '0.0';
	    		
	    		$clients[$item] = clone $this;
	    	}    	
    	}
    	    	
    	if(empty($params['uid']))
    	{
	        return $clients;
    	} else {
    		return $this;
    	} 

    }

//     public function get_active($params = NULL) {

//         if (!$params) {

//             $params = array(
//                 'where'	=>	array(
//                     'client_active'	=>	1
//                 )
//             );

//         }

//         else {

//             $params['where']['client_active'] = 1;

//         }

//         return $this->get($params);

//     }

    public function validate() {

        $this->form_validation->set_rules('client_active', $this->lang->line('client_active'));
        $this->form_validation->set_rules('client_name', $this->lang->line('client_name'), 'required');
        $this->form_validation->set_rules('client_tax_id', $this->lang->line('tax_id_number'));
        $this->form_validation->set_rules('client_address', $this->lang->line('street_address'));
        $this->form_validation->set_rules('client_address_2', $this->lang->line('street_address_2'));
        $this->form_validation->set_rules('client_city', $this->lang->line('city'));
        $this->form_validation->set_rules('client_state', $this->lang->line('state'));
        $this->form_validation->set_rules('client_zip', $this->lang->line('zip'));
        $this->form_validation->set_rules('client_country', $this->lang->line('country'));
        $this->form_validation->set_rules('client_phone_number', $this->lang->line('phone_number'));
        $this->form_validation->set_rules('client_fax_number',	$this->lang->line('fax_number'));
        $this->form_validation->set_rules('client_mobile_number', $this->lang->line('mobile_number'));
        $this->form_validation->set_rules('client_email_address', $this->lang->line('email_address'), 'valid_email');
        $this->form_validation->set_rules('client_web_address', $this->lang->line('web_address'));
        $this->form_validation->set_rules('client_notes', $this->lang->line('notes'));

		//no custom fields here
//         foreach ($this->custom_fields as $custom_field) {

//             $this->form_validation->set_rules($custom_field->column_name, $custom_field->field_name);

//         }

        return parent::validate($this);

    }

    public function delete($client_id) {

    	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/contact/'));
    	
    	//let's delete just the customer for now
    	
    	//TODO should I check if the customer exists before deleting him?
    	
    	//deleting customer
    	//$url = 'api/exposeObj/person/delete/';
    	$input = array('uid' => $client_id);
    	$rest_return = $this->rest->post('delete', $input, 'serialize'); //TODO this should be $this->rest->delete
		return $rest_return['0'];  //true or false
		   	
    	//TODO delete also invoices esteems and whatever
    	
//         $this->load->model('invoices/mdl_invoices');

//         /* Delete the client record */

//         parent::delete(array('client_id'=>$client_id));

//         /* Delete any related contacts */

//         $this->db->where('client_id', $client_id);

//         $this->db->delete('mcb_contacts');

//         /*
// 		 * Delete any related invoices, but use the invoice model so records
// 		 * related to the invoice are also deleted
//         */

//         $this->db->select('invoice_id');

//         $this->db->where('client_id', $client_id);

//         $invoices = $this->db->get('mcb_invoices')->result();

//         foreach ($invoices as $invoice) {

//             $this->mdl_invoices->delete($invoice->invoice_id);

//         }

    }

    public function save() {
		
    	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/person/'));
    	
        $db_array = parent::db_array();            
        
        //TODO I don't like this hardcoded stuff
        $data = array();
        $data['sn'] = $db_array['client_name'];
        $data['givenName'] = 'giveName';
        $data['homePostalAddress'] = $db_array['client_address'];
        $data['mozillaHomeLocalityName'] = $db_array['client_city'];
        $data['mozillaHomeState'] = $db_array['client_state'];
        $data['mozillaHomeCountryName'] = $db_array['client_country'];
        $data['mozillaHomePostalCode'] = $db_array['client_zip'];
        $data['companyPhone'] = $db_array['client_phone_number'];
        $data['facsimileTelephoneNumber'] = $db_array['client_fax_number'];
        $data['client_mobile_number'] = $db_array['client_mobile_number'];
        $data['mail'] = $db_array['client_email_address'];
        $data['labeledURI'] = $db_array['client_web_address'];
        $data['note'] = $db_array['client_notes'];
        $data['client_tax_id'] = $db_array['client_tax_id'];
        
        //mandatory for ldap
        $data['entryCreatedBy'] = 'dam';
        $data['category'] = 'mycategory';
        $data['cn'] = $data['sn'].' '.$data['givenName'];
        $data['displayName'] = $data['cn'];
        $data['fileAs'] = $data['cn'];        
        //uid is automatically set, so not needed
        //$data['uid'] = rand(10000000,99999999);
        $data['userPassword'] = 'mypassword';
        $this->input->post('client_active') ? $data['enabled'] = 'TRUE' : $data['enabled'] = 'FALSE';
        
        $rest_return = $this->rest->post('create', $data, 'serialize'); 
        
        //parent::save($db_array, uri_assoc('client_id'));

    }

}

?>