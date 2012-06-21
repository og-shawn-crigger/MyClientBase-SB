<?php
/**
 * Smarty {citranslate text='textToTranslate' lang='it'} function plugin.
 * Localizes a sentence using Code Igniter default translation method instead of the PO translation.
 * Type:     function<br>
 * Name:     citranslate<br>
 * Purpose:  print out a translated sentence using the common CI method (just a workaround)
 * 
 * @access		public
 * @param 		array 			$params
 * @var			
 * @return		string			Translated sentence or original sentence if there wase 
 * @example
 * @see
 *
 * @package 	Smarty
 * @subpackage 	PluginsFunction
 * 
 * @author 		Damiano Venturin
 * @copyright 	2V S.r.l.
 * @license		GPL
 * @link		http://www.squadrainformatica.com/en/development#mcbsb  MCB-SB official page
 * @since		Jan 21, 2012
 * 
 */
function smarty_function_citranslate($params, $template)
{
	$lang = array();
	
	$dirname = setupCiTranslate($params['lang']);
	
 	$filename = $dirname.'mcb_lang.php';
 	if(is_file($filename))
 	{
 		include $filename;
 	}
 	
 	$text = $params['text'];
 	if(isset($lang[$text]))
 	{
    	return $lang[$text];
 	} else {
 		return $params['text'];
 	}
}

?>