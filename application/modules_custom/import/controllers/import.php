<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Import extends Admin_Controller {

    function __construct() {

        parent::__construct(TRUE);

		$this->lang->load('import/import');

    }

    function index() {

        if ($this->input->post('btn_upload')) {

            $config = array(
                'upload_path'	=>	'./uploads/temp/',
                'allowed_types'	=>	'csv',
                'max_size'		=>	'9999',
                'file_name'     =>  $this->input->post('import_type')
            );

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload()) {

                $data = array(
                    'static_error'	=>	$this->upload->display_errors()
                );

                $this->load->view('index', $data);

            }

            else {

                $upload_data = $this->upload->data();

                $this->mdl_mcb_data->save('invoice_logo', $upload_data['file_name']);

                redirect('import/' . $this->input->post('import_type'));

            }

        }

        else {

            $this->load->view('index');

        }

    }

    function clients() {

        $file = './uploads/temp/clients.csv';

        $this->load->model('users/mdl_users');

        $fields = array(
            'client_name'           =>  $this->lang->line('client_name'),
            'client_address'        =>  $this->lang->line('street_address'),
            'client_address_2'      =>  $this->lang->line('street_address_2'),
            'client_city'           =>  $this->lang->line('city'),
            'client_state'          =>  $this->lang->line('state'),
            'client_zip'            =>  $this->lang->line('zip'),
            'client_country'        =>  $this->lang->line('country'),
            'client_phone_number'   =>  $this->lang->line('phone_number'),
            'client_fax_number'     =>  $this->lang->line('fax_number'),
            'client_mobile_number'  =>  $this->lang->line('mobile_number'),
            'client_email_address'  =>  $this->lang->line('email_address'),
            'client_web_address'    =>  $this->lang->line('web_address'),
            'client_notes'          =>  $this->lang->line('notes')
        );

        if (!$this->input->post('btn_import')) {

            if (($handle = fopen($file, "r")) !== FALSE) {
                $rows = fgetcsv($handle, 1000, ",");
            }

            fclose($handle);

            $this->load->model('users/mdl_users');

            $data = array(
                'fields'    =>  $fields,
                'headers'   =>  $rows,
                'users'     =>  $this->mdl_users->get()
            );

            $this->load->view('import_clients', $data);

        }

        else {

            $rows = array();

            if (($handle = fopen($file, "r")) !== FALSE) {

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                    $rows[] = $data;

                }

                fclose($handle);
            }

            array_shift($rows);

            foreach (array_keys($fields) as $key) {

                if (is_numeric($this->input->post($key))) {

                    $map[$key] = $this->input->post($key);

                }

            }

            foreach ($rows as $row) {

                $db_array = array(
                    'client_user_id'   =>  $this->input->post('user_id')
                );

                foreach ($map as $field=>$key) {

                    $db_array[$field] = $row[$key];

                }

                if (!isset($db_array['client_notes'])) {

                    $db_array['client_notes'] = '';

                }

                $this->db->insert('mcb_clients', $db_array);

            }

            unlink($file);

            $this->session->set_flashdata('custom_success', $this->lang->line('import_successful'));

            redirect('import');

        }

    }

}

?>
