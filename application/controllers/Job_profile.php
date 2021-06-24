<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Job_profile extends MainController {
    
    protected $table_position = 'master_position';
    public function __construct()
    {
        parent::__construct();
        // load model
        $this->load->model([
            'Jobpro_model', 
            'Divisi_model',
            'Dept_model',
            'posisi_m'
        ]);
        $this->load->library('tablename');
        $this->table_position = $this->tablename->get($this->table_position);

        $this->checkToken(); // cek token
        // load helper
        $this->load->helper('email');
        // $this->load->helper('encryption');
        $this->load->helper('random_string');
    }

    public function checkToken() {
        // Token Checker
        if(!empty($this->session->userdata('token'))){
            // cek data token
            if(!empty($data = $this->Jobpro_model->getDetail('data', 'user_token', array('token' => $this->session->userdata('token')))['data'])){
                $data = json_decode($data, true);
    
                if($this->session->userdata('position_id') == $data['id_posisi']){
                    // hapus token dari database
                    $this->Jobpro_model->delete('user_token', array('index' => 'token', 'data' => $this->session->userdata('token')));
    
                    $this->session->set_userdata('msg', array(
                        'icon' => 'warning',
                        'msg' => $data['msg']
                    ));
                } else {
                    // set toastr notification
                    $this->session->set_userdata('msg', array(
                        'icon' => 'error',
                        'title' => 'Error',
                        'msg' => 'The link token is not yours!'
                    ));
                }
            }

            // hapus session token
            $this->session->unset_userdata('token');            
        }
    }

/* -------------------------------------------------------------------------- */
/*                             main Job Profile                               */
/* -------------------------------------------------------------------------- */
    public function index(){
        $nik = $this->session->userdata('nik');
        $data['posisi'] = $this->Jobpro_model->getPosisi($nik);

        // cek jika tidak ada data jobprofile_approval
        $job_approval = $this->Jobpro_model->getDetail("*", "jobprofile_approval", array('id_posisi' => $data['posisi']['position_id']));
        if(empty($job_approval)){ // jika table job approval kosong
            $data = [
                'id_posisi' => $data['posisi']['position_id'],
                'diperbarui' => time(),
                'status_approval' => 0,
                'pesan_revisi' => "null"
            ];
            $this->db->insert('jobprofile_approval', $data);
        }else{
            //do nothing
        }

        if(empty($this->Jobpro_model->getDetail('*', 'jobprofile_jumlahstaff', array('id_posisi' => $data['posisi']['position_id'])))){ //cek apa jumlah staff sudah ada
            $this->Jobpro_model->insert('jobprofile_jumlahstaff', array(
                'id_posisi' => $data['posisi']['position_id'],
                'manager' => 0,
                'supervisor' => 0,
                'staff' => 0
            ));
        }

        //get back this variable, it is gone after I using the if.. else.. above
        $data['pos'] = $this->Jobpro_model->getAllPosition();
        $data['user'] = $this->db->get_where('master_employee', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['posisi'] = $this->Jobpro_model->getPosisi($nik);
        //ambil informasi approver 1 dan 2
        $data['approver'][0] =  $this->Jobpro_model->getPositionDetail($data['posisi']['id_approver1']);
        $data['approver'][1] =  $this->Jobpro_model->getPositionDetail($data['posisi']['id_approver2']);
        
        $data['statusApproval'] = $this->db->get_where('jobprofile_approval', ['id_posisi' => $data['posisi']['position_id']])->row_array(); //get status approval

        //ambil data my task dengan id_position dan status
        //$this->Jobpro_model->getMyTask(id_posisi, 'kolom_approver_di_database, status approval);
        $my_task = $this->Jobpro_model->getMyTask($data['posisi']['position_id'], 'id_approver1', '1');
        $my_task = array_merge($my_task, $this->Jobpro_model->getMyTask($data['posisi']['position_id'], 'id_approver2', '2'));

        //ambil data my task dengan approver 1 dan 2, vacant, jika admin
        if($this->Jobpro_model->getDetail('role_id', 'master_employee', array('nik' => $nik))['role_id'] == 1 || $this->userApp_admin == 1){
            if(!empty($my_vacant_task = $this->getMyTaskVacant())){ //ambil data vacant task
                $my_task = array_merge($my_task, $my_vacant_task); // gabungkan task
            }
            // if(!empty($ceoTask = $this->getCeoTask())){ // ambil CEO Task
            //     $my_task = array_merge($my_task, $ceoTask); // gabungkan task
            // }
        }
        
        $data['my_task'] = $this->getApprovalDetails($my_task); //get Approval Details

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu', array('url' => $this->uri->uri_string()))['title'];
        $data['userApp_admin'] = $this->userApp_admin;
		$data['load_view'] = 'job_profile/index_jobprofile_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		// $data['custom_styles'] = array('survey_styles');
        $data['custom_script'] = array('plugins/datatables/script_datatables', 'job_profile/script_index_jobprofile');
        
		$this->load->view('main_v', $data);
    }

    // function untuk menampilkan JP karyawan yang login
    public function myJp(){
        $nik = $this->session->userdata('nik');
        $id_posisi = $this->Jobpro_model->getDetail('position_id', 'master_employee', array('nik' => $nik))['position_id'];

        $data = $this->getDataJP($nik, $id_posisi);

        $data['jp_user'] = $this->db->get_where('master_employee', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['pos'] = $this->Jobpro_model->getAllPosition();
        
        $approval = $this->db->get_where('jobprofile_approval', ['id_posisi' => $data['posisi']['id']])->row_array(); //get status approval
        
        if ($approval['status_approval'] == 0 || $approval['status_approval'] == 3) {
            // main data
		    // $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['title']; // for submenu
            $data['load_view'] = 'job_profile/myjp_editor_jobprofile_v';
            // additional styles and custom script
            $data['custom_script'] = array('plugins/datatables/script_datatables', 'plugins/ckeditor/script_ckeditor', 'job_profile/script_jobprofile', 'job_profile/script_edit_jobprofile');
        } else {
            $data['approval'] = $approval;

            // main data
		    // $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['title']; // for submenu
            $data['load_view'] = 'job_profile/myjp_viewer_jobprofile_v';
            // additional styles and custom script
            $data['custom_script'] = array('plugins/datatables/script_datatables', 'job_profile/script_jobprofile','job_profile/script_view_jobprofile');            
        }

        $data['table_position'] = $this->table_position;

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['userApp_admin'] = $this->userApp_admin;
        $data['page_title'] = "My Job Profile";
        //additional styles and custom script   
        $data['custom_styles'] = array('jobprofile_styles');
        $data['additional_styles'] = array('job_profile/styles_jobprofile.php', 'plugins/datatables/styles_datatables');

        $this->load->view('main_v', $data);
    }

    // function untuk menampilkan JP karyawan bawahan task
    public function taskJp(){
        // prepare the data
        $nik = $this->input->get('task');
        $id_posisi = $this->input->get('id'); // posisi karyawan
        $my_position = $this->posisi_m->getMyPosition();
        $data = $this->getDataJP($nik, $id_posisi);
        // $data['status'] = $this->input->get('status');

        // cek akses taskJP
        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1){
            // nothing
        } else {
            if(empty($this->db->query("SELECT * FROM ". $this->table_position ." WHERE (id_approver1='".$my_position['id']."' AND id='".$id_posisi."') OR (id_approver2='".$my_position['id']."' AND id='".$id_posisi."')")->result())){ //cek kalo dia punya akses terhadap karyawan tersebut
                show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
                exit;
            } else {
                //nothing
            };      
        }

        // ambil status jp
        $approval = $this->Jobpro_model->getApproval($id_posisi);
        $data['status'] = $approval['status_approval'];

        // cek status berdasarkan status jp
        if($data['status'] == 0 || $data['status'] == 3 || $data['status'] == 4){
            show_404();
        }

        $data['table_position'] = $this->table_position;
        
        $data['pos'] = $this->Jobpro_model->getAllPosition();
        $data['title'] = 'My Task';
        $data['jp_user'] = $this->db->get_where('master_employee', ['nik' => $this->session->userdata('nik')])->row_array();

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
		// $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu', array('url' => $this->uri->uri_string()))['title'];
        $data['page_title'] = 'Task Job Profile';
        $data['userApp_admin'] = $this->userApp_admin;
		$data['load_view'] = 'job_profile/taskjp_jobprofile_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables', 'job_profile/styles_jobprofile.php');
		$data['custom_styles'] = array('jobprofile_styles');
        $data['custom_script'] = array('plugins/datatables/script_datatables', 'plugins/ckeditor/script_ckeditor.php', 'job_profile/script_jobprofile', 'job_profile/script_edit_jobprofile');
        
		$this->load->view('main_v', $data);
    }

    // function untuk menampilakn JP karyawan melalui halaman report
    public function reportJp(){
        $my_nik = $this->session->userdata('nik'); //get my nik
        $nik = $this->input->get('task');// get another nik

        $my_position_id = $this->Jobpro_model->getDetail('position_id', 'master_employee', array('nik' => $my_nik))['position_id']; //ambil position_id
        $role_id = $this->Jobpro_model->getDetail('role_id', 'master_employee', array('nik' => $my_nik))['role_id']; //ambil role_id
        
        // if($role_id != 1){ // cek role_id apakah punya hak akses
        //     redirect('auth/blocked','refresh'); //jika tidak punya hak akses tampilkan pesan error
        //     exit;
        // }
        //ambil position id
        $id_posisi =$this->input->get('id'); //ambil position id

        if($role_id == 1 || $this->userApp_admin == 1){
            // nothing
        } else {
            if(empty($this->db->query("SELECT * FROM ". $this->table_position ." WHERE (id_approver1='".$my_position_id."' AND id='".$id_posisi."') OR (id_approver2='".$my_position_id."' AND id='".$id_posisi."')")->result())){ //cek kalo dia punya akses terhadap karyawan tersebut
                show_404();
                exit;
            } else {
                //nothing
            };      
        }
            
        // prepare the data
        $data = $this->getDataJP($nik, $id_posisi);
        $data['status'] = $this->input->get('status');
        
        $data['pos'] = $this->Jobpro_model->getAllPosition();
        $data['title'] = 'Report';
        $data['jp_user'] = $this->db->get_where('master_employee', ['nik' => $this->session->userdata('nik')])->row_array();

        if($role_id == 1 || $this->userApp_admin == 1){ //cek jika dia admin
            // main data
            // $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu', array('url' => $this->uri->uri_string()))['title'];
            $data['load_view'] = 'job_profile/reportjp_editor_jobprofile_v';
            // additional styles and custom script
            $data['custom_script'] = array('plugins/datatables/script_datatables', 'plugins/ckeditor/script_ckeditor.php', 'job_profile/script_jobprofile','job_profile/script_edit_jobprofile');

            // $this->load->view('templates/user_header', $data);
            // $this->load->view('templates/user_sidebar', $data);
            // $this->load->view('templates/user_topbar', $data);
            // $this->load->view('job_profile/reportjp_v', $data);
            // $this->load->view('templates/jobs_footer_editor');
        } else {
            // main data
            // $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu', array('url' => $this->uri->uri_string()))['title'];
            $data['load_view'] = 'job_profile/reportjp_viewer_jobprofile_v';
            // additional styles and custom script
            $data['custom_script'] = array('plugins/datatables/script_datatables', 'job_profile/script_jobprofile','job_profile/script_view_jobprofile');

            // $this->load->view('templates/user_header', $data);
            // $this->load->view('templates/user_sidebar', $data);
            // $this->load->view('templates/user_topbar', $data);
            // $this->load->view('job_profile/reportjp_view_v', $data);
            // $this->load->view('templates/jobs_footer_editor');
        }

        $data['table_position'] = $this->table_position;

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = "Report Job Profile";
        $data['userApp_admin'] = $this->userApp_admin;
        //additional styles and custom script
        $data['custom_styles'] = array('jobprofile_styles');
        $data['additional_styles'] = array('job_profile/styles_jobprofile.php', 'plugins/datatables/styles_datatables');

        $this->load->view('main_v', $data);
    }

/* -------------------------------------------------------------------------- */
/*                             report main method                             */
/* -------------------------------------------------------------------------- */
    public function report(){
        $nik = $this->session->userdata('nik'); //get nik
        $my_position = $this->Jobpro_model->getDetail('position_id', 'master_employee', array('nik' => $nik))['position_id']; //ambil my_position
        $role_id = $this->Jobpro_model->getDetail('role_id', 'master_employee', array('nik' => $nik))['role_id']; //ambil role_id

        if($role_id == 1 || $this->userApp_admin == 1){ // cek role_id apakah punya hak akses admin atau userappadmin
            $task = $this->Jobpro_model->getAllAndOrder('id_posisi', 'jobprofile_approval');
            $data['dept'] = $this->Jobpro_model->getAllAndOrder('nama_departemen', 'master_department');
            $data['divisi'] = $this->Jobpro_model->getAllAndOrder('division', 'master_division');
        } else {
            $task1 = $this->Jobpro_model->getJoin2tables('*', 'jobprofile_approval', array('table' => $this->table_position, 'index' => $this->table_position .'.id = jobprofile_approval.id_posisi', 'position' => 'left'), "(id_approver1=".$my_position." AND status_approval=0) OR (id_approver1=".$my_position." AND status_approval=2) OR (id_approver1=".$my_position." AND status_approval=3) OR (id_approver1=".$my_position." AND status_approval=4)"); //cari approval di my position
            $task2 = $this->Jobpro_model->getJoin2tables('*', 'jobprofile_approval', array('table' => $this->table_position, 'index' => $this->table_position .'.id = jobprofile_approval.id_posisi', 'position' => 'left'), "(id_approver2=".$my_position." AND status_approval=0) OR (id_approver2=".$my_position." AND status_approval=1) OR (id_approver2=".$my_position." AND status_approval=3) OR (id_approver2=".$my_position." AND status_approval=4)");
            $task = array_merge($task1, $task2);

            $my_div = $this->Jobpro_model->getDetail('div_id', $this->table_position, array('id' => $my_position)); //ambil my_position
            $data['dept'] = $this->Jobpro_model->getDetails('nama_departemen', 'master_department', $my_div); //ambil departemen sesuai divisinya
        }

        $data['title'] = 'Report';
        $data['jp_user'] = $this->db->get_where('master_employee', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['hirarki_org'] = $this->Jobpro_model->getDetail('hirarki_org', $this->table_position, array('id' => $data['jp_user']['position_id']))['hirarki_org'];
        $data['approval_data'] = $this->getApprovalDetails($task);

        // $this->load->view('templates/user_header', $data);
        // $this->load->view('templates/user_sidebar', $data);
        // $this->load->view('templates/user_topbar', $data);
        // $this->load->view('job_profile/report_v', $data);
        // $this->load->view('templates/report_footer');

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
		// $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu', array('url' => $this->uri->uri_string()))['title'];
        $data['page_title'] = 'Report Profile';
        $data['userApp_admin'] = $this->userApp_admin;
		$data['load_view'] = 'job_profile/report_jobprofile_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		// $data['custom_styles'] = array('jobprofile_styles');
        $data['custom_script'] = array('plugins/datatables/script_datatables', 'job_profile/script_report_jobprofile');
        
		$this->load->view('main_v', $data);
    }
/* -------------------------------------------------------------------------- */


/* -------------------------------------------------------------------------- */
/*                                another code                                */
/* -------------------------------------------------------------------------- */
    public function getDataJP($nik, $id_posisi){
        $data['posisi']        = $this->Jobpro_model->getDetail('*', $this->table_position, array('id' => $id_posisi));
        $data['mydiv']         = $this->Jobpro_model->getDetail("*", 'master_division', array('id' => $data['posisi']['div_id']));
        $data['mydept']        = $this->Jobpro_model->getDetail('*', 'master_department', array('id' => $data['posisi']['dept_id']));
        $data['staff']         = $this->Jobpro_model->getStaff($data['posisi']['id']);
        $data['tujuanjabatan'] = $this->Jobpro_model->getProfileJabatan($data['posisi']['id']);

        if(!empty($nik)){
            $data['emp_name'] = $this->Jobpro_model->getDetail("emp_name", "master_employee", array('nik' => $nik));
        }

        $data['statusApproval'] = $this->db->get_where('jobprofile_approval', ['id_posisi' => $data['posisi']['id']])->row_array(); //get status approval

        //olah data chart
        //cek jika atasan 1 bukan CEO dan 0
        if($data['posisi']['id_atasan1'] != 0){
            // if($data['posisi']['id_atasan1'] != 1 && $data['posisi']['id_atasan1'] != 0){
            // Olah data orgchart
            $org_data = $this->olahDataChart($data['posisi']['id']);
        } elseif($data['posisi']['id_atasan1'] != 0 && $data['posisi']['div_id'] == 1){
            $org_data = $this->olahDataChart($data['posisi']['id']);
        // } elseif($data['posisi']['id_atasan1'] == 1){
        //     $org_data = $this->olahDataChart($data['posisi']['id']);
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

        return $data;
    }

    /* -------------------------------------------------------------------------- */
    /*                       untuk menambahkan task lainnya                       */
    /* -------------------------------------------------------------------------- */
    public function getMyTaskVacant(){ // ambil vacant task
        $my_task_vacant = array(); $x = 0; //prepare variables
        $id_vacant_task = $this->Jobpro_model->getJoin2tables(  $this->table_position .'.id', 
                                                                $this->table_position, 
                                                                array('table' => 'master_employee', 'index' => 'master_employee.position_id = '. $this->table_position .'.id', 'position' => 'left'), 
                                                                "master_employee.position_id IS NULL"
                                                            ); //ambil id position vacant

        foreach($id_vacant_task as $idpos_vacant){ //dapetin id Position Vacant
            //aturan getMyTask
            //$this->Jobpro_model->getMyTask(id_posisi, 'kolom_approver_di_database, status approval);
            if(!empty($value = $this->Jobpro_model->getMyTask($idpos_vacant['id'], 'id_approver1', '1'))){ //dapetin my task vacant approver 1
                foreach($value as $v){
                    $my_task_vacant[$x] = $v;
                    $x++;
                }
            } else {
                //nothing
            }
        }
        foreach($id_vacant_task as $idpos_vacant){ //dapetin id Position Vacant
            //aturan getMyTask
            //$this->Jobpro_model->getMyTask(id_posisi, 'kolom_approver_di_database, status approval);
            if(!empty($value = $this->Jobpro_model->getMyTask($idpos_vacant['id'], 'id_approver2', '2'))){ //dapetin my task vacant approver 2
                foreach($value as $v){
                    $my_task_vacant[$x] = $v;
                    $x++;
                }
            } else {
                //nothing
            }
        }
        
        return $my_task_vacant;
    }

    function getCeoTask(){ // ambil tasknya CEO
        $ceoTask = array(); $x = 0; // prepare variables
        // ambil id posisi yang atasan 1nya CEO
        $id_ceo_task = $this->Jobpro_model->getDetails('id', $this->table_position, 'id_approver1 = "1" OR id_approver2 = "1"'); // ambil id posisi yang atasan 1nya atau atasan 2nya CEO
        
        foreach($id_ceo_task as $value){
            if(!empty($value = $this->Jobpro_model->getJoin2tables( '*', 'jobprofile_approval', array('table' => $this->table_position, 'index' => 'jobprofile_approval.id_posisi = '. $this->table_position .'.id', 'position' => 'left'), array('id' => $value['id'], 'status_approval' => '1', 'id_approver1' => '1')))){ //ambil task CEO dengan status approval = 1
                $ceoTask[$x] = $value[0]; // ambil yang pertama karena isinya cuma 1
                $x++;
            }
        }

        foreach($id_ceo_task as $value){
            if(!empty($value = $this->Jobpro_model->getJoin2tables( '*', 'jobprofile_approval', array('table' => $this->table_position, 'index' => 'jobprofile_approval.id_posisi = '. $this->table_position .'.id', 'position' => 'left'), array('id' => $value['id'], 'status_approval' => '2', 'id_approver2' => '1')))){ //ambil task CEO dengan status approval = 2
                $ceoTask[$x] = $value[0]; // ambil yang pertama karena isinya cuma 1
                $x++;
            }
        }

        return $ceoTask;
    }

    public function taskAction(){ // apabila atasan mengklik tombol approve atau revise
        $pesan_revisi = $this->input->post('pesan_revisi');
        $nik = $this->input->post('nik');
        $id_posisi = $this->input->post('id_posisi');
        $status_sebelum = $this->input->post('status_sebelum');
        $status_approval = $this->input->post('status_approval');
        $approver = $this->Jobpro_model->getDetail('id_approver1, id_approver2', $this->table_position, array('id' => $id_posisi));
        $name_karyawan = $this->input->post('name_karyawan');

        // cek apa punya approver2
        if($approver['id_approver2'] != 0){
            //cek status_approval
            if($status_approval == "true"){ //jika disetujui
                if($status_sebelum == 1){ //atasan 1
                    $data = [
                        'diperbarui' => time(),
                        'status_approval' => '2',
                        'pesan_revisi' => 'null'
                    ];
                    $this->Jobpro_model->updateApproval($data, $id_posisi);

                    $data_approver2 = $this->Jobpro_model->getDetail('emp_name, email', 'master_employee', array('position_id' => $approver['id_approver2']));  //ambil email karyawan dengan id approver 1 

                    //ambil email karyawan buat cc
                    $x = 0; $email_cc = array();
                    foreach($this->Jobpro_model->getDetails('email', 'master_employee', array('position_id' => $id_posisi)) as $v){
                        $email_cc[$x] = $v['email'];
                        $x++;
                    }

                    $data_penerima_email = array(
                        'nama'      => $data_approver2['emp_name']. ",",
                        'email'     => $data_approver2['email'],
                        'email_cc'  => $email_cc,
                        'id_posisi' => $approver['id_approver2'],
                        'msg'       => 'There is a new employe waiting for your approval!'
                    );

                    $job_profile = array( //data job profile karyawan
                        'id_posisi' => $id_posisi,
                        'position_name' => $this->Jobpro_model->getDetail('position_name', $this->table_position, array('id' => $id_posisi))['position_name'],
                        'status'        => $data['status_approval']
                    );

                    $subject_email = '[Job Profile] Final Approval';

                } elseif($status_sebelum == 2){ //atasan 2, selesaikan task
                    $data = [
                        'diperbarui' => time(),
                        'status_approval' => '4',
                        'pesan_revisi' => 'null'
                    ];
                    $this->Jobpro_model->updateApproval($data, $id_posisi);
                    
                    $data_karyawan = $this->Jobpro_model->getDetails('emp_name, email', 'master_employee', array('position_id' => $id_posisi));  //ambil email karyawan dengan id approver 1 
                    /* --------------------------- ambil nama karyawan -------------------------- */
                    $counter_karyawan = count($data_karyawan);
                    $karyawan = array('<ul>'); //buka ul
                    foreach($data_karyawan as $key => $value){ //ambil nama karyawan)
                        $karyawan[$key + 1] = '<li> -  '. $value['emp_name'] .'</li>';
                        if($key+1 == $counter_karyawan){ //tutup kode ul
                            $karyawan[$key + 2] = '</ul>';
                        }
                    }
                    /* -------------------------- ambil email karyawan -------------------------- */
                    foreach($data_karyawan as $key => $value){
                        $karyawan_email[$key] = $value['email'];
                    }
                    
                    // $email_cc[0] = $this->Jobpro_model->getDetail('email', 'master_employee', array('position_id' => $approver['id_approver1']))['email'];
                    // $email_cc[1] = $this->Jobpro_model->getDetail('email', 'master_employee', array('position_id' => $approver['id_approver2']))['email'];

                    $email_cc = array(); $x = 0;
                    if(!empty($value = $this->Jobpro_model->getDetail('email', 'master_employee', array('position_id' => $approver['id_approver1']))['email'])){
                        $email_cc[$x] = $value;
                        $x++;
                    }
                    if(!empty($value = $this->Jobpro_model->getDetail('email', 'master_employee', array('position_id' => $approver['id_approver2']))['email'])){
                        $email_cc[$x] = $value;
                        $x++;
                    }

                    $data_penerima_email = array(
                        'nama'      => implode(" ", $karyawan),
                        'email'     => $karyawan_email,
                        'email_cc'  => $email_cc,
                        'id_posisi' => $id_posisi
                    );

                    $job_profile = array( //data job profile karyawan
                        'id_posisi' => $id_posisi,
                        'position_name' => $this->Jobpro_model->getDetail('position_name', $this->table_position, array('id' => $id_posisi))['position_name'],
                        'status'        => $data['status_approval']
                    );

                    $subject_email = '[Job Profile] Approved';

                } else {
                    show_404(); //error
                }
            } elseif($status_approval == "false") { // revisi jika tidak diapprove
                if(!empty($pesan_revisi)){
                    $simpan_pesan = $pesan_revisi;
                } else {
                    $simpan_pesan = 'null';
                }
                $data = [
                    'diperbarui' => time(),
                    'status_approval' => '3',
                    'pesan_revisi' => $simpan_pesan
                ];
                $this->Jobpro_model->updateApproval($data, $id_posisi);

                $data_karyawan = $this->Jobpro_model->getDetails('emp_name, email', 'master_employee', array('position_id' => $id_posisi));  //ambil email karyawan dengan id approver 1 
                /* --------------------------- ambil nama karyawan -------------------------- */
                $counter_karyawan = count($data_karyawan);
                $karyawan = array('<ul>'); //buka ul
                foreach($data_karyawan as $key => $value){ //ambil nama karyawan)
                    $karyawan[$key + 1] = '<li> -  '. $value['emp_name'] .'</li>';
                    if($key+1 == $counter_karyawan){ //tutup kode ul
                        $karyawan[$key + 2] = '</ul>';
                    }
                }
                /* -------------------------- ambil email karyawan -------------------------- */
                foreach($data_karyawan as $key => $value){
                    $karyawan_email[$key] = $value['email'];
                }

                // pengaturan email cc
                if($status_sebelum == 1){ // jika status sebelumnya adalah first approval
                    $email_cc = array(); $x = 0;
                    if(!empty($value = $this->Jobpro_model->getDetail('email', 'master_employee', array('position_id' => $approver['id_approver1']))['email'])){
                        $email_cc[$x] = $value;
                        $x++;
                    }
                } elseif($status_sebelum == 2){ // jika status sebelumnya adalah final approval
                    $email_cc = array(); $x = 0;
                    if(!empty($value = $this->Jobpro_model->getDetail('email', 'master_employee', array('position_id' => $approver['id_approver2']))['email'])){
                        $email_cc[$x] = $value;
                        $x++;
                    }
                }

                $data_penerima_email = array(
                    'nama'      => implode(" ", $karyawan),
                    'email'     => $karyawan_email,
                    'email_cc'  => $email_cc,
                    'id_posisi' => $id_posisi,
                    'msg'       => 'Please revise your Job Profile.'
                );

                $job_profile = array( //data job profile karyawan
                    'id_posisi' => $id_posisi,
                    'position_name' => $this->Jobpro_model->getDetail('position_name', $this->table_position, array('id' => $id_posisi))['position_name'],
                    'status'        => $data['status_approval']
                );

                $subject_email = '[Job Profile] Need Revise';
            
            } else {
                show_404(); //error
            }
        }else{
            //cek status_approval
            if($status_approval == "true"){ //jika disetujui selesaikan approval
                $data = [
                    'diperbarui' => time(),
                    'status_approval' => '4',
                    'pesan_revisi' => 'null'
                ];
                $this->Jobpro_model->updateApproval($data, $id_posisi);

                $data_karyawan = $this->Jobpro_model->getDetails('emp_name, email', 'master_employee', array('position_id' => $id_posisi));  //ambil email karyawan dengan id approver 1 
                /* --------------------------- ambil nama karyawan -------------------------- */
                $counter_karyawan = count($data_karyawan);
                $karyawan = array('<ul>'); //buka ul
                foreach($data_karyawan as $key => $value){ //ambil nama karyawan)
                    $karyawan[$key + 1] = '<li> -  '. $value['emp_name'] .'</li>';
                    if($key+1 == $counter_karyawan){ //tutup kode ul
                        $karyawan[$key + 2] = '</ul>';
                    }
                }
                /* -------------------------- ambil email karyawan -------------------------- */
                foreach($data_karyawan as $key => $value){
                    $karyawan_email[$key] = $value['email'];
                }
                
                $value = $this->Jobpro_model->getDetail('email', 'master_employee', array('position_id' => $approver['id_approver1']))['email'];
                if(!empty($value)){
                    $email_cc = $value;
                } else {
                    $email_cc = "";
                }

                $data_penerima_email = array(
                    'nama'      => implode(" ", $karyawan),
                    'email'     => $karyawan_email,
                    'email_cc'  => $email_cc,
                    'id_posisi' => $id_posisi
                );

                $job_profile = array( //data job profile karyawan
                    'id_posisi' => $id_posisi,
                    'position_name' => $this->Jobpro_model->getDetail('position_name', $this->table_position, array('id' => $id_posisi))['position_name'],
                    'status'        => $data['status_approval']
                );

                $subject_email = '[Job Profile] Approved';

            } elseif($status_approval == "false") {
                //cek apa ada pesan revisi
                if(!empty($pesan_revisi)){
                    $simpan_pesan = $pesan_revisi;
                } else {
                    $simpan_pesan = 'null';
                }
                $data = [
                    'diperbarui' => time(),
                    'status_approval' => '3',
                    'pesan_revisi' => $simpan_pesan
                ];
                $this->Jobpro_model->updateApproval($data, $id_posisi);

                $data_karyawan = $this->Jobpro_model->getDetails('emp_name, email', 'master_employee', array('position_id' => $id_posisi));  //ambil email karyawan dengan id approver 1 
                /* --------------------------- ambil nama karyawan -------------------------- */
                $counter_karyawan = count($data_karyawan);
                $karyawan = array('<ul>'); //buka ul
                foreach($data_karyawan as $key => $value){ //ambil nama karyawan)
                    $karyawan[$key + 1] = '<li> -  '. $value['emp_name'] .'</li>';
                    if($key+1 == $counter_karyawan){ //tutup kode ul
                        $karyawan[$key + 2] = '</ul>';
                    }
                }
                /* -------------------------- ambil email karyawan -------------------------- */
                foreach($data_karyawan as $key => $value){
                    $karyawan_email[$key] = $value['email'];
                }

                if(!empty($value = $this->Jobpro_model->getDetail('email', 'master_employee', array('position_id' => $approver['id_approver1']))['email'])){
                    $email_cc = $value;
                } else {
                    $email_cc = "";
                }

                $data_penerima_email = array(
                    'nama'      => implode(" ", $karyawan),
                    'email'     => $karyawan_email,
                    'email_cc'  => $email_cc,
                    'id_posisi' => $id_posisi,
                    'msg'       => 'Please revise your Job Profile.!'
                );

                $job_profile = array( //data job profile karyawan
                    'id_posisi' => $id_posisi,
                    'position_name' => $this->Jobpro_model->getDetail('position_name', $this->table_position, array('id' => $id_posisi))['position_name'],
                    'status'        => $data['status_approval']
                );

                $subject_email = '[Job Profile] Need Revise';
            } else {
                show_404(); //error
            }
        }

        //kirim notifikasi
        $this->notifikasi($nik, $job_profile, $data_penerima_email, $subject_email);
        header('location: ' . base_url('job_profile'));
    }
    public function setVerifyJP(){
        $verify_od = $this->input->post('verify_od');
        $id_posisi = $this->input->post('id_posisi');

        if(!empty($verify_od)){
            $this->Jobpro_model->update('jobprofile_approval', array('db' => 'id_posisi', 'server' => $id_posisi), array('verify' => '1'));
        } else {
            $this->Jobpro_model->update('jobprofile_approval', array('db' => 'id_posisi', 'server' => $id_posisi), array('verify' => '0'));
        }
    }
    
    /**
     * getEmailCCAtasan
     *
     * @param  mixed $id_posisi
     * @return void
     */
    function getEmailCCAtasan($id_posisi){
        // cek apa ada data approvernya
        $value = array(); $value = $this->Jobpro_model->getJoin2tables('id, status_approval, id_approver1, id_approver2, position_name', $this->table_position, array('table' => 'jobprofile_approval', 'index' => 'jobprofile_approval.id_posisi = '. $this->table_position .'.id', 'position' => 'left'), array('id' => $id_posisi))[0];
        if(!empty($value)){ //ambil data posisi
            $data_posisi = $value;
        } else {
            return "";
        }
        $email_cc = array(); $x = 0; //prepare variables
        if($data_posisi['id_approver1'] != 0 && $data_posisi['id_approver1'] != 1){ //jika id atasannya bukan 0 dan CEO
            $value = array(); $value = $this->Jobpro_model->getDetail('email', 'master_employee', array('position_id' => $data_posisi['id_approver1']));
            if(!empty($value)){
                $email_cc[$x] = $value['email'];
                $x++;
            } else {
                // nothing
            }
        }
        if($data_posisi['id_approver2'] != 0 && $data_posisi['id_approver2'] != 1){ // jika id atasannya bukan 0 dan CEO
            $value = array(); $value = $this->Jobpro_model->getDetail('email', 'master_employee', array('position_id' => $data_posisi['id_approver2']));
            if(!empty($value)){
                $email_cc[$x] = $value['email'];
                $x++;
            }
        }
        if($data_posisi['id_approver1'] == 1){ // kosongkan cc jika id approver1 adalah CEO
            $email_cc = "";
        }
        if($data_posisi['id_approver1'] == 0 && $data_posisi['id_approver2'] == 0){ // jika id approver 1 dan 2 nya 0
            $email_cc = "";
        }

        return $email_cc;
    }
    
    /* -------------------------------------------------------------------------- */
    /*        Komponen function buat mengirim notifikasi email ke karyawan        */
    /* -------------------------------------------------------------------------- */
    /**
     * notifikasi
     *
     * @param  mixed $nik
     * @param  mixed $job_profile
     * @param  mixed $data_penerima_email
     * @param  mixed $subject_email
     * @return void
     */
    public function notifikasi($nik, $job_profile, $data_penerima_email, $subject_email){
        // CG000001
        // Array ( [id_posisi] => 1 [position_name] => Chief Executive Officer [status] => 0 )
        // Array ( [nama] =>

        //     - Yan Raymond Jafri

        // [email] => Array ( [0] => yan.raymond@centratamagroup.com ) [email_cc] => [id_posisi] => 1 [msg] => Please fill your Job Profile and submit it! )
        // [Job Profile] Create Job Profile

        if($job_profile['status'] != 4){ // cek jika status approval bukan final
            /* ------------------- create webtoken buat penerima email ------------------ */
            $resep = array( // buat resep token agar unik
                'nik' => $nik,
                'id_posisi' => $data_penerima_email['id_posisi'],
                'date' => date('d-m-Y, H:i:s:v:u', time())
            );
            $token = md5(json_encode($resep)); // md5 encrypt buat id token
            $temp_token  = array( // data buat disave di token
                'direct'    => 'job_profile',
                'id_posisi' => $data_penerima_email['id_posisi']
            );
            if(!empty($data_penerima_email['msg'])){ // sematkan pesan ke data token
                $temp_token['msg'] = $data_penerima_email['msg'];
            }
            $data_token = json_encode($temp_token);

            // masukkan data token ke database
            $this->Jobpro_model->insert(
                'user_token',
                array(
                    'token'        => $token,
                    'data'         => $data_token,
                    'date_created' => date('Y-m-d H:i:s', time())
                )
            ); 
            $url_token = urlencode($token);
            //info penerima email tambahkan url
            $data_penerima_email['link'] = base_url('direct').'?token='.$url_token;
        }
        
        /* --------------------------- buat list karyawan --------------------------- */
        $data_karyawan = $this->Jobpro_model->getDetails('emp_name, email', 'master_employee', array('position_id' => $job_profile['id_posisi']));
        $counter_karyawan = count($data_karyawan);
        $karyawan = array('<ul>'); //buka ul
        foreach($data_karyawan as $key => $value){ //ambil nama karyawan)
            $karyawan[$key + 1] = '<li> -  '. $value['emp_name'] .'</li>';
            
            if($key+1 == $counter_karyawan){ //tutup kode ul
                $karyawan[$key + 2] = '</ul>';
            }
        }
        // info job profile tambahkan karyawan
        $job_profile['karyawan'] = implode(" ", $karyawan);

        $emailText = jobProfileNotif($job_profile, $data_penerima_email); // generate emailText
        //set penerima email adalah approver 1
        // sendEmail($penerima, $emailText, $subject_email)
        sendEmail($data_penerima_email, $emailText, $subject_email); // kirim email notifikasi pakai helper
    }
    
    public function insatasan(){
        $data= [
            'id_atasan1' => $this->input->post('position')
        ];

        $this->db->where('id', $this->input->post('id'));
        $this->db->update($this->table_position, $data);

        $datajabatan = [
            'id_posisi' => $this->session->userdata('position_id')
        ];
        $this->db->insert('jobprofile_profilejabatan', $datajabatan);
        
        redirect('job_profile','refresh');
    }
    
    //tujuan jabatan
    public function edittujuanjbtn($id){
        $data['title'] = 'Ubah Tujuan Jabatan';
        $data['tujab'] = $this->Jobpro_model->getTujabById($id);
        $data['user'] = $this->db->get_where('master_employee', ['nik' => $this->session->userdata('nik')])->row_array();

        $this->form_validation->set_rules('tujuan_jabatan', 'Form', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // $this->load->view('templates/user_header', $data);
            // $this->load->view('templates/user_sidebar', $data);
            // $this->load->view('templates/user_topbar', $data);
            // $this->load->view('job_profile/edittujab', $data);
            // $this->load->view('templates/jobs_footer_editor');
        } else {
            $this->Jobpro_model->updateTuJab();
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Your Profile Has Been Updated ! </div>');
            redirect('user/jobprofile');
        }
    }

    public function uptuj(){
        $id = $this->input->post('id');
        $tujuan = $this->input->post('tujuan');

        $this->Jobpro_model->insert('jobprofile_profilejabatan', array('id_posisi' => $id, 'tujuan_jabatan' => $tujuan));
    }

    public function edittujuan(){
        $data = [
            'tujuan_jabatan' => $this->input->post('tujuan')
        ];
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('jobprofile_profilejabatan', $data);
    }

    public function addTanggungJawab(){
        $data = [
            'keterangan'      => $this->input->post('tanggung_jawab'),
            'list_aktivitas'  => $this->input->post('aktivitas'),
            'list_pengukuran' => $this->input->post('pengukuran'),
            'id_posisi'       => $this->input->post('id_posisi')
        ];
        $this->Jobpro_model->insert('jobprofile_tanggungjawab', $data);
    }

    public function editTanggungJawab(){
        $data = [
            'keterangan'      => $this->input->post('tanggung_jawab'),
            'list_aktivitas'  => $this->input->post('aktivitas'),
            'list_pengukuran' => $this->input->post('pengukuran')
        ];
        $where = array(
            'db' => 'id_tgjwb',
            'server' => $this->input->post('idtgjwb')
        );
        $this->Jobpro_model->update('jobprofile_tanggungjawab', $where, $data);
    }
    
    public function getTjByID(){
        echo json_encode($this->Jobpro_model->getTjById($_POST['id']));
    }

    public function hapusTanggungJawab($id){
        // $this->db->where('id_tgjwb', $id);
        // $this->db->delete('jobprofile_tanggungjawab');
        $this->Jobpro_model->delete('jobprofile_tanggungjawab', array('index' => 'id_tgjwb', 'data' => $id));
        // $this->session->set_flashdata('flash', 'Deleted');
        // redirect('job_profile', 'refresh');
    }

    // -----ruang lingkup
    public function addruanglingkup(){
        $id = $this->input->post('id');
        $ruang1 = $this->input->post('ruangl');

        $this->Jobpro_model->insert('jobprofile_ruanglingkup', array('id_posisi' => $id, 'r_lingkup' => $ruang1));
    }
    public function editruanglingkup(){
        $ruang = $this->input->post('ruang');
        if ($ruang) {
            $this->db->set('r_lingkup', $ruang);
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('jobprofile_ruanglingkup');
            echo 'Success';
        }else{
            $ruang = '<b>-</b>';
            $this->db->set('r_lingkup', $ruang);
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('jobprofile_ruanglingkup');
            echo $ruang;
        }
    }

    //wewenang
    public function addwen(){
        $data = [
            'kewenangan' => $this->input->post('wewenang'),
            'wen_sendiri' => $this->input->post('wen_sendiri'),
            'wen_atasan1' => $this->input->post('wen_atasan1'),
            'wen_atasan2' => $this->input->post('wen_atasan2'),
            'id_posisi' => $this->input->post('id')
        ];
        $this->Jobpro_model->insert('jobprofile_wewenang', $data);
    }

    public function aksiwewenang(){
        $aksi = $this->input->post('action');
        $data = array(
            'id' => $this->input->post('id'),
            'kewenangan' => $this->input->post('kewenangan'),
            'wen_sendiri' => $this->input->post('wen_sendiri'),
            'wen_atasan1' => $this->input->post('wen_atasan1'),
            'wen_atasan2' => $this->input->post('wen_atasan2')
        );
        if ($aksi == 'edit') {
            $tableV='';
            if (isset($data['kewenangan'])) {
                $tableV .= "`kewenangan` = '".$data['kewenangan']."'";
            } elseif (isset($data['wen_sendiri'])) {
                $tableV .= "`wen_sendiri` = '".$data['wen_sendiri']."'";
            } elseif (isset($data['wen_atasan1'])) {
                $tableV .= "`wen_atasan1` = '".$data['wen_atasan1']."'";
            } elseif (isset($data['wen_atasan2'])) {
                $tableV .= "`wen_atasan2` = '".$data['wen_atasan2']."'";
            }
            if($tableV && $data['id']){
                $this->db->query("UPDATE `jobprofile_wewenang` SET $tableV WHERE id='".$data['id']."' ");
            }
        }
        if ($aksi == 'delete') {
            $this->db->where('id', $data['id']);
            $this->db->delete('jobprofile_wewenang');
        }
        echo json_encode($aksi);
    }

    // aksihubungan
    public function addHubungan(){
        $id = $this->input->post('id');
        $internal = $this->input->post('internal');
        $eksternal = $this->input->post('eksternal');

        if (!$internal && !$eksternal) {
            $array = [
                'hubungan_int' => '<b>-</b>',
                'hubungan_eks' => '<b>-</b>',
                'id_posisi' => $id
            ];
            $this->Jobpro_model->insert('jobprofile_hubkerja', $array);
        } elseif ($internal && $eksternal){
            $array = [
                'hubungan_int' => $internal,
                'hubungan_eks' => $eksternal,
                'id_posisi' => $id
            ];
            $this->Jobpro_model->insert('jobprofile_hubkerja', $array);
        } elseif($internal && !$eksternal) {
            $array = [
                'hubungan_int' => $internal,
                'hubungan_eks' => '<b>-</b>',
                'id_posisi' => $id
            ];
            $this->Jobpro_model->insert('jobprofile_hubkerja', $array);
        }elseif (!$internal && $eksternal) {
            $array = [
                'hubungan_int' => '<b>-</b>',
                'hubungan_eks' => $eksternal,
                'id_posisi' => $id
            ];
            $this->Jobpro_model->insert('jobprofile_hubkerja', $array);
        }
    }

    public function edithub(){
        $data = [
            'id' => $this->input->post('id'),
            'hubInt' => $this->input->post('hubInt'),
            'hubEks' => $this->input->post('hubEks'),
            'tipe' => $this->input->post('tipe')
        ];

        if ($data['tipe'] == 'internal') {
            $this->db->set('hubungan_int', $data['hubInt']);
            $this->db->where('id', $data['id']);
            $this->db->update('jobprofile_hubkerja');
            echo 'success';
        }
        if ($data['tipe'] == 'eksternal') {
            $this->db->set('hubungan_eks', $data['hubEks']);
            $this->db->where('id', $data['id']);
            $this->db->update('jobprofile_hubkerja');
            echo 'success';
        }
    }

    //aksitantangan
    public function addtantangan(){
        $data = [
            'text' => $this->input->post('tantangan'),
            'id_posisi' => $this->input->post('id')
        ];
        $this->Jobpro_model->insert('jobprofile_tantangan', $data);
    }

    public function edittantangan(){
        $data = [
            'text' => $this->input->post('tantangan')
        ];
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('jobprofile_tantangan', $data);
    }

    // kualifikasi aksi
    public function addkualifikasi(){
        $data = [
            'id_posisi' => $this->input->post('id_posisi'),
            'pendidikan' => $this->input->post('pendidikan'),
            'pengalaman' => $this->input->post('pengalaman'),
            'pengetahuan' => $this->input->post('pengetahuan'),
            'kompetensi' => $this->input->post('kompetensi')
        ];  
        $this->Jobpro_model->insert('jobprofile_kualifikasi', $data);
    }

    public function getKualifikasiById(){
        echo json_encode($this->Jobpro_model->getKualifikasiById($_POST['id']));
    }

    public function updateKualifikasi(){
        $id = $this->input->post('id_posisi');
        $data = [
            'pendidikan' => $this->input->post('pendidikan'),
            'pengalaman' => $this->input->post('pengalaman'),
            'pengetahuan' => $this->input->post('pengetahuan'),
            'kompetensi' => $this->input->post('kompetensi')
        ];


        $this->db->where('id_posisi', $id);
        $this->db->update('jobprofile_kualifikasi', $data);
    }

    //jenjang karir aksi
    public function addjenjangkarir(){
        $data = [
            'id_posisi' => $this->input->post('id'),
            'text' => $this->input->post('jenkar')
        ];
        $this->Jobpro_model->insert('jobprofile_jenjangkar', $data);
    }
    public function editjenjang(){
        $data = [
            'text' => $this->input->post('jenkar')
        ];
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('jobprofile_jenjangkar', $data);
    }
	public function updateStaff(){
        
		$data = [
			'manager' => $this->input->post('mgr'),
			'supervisor' => $this->input->post('spvr'),
			'staff' => $this->input->post('staf')
		];
		$this->db->where('id_posisi', $this->input->post('id_posisi'));
		$this->db->update('jobprofile_jumlahstaff', $data);
		echo 'staff updated';
    }
    
	public function setApprove(){ //Submit to atasan 1
        //post all data
        $nik            = $this->input->post('nik');
        $id_posisi      = $this->input->post('id_posisi');
        $approver1      = $this->input->post('approver1');

        //data approval
        $data = [
			'diperbarui'      => time(),
			'status_approval' => '1',
        ];
        $this->Jobpro_model->updateApproval($data, $id_posisi); // set approval ke database

        $data_approver1 = $this->Jobpro_model->getDetail('emp_name, email', 'master_employee', array('position_id' => $approver1));  //ambil email karyawan dengan id approver 1 

        //ambil email buat cc
        $x = 0; $email_cc = array();
        foreach($this->Jobpro_model->getDetails('email', 'master_employee', array('position_id' => $id_posisi)) as $v){
            $email_cc[$x] = $v['email'];
            $x++;
        }

        $data_penerima_email = array(
            'nama'      => $data_approver1['emp_name'].",",
            'email'     => $data_approver1['email'],
            'email_cc'  => $email_cc,
            'id_posisi' => $approver1,
            'msg'       => 'There is a new employe waiting for your approval!'
        );

        $job_profile = array( //data job profile karyawan
            'id_posisi'     => $id_posisi,
            'position_name' => $this->Jobpro_model->getDetail('position_name', $this->table_position, array('id' => $id_posisi))['position_name'],
            'status'        => $data['status_approval']
        );

        $this->notifikasi($nik, $job_profile, $data_penerima_email, '[Job Profile] First Approval');
    }

    public function getApprovalDetails($my_task){ // Copied from Jobs Controller
        // print_r($my_task);
        //lengkapi data my_task
        $tugas = array(); $x = 0;
        foreach($my_task as $key => $value){
            //cari employe dengan id posisi
            $temp_employe = $this->Jobpro_model->getDetails("nik, emp_name", "master_employee", array('position_id' => $value['id_posisi']));
            if(!empty($temp_employe)){
                foreach($temp_employe as $v){
                    $temp_tugas = array_merge($v, $value);
                    $tugas[$x] = array_merge($temp_tugas, $this->getPositionDetails($value['id_posisi']));
                    $x++;
                }
            }else{
                $tugas[$x] = array_merge($value, $this->getPositionDetails($value['id_posisi']));
                $tugas[$x]['emp_name'] = " ";
                $tugas[$x]['nik'] = " ";
                $x++; //increment the identifier
            }
        }

        return $tugas;
    }

    function getPositionDetails($id_posisi){
        $temp_posisi = $this->Jobpro_model->getDetail("div_id, dept_id, id", $this->table_position, array('id' => $id_posisi));
        // print_r($temp_posisi);
        foreach ($this->Jobpro_model->getDetail("position_name", $this->table_position, array('id' => $temp_posisi['id'])) as $v){// tambahkan nama posisi
            $detail_posisi['posisi'] = $v;
        }
        foreach($this->Jobpro_model->getDetail("nama_departemen", "master_department", array('id' => $temp_posisi['dept_id'])) as $v){// tambahkan nama departemen
            $detail_posisi['departement'] = $v;
        }
        foreach($this->Jobpro_model->getDetail("id", "master_department", array('id' => $temp_posisi['dept_id'])) as $v){// tambahkan id departemen
            $detail_posisi['id_dept'] = $v;
        }
        foreach($this->Jobpro_model->getDetail("division", "master_division", array('id' => $temp_posisi['div_id'])) as $v){// tambahkan nama master_division
            $detail_posisi['divisi'] = $v;
        }
        foreach($this->Jobpro_model->getDetail("id", "master_division", array('id' => $temp_posisi['div_id'])) as $v){// tambahkan id master_division
            $detail_posisi['id_div'] = $v;
        }
        return $detail_posisi;
    }

    //this function to regenerate job_approval starter data
    public function startJobApprovalSystem(){
        foreach($this->Jobpro_model->getDetails('*', $this->table_position, array()) as $k => $v){ //ambil semua nik
            $nik=$v['id'];// pindahkan ke variabel
            $data['posisi'] = $this->Jobpro_model->getPosisi($nik); //cari data posisi

            $job_approval = $this->Jobpro_model->getDetail('*', 'jobprofile_approval', array('id_posisi' => $v['id']));//cek apa sudah ada job_approvalnya
            if(empty($job_approval)){
                $data = [
                    'id_posisi'       => $v['id'],
                    'diperbarui'      => time(),
                    'status_approval' => 0,
                    'pesan_revisi'    => "null"
                ];
                $this->db->insert('jobprofile_approval', $data);
            }else{
                //do nothing
            }
        }
        
        $this->session->set_userdata( array('msgapproval' => '1'));
        header('location: ' . base_url('job_profile/settingApproval'));
    }

    public function getDate(){
        echo(date('d-m-Y, H:i', time()));
    }

    public function setStatusApproval(){
        $id_posisi = $this->input->post('id');
        // $status_approval = $this->input->post('value');

        $data = [
            'status_approval' => $this->input->post('status_approval')
        ];
        $this->Jobpro_model->updateApproval($data, $id_posisi);
    }

    /* -------------------------------------------------------------------------- */
    /*                                AJAX REQUESTS                               */
    /* -------------------------------------------------------------------------- */

    public function ajax_getDepartement(){
        if(!empty($div = $this->input->post('divisi'))){
            //get id divisi
            $div = explode('-', $div);
            // print_r($id_div);
            // exit;
            // $divisi_id = $this->Jobpro_model->getDetail("id", "master_division", array('division' => $this->input->post('divisi')))['id'];
            //ambil data departemen dengan divisi itu
            foreach($this->Jobpro_model->getDetails('*', 'master_department', array('div_id' => $div[1])) as $k => $v){
                $data[$k]=$v;
            }
        } else {
            foreach($this->Jobpro_model->getDetails('*', 'master_department', array()) as $k => $v){
                $data[$k]=$v;
            }
        }
        
        print_r(json_encode($data));

        //bawa balik ke ajax
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
        // get position
        $posisi = $this->posisi_m->getAllWhere(array("div_id" => $divisi, "dept_id" => $departemen));
        // bring back with json
        echo(json_encode($posisi));
    }

/* -------------------------------------------------------------------------- */
/*                            Job Profile Settings                            */
/* -------------------------------------------------------------------------- */
    public function settingApproval() {
        // cek role apa punya akses
        $nik = $this->session->userdata('nik'); //get nik
        $role_id = $this->Jobpro_model->getDetail('role_id', 'master_employee', array('nik' => $nik))['role_id']; //ambil role_id
        if($role_id != 1){
            show_404();
        }

        // siapkan data
        $task = $this->Jobpro_model->getAllAndOrder('id_posisi', 'jobprofile_approval');
        $data['dept'] = $this->Jobpro_model->getAllAndOrder('nama_departemen', 'master_department');
        $data['divisi'] = $this->Jobpro_model->getAllAndOrder('division', 'master_division');

        $data['title'] = 'Approval Setting';
        $data['user'] = $this->db->get_where('master_employee', ['nik' => $this->session->userdata('nik')])->row_array();
        $data['hirarki_org'] = $this->Jobpro_model->getDetail('hirarki_org', $this->table_position, array('id' => $data['user']['position_id']))['hirarki_org'];
        $data['approval_data'] = $this->getApprovalDetails($task);
        
        // $this->load->view('templates/user_header', $data);
        // $this->load->view('templates/user_sidebar', $data);
        // $this->load->view('templates/user_topbar', $data);
        // $this->load->view('job_profile/setting_approval_v', $data);
        // $this->load->view('templates/report_footer');

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = "Approval Settings";
        $data['userApp_admin'] = $this->userApp_admin;
		$data['load_view'] = 'job_profile/approval_settings_jobprofile_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		// $data['custom_styles'] = array('survey_styles');
        $data['custom_script'] = array('plugins/datatables/script_datatables', 'job_profile/script_approvalsettings_jobprofile');
        
		$this->load->view('main_v', $data);
    }

    //kirim notifikasi ketika tombol kirim email di pencet
    public function sendNotification(){
        // data yang diperlukan
        $id_posisi = $this->input->post('id_posisi');
        $nik = $this->input->post('nik');

        //data posisi karyawan
        $data_posisi = $this->Jobpro_model->getJoin2tables('id, status_approval, id_approver1, id_approver2, position_name', $this->table_position, array('table' => 'jobprofile_approval', 'index' => 'jobprofile_approval.id_posisi = '. $this->table_position .'.id', 'position' => 'left'), array('id' => $id_posisi))[0];
        
        if($data_posisi['status_approval'] == 1 || $data_posisi['status_approval'] == 2){
            if($data_posisi['status_approval'] == 1){
                if($data_posisi['id_approver1'] == 0){ // apa ada approver 1
                    http_response_code(500);
                    $status = array('txtStatus' => 'Karyawan tidak memiliki Approver 1', 'header' => 'Tidak ada Approver1');
                    echo(json_encode($status));
                    exit;
                }

                $temp_penerima = $this->Jobpro_model->getDetail('emp_name, email', 'master_employee', array('position_id' => $data_posisi['id_approver1']));
                if(empty($temp_penerima)){ // apa ada penerima di approver 1
                    http_response_code(500);
                    $status = array('txtStatus' => 'Posisi Approver 1 tidak memiliki karyawan', 'header' => 'Tidak dapat mengirim email');
                    echo(json_encode($status));
                    exit;
                }
                if(empty($temp_penerima['email'])){ // apa approver 1 punya email
                    http_response_code(500);
                    $status = array('txtStatus' => 'Posisi Approver 1 tidak memiliki Email.', 'header' => 'Tidak dapat mengirim email');
                    echo(json_encode($status));
                    exit;
                }
                
                //siapkan data
                $penerima_nama = $temp_penerima['emp_name'];
                $penerima_email = $temp_penerima['email'];
                $penerima_id_posisi = $data_posisi['id_approver1'];
                $subject_email = '[Job Profile] First Approval';

            } elseif ($data_posisi['status_approval'] == 2){
                if($data_posisi['id_approver2'] == 0){ //apa approver 2 ada
                    http_response_code(500);
                    echo(json_encode(array('txtStatus' => 'Karyawan tidak memiliki Approver 2', 'header' => 'Tidak ada Approver2')));
                    exit;
                }

                $temp_penerima =  $this->Jobpro_model->getDetail('emp_name, email', 'master_employee', array('position_id' => $data_posisi['id_approver2']));
                if(empty($temp_penerima)){ // apa ada emplye approver 2
                    http_response_code(500);
                    $status = array('txtStatus' => 'Posisi Approver 2 tidak memiliki karyawan', 'header' => 'Tidak dapat mengirim email');
                    echo(json_encode($status));
                    exit;
                }
                if(empty($temp_penerima['email'])){ // apa approver 2 ada email
                    http_response_code(500);
                    $status = array('txtStatus' => 'Posisi Approver 2 tidak memiliki email.', 'header' => 'Tidak dapat mengirim email');
                    echo(json_encode($status));
                    exit;
                }

                //siapkan data
                $penerima_nama = $temp_penerima['emp_name'];
                $penerima_email = $temp_penerima['email'];
                $penerima_id_posisi = $data_posisi['id_approver2'];
                $subject_email = '[Job Profile] Final Approval';

            }else{
                show_404();
                exit;
            }
            //ambil email buat cc
            $x = 0; $email_cc = array();
            foreach($this->Jobpro_model->getDetails('email', 'master_employee', array('position_id' => $id_posisi)) as $v){ // beri email cc ke karyawan
                $email_cc[$x] = $v['email'];
                $x++;
            }
            // email more data
            $penerima_msg = 'There is a new employe waiting for your approval!';

        } elseif($data_posisi['status_approval'] == 0 || $data_posisi['status_approval'] == 3 || $data_posisi['status_approval'] == 4) {
            // cek status approval
            if($data_posisi['status_approval'] == 0){ // status need to submit
                $subject_email = '[Job Profile] Create Job Profile';
                $penerima_msg = 'Please fill your Job Profile and submit it!';
            
                // cek apa ada data approvernya
                $value = array(); $value = $this->Jobpro_model->getJoin2tables('id, status_approval, id_approver1, id_approver2, position_name', $this->table_position, array('table' => 'jobprofile_approval', 'index' => 'jobprofile_approval.id_posisi = '. $this->table_position .'.id', 'position' => 'left'), array('id' => $id_posisi))[0];
                if(!empty($value)){ //ambil data posisi
                    $data_posisi = $value;
                } else {
                    return "";
                }
                $email_cc = array(); $x = 0; //prepare variables
                if($data_posisi['id_approver1'] != 0 && $data_posisi['id_approver1'] != 1){ //jika id atasannya bukan 0 dan CEO
                    $value = array(); $value = $this->Jobpro_model->getDetail('email', 'master_employee', array('position_id' => $data_posisi['id_approver1'])); // ambil data approver 1
                    if(!empty($value)){
                        $email_cc[$x] = $value['email'];
                        $x++;
                    } else {
                        // nothing
                    }
                }
                if($data_posisi['id_approver1'] == 1){ // kosongkan email cc jika approver1 = 1
                    $email_cc = "";
                }
                if($data_posisi['id_approver1'] == 0 && $data_posisi['id_approver2'] == 0){ // jika id approver 1 dan 2 nya 0
                    $email_cc = "";
                }

            } elseif ($data_posisi['status_approval'] == 3){ // need revise
                $subject_email = '[Job Profile] Need Revise';
                $penerima_msg = 'Please revise your Job Profile and submit it!';
                // $email_cc = $this->getEmailCCAtasan($id_posisi); // cc email atasan
                $email_cc = "";

            } elseif ($data_posisi['status_approval'] == 4){ // approved
                $subject_email = '[Job Profile] Approved';
                $penerima_msg = 'Thank You!';
                // $email_cc = $this->getEmailCCAtasan($id_posisi); // cc email atasan
                $email_cc = "";

            } else {
                show_404();
                exit;
            }
            //ambil email buat cc
            // $x = 0; $email_cc = array();
            // foreach($this->Jobpro_model->getDetails('email', 'master_employee', array('position_id' => $data_posisi['id_approver1'])) as $v){
            //     if(!empty($v['email'])){
            //         $email_cc[$x] = $v['email'];
            //         $x++;
            //     }
            // }
            // foreach($this->Jobpro_model->getDetails('email', 'master_employee', array('position_id' => $data_posisi['id_approver2'])) as $v){
            //     if(!empty($v['email'])){
            //         $email_cc[$x] = $v['email'];
            //         $x++;
            //     }
            // }

            $data_karyawan = $this->Jobpro_model->getDetails('emp_name, email', 'master_employee', array('position_id' => $id_posisi));  //ambil email karyawan
            // /* --------------------------- ambil nama karyawan -------------------------- */
            $counter_karyawan = count($data_karyawan);
            $karyawan = array('<ul>'); //buka ul
            foreach($data_karyawan as $key => $value){ //ambil nama karyawan)
                $karyawan[$key + 1] = '<li> -  '. $value['emp_name'] .'</li>';
                if($key+1 == $counter_karyawan){ //tutup kode ul
                    $karyawan[$key + 2] = '</ul>';
                }
            }
            /* -------------------------- ambil email karyawan -------------------------- */
            $x=0;
            foreach($data_karyawan as $key => $value){
                if(!empty($value['email'])){
                    $karyawan_email[$x] = $value['email'];
                    $x++;
                }
            }

            $penerima_nama = implode(" ", $karyawan); // gabungkan nama karyawan
            $penerima_email = $karyawan_email;
            if(empty($penerima_email)){
                http_response_code(500);
                $status = array('txtStatus' => 'Karyawan tidak memiliki email', 'header' => 'Tidak dapat mengirim email');
                echo(json_encode($status));
                exit;
            }
            
            // $email_cc = $this->getEmailCCAtasan($id_posisi); // cc atasan
            $email_cc = "";
            $penerima_id_posisi = $data_posisi['id'];
            // to test and see the email
            // http_response_code(500);v
            // $status = array('txtStatus' => '<ul><li>Posisi Approver 2 tidak memiliki karyawan</li><li>'. json_encode($penerima_email) .'</li></ul>', 'header' => 'Tidak dapat mengirim email');
            // echo(json_encode($status));
            // exit;

        } else {
            show_404();
            exit;
        }        

        $data_penerima_email = array(
            'nama'      => $penerima_nama,
            'email'     => $penerima_email,
            'email_cc'  => $email_cc,
            'id_posisi' => $penerima_id_posisi,
            'msg'       => $penerima_msg
        );

        $job_profile = array( //data job profile karyawan
            'id_posisi'     => $id_posisi,
            'position_name' => $data_posisi['position_name'],
            'status'        => $data_posisi['status_approval']
        );

        $this->notifikasi($nik, $job_profile, $data_penerima_email, $subject_email);
    }

    /* ----- kirim notifkasi dengan mengklik tombol pada setting job_profile ---- */
    function sendNotificatiOnStatus() {
        $status = $this->input->post('status'); //ambil status

        //update waktu terakhir kali dikirim notifikasi
        if($status == 0){
                $this->Jobpro_model->update('jobprofile_setting-notifstatus', array('db' => 'id', 'server' => 'SJP0'), array('date' => time()));
        } elseif($status == 1){
                $this->Jobpro_model->update('jobprofile_setting-notifstatus', array('db' => 'id', 'server' => 'SJP1'), array('date' => time()));
        } elseif($status == 2){
                $this->Jobpro_model->update('jobprofile_setting-notifstatus', array('db' => 'id', 'server' => 'SJP2'), array('date' => time()));
        } elseif($status == 3){
                $this->Jobpro_model->update('jobprofile_setting-notifstatus', array('db' => 'id', 'server' => 'SJP3'), array('date' => time()));
        } elseif($status == 4){
                $this->Jobpro_model->update('jobprofile_setting-notifstatus', array('db' => 'id', 'server' => 'SJP4'), array('date' => time()));
        }

        // ambil id position yang ada karyawannya
        $temp_posisi_karyawan = $this->Jobpro_model->getJoin2tables(  $this->table_position .'.id', 
                                                                    $this->table_position, 
                                                                    array('table' => 'master_employee', 'index' => 'master_employee.position_id = '. $this->table_position .'.id', 'position' => 'left'), 
                                                                    "master_employee.position_id IS NOT NULL"
                                                                );

        //ambil id posisi yang punya status itu dan id posisinya memiliki karyawan
        $id_temp_posisi = array(); $x = 0 ;
        foreach($temp_posisi_karyawan as $v){
            $id_temp_posisi[$x] = $v['id'];
            $x++;
        }
        //hapus duplikasi id
        $id_posisi_karyawan = array_unique($id_temp_posisi); // ambil 1 id aja kalo ada beberapa karyawan
        
        $counter_email = 0;
        foreach($id_posisi_karyawan as $value){
            // cocokkan id posisi dengan status
            $v = $this->Jobpro_model->getJoin2tables('id, status_approval, position_name, id_approver1, id_approver2', $this->table_position, array('table' => 'jobprofile_approval', 'index' => 'jobprofile_approval.id_posisi = '. $this->table_position .'.id', 'position' => 'left'), array('status_approval' => $status, 'id' => $value));
            // print_r($v);
            // echo("<br/>");
            if(!empty($v[0])){ // cek apa data kosong
                $this->sendEmailOnStatus($v[0]['id'], $v[0]['position_name'], $v[0]['id_approver1'], $v[0]['id_approver2'], $status);
                $counter_email++;
            }            
        }

        if($counter_email == 0){ // cek jika tidak ada email yang terkirim
            http_response_code(500);
            $status = array('txtStatus' => 'Tidak ada posisi yang berada di status ini.', 'header' => 'Tidak dapat mengirim email');
            echo(json_encode($status));
            exit;
        }
    }
        
    /**
     * sendEmailOnStatus
     *
     * @param  mixed $id_posisi
     * @param  mixed $position_name
     * @param  mixed $id_approver1
     * @param  mixed $id_approver2
     * @param  mixed $status
     * @return void
     */
    public function sendEmailOnStatus($id_posisi, $position_name, $id_approver1, $id_approver2, $status){
        if($status == 1 || $status == 2){ // status submitted atau first approval
            if($status == 1){ // status submitted
                if($id_approver1 == 0){ // apakah tidak punya approver 1
                    return;
                    // http_response_code(500);
                    // $status = array('txtStatus' => 'Karyawan tidak memiliki Approver 1', 'header' => 'Tidak dapat mengirim email');
                    // echo(json_encode($status));
                    // exit;
                }
                $temp_penerima = $this->Jobpro_model->getDetail('emp_name, email', 'master_employee', array('position_id' => $id_approver1)); // ambil data penerima
                if(empty($temp_penerima)){ // apakah tidak memiliki karyawan posisi atasannya
                    return;
                    // http_response_code(500);
                    // $status = array('txtStatus' => 'Posisi Approver 1 tidak memiliki karyawan', 'header' => 'Tidak dapat mengirim email');
                    // echo(json_encode($status));
                    // exit;
                }
                if(empty($temp_penerima['email'])){ // apakah tidak memiliki email si karyawan posisi atasannya
                    return;
                    // http_response_code(500);
                    // $status = array('txtStatus' => 'Posisi Approver 1 tidak memiliki karyawan', 'header' => 'Tidak dapat mengirim email');
                    // echo(json_encode($status));
                    // exit;
                }
                $penerima_nama = $temp_penerima['emp_name'];
                $penerima_email = $temp_penerima['email'];
                $penerima_id_posisi = $id_approver1;
                $subject_email = '[Job Profile] First Approval';
                
            } elseif($status == 2){ // status submitted
                if($id_approver2 == 0){ // posisi atasan 2 tidak memiliki atasan
                    return;
                //     http_response_code(500);
                //     $status = array('txtStatus' => 'Karyawan tidak memiliki Approver 2', 'header' => 'Tidak dapat mengirim email');
                //     echo(json_encode($status));
                //     exit;
                }
                $temp_penerima = $this->Jobpro_model->getDetail('emp_name, email', 'master_employee', array('position_id' => $id_approver2));
                if(empty($temp_penerima)){ // apakah posisi atasannya tidak memiliki karyawan
                    return;
                //     http_response_code(500);
                //     $status = array('txtStatus' => 'Posisi Approver 2 tidak memiliki karyawan', 'header' => 'Tidak dapat mengirim email');
                //     echo(json_encode($status));
                //     exit;
                }
                if(empty($temp_penerima['email'])){ // apakah tidak memiliki email si karyawan posisi atasannya
                    return;
                    // http_response_code(500);
                    // $status = array('txtStatus' => 'Posisi Approver 1 tidak memiliki karyawan', 'header' => 'Tidak dapat mengirim email');
                    // echo(json_encode($status));
                    // exit;
                }
                $penerima_nama = $temp_penerima['emp_name'];
                $penerima_email = $temp_penerima['email'];
                $penerima_id_posisi = $id_approver2;
                $subject_email = '[Job Profile] Final Approval';

            } else {
                show_404();
                exit;
            }
            //ambil email buat cc
            $x = 0; $email_cc = array();
            foreach($this->Jobpro_model->getDetails('email', 'master_employee', array('position_id' => $id_posisi)) as $v){
                $email_cc[$x] = $v['email'];
                $x++;
            }
            // email more data
            $penerima_msg = 'There is a new employe waiting for your approval!';

        } elseif($status == 0 || $status == 3 || $status == 4){ // untuk status di 3 kondisi ini
            //cek status
            if($status == 0){
                $subject_email = '[Job Profile] Create Job Profile';
                $penerima_msg = 'Please fill your Job Profile and submit it!';
                
                // cek apa ada data approvernya
                $value = array(); $value = $this->Jobpro_model->getJoin2tables('id, status_approval, id_approver1, id_approver2, position_name', $this->table_position, array('table' => 'jobprofile_approval', 'index' => 'jobprofile_approval.id_posisi = '. $this->table_position .'.id', 'position' => 'left'), array('id' => $id_posisi))[0];
                if(!empty($value)){ //ambil data posisi
                    $data_posisi = $value;
                } else {
                    return "";
                }
                $email_cc = array(); $x = 0; //prepare variables
                if($data_posisi['id_approver1'] != 0 && $data_posisi['id_approver1'] != 1){ //jika id atasannya bukan 0 dan CEO
                    $value = array(); $value = $this->Jobpro_model->getDetail('email', 'master_employee', array('position_id' => $data_posisi['id_approver1']));
                    if(!empty($value)){
                        $email_cc[$x] = $value['email'];
                        $x++;
                    } else {
                        // nothing
                    }
                }
                if($data_posisi['id_approver1'] == 1){ // kosongkan email cc jika approver1 = 1
                    $email_cc = "";
                }
                if($data_posisi['id_approver1'] == 0 && $data_posisi['id_approver2'] == 0){ // jika id approver 1 dan 2 nya 0
                    $email_cc = "";
                }

            } elseif($status == 3){
                $subject_email = '[Job Profile] Need Revise';
                $penerima_msg = 'Please revise your Job Profile and submit it!';
                // $email_cc = $this->getEmailCCAtasan($id_posisi); // cc email atasan
                $email_cc = "";

            } elseif($status == 4){
                $subject_email = '[Job Profile] Approved';
                $penerima_msg = 'Thank You!';
                // $email_cc = $this->getEmailCCAtasan($id_posisi); // cc email atasan
                $email_cc = "";

            }else {
                 show_404();
                 exit;
            }

            //ambil email karyawan
            $data_karyawan = $this->Jobpro_model->getDetails('emp_name, email', 'master_employee', array('position_id' => $id_posisi));  
            if(empty($data_karyawan)){
                return;
                // http_response_code(500);
                // $status = array('txtStatus' => 'Posisi Approver 1 tidak memiliki karyawan', 'header' => 'Tidak dapat mengirim email');
                // echo(json_encode($status));
                // exit;
            }
            // /* --------------------------- ambil nama karyawan -------------------------- */
            $counter_karyawan = count($data_karyawan);
            $karyawan = array('<ul>'); //buka ul
            foreach($data_karyawan as $key => $value){ //ambil nama karyawan)
                $karyawan[$key + 1] = '<li> -  '. $value['emp_name'] .'</li>';
                if($key+1 == $counter_karyawan){ //tutup kode ul
                    $karyawan[$key + 2] = '</ul>';
                }
            }
            /* -------------------------- ambil email karyawan -------------------------- */
            $x=0;
            foreach($data_karyawan as $key => $value){
                if(!empty($value['email'])){
                    $karyawan_email[$x] = $value['email'];
                    $x++;
                }
            }

            // $temp_penerima =  $this->Jobpro_model->getDetail('emp_name, email', 'master_employee', array('nik' => $nik));
            if(empty($karyawan_email)){
                return;
                // http_response_code(500);
                // $status = array('txtStatus' => 'Posisi Approver 1 tidak memiliki karyawan', 'header' => 'Tidak dapat mengirim email');
                // echo(json_encode($status));
                // exit;
            }
            $penerima_nama = implode(" ", $karyawan); // gabungkan nama karyawan
            $penerima_email = $karyawan_email;
            $penerima_id_posisi = $id_posisi;            
            // $email_cc = $this->getEmailCCAtasan($id_posisi); // cc email atasan
            $email_cc = "";
            
        } else {
            show_404();
            exit;
        }

        $data_penerima_email = array(
            'nama'      => $penerima_nama,
            'email'     => $penerima_email,
            'email_cc'  => $email_cc,
            'id_posisi' => $penerima_id_posisi,
            'msg'       => $penerima_msg
        );

        $job_profile = array( //data job profile karyawan
            'id_posisi'     => $id_posisi,
            'position_name' => $position_name,
            'status'        => $status
        );

        $nik = random_text(); // nik replaced to random string
        $this->notifikasi($nik, $job_profile, $data_penerima_email, $subject_email);
    }
    
    /* -------------------------------------------------------------------------- */
    /*        function buat mengolah data chart olahDataChart(id_position)        */
    /* -------------------------------------------------------------------------- */
    public function olahDataChart($my_positionId) {
        // MENGOLAH DATA Master Position menjadi orgchart data ===========================================================
        //sebelumnya ingat ada beberapa hal yang harus diperhatikan
        // 1. posisi Asistant dan bukan assistant berbeda perlakuannya juga berbeda
        // 2. kode ini digunakan untuk mengolah data dari database menjadi JSON
        // 3. nomor 200 dan 201 itu adalah id_posisi, dijadikan permisalan

        $my_pos_detail = $this->Jobpro_model->getPositionDetail($my_positionId); //ambil informasi detail posisi saya //200 //bukan assistant
        if(empty($my_pos_detail)){
            $my_pos_detail = $this->Jobpro_model->getPositionDetailAssistant($my_positionId);
        }
        
        $x = 0; $y = 0; $atasan = 0; //untuk penanda looping
        if(!empty($my_pos_detail)){//if data exist
            $my_atasan[$x]['id_atasan1'] = $my_pos_detail['id_atasan1'];
            $id_atasan1 = $my_pos_detail['id_atasan1'];
            $id_atasan2 = $my_pos_detail['id_atasan2'];

            if($my_pos_detail['id_atasan2'] != 1 && $my_pos_detail['id_atasan2'] != 0 && $my_pos_detail['div_id'] != 1){//apakah atasan 2 nya bukan CEO atau dan dia punya atasan 2
                //cari posisi yang bukan assistant
                $whois_sama[0] = $this->Jobpro_model->getWhoisSama($id_atasan1); //200 dan 201 ambil data yang sama sama saya yang bukan assistant
                $whois_sama[1] = $this->Jobpro_model->getWhoisSama($id_atasan2); //200 dan 201 ambil data yang sama sama saya yang bukan assistant
                $my_atasan[0] = $this->Jobpro_model->getPositionDetail($id_atasan1); //ambil informasi daftar atasan saya yang bukan assistant
                $my_atasan[1] = $this->Jobpro_model->getPositionDetail($id_atasan2); //ambil informasi daftar atasan saya yang bukan assistant
                //cari posisi yang assistant
                if(!empty($whois_sama_assistant1[$y] = $this->Jobpro_model->getWhoisSamaAssistant($id_atasan1))){ //cari assistant atasan 1
                    $y++;
                    $assistant_atasan1 = 1; //tandai buat nanti nampilin orgchartnya horizontal di level ke 3
                } else {
                    $assistant_atasan1 = 0; //tandai buat nanti nampilin orgchartnya horizontal di level ke 3
                }
                if(!empty($whois_sama_assistant2[$y] = $this->Jobpro_model->getWhoisSamaAssistant($id_atasan2))){ //cari assistant atasan 2
                    $y++;
                } else {
                    //nothing
                }
                $atasan = 2; //penanda atasan

            } elseif ($my_pos_detail['id_atasan2'] != 0 && $my_pos_detail['div_id'] == 1){
                //cari posisi yang bukan assistant                
                $whois_sama[0] = $this->Jobpro_model->getWhoisSamaCEOffice($id_atasan1, '1'); //200 dan 201 ambil data yang sama sama saya yang bukan assistant
                $whois_sama[1] = $this->Jobpro_model->getWhoisSamaCEOffice($id_atasan2, '1'); //200 dan 201 ambil data yang sama sama saya yang bukan assistant
                $my_atasan[0] = $this->Jobpro_model->getPositionDetail($id_atasan1); //ambil informasi daftar atasan saya yang bukan assistant
                $my_atasan[1] = $this->Jobpro_model->getPositionDetail($id_atasan2); //ambil informasi daftar atasan saya yang bukan assistant
                //cari posisi yang assistant
                if(!empty($whois_sama_assistant1[$y] = $this->Jobpro_model->getWhoisSamaAssistant($id_atasan1))){ //cari assistant atasan 1
                    $y++;
                    $assistant_atasan1 = 1; //tandai buat nanti nampilin orgchartnya horizontal di level ke 3
                } else {
                    $assistant_atasan1 = 0; //tandai buat nanti nampilin orgchartnya horizontal di level ke 3
                }
                if(!empty($whois_sama_assistant2[$y] = $this->Jobpro_model->getWhoisSamaAssistant($id_atasan2))){ //cari assistant atasan 2
                    $y++;
                } else {
                    //nothing
                }
                $atasan = 2; //penanda atasan

            } elseif($my_pos_detail['id_atasan1'] == 1 && $my_pos_detail['hirarki_org'] == 'N'){
                // cari posisi yang bukan assistant
                $whois_sama[0] = $this->Jobpro_model->getDetails("*", $this->table_position, 'id_atasan1 = "1" AND div_id != "1"');
                $my_atasan[0] = $this->Jobpro_model->getDetail("*", $this->table_position, array('id' => $my_pos_detail['id_atasan1']));
                
                $assistant_atasan1 = 0; //tandai buat nanti nampilin orgchartnya horizontal di level ke 3
                $atasan = 1; // penanda jumlah atasan
                
            } else {
                if($my_pos_detail['id_atasan1'] != 1 && $my_pos_detail['id_atasan1'] != 0 && $my_pos_detail['div_id'] != 1){ //apakah atasan 1nya bukan CEO atau dia punya atasan

                    //cari posisi yang bukan assistant
                    $whois_sama[0] = $this->Jobpro_model->getWhoisSama($id_atasan1); //200 dan 201 ambil data yang sama sama saya yang bukan assistant
                    $my_atasan[0] = $this->Jobpro_model->getPositionDetail($id_atasan1); //ambil informasi daftar atasan saya yang bukan assistant
                    //cari posisi yang assistant
                    if(!empty($whois_sama_assistant1[$y] = $this->Jobpro_model->getWhoisSamaAssistant($id_atasan1))){ //cari assistant atasan 1
                        $y++;
                    } else {
                        //nothing
                    }
                    $assistant_atasan1 = 0; //tandai buat nanti nampilin orgchartnya horizontal di level ke 3
                    $atasan = 1;//penanda atasan
                } elseif($my_pos_detail['id_atasan1'] == 1 && $my_pos_detail['div_id'] == 1){

                    //cari posisi yang bukan assistant
                    $whois_sama[0] = $this->Jobpro_model->getWhoisSamaCEOffice($id_atasan1, '1'); //200 dan 201 ambil data yang sama sama saya yang bukan assistant
                    $my_atasan[0] = $this->Jobpro_model->getPositionDetail($id_atasan1); //ambil informasi daftar atasan saya yang bukan assistant
                    //cari posisi yang assistant
                    if(!empty($whois_sama_assistant1[$y] = $this->Jobpro_model->getWhoisSamaAssistant($id_atasan1))){ //cari assistant atasan 1
                        $y++;
                    } else {
                        //nothing
                    }

                    $assistant_atasan1 = 0; //tandai buat nanti nampilin orgchartnya horizontal di level ke 3
                    $atasan = 1;//penanda atasan
                } else {
                    //nothing
                    $assistant_atasan1 = 0; //tandai buat nanti nampilin orgchartnya horizontal di level ke 3
                    $atasan = 0;//penanada atasan
                }
            }

            //cari id yang sama dengan $my_pos_detail di $whois_sama, lalu tambahin 'className': 'my-position' //jika my position bukan assistant
            foreach($whois_sama as $k => $v){
                foreach ($v as $key => $value){
                    if($my_pos_detail['id'] == $value['id']){
                        $whois_sama[$k][$key]['className'] = 'my-position';
                    }
                }
            }

            //reverse arraynya dulu
            $whois_sama = array_reverse($whois_sama);
            $my_atasan = array_reverse($my_atasan);

            //gabungkan array $whois_sama dengan $my_atasan
            $org_struktur = $my_atasan;
            foreach($my_atasan as $k => $v){
                $org_struktur[$k]['children'] = $whois_sama[$k];
            }

            if($atasan == 2){ //gabungkan array[0] dengan [1]; kalo dia punya atasan 1 dan 2
                $i = 0;
                foreach($org_struktur[1]['children'] as $key => $value){
                    foreach($org_struktur[0]['children'] as $k => $v){
                        if($org_struktur[1]['id'] == $org_struktur[0]['children'][$k]['id']){
                            $org_struktur[0]['children'][$k]['children'][$i] = $value;
                            $i++;
                        }
                    }
                }
            } else {
                //nothing
            }

            //ASSISTANT DATA
            //keluarkan semua assistant jadi di level teratas
            $org_assistant1 = array(); $x = 0; //initialize assistant atasan 1
            if(!empty($whois_sama_assistant1)){
                foreach($whois_sama_assistant1 as $k => $v){
                    foreach($v as $key => $value){
                        $org_assistant1[$x] = $value; //tambah value ke org_struktur
                        foreach($this->Jobpro_model->getAtasanAssistant($value['id_atasan1']) as $kunci => $nilai){ //cari atasannya 
    
                            // array_push($org_assistant[$x], $nilai); //tambah nama posisi atasannya
                            $org_assistant1[$x]['atasan_assistant'] = $nilai; //tambah nama posisi atasannya
                        }
                        $x++;
                    }
                }
            }
            if(!empty($whois_sama_assistant2)){
                $org_assistant2 = array(); $x = 0; //initialize assistant atasan 2
                foreach($whois_sama_assistant2 as $k => $v){
                    foreach($v as $key => $value){
                        $org_assistant2[$x] = $value; //tambah value ke org_struktur
                        foreach($this->Jobpro_model->getAtasanAssistant($value['id_atasan1']) as $kunci => $nilai){ //cari atasannya 
                            // array_push($org_assistant[$x], $nilai); //tambah nama posisi atasannya
                            $org_assistant2[$x]['atasan_assistant'] = $nilai; //tambah nama posisi atasannya
                        }
                        $x++;
                    }
                }
            }else{
                $org_assistant2 = array();
            }

            //jika assistant adalah my-position tambahkan className my-position
            foreach($org_assistant1 as $k => $v){ //cek di assistan atasan 1
                if($my_pos_detail['id'] == $v['id']){
                    $org_assistant1[$k]['className'] = 'my-position';
                }
            }
            foreach($org_assistant2 as $k => $v){ //cek di assistan atasan 2
                if($my_pos_detail['id'] == $v['id']){
                    $org_assistant2[$k]['className'] = 'my-position';
                }
            } 
            
            //simpan data assistant dalam bentuk JSON
            // $data['orgchart_data'] = json_encode($org_struktur[0]); //masukkan data orgchart yang sudah diolah ke JSON
            // $data['orgchart_data_assistant'] = json_encode($org_assistant);
            return array(json_encode($org_struktur[0]), json_encode($org_assistant1), json_encode($org_assistant2), $assistant_atasan1, $atasan);

        } else { //if orgchart data doesn't exist
            // $data['orgchart_data_assistant'] = json_encode("");
            // $data['orgchart_data'] = json_encode("");
            // print_r('gaada bro');
            exit;
        }
        // End of Pengolahan data orgchart ==============================================================================
    }
    
    //TODO Make PrintJP
    public function printJp(){
        //load the main library TCPDF (.application/library/tcpdf)
        //I have created a Library loader in that folder, just load using this code below
        $this->load->library('Pdf');

        // buat PDF baru
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);

        //atur informasi dokumen
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Job Profile');
        $pdf->SetAuthor('Centratama Group');
        $pdf->SetSubject('Document');
        $pdf->SetKeywords('Printed Document, Digital Document');

        //atur default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE. '001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        //atur font header dan footer
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        //atur default font monospaced
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        //atur margin
        // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetMargins(15, 30, 15, true);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // $pdf->SetHeaderMargin(30);
        // $pdf->SetTopMargin(20);
        // $pdf->setFooterMargin(20);

        //atur auto page break
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        //atur image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // --------------------------------------------------------------

        //atur default font subsetting mode
        $pdf->setFontSubsetting(true);

        //atur Font
        //dejavusans is a UTF-8 Unicode font if you only need to
        //print standard ASCII chars, you can use core fonts like
        //helvetica or times to reduce file size
        $pdf->setFont('dejavusans', '', 14, '', true);

        //Add a page
        //This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        //set text shadow effect
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196,196,196), 'opacity' => 1, 'blend_mode' => 'Normal'));

        //atur content untuk diprint
        $date = date('d F Y', time());
        $html = '
            <style>
                table{
                    border-collapse: collapse;
                    width: 100%;
                }
                table, th, td{
                    border: 1px solid black;
                }
            </style>

            <table>
                <thead>
                    <tr>
                        <th style="text:">PROFIL JABATAN</th>
                        <th>Tanggal: ' . $date . '</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td></td>
                    </tr>
                    
                </tbody>
            </table>
        ';

        //print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        // $pdf->Write(5, 'Tes 123 tes tes'); //write some text

        // --------------------------------------------------------------

        // tutup dan tampilkan dokumen PDF
        // This method has several options, check the source code documentation for more information.
        
        $pdf->SetDisplayMode('real', 'default');
        // $pdf->Output('Centratama-JP.pdf', 'I');

        $this->load->view('templates/print_preview.php');
        // print('hello');
        
    }

    public function blocked(){
        $this->load->view('auth/blocked');
    }
}

