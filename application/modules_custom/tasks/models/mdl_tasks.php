<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Tasks extends MY_Model {

	public $task_id;
	
	public function __construct() {

		parent::__construct();

		$this->table_name = 'mcb_tasks';

		$this->primary_key = 'mcb_tasks.task_id';

		$this->select_fields = "
		SQL_CALC_FOUND_ROWS mcb_tasks.*,
		mcb_users.last_name AS user_last_name,
		mcb_users.first_name AS user_first_name";

		$this->order_by = 'mcb_tasks.due_date, mcb_tasks.task_id DESC';

		$this->joins = array(
			'mcb_users'		=>	'mcb_users.user_id = mcb_tasks.user_id'
		);

		//loads the Task obj into the mcbsb obj
		$this->mcbsb->load('task');

		//TODO add here the check to see if the contact module is enabled
		
		//loads the Contact obj into the mcbsb obj
		$this->mcbsb->load('contact/mdl_contact','contact');
	}

	public function getAll($skip_contact_info = false) {
		
		//TODO add limit, order, pagination and filter
		if($tasks = $this->getTasks()) {
			
			if(!$skip_contact_info){

				foreach ($tasks as $task) {
					$this->retrieve_contact_name($task);
				}
			}
			
			return $tasks;		
		}
		
		return false;
	}

	public function getAllOpen($skip_contact_info = false) {
	
		$params = array('where' => array('complete_date' => ''));
		if($tasks = $this->getTasks($params)) {
				
			if(!$skip_contact_info){
	
				foreach ($tasks as $task) {
					$this->retrieve_contact_name($task);
				}
			}
				
			return $tasks;
		}
	
		return false;
	}	
	
	
	private function getTasks(array $params = null ) {
	
		if($tasks = $this->mcbsb->task->readAll($params)) return $tasks;
	
		return false;
	}
	
	/**
	 * Retrieves the contact info and sets the task attribute "client_name" with the appropriate contact attribute
	 * ("cn" in case of a person, "o" in case of an organization  
	 * 
	 * @access		public
	 * @param		$task Task object
	 * @var			
	 * @return		boolean
	 * @example
	 * @see
	 * 
	 * @author 		Damiano Venturin
	 * @copyright 	2V S.r.l.
	 * @license	GPL
	 * @link		http://www.mcbsb.com
	 * @since		Jun 24, 2012
	 * 
	 */
	public function retrieve_contact_name(Task $task){
		
		if($task->client_id_key == 'uid') {
			$this->mcbsb->load('contact/mdl_person','person');
			$this->mcbsb->person->uid = $task->client_id;
			if($this->mcbsb->person->get(null,false)) {
				$task->client_name = $this->mcbsb->person->cn;
				return true;
			}
		}
		
		if($task->client_id_key == 'oid') {
			$this->mcbsb->load('contact/mdl_organization','organization');
			$this->mcbsb->organization->oid = $task->client_id;
			if($this->mcbsb->organization->get(null,false)) {
				$task->client_name = $this->mcbsb->organization->o;
				return true;
			}
		}
		
		return false;
	}
	
	public function retrieve_contact(array $input){
		
		return 
		
		$this->load->model('contact/mdl_contacts','contacts');
		
		if($client = $this->contacts->get($input)) {
			
			if(strtolower($client->objName) == 'uid') {
				$client->client_name = $client->cn;
				$client->client_id_key = 'uid';
			}
			
			if(strtolower($client->objName) == 'oid') {
				$client->client_name = $client->o;
				$client->client_id_key = 'oid';
			}
			
			return $client;
		}
		
		return false;
	}
	
	function validate() {

		$this->form_validation->set_rules('task_id', 'task_id');
		$this->form_validation->set_rules('client_id', $this->lang->line('client'), 'required');
		$this->form_validation->set_rules('client_id_key', $this->lang->line('client_id_key'), 'required');
		$this->form_validation->set_rules('start_date', $this->lang->line('start_date'), 'required');
		$this->form_validation->set_rules('due_date', $this->lang->line('due_date'));
		$this->form_validation->set_rules('complete_date', $this->lang->line('complete_date'));
		$this->form_validation->set_rules('title', $this->lang->line('title'), 'required');
		$this->form_validation->set_rules('description', $this->lang->line('description'), 'required');

		if($return = parent::validate())
		{
			//apply the changes to the task object
			foreach ($this->mdl_tasks->form_values as $key => $value) {
				switch ($key) {
					case 'start_date':
					case 'due_date':
					case 'complete_date':
						$this->mcbsb->task->$key = strtotime(standardize_date($value));
						break;
			
					case 'task_id':
						if(!empty($value)) $this->mcbsb->task->$key = $value;
						break;
			
					default:
						$this->mcbsb->task->$key = $value;
						break;
				}
			}
			
			$this->mcbsb->task->user_id = $this->mcbsb->_user->id;
			return true;			
		} else {
			return $return;
		}
	}

 	public function get($skip_contact_info = false) {
			
		if(!empty($this->mcbsb->task->task_id)) {
			
			if($this->mcbsb->task->read()) {
				
				if($skip_contact_info) return true;
				
				return $this->retrieve_contact_name($this->mcbsb->task);
			
			} else {

				return false;
			}
		} else {
			//TODO add system message
			return false;
		}
	}
	
	
	function save() {

		$db_array = parent::db_array();

		if (!uri_assoc('task_id', 3)) {

			$db_array['user_id'] = $this->session->userdata('user_id');

		}

		if (isset($db_array['due_date']) and $db_array['due_date']) {

			$db_array['due_date'] = strtotime(standardize_date($db_array['due_date']));

		}

		if (isset($db_array['complete_date']) and $db_array['complete_date']) {

			$db_array['complete_date'] = strtotime(standardize_date($db_array['complete_date']));

		}

		$db_array['start_date'] = strtotime(standardize_date($db_array['start_date']));

		parent::save($db_array, uri_assoc('task_id', 3));

	}

	function save_invoice_relation($invoice_id, $task_id) {

		$db_array = array(
			'task_id'			=>	$task_id,
			'invoice_id'		=>	$invoice_id
		);

		$this->db->insert('mcb_tasks_invoices', $db_array);
		
	}

	function create_invoice_from_tasks($task_ids) {

		$this->load->model('invoices/mdl_invoices');

		$params = array(
			'where_in'	=>	array('mcb_tasks.task_id' => $task_ids)
		);

		$tasks = parent::get($params);

		foreach ($tasks as $task) {

			if (!isset($invoice_id)) {

				$invoice_id = $this->mdl_invoices->save($task->client_id, $task->client_id_key, $task->complete_date, FALSE);

			}

			$this->mdl_invoices->save_invoice_item($invoice_id, $task->title, $task->description, 1, 0);

			$db_array = array(
				'task_id'		=>	$task->task_id,
				'invoice_id'	=>	$invoice_id
			);

			$this->db->insert('mcb_tasks_invoices', $db_array);

		}

		return $invoice_id;

	}

/*	
	function prep_validation($key = null) {

		#parent::prep_validation($key);

		if (!$_POST) {

			if ($this->form_value('due_date')) {

				$this->set_form_value('due_date', format_date($this->form_value('due_date')));

			}

			if ($this->form_value('complete_date')) {

				$this->set_form_value('complete_date', format_date($this->form_value('complete_date')));

			}

			if ($this->form_value('start_date')) {

				$this->set_form_value('start_date', format_date($this->form_value('start_date')));

			}	

		}

	}
*/
	function delete($params) {

		parent::delete($params);

		$this->db->where('task_id', $params['task_id']);

		$this->db->delete('mcb_tasks_invoices');

	}

}

?>