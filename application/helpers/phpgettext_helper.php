<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function setupPhpGettext() {

	$CI =& get_instance();

	$CI->load->config('phpgettext');
	
	// define constants
	if(!defined('PROJECT_DIR')) define('PROJECT_DIR', realpath($CI->config->item('gettextProjectDir')));
	if(!defined('LOCALE_DIR')) define('LOCALE_DIR', realpath($CI->config->item('gettextLocaleDir')));
	if(!defined('DEFAULT_LOCALE')) define('DEFAULT_LOCALE', $CI->config->item('gettextDefaultLocale'));
	
	if(is_file($CI->config->item('gettextInc')))
	{
		require_once($CI->config->item('gettextInc'));
	} else {
		log_message('debug','File '.$CI->config->item('gettextInc').' can not be found.');
	}
	
	$supported_locales = $CI->config->item('gettextSupportedLocales');
	$encoding = $CI->config->item('gettextEncoding');
	
	//TODO maybe this should be set through GET or POST or SESSION ... will see
	$locale = $CI->config->item('gettextDefaultLocale'); //(isset($_GET['lang']))? $_GET['lang'] : DEFAULT_LOCALE;

	return $locale;
}

/* End of file phpgettext_helper.php */
/* Location: ./application/helpers/phpgettext_helper.php */