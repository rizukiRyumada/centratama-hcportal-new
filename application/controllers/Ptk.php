<?php
// TODO unbudgeted form position add freetext, budgeted choose position on div and dept
// TODO Work location ambil api kota di Indonesia
// TODO di tabel position tambah man power kuota => mpp
// TODO interviewer dari atasan 1 dan atasan 2, dan tambahin dari data lain
// TODO tambah popover di tiap kotak form
// TODO tambah oragnisasi di tab form sebelah job profile


// NOW ambil data taruh di form
// NOW simpan data dalam bentuk post
// NOW masukkan ke database
// NOW buatnya harus OOP

defined('BASEPATH') OR exit('No direct script access allowed');

class Ptk extends SpecialUserAppController {
    // page title
    protected $page_title = array(
        'index' => "Employee Requisition"
    );

    protected $table = array(
        'employee' => 'master_employee',
        'entity' => 'master_entity',
        'department' => 'master_department',
        'division' => 'master_division',
        'position' => 'master_position'
    );
    
    public function __construct() {
        parent::__construct();
        
        // load models
        $this->load->model(['entity_m', 'divisi_model', 'dept_model', 'employee_m', 'posisi_m']);
    }
    

    public function index() {
        

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['index'];
		$data['load_view'] = 'ptk/index_ptk_v';
		// additional styles and custom script
        // $data['additional_styles'] = array();
		// $data['custom_styles'] = array();
        // $data['custom_script'] = array('plugins/jqueryValidation/script_jqueryValidation');
        
		$this->load->view('main_v', $data);
    }

    function createNewForm(){
        // load library form validation
        $this->load->library('form_validation');

        // TODO if checkbox replacement checked set rules required to replacement_who
        // TODO if checkbox job_position_check checked set rules required to internal_who
        // TODO if checkbox job_position_check checked set rules required to job_position_text
        // TODO if checkbox work_exp == 1 set rules to work_exp_years
        // TODO if checkbox interviewer_name diisi, set rules interviewer_job_title, dan sebaliknya

        // set rules form validation
        // array('field' => 'entity', 'label' => 'Entity', 'rules' => 'required'),
        // array('field' => 'password', 'label' => 'Password', 'rules' => 'required', 'errors' => array('required' => 'You must provide a %s.',),),
        $config = array(
                array('field' => 'entity', 'label' => 'Entity', 'rules' => 'required'),
                array('field' => 'job_position', 'label' => 'Job Position', 'rules' => 'required'),
                array('field' => 'job_level', 'label' => 'Job Level', 'rules' => 'required'),
                array('field' => 'division', 'label' => 'Division', 'rules' => 'required'),
                array('field' => 'department', 'label' => 'Departemen', 'rules' => 'required'),
                array('field' => 'work_location', 'label' => 'Work Location', 'rules' => 'required'),
                array('field' => 'budget', 'label' => 'Budget', 'rules' => 'required'),
                array('field' => 'resources', 'label' => 'Resources', 'rules' => 'required'),
                array('field' => 'mpp_req', 'label' => 'MPP Req', 'rules' => 'required'),
                array('field' => 'emp_stats', 'label' => 'Employee Status', 'rules' => 'required'),
                array('field' => 'date_required', 'label' => 'Date Required', 'rules' => 'required'),
                array('field' => 'education', 'label' => 'Education', 'rules' => 'required'),
                array('field' => 'majoring', 'label' => 'Majoring', 'rules' => 'required'),
                array('field' => 'preferred_age', 'label' => 'Preferred Age', 'rules' => 'required'),
                array('field' => 'sex', 'label' => 'Sex', 'rules' => 'required'),
                array('field' => 'work_exp', 'label' => 'Working Experience', 'rules' => 'required'),
                array('field' => 'ska', 'label' => 'Skill, Knowledge, and Abilities', 'rules' => 'required'),
                array('field' => 'req_special', 'label' => 'Special Requirement', 'rules' => 'required'),
                array('field' => 'outline', 'label' => 'Outline Why This Position is necessary', 'rules' => 'required'),
                array('field' => 'main_responsibilities', 'label' => 'Main Responsibilities', 'rules' => 'required'),
                array('field' => 'tasks', 'label' => 'Tasks', 'rules' => 'required')
        );
        $this->form_validation->set_rules($config);

        if($this->form_validation->run() == FALSE){
            // Form Data
            $detail_emp = $this->employee_m->getDeptDivFromNik($this->session->userdata('nik'))[0]; // ambil posisi dianya

            $data['entity'] = $this->_general_m->getAll("*", $this->table['entity'], array()); // ambil entity
            $data['division'] = $this->divisi_model->getOnceById($detail_emp['div_id'])[0]; // ambil division
            $data['department'] = $this->dept_model->getDetailById($detail_emp['dept_id'])[0]; // ambil departemen
            $data['position'] = $this->posisi_m->getAllWhere(array('div_id' => $detail_emp['div_id'], 'dept_id' => $detail_emp['dept_id'])); // position
            $data['emp_status'] = $this->_general_m->getAll('*', 'master_employee_status', array()); // employee status
            $data['education'] = $this->_general_m->getAll('*', 'ptk_education', array()); // education
            // TODO dari javascript MPP dari table position
            $data['data_atasan'] = $this->posisi_m->whoAtasanS(); // ambil data atasan 1 dan 2

            // sorting
            usort($data['entity'], function($a, $b) { return $a['keterangan'] <=> $b['keterangan']; }); // sort berdasarkan title menu
            usort($data['position'], function($a, $b) { return $a['position_name'] <=> $b['position_name']; }); // sort berdasarkan title menu
            // usort($data['emp_status'], function($a, $b) { return $a['status_name'] <=> $b['status_name']; }); // sort berdasarkan title menu
            // usort($data['education'], function($a, $b) { return $a['name'] <=> $b['name']; }); // sort berdasarkan title menu

            // echo(json_encode($data['division']));
            // exit;

            // main data
            $data['sidebar'] = getMenu(); // ambil menu
            $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
            $data['user'] = getDetailUser(); //ambil informasi user
            $data['page_title'] = "Create New Form";
            // $data['page_title'] = "Create New Form ".$this->page_title['index'];
            $data['load_view'] = 'ptk/createNew_ptk_v';
            // additional styles and custom script
            // $data['additional_styles'] = array();
            // $data['custom_styles'] = array();
            $data['custom_script'] = array('plugins/jqueryValidation/script_jqueryValidation', 'plugins/daterange-picker/script_daterange-picker', 'plugins/ckeditor/script_ckeditor.php', 'ptk/script_createNew_ptk');
            
            $this->load->view('main_v', $data);
        } else {
            echo"submitted";
        }
    }

/* -------------------------------------------------------------------------- */
/*                                AJAX Function                               */
/* -------------------------------------------------------------------------- */

