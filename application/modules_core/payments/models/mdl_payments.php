<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Payments extends MY_Model {

	public function __construct() {

		parent::__construct();

		$this->load->model('contact/mdl_contacts');
		
		$this->table_name = 'mcb_payments';

		$this->primary_key = 'mcb_payments.payment_id';

		$this->select_fields = "
		SQL_CALC_FOUND_ROWS *,
		mcb_payments.payment_method_id";

		$this->order_by = 'mcb_payments.payment_date DESC';

		$this->joins = array(
			'mcb_invoices'			=>	'mcb_invoices.invoice_id = mcb_payments.invoice_id',
			'mcb_payment_methods'	=>	array('mcb_payment_methods.payment_method_id = mcb_payments.payment_method_id', 'left')
		);

		$this->limit = $this->mdl_mcb_data->setting('results_per_page');

		$this->custom_fields = $this->mdl_fields->get_object_fields(5);

	}

	public function validate() {

		if (!uri_assoc('invoice_id')) {

			$this->form_validation->set_rules('invoice_id', $this->lang->line('invoice'), 'required');

		}

		$this->form_validation->set_rules('payment_date', $this->lang->line('payment_date'), 'required');
		$this->form_validation->set_rules('payment_method_id', $this->lang->line('payment_method'));
		$this->form_validation->set_rules('payment_amount', $this->lang->line('amount'), 'required|callback_amount_validate');
		$this->form_validation->set_rules('payment_note', $this->lang->line('note'));

		foreach ($this->custom_fields as $custom_field) {

			$this->form_validation->set_rules($custom_field->column_name, $custom_field->field_name);

		}

		return parent::validate($this);

	}
	
	private function retrieve_contact(stdClass $payment) {
		
		if(! empty($payment->client_id)) {
			$params = array($payment->client_id_key => $payment->client_id);
		
			if($contact = $this->get_contact($params))
			{
				if(strtolower($contact->objName) == "person") $payment->client_name = $contact->cn;
		
				if(strtolower($contact->objName) == "organization") $payment->client_name = $contact->o;
			}
		}
		return $payment;
	}
	
	public function get(array $params) {
		$tmp_payments = parent::get($params);
		
		$payments = array();
		
		if(is_array($tmp_payments)) {
			foreach ($tmp_payments as $payment) {

				$payments[] = $this->retrieve_contact($payment);
			}
		} else {
			//only one result
			if(is_object($tmp_payments)) {
				return $this->retrieve_contact($tmp_payments);
			} else {
				return false;
			}	
		}
		
		return $payments;
	}

	private function get_contact(array $params){
	
		//when the request is performed using client_id || uid || oid as input I get an object
		if(is_object($obj = $this->mdl_contacts->get($params)))
		{
			//$obj = $rest_return;
			$obj->prepareShow();
			return $obj;
		}
		return false;
	}
	
	public function amount_validate($payment_amount) {

		$payment_amount = standardize_number($payment_amount);

		if (uri_assoc('invoice_id')) {

			$invoice_id = uri_assoc('invoice_id');

		}

		elseif ($this->input->post('invoice_id')) {

			$invoice_id = $this->input->post('invoice_id');

		}

		if (isset($invoice_id)) {

			if ($payment_amount <= 0) {

				$this->form_validation->set_message('amount_validate', $this->lang->line('amount_greater_than_zero'));

				return FALSE;
				
			}

			$this->db->select('invoice_balance');

			$this->db->where('invoice_id', $invoice_id);

			$invoice_balance = $this->db->get('mcb_invoice_amounts')->row()->invoice_balance;

			if (!uri_assoc('payment_id')) {

				if ($payment_amount > $invoice_balance) {

					$this->form_validation->set_message('amount_validate', $this->lang->line('amount_cannot_exceed_invoice_balance'));

					return FALSE;

				}

			}

			elseif (uri_assoc('payment_id')) {

				$params = array(
					'where'	=>	array(
						'mcb_payments.payment_id'	=>	uri_assoc('payment_id')
					)
				);

				$original_amount = parent::get($params)->payment_amount;

				if ($payment_amount > ($invoice_balance + $original_amount)) {

					$this->form_validation->set_message('amount_validate', $this->lang->line('amount_cannot_exceed_invoice_balance'));

					return FALSE;

				}

			}

		}

		return TRUE;

	}

	public function db_array() {

		$db_array = parent::db_array();

		if (uri_assoc('invoice_id')) {

			$db_array['invoice_id'] = uri_assoc('invoice_id');

		}

		elseif ($this->input->post('invoice_id')) {

			$db_array['invoice_id'] = $this->input->post('invoice_id');

		}

		$db_array['payment_date'] = strtotime(standardize_date($db_array['payment_date']));

		$db_array['payment_amount'] = standardize_number($db_array['payment_amount']);

		return $db_array;

	}

	public function save() {

		$db_array = $this->db_array();

		parent::save($db_array, uri_assoc('payment_id'));

		if (isset($db_array['payment_method_id']) and $db_array['payment_method_id'] == '9999') {

			$this->db->select('client_id');
			$this->db->where('invoice_id', $db_array['invoice_id']);
			$client_id = $this->db->get('mcb_invoices')->row()->client_id;

			$credit_db_array = array(
				'client_credit_client_id'	=>	$client_id,
				'client_credit_amount'		=>	$db_array['payment_amount'] * -1,
				'client_credit_date'		=>	$db_array['payment_date']
			);

			$this->db->insert('mcb_client_credits', $credit_db_array);

		}

	}

	public function prep_validation($key) {

		parent::prep_validation($key);

		if (!$_POST) {

			$this->set_form_value('payment_date', format_date($this->form_value('payment_date')));

		}

	}

	public function set_date() {

		$this->set_form_value('payment_date', format_date(time()));

	}

	public function get_invoice_id($payment_id) {

		$this->db->select('invoice_id');

		$this->db->where('payment_id', $payment_id);

		return $this->db->get('mcb_payments')->row()->invoice_id;

	}

	public function get_total_paid($params = NULL) {

		$params = ($params) ? $params : array();

		$params['select'] = 'IFNULL(SUM(payment_amount), 0) AS total_invoice_paid';

		$result = parent::get($params);

		return $result[0]->total_invoice_paid;

	}

}

?>