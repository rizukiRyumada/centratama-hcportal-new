<?php
// TODO unbudgeted form position add freetext, budgeted choose position on div and dept
// TODO Work location ambil api kota di Indonesia
// TODO di tabel position tambah man power kuota => mpp
// TODO interviewer dari atasan 1 dan atasan 2, dan tambahin dari data lain
// TODO tambah popover di tiap kotak form
// TODO tambah oragnisasi di tab form sebelah job profile

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
        'position' => 'master_position',
        'location' => 'master_location',
        'ptk_status' => 'ptk_status'
    );
    
    public function __construct() {
        parent::__construct();
        
        // load models
        $this->load->model(['entity_m', 'divisi_model', 'dept_model', 'employee_m', 'posisi_m', 'ptk_m']);
    }
    

    public function index() {
        // ptk status
        $data['ptk_status'] = $this->ptk_m->getAll_ptkStatus();

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['index'];
		$data['load_view'] = 'ptk/index_ptk_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		// $data['custom_styles'] = array();
        $data['custom_script'] = array('plugins/datatables/script_datatables', 'ptk/script_index_ptk');
        
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
                // array('field' => 'job_position', 'label' => 'Job Position', 'rules' => 'required'),
                array('field' => 'job_level', 'label' => 'Job Level', 'rules' => 'required'),
                // array('field' => 'division', 'label' => 'Division', 'rules' => 'required'),
                // array('field' => 'department', 'label' => 'Departemen', 'rules' => 'required'),
                // array('field' => 'work_location', 'label' => 'Work Location', 'rules' => 'required'),
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
                // array('field' => 'req_special', 'label' => 'Special Requirement', 'rules' => 'required'),
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
            $data['work_location'] = $this->_general_m->getAll('id, location', $this->table['location'], array());

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
            $data['additional_styles'] = array('plugins/datepicker/styles_datepicker');
            // $data['custom_styles'] = array();
            $data['custom_script'] = array('plugins/jqueryValidation/script_jqueryValidation', 'plugins/datepicker/script_datepicker', 'plugins/ckeditor/script_ckeditor.php', 'ptk/script_createNew_ptk');
            
            $this->load->view('main_v', $data);
        } else {
            // tampilkan post
            // print_r($this->input->post());
            // exit;

            //timestamp id
            $data['id_time'] = time();
            // time modified
            $data['time_modified'] = time();
            // created at
            $status = $this->_general_m->getAll("id", $this->table["ptk_status"], array()); // ambil data status
            $status['0']['time'] = time(); // tambahkan waktu pada status
            $data['status'] = json_encode($status);
            // add status now
            $data['status_now'] = 'ptk_stats-1';
            // Entity
            $data['id_entity'] = $this->input->post('entity');
            // budget
            $data['budget'] = $this->input->post('budget');
            // Job Position
            if($this->input->post('budget') == 0){ // jika unbudgetted
                // masukkan id_posisi jadi nol
                $data['id_pos'] = 0;
                $data['position_other'] = $this->input->post('job_position_text');
            } else {
                // masukkan id_posisi
                $data['id_pos'] = $this->input->post('job_position_choose');
                $data['position_other'] = "";
            }
            // Job Level
            $data['job_level'] = $this->input->post('job_level');
            // Division
            $data['id_div'] = $this->input->post('division');
            // Department
            $data['id_dept'] = $this->input->post('department');
            // Work Location
            if(filter_var($this->input->post('work_location_otherTrigger'), FILTER_VALIDATE_BOOLEAN) == true){
                $work_location = [
                    "other"    => filter_var($this->input->post('work_location_otherTrigger'), FILTER_VALIDATE_BOOLEAN),
                    "location" => $this->input->post('work_location_text')];
                $data['work_location'] = json_encode($work_location);
            } else {
                $work_location = [
                    "other"    => false,
                    "location" => $this->input->post('work_location_choose')];
                $data['work_location'] = json_encode($work_location);
            }
            // replacement
            if(filter_var($this->input->post('replacement'), FILTER_VALIDATE_BOOLEAN) == true){
                $data['replacement'] = $this->input->post('replacement_who');
            } else {
                $data['replacement'] = "";
            }
            // resources
            if($this->input->post('resources') == "int"){
                $data['resources'] = json_encode([
                    "resources"    => $this->input->post('resources'),
                    "internal_who" => $this->input->post('internal_who')
                ]);
            } else {
                $data['resources'] = json_encode([
                    "resources"    => $this->input->post('resources'),
                    "internal_who" => ""]);
            }
            // mpp
            $data['req_mpp'] = $this->input->post('mpp_req');
            // status employement
            $data['id_employee_status'] = $this->input->post('emp_stats');
            // Date Required
            $data['req_date'] = date("o-m-d", strtotime($this->input->post('date_required')));
            // Education
            $data['id_ptk_edu'] = $this->input->post('education');
            // Majoring
            $data['majoring'] = $this->input->post('majoring');
            // Preferred Age
            $data['age'] = $this->input->post('preferred_age');
            // Sex
            $data['sex'] = $this->input->post('sex');
            // Working Experience
            $data['work_exp'] = $this->input->post('work_exp_years');
            // ska
            $data['req_ska'] = $this->input->post('ska');
            // Special Requirement
            $data['req_special'] = $this->input->post('req_special');
            // Outline
            $data['outline'] = $this->input->post('outline');
            // Interviewer
            if(!empty($this->input->post('interviewer_name3')) && !empty($this->input->post('interviewer_position3'))){
                $data['interviewer3'] = json_encode([
                    'name' => $this->input->post('interviewer_name3'),
                    'position' => $this->input->post('interviewer_position3')
                ]);
            }
            // Main Responsibilities
            $data['main_responsibilities'] = $this->input->post('main_responsibilities');
            // Tasks
            $data['tasks'] = $this->input->post('tasks');

            $this->ptk_m->saveForm($data);
            
            // set pesan berhasil disubmit
            
            // balikkan ke halaman awal employee Requisition
        }
    }

