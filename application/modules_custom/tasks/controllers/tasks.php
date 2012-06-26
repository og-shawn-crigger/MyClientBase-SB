<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Tasks extends Admin_Controller {
	
	function __construct() {

		parent::__construct();

		if (!$this->mdl_mcb_modules->check_enable('tasks')) {
			redirect('contact');
		}

		$this->load->helper('date');
		
		$this->load->model('mdl_tasks');

	}

	/**
	 * Retrieves all the tasks and shows them in a table
	 * 
	 * @access		public
	 * @param		none		
	 * @var			
	 * @return		none
	 * @example
	 * @see
	 * 
	 * @author 		Damiano Venturin
	 * @copyright 	2V S.r.l.
	 * @license	GPL
	 * @link		http://www.squadrainformatica.com/en/development#mcbsb  MCB-SB official page
	 * @since		Jun 24, 2012
	 * 
	 */
	function index() {
		
		$this->_post_handler();
		
		$this->redir->set_last_index();

		$params = array(
			'limit'		=>	20,
			'paginate'	=>	TRUE,
			'page'		=>	uri_assoc('page', 3)
		);

		$data = array(
			'tasks'					=>	$this->mdl_tasks->getAll(), 
			'show_task_selector'	=>	TRUE
		);

		$data['site_url'] = site_url($this->uri->uri_string());
		$data['table'] = $this->plenty_parser->parse('table.tpl', $data, true, 'smarty', 'tasks');
		$data['actions_panel'] = $this->plenty_parser->parse('actions_panel.tpl', $data, true, 'smarty', 'tasks');				
		$this->load->view('index', $data);

	}

	//TODO rename in EDIT
	function form() {

		$this->redir->set_last_index();

		$data = array();
		
		$this->_post_handler();

		if ($_POST) { 	//it's an update or a new task
				
			//is it a validated submit? (i.e. are all the rules respected?)
			if ($this->mdl_tasks->validate()) {
				
				if(empty($this->mcbsb->task->task_id)) {
					
					//it's a new task
					if($this->mcbsb->task->create()) {
						$this->mcbsb->system_messages->success = 'Task has been successfully created';
					} else {
						$this->mcbsb->system_messages->error = 'Task has not been created';
					}
				
				} else {
				
					//it's an update
					if($this->mcbsb->task->update()) {
						$this->mcbsb->system_messages->success = 'The task has been successfully updated';
					} else {
						$this->mcbsb->system_messages->error = 'The task has not been updated';
					}
				}
				
				redirect('tasks');
				
			} else {
				$this->mcbsb->system_messages->error = 'The task has been successfully created';
			}			
		}
		
		if($task_id = uri_assoc('task_id', 3)) {
			
			//it's an old task to be edit
			
			$this->mcbsb->task->task_id = $task_id;
			if($this->mdl_tasks->get()) { //this populates $this->mcbsb->task with contact information 
			
				//task and contact have been found.				

				$data['task'] = $this->mcbsb->task;
												
			} else {
				//contact not found. That's a huge error
				//TODO system message
				//TODO redirect
			}
		} else {
						
			//it's a new task
			
			$values = retrieve_uid_oid();
			if(isset($values['client_id'])) $this->mcbsb->task->client_id = $values['client_id'];
			if(isset($values['client_id_key'])) $this->mcbsb->task->client_id_key = $values['client_id_key'];

			if(! $this->mdl_tasks->retrieve_contact_name($this->mcbsb->task)) {
				//TODO do something
			}
			
			$this->mcbsb->task->start_date = now();
			$data['task'] = $this->mcbsb->task;
		}
		
		$data['site_url'] = site_url($this->uri->uri_string());
		$data['actions_panel'] = $this->plenty_parser->parse('actions_panel.tpl', $data, true, 'smarty', 'tasks');
		$this->load->view('form', $data);
	}

	function create_invoice() {
		
		$this->redir->set_last_index();
		
		if($task_id = uri_assoc('task_id', 3)) {
			$this->mcbsb->task->task_id = $task_id;
			if($this->mdl_tasks->get()) { //this populates $this->mcbsb->task with contact information
					
				//task and contact have been found.
		
				$this->load->model('invoices/mdl_invoices');
				
				if(!$this->mcbsb->task->complete_date) {
					//TODO system message
					redirect($this->session->userdata('last_index'));
				}
				$invoice_items = array();
				$invoice_items[] = array(
						'item_name'			=>	$this->mcbsb->task->title,
						'item_description'	=>	$this->mcbsb->task->description,
						'item_qty'			=>	1,
						'item_price'		=>	0
				);

				$package = array(
						'client_id'				=>	$this->mcbsb->task->client_id,
						'client_id_key'			=>	$this->mcbsb->task->client_id_key,
						'invoice_date_entered'	=>	$this->mcbsb->task->complete_date,
						'invoice_is_quote'		=>	0,
						'invoice_group_id'		=>	1,//$this->input->post('invoice_group_id'),
						'invoice_items'			=>	$invoice_items
				);
				
				if($invoice_id = $this->mdl_invoices->create_invoice($package)) {
				
					$this->mdl_tasks->save_invoice_relation($invoice_id, $task_id);
					
					redirect('invoices/edit/invoice_id/' . $invoice_id);
				} else {
					//TODO system message
					redirect('tasks');
				}
								
			} else {
				//contact not found. That's a huge error
				//TODO system message
				redirect('tasks');
			}
				
		} else {
			//no task_id in the url
			//TODO system message
			redirect('tasks');
		}
		
	}
	

	function delete() {

		if ($task_id = uri_assoc('task_id', 3)) {

			$this->mcbsb->task->task_id = $task_id;
			if($this->mdl_tasks->get()) {
				
				//TODO if is not invoiced ...
				
				if($this->mcbsb->task->delete()) {
					//TODO system message
				} else {
					//TODO system message
				}
			}
			//$this->mdl_tasks->delete(array('task_id'=>uri_assoc('task_id', 3)));
		} else {
			//TODO system message
		}

		$this->redir->redirect('tasks');

	}

	
	function save_settings() {

		if ($this->input->post('dashboard_show_open_tasks')) {

			$this->mdl_mcb_data->save('dashboard_show_open_tasks', "TRUE");

		}

		else {

			$this->mdl_mcb_data->save('dashboard_show_open_tasks', "FALSE");

		}

	}

	
	function dashboard_widget() {

		if ($this->mdl_mcb_data->setting('dashboard_show_open_tasks') == "TRUE") {

			$params = array(
				'limit'	=>	10,
				'where'	=>	array(
					'complete_date'=>''
				)
			);

			$data = array(
				'tasks'	=>	$this->mdl_tasks->get($params)
			);

			$this->load->view('dashboard_widget', $data);

		}

	}

	
	function _post_handler() {
		
		if ($this->input->post('btn_cancel')) {

			redirect('tasks/index');

		}
	}

}

?>