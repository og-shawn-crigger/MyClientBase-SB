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

		$this->load->view('index');

	}

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