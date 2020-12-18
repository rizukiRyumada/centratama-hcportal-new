<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Direct extends CI_Controller {

    // main redirect on this class
    protected $redirect = 'job_profile';

    public function index()
    {
        // get  the token
        // show login page
        
        $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Please Login first to continue. </div>');
        $this->session->set_userdata(array('error' => 1));
        if($this->input->get('token')){
            $this->session->set_userdata(array('token' => $this->input->get('token')));
        }

        header('location: '. base_url('login'));
    }

    function arahkanFromEmail(){
        $this->load->model('Jobpro_model'); // load Jobpro_model

        if(!empty($data = $this->Jobpro_model->getDetail('data', 'user_token', array('token' => $this->session->userdata('token')))['data'])){ // ambil data token
            $data = json_decode($data, true);

            // hapuss session error
            $this->session->unset_userdata('error');

            header('location: '. base_url($data['direct']));
        } else {
            // hapus session token dan error
            $this->session->unset_userdata('token');
            $this->session->unset_userdata('error');

            // set toastr notification
            $this->session->set_userdata('msg', array(
                'icon' => 'error',
                'title' => 'Error',
                'msg' => 'The link token expired!'
            ));
            
            header('location: '. base_url('dashboard'));
        }
    }

    /* -------------------------------------------------------------------------- */
    /*                                link redirect                               */
    /* -------------------------------------------------------------------------- */
    function checkhealth(){
        // set session redirect url code
        $this->session->set_userdata('redirect', 'healthReport/healthStatus');
        // arahkan ke halaman login
        header('location: '. base_url('login'));
    }

    function survey(){
        // set session redirect url code
        $this->session->set_userdata('redirect', 'survey');
        // arahkan ke halaman login
        header('location: '. base_url('login'));
    }
}

/* End of file Direct.php */