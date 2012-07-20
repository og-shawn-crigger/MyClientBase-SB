<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Setup extends Admin_Controller {

	function __construct() {

		parent::__construct(TRUE);

	}

	function index() {

	}

	function install() {

		$queries = array(
"CREATE TABLE IF NOT EXISTS `mcb_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `duration` float(8,2) NOT NULL DEFAULT '0.00',
  `mileage` int(11) NOT NULL DEFAULT '0',
  `tag` varchar(250) DEFAULT NULL,
  `task_weight` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"				
		);

		foreach ($queries as $query) {
			
			$this->db->query($query);

		}

	}

	function uninstall() {

		$queries = array(
			"DROP TABLE IF EXISTS `mcb_activities`"
		);

		foreach ($queries as $query) {
			$this->db->query($query);
		}
	}

	function upgrade() {

		$installed_version = $this->mdl_mcb_modules->custom_modules['activities']->module_version;

		//TODO dig about MCB module update
        if ($installed_version == '0.9.2') {
            $this->u093();
        }

	}

	function u093() {

		$this->db->where('module_path', 'activities');
		$this->db->set('module_version', '0.9.3');
		$this->db->update('mcb_modules');

	}

}
/* End of file setup.php */
/* Location: ./application/modules_custom/activities/controllers/setup.php */
?>