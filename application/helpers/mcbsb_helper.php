<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function isAssociativeArray(array $arr) {
	return (bool)count(array_filter(array_keys($arr), 'is_string'));
}

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
function retrieve_uid_oid(){
	$CI = &get_instance();
	$uid = uri_assoc('uid');
	$oid = uri_assoc('oid');
	
	if(empty($uid) && empty($oid))
	{
		if(uri_assoc('client_id'))
		{
			$client_id = uri_assoc('client_id');   //retrieving client_id from GET
		} else {
			if($CI->input->get_post('client_id')) $client_id = $this->input->post('client_id'); //retrieving client_id from POST
		}
	}
	 
	if($uid) {
		$params = array(
				'uid' => $uid,
				'client_id' => $uid,
				'client_id_key' => 'uid'
		);
	}
	
	if($oid) {
		$params = array(
				'oid' => $oid,
				'client_id' => $oid,
				'client_id_key' => 'oid'
		);
				
	}
		
	if(isset($client_id) && $client_id) {
		$params = array(
				'client_id' => $client_id
		);
	}
	
	return isset($params) ? $params : null;	
}

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

/**
 * in_array case insensitive: it will return a match if the needle is found in the haystack 
 * 
 * @access		public
 * @param		$needle String
 * @param		$haystack Array
 * @var			
 * @return		boolean
 * @example
 * @see
 * 
 * @author 		Damiano Venturin
 * @copyright 	2V S.r.l.
 * @license		GPL
 * @link		http://www.squadrainformatica.com/en/development#mcbsb  MCB-SB official page
 * @since		Jun 22, 2012
 * 
 * @todo		
 */
 
function in_arrayi($needle, array $haystack) {
	$needle = strtolower($needle);
	
	$haystack_flipped = array_flip($haystack);
	$haystack_flipped_lower = array_change_key_case($haystack_flipped);
	$haystack = array_flip($haystack_flipped_lower);
	
	return in_array($needle, $haystack);
}

/* End of file mcbsb_helper.php */
/* Location: ./application/helpers/mcbsb_helper.php */