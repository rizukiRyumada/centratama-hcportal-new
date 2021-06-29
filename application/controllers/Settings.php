<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends SuperAdminController {

    protected $page_title = [
        'masterData' => 'Master Data Management',
        'masterData_employee' => 'Master Employee',
        'masterData_position' => 'Master Position',
        'masterData_position_update' => 'Update Master Position',
    ];

    protected $table = [
        'emp_stats' => "employee_status",
        'level' => "master_level"
    ];
    
    public function __construct()
    {
        parent::__construct();
        // Load Models
        $this->load->model(['dept_model', 'divisi_model', 'employee_m', 'entity_m', 'master_m', 'posisi_m']);
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
        redirect('maintenance');
        die;
        
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
        $data['custom_js'] = [
            '_core/settings/settings',
        ];
        
		$this->load->view('main_v', $data);
    }

/* -------------------------------------------------------------------------- */
/*                            master data employee                            */
/* -------------------------------------------------------------------------- */

    function masterData_employee(){
        // employee data
        $data['employe'] = $this->employee_m->getAllEmp();
        // $data['nik'] = $this->Employe_model->getLastNik();
        $data['dept'] = $this->dept_model->getAll();
        $data['divisi'] = $this->divisi_model->getAll();
        $data['entity'] = $this->entity_m->getAll();
        $data['role'] = $this->master_m->getAll_userRole();
        $data['emp_stats'] = $this->_general_m->getAll("*", $this->table['emp_stats'], []);
        $data['master_level'] = $this->_general_m->getAll('*', $this->table['level'], []);

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['masterData_employee'];
		$data['load_view'] = 'settings/masterData_employee_settings_v';
		// additional styles and custom script
        $data['additional_styles'] = array(
            'plugins/datatables/styles_datatables',
            'plugins/datepicker/styles_datepicker'
        );
		// $data['custom_styles'] = array();
        $data['custom_script'] = array(
            'plugins/datatables/script_datatables',
            'plugins/datepicker/script_datepicker', 
            'plugins/jqueryValidation/script_jqueryValidation',
            'settings/script_masterData_employee_settings'
        );
        
		$this->load->view('main_v', $data);
    }
    
    /**
     * add new data employee
     *
     * @return void
     */
    public function employee_addNew(){//fungsi untuk menambah employe
        // cek role surat dan is_active
        //ubah password ke bcrypt
        //simpan ke database
        $data = array(
            'nik'            => $this->input->post('nik'),
            'emp_name'       => $this->input->post('name'),
            'position_id'    => $this->input->post('position'),
            'id_entity'      => $this->input->post('entity'),
            'role_id'        => $this->input->post('role'),
            'emp_stats'      => $this->input->post('emp_stats'),
            'level_personal' => $this->input->post('master_level'),
            'date_birth'     => $this->input->post('date_birth'),
            'date_join'      => $this->input->post('date_join'),
            'email'          => $this->input->post('email'),
            'password'       => password_hash($this->input->post('password'), PASSWORD_BCRYPT)  // hashing password
        );

        // cek role surat 
        if($this->input->post('role_surat') == 'on'){
            $data['akses_surat_id'] = 1;
        } else {
            $data['akses_surat_id'] = 0;
        }

        $this->employee_m->insert($data);

        // siapkan notifikasi swal
        $this->session->set_userdata('msg_swal', array(
            'icon' => 'success',
            'title' => 'Added Successfully',
            'msg' => 'The new Employee Data has been added to main database.'
        ));
        header('location: ' . base_url('settings/masterData_employee'));
    }

    /**
     * edit data employee
     * $onik ~ original nik
     *
     * @return void
     */
    public function employee_editEmployee(){ //fungsi untuk mengedit employe
        // jika nik tidak diubah
        $nik = $this->input->post('nik');
        $onik = $this->input->post('onik');
        $data = array(
            'emp_name' => $this->input->post('name'),
            'id_entity' => $this->input->post('entity'),
            'role_id' => $this->input->post('role'),
            'emp_stats'      => $this->input->post('emp_stats'),
            'level_personal' => $this->input->post('master_level'),
            'date_birth'     => $this->input->post('date_birth'),
            'date_join'      => $this->input->post('date_join'),
            'email' => $this->input->post('email')
        );
        //get origin data
        // $dataEmploye = $this->Master_m->getDetail('*', 'employe', array('nik' => $onik));
        
        //cek kalau password tidak kosong
        if(!empty($password = $this->input->post('password'))){ // hasing password dan simpan ke $dataEmploye
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        } else {
            // nothing
        }

        // cek role surat
        if($this->input->post('role_surat') == 'on'){
            $data['akses_surat_id'] = 1;
        } else {
            $data['akses_surat_id'] = 0;
        }

        //cek jika posisi kosong atau tidak
        if(!empty($position_id = $this->input->post('position'))){
            $data['position_id'] = $position_id;
        } else {
            //nothing
        }

        //cek jika nik diubah atau tidak
        if($nik != $onik){
            $data['nik'] = $nik;
        } else {
            //nothing
        }

        $where = array('nik' => $onik); // buat where
        $this->employee_m->update($where, $data);

        // siapkan notifikasi swal
        $this->session->set_userdata('msg_swal', array(
            'icon' => 'success',
            'title' => 'Edited Successfully',
            'msg' => 'Your changes has been saved to database.'
        ));
        header('location: ' . base_url('settings/masterData_employee'));
    }

/* -------------------------------------------------------------------------- */
/*                             masterdata position                            */
/* -------------------------------------------------------------------------- */

    function masterData_position() {
        $data['dept'] = $this->dept_model->getAll();
        $data['divisi'] = $this->divisi_model->getAll();
        $data['master_level'] = $this->_general_m->getAll('*', $this->table['level'], []);

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['masterData_position'];
		$data['load_view'] = 'settings/masterData_position_settings_v';
		// additional styles and custom script
        $data['additional_styles'] = array(
            'plugins/datatables/styles_datatables',
            'plugins/jquery-uploadfile/styles',
        );
		// $data['custom_styles'] = array();
        $data['custom_script'] = array(
            'plugins/datatables/script_datatables',
            'plugins/jqueryValidation/script_jqueryValidation',
            'plugins/jquery-uploadfile/script',
            'settings/script_masterData_position_settings'
        );
        $data['custom_js'] = [
            '_core/settings/masterData/position_update', // position js file
        ];

		$this->load->view('main_v', $data);
    }

    function ajax_getDataPosition(){
        $where = "";
        // filtering data
        if(!empty($this->input->post('divisi'))){
            $divisi = explode("-", $this->input->post('divisi'))[1];
        } else {
            $divisi = "";
        }
        if(!empty($this->input->post('department'))){
            $department = explode("-", $this->input->post('department'))[1];
        } else {
            $department = "";
        }
        // taruh divisi
        if(!empty($divisi)){
            $where .= "div_id='".$divisi."'";
        }

        if(!empty($department)){
            if(!empty($where)){
                $where .= " AND ";
            } else {
                // nothing
            }

            $where .= "dept_id='".$department."'";
        }

        // position data
        if(empty($where)){
            $data_posisi = $this->posisi_m->getAll();
        } else {
            $data_posisi = $this->posisi_m->getAllWhere($where);
        }
        
        // lengkapi data posisi
        foreach($data_posisi as $k => $v){
            $data_posisi[$k]['divisi'] = $this->divisi_model->getOnceWhere(array('id' => $v['div_id']))['division']; // ambil data divisi
            $data_posisi[$k]['department'] = $this->dept_model->getDetailById($v['dept_id'])['nama_departemen']; // ambil data department
            // $data_posisi[$k]['nama_atasan1'] = 
            // ambil data atasan 2
            // ambil data approver 1
            // ambil data approver 2
        }

        echo(json_encode(array(
            'data' => $data_posisi
        )));
    }

/* -------------------------------------------------------------------------- */
/*                                AJAX FUNCTION                               */
/* -------------------------------------------------------------------------- */
        
    /**
     * download all position data on current table
     *
     * @return void
     */
    function ajax_downloadPositionData() {
        $table_posisi = $this->posisi_m->getTableName();
        $this->_general_m->downloadTableDataAsCsv($table_posisi);
    }

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

    function ajax_getDetailPosition(){
        $id_posisi = $this->input->post('id_posisi');
        
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
    
    /**
     * ajax_removeEmployee
     *
     * @return void
     */
    protected $table_employee = [
        'employee' => 'master_employee'
    ];
    function ajax_removeEmployee(){
        $nik = $this->input->post('nik'); // get nik data
        // load model archives
        $this->load->model('_archives_m');

        $data_employee = $this->employee_m->getDetail_employeeAllData($nik); // ambil data karyawan full
        // lengkapi data employee
        $data_pos = $this->posisi_m->getOnceWhere(array('id' => $data_employee['position_id']));
        $data_div = $this->divisi_model->getOnceWhere(array('id' => $data_pos['div_id']));
        $data_dept = $this->dept_model->getDetailById($data_pos['dept_id']);
        // masukkan ke dalam data employee
        $data_employee['div_id'] = $data_div['id'];
        $data_employee['div_name'] = $data_div['division'];
        $data_employee['dept_id'] = $data_dept['id'];
        $data_employee['dept_name'] = $data_dept['nama_departemen'];
        $data_employee['position_name'] = $data_pos['position_name'];
        $data_employee['hirarki_org'] = $data_pos['hirarki_org'];
        $data_employee['job_grade'] = $data_pos['job_grade'];
        $data_employee['date'] = time(); // get now_date

        $this->_archives_m->insert($this->table_employee['employee'], $data_employee); // masukkan data employee ke dalam database archives
        $this->employee_m->remove($nik); // hapus data employee dengan nik tersebut
        echo(1); // tandai proses berhasil atau gagal
    }

    function ajax_updatePositionData() {
        $path        = $this->input->post('path');
        $file_update = $this->input->post('file_update');
        $tablename_posisi = 'master_position';
        $tablename_table = 'setting_tablename';

        try {
            $data = array_map('str_getcsv', file($path . '/' . $file_update['file_name']));
            // tarik row pertama, row pertama adalah header, jadiin buat bikin tabel dengan fields
            $data_fields = $data[0];
            array_splice($data, 0, 1);
            // ubah data ke bentuk queriable
            foreach($data as $k => $v) {
                $prepareData = [];
                foreach ($v as $key => $value){
                    $prepareData[$data_fields[$key]] = $value;
                }
                $data[$k] = $prepareData;
            }
            // ambil data nama tabel di database
            $tablename = $this->_general_m->getOnce('*', $tablename_table, ['tablename' => $tablename_posisi]);
            // untuk id cek tahun, kalo tahunnya sama, tambah increment
            $tablename_postfix = explode('_', $tablename['currentPostfix']);
            if($tablename_postfix[0] == date('Y')){
                $tablename_postfix[1] = str_pad(intval($tablename_postfix[1]) + 1, 2, '0', STR_PAD_LEFT);
                $tablename_postfixNew = implode('_', $tablename_postfix);
            }
            // siapkan data history
            $historyPostfix = json_decode($tablename['historyPostfix'], true);
            $historyPostfix_new = [];
            if($historyPostfix != []){
                $historyPostfix_new = [
                    ...$historyPostfix,
                    [
                        'postfix' => $tablename['currentPostfix'],
                        'date_changed' => date('Ymd-His'),
                    ],
                ];
            } else {
                $historyPostfix_new = [
                    [
                        'postfix' => $tablename['currentPostfix'],
                        'date_changed' => date('Ymd-His'),
                    ],
                ];
            }
            // ambil fields buat bikin table posisi
            $this->load->library('tablefields');
            $fields = $this->tablefields->get()[$tablename_posisi];
            // buat tabel baru, dan masukkan data ke database
            $this->load->dbforge();
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE); // primary key
            $this->dbforge->create_table($tablename_posisi.'_'.$tablename_postfixNew);
            // masukkan data ke dalam table baru
            $this->_general_m->insertAll($tablename_posisi.'_'.$tablename_postfixNew, $data);
            // foreach($data as $v) {
            //     $this->_general_m->insert($tablename_posisi . '_' . $tablename_postfixNew, $v);
            // }
            // update tablename
            $this->_general_m->update($tablename_table, 'tablename', $tablename_posisi, [
                'currentPostfix' => $tablename_postfixNew,
                'historyPostfix' => json_encode($historyPostfix_new),
            ]);
            // hapus session tablename
            $this->session->unset_userdata('tablename');
            // kirim pesan komplit
            echo json_encode([
                'code' => 200,
                'message' => 'Position data was successfully updated.',
            ]);
        } catch(Exception $e) {
            echo json_encode([
                'code' => 500,
                'message' => $e->getMessage(),
            ]);
        }
    }
}

/* End of file Settings.php */
