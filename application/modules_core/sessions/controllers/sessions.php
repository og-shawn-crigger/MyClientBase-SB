<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Sessions extends CI_Controller {

    function __construct() {

        parent::__construct();

        $this->load->library(array('session'));

        $this->load->database();
		
		if (!$this->db->table_exists('mcb_users')) {

			$this->load->helper('url');

			redirect('setup');

		}

        $this->load->helper('mcb_app');

        $this->load->model('mcb_data/mdl_mcb_data');

        $this->mdl_mcb_data->set_application_title();

    }

    function index() {

        redirect('sessions/login');

    }

    function login() {

        $this->_load_language();

        $this->load->model('mdl_auth');
        
        if ($this->mdl_auth->validate_login()) {

        	$posted_captcha = strtolower($this->input->post('captcha'));
        	$saved_captcha = strtolower($this->session->userdata('captcha'));
        	if($posted_captcha != $saved_captcha) {
        		redirect('/');
        	}
        	 
            if ($user = $this->mdl_auth->auth($this->input->post('username'), $this->input->post('password'))) {
            	
            	
                $object_vars = array('user_id', 'last_name', 'first_name', 'global_admin');
                
                $this->mdl_auth->set_session($user, $object_vars, array('is_admin'=>TRUE));
                                
                // update the last login field for this user
                $this->mdl_auth->update_timestamp('mcb_users', 'user_id', $user->user_id, 'last_login', time());

                //default home page
                redirect('contact');
            }

        }

        //adds captcha
        $this->load->helper('captcha');
        $captcha = rand_string(5);
        $this->session->set_userdata(array('captcha' => $captcha));
        
        //remove old captcha pictures
        $jpgpath = FCPATH . 'captcha/*.jpg';
        $files = glob($jpgpath);
        foreach($files as $file){
        	if(is_file($file)) unlink($file);
        }        
        
        //creates captcha picture
        $vals = array(
        		'word'	 => $captcha,
        		'img_path'	 => './captcha/',
        		'img_url'	 => base_url() . 'captcha/',
        		'img_width'	 => '115',
        		'img_height' => 45,
        		'expiration' => 7200
        );
        
        $data = array('captcha' => create_captcha($vals));
        
        $this->load->view('login',$data);

    }

    function logout() {

        $this->load->helper('url');

        $this->session->sess_destroy();

        redirect('sessions/login');

    }

    function recover() {

        $this->_load_language();

        $this->load->model('mdl_recover');

        //$this->load->helper(array('url', 'form'));

        if (!$this->mdl_recover->validate_recover()) {

            $this->load->view('recover');

        }

        else {

            $this->mdl_recover->recover_password($this->input->post('username'));

            $this->load->view('recover_email');

        }

    }

    function _load_language() {

        $this->load->model('mcb_data/mdl_mcb_data');

        $default_language = $this->mdl_mcb_data->get('default_language');

        if ($default_language) {

            $this->load->language('mcb', $default_language);

        }

        else {

            $this->load->language('mcb');

        }

    }

}

?>