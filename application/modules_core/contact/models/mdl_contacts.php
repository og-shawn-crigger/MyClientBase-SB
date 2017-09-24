<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * This class, originally from MCB and written by Jesse, has been modified to hook Contact Engine without breaking
 * MCB standard functionalities. It uses the methods offered by the Mdl_Contact class and its extensions
 *  
 * Modified by Damiano Venturin @ squadrainformatica.com
 */

class Mdl_Contacts extends MY_Model {

    public function __construct() {

        parent::__construct();
       
        $this->load->model('contact/mdl_contact','contact');
        
        $this->load->model('contact/mdl_person','person');
        
        $this->load->model('contact/mdl_organization','organization');

        $this->load->model('contact/mdl_location','location');        
     
    }
    
    public function get(array $params, $by_location = false) {
    	
    	if(!is_array($params)) return false;
    	
    	//I demand to search something before making a request to contact engine (it doesn't make sense to get the whole customer list)
    	//otherwise I require an uid or an oid
    	if(empty($params['search']) && empty($params['uid']) && empty($params['oid']) && empty($params['client_id'])) return false;
    	
    	$input=array();
    	
    	if(empty($params['uid']) && empty($params['oid']) && $by_location==false)
    	{
    		if(empty($params['client_id']))
    		{
	    		//looking for all contacts
    			$fields = array('cn','o','mail','omail','mobile','oMobile','homePhone','telephoneNumber','labeledURI','oURL');
    			$input['filter'] = '';
    			$subsearches = preg_split('/ /', $params['search']);
    			$count_subsearches = count($subsearches);
    			foreach ($fields as $field){
    				if( $count_subsearches == 2 && $field == 'cn'){
    					//searches for Mario Rossi and Rossi Mario
    					$input['filter'] .= '('.$field.'=*'.$subsearches[0].' '.$subsearches[1].'*)';
    					$input['filter'] .= '('.$field.'=*'.$subsearches[1].' '.$subsearches[0].'*)';
    				} else {
    					$input['filter'] .= '('.$field.'=*'.$params['search'].'*)';
    				}
    				
    			}
    			$input['filter'] = '(|'.$input['filter'].')';
    		} else {
    			//looking for a single contact but I don't know if it's an organization or a person
    			$input['filter'] = '(|(uid='.$params['client_id'].')(oid='.$params['client_id'].'))';
    		}
    	} else {
    		//looking for a specific contact
    		if(!empty($params['uid'])) $input['filter'] = '(uid='.$params['uid'].')';
    		
    		if(!empty($params['oid'])) $input['filter'] = '(oid='.$params['oid'].')';
    	}
    	
    	if($by_location) {
    		$input['filter'] = '';
    		
    		if(is_array($params['search']) && !empty($params['search']['city'])) {
    			$filter = '';
    			$fields = array('mozillaHomeLocalityName','l');
    			foreach ($fields as $field){
    				$filter .= '('.$field.'=*'.$params['search']['city'].'*)';
    			}
    			$filter1 = '(|'.$filter.')';
    		}
    		
    		if(is_array($params['search']) && !empty($params['search']['state'])) {
    			$filter = '';
    			$fields = array('mozillaHomeState','st');
    			foreach ($fields as $field){
    				$filter .= '('.$field.'=*'.$params['search']['state'].'*)';
    			}
    			$filter2 = '(|'.$filter.')';
    		}
    		
    		if(isset($filter1) && isset($filter2)) {
    			$input['filter'] = '(&'.$filter1.$filter2.')';
    		} else {
    			if(isset($filter1)) $input['filter']  = $filter1;
    			if(isset($filter2)) $input['filter']  = $filter2;
    		}
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
    	if(isset($rest_info['results_number'])) {
    		$tmp = $rest_info['results_pages'] * $input['items_page'];
    		if($rest_info['results_number'] > ($tmp))
    		{
    			//this means that the result has been paginated by the object Contact and contains both people and organizations,
    			//so the total number of items to take in consideration is lower than the value return
    			$params['total_rows'] = $tmp;
    		} else {
    			$params['total_rows'] = $rest_info['results_number'];
    		}
    	} else {
    		$params['total_rows'] = 0;
    	}
    		
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
    		$output = array('people' => $people, 'orgs' => $orgs, 'total_number' => $rest_info['results_number']);
    		return $output;
    	} else {

    		if(isset($params['active']) && $params['active'])
    		{
    			//this is the return for for invoices, quotes ...
    			if(count($people)>0) return $people;
    			if(count($orgs)>0) return $orgs;
    		} else {
	 			//this is the return for contact details, contact form
	    		if(count($people)>0) return $people['0'];
	    		if(count($orgs)>0) return $orgs['0'];
    		}
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
         		$params['active'] = true;
         		return $this->get($params);
         	}
         	
        }
        
        $params['active'] = true;
        return $this->get($params);

    }

    public function validate() {

        return parent::validate($this);
    }

    public function delete($client_id) {

    	$this->rest->initialize(array('server' => $this->config->item('rest_server').'/exposeObj/contact/'));
    	
    	//TODO let's delete just the customer for now, but I don't think it's a great idea
    	
    	$input = array('uid' => $client_id);
    	
    	$this->crr->importCeReturnObject($this->rest->post('delete', $input, 'serialize'));    	

    	return $this->crr->has_no_errors;
    	
//     	$rest_return = $this->rest->post('delete', $input, 'serialize'); //TODO this should be $this->rest->delete
// 		return $rest_return['0'];  //true or false
		   	
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

    public function save($obj = null) {
		
    	//this is here just for retro-compatibility
    	$obj->save();
    	return ;

    }
    
    //overrides _prep_pagination function
    private function _prep_pagination($params) {
    
    	if (isset($params['paginate']) AND $params['paginate'] == TRUE) {
    
    		$this->load->library('pagination');
    		
    		empty($params['page']) ? $page = uri_assoc('page') : $page = $params['page'];
    		
    		$config = array(
        		   					'base_url'			=>	site_url('/contact/index/page'),
        		   					'total_rows'		=>	$params['total_rows'],
        		   					'per_page'			=>	$params['items_page'],
        		   					'next_link'			=>	$this->lang->line('next') . ' >',
        		   					'prev_link'			=>	'< ' . $this->lang->line('prev'),
        		   					'cur_tag_open'		=>	'<span class="active_link">',
        		   					'cur_tag_close'		=>	'</span>',
        		   					'num_links'			=>	3,
        		   					'cur_page'			=>  $page, //$this->offset;
    		);
    		
    		$this->pagination->initialize($config);
    		$this->page_links = $this->pagination->create_links();
    		$params['items_page'] > 0 ? $this->current_page = ($page / $params['items_page']) + 1 : $this->current_page = 0;
    		$params['items_page'] > 0 ? $this->num_pages = ceil($params['total_rows'] / $params['items_page']) : $this->num_pages = 0;
    	}
    }    

}

?>