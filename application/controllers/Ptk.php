<?php
// TODO buat tampilan form viewer
// TODO di tabel position tambah man power kuota => mpp
// TODO tambah popover di tiap kotak form
// TODO buat dia milih replacement karyawan


defined('BASEPATH') OR exit('No direct script access allowed');

class Ptk extends SpecialUserAppController {
    // page title
    protected $page_title = array(
        'index' => "Employee Requisition",
        'form' => "Employee Requisition Form"
    );
    protected $id_menu = 11;

    // table name list
    protected $table = array(
        'admin' => 'user_userapp_admins',
        'employee' => 'master_employee',
        'employee_status' => 'employee_status',
        'entity' => 'master_entity',
        'department' => 'master_department',
        'division' => 'master_division',
        'position' => 'master_position',
        'location' => 'master_location',
        'joblevel' => 'master_level',
        'ptk_status' => 'ptk_status',
        'ptk_status_pj' => 'ptk_status-pj',
        'ptk_education' => 'ptk_education'
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
            // array('field' => 'job_level', 'label' => 'Job Level', 'rules' => 'required'),
            // array('field' => 'division', 'label' => 'Division', 'rules' => 'required'),
            // array('field' => 'department', 'label' => 'Departemen', 'rules' => 'required'),
            // array('field' => 'work_location', 'label' => 'Work Location', 'rules' => 'required'),
            array('field' => 'budget', 'label' => 'Budget', 'rules' => 'required'),
            array('field' => 'resources', 'label' => 'Resources', 'rules' => 'required'),
            array('field' => 'mpp_req', 'label' => 'MPP Req', 'rules' => 'required'),
            // array('field' => 'emp_stats', 'label' => 'Employee Status', 'rules' => 'required'),
            array('field' => 'date_required', 'label' => 'Date Required', 'rules' => 'required'),
            // array('field' => 'education', 'label' => 'Education', 'rules' => 'required'),
            // array('field' => 'majoring', 'label' => 'Majoring', 'rules' => 'required'),
            // array('field' => 'preferred_age', 'label' => 'Preferred Age', 'rules' => 'required'),
            // array('field' => 'sex', 'label' => 'Sex', 'rules' => 'required'),
            // array('field' => 'work_exp', 'label' => 'Working Experience', 'rules' => 'required'),
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
        $this->load->library(['form_validation', 'tablename']);
        // ambil nama table yang terupdate
        $this->table['position'] = $this->tablename->get($this->table['position']);

        // Token Checker buat ngapus token
        if(!empty($this->session->userdata('token'))){
            $data_token = $this->_general_m->getOnce('data', 'user_token', array('token' => $this->session->userdata('token')))['data'];
            $data_token = json_decode($data_token, true);

            $whoami = $this->employee_m->getDetails_employee($this->session->userdata('nik'));

            $is_allowed = 0; // penanda akses token
            if(is_array($data_token['email_penerima'])){
                // cek apa email user sama dengan yang tercantum di database
                foreach($data_token['email_penerima'] as $v){ // liat satu per satu email
                    if($whoami['email'] == $v){
                        $is_allowed++;
                    }
                }
            } else {
                // cek apa email user sama dengan yang tercantum di database
                if($whoami['email'] == $data_token['email_penerima']){
                    $is_allowed++;
                }
            }
            
            // jika cek is_allowed benar datanya
            if($is_allowed > 0){
                // hapus token dari database
                $this->_general_m->delete('user_token', array('index' => 'token', 'data' => $this->session->userdata('token')));
            } else {
                // set toastr notification
                $this->session->set_userdata('msg', array(
                    'icon' => 'error',
                    'title' => 'Error',
                    'msg' => 'The link token is not yours!'
                ));
            }
            // hapus session token
            $this->session->unset_userdata('token');            
        }
    }

    public function index() {
        // ptk data
        $data['my_hirarki'] = $this->posisi_m->getOnceWhere(array('id' => $this->session->userdata('position_id')))['hirarki_org'];
        $data['ptk_status'] = $this->ptk_m->getAll_ptkStatus();

        $position_my = $this->posisi_m->getMyPosition();
        $dataStatusList = array(); $x = 0;
        if((int)$position_my['id'] == 1 || (int)$position_my['id'] == 196){
            $a = $this->_general_m->getAll('id_ptkstatus', $this->table['ptk_status_pj'], array('condition_value' => (int)$position_my['id']));
            foreach($a as $v){
                $dataStatusList[$x] = $v['id_ptkstatus'];
                $x++;
            }
        } elseif($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1){
            $a = $this->_general_m->getAll('id_ptkstatus', $this->table['ptk_status_pj'], array('condition_value' => 'admin'));
            $b = $this->_general_m->getAll('id_ptkstatus', $this->table['ptk_status_pj'], array('condition_value' => (int)$position_my['id']));
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
            'plugins/daterange-picker/script_daterange-picker',
            'ptk/script_index_ptk',
            'ptk/script_ajax_timelineStatusHistory_ptk'
        );
        
		$this->load->view('main_v', $data);
    }
    
    /**
     * this function used for create new form
     *
     * @return void
     */
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
            $detail_emp = $this->employee_m->getDeptDivFromNik($this->session->userdata('nik')); // ambil informasi departemen dan divisi dianya

