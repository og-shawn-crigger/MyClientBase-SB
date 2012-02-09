<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Transforms and returs the given string. Spaces are replaced with underscores and only characters and numbers are left
 * 
 * @access		public
 * @param		$string		string	The given string	
 * @return		string | false
 * @example
 * @see
 * 
 * @author 		Damiano Venturin
 * @copyright 	2V S.r.l.
 * @license		GPL
 * @link		http://www.squadrainformatica.com/en/development#mcbsb  MCB-SB official page
 * @since		Feb 5, 2012
 * 
 */

function only_chars_nums_underscore($string)
{
    if(is_array($string)) return false;
    $string = preg_replace('/ /', '_', trim($string));
	$string = preg_replace('/[^A-Za-z0-9-_]/', '', $string);
	return $string;
}

function saveUploadedFile()
{
	if($_FILES)
	{	 
		$CI = &get_instance();
		
		//TODO this should go in a config file
		$config['upload_path'] = 'uploads/';
		$config['allowed_types'] = 'gif|jpg|png'; 
		$config['max_size']	= '1000';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		$config['encrypt_name']  = true;  //If set to TRUE the file name will be converted to a random encrypted string
		 
		$CI->load->library('upload', $config);
		 
		$data = array();
		$error = array();
		foreach ($_FILES as $key => $values) {
			if ( ! $CI->upload->do_upload($key))
			{
				$error[$key] = $CI->upload->display_errors();
			}
			else
			{	
				//saves the file locally
				$data[$key] = $CI->upload->data();				
			}
		}
		$output = array('error' => $error, 'data' => $data);
		return $output;
	}	
}

/* End of file mcbsb_helper.php */
/* Location: ./application/helpers/mcbsb_helper.php */