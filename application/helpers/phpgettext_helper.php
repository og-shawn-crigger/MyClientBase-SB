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
		if (!function_exists('_gettext')) {
			require_once($CI->config->item('gettextInc'));
			log_message('debug','File '.$CI->config->item('gettextInc').' has been included.');
		}
	} else {
		log_message('debug','File '.$CI->config->item('gettextInc').' can not be found.');
	}
	
	$supported_locales = $CI->config->item('gettextSupportedLocales');
	$encoding = $CI->config->item('gettextEncoding');
	
	//takes the language settings from MCB and transforms it in a valid gettext locale
	$mcb_locale = $CI->mdl_mcb_data->get('default_language');
	switch ($mcb_locale) {
		case 'english':
			$smarty_locale = 'en_US';
		break;
		
		case 'italian':
			$smarty_locale = 'it_IT';
		break;
	}
	
	if(!isset($smarty_locale)) {
		$locale = $CI->config->item('gettextDefaultLocale'); //(isset($_GET['lang']))? $_GET['lang'] : DEFAULT_LOCALE;
	} else {
		$locale = $smarty_locale;
	}
	
	return array('locale' => $locale,
				 'encoding' => $encoding,
				 'supported_locales' => $supported_locales,
				 'project_dir' => PROJECT_DIR,
				 'locale_dir' => LOCALE_DIR,
				 'default_locale' => DEFAULT_LOCALE,
				 );
}

/* End of file phpgettext_helper.php */
/* Location: ./application/helpers/phpgettext_helper.php */