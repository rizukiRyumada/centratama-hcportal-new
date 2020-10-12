<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pmk extends SpecialUserAppController {
    protected $id_menu = 12; // id menu

    // page title variable
    protected $page_title = [
        'index' => 'Penilaian Masa Kontrak',
        'assessment' => 'Assessment Form'
    ];

    protected $table = [
        'contract' => 'master_employee_contract',
        'form'     => 'pmk_form',
        'position' => 'master_position',
        'status'   => 'pmk_status'
    ];
    
    public function __construct()
    {
        parent::__construct();

        // load models
        $this->load->model(['divisi_model', 'dept_model', 'email_m', 'employee_m', 'posisi_m', 'pmk_m', "user_m"]);
        
        // Token Checker
        if(!empty($this->session->userdata('token'))){
            // hapus token dari database
            $this->Jobpro_model->delete('user_token', array('index' => 'token', 'data' => $this->session->userdata('token')));
            // hapus session token
            $this->session->unset_userdata('token');            
        }
    }
    

/* -------------------------------------------------------------------------- */
/*                                MAIN FUNCTION                               */
/* -------------------------------------------------------------------------- */
    
    /**
     * index page of PMK Module
     *
     * @return void
     */
    public function index()
    {
        // pmk data
        $data_divisi = $this->divisi_model->getAll(); // get all data divisi
        foreach($data_divisi as $k => $v){
            $data_divisi[$k]['emp_total'] = $this->employee_m->count_where(['div_id' => $v['id']]);
        }

        $position_my = $this->posisi_m->getMyPosition();
        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1 || $position_my['id'] == 1 || $position_my['id'] == 196){
            // ambil semua data pmk_status
            $data['pmk_status'] = $this->pmk_m->getAll_pmkStatus(); // get semua status info

        } else {
            // ambil data form di divisi dia aja
            $data['pmk_status'] = array(); $x = 0;
            $pmk_status = $this->pmk_m->getAll_pmkStatus(); // get semua status info
            foreach($pmk_status as $v){
                if(!empty($v['pic'])){
                    $pic = json_decode($v['pic'], true);
                    foreach($pic as $value){
                        if($value == $position_my['hirarki_org']){
                            $data['pmk_status'][$x] = $v;
                            $x++;
                        }
                    }
                }
            }
        }

        $data['divisi'] = $data_divisi;
        $data['userApp_admin'] = $this->userApp_admin; // flag apa dia admin atau bukan
        
        $data['position_my'] = $position_my;

        if($position_my['hirarki_org'] == "N" || $this->session->userdata('role_id') == 1 || $this->userApp_admin == 1){
            $data['filter_divisi'] = $this->divisi_model->getDivisi();
        }

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['index'];
		$data['load_view'] = 'pmk/index_pmk_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		// $data['custom_styles'] = array();
        $data['custom_script'] = array(
            'plugins/datatables/script_datatables',
            'plugins/daterange-picker/script_daterange-picker', 
            'pmk/script_index_pmk'
        );
        
		$this->load->view('main_v', $data);
    }

    public function assessment(){
        // data posisi
        $position_my = $this->posisi_m->getMyPosition();
        $position = $this->employee_m->getDetails_employee($this->input->get('nik'));
        // cek akses assessment
        $this->cekAkses_pmk($position_my, $position);

        // cek ketersediaan survey

        // assessment data
        $data['data_assess'] = array($this->input->get('nik'), $this->input->get('contract')); // ambil data nik dan contract di get dari url
        $data['pertanyaan'] = $this->pmk_m->getAll_pertanyaan();
        $data['employee'] = $this->employee_m->getDetails_employee($this->input->get('nik'));
        $data['contract'] = $this->pmk_m->getOnceWhere_contract(array('nik' => $this->input->get('nik'), 'contract' => $this->input->get('contract')));

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['assessment'];
		$data['load_view'] = 'pmk/assessment_pmk_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		$data['custom_styles'] = array('pmk_styles');
        $data['custom_script'] = array(
            'plugins/datatables/script_datatables',
            'pmk/script_assessment_pmk',
        );
        
		$this->load->view('main_v', $data);
    }
    
    /**
     * summary function to view per division
     *
     * @return void
     */
    function summary(){
        echo($this->input->get('div'));
    }

