<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A function to update the content of a Code Igniter config file. 
 * 
 * @access		public
 * @param		string		$filename			The name of the config file
 * @param		array		$config_items		An array containing the config file items to be written
 * @param		boolean		$create				If true creates the config file if it doesn't exist
 * @var			
 * @return		boolean
 * @example
 * 
 * @author 		Damiano Venturin
 * @copyright 	2V S.r.l. 2011-08-01
 * @license		GPL
 * @link		http://www.squadrainformatica.com/en/development#mcbsb  MCB-SB official page
 * @since		2012-01-21
 * 
 */
function write_config($filename, array $config_items, $create=false) {
	
	$CI =& get_instance();
	$CI->load->helper('file');
	
	//The config file might be in the CI default config path (i.e. application/config or inside of the HMVC module in the config folder)
	//For now this function takes care ONLY of the default config path
	
	//Looking in the CI default path
	$filepath = APPPATH.'config/'.$filename.'.php';
	if(!file_exists($filepath) and !$create) return false;
	
	/*
	//This migh be a possible way to look inside the modules in a future version of this function
	//Looking in the HMVC config path
	//$loaded = $CI->is_loaded;
	//list($path, $file) = Modules::find($filename, $CI->MX_Loader->_module, 'config/');
	*/
	
	//that's the first row to write in the config file according with CI common practice
	$config = '<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');'."\n\n";
	
	//adding the configuration file items
	foreach ($config_items as $key => $item) {
		$new_value = $CI->config->item($item);
		if($new_value)
		{
			if(is_array($new_value))
			{
				$string = '';
				
				//if it's not an associative array (unfortunately there is no way to have a precise check ...)
				//just implode the array, otherwise pass through all the items
				if(isset($new_value[0]))
				{
					$string = 'array(\''.implode('\',\'', $new_value).'\');'."\n";
				} else {
					foreach ($new_value as $key => $value) {
						$string .= "\t\t\t\t".'\''.$key.'\' => \''.$value.'\','."\n";
					}
					$string = 'array('."\n".$string.');'."\n";
				}
			} else {
				$string = '"' . $new_value . '"' . ";\n";
			}
			$config .= '$config[\''.$item.'\'] = '.$string;
		}
	}
	
	//return file_put_contents($filepath, $config);
	return write_file($filepath, $config);
}

/* End of file configfile_helper.php */
/* Location: ./application/helpers/configfile_helper.php */