/* End of file Job_profile.php */

/* Status Approval Infomation
0 = Belum diisi
1 = Direview Atasan 1
2 = Direview Atasan 2
3 = Revisi
4 = Selesai
*/


//Lab Area, you know I want to try if we can get all those hiearchy from my position to the last top hierachy, I'm still stuck here. here is my progress
//SHIFT+CTRL+END then CTRL+/ to remove comments

// $x = 0;
// $i = 0;
// //ambil semua data sampai pada id_atasan = 1
// while($i<1) {
//     $whois_sama[$x] = $this->Jobpro_model->getWhoisSama($id_atasan1); //200 dan 201
//     $my_atasan[$x] = $this->Jobpro_model->getPositionDetail($id_atasan1); //ambil informasi detail posisi saya
//     $id_atasan1 = $my_atasan[$x]['id_atasan1'];
//     if($id_atasan1 == 1){
//         $i++;
//     }else{
//         $x++;
//     }
// }

// $my_atasan_tertinggi = $this->Jobpro_model->getPositionDetail($id_atasan1); //ambil informasi detail posisi saya //200


// // print_r($whois_sama);
// // print('<br>');
// // print('<br>');
// // print_r($my_atasan);
// // print('<br>');
// // print('<br>');
// // print_r($my_atasan_tertinggi);
// // print('<br>');
// // print('<br>');
// // output $my_atasan
// // Array ( [0] => Array ( [id] => 199 [position_name] => Organization Development Dept. Head [dept_id] => 29 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 ) 
// //         [1] => Array ( [id] => 196 [position_name] => Human Capital Division Head [dept_id] => 26 [div_id] => 6 [id_atasan1] => 1 [id_atasan2] => 0 )
// //       ) 

