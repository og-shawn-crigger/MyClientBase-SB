<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Sales_by_Customer extends Admin_Controller {

    public function __construct() {

        parent::__construct();

    }

    public function index() {

        $this->load->model('clients/mdl_clients');

        $data = array(
            'output_types' => array('pdf', 'view')
        );

        $this->load->view('sales_by_customer', $data);

    }

    public function jquery_display_results($output_type, $from_date = 0, $to_date = 0) {

		$this->load->model('mdl_sales_by_customer');

		$totals = $this->mdl_sales_by_customer->get($from_date, $to_date);
		
		$data = array(
			'totals'	=>	$totals
		);

		if ($output_type == 'view') {

			$this->load->view('sales_by_customer_view', $data);

		}

		elseif ($output_type == 'pdf') {

            $this->load->helper($this->mdl_mcb_data->setting('pdf_plugin'));

            $html = $this->load->view('sales_by_customer_pdf', $data, TRUE);

            pdf_create($html, url_title($this->lang->line('sales_by_customer'), '_'), TRUE);

		}

    }

}

?>