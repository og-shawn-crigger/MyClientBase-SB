<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function setupCiTranslate($lang) {

	$CI =& get_instance();

	$CI->load->config('citranslate');
	
	$dirname = $CI->config->item('languageDirName_'.$lang);

	return $dirname;
}

/* End of file citranslate_helper.php */
/* Location: ./application/helpers/citranslate_helper.php */