// //output $whois_sama
// // Array ( [0] => Array ( [0] => Array ( [id] => 200 [position_name] => Recruitment Officer [dept_id] => 29 [div_id] => 6 [id_atasan1] => 199 [id_atasan2] => 196 ) 
// //                        [1] => Array ( [id] => 201 [position_name] => Talent & Performance Management Specialist [dept_id] => 29 [div_id] => 6 [id_atasan1] => 199 [id_atasan2] => 196 ) 
// //                        ) 
// //         [1] => Array ( [0] => Array ( [id] => 182 [position_name] => Compensation & Benefit Dept. Head [dept_id] => 27 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 ) 
// //                        [1] => Array ( [id] => 190 [position_name] => General Affairs & GovRel Dept. Head [dept_id] => 28 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 ) 
// //                        [2] => Array ( [id] => 194 [position_name] => Employee Relation & Safety Officer [dept_id] => 26 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 ) 
// //                        [3] => Array ( [id] => 195 [position_name] => HCIS Officer [dept_id] => 26 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 ) 
// //                        [4] => Array ( [id] => 199 [position_name] => Organization Development Dept. Head [dept_id] => 29 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 ) 
// //                        ) 
// //     ) 
// //output my_atasan_tertinggi
// // Array ( [id] => 1 [position_name] => Chief Executive Officer [dept_id] => 1 [div_id] => 1 [id_atasan1] => 0 [id_atasan2] => 0 ) 

