<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['gettextProjectDir'] = FCPATH.APPPATH.'third_party/php-gettext-1.0.11';
$config['gettextLocaleDir'] = FCPATH.APPPATH.'third_party/php-gettext-1.0.11/locale';
//$config['gettextDefaultLocale'] = 'en_US';
$config['gettextDefaultLocale'] = 'ru_RU';
//$config['gettextDefaultLocale'] = 'it_IT';
$config['gettextInc'] = FCPATH.APPPATH.'third_party/php-gettext-1.0.11/gettext.inc';
$config['gettextSupportedLocales'] = array('en_US', 'it_IT', 'ru_RU');
$config['gettextEncoding'] = 'UTF-8';

/* End of file phpgettext.php */
/* Location: ./application/config/phpgettext.php */