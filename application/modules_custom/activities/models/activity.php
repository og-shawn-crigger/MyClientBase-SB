<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Activity object
 *  
 * @author 		Damiano Venturin
 * @copyright 	2V S.r.l.
 * @link		http://www.mcbsb.com
 * @since		July 11, 2012
 * 	
 */
class Activity extends Db_Obj
{
	//all the table fields go here as protected attributes
	protected $id = NULL ;
	protected $task_id = NULL ;
	protected $user_id =  NULL ;
	protected $date =  NULL ;
	protected $description = NULL ;
	protected $duration = NULL ;
	protected $mileage = NULL ;
	protected $tag =  NULL ;
	protected $task_weight = NULL ;
	
	public function __construct(){
		parent::__construct();
		
		$this->obj_ID_field = 'id';  //here goes the name of the primary field identifying each record
		$this->obj_name = get_class($this);
		$this->db_table = 'mcb_activities';
		
		$this->initialize();
	}

	public function __destruct(){
	
	}
	
	public function create(){
		if(!is_null($this->obj_ID_value)) return false;		
		return parent::create();
	}

	public function read(){
		if(is_null($this->obj_ID_value)) return false;		
		return parent::read();
	}
		
	public function update(){
		if(is_null($this->obj_ID_value)) return false;
		return parent::update();
	}	
	
	public function delete(){
		if(is_null($this->obj_ID_value)) return false;
		if(!$this->read()) return false;
		return parent::delete();	
	}
	
	public function readAll(array $params = null){

		$where = null;
		$logic_operator = 'AND';
				
		if(!is_null($params)) extract($params);
			
		if($results = $this->search($where,$logic_operator)){
			$tasks = array();
			foreach ($results as $key => $id) {
				$tmp = new Activity();
				$tmp->obj_ID_value = $id;
				$tmp->read();
				
				$activities[] = clone $tmp;
			}
				
			return $activities;
		}
		
		return false;
	}
		
	public function searchProvidingSql($sql){
		//generally on a specific database object you don't want to perform generic queries.
		//if you need to perform generic queries, use db_obj 
		return false;
	}	
}

/* End of file activity.php */
/* Location: ./application/modules_custom/activities/models/activity.php */