<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {icon name='edit' alt='edit' ext='png'} function plugin
 *
 * Type:     function<br>
 * Name:     icon<br>
 * Purpose:  print out the html img code for a MCBSB icon
 * @author Damiano Venturin 
 * @return string|null
 */
function smarty_function_icon($params, $template) {
	extract($params);
	//TODO if file exist ... bla bla
	return '<img src="/assets/style/img/icons/' . $name . '.' . $ext . '" alt="' . $alt . '" />';

}
?>