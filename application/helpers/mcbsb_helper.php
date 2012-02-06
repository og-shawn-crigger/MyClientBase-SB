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

/* End of file mcbsb_helper.php */
/* Location: ./application/helpers/mcbsb_helper.php */