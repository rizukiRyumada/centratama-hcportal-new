<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends SuperAdminController {

    protected $page_title = [
        'masterData' => 'Master Data Management',
        'masterData_employee' => 'Master Employee',
        'masterData_position' => 'Master Position'
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
            'nik' => $this->input->post('nik'),
            'emp_name' => $this->input->post('name'),
            'position_id' => $this->input->post('position'),
            'id_entity' => $this->input->post('entity'),
            'role_id' => $this->input->post('role'),
            'email' => $this->input->post('email'),
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT) // hashing password
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
        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['masterData_position'];
		$data['load_view'] = 'settings/masterData_position_settings_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		// $data['custom_styles'] = array();
        $data['custom_script'] = array(
            'plugins/datatables/script_datatables',
            'plugins/jqueryValidation/script_jqueryValidation',
            'settings/script_masterData_position_settings'
        );

		$this->load->view('main_v', $data);
    }

    function getData_position(){
        // position data
        $data_posisi = $this->posisi_m->getAll();
        // lengkapi data posisi
        // foreach($data_posisi as $k => $v){
        //     $data_posisi[$k]['divisi'] = $this->divisi_model->getOnceWhere(['id' => $v['div_id']])['division']; // ambil data divisi
        //     $data_posisi[$k]['department'] = $this->dept_model->getDetailById(['id' => $v['dept_id']])['nama_departemen']; // ambil data department
        //     $data_posisi[$k]['nama_atasan1'] = $this-> // ambil data atasan 1
        //     // ambil data atasan 2
        //     // ambil data approver 1
        //     // ambil data approver 2
        // }
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
}

/* End of file Settings.php */
