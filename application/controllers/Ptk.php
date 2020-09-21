<?php
// TODO buat tampilan form viewer
// TODO di tabel position tambah man power kuota => mpp
// TODO tambah popover di tiap kotak form
// TODO buat dia milih replacement karyawan

// NOW tampilan all data, tampilkan semua data form olah dalam bentuk satu table memanjang

defined('BASEPATH') OR exit('No direct script access allowed');

class Ptk extends SpecialUserAppController {
    // page title
    protected $page_title = array(
        'index' => "Employee Requisition"
    );

    // table name list
    protected $table = array(
        'employee' => 'master_employee',
        'entity' => 'master_entity',
        'department' => 'master_department',
        'division' => 'master_division',
        'position' => 'master_position',
        'location' => 'master_location',
        'ptk_status' => 'ptk_status',
        'ptk_status_pj' => 'ptk_status-pj'
    );

    // TODO if checkbox replacement checked set rules required to replacement_who
    // TODO if checkbox job_position_check checked set rules required to internal_who
    // TODO if checkbox job_position_check checked set rules required to job_position_text
    // TODO if checkbox work_exp == 1 set rules to work_exp_years
    // TODO if checkbox interviewer_name diisi, set rules interviewer_job_title, dan sebaliknya

    // set rules form validation
    // array('field' => 'entity', 'label' => 'Entity', 'rules' => 'required'),
    // array('field' => 'password', 'label' => 'Password', 'rules' => 'required', 'errors' => array('required' => 'You must provide a %s.',),),
    protected $config_formValidation = array(
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
    
    public function __construct() {
        parent::__construct();
        
        // load models
        $this->load->model(['entity_m', 'divisi_model', 'dept_model', 'employee_m', 'posisi_m', 'ptk_m']);

        // load library
        $this->load->library('form_validation');
    }
    

    public function index() {
        // ptk data
        $data['my_hirarki'] = $this->posisi_m->getOnceWhere(array('id' => $this->session->userdata('position_id')))['hirarki_org'];
        $data['ptk_status'] = $this->ptk_m->getAll_ptkStatus();

        $position_my = $this->posisi_m->getMyPosition();
        $dataStatusList = array(); $x = 0;
        if($position_my['id'] == 1 || $position_my['id'] == 196){
            $a = $this->_general_m->getAll('id_ptkstatus', $this->table['ptk_status_pj'], array('condition_value' => $position_my['id']));
            foreach($a as $v){
                $dataStatusList[$x] = $v['id_ptkstatus'];
                $x++;
            }
        } elseif($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1){
            $a = $this->_general_m->getAll('id_ptkstatus', $this->table['ptk_status_pj'], array('condition_value' => 'admin'));
            $b = $this->_general_m->getAll('id_ptkstatus', $this->table['ptk_status_pj'], array('condition_value' => $position_my['id']));
            $c = $this->_general_m->getAll('id_ptkstatus', $this->table['ptk_status_pj'], array('condition_value' => $position_my['hirarki_org']));
            
            foreach($a as $v){
                $dataStatusList[$x] = $v['id_ptkstatus'];
                $x++;
            }
            foreach($b as $v){
                $dataStatusList[$x] = $v['id_ptkstatus'];
                $x++;
            }
            foreach($c as $v){
                $dataStatusList[$x] = $v['id_ptkstatus'];
                $x++;
            }
        } elseif($position_my['hirarki_org'] == "N" || $position_my['hirarki_org'] == "N-1" || $position_my['hirarki_org'] == "N-2"){
            $a = $this->_general_m->getAll('id_ptkstatus', $this->table['ptk_status_pj'], array('condition_value' => $position_my['hirarki_org']));
            foreach($a as $v){
                $dataStatusList[$x] = $v['id_ptkstatus'];
                $x++;
            }
        } else {
            show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
            exit;
        }

        // my task status
        $data['mytask'] = json_encode(array('my_task' => $dataStatusList));

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['index'];
		$data['load_view'] = 'ptk/index_ptk_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		// $data['custom_styles'] = array();
        $data['custom_script'] = array(
            'plugins/datatables/script_datatables', 
            'ptk/script_index_ptk',
            'ptk/script_ajax_timelineStatusHistory_ptk'
        );
        
		$this->load->view('main_v', $data);
    }

    function createNewForm(){
        // get my hirarki
        $my_hirarki = $this->posisi_m->getOnceWhere(array('id' => $this->session->userdata('position_id')))['hirarki_org'];
        // cekakses hanya admin, superadmin, N-2 dan N-1 yang bisa akses
        if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1){
            // have all access
        } else {
            // cek akses berdasarkan hirarki N-1 dan N-2 yang bisa akses
            if($my_hirarki == "N-1" || $my_hirarki == "N-2"){
                // have access
            } else {
                show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
            }
        }
        
        $this->form_validation->set_rules($this->config_formValidation); // load settings
        if($this->form_validation->run() == FALSE){ // jalankan validasi
            // Form Data
            $detail_emp = $this->employee_m->getDeptDivFromNik($this->session->userdata('nik')); // ambil posisi dianya

            // Form Data
            if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1){
                $data['division'] = $this->divisi_model->getDivisi(); // ambil division
            } else {
                $data['division'] = $this->divisi_model->getOnceById($detail_emp['div_id'])[0]; // ambil division
                $data['department'] = $this->dept_model->getDetailById($detail_emp['dept_id']); // ambil departemen
                $data['position'] = $this->posisi_m->getAllWhere(array('div_id' => $detail_emp['div_id'], 'dept_id' => $detail_emp['dept_id'])); // position
            }

            // form data
            $data['entity'] = $this->_general_m->getAll("*", $this->table['entity'], array()); // ambil entity
            $data['emp_status'] = $this->_general_m->getAll('*', 'master_employee_status', array()); // employee status
            $data['education'] = $this->_general_m->getAll('*', 'ptk_education', array()); // education
            // TODO dari javascript MPP dari table position
            $data['data_atasan'] = $this->posisi_m->whoMyAtasanS(); // ambil data atasan 1 dan 2
            $data['work_location'] = $this->_general_m->getAll('id, location', $this->table['location'], array());
            $data['position_my'] = $this->posisi_m->getMyPosition();

            // sorting
            usort($data['entity'], function($a, $b) { return $a['keterangan'] <=> $b['keterangan']; }); // sort berdasarkan title menu
            if(!empty($data['position'])){
                usort($data['position'], function($a, $b) { return $a['position_name'] <=> $b['position_name']; }); // sort berdasarkan title menu
            }
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
            $data['custom_script'] = array(
                'plugins/jqueryValidation/script_jqueryValidation', 
                'plugins/datepicker/script_datepicker', 
                'plugins/ckeditor/script_ckeditor.php', 
                'ptk/script_formvariable_ptk',
                'ptk/script_createNew_ptk',
                'ptk/script_validator_ptk',
                'ptk/script_submitValidator_ptk');
            
            $this->load->view('main_v', $data);
        } else {
            // cekakses hanya admin, superadmin, N-2 dan N-1 yang bisa akses
            if($my_hirarki == "N-2") {
                $status_now = 'ptk_stats-1'; //  set status drafted
                $title = "Saved"; $msg = "Your form has been saved.";
            } elseif($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1 || $my_hirarki == "N-1") {
                if($this->input->post('action') == "save"){
                    $status_now = 'ptk_stats-1'; // set status saved
                    $title = "Saved"; $msg = "Your form has been saved.";
                } elseif ($this->input->post('action') == "submit"){
                    $status_now = 'ptk_stats-2'; // set status proposed
                    $title = "Submitted"; $msg = "Your form has been submited.";
                } else {
                    show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
                }
            } else {
                show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
            }

            // persiapan data status
            // $status = $this->_general_m->getAll("id", $this->table["ptk_status"], array()); // ambil data status
            
            $temp_status_data = array();

            // buat status data
            $name_signed = $this->_general_m->getOnce('emp_name', $this->table['employee'], array('nik' => $this->session->userdata('nik')))['emp_name'];
            $nik_signed = $this->session->userdata('nik');
            $status_data = $this->process_statusData($status_now, $temp_status_data, $name_signed, $nik_signed)['status_data'];

            // save form
            $data = $this->saveForm_post($status_now, $status_data);
            $this->saveForm_new($data);
            
            // set pesan berhasil disubmit
            $this->session->set_userdata('msg', array(
                'icon' => 'success',
                'title' => $title,
                'msg' => $msg
            ));
            
            // balikkan ke halaman awal employee Requisition
            redirect('ptk');
        }
    }

