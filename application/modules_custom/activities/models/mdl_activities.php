<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Activities extends MY_Model {

	public $id;
	
	public function __construct() {

		parent::__construct();

		//loads the Activity obj into the mcbsb obj
		$this->mcbsb->load('activities/activity','activity');
		
		//loads the Contact obj into the mcbsb obj
		$this->mcbsb->load('contact/mdl_contact','contact');
	}

	public function getAll(array $params = null, $skip_user_info = false) {
		
		if(is_null($params)) $params = array();
		
		if($activities = $this->mcbsb->activity->readAll($params)) {
			
			if(!$skip_user_info){

				foreach ($activities as $activity) {
					$this->retrieve_user_name($activity);
				}
			}
			
			return $activities;		
		}
		
		return false;
	}
	
	public function retrieve_user_name(Activity $activity){
	
		if($username = $this->mcbsb->users->get_full_name($activity->user_id)) {
			$activity->username = $username;
			return true;
		}
		$activity->username = '--';
		return false;
	}

	function validate() {
	
		$this->form_validation->set_rules('id', 'id');
		$this->form_validation->set_rules('task_id', 'task_id');
		$this->form_validation->set_rules('date', $this->lang->line('date'));
		$this->form_validation->set_rules('description', $this->lang->line('description'),'required');
		$this->form_validation->set_rules('duration', $this->lang->line('duration'));
		$this->form_validation->set_rules('mileage', $this->lang->line('mileage'), 'required');
		//$this->form_validation->set_rules('tag', $this->lang->line('tag'), 'required');
	
		if($return = parent::validate())
		{
			//apply the changes to the task object
			foreach ($this->mdl_activities->form_values as $key => $value) {
				switch ($key) {
					case 'date':
						$this->mcbsb->activity->$key = strtotime(standardize_date($value));
					break;
							
					case 'id':
						if(!empty($value)) $this->mcbsb->activity->$key = $value;
					break;
							
					default:
						$this->mcbsb->activity->$key = $value;
					break;
				}
			}
				
			$this->mcbsb->activity->user_id = $this->mcbsb->_user->id;
			return true;
		} else {
			return $return;
		}
	}	
}

?>