// //reverse arraynya dulu
// $whois_sama = array_reverse($whois_sama);
// $my_atasan = array_reverse($my_atasan);

// // print_r($whois_sama);
// // print('<br>');
// // print('<br>');
// // print_r($my_atasan);
// // print('<br>');
// // print('<br>');
// // print_r($my_atasan_tertinggi);

// //output $whois_sama
// // Array ( [0] => Array ( [0] => Array ( [id] => 182 [position_name] => Compensation & Benefit Dept. Head [dept_id] => 27 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 ) 
// //                        [1] => Array ( [id] => 190 [position_name] => General Affairs & GovRel Dept. Head [dept_id] => 28 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 ) 
// //                        [2] => Array ( [id] => 194 [position_name] => Employee Relation & Safety Officer [dept_id] => 26 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 ) 
// //                        [3] => Array ( [id] => 195 [position_name] => HCIS Officer [dept_id] => 26 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 )
// //                        [4] => Array ( [id] => 199 [position_name] => Organization Development Dept. Head [dept_id] => 29 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 ) 
// //                     ) 
// //         [1] => Array ( [0] => Array ( [id] => 200 [position_name] => Recruitment Officer [dept_id] => 29 [div_id] => 6 [id_atasan1] => 199 [id_atasan2] => 196 ) 
// //                        [1] => Array ( [id] => 201 [position_name] => Talent & Performance Management Specialist [dept_id] => 29 [div_id] => 6 [id_atasan1] => 199 [id_atasan2] => 196 ) 
// //                     ) 
// //     )

