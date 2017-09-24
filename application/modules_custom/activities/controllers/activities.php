<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Activities extends Admin_Controller {
	
	public function __construct() {

		parent::__construct();

		if (!$this->mdl_mcb_modules->check_enable('activities')) {
			redirect('contact');
		}

		$this->load->helper('date');
		
		//loads the Task obj into the mcbsb obj
		$this->mcbsb->load('tasks/task','task');
		
		$this->load->model('tasks/mdl_tasks','mdl_tasks');
		
		//loads the Activity obj into the mcbsb obj
		$this->mcbsb->load('activities/activity','activity');

		$this->load->model('activities/mdl_activities','mdl_activities');
	}

	public function index() {

		redirect('/tasks');
			
	}
	
	function form() {
	
		$this->redir->set_last_index();
	
		$data = array();
	
		$this->_post_handler();
	
		if ($_POST) { 	//it's an update or a new activity
	
			//is it a validated submit? (i.e. are all the rules respected?)
			if ($this->mdl_activities->validate()) {
	
				if(empty($this->mcbsb->activity->id)) {
						
					//it's a new activity
					if($this->mcbsb->activity->create()) {
						$this->mcbsb->system_messages->success = 'Activity has been successfully created';
					} else {
						$this->mcbsb->system_messages->error = 'Activity has not been created';
					}
	
				} else {
	
					//it's an update
					if($this->mcbsb->activity->update()) {
						$this->mcbsb->system_messages->success = 'The activity has been successfully updated';
					} else {
						$this->mcbsb->system_messages->error = 'The activity has not been updated';
					}
				}
	
				redirect('tasks/form/task_id/'.$this->mcbsb->activity->task_id);
	
			} else {
				//$this->mcbsb->system_messages->error = 'The activity has been successfully created';
			}
		}
	
		if($activity_id = uri_assoc('activity_id', 3)) {
				
			//it's an old task to be edit
				
			$this->mcbsb->activity->id = $activity_id;
			if($this->mcbsb->activity->read()) { //this populates $this->mcbsb->task with contact information
					
				$this->mcbsb->task->task_id = $this->mcbsb->activity->task_id;
				if($this->mcbsb->mdl_tasks->get()) {
					$data['task'] = $this->mcbsb->task;
				} else {
					$this->mcbsb->system_messages->error = 'The specified task can not be retrieved';
					redirect('tasks/form/task_id/'.$this->mcbsb->activity->task_id);
				}
				$data['activity'] = $this->mcbsb->activity;
	
			} else {
				//activity not found. That's a huge error
				$this->mcbsb->system_messages->error = 'The specified activity can not be retrieved';
				redirect('tasks');
			}
				
		} else {
	
			//it's a new activity
			if($task_id = uri_assoc('task_id', 3)) {
				$this->mcbsb->task->task_id = $task_id;
				if($this->mcbsb->mdl_tasks->get()) {
					$data['task'] = $this->mcbsb->task;
					$this->mcbsb->activity->task_id = $task_id;
					$this->mcbsb->activity->date = now();
					$data['activity'] = $this->mcbsb->activity;
				} else {
					$this->mcbsb->system_messages->error = 'The specified task can not be retrieved';
					redirect('tasks/form/task_id/'.$task_id);
				}
			} else {
				$this->mcbsb->system_messages->error = 'Task_id is missing';
				redirect('tasks');				
			}			
		}
	
		$data['site_url'] = site_url($this->uri->uri_string());
		$data['actions_panel'] = $this->plenty_parser->parse('actions_panel.tpl', $data, true, 'smarty', 'tasks');
		$this->load->view('form', $data);
	}
	
	
	function delete() {
	
		if ($activity_id = uri_assoc('activity_id', 3)) {
	
			$this->mcbsb->activity->id = $activity_id;
			if($this->mcbsb->activity->read()) {
	
				//TODO if is not invoiced ...
	
				if($this->mcbsb->activity->delete()) {
					$this->mcbsb->system_messages->success = 'The activity has been successfully deleted';
				} else {
					$this->mcbsb->system_messages->error = 'The activity has been deleted';
				}
			}

		} else {
			$this->mcbsb->system_messages->error = 'Activity_id is missing';
			redirect('tasks');
		}
	
		redirect('tasks/form/task_id/'.$this->mcbsb->activity->task_id);
	
	}
		
	function _post_handler() {
	
		if ($this->input->post('btn_cancel')) {
	
			redirect('tasks');
	
		}
	}	
	
}
