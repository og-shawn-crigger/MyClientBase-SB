<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This is a basic database object example. You can copy and paste it and use it as a start to create any database related object.
 * Out of the box you get a complete ready to go CRUD. 
 *  
 * @author 		Damiano Venturin
 * @copyright 	2V S.r.l.
 * @link		http://www.mcbsb.com
 * @since		June 24, 2012
 * 	
 */
class Task extends Db_Obj
{
	//all the table fields go here as protected attributes
	protected $task_id = NULL ;
	protected $user_id =  NULL ;
	protected $client_id_key = NULL ;
	protected $client_id = NULL ;
	protected $start_date = NULL ;
	protected $due_date = NULL ;
	protected $complete_date = NULL ;
	protected $title = NULL ;
	protected $description	= NULL ;
	
	public function __construct() {
		parent::__construct();
		
		$this->obj_ID_field = 'task_id';  //here goes the name of the primary field identifying each record
		$this->obj_name = get_class($this);
		$this->db_table = 'mcb_tasks';   //here goes the table name
		
		$this->initialize();
	}

	public function __destruct(){
	
	}
	
	public function create() {
		if(!is_null($this->obj_ID_value)) return false;		
		return parent::create();
	}

	public function read() {
		if(is_null($this->obj_ID_value)) return false;		
		return parent::read();
	}
		
	public function update() {
		if(is_null($this->obj_ID_value)) return false;
		return parent::update();
	}	
	
	public function delete() {
		if(is_null($this->obj_ID_value)) return false;
		if(!$this->read()) return false;
		return parent::delete();	
	}
	
	public function readAll(array $params = null) {

		$where = null;
		$logic_operator = 'AND';
				
		if(!is_null($params)) extract($params);
				
		if($results = $this->search($where,$logic_operator)) {
			$tasks = array();
			foreach ($results as $key => $id) {
				$this->task_id = $id;
				$this->read();
				$tasks[] = clone $this;
			}
				
			return $tasks;
		}
		
		return false;
	}
		
	public function searchProvidingSql($sql) {
		//generally on a specific database object you don't want to perform generic queries.
		//if you need to perform generic queries, use db_obj 
		return false;
	}	
}