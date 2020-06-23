<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function index(){
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = "Survey";
        $data['load_view'] = 'survey/index_survey_v';
        
        $this->load->view('main_v', $data);
    }

}

/* End of file Dashboard.php */
