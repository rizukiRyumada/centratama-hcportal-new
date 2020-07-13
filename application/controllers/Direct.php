<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Direct extends CI_Controller {

    public function index()
    {
        // get  the token
        // show login page
        
        $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">
                Please Login first to continue. </div>');
        $this->session->set_userdata(array('error' => 1));
        if($this->input->get('token')){
            $this->session->set_userdata(array('token' => $this->input->get('token')));
        }

        header('location: '. base_url('login'));
    }

    function arahkan(){
        $this->load->model('Jobpro_model'); // load Jobpro_model

        if(!empty($data = $this->Jobpro_model->getDetail('data', 'user_token', array('token' => $this->session->userdata('token')))['data'])){ // ambil data token
            $data = json_decode($data, true);
            $token = $this->session->userdata('token');

            // hapuss session token dan errir
            $this->session->unset_userdata('token');
            $this->session->unset_userdata('error');

            // print_r($this->session->userdata('position_id'));
            // echo "<br/>";
            // echo "<br/>";
            // print_r($data['id_posisi']);
            // exit;

            if($this->session->userdata('position_id') == $data['id_posisi']){
                // hapus token dari database
                $this->Jobpro_model->delete('user_token', array('index' => 'token', 'data' => $token));
                $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">
                        '. $data['msg'] .' </div>');
                header('location: '. base_url($data['direct']));
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">
                        The link token is not yours! </div>');
                
                header('location: '. base_url('job_profile'));
            }
        } else {
            // hapuss session token dan errir
            $this->session->unset_userdata('token');
            $this->session->unset_userdata('error');

            $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">
                        The link token expired! </div>');
            
            header('location: '. base_url('job_profile'));
        }
    }

}

/* End of file Direct.php */
