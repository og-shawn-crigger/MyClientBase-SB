<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {a url='http://www.google.com' text='link'} function plugin
 *
 * Type:     function<br>
 * Name:     citranslate<br>
 * Purpose:  print out a translated sentence using the common CI method (just a workaround)
 * @author Damiano Venturin 
 * @param array parameters
 * @param Smarty
 * @param object $template template object
 * @return string|null
 */
function smarty_function_a($params, $template)
{
	return '<a class="'.$params['class'].'" href="'.$params['url'].'">'.$params['text'].'</a>';    
}

?>