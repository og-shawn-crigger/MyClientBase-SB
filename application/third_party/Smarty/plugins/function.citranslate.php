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
	
 	switch ($params['lang']) {
 		case 'en':
 			$dirname='/home/damko/development/code/php/myclientbase-sb/application/language/english/';
 		break;
 		
 		case 'it':
			$dirname='/home/damko/development/code/php/myclientbase-sb/application/language/italian/'; 			
 		break;
 		
 		default:
 			$dirname='/home/damko/development/code/php/myclientbase-sb/application/language/english/';
 		break;
 	}
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