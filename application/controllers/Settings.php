<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends SuperAdminController {

    protected $page_title = [
        'masterData' => 'Master Data Management',
        'masterData_employee' => 'Master Employee'
    ];

    
    public function __construct()
    {
        parent::__construct();
        // Load Models
        $this->load->model(['master_m', 'entity_m', 'divisi_model', 'dept_model', 'employee_m']);
    }
    

    public function index() {
        echo"adminsApp";
    }

/* -------------------------------------------------------------------------- */
/*                                main function                               */
/* -------------------------------------------------------------------------- */

    /**
     * Admins App Management
     *
     * @return void
     */
    public function adminsApp(){
        // ambil data aplikasi admin
        $data['adminsapp'] = $this->_general_m->getAll('*', 'user_adminsapp', array());
        // ambil detail icon menu
        foreach($data['adminsapp'] as $k => $v){
            $data['adminsapp'][$k]['icon'] = $this->_general_m->getOnce('icon', 'user_menu', array());
        }

        echo(json_encode($data));
        exit;

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['title'];
        $data['load_view'] = 'settings/adminapp_settings_v';
        // $data['custom_styles'] = array('survey_styles');
        // $data['custom_script'] = array('profile/script_profile');
        
        $this->load->view('main_v', $data);
    }
    
    /**
     * Master Data Management
     *
     * @return void
     */
    function masterData(){
        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['masterData'];
		$data['load_view'] = 'settings/masterData_settings_v';
		// additional styles and custom script
        // $data['additional_styles'] = array();
		// $data['custom_styles'] = array();
        // $data['custom_script'] = array();
        
		$this->load->view('main_v', $data);
    }

/* -------------------------------------------------------------------------- */
/*                            master data functions                           */
/* -------------------------------------------------------------------------- */

    function masterData_employee(){
        // employee data
        $data['employe'] = $this->employee_m->getAllEmp();
        // $data['nik'] = $this->Employe_model->getLastNik();
        $data['dept'] = $this->dept_model->getAll();
        $data['divisi'] = $this->divisi_model->getAll();
        $data['entity'] = $this->entity_m->getAll();
        $data['role'] = $this->master_m->getAll_userRole();

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['masterData_employee'];
		$data['load_view'] = 'settings/masterData_employee_settings_v';
		// additional styles and custom script
        // $data['additional_styles'] = array();
		// $data['custom_styles'] = array();
        $data['custom_script'] = array();
        
		$this->load->view('main_v', $data);
    }
}

/* End of file Settings.php */
