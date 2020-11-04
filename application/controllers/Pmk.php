<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pmk extends SpecialUserAppController {
    protected $id_menu = 12; // id menu

    // page title variable
    protected $page_title = [
        'index' => 'Evaluasi Masa Kontrak',
        'assessment' => 'Assessment Form',
        'summary' => 'Summary Evaluasi Masa Kontrak'
    ];

    protected $table = [
        'contract' => 'master_employee_contract',
        'form'     => 'pmk_form',
        'position' => 'master_position',
        'status'   => 'pmk_status',
        'summary'  => "pmk_form_summary",
        'summary_status' => 'pmk_status_summary',
        'survey'   => 'pmk_survey_hasil'
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
    public function index(){
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

        // pmk data
        $data['status_summary'] = $this->_general_m->getAll('id, name_text', $this->table['summary_status'], array());

        // ambil data summary dengan cek dia userapp admins, superadmin, 1, 196, N
        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1 || $position_my['id'] == 1 || $position_my['id'] == 196 || $position_my['hirarki_org'] == "N"){
            // cek jika dia 196, 1, atau N
            if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1 || $position_my['id'] == 1 || $position_my['id'] == 196){
                $data_divisi = $this->divisi_model->getAll(); // get all data divisi
                $data['chooseDivisi'] = ""; // set data buat choose divisi
            } elseif($position_my['hirarki_org'] == "N"){
                $data_divisi = $this->divisi_model->getAll_where(array('id' => $position_my['div_id']));
                $data['chooseDivisi'] = 'div-'.$position_my['div_id']; // set data buat choose divisi
            } else {
                show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
            }
            foreach($data_divisi as $k => $v){
                $data_divisi[$k]['emp_total'] = $this->employee_m->count_where(['div_id' => $v['id']]);
                // ambil summary yang sesuai dengan status dan jabatam
                if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1){
                    $myTask = $this->_general_m->getAll('id', $this->table['summary_status'], "pic='N' OR pic='1' OR pic='196'");
                } elseif($position_my['hirarki_org'] == "N"){
                    $myTask = $this->_general_m->getAll('id', $this->table['summary_status'], "pic='N'");
                } elseif($position_my['id'] == "196"){
                    $myTask = $this->_general_m->getAll('id', $this->table['summary_status'], "pic='196'");
                } elseif($position_my['id'] == "1"){
                    $myTask = $this->_general_m->getAll('id', $this->table['summary_status'], "pic='1'");                    
                } else {
                    show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                }

                $data_divisi[$k]["count_summary"] = 0;
                foreach($myTask as $value){
                    $data_divisi[$k]["count_summary"] += $this->_general_m->getRow($this->table['summary'], array('status_now_id' => $value['id'], 'id_div' => $v['id']));
                }
            }
            // more advance pmk data
            $data['summary'] = 1; // flag bahwa karyawan ini berhak melihat summary
            $data['divisi'] = $data_divisi;
            // beri script dengan summary script
            $data['custom_script'] = array(
                'plugins/datatables/script_datatables',
                'plugins/daterange-picker/script_daterange-picker', 
                'pmk/script_index_pmk',
                'pmk/script_summary_pmk'
            );
        } else {
            // beri script tanpa summary script
            $data['custom_script'] = array(
                'plugins/datatables/script_datatables',
                'plugins/daterange-picker/script_daterange-picker', 
                'pmk/script_index_pmk'
            );
        }

        $data['userApp_admin'] = $this->userApp_admin; // flag apa dia admin atau bukan
        $data['position_my'] = $position_my;

        // ambil data redirect
        if(!empty($this->input->get('direct'))){
            if($this->input->get('direct') == "sumhis"){
                $data['redirect_summary'] = 1;
            }
        }

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
        // $data['custom_script'] = array(
        //     'plugins/datatables/script_datatables',
        //     'plugins/daterange-picker/script_daterange-picker', 
        //     'pmk/script_index_pmk'
        // );
        
		$this->load->view('main_v', $data);
    }
    
    /**
     * assessment page of PMK Module
     *
     * @return void
     */
    public function assessment(){
        $nik = substr($this->input->get("id"), 0, 8);
        // data posisi
        $position_my = $this->posisi_m->getMyPosition();
        $position = $this->employee_m->getDetails_employee($nik);
        // cek akses assessment
        $data['is_access'] = $this->cekAkses_pmk($position_my, $position);

        $data['exist_empPhoto'] = $this->employee_m->check_empPhoto($nik); // check employee photo exist or not

        // cek ketersediaan survey
        $data['id_pmk'] = $this->input->get('id'); // ambil data nik dan contract di get dari url
        $data_pmk = $this->pmk_m->getOnceWhere_form(array('id' => $data['id_pmk']));
        if($data_pmk['status_now_id'] == 1 || $data_pmk['status_now_id'] == 2 || $data_pmk['status_now_id'] == 8){
            // akses edit
            $data['load_view'] = 'pmk/assessment_editor_pmk_v';
            $script_assessment = 'pmk/script_assessment_editor_pmk';
        } else {
            // akses preview
		    $data['load_view'] = 'pmk/assessment_viewer_pmk_v';
            $script_assessment = 'pmk/script_assessment_viewer_pmk';
        }

        // assessment data
        $detail_emp = $this->employee_m->getDetails_employee($nik);
        if($detail_emp['level_personal'] < 10){
            $where = "id_pertanyaan_tipe = 'A1'";
        } elseif($detail_emp['level_personal'] < 18){
            $where = "id_pertanyaan_tipe = 'A1' OR id_pertanyaan_tipe = 'A2'";
        } else {
            $where = "id_pertanyaan_tipe = 'A1' OR id_pertanyaan_tipe = 'A2' OR id_pertanyaan_tipe = 'A3'";
        }
        $data['pertanyaan'] = $this->pmk_m->getAllWhere_pertanyaan($where);
        $data['level_personal'] = $detail_emp['level_personal'];
        $data['employee'] = $position;
        $data['employee']['level_personal'] = $detail_emp['level_personal'];
        $contract_last = $this->pmk_m->getOnce_LastContractByNik($nik);
        $data['contract'] = $this->pmk_m->getOnce_contract($contract_last['nik'], $contract_last['contract']);

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['assessment'];
		// $data['load_view'] = 'pmk/assessment__editor_pmk_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		$data['custom_styles'] = array('pmk_styles');
        $data['custom_script'] = array(
            'plugins/datatables/script_datatables',
            $script_assessment
        );
        
		$this->load->view('main_v', $data);
    }
    
    /**
     * summary function to view per division
     *
     * @return void
     */
    function summary(){
        $position_my = $this->posisi_m->getMyPosition(); // ambil data my position
        // cek akses apa user ini diperbolehkan akses summary
        $this->cekAkses_summary($position_my);
        
        // ambil detail divisi
        $detail_divisi = $this->divisi_model->getOnceById($this->input->get('div'));
        $detail_divisi['divhead_name'] = $this->employee_m->getDetails_employee($detail_divisi['nik_div_head'])['emp_name']; // ambil nama divhead
        
        // summary data
        $data['divisi'] = $detail_divisi;
        $data['id_div'] = $this->input->get('div');
        $data['status_summary'] = $this->_general_m->getAll('id, name_text', $this->table['summary_status'], array());

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['summary'];
		$data['load_view'] = 'pmk/summary_index_pmk_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		$data['custom_styles'] = array('pmk_styles');
        $data['custom_script'] = array(
            'plugins/datatables/script_datatables',
            'plugins/daterange-picker/script_daterange-picker',
            'pmk/script_summary_pmk'
        );
        
		$this->load->view('main_v', $data);
    }

    /**
     * summary process
     *
     * @return void
     */
    function summary_process(){
        // summary data
        $data['id_summary'] = $this->input->get('id'); // id summary
        $data_summary = $this->getSummaryListProcess($this->input->get('id'));
        $data['data_summary'] = $data_summary['data']; // data summary for table
        $data['summary'] = $data_summary['summary']; // summary identities
        $data['pa_year'] = $data_summary['pa_year']; // data year pa
        $data['entity'] = $this->entity_m->getAll_notAtAll(); // semua data entity

        // ambil data my position
        $position_my = $this->posisi_m->getMyPosition();
        $data['position_my'] =  $position_my; 

        // cek akses buat ngubah summary action, ngisi notes dan submit summary
        if(($data_summary['summary']['status_now_id'] == "pmksum-01" && $position_my['hirarki_org'] == "N" && $position_my['id'] != 196 && $position_my['id'] != 1) ||
           ($data_summary['summary']['status_now_id'] == "pmksum-02" && $position_my['id'] == 196) ||
           ($data_summary['summary']['status_now_id'] == "pmksum-03" && $position_my['id'] == 1)){
            $data['is_akses'] = 1;
        } else {
            $data['is_akses'] = 0;
        }

        // if untuk set pesan alert
        if($data_summary['summary']['status_now_id'] != "pmksum-01"){
            if($position_my['hirarki_org'] == "N" && $position_my['id'] != 196 && $position_my['id'] != 1){
                $data['alert_message'] = $this->getAlertSummary(0);
            } else {
                $data['alert_message'] = $this->getAlertSummary(1);
            }
        } elseif($data_summary['summary']['status_now_id'] != "pmksum-02"){
            if($position_my['id'] == 196){
                $data['alert_message'] = $this->getAlertSummary(0);
            } else {
                $data['alert_message'] = $this->getAlertSummary(1);
            }
        } elseif($data_summary['summary']['status_now_id'] != "pmksum-03"){
            if($position_my['id'] == 1){
                $data['alert_message'] = $this->getAlertSummary(0);
            } else {
                $data['alert_message'] = $this->getAlertSummary(1);
            }
        }

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['summary'];
		$data['load_view'] = 'pmk/summary_process_pmk_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		// $data['custom_styles'] = array('pmk_styles');
        $data['custom_script'] = array(
            'plugins/datatables/script_datatables',
            // 'plugins/daterange-picker/script_daterange-picker',
            'plugins/ckeditor/script_ckeditor',
            'pmk/script_summary_process_pmk'
        );
        
		$this->load->view('main_v', $data);
    }
    
    /**
     * summary process, the data loaded using ajax
     *
     * @return void
     */
    function summary_process_ajax(){
        // $id_summary = $this->input->get('id');

        // print_r($id_summary);

        // summary data
        $data['id_summary'] = $this->input->get('id');

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['summary'];
		$data['load_view'] = 'pmk/summary_process_pmk_v_ajax';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		// $data['custom_styles'] = array('pmk_styles');
        $data['custom_script'] = array(
            'plugins/datatables/script_datatables',
            // 'plugins/daterange-picker/script_daterange-picker',
            'pmk/script_summary_process_pmk_ajax'
        );
        
		$this->load->view('main_v', $data);
    }

