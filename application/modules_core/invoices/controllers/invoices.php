<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Invoices extends Admin_Controller {

	function __construct() {

		parent::__construct();

		$this->load->model('contact/mdl_contacts');
		
		$this->load->model('mdl_invoices');
	}

	function index() {

		$this->_post_handler();

		$this->redir->set_last_index();

		//parse the url
		$order_by = uri_assoc('order_by');
		$order = uri_assoc('order');
		$status = uri_assoc('status');
		$is_quote = uri_assoc('is_quote');
				
		$params = array(
				'paginate'				=>	TRUE,
				'limit'					=>	$this->mdl_mcb_data->setting('results_per_page'),
				'page'					=>	uri_assoc('page'),
				'where'					=>	array(),
		);
		
		//contains the info sent to the view
		$data = array();
		
		//gets the contact if specified in the url
		if($contact = $this->retrieve_contact()) $client_id = $contact->client_id;

		$params['where']['mcb_invoices.invoice_is_quote'] = ($is_quote) ? 1 : 0;
		
		if (isset($client_id)) $params['where']['mcb_invoices.client_id'] = $client_id;

		if ($status) $params['where']['invoice_status'] = $status;

		switch ($order_by) {
			case 'invoice_id':
				$params['order_by'] = 'mcb_invoices.invoice_number ' . $order;
			break;
			
			case 'amount':
				$params['order_by'] = 'invoice_total ' . $order;
			break;
			
			case 'duedate':
				$params['order_by'] = 'mcb_invoices.invoice_due_date ' . $order;
			break;
			
			case 'date':
				$params['order_by'] = 'mcb_invoices.invoice_date_entered ' . $order;
			break;
			
			default:
				$params['order_by'] = 'mcb_invoices.invoice_date_entered DESC, mcb_invoices.invoice_number DESC';
		}

		$invoices = $this->mdl_invoices->get($params);
		
		$data['invoices'] =	$invoices;
		$data['sort_links']	= TRUE;
		$data['site_url'] = site_url($this->uri->uri_string());
		$data['actions_panel'] = $this->plenty_parser->parse('actions_panel.tpl', $data, true, 'smarty', 'invoices');		

		$this->load->view('index', $data);
	}

	public function retrieve_contact()
	{
		$params = retrieve_uid_oid();
		 
		//check: I need at least one of the 3 parameters uid,oid,client_id
		if(is_null($params) || count($params)==0) return false;
		 
		return $this->mdl_invoices->get_contact($params);
	}	
	

	
	public function create() {

		if ($this->input->post('btn_cancel')) {
			if ($this->input->post('btn_cancel')) {
				 
				//see if we where creating a quote or not for the proper redirect
				//             if ( strstr(uri_string(), "quote") )
				//                 redirect('invoices/index/is_quote/1');
				//             else
				//               redirect('invoices');
				//             }
				if ( strstr(uri_string(), "quote")) {
					if(strtolower($contact->objName) == "person") {
						redirect('invoices/index/uid/'.$contact->client_id.'/is_quote/1');
					} else {
						redirect('invoices/index/oid/'.$contact->client_id.'/is_quote/1');
					}
				}
			}
		}
		
		if($contact = $this->retrieve_contact())
		{
			$client_id = $contact->client_id;
		} else {
			redirect($this->session->userdata('last_index'));
			//TODO display an error
		}
		
		if(strtolower($contact->objName) == "person")
		{
			$this->mdl_invoices->set_form_value('client_id_autocomplete_label', $contact->cn);
			$client_id_key = 'uid';
		}
		
		if(strtolower($contact->objName) == "organization")
		{
			$this->mdl_invoices->set_form_value('client_id_autocomplete_label', $contact->o);
			$client_id_key = 'oid';
		}
		$this->mdl_invoices->set_form_value('client_id_key', $client_id_key);

		
		if (!$this->mdl_invoices->validate_create()) { //it means that the form has not been submitted or it's not a valid submit
			
			$this->load->model('mdl_invoice_groups');
			
			//should I create an invoice or quote form?
			if ( strstr(uri_string(), "quote")) $is_quote = true;
				
			/* If client_id exists in URL, pre-select the client */
			if ($client_id and !$_POST) {

				$this->mdl_invoices->set_form_value('client_id', $client_id);
			}

			$data = array(
				'invoice_groups'	=>	$this->mdl_invoice_groups->get()
			);

			$data['site_url'] = site_url($this->uri->uri_string());
			$data['actions_panel'] = $this->plenty_parser->parse('actions_panel.tpl', $data, true, 'smarty', 'invoices');
			
			$this->load->view('choose_client', $data);

		}

		else {

			if (!$client_id) {
				
				$db_array = array(
					'client_name'	=>	$this->input->post('client_id_autocomplete_label'),
					'client_active'	=>	1
				);

				$this->mdl_clients->save($db_array);
				
				$client_id = $this->db->insert_id();

			}

			$package = array(
				'client_id'				=>	$client_id,
				'client_id_key'			=>  $client_id_key,
				'invoice_date_entered'	=>	$this->input->post('invoice_date_entered'),
				'invoice_group_id'		=>	$this->input->post('invoice_group_id'),
				'invoice_is_quote'		=>	$this->input->post('invoice_is_quote')
			);

			$invoice_id = $this->mdl_invoices->create_invoice($package);

			redirect('invoices/edit/invoice_id/' . $invoice_id);

		}

	}

	function delete() {

		$invoice_id = uri_assoc('invoice_id');

		if ($invoice_id) {

			$this->mdl_invoices->delete($invoice_id);

		}

		redirect($this->session->userdata('last_index'));

	}


	
	function edit() {

		$tab_index = ($this->session->flashdata('tab_index')) ? $this->session->flashdata('tab_index') : 0;

		$this->_post_handler();

		$this->redir->set_last_index();

		$this->load->model(
			array(
			//'clients/mdl_clients',
			'payments/mdl_payments',
			'tax_rates/mdl_tax_rates',
			'invoice_statuses/mdl_invoice_statuses',
			'templates/mdl_templates',
			'users/mdl_users'
			)
		);

		$params = array(
			'where'	=>	array(
				'mcb_invoices.invoice_id'	=>	uri_assoc('invoice_id')
			)
		);

//TODO remove this ACL
//         if (!$this->session->userdata('global_admin')) {
//             $params['where']['mcb_invoices.user_id'] = $this->session->userdata('user_id');
//         }

		
		if (!$invoice = $this->mdl_invoices->get($params)) {
			$this->mcbsb->system_messages->error = 'Invoice not found';
			redirect('invoices');
		}

		$user_params = array(
			'where' =>  array(
				'mcb_users.user_client_id'   =>  0
			)
		);

        if (!$this->session->userdata('global_admin')) {

            $user_params['where']['user_id'] = $this->session->userdata('user_id');

        }

		$data = array(
			'invoice'			=>	$invoice,
			'payments'          =>  $this->mdl_invoices->get_invoice_payments($invoice->invoice_id),
			'history'           =>  $this->mdl_invoices->get_invoice_history($invoice->invoice_id),
			'invoice_items'     =>  $this->mdl_invoices->get_invoice_items($invoice->invoice_id),
			'invoice_tax_rates' =>  $this->mdl_invoices->get_invoice_tax_rates($invoice->invoice_id),
			'tags'              =>  $this->mdl_invoices->get_invoice_tags($invoice->invoice_id),
			'tax_rates'			=>	$this->mdl_tax_rates->get(),
			'invoice_statuses'	=>	$this->mdl_invoice_statuses->get(),
			'tab_index'			=>	$tab_index,
			'custom_fields'		=>	$this->mdl_fields->get_object_fields(1),
			'users'             =>  $this->mdl_users->get($user_params)
		);

		$data['site_url'] = site_url($this->uri->uri_string());
		$data['actions_panel'] = $this->plenty_parser->parse('actions_panel.tpl', $data, true, 'smarty', 'invoices');
				
		$this->load->view('invoice_edit', $data);

	}

	function copy() {

		$new_invoice_id = $this->mdl_invoices->copy(uri_assoc('invoice_id'));

		redirect('invoices/edit/invoice_id/' . $new_invoice_id);

	}

	function generate_pdf() {
		
		$this->load->library('lib_output');
		$this->load->model('invoices/mdl_invoice_history');
		
		$invoice_id = uri_assoc('invoice_id');
		$invoice = $this->mdl_invoices->get_by_id($invoice_id);		
		
		if($invoice->invoice_is_quote) {
			$this->mdl_invoice_history->save($invoice_id, $this->session->userdata('user_id'), $this->lang->line('generated_quote_pdf'));
		} else {
			$this->mdl_invoice_history->save($invoice_id, $this->session->userdata('user_id'), $this->lang->line('generated_invoice_pdf'));
		}
		
		$this->lib_output->pdf($invoice_id, uri_assoc('invoice_template'));

	}

	function generate_html() {

		$this->load->library('invoices/lib_output');

		$this->load->model('invoices/mdl_invoice_history');

		$invoice_id = uri_assoc('invoice_id');
		$invoice = $this->mdl_invoices->get_by_id($invoice_id);
		
		if($invoice->invoice_is_quote) {
			$this->mdl_invoice_history->save($invoice_id, $this->session->userdata('user_id'), $this->lang->line('generated_quote_html'));
		} else {
			$this->mdl_invoice_history->save($invoice_id, $this->session->userdata('user_id'), $this->lang->line('generated_invoice_html'));
		}

		$this->lib_output->html($invoice_id, uri_assoc('invoice_template'));

	}

	function recalculate() {

		$this->load->model('mdl_invoice_amounts');

		$this->mdl_invoice_amounts->adjust();

		redirect('settings');

	}

	function quote_to_invoice() {

		$this->_post_handler();

		if(!$invoice_id = uri_assoc('invoice_id')) {
			redirect('invoices/index/is_quote/1');
		}

		if (!$this->mdl_invoices->validate_quote_to_invoice()) {

			$this->load->model('mdl_invoice_groups');
			
			$params = array('where' => array('mcb_invoices.invoice_id' => $invoice_id));
			
			$invoice = $this->mdl_invoices->get($params);
			
			$client_id = $invoice->client_id;
			
			$data = array(
				'invoice_groups'	=>	$this->mdl_invoice_groups->get(),
				'invoice'			=>  $invoice
			);

			$data['site_url'] = site_url($this->uri->uri_string());
			$data['actions_panel'] = $this->plenty_parser->parse('actions_panel.tpl', $data, true, 'smarty', 'invoices');
				
			$this->load->view('quote_to_invoice', $data);

		}

		else {

			$this->mdl_invoices->quote_to_invoice($invoice_id, $this->input->post('invoice_date_entered'), $this->input->post('invoice_group_id'));

			redirect('invoices/edit/invoice_id/' . $invoice_id);

		}

	}

	function _post_handler() {

		if ($this->input->post('btn_add_new_item') || $this->input->get('btn_add_new_item')) {

			redirect('invoices/items/form/invoice_id/' . uri_assoc('invoice_id'));

		}

		elseif ($this->input->post('btn_add_payment') || $this->input->get('btn_add_payment')) {

			redirect('payments/form/invoice_id/' . uri_assoc('invoice_id'));

		}

		elseif ($this->input->post('btn_copy_invoice') || $this->input->get('btn_copy_invoice') ) {

			redirect('invoices/copy/invoice_id/' . uri_assoc('invoice_id'));

		}

		elseif ($this->input->post('btn_add_invoice') || $this->input->get('btn_add_invoice')) {

			redirect('invoices/create');

		}

		elseif ($this->input->post('btn_add_quote') || $this->input->get('btn_add_quote')) {            
			    redirect('invoices/create/quote');

		}

		elseif ($this->input->post('btn_cancel') || $this->input->get('btn_cancel')) {
            
			    redirect('invoices/index');

		}

		elseif ($this->input->post('btn_submit_options_general') or $this->input->post('btn_submit_options_tax') or $this->input->post('btn_submit_notes')) {

			if ($this->input->post('btn_submit_options_general')) {

				$this->session->set_flashdata('tab_index', 0);

			}

			elseif ($this->input->post('btn_submit_options_tax')) {

				$this->session->set_flashdata('tab_index', 3);

			}

			elseif ($this->input->post('btn_submit_notes')) {

				$this->session->set_flashdata('tab_index', 4);

			}

			if ($this->mdl_invoices->validate()) {

				$this->mdl_invoices->save_invoice_options($this->mdl_fields->get_object_fields(1));

				$this->load->model('mdl_invoice_amounts');

				$this->mdl_invoice_amounts->adjust(uri_assoc('invoice_id'));

				redirect('invoices/edit/invoice_id/' . uri_assoc('invoice_id'));

			}

		}

		elseif ($this->input->post('btn_quote_to_invoice') || $this->input->get('btn_quote_to_invoice') ) {

			redirect('invoices/quote_to_invoice/invoice_id/' . uri_assoc('invoice_id'));

		}

		elseif ($this->input->post('btn_calendar_view') || $this->input->get('btn_calendar_view')) {

			redirect('calendar');

		}

	}

	function jquery_client_invoice_template($client_id, $quote = 0) {

		$this->load->model('mcb_data/mdl_mcb_client_data');

		$type = (!$quote) ? 'invoice' : 'quote';

		$default_template = $this->mdl_mcb_client_data->get($client_id, 'default_' . $type . '_template');

		if (!$default_template) {

			$default_template = $this->mdl_mcb_data->setting('default_' . $type . '_template');

		}

		echo $default_template;

	}

	function jquery_client_invoice_group($client_id, $type = 'invoice') {

		$this->load->model('mcb_data/mdl_mcb_client_data');

		$default_group_id = $this->mdl_mcb_client_data->get($client_id, 'default_' . $type . '_group_id');

		if (!$default_group_id) {

			$default_group_id = $this->mdl_mcb_data->setting('default_' . $type . '_group_id');

		}

		echo $default_group_id;

	}

	/**
	 * Displays the create invoice form from other modules
	 */
	function display_create_invoice() {

		//$this->load->model('clients/mdl_clients');

		$this->load->model('invoices/mdl_invoice_groups');

		$data = array(
			'invoice_groups'	=>	$this->mdl_invoice_groups->get()
		);

		//$this->load->view('invoices/choose_client', $data);

	}

	function display_invoice_table($invoices, $quotes = FALSE) {

		$this->load->model('invoices/mdl_invoice_table');

		$data = array(
			'invoices'			=>	$invoices,
			'quotes'			=>	$quotes,
			'table_headers'		=>	$this->mdl_invoice_table->get_table_headers()
		);
		
		$this->load->view('invoices/invoice_table', $data);

	}

}

?>