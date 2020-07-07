<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MainController {
    
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
    }
    

    public function index()
    {
        print_r($this->session->userdata());
    }

}

/* End of file Profile.php */
