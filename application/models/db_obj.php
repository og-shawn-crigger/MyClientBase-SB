<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * NOTE: REFACTORY IN SLOW PROGRESS. If you are not refactoring please rely on the other files
 * 
 * @access		public
 * @param		
 * @var			
 * @return		
 * @example
 * @see
 * 
 * @author 		Damiano Venturin
 * @copyright 	Taptank
 * @link		http://www.taptank.com
 * @since		Feb 25, 2012
 * 	
 */
class Db_Obj extends CI_Model
{
	private $obj_ID_field = null;
	private $obj_ID_value = null;
	private $obj_name = null;
	private $db;
	private $db_name = null;
	private $db_table = null;
	protected $db_table_fields = array();
	
	public function __construct(){
		parent::__construct();
		
		$CI = &get_instance();
		$this->db = $CI->db;
		$this->db_name = $CI->db->database;
	}

	public function __destruct(){
		
	}

	protected function initialize(){
		if(is_null($this->db_name) || is_null($this->db_table) || empty($this->db_name) || empty($this->db_table)) {
			die('The object: '.$this->obj_name.' has no db_name or db_table set');
		}
		
		$rd = new Record_Descriptor();
		$this->db_table_fields = $rd->getFieldsList($this->db_name, $this->db_table);
		
		return true; //TODO improve return
	}
	
	public function __set($attribute, $value) {
		if(in_array($attribute, $this->db_table_fields)) {
			//TODO DAM improve filter
			//$this->$attribute = $this->db->escape(trim($value));
			if(is_array($value)){
				//transform the array into a string with values separated by comma
				$this->$attribute = implode(',',$value);  //TODO I'm not sure about this. Maybe it's better to skip the setting...
			} else {
				$this->$attribute = trim($value);
			}
		} else {
			if($attribute == 'obj_ID_value') {
				$id_field = $this->obj_ID_field;
				$this->$id_field = $value;
			} else {
				//TODO evaluate: what to do with attributes not set in the db fields?
				$this->$attribute = $value;
			}
		}
		
	}
	
	public function __get($attribute) {
		
		if($attribute == 'obj_ID_value')
		{
			$id_field = $this->obj_ID_field;
			$this->obj_ID_value = $this->$id_field;
		}
		return isset($this->$attribute) ? $this->$attribute : null;
	}
	
	public function __isset($attribute) {
		return isset($this->$attribute) ? true : false;
	}	

	
	protected function create(){
		//if(!is_null($this->obj_ID_value)) return false;
		
		$this->object_to_db();
		
		if($result_insert = $this->db->insert($this->db_table)) {
 
			$new_id = $this->db->insert_id();
			
			//If the table has no auto_increment primary key then $this->db->insert_id() returns zero
			if($new_id != 0) {
				$attribute = $this->obj_ID_field;
				$this->obj_ID_value = $new_id;
			 	$this->__set($attribute, $new_id);
			} else {
				//TODO DAM 			
				//In this case we might want to return the id value set before the insert using "$new_id = select max(id) + 1". We'll see.
			}
			
			return $this->obj_ID_value; 
		}
		return false;		
	}

	protected function read(){
		if(is_null($this->__get('obj_ID_value'))) return false;
		
		$this->db->select(implode(',',$this->db_table_fields));
		$this->db->where($this->obj_ID_field,$this->obj_ID_value);
		$query = $this->db->get($this->db_table);
		
		return $this->toObject($query);
	}
		
	protected function update(){
		if(is_null($this->obj_ID_value)) return false;
		
		$this->object_to_db();
		$this->db->where($this->obj_ID_field,$this->obj_ID_value);
		return $this->db->update($this->db_table);
	}	
	
	protected function delete(){
		if(is_null($this->obj_ID_value)) return false;
		
		$this->db->where($this->obj_ID_field,$this->obj_ID_value);
		return $this->db->delete($this->db_table);
	}
	
	/**
	 * Returns an array containing the IDs of the records matching the query
	 * 
	 * @access		public
	 * @param		
	 * @var			
	 * @return		array
	 * @example
	 * @see
	 * 
	 * @author 		Damiano Venturin
	 * @copyright 	Taptank
	 * @link		http://www.taptank.com
	 * @since		Feb 28, 2012
	 * 	
	 */
	public function search(array $where = null, $logic_operator = 'AND'){
		
		$this->db->select($this->obj_ID_field);
		
		$where_is_set = true; //by default we mean to return all the records without a filter
		
		if(!is_null($where)) $where_is_set = $this->addWhereCondition($where, $logic_operator);
		
		if($where_is_set) {
			$ids = array();
			$result = $this->performSearch();
			if($result) {
				foreach ($result as $item){
					$ids[] = $item[$this->obj_ID_field];
				}
				return $ids;
			}
		} else {
			return false;
		}
		
	}
	
	public function searchProvidingSql($sql, $return_as_data_array = true){
		return $this->performSearch($sql, $return_as_data_array);
	}
	
	public function getAllRecords(array $where = null, $logic_operator = 'AND', $return_as_data_array = true, array $orderby = null) {
		
		$this->db->select('*');
		
		$where_is_set = true; //by default we mean to return all the records without a filter
		
		if(!is_null($where)) $where_is_set = $this->addWhereCondition($where, $logic_operator);
		
		if(count($orderby)>0) $this->addOrderCondition($orderby);
		
		return $where_is_set ? $this->performSearch(null, $return_as_data_array) : false;
				
	}	
	
	private function addWhereCondition(array $where, $logic_operator) {

		if(count($where) == 0) return false;
		
		if(!isAssociativeArray($where)) return false;

		foreach ($where as $field => $value)
		{
			switch ($logic_operator) {
				case 'OR':
					$this->db->or_where($field, $value);
				break;

				case 'AND':
					$this->db->where($field, $value);
				break;

				default:
					return false;
				break;
			}
		}
			
		return true;
	}
	
	private function addOrderCondition(array $orderby) {
		
		if(!isAssociativeArray($orderby)) return false;
		
		foreach ($orderby as $field => $value)
		{
			$this->db->order_by($field,$value);	
		}
		
		return true;
	}	
	
	public function performSearch($sql = null, $return_as_data_array = true) {

		//fields selection and where conditions must be set in the methods calling this method
		 
		if(is_null($sql)) {
			$query = $this->db->get($this->db_table);
		} else {
			$query = $this->db->query($sql);
		}
		
		$result = array();

		if($return_as_data_array) {
			return $query->result_array();
		} else {
			return $query->result();
		}
	}
	
	private function object_to_db(){
		foreach ($this->db_table_fields as $field) {
			//TODO add validation, filters etc
			$this->db->set($field,$this->$field);
		}
	}
	
	protected function toObject($query) {
		if(!is_object($query) || get_class($query)!='CI_DB_mysql_result') return false;

		//TODO DAM query validation, like only one record ... bla bla
		$record = $query->row();
		if($record)
		{
			foreach ($this->db_table_fields as $field) {
				if(isset($record->$field)) $this->$field = $record->$field; //TODO is this still throwing errors?
			}
			return true;
		} 
		return false;
	}
		
	public function toArray(){
		$result = array();
		foreach ($this->db_table_fields as $field) {
			$result[$field] = $this->$field;
		}
		return $result;
	}
}