/* -------------------------------------------------------------------------- */
/*                                AJAX FUNCTION                               */
/* -------------------------------------------------------------------------- */
    
    /**
     * get assessment survey hasil data
     *
     * @return void
     */
    function ajax_getAssessmentData(){
        $id = $this->input->post('id');

        // cek akses
        $nik = substr($id, 0, 8);
        $position_my = $this->posisi_m->getMyPosition();
        $position = $this->employee_m->getDetails_employee($nik);
        // cek akses assessment
        $this->cekAkses_pmk($position_my, $position);

        if($this->_general_m->getRow($this->table['survey'], array('id' => $id)) > 0){ // cek jika ada isi surveynya
            $data = $this->pmk_m->getAllWhere_assessment($id); // ambil data jawaban survey
            $status = 1; // beri tanda status
        } else {
            $data = ""; // set kosong data
            $status = 0; // beri tanda status
        }
        
        echo(json_encode(array(
            'data' => $data,
            'status' => $status
        )));
    }

    /**
     * get list of assesment
     *
     * @return void
     */
    function ajax_getList() {
        // ambil semua parameter
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
    
    /**
     * refresh karyawan kontrak yang bulan -2 selesai
     *
     * @return void
     */
    function pmk_refresh() {
        // cek akses admin
        $this->cekAkses_admin();
        // ambil bulan setelah 2 bulan lagi
        // $date = strtotime("+2 month", time());
        // ambil hari terakhir di dua bulan lagi
        // TODO buat range pengambilan tanggal by setting
        $date = strtotime(date('t-m-Y', strtotime("+2 month", time())));
        // ambil data contract terakhir
        $data_contract = $this->pmk_m->getAll_LastContract();
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

                    $emp_data = $this->employee_m->getDetails_employee($v['nik']);
                    $counter_new++; // counter new data
                    // prepare data
                    $data_pmk[$x]['id'] = $this->pmk_m->getId_form($result['nik'], $result['contract']);
                    if(!empty($approver_nik)){
                        if($emp_data['hirarki_org'] == "Functional-div" || $emp_data['hirarki_org'] == "Functional-adm"){
                            $data_pmk[$x]['status'] = json_encode([
                                0 => [
                                    'id_status' => 8,
                                    'by' => 'system',
                                    'nik' => '',
                                    'time' => time(),
                                    'text' => 'Form Generated and the assessment for this employee adressed to Division Head.'
                                ]
                            ]);
                            $data_pmk[$x]['status_now_id'] = 8;
                        } else {
                            $data_pmk[$x]['status'] = json_encode([
                                0 => [
                                    'id_status' => 1,
                                    'by' => 'system',
                                    'nik' => '',
                                    'time' => time(),
                                    'text' => 'Form generated.'
                                ]
                            ]);
                            $data_pmk[$x]['status_now_id'] = 1;
                        }
                    } else {
                        $data_pmk[$x]['status'] = json_encode([
                            0 => [
                                'id_status' => 8,
                                'by' => 'system',
                                'nik' => '',
                                'time' => time(),
                                'text' => 'The System cannot found approver 1, so the assessment for this employee adressed to Division Head.'
                            ]
                        ]);
                        $data_pmk[$x]['status_now_id'] = 8;
                    }
                    $data_pmk[$x]['created'] = time();
                    $data_pmk[$x]['modified'] = time();

                    $data_employee = $this->employee_m->getDetails_employee($v['nik']); // ambil detail data employee
                    $data_pmk[$x]['id_summary'] = date("Ym", $date).$data_employee['div_id']; // pmk_id nanti setelah hc divhead melakukan pembuatan summary
                    $this->cekPmkSummary($data_pmk[$x]['id_summary'], $date, $data_employee['div_id']); // lakukan pemeriksaan summary
                    $x++;
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
     * get assessment per employee timeline
     *
     * @return void
     */
    function ajax_getTimeline(){
        $id = $this->input->post('id');
        // $id = "CG00030901200103";

        // cek akses
        $nik = substr($id, 0, 8);
        $position_my = $this->posisi_m->getMyPosition();
        $position = $this->employee_m->getDetails_employee($nik);
        // cek akses assessment
        $this->cekAkses_pmk($position_my, $position);

        $temp_status_data = $this->pmk_m->getDetail_pmkStatus($id); // ambil data jawaban survey
        $status_data = array_reverse($temp_status_data);

        foreach($status_data as $k => $v){
            $el = $this->pmk_m->getDetail_pmkStatusDetailByStatusId($v['id_status']);
            // get status data attribute
            $status_data[$k]['time'] = date("j M o<~>H:i", $v['time']);
            $status_data[$k]['name_text'] = $el['name_text'];
            $status_data[$k]['css_color'] = $el['css_color'];
            $status_data[$k]['icon'] = $el['icon'];
        }

        echo(json_encode($status_data));
    }
    
    /**
     * get summary list
     *
     * @return void
     */
    function ajax_getSummaryList(){
        $switchData = $this->input->post('switchData');
        $filter_status = $this->input->post('filter_status');
        $filter_daterange = $this->input->post('filter_daterange');
        $position_my = $this->posisi_m->getMyPosition();

        if(!empty($this->input->post('divisi'))){
            $divisi = explode('-', $this->input->post('divisi'))[1];
            $where = "id_div=".$divisi;
        } else {
            $where = "";
        }

        // cek apa datanya ambil history atau mytask
        if($switchData == 0){ // apabila mytask
            // cek apa wherenya empty
            if(empty($where)){
                // nothing
            } else {
                $where .= " AND ";
            }

            // cek hirarki
            if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1){ // apakah dia admin?
                $where .= "status_now_id='pmksum-01' OR status_now_id='pmksum-02' OR status_now_id='pmksum-03'";
            } elseif($position_my['id'] == 196){ // apakah dia hc divhead?
                // $status = "pmksum-02";
                $where .= "status_now_id='pmksum-02' OR (status_now_id='pmksum-01' AND id_div='6')";
            } elseif($position_my['id'] == 1){ // apakah dia CEO?
                // $status = "pmksum-03";
                $where .= "status_now_id='pmksum-03'";
            } elseif($position_my['hirarki_org'] == "N"){ // apakah dia divhead?
                // cek akses buat N
                if($position_my['div_id'] != $divisi){
                    show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
                }
                // $status = "pmksum-01";
                $where .= "status_now_id='pmksum-01'";
            } else { // bukan siapa-siapa?
                show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
            }

        } elseif($switchData == 1){ // apabila history
            // $where = "id_div=$divisi";

            // filtering if
            if(!empty($filter_status)){
                if(empty($where)){
                    // nothing
                } else {
                    $where .= " AND ";
                }
                $where .= "status_now_id = '$filter_status'";
            }
            if(!empty($filter_daterange)){
                if(empty($where)){
                    // nothing
                } else {
                    $where .= " AND ";
                }
                $daterange = explode(" - ", $filter_daterange); // pisahkan dulu daterangenya
                $daterange[0] = strtotime($daterange[0]);
                $daterange[1] = strtotime($daterange[1]);
                $where .= "created >= ".$daterange[0]." AND created <= ".$daterange[1]; // tambahkan where tanggal buat ngebatesin view biar ga load lama
            }
        } else {
            show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
        }
        
        // ambil data summary
        $data = $this->_general_m->getAll('*', $this->table['summary'], $where);

        // lengkapi data
        foreach($data as $k => $v){
            // data divisi
            $result_data = $this->divisi_model->getOnceById($v['id_div']);
            $data[$k]['divisi_name'] = $result_data['division'];
            
            // data status
            $status = $this->pmk_m->getOnceWhere_statusSummary(array('id' => $v['status_now_id']));
            $data[$k]['status_now'] = json_encode(array('status' => $status, 'trigger' => $v['id_summary']));

            // olah data tanggal
            $data[$k]['date'] = date('F (Y)', $v['deadline']);
            $data[$k]['created'] = date('j M Y, H:i', $v['created']);
            $data[$k]['modified'] = date('j M Y, H:i', $v['modified']);
            $data[$k]['employee_total'] = $this->_general_m->getRow($this->table['form'], array('id_summary' => $v['id_summary']));
        }

        echo(json_encode(array(
            'data' => $data
        )));
    }
        
    /**
     * ambil data summary list process
     *
     * @return void
     */
    function ajax_getSummaryListProcess(){
        $id_summary = $this->input->post('id_summary');

        // ambil detail data form summarynya
        $data_summary = $this->pmk_m->getDetail_summary($id_summary);

        // data divisi
        $result_data = $this->divisi_model->getOnceById($data_summary['id_div']);
        $data_summary['divisi_name'] = $result_data['division'];
        
        // data status
        $status = $this->pmk_m->getOnceWhere_statusSummary(array('id' => $data_summary['status_now_id']));
        $data_summary['status_now'] = json_encode(array('status' => $status, 'trigger' => $data_summary['id_summary']));

        // olah data tanggal
        $data_summary['bulan'] = date('F (m)', $data_summary['created']);
        $data_summary['tahun'] = date('Y', $data_summary['created']);
        $data_summary['created'] = date('j M Y, H:i', $data_summary['created']);
        $data_summary['modified'] = date('j M Y, H:i', $data_summary['modified']);

        // ambil data form
        $pmk = $this->pmk_m->getAllWhere_form(array('id_summary' => $id_summary));
        $data_form = $this->pmk_m->detail_summary($pmk);

        echo(json_encode(array(
            'data' => $data_form,
            'summary' => $data_summary
        )));
    }
    
    /**
     * ajax update summary
     *
     * @return void
     */
    function ajax_updateApproval(){
        // cek akses
        $this->cekAkses_summary($this->posisi_m->getMyPosition());
        
        $id = $this->input->post('id');
        $value = $this->input->post('value');
        $entity = $this->input->post('entity');
        $extend_for = $this->input->post('extend_for');

        // cek untuk menentukan identitas user
        $position_my = $this->posisi_m->getMyPosition();

        // ambil data summary
        $summary_result = $this->pmk_m->getOnceWhereSelect_form('recomendation', array('id' => $id));
        if(empty($summary_result)){ // jika summary resultnya kosong
            $summary_data = array(); // siapkan array kosong
        } else {
            $summary_data = json_decode($summary_result['recomendation'], true); // keluarkan summary
        }

        // update data summary
        $summary_data['entity'] = $entity;
        $summary_data['summary'] = $value;
        $summary_data['extend_for'] = $extend_for;

        // update ke database
        $this->pmk_m->updateForm(
            array(
                'recomendation' => json_encode($summary_data),
                'modified' => time()
            ),
            array('id' => $id)
        );

    }

/* -------------------------------------------------------------------------- */
/*                                DATA FUNCTION                               */
/* -------------------------------------------------------------------------- */
    /**
     * ambil data summary list process
     *
     * @return void
     */
    function getSummaryListProcess($id_summary){
        // ambil detail data form summarynya
        $data_summary = $this->pmk_m->getDetail_summary($id_summary);

        // data divisi
        $result_data = $this->divisi_model->getOnceById($data_summary['id_div']);
        $data_summary['divisi_name'] = $result_data['division'];
        
        // data status
        $status = $this->pmk_m->getOnceWhere_statusSummary(array('id' => $data_summary['status_now_id']));
        $data_summary['status_now'] = json_encode(array('status' => $status, 'trigger' => $data_summary['id_summary']));

        // olah data tanggal
        $data_summary['bulan'] = date('F (m)', $data_summary['created']);
        $data_summary['tahun'] = date('Y', $data_summary['created']);
        $data_summary['created'] = date('j M Y, H:i', $data_summary['created']);
        $data_summary['modified'] = date('j M Y, H:i', $data_summary['modified']);

        // ambil data form
        $pmk = $this->pmk_m->getAllWhere_form(array('id_summary' => $id_summary));
        $data_form = $this->pmk_m->detail_summary($pmk);

        return array(
            'data'    => $data_form['data_pmk'],
            'pa_year' => $data_form['pa_year'],
            'summary' => $data_summary
        );
    }

/* -------------------------------------------------------------------------- */
/*                               OTHER FUNCTION                               */
/* -------------------------------------------------------------------------- */    
    
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
     * cek akses siapa aja yang boleh akses pmk
     *
     * @param  mixed $position_my
     * @param  mixed $position
     * @return void
     */
    function cekAkses_pmk($position_my, $position){
        // cek apa dia admin atau userapp admin
        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1 || $position_my['id'] == 1){
            // perbolehkan akses bebas
            $value = 3; // flag bisa akses tapi ga berhak submit
        } elseif($position_my['id'] == 196){
            if($position_my['div_id'] == $position['div_id']){
                // perbolehkan akses
                $value = 1;
            } else {
                // perbolehkan akses tapi jangan kasih dia buat submit form
                $value = 3;
            }
        } else {
            // cek berdasarkan hirarki
            if($position_my['hirarki_org'] == "N"){
                if($position_my['div_id'] == $position['div_id']){
                    // perbolehkan akses
                    $value = 1;
                } else {
                    show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
                    exit;
                }
            } elseif($position_my['hirarki_org'] == "N-1") {
                // cek berdasarkan kesamaan divisi dan department
                if($position_my['div_id'] == $position['div_id'] && $position_my['dept_id'] == $position['dept_id']){
                    // perbolehkan akses
                    $value = 1;
                } else {
                    show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
                    exit;
                }
            } elseif($position_my['hirarki_org'] == "N-2"){
                if($position_my['id'] == $position['id_approver1']){
                    // perbolehkan akses
                    $value = 1; // beri tanda kalo dia N-2
                } else {
                    show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
                    exit;
                }
            } else {
                show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
                exit;
            }
        }
        return $value;
        // cek otoritas apa divisi id dan dept idnya sama antara my position dengan id posisi yang dituju
    }
    
    /**
     * cek akses siapa aja yang boleh akses summary
     *
     * @return void
     */
    function cekAkses_summary($position_my){
        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1 || $position_my['id'] == 1 || $position_my['id'] == 196 || $position_my['hirarki_org'] == "N"){
            // perbolehkan akses
        } else {
            show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
        }
    }
    
    /**
     * cek summary pmk jika ada buat pmk summary baru di 2 bulan ke depan
     *
     * @return void
     */
    function cekPmkSummary($id_summary, $date, $id_div){
        if($this->_general_m->getRow($this->table['summary'], array('id_summary' => $id_summary)) < 1){
            // buat data status summary pmk
            $data['id_summary'] = $id_summary;
            $data['bulan']  = date("m", $date);
            $data['tahun']  = date("Y", $date);
            $data['id_div'] = $id_div;
            $data['status'] = json_encode([
                0 => [
                    'id_status' => "pmksum-01",
                    'by' => 'system',
                    'nik' => '',
                    'time' => time(),
                    'text' => 'Summary form generated.'
                ]
            ]);
            $data['status_now_id'] = "pmksum-01";
            $data['deadline'] = $date;
            $data['created'] = time();
            $data['modified'] = time();

            $this->pmk_m->saveSummary($data);
        } else {
            // nothing
        }
    }
    
    /**
     * get summary detail data
     *
     * @param  mixed $data_summary
     * @return void
     */
    function detailSummary($data_summary){
        // lengkapi data
        foreach($data_summary as $k => $v){
            // data divisi
            $result_data = $this->divisi_model->getOnceById($v['id_div']);
            $data[$k]['divisi_name'] = $result_data['division'];
            
            // data status
            $status = $this->pmk_m->getOnceWhere_statusSummary(array('id' => $v['status_now_id']));
            $data[$k]['status_now'] = json_encode(array('status' => $status, 'trigger' => $v['id_summary']));

            // olah data tanggal
            $data[$k]['created'] = date('j M Y, H:i', $v['created']);
            $data[$k]['modified'] = date('j M Y, H:i', $v['modified']);
        }

        return $data;
    }

    /**
     * getalert summary detail
     * 
     * @return void
     */
    function getAlertSummary($switch){
        if($switch == 0){
            return array(
                'type' => 'warning',
                'icon' => 'fa-exclamation-triangle',
                'title' => 'Warning!',
                'text' => "You can't submit this summary until all employee assessment finished."
            );
        } else {
            return array(
                'type' => 'info',
                'icon' => 'fa-info',
                'title' => 'Info',
                'text' => "You can't process this summary for at this moment."
            );
        }
    }
    
    /**
     * save assessment survey data to database
     * 
     * @return void
     */
    function saveAssessment(){
        // proses data post
        $data_assess = $this->saveAssessment_post();

        // ambil form detail
        $pmk_data = $this->pmk_m->getOnceWhere_form(array('id' => $this->input->post('id')));
        $status_new = json_decode($pmk_data['status'], true); // ubah status detailnya
        $penilai = $this->employee_m->getDetails_employee($this->session->userdata('nik'));
        
        // data posisi
        $nik = substr($this->input->post("id"), 0, 8);
        $position_my = $this->posisi_m->getMyPosition();
        $position = $this->employee_m->getDetails_employee($nik);
        // cek akses assessment
        $this->cekAkses_pmk($position_my, $position);

        if($this->input->post('action') == 0){ // jika actionnya save
            // cek status sebelumnya
            if($pmk_data['status_now_id'] == 1){
                $status_now_id = "1";
                $status_new[array_key_last($status_new)+1] = array(
                    'id_status' => "1",
                    'by' => $penilai['emp_name'],
                    'nik' => $penilai['nik'],
                    'time' => time(),
                    'text' => 'Assessment form was changed.'
                );
            } elseif($pmk_data['status_now_id'] == 2){
                $status_now_id = "2";
                $status_new[array_key_last($status_new)+1] = array(
                    'id_status' => "2",
                    'by' => $penilai['emp_name'],
                    'nik' => $penilai['nik'],
                    'time' => time(),
                    'text' => 'Assessment form was changed.'
                );
            } elseif($pmk_data['status_now_id'] == 8){
                $status_now_id = "8";
                $status_new[array_key_last($status_new)+1] = array(
                    'id_status' => "8",
                    'by' => $penilai['emp_name'],
                    'nik' => $penilai['nik'],
                    'time' => time(),
                    'text' => 'Assessment form was changed.'
                );
            } else {
                show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
            }
        } else { // jika actionnya submit
            if($penilai['hirarki_org'] == "N-2"){
                // cek jika atasannya (N-1) ada atau engga
                if(empty($this->posisi_m->whoIsOnThisPosition($penilai['id_approver1']))){ // kalo N-1nya kosong isi dengan status id 8
                    $status_now_id = "8";
                    $status_new[array_key_last($status_new)+1] = array(
                        'id_status' => "2",
                        'by' => $penilai['emp_name'],
                        'nik' => $penilai['nik'],
                        'time' => time(),
                        'text' => 'Assessment form was submitted by N-2.'
                    );
                } else { // seperti biasa buat N-1
                    $status_now_id = "2";
                    $status_new[array_key_last($status_new)+1] = array(
                        'id_status' => "2",
                        'by' => $penilai['emp_name'],
                        'nik' => $penilai['nik'],
                        'time' => time(),
                        'text' => 'Assessment form was submitted by N-2.'
                    );
                }
            } else{
                $status_now_id = "3";
                if($penilai['hirarki_org'] == "N-1"){
                    $status_new[array_key_last($status_new)+1] = array(
                        'id_status' => "3",
                        'by' => $penilai['emp_name'],
                        'nik' => $penilai['nik'],
                        'time' => time(),
                        'text' => 'Assessment form was submitted by N-1.'
                    );
                } elseif($penilai['hirarki_org'] == "N"){
                    $status_new[array_key_last($status_new)+1] = array(
                        'id_status' => "3",
                        'by' => $penilai['emp_name'],
                        'nik' => $penilai['nik'],
                        'time' => time(),
                        'text' => 'Assessment form was submitted by N.'
                    );
                } else {
                    show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                }
            }
        }

        // masukkan ke database
        if($this->_general_m->getRow($this->table['survey'], array('id' => $this->input->post('id'))) > 0){ // cek jika ada isi surveynya
            $this->pmk_m->delete_assessment($this->input->post('id'));
        }
        $this->pmk_m->insertAll_surveyHasil($data_assess['data_assess']); // masukkan data penilaian assessment

        // prepare updated data
        $update_pmk = array(
            'status' => json_encode($status_new),
            'status_now_id' => $status_now_id,
            'modified' => time(),
            'survey_rerata' => json_encode($data_assess['data_rerata'])
        );
        // update pmk data form
        $this->pmk_m->updateForm($update_pmk, array('id' => $this->input->post('id')));

        redirect('pmk');
    }
    
    /**
     * this function used to process data from post to a ready-to-insert variable to database
     *
     * @return void
     */
    function saveAssessment_post(){
        // ambil tipe pertanyaan
        $pertanyaan = $this->pmk_m->getAll_pertanyaan();

        $pmk_survey = array(); $x = 0;
        foreach($pertanyaan as $v){
            foreach($this->input->post() as $key => $value){
                if($v['id_pertanyaan'] == $key){
                    $pmk_survey[$x]['id'] = $this->input->post('id');
                    $pmk_survey[$x]['id_pertanyaan'] = $key;
                    $pmk_survey[$x]['jawaban'] = $value;
                    $pmk_survey[$x]['pertanyaan_kustom'] = "";
                    $x++;
                }
            }
        }

        // khusus untuk pertanyaan technical
        $y = 0;
        foreach($this->input->post() as $k => $v){
            if(fnmatch("B0*", $k)){ // cek apa dia technical competency assessment
                if(!fnmatch("*_pertanyaan", $k)){ // cek jika bukan pertanyaan
                    if(!empty($this->input->post($k."_pertanyaan"))){ // cek apa pertanyaannya kosong
                        $pmk_survey[$x]['id'] = $this->input->post('id');
                        $pmk_survey[$x]['id_pertanyaan'] = "B0-".str_pad($y, 2, '0', STR_PAD_LEFT);
                        $pmk_survey[$x]['jawaban'] = $v;
                        $pmk_survey[$x]['pertanyaan_kustom'] = $this->input->post($k."_pertanyaan");
                        $x++; $y++;
                    }
                }
            }
        }

        // ambil semua tipe pertanyaan
        $pertanyaan_tipe = $this->pmk_m->getAll_IdSurveyPertanyaanTipe(); $y = 0; $rerata = array();
        // ambil data rata-rata
        foreach($pertanyaan_tipe as $v){
            $rerata[$v] = $this->input->post('rerata_'.$v);
        }
        $rerata['B0'] = $this->input->post('rerata_B0'); // ambil data rata-rata khusus technical pertanyaan
        $rerata['total'] = $this->input->post('rerata_keseluruhan'); // ambil rerata keseluruhan khusus technical pertanyaan

        return(array(
            'data_assess' => $pmk_survey,
            'data_rerata' => $rerata
        ));
    }
    
    /**
     * fungsi untuk mengupdate process summary
     *
     * @return void
     */
    function updateSummaryProcess(){
        $notes = $this->input->post('notes');
        $id_summary = $this->input->post('id_summary');

        // ambil data pribadi
        $whoami = $this->employee_m->getDetails_employee($this->session->userdata('nik'));
        // result for summary
        $result_summary = $this->_general_m->getOnce('status, notes', $this->table['summary'], array('id_summary' => $id_summary));
        // ambil status dari summary
        $summary_status_new = json_decode($result_summary['status'], true);
        // ambil data pesan
        if(!empty($result_summary['notes'])){
            $summary_notes = json_decode($result_summary['notes'], true);
        } else {
            $summary_notes = array(
                'N' => array(
                    'whoami' => "Division Head",
                    'by'     => "",
                    'time'   => "",
                    'text'   => ""
                ),
                196 => array(
                    'whoami' => 'HC Divhead',
                    'by'     => "",
                    'time'   => "",
                    'text'   => ""
                ),
                1   => array(
                    'whoami' => 'CEO',
                    'by'     => "",
                    'time'   => "",
                    'text'   => ""
                )
            );
        }
        // atur status now
        if($whoami['position_id'] == 196){
            $summary_status_now_id = "pmksum-03";
            $summary_status_text = "Summary form was submitted by HC Divhead.";
            $summary_notes[$whoami['position_id']]['text'] = $notes;
            $summary_notes[$whoami['position_id']]['by'] = $whoami['position_name'];
            $summary_notes[$whoami['position_id']]['time'] = time();
        } elseif($whoami['position_id'] == 1){
            $summary_status_now_id = "pmksum-04";
            $summary_status_text = "Contract Evaluation has been Completed.";
            $summary_notes[$whoami['position_id']]['text'] = $notes;
            $summary_notes[$whoami['position_id']]['by'] = $whoami['position_name'];
            $summary_notes[$whoami['position_id']]['time'] = time();
        } elseif($whoami['hirarki_org'] == "N"){
            $summary_status_now_id = "pmksum-02";
            $summary_status_text = "Summary form was submitted by N.";
            $summary_notes[$whoami['hirarki_org']]['text'] = $notes;
            $summary_notes[$whoami['hirarki_org']]['by'] = $whoami['position_name'];
            $summary_notes[$whoami['hirarki_org']]['time'] = time();
        } else {
            show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
        }
        // update status summary
        $summary_status_new[array_key_last($summary_status_new)+1] = array(
            'id_status' => $summary_status_now_id,
            'by' => $whoami['emp_name'],
            'nik' => $whoami['nik'],
            'time' => time(),
            'text' => $summary_status_text
        );
        // prepare updated summary data
        $update_pmkSummary = array(
            'notes' => json_encode($summary_notes),
            'status' => json_encode($summary_status_new),
            'status_now_id' => $summary_status_now_id,
            'modified' => time()
        );
        // update pmk data form
        $this->pmk_m->updateForm_summary($update_pmkSummary, array('id_summary' => $id_summary));

        // update satu persatu data form karyawan
        $form = $this->pmk_m->getAllWhereSelect_form('id, status', array('id_summary' => $id_summary));
        foreach($form as $v){
            $status_new = json_decode($v['status'], true);
            // beri status now id sesuai dengan siapa yang menilai
            if($whoami['position_id'] == 196){
                $status_now_id = 5;
                $status_text = $notes;
            } elseif($whoami['position_id'] == 1){
                $status_now_id = 7;
                $status_text = $notes;
            } elseif($whoami['hirarki_org'] == "N"){
                $status_now_id = 4;
                $status_text = $notes;
            } else {
                show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
            }
            // update status form karyawan
            $status_new[array_key_last($status_new)+1] = array(
                'id_status' => $status_now_id,
                'by' => $whoami['emp_name'],
                'nik' => $whoami['nik'],
                'time' => time(),
                'text' => $status_text
            );
            // prepare updated form data
            $update_pmk = array(
                'status' => json_encode($status_new),
                'status_now_id' => $status_now_id,
                'modified' => time()
            );
            // update ke database
            $this->pmk_m->updateForm($update_pmk, array('id' => $v['id']));
        }

        // redirect ke pmk summary
        header('location: ' . base_url('pmk').'?direct=sumhis');
    }

    function test(){
        $date = strtotime(date('t-m-Y', strtotime("+2 month", time())));
        
        
        print_r($date);
        echo("<br/>".date('d-m-Y', $date));
    }

}

/* End of file Pmk.php */
