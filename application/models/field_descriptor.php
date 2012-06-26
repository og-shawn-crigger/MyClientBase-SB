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
class Field_Descriptor extends CI_Model
{
	var $name;
	var $type;
	var $lenght;
	
	public function __construct(){
		parent::__construct();
		// 		$CI = &get_instance();
		// 		$CI->load->database();
	}
	
	public function __destruct(){
	
	}
	
}