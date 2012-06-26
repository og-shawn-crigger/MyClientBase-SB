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
class Record_Descriptor extends CI_Model
{
	private $fields = array();
	
	public function __construct(){
		parent::__construct();
	}
	
	public function __destruct(){
	
	}	
	
	protected function read($db_name , $table_name) {
			$query = $this->db->query(	'select *
									from information_schema.columns 
									where table_schema="'.$db_name.'" and table_name="'.$table_name.'" 
									order by column_name');
		
		foreach ($query->result() as $row)
		{
			$fd = new Field_Descriptor();
			$fd->name = $row->COLUMN_NAME;
			$fd->type = $row->DATA_TYPE;
			$tmp = $row->COLUMN_TYPE;
			$pieces = preg_split('/\(/', $tmp);
			if(isset($pieces[1])) $fd->lenght = str_replace(')', '', $pieces[1]);
			$this->fields[] = $fd;
		}		
	}
	
	public function getFieldsList($db_name, $table_name) {
		if(count($this->fields) == 0) $this->read($db_name, $table_name);
		$list = array(); 
		foreach ($this->fields as $key => $field) {
			$list[] = $field->name;
		}
		return $list;
	}
}