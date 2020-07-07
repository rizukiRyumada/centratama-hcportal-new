<?php

defined('BASEPATH') OR exit('No direct script access allowed');

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

class MainController extends MY_Controller {
    
    public function __construct()
    {
        parent::__construct();

        // main helper
        is_logged_in(); //Cek Login
        
        date_default_timezone_set('Asia/Jakarta'); // set timezone
    }
    
}

/* End of file MY_Controller.php */