/* -------------------------------------------------------------------------- */
/*                                AJAX FUNCTION                               */
/* -------------------------------------------------------------------------- */
    
    /**
     * refresh karyawan kontrak yang bulan -2 selesai
     *
     * @return void
     */
    function pmk_refresh() {
        // cek akses admin
        $this->cekAkses_admin();
        // ambil bulan setelah 2 bulan lagi
        $date = strtotime("+2 month", time());
        // ambil data contract terakhir
        $data_contract = $this->db->query("SELECT nik, MAX(contract) AS contract FROM ".$this->table['contract']." GROUP BY nik ORDER BY nik")->result_array();
        // cari yg datenya udh beberapa bulan lagi
        $data_pmk = []; $x = 0; $counter_pmk = 0; $counter_new = 0;
        foreach($data_contract as $k => $v){
            // cek apa data sudah ada di pmk_form
            $vya = $this->pmk_m->getRow_form($v['nik'], $v['contract']);
            // cek apa kontraknya mau habis dalam 2 bulan
            $result = $this->_general_m->getOnce('nik, contract', $this->table['contract'], "nik = '".$v['nik']."' AND contract = '".$v['contract']."' AND date_end <= ".$date);
            // cek apa ada pada 2 bulan ke depan dengan kontrak terakhir
            if(!empty($result)){
                $counter_pmk++; // counter data yg abis di 2 bulan ke depan
                // cek apa tidak ada datanya di kontrak terakhir
                if($vya == 0){
                    // cek apa dia punya approver
                    $approver_nik = $this->employee_m->getApprover_nik($v['nik']); // ambil nik approver 1nya dia

                    $counter_new++; // counter new data
                    // prepare data
                    $data_pmk[$x]['id'] = $this->pmk_m->getId_form($result['nik'], $result['contract']);
                    if(!empty($approver_nik)){
                        $data_pmk[$x]['status'] = json_encode([
                            0 => [
                                'id_status' => 1,
                                'text' => 'Form generated.'
                            ]
                        ]);
                        $data_pmk[$x]['status_now_id'] = 1;
                    } else {
                        $data_pmk[$x]['status'] = json_encode([
                            0 => [
                                'id_status' => 8,
                                'text' => 'The System cannot found approver 1, so the assessment for this employee adressed to Division Head.'
                            ]
                        ]);
                        $data_pmk[$x]['status_now_id'] = 8;
                    }
                    $data_pmk[$x]['created'] = time();
                    $data_pmk[$x]['modified'] = time();

                    // $data_pmk[$x]['pmk_id'] = ""; // pmk_id nanti setelah hc divhead melakukan pembuatan summary
                    $x++;

                    $data_employee = $this->employee_m->getDetails_employee($v['nik']); // ambil detail data employee
                    // cek apa dia N-3, N-4, N-2, N-1, Functional-dept
                    // if($data_employee['hirarki_org'] == "N-3" || $data_employee['hirarki_org'] == "N-4"){
                    //     $email = $this->employee_m->getEmail_approver12($v['nik']); // ambil data email approver 1 dan 2 (N-2, N-1)                        
                    // } elseif($data_employee['hirarki_org'] == "N-2" || $data_employee['hirarki_org'] == "Functional-dept"){
                    //     $email = $this->employee_m->getEmail_approver1($v['nik']); // ambil data email dri approver1 (N-1)
                    // } elseif($data_employee['hirarki_org'] == "N-1" || $data_employee['hirarki_org'] == "Functional-div" || $data_employee['hirarki_org'] == "Functional-adm"){
                    //     $email = $this->employee_m->getEmail_approver1($v['nik']); // ambil data email dri approver1 (N)
                    // } else {
                    //     show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                    //     exit;
                    // }
                } else {
                    // nothing
                }
            } else {
                //nothing
            }
        }
        // masukkan ke table pmk_form
        if(!empty($data_pmk)){
            $this->_general_m->insertAll($this->table['form'], $data_pmk);

            foreach($data_pmk as $v){
                $data_employee = $this->employee_m->getDetails_employee(substr($v['id'], 0, 8)); // ambil detail data employee
                $approver_nik = $this->employee_m->getApprover_nik(substr($v['id'], 0, 8)); // ambil nik approver 1nya dia
                
                // jika dia gapunya atasan kirim email ke division head
                if(empty($approver_nik)){
                    $approver_data = $this->divisi_model->get_divHead($data_employee['div_id']); // ambil data divhead
                } else {
                    $approver_data = $this->employee_m->getDetails_employee($approver_nik);
                }
                
                $email = $approver_data['email']; // emailin ke approver 1
                // $email_cc = $data_employee['email']; // cc ke karyawannya sendiri
                $email_cc = "";
                $penerima_nama = $approver_data['emp_name'];
                $subject_email = "Employee Evaluation has been Started";
                $status = "Status: Draft";
                $details = '<tr>
                                <td>Employee Name</td>
                                <td>:</td>
                                <td>'. $data_employee['emp_name'] .'</td>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <td>:</td>
                                <td>'. $data_employee['nik'] .'</td>
                            </tr>
                            <tr>
                                <td>Division</td>
                                <td>:</td>
                                <td>'. $data_employee['divisi'] .'</td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td>:</td>
                                <td>'. $data_employee['departemen'] .'</td>
                            </tr>
                            <tr>
                                <td>Position</td>
                                <td>:</td>
                                <td>'. $data_employee['position_name'] .'</td>
                            </tr>';
                $msg = "This Employee Contract will be ended in 2 months after now, please fill the employee evaluation assessment below.";
                /* ------------------- create webtoken buat penerima email ------------------ */
                $resep = array( // buat resep token agar unik
                    'nik' => $data_employee['nik'],
                    'id_posisi' => $data_employee['position_id'],
                    'date' => date('d-m-Y, H:i:s:v:u', time())
                );
                $token = md5(json_encode($resep)); // md5 encrypt buat id token
                
                $data_temp_token  = array( // data buat disave di token
                    'direct'    => 'pmk'
                );
                $data_token = json_encode($data_temp_token);
                // masukkan data token ke database
                $this->_general_m->insert(
                    'user_token',
                    array(
                        'token'        => $token,
                        'data'         => $data_token,
                        'date_created' => date('Y-m-d H:i:s', time())
                    )
                ); 
                $url_token = urlencode($token);
                $link = base_url('direct').'?token='.$url_token;
                
                $this->email_m->general_sendEmail($email, $email_cc, $penerima_nama, $subject_email, $status, $details, $msg, $link);
            }
        }

        // ambil status aktif
        $pmk_active = $this->_general_m->getAll('id_status', $this->table['status'], ['is_active' => 1]);
        $counter_active = 0;
        foreach($pmk_active as $v){
            $count_row = $this->_general_m->getRow($this->table['form'], ['status_now_id' => $v['id_status']]);
            $counter_active = $counter_active + $count_row;
        }

        // ambil counter status inactive
        $pmk_inactive = $this->_general_m->getAll('id_status', $this->table['status'], ['is_active' => 0]);
        $counter_inactive = 0;
        foreach($pmk_inactive as $v){
            $count_row = $this->_general_m->getRow($this->table['form'], ['status_now_id' => $v['id_status']]);
            $counter_inactive = $counter_inactive + $count_row;
        }

        echo(json_encode([
            'counter_pmk' => $counter_pmk,
            'counter_active' => $counter_active,
            'counter_inactive' => $counter_inactive,
            'counter_new' => $counter_new
        ]));
        // simpan data pmk di database
    }
    
    /**
     * get list of assesment
     *
     * @return void
     */
    function ajax_getList() {
        // Array ( [showhat] => 0 [divisi] => [departemen] => [status] => [daterange] => 08/11/2020 - 12/11/2020 )
        $showhat = $this->input->post('showhat');
        $filter_divisi = $this->input->post('divisi');
        $filter_departemen = $this->input->post('departemen');
        $filter_status = $this->input->post('status');
        $filter_daterange = $this->input->post('daterange');

        // ambil data posisi
        $position_my = $this->posisi_m->getMyPosition();

        echo(json_encode(array(
            "data" => $this->pmk_m->getComplete_pmkList($position_my, $showhat, $filter_divisi, $filter_departemen, $filter_status, $filter_daterange)
        )));
    }

/* -------------------------------------------------------------------------- */
/*                               OTHER FUNCTION                               */
/* -------------------------------------------------------------------------- */
    function cekAkses_pmk($position_my, $position){
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
            } elseif($position_my['hirarki_org'] == "N-1") {
                // cek berdasarkan kesamaan divisi dan department
                if($position_my['div_id'] == $position['div_id'] && $position_my['dept_id'] == $position['dept_id']){
                    // perbolehkan akses
                } else {
                    show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
                    exit;
                }
            } elseif($position_my['hirarki_org'] == "N-2"){
                if($position_my['id'] == $position['id_approver1']){
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
     * cek akses dengan admin previledge
     *
     * @return void
     */
    function cekAkses_admin(){
        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1){
            // perbolehkan akses
        } else {
            // tolak izin
            show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
            exit;
        }
    }
    
    /**
     * save assessment survey data to database
     *
     * @return void
     */
    function saveAssessment(){
        print_r($_POST);
    }

    function test(){
        
    }

}

/* End of file Pmk.php */
