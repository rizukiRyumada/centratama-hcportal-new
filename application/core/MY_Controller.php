<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Controller
 */
class MY_Controller extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
    }
    

    public function index()
    {
        
    }

}

/**
 * MainController
 */
class MainController extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();

        // main helper
        is_logged_in(); //Cek Login
        $this->checkToken();
        
        date_default_timezone_set('Asia/Jakarta'); // set timezone
    }
    public function checkToken() {
        // Token Checker
        if(!empty($this->session->userdata('token'))){
            // cek data token
            if(!empty($data = $this->Jobpro_model->getDetail('data', 'user_token', array('token' => $this->session->userdata('token')))['data'])){
                $data = json_decode($data, true);
    
                if($this->session->userdata('position_id') == $data['id_posisi']){
                    // hapus token dari database
                    $this->Jobpro_model->delete('user_token', array('index' => 'token', 'data' => $this->session->userdata('token')));
    
                    $this->session->set_userdata('msg', array(
                        'icon' => 'warning',
                        'msg' => $data['msg']
                    ));
                } else {
                    // set toastr notification
                    $this->session->set_userdata('msg', array(
                        'icon' => 'error',
                        'title' => 'Error',
                        'msg' => 'The link token is not yours!'
                    ));
                }
            }

            // hapus session token
            $this->session->unset_userdata('token');            
        }
    }
    
}

/**
 * AdminController
 */
class AdminController extends MainController {
    
    public function __construct(){
        parent::__construct();
        // load model
        $this->load->library('form_validation');
        
        // cek apa dia punya role admin atau maintenance
        if($this->session->userdata('role_id') != 1 && $this->session->userdata('role_id') != 2){
            show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
        }
        
        // cek akses jika dia role idnya 2 -> admin
        if($this->session->userdata('role_id') == 2){
			$this->cekAkses(); // cek akses
        }
    }

	// Admin Apps Checker -> cek apa admin punya akses sama aplikasi
	protected function cekAkses(){
		// cek akses menu pada url 1
		$id_menu_sub = $this->_general_m->getOnce('id_menu_sub', 'user_menu_sub', array('url' => $this->uri->segment(1)))['id_menu_sub'];
		// cek akses admin
		if($this->_general_m->getRow('user_adminsapp', array('id_menu_sub' => $id_menu_sub, 'nik' => $this->session->userdata('nik'))) < 1){
			show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
		}

		// jika akses sub menu dibawahnya cek dengan 2 url
		if(!empty($this->uri->segment(2))){
			// cek apa dia punya akses buat sesi ini
			if($this->session->userdata('role_id') == 2){
				// ambil id menu sub
				$id_menu_sub = $this->_general_m->getOnce('id_menu_sub', 'user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['id_menu_sub'];
				// cek akses admin	
				if($this->_general_m->getRow('user_adminsapp', array('id_menu_sub' => $id_menu_sub, 'nik' => $this->session->userdata('nik'))) < 1){
					show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
				}
			}
		}
	}
}

/**
 * SuperAdminController
 * This controller for admin function with user role 1
 */
class SuperAdminController extends MainController {
    
    public function __construct()
    {
        parent::__construct();
        
        // cek apa dia punya role maintenance
        if($this->session->userdata('role_id') != 1){
            show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
        }
    }
    
}

/* End of file MY_Controller.php */