// //output $my_atasan
// // Array ( [0] => Array ( [id] => 196 [position_name] => Human Capital Division Head [dept_id] => 26 [div_id] => 6 [id_atasan1] => 1 [id_atasan2] => 0 ) 
// //         [1] => Array ( [id] => 199 [position_name] => Organization Development Dept. Head [dept_id] => 29 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 ) 
// //     ) 

// //output my_atasan_tertinggi
// // Array ( [id] => 1 [position_name] => Chief Executive Officer [dept_id] => 1 [div_id] => 1 [id_atasan1] => 0 [id_atasan2] => 0 ) 

// //masukkin anak2 dari atasan
// $org_struktur = $my_atasan;
// foreach($my_atasan as $k => $v){
//     $org_struktur[$k]['children'] = $whois_sama[$k];
// }

// print_r($org_struktur);
// //output $org_struktur
// // Array ( [0] => Array ( [id] => 196 [position_name] => Human Capital Division Head [dept_id] => 26 [div_id] => 6 [id_atasan1] => 1 [id_atasan2] => 0 
// //                        [children] => Array ( [0] => Array ( [id] => 182 [position_name] => Compensation & Benefit Dept. Head [dept_id] => 27 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 )
// //                                              [1] => Array ( [id] => 190 [position_name] => General Affairs & GovRel Dept. Head [dept_id] => 28 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 )
// //                                              [2] => Array ( [id] => 194 [position_name] => Employee Relation & Safety Officer [dept_id] => 26 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 ) 
// //                                              [3] => Array ( [id] => 195 [position_name] => HCIS Officer [dept_id] => 26 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 ) 
// //                                              [4] => Array ( [id] => 199 [position_name] => Organization Development Dept. Head [dept_id] => 29 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 ) 
// //                                              ) 
// //                     ) 
// //         [1] => Array ( [id] => 199 [position_name] => Organization Development Dept. Head [dept_id] => 29 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 
// //                        [children] => Array ( [0] => Array ( [id] => 200 [position_name] => Recruitment Officer [dept_id] => 29 [div_id] => 6 [id_atasan1] => 199 [id_atasan2] => 196 ) 
// //                                              [1] => Array ( [id] => 201 [position_name] => Talent & Performance Management Specialist [dept_id] => 29 [div_id] => 6 [id_atasan1] => 199 [id_atasan2] => 196 ) 
// //                                              ) 
// //                     ) 
// //     ) 
// exit;

