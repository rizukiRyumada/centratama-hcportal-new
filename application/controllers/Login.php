<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function index()
    {
        $data['page_title'] = "Survey";
        $this->load->view('login_v');
    }

    public function test(){
        $this->load->view('test');
    }

}

/* End of file Login.php */
