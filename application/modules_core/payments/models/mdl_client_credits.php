<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Client_Credits extends MY_Model {

	public function __construct() {

		parent::__construct();

		$this->table_name = 'mcb_client_credits';

		$this->primary_key = 'mcb_client_credits.client_credit_id';

		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *";

		$this->order_by = 'mcb_client_credits.client_credit_date DESC';

		$this->joins = array(
			'mcb_clients'	=>	'mcb_clients.client_id = mcb_client_credits.client_credit_client_id'
		);

		$this->limit = $this->mdl_mcb_data->setting('results_per_page');

		$this->custom_fields = $this->mdl_fields->get_object_fields(5);

	}

	public function validate() {

		$this->form_validation->set_rules('client_credit_client_id', $this->lang->line('client'), 'required');
		$this->form_validation->set_rules('client_credit_date', $this->lang->line('date'), 'required');
		$this->form_validation->set_rules('client_credit_amount', $this->lang->line('amount'), 'required');
		$this->form_validation->set_rules('client_credit_note', $this->lang->line('note'));

		foreach ($this->custom_fields as $custom_field) {

			$this->form_validation->set_rules($custom_field->column_name, $custom_field->field_name);

		}

		return parent::validate($this);

	}

	public function db_array() {

		$db_array = parent::db_array();
		$db_array['client_credit_date'] = strtotime(standardize_date($db_array['client_credit_date']));
		$db_array['client_credit_amount'] = standardize_number($db_array['client_credit_amount']);

		return $db_array;

	}

	public function prep_validation($key) {

		parent::prep_validation($key);

		if (!$_POST) {

			$this->set_form_value('client_credit_date', format_date($this->form_value('client_credit_date')));

		}

	}

	public function set_date() {

		$this->set_form_value('client_credit_date', format_date(time()));

	}

}

?>