// $org_struktur = $my_atasan_tertinggi;

// //masukin atasan ke dalam jabatan tertinggi array
// foreach($whois_sama as $k => $v){
//     if($org_struktur['id'] == $my_atasan[$k]['id_atasan1']){
//         $org_children = $my_atasan[$k];
//     }
// } 

// //buat pointer
// $org_pointer = array();
// foreach($my_atasan as $k => $v){
//     $org_pointer[$k] = "children";
// }

// $in  = $org_pointer; // Array with incoming params
// $res = array();        // Array where we will write result
// $t   = &$res;          // Link to first level
// foreach ($in as $k) {  // Walk through source array
// if (empty($t[$k])) { // Check if current level has required key
//     $t[$k] = $my_atasan;  // If does not, create empty array there
//     $t = &$t[$k];      // And link to it now. So each time it is link to deepest level.
// }
// }
// unset($t); // Drop link to last (most deep) level
// print_r($res);
// die();



// // $org_struktur[$org_pointer] = $org_children;

// //buat pointer
// $org_pointer;
// // foreach($my_atasan as $v){

// // }
// $a = array("1","5","6");
// $b = array();
// $c =& $b;

// foreach ($a as $k) {
//     $c[$k] = array();
//     $c     =& $c[$k];
// }



// var_dump($org_struktur);
// // Array ( [id] => 1 [position_name] => Chief Executive Officer [dept_id] => 1 [div_id] => 1 [id_atasan1] => 0 [id_atasan2] => 0 
// //         [children] => Array ( [id] => 196 [position_name] => Human Capital Division Head [dept_id] => 26 [div_id] => 6 [id_atasan1] => 1 [id_atasan2] => 0 ) 
// //         )