/* -------------------------------------------------------------------------- */
/*                                AJAX Function                               */
/* -------------------------------------------------------------------------- */
    
    /**
     * get ajax form list for index page
     *
     * @return void
     */
    public function ajax_getMyFormList(){
        // get with status
        $getWithStatus = $this->input->post('status');

        // ambil divisi departemen posisi
        $deptDiv = $this->employee_m->getDeptDivFromNik($this->session->userdata('nik'));
        $position_my = $this->posisi_m->getMyPosition(); // get my position data

        // prepare variable
        $statuses = array();

        // date division department status details
        // get form list ptk
        if($getWithStatus == "2"){
            if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1){
                $data_ptk = $this->ptk_m->getAll_ptkList();
            } else {
                show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
            }
        } elseif($getWithStatus == "0" || $getWithStatus == "1") {
            if($position_my['id'] == 1 || $position_my['id'] == 196 || $this->userApp_admin == 1 || $this->session->userdata('role_id') == 1){
                $data_ptk = $this->ptk_m->get_ptkList(array(
                    // 'type' => $getWithStatus
                ));  
            } elseif($position_my['hirarki_org'] == "N"){
                $data_ptk = $this->ptk_m->get_ptkList(array(
                    // 'type' => $getWithStatus,
                    'id_div' => $deptDiv['div_id']
                ));    
            } elseif($position_my['hirarki_org'] == "N-1" || $position_my['hirarki_org'] == "N-2"){
                $data_ptk = $this->ptk_m->get_ptkList(array(
                    // 'type' => $getWithStatus,
                    'id_div' => $deptDiv['div_id'],
                    'id_dept' => $deptDiv['dept_id']
                ));
            } else {
                show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
            }
        } else {
            if(!empty($getWithStatus)) {
                $statuses = json_decode($getWithStatus, true);
                $statuses = $statuses['my_task'];
                
                $temp_ptk = array();
                if($position_my['id'] == 1 || $position_my['id'] == 196 || $this->userApp_admin == 1 || $this->session->userdata('role_id') == 1){
                    foreach($statuses as $k => $v){
                        $temp_ptk[$k] = $this->ptk_m->get_ptkList(array(
                            'status_now' => $v
                        ));
                    }
                } elseif($position_my['hirarki_org'] == "N"){
                    foreach($statuses as $k => $v){
                        $temp_ptk[$k] = $this->ptk_m->get_ptkList(array(
                            'status_now' => $v,
                            'id_div' => $deptDiv['div_id']
                        ));
                    }
                } elseif($position_my['hirarki_org'] == "N-1" || $position_my['hirarki_org'] == "N-2"){
                    foreach($statuses as $k => $v){
                        $temp_ptk[$k] = $this->ptk_m->get_ptkList(array(
                            'status_now' => $v,
                            'id_div' => $deptDiv['div_id'],
                            'id_dept' => $deptDiv['dept_id']
                        ));
                    }
                } else {
                    show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
                }

                $data_ptk = array();
                foreach($temp_ptk as $k => $v){
                    if($k == array_key_first($temp_ptk)){
                        $data_ptk = $v;
                    } else {
                        $data_ptk = array_merge($data_ptk, $v);
                    }
                }
            } else {
                show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                exit;
            }
        }

        // ambil informasi tambahan
        foreach ($data_ptk as $key => $value) {
            $data_ptk[$key]["name_div"] = $this->divisi_model->getDetailById($value['id_div'])['division'];
            $data_ptk[$key]["name_dept"] = $this->dept_model->getDetailById($value['id_dept'])['nama_departemen'];
            $data_ptk[$key]["time_modified"] = date("o-m-d", $value['time_modified']);
            $data_ptk[$key]["href"] = base_url('ptk/viewPTK')."?id_entity=".$value['id_entity']."&id_div=".$value['id_div']."&id_dept=".$value['id_dept']."&id_pos=".$value['id_pos']."&id_time=".$value['id_time'];
            $data_ptk[$key]['status_now'] = $value['status_now']."<~>".json_encode(array($value['id_entity'], $value['id_div'], $value['id_dept'], $value['id_pos'], $value['id_time']));
        }

        // olah status beri nama
        $myStatus = array();
        if($getWithStatus == "0" || $getWithStatus == "1" || $getWithStatus == "2"){
            // ambil semua status
            $statuses = $this->ptk_m->getAll_ptkStatus();
            foreach($statuses as $k => $v){
                $myStatus[$k]['id'] = $v['id'];
                $myStatus[$k]['name'] = $v['status_text'];
            }
        } elseif(!empty($getWithStatus)) {
            foreach($statuses as $k => $v){
                $myStatus[$k]['id'] = $v;
                $myStatus[$k]['name'] = $this->ptk_m->getDetail_ptkStatusDetailByStatusId($v)['status_text'];
            }
        } else {
            show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
            exit;
        }

        echo(json_encode([
            'statuses' => $myStatus,
            'data' => $data_ptk
        ]));
    }
    
    /**
     * Function for ajax to get data form
     *
     * @return void
     */
    function ajax_getPTKdata(){
        // data posisi
        $position_my = $this->posisi_m->getMyPosition();
        $position = $this->posisi_m->getOnceWhere(array('id' => $this->input->post('id_pos')));
        // cek akses
        $this->cekakses_ptk($position_my, $position);

        $data = $this->ptk_m->getDetail_ptk(
            $this->input->post('id_entity'),
            $this->input->post('id_div'),
            $this->input->post('id_dept'),
            $this->input->post('id_pos'),
            $this->input->post('id_time')
        );
        // ubah bentuk date
        $data['req_date'] = date("d-m-o", strtotime($data['req_date']));

        // print_r($data);
        // exit;

        echo(json_encode([
            "data" => $data
        ]));
    }
    
    /**
     * ajax_getStatusData
     *
     * @return void
     */
    function ajax_getStatusData(){
        $id_entity = $this->input->post('id_entity');
        $id_div = $this->input->post('id_div');
        $id_dept = $this->input->post('id_dept');
        $id_pos = $this->input->post('id_pos');
        $id_time = $this->input->post('id_time');

        // ambil data status
        $temp_status_data = $this->ptk_m->getDetail_ptkStatus($id_entity, $id_div, $id_dept, $id_pos, $id_time);

        $status_data = array_reverse($temp_status_data);

        // lengkapi data
        foreach($status_data as $k => $v){
            $status_data[$k]['time'] = date("j M o<~>H:i", $v['time']);
            // get status data attribute
            $el = $this->ptk_m->getDetail_ptkStatusDetailByStatusId($v['id']);
            $status_data[$k]['status_name'] = $el['status_name'];
            $status_data[$k]['css_color'] = $el['css_color'];
            $status_data[$k]['icon'] = $el['icon'];
        }

        echo(json_encode($status_data));
    }

