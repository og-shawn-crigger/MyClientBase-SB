<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {citranslate text='textToTranslate' lang='it'} function plugin
 *
 * Type:     function<br>
 * Name:     citranslate<br>
 * Purpose:  print out a translated sentence using the common CI method (just a workaround)
 * @author Damiano Venturin 
 * @param array parameters
 * @param object $template template object
 * @return string
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