<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Returns the Code Igniter translation_folder path for the required language.
 * 
 * @access		public
 * @param		string			$lang		The required language (english, italian ...)
 * @var			
 * @return		string			The absolute folder_path according to the configuration file citranslate.php
 * @example
 * @see ./application/third_party/Smarty/plugins/function.citranslate.php
 * 
 * @author 		Damiano Venturin
 * @copyright 	2V S.r.l.
 * @license		GPL
 * @link		http://www.squadrainformatica.com/en/development#mcbsb  MCB-SB official page
 * @since		Jan 21, 2012
 * 
 * @todo		
 */
function setupCiTranslate($lang) {

	$CI =& get_instance();

	$CI->load->config('citranslate');
	
	$dirname = $CI->config->item('languageDirName_'.$lang);

	return $dirname;
}

/* End of file citranslate_helper.php */
/* Location: ./application/helpers/citranslate_helper.php */