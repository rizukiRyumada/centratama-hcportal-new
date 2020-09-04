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
        $this->checkToken(); // cek token
        $this->userApp_admin = $this->cekUserAppAdmin(); // cek userapp admin
        
        date_default_timezone_set('Asia/Jakarta'); // set timezone
    }

    protected function cekUserAppAdmin(){
        // cek buat userapp admin di menusub health report
        if($this->session->userdata('role_id') == 2){
            // get menu id sekaligus ini daftar aplikasi
            $id_menu = $this->_general_m->getOnce('id_menu', 'user_menu', array('url' => $this->uri->segment(1)))['id_menu'];
            $value = $this->_general_m->getRow('user_userapp_admins', array(
                'id_menu' => $id_menu,
                'nik' => $this->session->userdata('nik')
            ));

            if($value > 0){
                return 1; // tandai sebagai iya
            } else {
                return 0; // tandai sebagai tidak
            }
        } else { // untuk user lain
            return 0; // tandai sebagai tidak
        }
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
        $id_menu = $this->_general_m->getOnce('id_menu', 'user_menu', array('url' => $this->uri->segment(1)))['id_menu'];
        
        // cek akses menu admin dengan id menu
        if($this->_general_m->getRow('user_adminsapp', array('id_menu' => $id_menu, 'nik' => $this->session->userdata('nik'))) < 1) {
		 	show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
        }
		// cek akses admin
		// if($this->_general_m->getRow('user_adminsapp', array('id_menu_sub' => $id_menu_sub, 'nik' => $this->session->userdata('nik'))) < 1){
		// 	show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
		// }

		// jika akses sub menu dibawahnya cek dengan 2 url
		// if(!empty($this->uri->segment(2))){
		// 	// cek apa dia punya akses buat sesi ini
		// 	if($this->session->userdata('role_id') == 2){
		// 		// ambil id menu sub
		// 		$id_menu_sub = $this->_general_m->getOnce('id_menu_sub', 'user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['id_menu_sub'];
		// 		// cek akses admin	
		// 		if($this->_general_m->getRow('user_adminsapp', array('id_menu_sub' => $id_menu_sub, 'nik' => $this->session->userdata('nik'))) < 1){
		// 			show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
		// 		}
		// 	}
		// }
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

/**
 * SpecialUserAppController
 */
class SpecialUserAppController extends MainController{
    
    public function __construct() {
        parent::__construct();
        
        // special user checker to check basic user who has special access based on user identifier
        // superadmin has all access
        if($this->session->userdata('role_id') == 1){
            // superadmin can access all
        // now the checker is used for admins and user
        } elseif($this->session->userdata('role_id') == 2 || $this->session->userdata('role_id') == 3) {
            // cek apa dia userapp admin
            if($this->userApp_admin != 1){
                $this->cekAkses();
            } else {
                // Userapp admin have all access
            }
        // other role disable access
        } else {
            show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
        }
    }
    
    protected function cekAkses(){
        // ambil rule dari id_menu
        $id_menu = $this->_general_m->getOnce('id_menu', 'user_menu', array('url' => $this->uri->segment(1)))['id_menu'];
        $rule_menu = $this->_general_m->getAll('rule, rule_value', 'user_userapp_special', array('id_menu' => $id_menu));

        // prepare variabel checker
        $is_allowed = 0;
        foreach($rule_menu as $v){
            // cari rule dengan gabungin table master employee dan master position
            $result = $this->_general_m->getJoin2tables(
                'nik', 
                'master_employee', 
                'master_position', 
                'master_employee.position_id = master_position.id',
                array(
                    'nik' => $this->session->userdata('nik'),
                    $v['rule'] => $v['rule_value']
                ));

            if(!empty($result)){
                $is_allowed++;
                break 1;
            }
        }

        // cek is_allowed
        if($is_allowed < 1){
            show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
        }
    }
}

/* End of file MY_Controller.php */