/* -------------------------------------------------------------------------- */
/*                                AJAX Function                               */
/* -------------------------------------------------------------------------- */
    
    /**
     * ajax_getMyFormList
     *
     * @return void
     */
    public function ajax_getMyFormList(){
        // get with status
        $getWithStatus = $this->input->post('status');

        // ambil divisi departemen posisi
        $DeptDiv = $this->employee_m->getDeptDivFromNik($this->session->userdata('nik'));

        // date division department status details
        // get form list ptk
        if($getWithStatus == 2){
            if($this->session->userdata('role_id') == 1){
                $data_ptk = $this->ptk_m->getAll_ptkList();
            } else {
                show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
            }
        } else {
            $data_ptk = $this->ptk_m->get_ptkList($getWithStatus);
        }
         // NOW
        foreach ($data_ptk as $key => $value) {
            $data_ptk[$key]["name_div"] = $this->divisi_model->getDetailById($value['id_div'])['division'];
            $data_ptk[$key]["name_dept"] = $this->dept_model->getDetailById($value['id_dept'])['nama_departemen'];
            $data_ptk[$key]["time_modified"] = date("o-m-d", $value['time_modified']);
            $data_ptk[$key]["href"] = base_url('ptk/viewPTK')."?id_entity=".$value['id_entity']."&id_div=".$value['id_div']."&id_dept=".$value['id_dept']."&id_time=".$value['id_time'];
        }

        echo(json_encode([
            'data' => $data_ptk
        ]));
    }

/* -------------------------------------------------------------------------- */
/*                               OTHER Functions                              */
/* -------------------------------------------------------------------------- */
    // cek akses buat frame viewer
    public function cekakses_viewer($position_my, $position){
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
    }

    // viewer job profile
    public function viewer_jobProfile($id_posisi){
        // data posisi
        $position_my = $this->posisi_m->getMyPosition();
        $position = $this->posisi_m->getOnceWhere(array('id' => $id_posisi));
        // cek akses
        $this->cekakses_viewer($position_my, $position);
        
        // load Job Profile model
        $this->load->model('jobpro_model');
        // prepare the data
        // $nik = $this->input->get('task');
        // $id_posisi = $this->input->get('id');
        // $data = $this->getDataJP($nik, $id_posisi);

        // ambil data position
        $data = $this->jobpro_model->getJobProfileData($position);  // get data Job Profile

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
        // $data['page_title'] = 'Task Job Profile';
        // $data['userApp_admin'] = $this->userApp_admin;
		$data['load_view'] = 'ptk/jobProfile_ptk_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables', 'job_profile/styles_jobprofile.php');
		$data['custom_styles'] = array('jobprofile_styles');
        $data['custom_script'] = array('job_profile/script_jobprofile', 'job_profile/script_view_jobprofile');        
        
		$this->load->view('main_frame_v', $data);
    }

    // viewer orgchart
    public function viewer_jobProfile_orgchart($id_posisi) {
        // data posisi
        $position_my = $this->posisi_m->getMyPosition();
        $position = $this->posisi_m->getOnceWhere(array('id' => $id_posisi));
        // cek akses
        $this->cekakses_viewer($position_my, $position);

        // load Job Profile model
        $this->load->model('jobpro_model');

        // ambil dan olah data chart
        // cek jika atasan 1 bukan CEO dan 0
        if($position['id_atasan1'] != 0){
            // if($data_position['id_atasan1'] != 1 && $data_position['id_atasan1'] != 0){
            // Olah data orgchart
            $org_data = $this->jobpro_model->getOrgChartData($position['id']);
        } elseif($position['id_atasan1'] != 0 && $position['div_id'] == 1){
            $org_data = $this->jobpro_model->getOrgChartData($position['id']);
        // } elseif($position['id_atasan1'] == 1){
        //     $org_data = $this->olahDataChart($position['id']);
        } else {
            //siapkan data null
            $org_data[0] = json_encode(null);
            $org_data[1] = json_encode(null);
            $org_data[2] = json_encode(null);
            $org_data[3] = 0;
            $org_data[4] = 0;
        }

        $data['orgchart_data'] = $org_data[0]; //masukkan data orgchart yang sudah diolah ke JSON
        $data['orgchart_data_assistant1'] = $org_data[1];
        $data['orgchart_data_assistant2'] = $org_data[2];
        $data['assistant_atasan1'] = $org_data[3];
        $data['atasan'] = $org_data[4];

        $data['title'] = 'My Task';
        $data['jp_user'] = $this->db->get_where('master_employee', ['nik' => $this->session->userdata('nik')])->row_array();

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
		// $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu', array('url' => $this->uri->uri_string()))['title'];
        // $data['page_title'] = 'Task Job Profile';
        // $data['userApp_admin'] = $this->userApp_admin;
		$data['load_view'] = 'ptk/jobProfile_orgchart_ptk_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables', 'job_profile/styles_jobprofile.php');
		$data['custom_styles'] = array('jobprofile_styles');
        $data['custom_script'] = array('job_profile/script_jobprofile', 'job_profile/script_view_jobprofile');        
        
		$this->load->view('main_frame_v', $data);
    }

}

/* End of file Ptk.php */
 