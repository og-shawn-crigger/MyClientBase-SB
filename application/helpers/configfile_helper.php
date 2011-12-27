<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//DAM quick and dirty to update a config file. Can be improved A LOT
function write_config($filename, array $config_items, $create=false) {
	
	//TODO add a method to retrieve the right path for the specified config file
	$filepath = APPPATH.'config/'.$filename.'.php';
	if(!file_exists($filepath) and !$create) return false;
	
	$CI =& get_instance();
	
	$config = '<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');'."\n\n";
	
	foreach ($config_items as $key => $item) {
		$value = $CI->config->item($item);
		if($value)
		{
			if(is_array($value)) $value = 'array(\''.implode('\',\'', $value).'\');'."\n";
			$config .= '$config[\''.$item.'\'] = '.$value;
		}
	}
	
	return file_put_contents($filepath, $config);
	
}

/* End of file configfile_helper.php */
/* Location: ./application/helpers/configfile_helper.php */