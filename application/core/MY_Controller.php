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
        
        date_default_timezone_set('Asia/Jakarta'); // set timezone
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
