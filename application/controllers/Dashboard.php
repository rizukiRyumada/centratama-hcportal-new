<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MainController {
    
    public function __construct()
    {
        parent::__construct();

    }

    public function index(){
        // if($this->_general_m->getRow('survey_exc_hasil', array('nik' => $this->session->userdata('nik'))) < 1){
        //     //nothing
        // } else {
        //     $data['survey_status']['exc'] = 'closed';
        // }

        // if($this->_general_m->getRow('survey_eng_hasil', array('nik' => $this->session->userdata('nik'))) < 1){
        //     // nothing
        // } else {
        //     $data['survey_status']['eng'] = 'closed';
        // }

        // // main data
        // $data['sidebar'] = getMenu(); // ambil menu
        // $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        // $data['user'] = getDetailUser(); //ambil informasi user
        // $data['page_title'] = "Dashboard";
        // $data['load_view'] = 'dashboard/main_dashboard_v';
        
        // $this->load->view('main_v', $data);
        header('location: ' . base_url('job_profile'));
    }

}

/* End of file Dashboard.php */
