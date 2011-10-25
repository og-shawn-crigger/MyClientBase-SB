<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//modified by Damiano Venturin @ squadrainformatica.com

class Mdl_Clients extends MY_Model {

    public function __construct() {

        parent::__construct();
       
        $this->load->model('clients/mdl_contact','contact');
        
        $this->load->model('clients/mdl_person','person');
		
		$this->load->model('clients/mdl_organization','organization');	
    }
    
    public function get(array $params) {
    	
    	if(!is_array($params)) return false;
    	
    	//I demand to search something before making a request to contact engine (it doesn't make sense to get the whole customer list)
    	//otherwise I require an uid or an oid
    	if(empty($params['search']) && empty($params['uid']) && empty($params['oid']) && empty($params['client_id'])) return false;
    	
    	$input=array();
    	
    	if(empty($params['uid']) && empty($params['oid']))
    	{
    		if(empty($params['client_id']))
    		{
	    		//looking for all contacts
	    		$input['filter'] = '(|(cn=*'.$params['search'].'*)(o=*'.$params['search'].'*))';
    		} else {
    			//looking for a single contact but I don't know if it's an organization or a person
    			$input['filter'] = '(|(uid='.$params['client_id'].')(oid='.$params['client_id'].'))'; //TODO why the $client_id is not extracted?
    		}
    	} else {
    		//looking for a specific contact
    		if(!empty($params['uid'])) 
    		{
    			$input['filter'] = '(uid='.$params['uid'].')';
    		}
    		if(!empty($params['oid'])) $input['filter'] = '(oid='.$params['oid'].')';
    	}
    	
    	//defaults
    	isset($params['method']) ? $input['method'] = $params['method'] : $input['method'] = 'POST';
    	isset($params['sort_by']) ? $input['sort_by'] = $params['sort_by'] : $input['sort_by'] = array('sn');
    	isset($params['flow_order']) ? $input['flow_order'] = $params['flow_order'] : $input['flow_order'] = 'asc';
    	isset($params['wanted_page']) ? $input['wanted_page'] = $params['wanted_page'] : $input['wanted_page'] = '0';
    	isset($params['items_page']) ? $input['items_page'] = $params['items_page'] :$input['items_page'] = '12';

		$rest_return = $this->contact->get($input);
    	
    	//retrieving query info  
    	$rest_info = $rest_return['status'];										
    	
    	//pagination
    	isset($rest_info['results_number']) ? $params['total_rows'] = $rest_info['results_number'] : $params['total_rows'] = 0; 
    	$this->_prep_pagination($params);
    	
    	//prepare return for output
    	$people = array();
    	$orgs = array();
    	$num = count($rest_return['data']);
    	
    	//TODO what happens when I get 0 contacts? or went I get an error?
    	if (is_array($rest_return['data'])) {
	    	foreach ($rest_return['data'] as $item => $contact) {
	    		//$objectClass = explode(',',$this->getReturnValue('objectClass', $contact));
	    		if(in_array('dueviPerson', $contact['objectClass']))
	    		{
	    			//It's a person
					if($this->person->arrayToObject($contact)) $people[] = clone $this->person;	    			
	    		}
	    		
	    		if(in_array('dueviOrganization', $contact['objectClass']))
	    		{
	    			if($this->organization->arrayToObject($contact)) $orgs[] = clone $this->organization;
	    		}
	    	}    	
    	}
    	
    	if(empty($params['client_id']) && empty($params['uid']) && empty($params['oid']))
    	{
    		//this is the return for the contact search form
    		$output = array('people' => $people, 'orgs' => $orgs);
    		return $output;
    	} else {
 			//this is the return for invoices, quotes, whatever
    		if(count($people)>0) return $people['0'];
    		if(count($orgs)>0) return $orgs['0'];
    	}
    }

    public function get_active($params = NULL) {

         if (!$params) {
			
         	$params = array();
			
         	$segs = $this->uri->segment_array();
         	
         	foreach ($segs as $key => $item)
         	{
         		$segmnent = $key;
         		switch ($item) {
         			case 'uid':
         				$var = 'uid';
         			break;

         			case 'oid':
         				$var = 'oid';
         			break;

         			case 'client_id':
         				$var = 'client_id';
         			break;         				         			
         		}         		
         	}

         	if($var) 
         	{
         		$params[$var] = $this->uri->segment($segmnent);
         		return $this->get($params);
         	}
         	
        }
        
        return $this->get($params);

    }

    public function validate() {

    	//TODO some work needed here!
        //validation rules for person
  		$this->form_validation->set_rules('sn', $this->lang->line('sn'), 'required');
        $this->form_validation->set_rules('givenName', $this->lang->line('givenName'), 'required');
        
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
	
    	//TODO this function is ok for the UPDATE but not for the CREATE yet
        $data = array();
        
        if(isset($this->form_values['uid'])) $obj = 'person';
        
        if(isset($this->form_values['oid'])) $obj = 'organization';

        $this->contact->getProperties($obj);
        $properties = $this->contact->properties;
        
        foreach ($this->form_values as $property => $value) {
        	if(in_array($property, array_keys($properties)))
        	{
        		$data[$property] = $value;
        	}
        }
        
        //mandatory fields for ldap
        if(!isset($data['entryCreatedBy'])) $data['entryCreatedBy'] = 'mcb-sm';  //TODO put here the MCB user ID
        if(!isset($data['entryUpdatedBy'])) $data['entryCreatedBy'] = 'mcb-sm';  //TODO put here the MCB user ID
        if(!isset($data['category'])) $data['category'] = 'mycategory';
        $data['cn'] = $data['sn'].' '.$data['givenName'];
        $data['displayName'] = $data['givenName'].' '.$data['sn'];
        $data['fileAs'] = $data['cn'];        
        
        $data['userPassword'] = 'mypassword'; //TODO is this field mandatory?
        ($this->form_values->enabled == 'TRUE') ? $data['enabled'] = 'TRUE' : $data['enabled'] = 'FALSE';
        
        
        if(isset($this->form_values['uid']))
        {
        	$this->person->update($data);
        }
        
        if(isset($this->form_values['uid']))
        {
        	$this->organization->update($data);
        }
    }
    
    
    //overrides _prep_pagination function
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

}

?>