// //penunjuk lokasi tingkatan org_struktur
// $org_pointer = "children";
// // print_r($org_struktur[$org_pointer]);

// // while($i<1){
// //     if($my_atasan)
// // }

// exit;
// // Array ( [id] => 1 
// // [position_name] => Chief Executive Officer 
// // [dept_id] => 1 
// // [div_id] => 1 
// // [id_atasan1] => 0 
// // [id_atasan2] => 0 
// // [0] => Array ( [id] => 196 [position_name] => Human Capital Division Head [dept_id] => 26 [div_id] => 6 [id_atasan1] => 1 [id_atasan2] => 0 ) 
// // [children] => 9 [1] => Array ( [id] => 199 [position_name] => Organization Development Dept. Head [dept_id] => 29 [div_id] => 6 [id_atasan1] => 196 [id_atasan2] => 1 ) ) 

// print_r($org_struktur);
// exit;

// while($i<1) {
//     $org_struktur['children'] = array_push($org_struktur, $my_atasan[$k]);


//     $whois_sama[$x] = $this->Jobpro_model->getWhoisSama($id_atasan1); //200 dan 201
//     $my_atasan[$x] = $this->Jobpro_model->getPositionDetail($id_atasan1); //ambil informasi detail posisi saya
//     $id_atasan1 = $my_atasan[$x]['id_atasan1'];
//     if($id_atasan1 == 1){
//         $i++;
//     }else{
//         $x++;
//     }
// }

