<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Calendar extends Admin_Controller {

	function __construct() {

		parent::__construct();

		if ($this->input->post('btn_list_view')) {

			redirect('invoices');

		}

		elseif ($this->input->post('btn_add_invoice')) {

			redirect('invoices/create');

		}

		$this->load->model('invoices/mdl_invoices');

	}

	function index() {
		$data = array();
		$data['site_url'] = site_url($this->uri->uri_string());
		$data['base_url'] = base_url();
		$data['actions_panel'] = $this->plenty_parser->parse('actions_panel.tpl', $data, true, 'smarty', 'invoices');
		$this->load->view('index',$data);

	}

	public function jquery_get_invoices($status = 'open') {

		$function = "get_" . $status;

		$invoices = $this->mdl_invoices->$function();

		$inv_array = array();

		foreach ($invoices as $invoice) {
			
			$inv_array[] = array(
				'id'    => $invoice->invoice_id,
				'title' => $invoice->client_name . ' (' . display_currency($invoice->invoice_total) . ')',
				//'title' => $invoice->invoice_id,
				'start' => date('Y-m-d', $invoice->invoice_due_date),
				'url'   => './invoices/edit/invoice_id/'. $invoice->invoice_id,
			);

		}

		echo json_encode($inv_array);
		
	}

	//TODO Dam this function has been removed in 0.12. Should I remove it too?
	function get_overdue() {

		$data = array(
			'overdue'	=>	$this->mdl_invoices->get_overdue()
		);

		$this->load->view('json_overdue.php', $data);

	}

	function get_open() {

		$data = array(
			'open'	=>	$this->mdl_invoices->get_open()
		);

		$this->load->view('json_open.php', $data);

	}


}