            // Form Data
            if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1){
                $data['division'] = $this->divisi_model->getDivisi(); // ambil division
            } else {
                $data['division'] = $this->divisi_model->getOnceById($detail_emp['div_id']); // ambil division
                $data['department'] = $this->dept_model->getDetailById($detail_emp['dept_id']); // ambil departemen
                $data['position'] = $this->posisi_m->getAllWhere(array('div_id' => $detail_emp['div_id'], 'dept_id' => $detail_emp['dept_id'])); // position
            }

            // data useradmin app
            $data['userApp_admin'] = $this->userApp_admin;

            // form data
            $data['entity'] = $this->entity_m->getAll_notAtAll(); // ambil entity
            $data['emp_status'] = $this->_general_m->getAll('*', 'employee_status', array()); // employee status
            $data['education'] = $this->_general_m->getAll('*', 'ptk_education', array()); // education
            $data['is_edit'] = 1;
            $data['master_level'] = $this->ptk_m->get_masterLevel(); // ambil master level
            $data['position_my'] = $this->posisi_m->getMyPosition(); // ambil data my position
            $data['work_location'] = $this->_general_m->getAll('id, location', $this->table['location'], array());

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
            $data['page_title'] = $this->page_title['form'];
            $data['load_view'] = 'ptk/createNew_ptk_v';
            // additional styles and custom script
            $data['additional_styles'] = array(
                'plugins/datatables/styles_datatables',
                'plugins/datepicker/styles_datepicker',
                'plugins/jquery-uploadfile/styles'
            );
            // $data['custom_styles'] = array();
            $data['custom_script'] = array(
                'plugins/datatables/script_datatables', 
                'plugins/datepicker/script_datepicker', 
                'plugins/ckeditor/script_ckeditor',
                'plugins/jquery-uploadfile/script',
                'ptk/script_formvariable_ptk',
                'ptk/script_createNew_ptk',
                'ptk/script_validator_ptk',
                'ptk/script_submitValidator_ptk');
            
            $this->load->view('main_v', $data);
        } else {
            $status_now = ""; // siapkan variabel
            // cekakses hanya admin, superadmin, N-2 dan N-1 yang bisa akses
            if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1 || $my_hirarki == "N-1") {
                if($this->input->post('action') == 3){
                    $status_now = 'ptk_stats-1'; // set status saved
                    $title = "Drafted"; $msg = "New form has been created.";
                } elseif ($this->input->post('action') == 1){
                    // cek jika divisinya HC
                    if((int)$this->input->post('division') == 6){
                        $status_now = 'ptk_stats-3'; // set status proposed
                    } else {
                        $status_now = 'ptk_stats-2'; // set status proposed
                    }
                    $title = "Proposed"; $msg = "The form has been Proposed.";
                } else {
                    show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                }
            } elseif($my_hirarki == "N-2") {
                $status_now = 'ptk_stats-1'; //  set status drafted
                $title = "Drafted"; $msg = "New form has been created.";
            } else {
                show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
            }

            // kirim remail notfikasi'
            if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1 || $my_hirarki == "N-1") {
                if ($this->input->post('action') == 1){ // jika actionnya submit
                    // cek jika divisinya HC
                    if((int)$this->input->post('division') == 6){
                        // kirim email ke OD
                        $admins_nik = $this->_general_m->getAll('nik', $this->table['admin'], array('id_menu' => $this->id_menu));
                        foreach($admins_nik as $k => $v){ // ambil data admins 1 per 1
                            $admins[$k] = $this->employee_m->getDetails_employee($v['nik']);
                        }
                        // jika adminnya lebih dari satu
                        if(count($admins) > 1){
                            $email_penerima = array(); $temp_namaPenerima = array();
                            foreach($admins as $k => $v){
                                $email_penerima[$k] = $v['email'];
                                $temp_namaPenerima[$k] = $v['emp_name'];
                            }
                            $penerima_nama = implode(', ', $temp_namaPenerima);
                        } else { // jika adminnya cuma satu
                            $email_penerima = $admins[0]['email'];
                            $penerima_nama = $admins[0]['emp_name'];
                        }
                        $email_cc = "";
                    } else {
                        // kirim email ke divhead
                        $email_data = $this->divisi_model->get_divHead($this->input->post('division'));
                        // email data
                        $email_penerima = $email_data['email'];
                        $email_cc = "";
                        $penerima_nama = $email_data['emp_name'];
                    }
                }
            } elseif($my_hirarki == "N-2") {
                // kirim email ke N-1
                $position_my = $this->posisi_m->getMyPosition();
                $email_employee = $this->posisi_m->whoIsOnThisPosition($position_my['id_approver1']);
                // jika N-1 nya lebih dari satu
                if(count($email_employee) > 1){
                    $email_penerima = array(); $temp_namaPenerima = array();
                    foreach($email_employee as $k => $v){
                        $email_penerima[$k] = $v['email'];
                        $temp_namaPenerima[$k] = $v['emp_name'];
                    }
                    $penerima_nama = implode(', ', $temp_namaPenerima);
                } else { // jika adminnya cuma satu
                    $email_penerima = $email_employee[0]['email'];
                    $penerima_nama = $email_employee[0]['emp_name'];
                }
                $email_cc = "";
            } else {
                show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
            }

            // ambil detail nama divisi dan department
            $name_divisi = $this->divisi_model->getOnceWhere(array('id' => $this->input->post('division')))['division'];
            $name_department = $this->dept_model->getDetailById($this->input->post('department'))['nama_departemen'];

            // email data
            $email_subject = "[".$this->page_title['index']."] ".$title;
            $status_detailText = $this->ptk_m->getDetail_ptkStatusDetailByStatusId($status_now)['status_text'];
            $status_text = "Status : ".$status_detailText;
            $message_details = array(
                0 => array(
                    'info_name' => 'Divisi',
                    'info' => $name_divisi
                ),
                1 => array(
                    'info_name' => 'Department',
                    'info' => $name_department
                ),
                2 => array(
                    'info_name' => 'Date Modified',
                    'info' => date('j F Y H:i')
                )
            );
            $token_data = array( // data buat disave di token
                'direct'         => 'ptk',
                'email_penerima' => $email_penerima
            );

            $this->load->model('email_m');
            // kirim email permberitahuan kalo ada emailnya
            if(!empty($email_penerima) && $status_now == 'ptk_stats-1'){
                $this->email_m->general_sendNotifikasi($email_penerima, $email_cc, $penerima_nama, $email_subject, $status_text, $msg, $message_details, $token_data, false);
            } elseif(!empty($email_penerima)) {
                $this->email_m->general_sendNotifikasi($email_penerima, $email_cc, $penerima_nama, $email_subject, $status_text, $msg, $message_details, $token_data);
            }

            // persiapan data status
            // $status = $this->_general_m->getAll("id", $this->table["ptk_status"], array()); // ambil data status
            
            $temp_status_data = array();

            // buat status data
            $name_signed = $this->_general_m->getOnce('emp_name', $this->table['employee'], array('nik' => $this->session->userdata('nik')))['emp_name'];
            $nik_signed = $this->session->userdata('nik');
            $status_data = $this->process_statusData($status_now, $temp_status_data, $name_signed, $nik_signed, $this->input->post('pesan_komentar'))['status_data'];

            // save form
            $data = $this->saveForm_post($status_now, $status_data);
            $data['files'] = json_encode($this->session->userdata('ptk_files')); // masukkan data files
            $data['files_id'] = md5($data['id_entity'].$data['id_div'].$data['id_dept'].$data['id_pos'].$data['id_time'].microtime()); // generate unique id

            // deklarasi path folder dengan bantuan composite key
            $path_temp = './assets/temp/files/ptk/'.$this->session->userdata('nik')."/";
            $path = "./assets/document/ptk/".$data['files_id']."/";

            // PRODUCTION ubah path dan path temp sesuai server windows
            // $path_temp = str_replace('\\', '/', $path_temp);
            // $path = str_replace('\\', '/', $path);

            // buat directory penyimpanan
            if(is_dir($path) == false){
                mkdir($path, 0755, false);
                // PRODUCTION masalah folder path windows
                // mkdir($path, 0777, true); // buat di windows server, mode is ignored in windows
            }
            // pindahkan 1 persatu file dari folder path_temp ke path
            foreach($this->session->userdata('ptk_files') as $v){
                //Move the file using PHP's rename function.
                rename($path_temp.$v['file_name'], $path.$v['file_name']);
            }
            // hapus directory path temp
            $this->load->model('upload_m');
            $this->upload_m->delete_files($path_temp);
            $this->session->unset_userdata('ptk_files'); // hapus session file ptk

            // $data['files'] = json_encode($this->session->userdata())
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
     * function to update files data on database
     *
     * @return void
     */
    function ajax_files_update(){
        $id_entity = $this->input->post('id_entity');
        $id_div = $this->input->post('id_div');
        $id_dept = $this->input->post('id_dept');
        $id_pos = $this->input->post('id_pos');
        $id_time = $this->input->post('id_time');
        $files = $this->input->post('files');

        $this->ptk_m->updateForm(array('files' => $files), array(
            'id_entity' => $id_entity,
            'id_div' => $id_div,
            'id_dept' => $id_dept,
            'id_pos' => $id_pos,
            'id_time' => $id_time
        ));
    }
    
    /**
     * ambil daftar employee yang terdaftar pada position
     *
     * @return void
     */
    function ajax_getEmployeeList(){
        $position = $this->input->post('position');

        // ambil employe yang ada di position ini
        $employee = $this->posisi_m->whoIsOnThisPosition($position);
        $list_employee = array(); $x = 0; // persiapkan list employee
        foreach($employee as $k => $v){
            $list_employee[$k] = array(
                'value' => $v['nik'],
                'text' => $v['emp_name']." [".$v['nik']."]"
            );
        }

        // cek apa list employeenya kosong
        if(!empty($list_employee)){
            // sort by nama employee
            usort($list_employee, function($a, $b) { return $a['text'] <=> $b['text']; }); // sort berdasarkan title menu
        } else {
            $list_employee = "";
        }

        echo(json_encode(array(
            'data' => $list_employee
        )));
    }
        
    /**
     * get interviewer with ajax
     *
     * @return void
     */
    function ajax_getInterviewer(){
        $position = $this->input->post('position'); // ambil data posisi
        $approver = $this->posisi_m->whoApprover($position); // ambil data approver
        echo json_encode($approver); // bawa kembali
    }
    
    /**
     * get job level via ajax
     *
     * @return void
     */
    function ajax_getJobLevel(){
        $id_posisi = $this->input->post('id_posisi');
        $detail_position = $this->posisi_m->getOnceWhere(array('id' => $id_posisi));
        if(!empty($detail_position['job_grade'])){
            $result_level = $this->posisi_m->whatLevelIsThis($detail_position['job_grade']);
            if(!empty($result_level)){
                $job_level = $result_level['id_level'];
            } else {
                $job_level = "";
            }
        } else {
            $job_level = "";
        }

        echo(json_encode(array(
            'data' => $job_level
        ))) ;
    }

    /**
     * get ajax form list for index page
     *
     * @return void
     */
    public function ajax_getMyFormList(){
        // get with status
        $getWithStatus = $this->input->post('status');
        $filter_daterange = $this->input->post('daterange');

        $where = "";
        // filter daterange
        if(!empty($filter_daterange)){
            $daterange = explode(" - ", $filter_daterange); // pisahkan dulu daterangenya
            $daterange[0] = strtotime($daterange[0]);
            $daterange[1] = strtotime($daterange[1]);
            $where .= "id_time >= ".$daterange[0]." AND id_time <= ".$daterange[1]; // tambahkan where tanggal buat ngebatesin view biar ga load lama
        }

        // ambil divisi departemen posisi
        $deptDiv = $this->employee_m->getDeptDivFromNik($this->session->userdata('nik'));
        $position_my = $this->posisi_m->getMyPosition(); // get my position data

        // prepare variable
        $statuses = array();

        // date division department status details
        // get form list ptk
        // if($getWithStatus == "2"){ // jika history
        //     if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1){
        //         $data_ptk = $this->ptk_m->getAll_ptkList();
        //     } else {
        //         show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
        //     }
        // } else
        if($getWithStatus == "0" || $getWithStatus == "1") { // jika statusnya aktif atau tidak
            if((int)$position_my['id'] == 1 || (int)$position_my['id'] == 196 || $this->userApp_admin == 1 || $this->session->userdata('role_id') == 1){
                $data_ptk = $this->ptk_m->get_ptkList($where);
                // $data_ptk = $this->ptk_m->get_ptkList(array(
                //     // 'type' => $getWithStatus
                // ));
            } elseif($position_my['hirarki_org'] == "N"){
                if(!empty($where)){
                    $where .= " AND ";
                }
                $data_ptk = $this->ptk_m->get_ptkList($where.'id_div='.$deptDiv['div_id']);
            } elseif($position_my['hirarki_org'] == "N-1" || $position_my['hirarki_org'] == "N-2"){
                if(!empty($where)){
                    $where .= " AND ";
                }
                $data_ptk = $this->ptk_m->get_ptkList($where.'id_div='.$deptDiv['div_id'].' AND id_dept='.$deptDiv['dept_id']);
            } else {
                show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
            }
        } else {
            if(!empty($getWithStatus)) {
                $statuses = json_decode($getWithStatus, true);
                $statuses = $statuses['my_task'];

                if(!empty($where)){
                    $where .= " AND ";
                }
                
                $temp_ptk = array();
                if((int)$position_my['id'] == 1 || (int)$position_my['id'] == 196 || $this->userApp_admin == 1 || $this->session->userdata('role_id') == 1){
                    foreach($statuses as $k => $v){
                        $temp_ptk[$k] = $this->ptk_m->get_ptkList($where.'status_now="'.$v.'"');
                    }
                } elseif($position_my['hirarki_org'] == "N"){
                    foreach($statuses as $k => $v){
                        $temp_ptk[$k] = $this->ptk_m->get_ptkList($where.'status_now="'.$v.'"'.' AND id_div='.$deptDiv['div_id']);
                    }
                } elseif($position_my['hirarki_org'] == "N-1" || $position_my['hirarki_org'] == "N-2"){
                    foreach($statuses as $k => $v){
                        $temp_ptk[$k] = $this->ptk_m->get_ptkList($where.'status_now="'.$v.'"'.' AND id_div='.$deptDiv['div_id'].' AND id_dept='.$deptDiv['dept_id']);
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
            // jika id_posisi nya kosong, biasanya untuk unbudgetted
            if(empty($value['id_pos'])){
                $data_ptk[$key]['name_pos'] = $value['position_other']; // ambil nama position customnya
            } else {
                $data_ptk[$key]["name_pos"] = $this->posisi_m->getOnceWhere(array('id' => $value['id_pos']))['position_name'];
            }
            $data_ptk[$key]["time_modified"] = date("Y-m-d", $value['time_modified']);
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
     * fungsi untuk mengolah get mpp untuk number of incumbent di form, setelah pilih posisi
     *
     * @return void
     */
    function ajax_getPositionMpp(){
        $id_posisi = $this->input->post('id_posisi');
        $budget = $this->input->post('budget');
        $mpp = $this->posisi_m->howMuchOnThisPosition($id_posisi); // cari berapa banyak yang ada di posisi ini
        
        if($budget == 2){
            $mpp_empty = $mpp['needed'];
        } else {
            $mpp_empty = $mpp['needed'] - $mpp['filled'];
        }

        // $noi = $mpp - $mpp_filled;
        echo json_encode(array(
            'empty' => $mpp_empty,
            'noi' => $mpp['filled']
        ));
    }

    /**
     * get position with divisi and departemen _POST
     *
     * @return void
     */
    public function ajax_getPosition(){
        // take division and department
        $divisi = $this->input->post('divisi');
        $departemen = $this->input->post('departemen');
        $budget = (int)$this->input->post('budget');
        // get position

        // cari di masing2 data posisi untuk mendapatkan siapa aja yg ada di posisi ini
        if($budget != 2){ // jika budgetnya bukan replacement, pilih positionnya yang masih ada kuota tersedia
            $posisi = $this->posisi_m->getAllWhere_mppAvailable(array("div_id" => $divisi, "dept_id" => $departemen));
        } else {
            $posisi = $this->posisi_m->getAllWhere(array("div_id" => $divisi, "dept_id" => $departemen));
            // nothing
        }
        // bring back with json
        echo(json_encode($posisi));
    }
    
    /**
     * Function for ajax to get data form
     *
     * @return void
     */
    function ajax_getPTKdata(){
        // data posisi
        $position_my = $this->posisi_m->getMyPosition();
        if(empty($this->input->post('id_pos'))){
            // ambil divisi dan department dan masukkan dalam variabel position
            $position = array(
                'div_id' => $this->input->post('id_div'),
                'dept_id' => $this->input->post('id_dept')
            );
        } else {
            $position = $this->posisi_m->getOnceWhere(array('id' => $this->input->post('id_pos')));
        }
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
        $data['req_date'] = date("d-m-Y", strtotime($data['req_date']));

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
            $status_data[$k]['time'] = date("j M Y<~>H:i", $v['time']);
            // get status data attribute
            $el = $this->ptk_m->getDetail_ptkStatusDetailByStatusId($v['id']);
            $status_data[$k]['status_name'] = $el['status_name'];
            $status_data[$k]['css_color'] = $el['css_color'];
            $status_data[$k]['icon'] = $el['icon'];
        }

        echo(json_encode($status_data));
    }

    /**
     * ajax function to refresh data files attachment
     *
     * @return void
     */
    function ajax_refreshListFiles(){
        // variabel parameter data sessions dan files
        $session_name = $this->input->post('session_name');
        $files = $this->input->post('files');
        $file_data = $this->session->userdata($session_name);
        if(!empty($file_data) && !empty($session_name)){
            $file_counter = count($file_data);
        } elseif(!empty($files)){
            $file_data = json_decode($files, true);
            if(!empty($file_data)){
                $file_counter = count($file_data);
            } else {
                $file_counter = 0;
            }
        } else {
            $file_counter = 0;
            $file_data = "";
        }

        echo json_encode(array(
            'file_counter' => $file_counter,
            'data' => $file_data
        ));
    }

/* -------------------------------------------------------------------------- */
/*                               OTHER Functions                              */
/* -------------------------------------------------------------------------- */
    // cek akses buat frame viewer
    public function cekakses_ptk($position_my, $position){
        // cek apa dia admin atau userapp admin
        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1 || (int)$position_my['id'] == 196 || (int)$position_my['id'] == 1){
            // perbolehkan akses bebas
        } else {
            // cek berdasarkan hirarki
            if($position_my['hirarki_org'] == "N"){
                if((int)$position_my['div_id'] == (int)$position['div_id']){
                    // perbolehkan akses
                } else {
                    show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
                    exit;
                }
            } elseif($position_my['hirarki_org'] == "N-1" || $position_my['hirarki_org'] == "N-2") {
                // $div = 
                // cek berdasarkan kesamaan divisi dan department
                if((int)$position_my['div_id'] == (int)$position['div_id'] && (int)$position_my['dept_id'] == (int)$position['dept_id']){
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

    function exportHistory(){
        if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1){
            // allow access
        } else {
            show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
            exit;
        }

        // ambil semua data
        $data_ptk = $this->ptk_m->getAll();

        foreach($data_ptk as $k => $v){
            $data_ptk[$k]['entity'] = $this->entity_m->getOnce(array('id' => $v['id_entity']))['keterangan'];
            $data_ptk[$k]['division'] = $this->divisi_model->getOnceWhere(array('id' => $v['id_div']))['division'];
            $data_ptk[$k]['department'] = $this->dept_model->getDetailById($v['id_dept'])['nama_departemen'];
            $data_ptk[$k]['job_level'] = $this->_general_m->getOnce('name', $this->table['joblevel'], array('id' => $v['job_level']))['name']; // lengkapi data job_level
            $data_ptk[$k]['education'] = $this->_general_m->getOnce('name', $this->table['ptk_education'], array('id' => $v['id_ptk_edu']))['name']; // get ptk edu name
            $data_ptk[$k]['employee_status'] = $this->_general_m->getOnce('status_name', $this->table['employee_status'], array('id' => $v['id_employee_status']))['status_name']; // get employee status name
            $data_ptk[$k]['status'] = $this->_general_m->getOnce('status_text', $this->table['ptk_status'], array('id' => $v['status_now']))['status_text']; // lengkapi data status
            $data_ptk[$k]['time_modified'] = date('Y-m-d H:i', $v['time_modified']);

            // lengkapi data posisi 
            if($v['id_pos'] != 0){
                $data_posisi = $this->posisi_m->getOnceWhere(array('id' => $v['id_pos']));
                $data_ptk[$k]['position'] = $data_posisi['position_name'];
                $data_ptk[$k]['hirarki_org'] = $data_posisi['hirarki_org'];
            }

            // lengkapi data resources
            $data_resources = json_decode($v['resources'], true);
            unset($data_ptk[$k]['resources']); // hapus data resources
            if($data_resources == "ext"){
                $data_ptk[$k]['resources'] = "Eksternal";
                $data_ptk[$k]['internal_who'] = null;
            } else {
                $data_ptk[$k]['resources'] = "Internal";
                $data_ptk[$k]['resources_internal_who'] = $data_resources['internal_who'];
            }
            // lengkapi data interviewer
            $data_interviewer = json_decode($v['interviewer'], true);
            $data_ptk[$k]['interviewer1_name'] = $data_interviewer[0]['name'];
            $data_ptk[$k]['interviewer1_position'] = $data_interviewer[0]['position'];
            $data_ptk[$k]['interviewer2_name'] = $data_interviewer[1]['name'];
            $data_ptk[$k]['interviewer2_position'] = $data_interviewer[1]['position'];
            $data_ptk[$k]['interviewer3_name'] = $data_interviewer[2]['name'];
            $data_ptk[$k]['interviewer3_position'] = $data_interviewer[2]['position'];
            // lengkapi data work location
            $data_workLocation = json_decode($v['work_location'], true);
            if($data_workLocation['other'] == false){
                $data_ptk[$k]['work_location'] = $this->_general_m->getOnce('location', $this->table['location'], array('id' => $data_workLocation['location']))['location'];
            } else {
                $data_ptk[$k]['work_location'] = $data_workLocation['location'];
            }
            // lengkapi requirement special
            if(!empty($v['req_special'])){
                $data_ptk[$k]['req_special'] = str_replace("&nbsp;", "" , trim(strip_tags($v['req_special'])));
            }
            // olah budget
            if($v['budget'] == 1){
                $data_ptk[$k]['budget'] = "Budgetted";
            } else {
                $data_ptk[$k]['budget'] = "Unbudgetted";
            }
            // olah sex
            if($v['sex'] == 1){
                $data_ptk[$k]['sex'] = "Male";
            } else {
                $data_ptk[$k]['sex'] = "Female";
            }
            
            // strip tags from input form
            $data_ptk[$k]['outline'] = str_replace("&nbsp;", "" , trim(strip_tags($v['outline'])));
            $data_ptk[$k]['main_responsibilities'] = str_replace("&nbsp;", "" , trim(strip_tags($v['main_responsibilities'])));
            $data_ptk[$k]['tasks'] = str_replace("&nbsp;", "" , trim(strip_tags($v['tasks'])));
            $data_ptk[$k]['req_ska'] = str_replace("&nbsp;", "" , trim(strip_tags($v['req_ska'])));

            // remove some unused data
            unset($data_ptk[$k]['interviewer']);
            unset($data_ptk[$k]['id_entity']);
            unset($data_ptk[$k]['id_div']);
            unset($data_ptk[$k]['id_dept']);
            unset($data_ptk[$k]['id_pos']);
            unset($data_ptk[$k]['id_time']);
            unset($data_ptk[$k]['id_ptk_edu']);
            unset($data_ptk[$k]['id_employee_status']);
            unset($data_ptk[$k]['status_now']);
            unset($data_ptk[$k]['internal_who']);
        }

        export2Excel($data_ptk, 'Employee Requisition Data_'.date('Y-m-d_H:i', time()));
    }
    
    /**
     * function used for save ptk form
     *
     * @return void
     */
    public function saveForm_post($status_now, $status_data, $time = "") {
        //timestamp id
        if(empty($time)){
            $time = time();
        } else {
            // nothing
        }
        $data['id_time'] = $time;
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

            // jika budget replacement, isi replacement who dengan detail employee
            if($this->input->post('budget') == 2){
                $emp_detail = $this->employee_m->getDetails_employee($this->input->post('replacement_who'));
                $data['replacement'] = json_encode($emp_detail);
            } else {
                $data['replacement'] = "";
            }
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
        // resources
        if($this->input->post('resources') == "int"){
            $data['resources'] = json_encode([
                "resources"    => $this->input->post('resources'),
                "internal_who" => $this->input->post('internal_who')
            ]);
        } else {
            $data['resources'] = json_encode([
                "resources"    => $this->input->post('resources'),
                "internal_who" => ""
            ]);
        }
        // mpp
        $data['req_mpp'] = $this->input->post('mpp_req');
        // status employement dan temporary month
        $data['id_employee_status'] = $this->input->post('emp_stats');
        $data['empstats_temporary_month'] = $this->input->post('temporary_month');
        // Date Required
        $data['req_date'] = date("Y-m-d", strtotime($this->input->post('date_required')));
        // Education
        $data['id_ptk_edu'] = $this->input->post('education');
        // Majoring
        $data['majoring'] = $this->input->post('majoring');
        // Preferred Age
        $data['age'] = $this->input->post('preferred_age');
        // Sex
        // $data['sex'] = $this->input->post('sex');
        // Working Experience
        $data['work_exp'] = $this->input->post('work_exp_years');
        if($this->input->post('work_exp_years') > 0){
            $data['work_exp_at'] = $this->input->post('work_exp_at');
        } else {
            $data['work_exp_at'] = "";
        }
        // ska
        $data['req_ska'] = $this->input->post('ska');
        // Special Requirement
        $data['req_special'] = $this->input->post('req_special');
        // Outline
        $data['outline'] = $this->input->post('outline');
        // Main Responsibilities
        $data['main_responsibilities'] = $this->input->post('main_responsibilities');
        // Tasks
        $data['tasks'] = $this->input->post('tasks');
        // Interviewer
        $data['interviewer'] = json_encode([
            0 => array(
                'name' => $this->input->post('interviewer_name1'),
                'position' => $this->input->post('interviewer_position1')
            ),
            1 => array(
                'name' => $this->input->post('interviewer_name2'),
                'position' => $this->input->post('interviewer_position2')
            ),
            2 => array(
                'name' => $this->input->post('interviewer_name3'),
                'position' => $this->input->post('interviewer_position3')
            ),
            3 => array(
                'name' => $this->input->post('interviewer_name4'),
                'position' => $this->input->post('interviewer_position4')
            )
        ]);

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

        $data['flag_viewer'] = 1; // penanda flag viewer untuk files uploader
        $data['userApp_admin'] = $this->userApp_admin; // data useradmin app
        // form data
        $data['status_form'] = $this->ptk_m->getDetail_ptkStatusNow($data['id_entity'], $data['id_div'], $data['id_dept'], $data['id_pos'], $data['id_time']); // get status id
        $data['status_before'] = $this->ptk_m->getStatusBefore($data['status_form']);

        // cek apa dia CEO Office
        if((int)$position_my['id'] == 1){
            if($data['status_form'] == "ptk_stats-B"){
                // Form Data
                if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1){
                    $data['division'] = $this->divisi_model->getDivisi(); // ambil division
                } else {
                    $data['division'] = $this->divisi_model->getOnceById($data['id_div']); // ambil division
                    $data['department'] = $this->dept_model->getDetailById($data['id_dept']); // ambil departemen
                    $data['position'] = $this->posisi_m->getAllWhere(array('div_id' => $data['id_div'], 'dept_id' => $data['id_dept'])); // position
                }

                $data['is_edit'] = 1; // tambah edit status
            } else {
                // Form Data
                $data['department'] = $this->dept_model->getDetailById($data['id_dept']); // ambil departemen
                $data['division']   = $this->divisi_model->getOnceById($data['id_div']); // ambil division

                $data['is_edit'] = 0; //  tambah viewer status
            }
        // buat Divisi HC ga baca ke sini dah
        } elseif((int)$position_my['id'] == 196) {
            if($data['status_form'] == "ptk_stats-4"){
                // Form Data
                if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1){
                    $data['division'] = $this->divisi_model->getDivisi(); // ambil division
                } else {
                    $data['division'] = $this->divisi_model->getOnceById($data['id_div']); // ambil division
                    $data['department'] = $this->dept_model->getDetailById($data['id_dept']); // ambil departemen
                    $data['position'] = $this->posisi_m->getAllWhere(array('div_id' => $data['id_div'], 'dept_id' => $data['id_dept'])); // position
                }

                $data['is_edit'] = 1; // tambah edit status
            } else {
                // Form Data
                $data['department'] = $this->dept_model->getDetailById($data['id_dept']); // ambil departemen
                $data['division']   = $this->divisi_model->getOnceById($data['id_div']); // ambil division

                $data['is_edit'] = 0; //  tambah viewer status
            }
        // buat Admin
        } elseif($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1) {
            if($data['status_form'] == "ptk_stats-3" || $data['status_form'] == "ptk_stats-1" || $data['status_form'] == "ptk_stats-C" || $data['status_form'] == "ptk_stats-D" || $data['status_form'] == "ptk_stats-E" || $data['status_form'] == "ptk_stats-F"){
                // Form Data
                $data['division'] = $this->divisi_model->getDivisi(); // ambil division

                $data['is_edit'] = 1; // tambah edit status
            } else {
                $data['department'] = $this->dept_model->getDetailById($data['id_dept']); // ambil departemen
                $data['division']   = $this->divisi_model->getOnceById($data['id_div']); // ambil division

                $data['is_edit'] = 0; //  tambah viewer status
            }
        // buat hirarki N
        } elseif($position_my['hirarki_org'] == "N") {
            if($data['status_form'] == "ptk_stats-2"){
                // Form Data
                // if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1){
                //     $data['division'] = $this->divisi_model->getDivisi(); // ambil division
                // } else {
                    $data['division'] = $this->divisi_model->getOnceById($data['id_div']); // ambil division
                    $data['department'] = $this->dept_model->getDetailById($data['id_dept']); // ambil departemen
                    // $data['department'] = $this->dept_model->getAll(); // ambil departemen
                    $data['position'] = $this->posisi_m->getAllWhere(array('div_id' => $data['id_div'], 'dept_id' => $data['id_dept'])); // position
                // }

                $data['is_edit'] = 1; // tambah edit status
            } else {
                $data['department'] = $this->dept_model->getDetailById($data['id_dept']); // ambil departemen
                $data['division']   = $this->divisi_model->getOnceById($data['id_div']); // ambil division

                $data['is_edit'] = 0; //  tambah viewer status
            }
        // buat hirarki N-1
        } elseif($position_my['hirarki_org'] == "N-1") {
            if($data['status_form'] == "ptk_stats-1" || $data['status_form'] == "ptk_stats-C" || $data['status_form'] == "ptk_stats-D" || $data['status_form'] == "ptk_stats-E" || $data['status_form'] == "ptk_stats-F"){
                // Form Data        
                $data['division'] = $this->divisi_model->getOnceById($data['id_div']); // ambil division
                $data['department'] = $this->dept_model->getDetailById($data['id_dept']); // ambil departemen
                $data['position'] = $this->posisi_m->getAllWhere(array('div_id' => $data['id_div'], 'dept_id' => $data['id_dept'])); // position

                $data['is_edit'] = 1; // tambah edit status
            } else {
                $data['department'] = $this->dept_model->getDetailById($data['id_dept']); // ambil departemen
                $data['division']   = $this->divisi_model->getOnceById($data['id_div']); // ambil division

                $data['is_edit'] = 0; //  tambah viewer status
            }
        // buat hirarki N-2
        } else {
            if($data['status_form'] == "ptk_stats-1" || $data['status_form'] == "ptk_stats-C" || $data['status_form'] == "ptk_stats-D" || $data['status_form'] == "ptk_stats-E" || $data['status_form'] == "ptk_stats-F"){
                // Form Data
                $data['division'] = $this->divisi_model->getOnceById($data['id_div']); // ambil division
                $data['department'] = $this->dept_model->getDetailById($data['id_dept']); // ambil departemen
                $data['position'] = $this->posisi_m->getAllWhere(array('div_id' => $data['id_div'], 'dept_id' => $data['id_dept'])); // position

                $data['is_edit'] = 1; // tambah edit status
            } else {
                $data['department'] = $this->dept_model->getDetailById($data['id_dept']); // ambil departemen
                $data['division']   = $this->divisi_model->getOnceById($data['id_div']); // ambil division

                $data['is_edit'] = 0; //  tambah viewer status
            }
        }
        
        // form data
        $data['education']  = $this->_general_m->getAll('*', 'ptk_education', array()); // education
        $data['emp_status'] = $this->_general_m->getAll('*', 'employee_status', array()); // employee status
        $data['entity']     = $this->entity_m->getAll_notAtAll("*", $this->table['entity'], array()); // ambil entity
        $data['master_level'] = $this->ptk_m->get_masterLevel(); // ambil master level
        $data['position']   = $this->posisi_m->getAllWhere(array('div_id' => $data['id_div'], 'dept_id' => $data['id_dept'])); // position
        $data['position_my'] = $position_my; // my position data
        $data['status_detail'] = $this->ptk_m->getDetail_ptkStatusDetailByStatusId($data['status_form']); // get status details
        $data['work_location'] = $this->_general_m->getAll('id, location', $this->table['location'], array());
        // sorting
        usort($data['entity'], function($a, $b) { return $a['keterangan'] <=> $b['keterangan']; }); // sort berdasarkan title menu
        usort($data['position'], function($a, $b) { return $a['position_name'] <=> $b['position_name']; }); // sort berdasarkan title menu

        // main data
        $data['sidebar']    = getMenu();        // ambil menu
        $data['breadcrumb'] = getBreadCrumb();  // ambil data breadcrumb
        $data['user']       = getDetailUser();  //ambil informasi user
        $data['page_title'] = $this->page_title['form'];
        $data['load_view'] = 'ptk/viewPTK_ptk_v';
        // additional styles and custom script
        $data['additional_styles'] = array(
            'plugins/datatables/styles_datatables',
            'plugins/datepicker/styles_datepicker',
            'plugins/jquery-uploadfile/styles'
        );
        // $data['custom_styles'] = array();
        $data['custom_script'] = array(
            'plugins/datatables/script_datatables', 
            'plugins/datepicker/script_datepicker', 
            'plugins/ckeditor/script_ckeditor.php', 
            'plugins/jquery-uploadfile/script',
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
        $pesan_komentar = $this->input->post('pesan_komentar');
        $status_now = $this->input->post('status_now'); // ambil id status
        $status_data = $this->ptk_m->getDetail_ptkStatus($id_entity, $id_div, $id_dept, $id_pos, $id_time); // get status data
        $name_signed = $this->_general_m->getOnce('emp_name', $this->table['employee'], array('nik' => $this->session->userdata('nik')))['emp_name'];
        $nik_signed = $this->session->userdata('nik');
        
        // jika actionnya revise
        if($action == 2){
            $revise_to = $this->input->post('revise_to');
        }

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
            
            header('location: ' . base_url('ptk/viewPTK')."?id_entity=$id_entity&id_div=$id_div&id_dept=$id_dept&id_pos=$id_pos&id_time=$id_time");
        } else {
            // cek posisi dan status dari form lalu ubah status datanya, dan tambah pesan revisi
            if((int)$position_my['id'] == 1){
                if($status_now == "ptk_stats-B"){
                    // cek action
                    if($action == 0){
                        $new_statsData = $this->process_statusData("ptk_stats-8", $status_data, $name_signed, $nik_signed, $pesan_komentar);
                    } elseif($action == 1){
                        $new_statsData = $this->process_statusData("ptk_stats-A", $status_data, $name_signed, $nik_signed, $pesan_komentar, $this->input->post('division'), $this->input->post('department'));
                    } elseif($action == 2){
                        $new_statsData = $this->process_statusData($revise_to, $status_data, $name_signed, $nik_signed, $pesan_komentar, $this->input->post('division'), $this->input->post('department'));
                    } else {
                        show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                        exit;
                    }
                } else {
                    show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                    exit;
                }
            } elseif((int)$position_my['id'] == 196) {
                if($status_now == "ptk_stats-4"){
                    // cek action
                    if($action == 0){
                        $new_statsData = $this->process_statusData("ptk_stats-7", $status_data, $name_signed, $nik_signed, $pesan_komentar);
                    } elseif($action == 1){
                        $new_statsData = $this->process_statusData("ptk_stats-B", $status_data, $name_signed, $nik_signed, $pesan_komentar);
                    } elseif($action == 2){
                        $new_statsData = $this->process_statusData($revise_to, $status_data, $name_signed, $nik_signed, $pesan_komentar, $this->input->post('division'), $this->input->post('department'));
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
                        $new_statsData = $this->process_statusData("ptk_stats-6", $status_data, $name_signed, $nik_signed, $pesan_komentar);
                    } elseif($action == 1){
                        $new_statsData = $this->process_statusData("ptk_stats-3", $status_data, $name_signed, $nik_signed, $pesan_komentar);
                    } elseif($action == 2){
                        $new_statsData = $this->process_statusData($revise_to, $status_data, $name_signed, $nik_signed, $pesan_komentar, $this->input->post('division'), $this->input->post('department'));
                    } else {
                        show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                        exit;
                    }
                } else {
                    show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                    exit;
                }
            } elseif($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1){
                if($status_now == "ptk_stats-3"){
                    // cek action
                    if($action == 0){
                        $new_statsData = $this->process_statusData("ptk_stats-5", $status_data, $name_signed, $nik_signed, $pesan_komentar);
                    } elseif($action == 1){
                        $new_statsData = $this->process_statusData("ptk_stats-4", $status_data, $name_signed, $nik_signed, $pesan_komentar);
                    } elseif($action == 2){
                        $new_statsData = $this->process_statusData($revise_to, $status_data, $name_signed, $nik_signed, $pesan_komentar, $this->input->post('division'), $this->input->post('department'));
                    } else {
                        show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                        exit;
                    }
                } else {
                    if((int)$this->input->post('division') == 6){
                        if($action == 1){
                            $new_statsData = $this->process_statusData("ptk_stats-3", $status_data, $name_signed, $nik_signed, $pesan_komentar);
                        } elseif($action == 3){
                            $new_statsData = $this->process_statusData("ptk_stats-1", $status_data, $name_signed, $nik_signed, $pesan_komentar);
                        } else {
                            show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                            exit;
                        }
                    } else {
                        if($action == 1){
                            $new_statsData = $this->process_statusData("ptk_stats-2", $status_data, $name_signed, $nik_signed, $pesan_komentar);
                        } elseif($action == 3){
                            $new_statsData = $this->process_statusData("ptk_stats-1", $status_data, $name_signed, $nik_signed, $pesan_komentar);
                        } else {
                            show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                            exit;
                        }
                    }
                }
            } elseif($position_my['hirarki_org'] == "N-1") {
                // cek action
                if($action == 1){
                    $new_statsData = $this->process_statusData("ptk_stats-2", $status_data, $name_signed, $nik_signed, $pesan_komentar);
                } elseif($action == 3){
                    $new_statsData = $this->process_statusData("ptk_stats-1", $status_data, $name_signed, $nik_signed, $pesan_komentar);
                } else {
                    show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                    exit;
                }
            } elseif($position_my['hirarki_org'] == "N-2") {
                if($action == 3){
                    $new_statsData = $this->process_statusData("ptk_stats-1", $status_data, $name_signed, $nik_signed, $pesan_komentar);
                } else {
                    show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                    exit;
                }
            } else {
                show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                exit;
            }
            // simpan ke dalam database
            $data = $this->saveForm_post($new_statsData['status_new'], $new_statsData['status_data'], $id_time);
            $this->updateForm($data, $id_entity, $id_div, $id_dept, $id_pos, $id_time);

            // siapkan notifikasi
            if($action == 0){
                $icon = "error"; $title = "Rejected"; $msg = "You have Rejected this form.";
            } elseif($action == 1){
                if($status_now == "ptk_stats-1"){
                    $icon = "success"; $title = "Proposed"; $msg = "You have Proposed this form.";
                } elseif($status_now == "ptk_stats-B"){
                    $icon = "success"; $title = "Completed"; $msg = "The form Approval has been completed.";
                } else {
                    $icon = "success"; $title = "Approved"; $msg = "You have Approved this form.";
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
    function process_statusData($status_new, $status_data, $name_signed, $nik_signed, $pesan_komentar = "", $id_div = "", $id_dept = ""){
        if($status_data == array()){
            $status_data[0] = array(
                'id' => $status_new,
                'time' => time(),
                'pesan_komentar' => $pesan_komentar,
                'signedby' => $name_signed,
                'signedbynik' => $nik_signed
            );
        } else {
            $index = array_key_last($status_data)+1; // set index for new status
            $status_data[$index] = array(
                'id' => $status_new,
                'time' => time(),
                'pesan_komentar' => $pesan_komentar,
                'signedby' => $name_signed,
                'signedbynik' => $nik_signed
            );
        }

        // kirim email notifikasi ke karyawan dituju, cek berdasarkan status
        if($status_new == "ptk_stats-2"){
            // kirim email ke divhead
            $email_data = $this->divisi_model->get_divHead($this->input->post('division'));
            // email data
            $email_penerima = $email_data['email'];
            $email_cc = "";
            $penerima_nama = $email_data['emp_name'];
        } elseif($status_new == "ptk_stats-3"){
            // kirim email ke OD Department
            $admins_nik = $this->_general_m->getAll('nik', $this->table['admin'], array('id_menu' => $this->id_menu));
            foreach($admins_nik as $k => $v){ // ambil data admins 1 per 1
                $admins[$k] = $this->employee_m->getDetails_employee($v['nik']);
            }
            if(!empty($admins)){
                // jika adminnya lebih dari satu
                if(count($admins) > 1){
                    $email_penerima = array(); $temp_namaPenerima = array();
                    foreach($admins as $k => $v){
                        $email_penerima[$k] = $v['email'];
                        $temp_namaPenerima[$k] = $v['emp_name'];
                    }
                    $penerima_nama = implode(', ', $temp_namaPenerima);
                } else { // jika adminnya cuma satu
                    $email_penerima = $admins[0]['email'];
                    $penerima_nama = $admins[0]['emp_name'];
                }
                $email_cc = "";
            }
        } elseif($status_new == "ptk_stats-4"){
            // kirim email ke HC Divhead
            $email_data = $this->divisi_model->get_divHead(6);
            // email data
            $email_penerima = $email_data['email'];
            $email_cc = "";
            $penerima_nama = $email_data['emp_name'];
        } elseif($status_new == "ptk_stats-B"){
            // kirim email ke CEO
            $email_data = $this->divisi_model->get_divHead(1);
            // email data
            $email_penerima = $email_data['email'];
            $email_cc = "";
            $penerima_nama = $email_data['emp_name'];
        } elseif($status_new == "ptk_stats-1"){
            // kirim email ke Depthead dari divisi dan departemen dipilih
            $email_data = $this->dept_model->getDeptHead($id_div, $id_dept);
            if(!empty($email_data)){
                // email data
                $email_penerima = $email_data['email'];
                $email_cc = "";
                $penerima_nama = $email_data['emp_name'];
            } else {
                // kirim email ke OD Department
                $admins_nik = $this->_general_m->getAll('nik', $this->table['admin'], array('id_menu' => $this->id_menu));
                foreach($admins_nik as $k => $v){ // ambil data admins 1 per 1
                    $admins[$k] = $this->employee_m->getDetails_employee($v['nik']);
                }
                if(!empty($admins)){
                    // jika adminnya lebih dari satu
                    if(count($admins) > 1){
                        $email_penerima = array(); $temp_namaPenerima = array();
                        foreach($admins as $k => $v){
                            $email_penerima[$k] = $v['email'];
                            $temp_namaPenerima[$k] = $v['emp_name'];
                        }
                        $penerima_nama = implode(', ', $temp_namaPenerima);
                    } else { // jika adminnya cuma satu
                        $email_penerima = $admins[0]['email'];
                        $penerima_nama = $admins[0]['emp_name'];
                    }
                    $email_cc = "";
                }
            }
        } elseif($status_new == "ptk_stats-A"){
            // kirim notifikasi ke semua aktor
            $ed_divh = $this->divisi_model->get_divHead($this->input->post('division'));
            $ed_hc = $this->divisi_model->get_divHead(6);

            // set email penerima
            $email_penerima = array(
                0 => $ed_divh['email'],
                1 => $ed_hc['email']
            );
            $temp_namaPenerima = array(
                0 => $ed_divh['emp_name'],
                1 => $ed_hc['emp_name']
            );

            // tambah email email OD Department
            $admins_nik = $this->_general_m->getAll('nik', $this->table['admin'], array('id_menu' => $this->id_menu));
            foreach($admins_nik as $k => $v){ // ambil data admins 1 per 1
                $admins[$k] = $this->employee_m->getDetails_employee($v['nik']);
            }
            if(!empty($admins)){
                // jika adminnya lebih dari satu
                $x = array_key_last($email_penerima) + 1; $y = array_key_last($temp_namaPenerima) + 1;
                foreach($admins as $k => $v){
                    $email_penerima[$x] = $v['email'];
                    $temp_namaPenerima[$y] = $v['emp_name'];
                    $x++; $y++;
                }
            }

            // tambah email dephead
            $ed_deph = $this->dept_model->getDeptHead($id_div, $id_dept);
            if(!empty($ed_deph)){
                // email data
                $email_penerima[array_key_last($email_penerima) + 1] = $ed_deph['email'];
                $penerima_nama[array_key_last($temp_namaPenerima) + 1]= $ed_deph['emp_name'];
            }

            // gabungkan nama penerima email
            $penerima_nama = implode(', ', $temp_namaPenerima);

            // kirim cc ke CEO
            $ed_ceo = $this->divisi_model->get_divHead(1);
            $email_cc = $ed_ceo['email'];
        }

        if($this->input->post('action') == 0){ // action rejected
            // email data
            $title = "Rejected";
            $message = "The form has been Rejected.";
        } elseif($this->input->post('action') == 1){ // action approved
            if($status_new == "ptk_stats-2"){
                // email data
                $title = "Proposed";
                $message = "An Employee Requisition Form has been Proposed.";
            } elseif($status_new == "ptk_stats-A"){
                // email data
                $title = "Completed";
                $message = "The form Approval has been completed.";
            } else {
                // email data
                $title = "Approved";
                $message = "The form has been Approved.";
            }
        } elseif($this->input->post('action') == 2){ // action revised
            // email data
            $title = "Revised";
            $message = "The form has been Revised.";
        }

        // untuk action_who
        if($status_new == "ptk_stats-2"){
            $action_who = "Employee";
        } else {
            $action_who = "Approver";
        }

        // email data
        $email_subject = "[".$this->page_title['index']."] ".$title;
        $status_detailText = $this->ptk_m->getDetail_ptkStatusDetailByStatusId($status_new)['status_text'];
        $status_text = "Status : ".$status_detailText;
        $message_details = array(
            0 => array(
                'info_name' => $action_who,
                'info' => $name_signed
            ),
            2 => array(
                'info_name' => 'Date Modified',
                'info' => date('j F Y H:i')
            )
        );
        $token_data = array( // data buat disave di token
            'direct'         => 'ptk',
            'email_penerima' => $email_penerima
        );

        $this->load->model('email_m'); // load model email_m
        // kirim email permberitahuan kalo ada emailnya
        if(!empty($email_penerima) && $status_new == "ptk_stats-A"){
            $this->email_m->general_sendNotifikasi($email_penerima, $email_cc, $penerima_nama, $email_subject, $status_text, $message, $message_details, $token_data, false);
        } elseif(!empty($email_penerima)) {
            $this->email_m->general_sendNotifikasi($email_penerima, $email_cc, $penerima_nama, $email_subject, $status_text, $message, $message_details, $token_data);
        }
            
        return array(
            'status_new' => $status_new, 
            'status_data' => $status_data);
    }

}

/* End of file Ptk.php */
 