// // var datasource = {
// // 	'id': '1',
// // 	'name': 'Lao Lao',
// // 	'title': 'general manager',
// // 	'children': [
// // 	  	{ 'id': '2', 'name': 'Bo Miao', 'title': 'department manager' },
// // 	  	{ 'id': '3', 'name': 'Su Miao', 'title': 'department manager',
// // 			'children': [
// // 				{ 'id': '4', 'name': 'Tie Hua', 'title': 'senior engineer' },
// // 				{ 'id': '5', 'name': 'Hei Hei', 'title': 'senior engineer',
// // 					'children': [
// // 						{ 'id': '6', 'name': 'Pang Pang', 'title': 'engineer' },
// // 						{ 'id': '7', 'name': 'Xiang Xiang', 'title': 'UE engineer' }
// // 					]
// // 				}
// // 			]
// // 	   	},
// // 	   	{ 'id': '8', 'name': 'Hong Miao', 'title': 'department manager' },
// // 	   	{ 'id': '9', 'name': 'Chun Miao', 'title': 'department manager' }
// // 	]
// // };

// // print_r($my_atasan);
// exit;
// // cari yang sama-sama memiliki atasan1 yang sama
// $whois_sama1 = $this->Jobpro_model->getWhoisSama($my_pos_detail['id_atasan1']); //200 dan 201
// // retrieve id yang sama-sama punya atasan2
// $my_atasan1 = $this->Jobpro_model->getPositionDetail($my_pos_detail['id_atasan1']); //ambil informasi detail posisi saya //199
// // ambil atasan2
// $whois_sama2 = $this->Jobpro_model->getWhoisSama($my_atasan1['id_atasan1']);
// // cari yang sama
// $my_atasan2 = $this->Jobpro_model->getPositionDetail($my_atasan1['id_atasan1']); //ambil informasi detail posisi saya

// //if $my_atasan2 == 1 thrn stop
// print_r($my_atasan2);
// exit;


// //ambil data diri
// //ambil data teman
// //ambil data diri
// //ambil data teman
// //ambil data diri
// //ambil data sampai id_atasan=1

// // $myJSON = json_encode($print); convert any array to JSON

// // print('<br>');
// // print_r($myJSON);
// // $myObj->name = "John";
// // $myObj->age = 30;
// // $myObj->city = "New York";

// // print('<br>');
// // print_r($myObj);
// // print_r($print);
// exit();

// // var datasource = {
// // 	'id': '1',
// // 	'name': 'Lao Lao',
// // 	'title': 'general manager',
// // 	'children': [
// // 	  	{ 'id': '2', 'name': 'Bo Miao', 'title': 'department manager' },
// // 	  	{ 'id': '3', 'name': 'Su Miao', 'title': 'department manager',
// // 			'children': [
// // 				{ 'id': '4', 'name': 'Tie Hua', 'title': 'senior engineer' },
// // 				{ 'id': '5', 'name': 'Hei Hei', 'title': 'senior engineer',
// // 					'children': [
// // 						{ 'id': '6', 'name': 'Pang Pang', 'title': 'engineer' },
// // 						{ 'id': '7', 'name': 'Xiang Xiang', 'title': 'UE engineer' }
// // 					]
// // 				}
// // 			]
// // 	   	},
// // 	   	{ 'id': '8', 'name': 'Hong Miao', 'title': 'department manager' },
// // 	   	{ 'id': '9', 'name': 'Chun Miao', 'title': 'department manager' }
// // 	]
// // };