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
        $this->load->model(['dept_model', 'divisi_model', 'employee_m', 'entity_m', 'master_m', 'posisi_m']);
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
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		// $data['custom_styles'] = array();
        $data['custom_script'] = array(
            'plugins/datatables/script_datatables',
            'plugins/jqueryValidation/script_jqueryValidation',
            'settings/script_masterData_employee_settings'
        );
        
		$this->load->view('main_v', $data);
    }

/* -------------------------------------------------------------------------- */
/*                                AJAX FUNCTION                               */
/* -------------------------------------------------------------------------- */
        
    /**
     * get Departement data
     *
     * @return void
     */
    public function ajax_getDepartment(){
        if(!empty($div = $this->input->post('divisi'))){
            //get id divisi
            $div = explode('-', $div);
            // print_r($id_div);
            // exit;
            // $divisi_id = $this->Jobpro_model->getDetail("id", "divisi", array('division' => $this->input->post('divisi')))['id'];
            //ambil data departemen dengan divisi itu
            foreach($this->dept_model->getAll_where(array('div_id' => $div[1])) as $k => $v){
                $data[$k]=$v;
            }
        } else {
            foreach($this->dept_model->getAll() as $k => $v){
                $data[$k]=$v;
            }
        }
        print_r(json_encode($data));
    }

    /**
     * get detail employee with nik post data
     *
     * @return void
     */
    public function ajax_getDetails_employee(){
        $nik = $this->input->post('nik');
        $employe = $this->employee_m->getDetails_employee($nik);

        // $employe['divisi'] = $this->Master_m->getDetail('division', 'divisi', array('id' => $employe['div_id']))['division'];
        $employe['departemen'] = $this->dept_model->ajaxDeptById($employe['dept_id'])['nama_departemen'];

        echo json_encode($employe);
    }
    
    /**
     * ajax_getPosition
     *
     * @return void
     */
    function ajax_getPosition(){
        $div = explode('-', $this->input->post('div'));
        $dept = $this->input->post('dept');
        echo(json_encode($this->posisi_m->getAll_whereSelect('id, position_name', array('div_id' => $div[1], 'dept_id' => $dept))));
    }

}

/* End of file Settings.php */