    public function ajax_getJobProfile(){
        // ambil data posisi
        
        exit;
    }

/* -------------------------------------------------------------------------- */
/*                               OTHER Functions                              */
/* -------------------------------------------------------------------------- */
    public function ajax_viewer_jobProfile(){
        
    }

    public function viewer_jobProfile($id_posisi){
        // data posisi
        $position_my = $this->posisi_m->getMyPosition();
        $position = $this->posisi_m->getOnceWhere(array('id' => $id_posisi));
        // cek apa dia admin atau userapp admin
        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1){
            // perbolehkan akses bebas
        } else {
            // cek berdasarkan hirarki
            if($position_my['hirarki_org'] == "N-1" || $position_my['hirarki_org'] == "N-2"){
                // cek berdasarkan kesamaan divisi dan department
                if($position_my['div_id'] == $position['div_id'] && $position_my['dept_id'] == $position['dept_id']){
                    // perbolehkan akses
                } else {
                    show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
                    exit;    
                }
                // TODO tambah cek akses per approver
            } else {
                show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
                exit;
            }
        }
        // cek otoritas apa divisi id dan dept idnya sama antara my position dengan id posisi yang dituju
        
        // load Job Profile model
        $this->load->model('jobpro_model');
        // prepare the data
        // $nik = $this->input->get('task');
        // $id_posisi = $this->input->get('id');
        // $data = $this->getDataJP($nik, $id_posisi);

        // ambil data position
        $data   = $this->jobpro_model->getJobProfileData($position);  // get data Job Profile

        // get data status
        // $data['status'] = $this->input->get('status');
        
        // $data['pos'] = $this->Jobpro_model->getAllPosition();
        $data['title'] = 'My Task';
        $data['jp_user'] = $this->db->get_where('master_employee', ['nik' => $this->session->userdata('nik')])->row_array();

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
		// $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu', array('url' => $this->uri->uri_string()))['title'];
        $data['page_title'] = 'Task Job Profile';
        // $data['userApp_admin'] = $this->userApp_admin;
		$data['load_view'] = 'ptk/jobProfile_ptk_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables', 'job_profile/styles_jobprofile.php');
		$data['custom_styles'] = array('jobprofile_styles');
        $data['custom_script'] = array('plugins/datatables/script_datatables', 'plugins/ckeditor/script_ckeditor.php', 'job_profile/script_jobprofile', 'job_profile/script_edit_jobprofile');
        
        
		$this->load->view('main_frame_v', $data);
    }

}

/* End of file Ptk.php */
 