/* -------------------------------------------------------------------------- */
/*                               OTHER Functions                              */
/* -------------------------------------------------------------------------- */
    // cek akses buat frame viewer
    public function cekakses_ptk($position_my, $position){
        // cek apa dia admin atau userapp admin
        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1 || $position_my['id'] == 196 || $position_my['id'] == 1){
            // perbolehkan akses bebas
        } else {
            // cek berdasarkan hirarki
            if($position_my['hirarki_org'] == "N"){
                if($position_my['div_id'] == $position['div_id']){
                    // perbolehkan akses
                } else {
                    show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
                    exit;
                }
            } elseif($position_my['hirarki_org'] == "N-1" || $position_my['hirarki_org'] == "N-2") {
                // cek berdasarkan kesamaan divisi dan department
                if($position_my['div_id'] == $position['div_id'] && $position_my['dept_id'] == $position['dept_id']){
                    // perbolehkan akses
                } else {
                    show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
                    exit;
                }
            } else {
                show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
                exit;
            }
        }
        // cek otoritas apa divisi id dan dept idnya sama antara my position dengan id posisi yang dituju
    }
    
    /**
     * function used for save ptk form
     *
     * @return void
     */
    public function saveForm_post($status_now, $status_data) {
        //timestamp id
        $data['id_time'] = time();
        // time modified
        $data['time_modified'] = time();
        // created at
        $data['status'] = json_encode($status_data);
        // add status now
        $data['status_now'] = $status_now;
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

        return $data;
    }
    
    /**
     * saveForm_new
     *
     * @param  mixed $data
     * @return void
     */
    function saveForm_new($data){
        // save form to database
        $this->ptk_m->saveForm($data);
    }
    
    /**
     * updateForm
     *
     * @param  mixed $data
     * @return void
     */
    function updateForm($data, $id_entity, $id_div, $id_dept, $id_pos, $id_time){
        // save form to database
        $this->ptk_m->updateForm($data, array(
            'id_entity' => $id_entity,
            'id_div' => $id_div,
            'id_dept' => $id_dept,
            'id_pos' => $id_pos,
            'id_time' => $id_time
        ));
    }

    // viewer job profile
    public function viewer_jobProfile($id_posisi){
        // data posisi
        $position_my = $this->posisi_m->getMyPosition();
        $position = $this->posisi_m->getOnceWhere(array('id' => $id_posisi));
        // cek akses
        $this->cekakses_ptk($position_my, $position);
        
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
		$data['load_view'] = '_frame/job_profile/jobProfile_viewer_v';
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
        $this->cekakses_ptk($position_my, $position);

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
		$data['load_view'] = '_frame/job_profile/jobProfile_orgchart_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables', 'job_profile/styles_jobprofile.php');
		$data['custom_styles'] = array('jobprofile_styles');
        $data['custom_script'] = array('job_profile/script_jobprofile', 'job_profile/script_view_jobprofile');        
        
		$this->load->view('main_frame_v', $data);
    }
    
    /**
     * PTK viewer
     *
     * @return void
     */
    function viewPTK(){
        // ptk data
        $data['id_entity'] = $this->input->get('id_entity');
        $data['id_div']    = $this->input->get('id_div');
        $data['id_dept']   = $this->input->get('id_dept');
        $data['id_pos']    = $this->input->get('id_pos');
        $data['id_time']   = $this->input->get('id_time');

        // cek apa ada datanya
        if($this->ptk_m->getRow_form($data['id_entity'], $data['id_div'], $data['id_dept'], $data['id_pos'], $data['id_time']) < 1){
            show_error('Sorry there is no data.', 404, 'Not Found');
            exit;
        }
    
        // data posisi
        $position_my = $this->posisi_m->getMyPosition();
        $position    = array("div_id" => $data['id_div'], "dept_id" => $data['id_dept']);
        // cek akses
        $this->cekakses_ptk($position_my, $position);

        // data useradmin app
        $data['userApp_admin'] = $this->userApp_admin;
        // form data
        $data['position_my'] = $position_my; // my position data
        $data['status_form'] = $this->ptk_m->getDetail_ptkStatusNow($data['id_entity'], $data['id_div'], $data['id_dept'], $data['id_pos'], $data['id_time']); // get status id
        $data['status_detail'] = $this->ptk_m->getDetail_ptkStatusDetailByStatusId($data['status_form']); // get status details
        $data['entity']     = $this->_general_m->getAll("*", $this->table['entity'], array()); // ambil entity
        $data['division']   = $this->divisi_model->getOnceById($data['id_div'])[0]; // ambil division
        $data['department'] = $this->dept_model->getDetailById($data['id_dept']); // ambil departemen
        $data['position']   = $this->posisi_m->getAllWhere(array('div_id' => $data['id_div'], 'dept_id' => $data['id_dept'])); // position
        $data['emp_status'] = $this->_general_m->getAll('*', 'master_employee_status', array()); // employee status
        $data['education']  = $this->_general_m->getAll('*', 'ptk_education', array()); // education
        // TODO dari javascript MPP dari table position
        if($data['id_pos'] != 0){
            $data['data_atasan'] = $this->posisi_m->whoAtasanS($data['id_pos']); // ambil data atasan 1 dan 2
        } else {
            $data['data_atasan'] = "";
        }
        $data['work_location'] = $this->_general_m->getAll('id, location', $this->table['location'], array());
        // sorting
        usort($data['entity'], function($a, $b) { return $a['keterangan'] <=> $b['keterangan']; }); // sort berdasarkan title menu
        usort($data['position'], function($a, $b) { return $a['position_name'] <=> $b['position_name']; }); // sort berdasarkan title menu

        // main data
        $data['sidebar']    = getMenu();        // ambil menu
        $data['breadcrumb'] = getBreadCrumb();  // ambil data breadcrumb
        $data['user']       = getDetailUser();  //ambil informasi user
        $data['page_title'] = "View PTK";
        // $data['page_title'] = "Create New Form ".$this->page_title['index'];
        $data['load_view'] = 'ptk/viewPTK_ptk_v';
        // additional styles and custom script
        $data['additional_styles'] = array('plugins/datepicker/styles_datepicker');
        // $data['custom_styles'] = array();
        $data['custom_script'] = array(
            // 'plugins/jqueryValidation/script_jqueryValidation', 
            'plugins/datepicker/script_datepicker', 
            'plugins/ckeditor/script_ckeditor.php', 
            'ptk/script_formvariable_ptk',
            'ptk/script_viewer_ptk',
            'ptk/script_updateStatus_ptk',
            'ptk/script_validator_ptk',
            'ptk/script_submitValidator_ptk',
            'ptk/script_ajax_timelineStatusHistory_ptk'
        );
        
        $this->load->view('main_v', $data);
    }
    
    /**
     * update status form ptk
     *
     * @return void
     */
    function updateStatus(){
        // get form info
        $id_entity = $this->input->post('id_entity');
        $id_div = $this->input->post('id_div');
        $id_dept = $this->input->post('id_dept');
        $id_pos = $this->input->post('id_pos');
        $id_time = $this->input->post('id_time');
        $action = $this->input->post('action');
        $pesan_revisi = $this->input->post('pesan_revisi');
        $status_now = $this->input->post('status_now'); // ambil id status
        $status_data = $this->ptk_m->getDetail_ptkStatus($id_entity, $id_div, $id_dept, $id_pos, $id_time); // get status data
        $name_signed = $this->_general_m->getOnce('emp_name', $this->table['employee'], array('nik' => $this->session->userdata('nik')))['emp_name'];
        $nik_signed = $this->session->userdata('nik');

        // cek apa ada datanya
        if($this->ptk_m->getRow_form($id_entity, $id_div, $id_dept, $id_pos, $id_time) < 1){
            show_error('Sorry there is no data.', 404, 'Not Found');
            exit;
        }

        $position_my = $this->posisi_m->getMyPosition(); // get my position data
        $position    = array("div_id" => $id_div, "dept_id" => $id_dept);
        $this->cekakses_ptk($position_my, $position); // cek akses

        $this->form_validation->set_rules($this->config_formValidation); // load settings
        if($this->form_validation->run() == FALSE){ // jalankan validasi
            // tampilkan pesan error
            $this->session->set_userdata('msg_swal',
                array(
                    'icon' => 'error',
                    'title' => 'Form Validation Error?!',
                    'msg' => 'Sorry there is an error on form validation please check again and please enable javascript to showing what form needed to be fill.'
                )
            );
            
            header('location: ' . base_url('ptk/testStatus')."?id_entity=$id_entity&id_div=$id_div&id_dept=$id_dept&id_pos=$id_pos&id_time=$id_time");
        } else {
            // cek posisi dan status dari form lalu ubah status datanya, dan tambah pesan revisi
            if($position_my['id'] == 1){
                if($status_now == "ptk_stats-B"){
                    // cek action
                    if($action == 0){
                        $new_statsData = $this->process_statusData("ptk_stats-8", $status_data, $name_signed, $nik_signed);
                    } elseif($action == 1){
                        $new_statsData = $this->process_statusData("ptk_stats-A", $status_data, $name_signed, $nik_signed);
                    } elseif($action == 2){
                        $new_statsData = $this->process_statusData("ptk_stats-C", $status_data, $name_signed, $nik_signed, $pesan_revisi);
                    } else {
                        show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                        exit;
                    }
                } else {
                    show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                    exit;
                }
            } elseif($position_my['id'] == 196) {
                if($status_now == "ptk_stats-4"){
                    // cek action
                    if($action == 0){
                        $new_statsData = $this->process_statusData("ptk_stats-7", $status_data, $name_signed, $nik_signed);
                    } elseif($action == 1){
                        $new_statsData = $this->process_statusData("ptk_stats-B", $status_data, $name_signed, $nik_signed);
                    } elseif($action == 2){
                        $new_statsData = $this->process_statusData("ptk_stats-D", $status_data, $name_signed, $nik_signed, $pesan_revisi);
                    } else {
                        show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                        exit;
                    }
                } else {
                    show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                    exit;
                }
            } elseif($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1) {
                if($status_now == "ptk_stats-3"){
                    // cek action
                    if($action == 0){
                        $new_statsData = $this->process_statusData("ptk_stats-5", $status_data, $name_signed, $nik_signed);
                    } elseif($action == 1){
                        $new_statsData = $this->process_statusData("ptk_stats-4", $status_data, $name_signed, $nik_signed);
                    } elseif($action == 2){
                        $new_statsData = $this->process_statusData("ptk_stats-E", $status_data, $name_signed, $nik_signed, $pesan_revisi);
                    } else {
                        show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                        exit;
                    }
                } else {
                    show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                    exit;
                }
            } elseif($position_my['hirarki_org'] == "N") {
                if($status_now == "ptk_stats-2"){
                    // cek action
                    if($action == 0){
                        $new_statsData = $this->process_statusData("ptk_stats-6", $status_data, $name_signed, $nik_signed);
                    } elseif($action == 1){
                        $new_statsData = $this->process_statusData("ptk_stats-3", $status_data, $name_signed, $nik_signed);
                    } elseif($action == 2){
                        $new_statsData = $this->process_statusData("ptk_stats-F", $status_data, $name_signed, $nik_signed, $pesan_revisi);
                    } else {
                        show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                        exit;
                    }
                } else {
                    show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                    exit;
                }
            } elseif($position_my['hirarki_org'] == "N-1") {
                // cek action
                if($action == 1){
                    $new_statsData = $this->process_statusData("ptk_stats-2", $status_data, $name_signed, $nik_signed);
                } elseif($action == 3){
                    $new_statsData = $this->process_statusData("ptk_stats-1", $status_data, $name_signed, $nik_signed);
                } else {
                    show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                    exit;
                }    
            } else {
                show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                exit;
            }
            // simpan ke dalam database
            $data = $this->saveForm_post($new_statsData['status_new'], $new_statsData['status_data']);
            $this->updateForm($data, $id_entity, $id_div, $id_dept, $id_pos, $id_time);

            // siapkan notifikasi
            if($action == 0){
                $icon = "error"; $title = "Rejected"; $msg = "You have Rejected this form.";
            } elseif($action == 1){
                if($status_now == "ptk_stats-1"){
                    $icon = "success"; $title = "Proposed"; $msg = "You have Proposed this form.";
                } else {
                    $icon = "success"; $title = "Accepted"; $msg = "You have Accepted this form.";
                }
            } elseif($action == 2){
                $icon = "warning"; $title = "Requested to Revise"; $msg = "You have requested to Revise this form.";
            } elseif($action == 3){
                $icon = "success"; $title = "Saved"; $msg = "You have Saved this form.";
            }
            $this->session->set_userdata('msg', array(
                'icon' => $icon,
                'title' => $title,
                'msg' => $msg
            ));
            // balikkan ke halaman ptk
            redirect('ptk');
        }
    }

    // Print Function
    function printPTK(){
        redirect('maintenance');
    }

/* -------------------------------------------------------------------------- */
/*                                Mini Function                               */
/* -------------------------------------------------------------------------- */
    function process_statusData($status_new, $status_data, $name_signed, $nik_signed, $pesan_revisi = ""){
        if($status_data == array()){
            $status_data[0] = array(
                'id' => $status_new,
                'time' => time(),
                'signedby' => $name_signed,
                'signedbynik' => $nik_signed
            );
        } else {
            $index = array_key_last($status_data)+1; // set index for new status
            $status_data[$index] = array(
                'id' => $status_new,
                'time' => time(),
                'signedby' => $name_signed,
                'signedbynik' => $nik_signed
            );
            // masukkan pesan revisi jika ada
            if(!empty($pesan_revisi)){
                $status_data[$index]['pesan_revisi'] = $pesan_revisi;
            }
        }

        return array(
            'status_new' => $status_new, 
            'status_data' => $status_data);
    }

}

/* End of file Ptk.php */
 