<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pmk extends SpecialUserAppController {
    protected $app_name = "Employee Requisition";
    protected $id_menu = 12; // id menu

    // page title variable
    protected $page_title = [
        'index' => 'Penilaian Kontrak',
        'assessment' => 'Assessment Form',
        'summary' => 'Approval Penilaian Kontrak'
    ];

    protected $table = [
        'admin'    => 'user_userapp_admins',
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
        // ambil nama table yang terupdate
        $this->load->library('tablename');
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

            // ambil data departemen khusus buat division head
            if($position_my['hirarki_org'] == "N" && $position_my['id'] != 1 && $position_my['id'] != 196){
                $data['department'] = $this->dept_model->getAll_where(array('div_id' => $position_my['div_id']));
            }

            // more advance pmk data
            $data['summary'] = 1; // flag bahwa karyawan ini berhak melihat summary
            $data['divisi'] = $data_divisi;
            // beri script dengan summary script
            $data['custom_script'] = array(
                'plugins/datatables/script_datatables',
                'plugins/daterange-picker/script_daterange-picker',
                // 'plugins/daterange-superpicker/script_daterange-superpicker',
                'pmk/script_index_pmk',
                'pmk/script_summary_pmk'
            );
        } else {
            // beri script tanpa summary script
            $data['custom_script'] = array(
                'plugins/datatables/script_datatables',
                'plugins/daterange-picker/script_daterange-picker',
                // 'plugins/daterange-superpicker/script_daterange-superpicker',
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
        if(empty($nik)){ // cek apa niknya ada
            show_404();
        }
        // data posisi
        $position_my = $this->posisi_m->getMyPosition();
        $position = $this->employee_m->getDetails_employee($nik);
        // cek akses assessment
        $data['is_access'] = $this->cekAkses_pmk($position_my, $position);
        // cek akses jika dia mau ngakses data sendiri
        if($this->session->userdata('nik') == $nik){
            show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
            exit;
        } else {
            // izinkan akses
        }
        $data['exist_empPhoto'] = $this->employee_m->check_empPhoto($nik); // check employee photo exist or not

        // cek ketersediaan survey
        $data['id_pmk'] = $this->input->get('id'); // ambil data nik dan contract di get dari url
        $data_pmk = $this->pmk_m->getOnceWhere_form(array('id' => $data['id_pmk']));
        if(empty($data_pmk)){ // cel apa data pmk kosong
            show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
        }
        if($data_pmk['status_now_id'] == 1 || $data_pmk['status_now_id'] == 2 || $data_pmk['status_now_id'] == 9){
            // cek izin akses khusus CEO dan HC Division Head
            $editable = 0; // penanda editable
            if($position_my['id'] == 196 || $position_my['id'] == 1){
                if($position_my['div_id'] == $position['div_id']){
                    $editable = 1;
                } else {
                    $editable = 0;
                }
            } else {
                $editable = 1;
            }

            if($editable == 1){ // jika editable
                // akses edit
                $data['load_view'] = 'pmk/assessment_editor_pmk_v';
                $script_assessment = 'pmk/script_assessment_editor_pmk';
            } else { // jika tidak editable
                // akses preview
                $data['load_view'] = 'pmk/assessment_viewer_pmk_v';
                $script_assessment = 'pmk/script_assessment_viewer_pmk';
            }
        } else {
            // akses preview
		    $data['load_view'] = 'pmk/assessment_viewer_pmk_v';
            $script_assessment = 'pmk/script_assessment_viewer_pmk';
        }

        // cek untuk kesediaan direct_summary
        $direct_summary = $this->input->get('direct_summary');
        if(!empty($direct_summary)){ // jika direct summary ada
            $data['direct_url'] = base_url('pmk/summary_process')."?id=".$direct_summary;
        } else {
            $data['direct_url'] = base_url('pmk');
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
     * kirim email notifikasi bahwa semua assessment
     * dalam summary di form assessment ini sudah 
     * diisi semua
     *
     * @param  mixed $id_assessment
     * @return void
     */
    public function sendEmail_allComplete($id_assessment)
    {
        // ambil detail assessment buat ngambil id_summary
        $detail_assessment = $this->_general_m->getOnce('*', $this->table['form'], ['id' => $id_assessment]);

        // ambil semua form pada summary tersebut
        $summaryForms = $this->_general_m->getAll('*', $this->table['form'], ['id_summary' => $detail_assessment['id_summary']]);
        $detail_divisi = $this->divisi_model->getOnceWhere(array('id' => substr($detail_assessment['id_summary'], 6, 6))); // ambil informasi divisi

        //cek satu per satu apa sudah memenuhi kondisi sudah selesai semua formnya?, beri tanda pada flag
        $count_summaryForms = count($summaryForms); $count_complete = 0;
        foreach($summaryForms as $v){
            if($v['status_now_id'] == '4' || $v['status_now_id'] == '3'){
                $count_complete++;
            }
        }

        if($count_summaryForms == $count_complete){
            if($summaryForms[0]['status_now_id'] == "4"){ // kirim email ke OD Department atau adminsapp
                $admins_nik = $this->_general_m->getAll('nik', $this->table['admin'], array('id_menu' => $this->id_menu));
                $admins = array();
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

                // update summary ke OD
                $this->updateSummaryToOD($this->input->post("id")); // update summary untuk ke OD
            } else { // kirim email ke division head
                // data posisi
                $nik = substr($id_assessment, 0, 8);
                $position = $this->employee_m->getDetails_employee($nik);

                $email_data = $this->divisi_model->get_divHead($position['div_id']);
                // email data
                $email_penerima = $email_data['email'];
                $email_cc = "";
                $penerima_nama = $email_data['emp_name'];
            }

            if(!empty($email_penerima)){
                // ambil status detail
                $status_text = 'All Assessment Completed';
                $email_subject = "[".$this->app_name."] ".$status_text;
                $message = 'Semua assessment pada Penilaian Kontrak di Divisi anda sudah terisi, dimohon untuk melakukan approval melalui link yang tertera.';
                $direct_to = "pmk/index?direct=sumhis";
                $message_details = array(
                    0 => array(
                        'info_name' => 'Divisi',
                        'info' => $detail_divisi['division']
                    ),
                    1 => array(
                        'info_name' => 'Date Modified',
                        'info' => date('j F Y H:i')
                    )
                );

                $token_data = array( // data buat disave di token url
                    'direct'            => $direct_to,
                    'email_penerima'    => $email_penerima
                );
                
                $this->email_m->general_sendNotifikasi($email_penerima, $email_cc, $penerima_nama, $email_subject, $status_text, $message, $message_details, $token_data);
            }
        }
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
        $data['is_admin'] = $this->userApp_admin; // flag admin
        $data['summary_notes'] = json_decode($data['summary']['notes'], true); // decode summary notes
        unset($data['summary']['notes']); // hapus data summary notes

        // ambil data my position
        $position_my = $this->posisi_m->getMyPosition();
        $data['position_my'] =  $position_my;
        $data['status_before'] = $this->pmk_m->getStatusBefore($data_summary['summary']['status_now_id']); // ambil status sebelumnya
        // cari whoami
        if($position_my['id'] == 1){
            $data['whoami'] = 1;
        } elseif($position_my['id'] == 196){
            $data['whoami'] = 196;
        } elseif(($this->userApp_admin == 1 && $position_my['div_id'] == 6) || $this->session->userdata('role_id') == 1){
            $data['whoami'] = 196;
        } elseif($position_my['hirarki_org'] == "N"){
            $data['whoami'] = "N";
        } else {
            show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
        }
        
        // ambil status now summary
        $status_now = $this->pmk_m->getDetail_statusNowSummary($data['id_summary']);
        // cek akses buat ngubah summary action, ngisi notes dan submit summary
        if(((($this->userApp_admin == 1 && $position_my['div_id'] == 6) || $this->session->userdata('role_id') == 1) && $status_now['id'] == "pmksum-02") || 
           (($position_my['hirarki_org'] == "N" && $position_my['id'] != 196 && $position_my['id'] != 1) && $status_now['id'] == "pmksum-01") ||
           ($position_my['id'] == 196 && $status_now['id'] == "pmksum-03") ||
           ($position_my['id'] == 1 && $status_now['id'] == "pmksum-04")){
            $data['is_akses'] = 1; // beri akses
        } else {
            $data['is_akses'] = 0; // jangan beri akses
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
        $date = date('Y-m-t', strtotime("+2 month", time()));
        $date_now = date('Y-m-d', time());
        // ambil data contract terakhir
        $data_contract = $this->pmk_m->getAll_LastContract();
        // cari yg datenya udh beberapa bulan lagi
        $data_pmk = []; $x = 0; $counter_pmk = 0; $counter_new = 0;
        foreach($data_contract as $k => $v){
            // cek apa data sudah ada di pmk_form
            $vya = $this->pmk_m->getRow_form($v['nik'], $v['contract']);
            // cek apa kontraknya mau habis dalam 2 bulan
            $result = $this->_general_m->getOnce(
                '*', 
                $this->table['contract'], 
                "nik = '".$v['nik'].
                    "' AND contract = '".$v['contract'].
                    "' AND date_end >= '".$date_now.
                    "' AND date_end <= '".$date."'");
            // cek apa ada pada 2 bulan ke depan dengan kontrak terakhir
            if(!empty($result)){
                $counter_pmk++; // counter data yg abis di 2 bulan ke depan
                if($vya == 0){ // cek apa tidak ada datanya di kontrak terakhir
                    /**
                     * Mengecek apa dia punya approver
                     * - cek apa ada approver 1
                     * - kalo gaada approver 1 cari approver 2 nya
                     * - kalo 2 2nya gaada lariin aja ke divhead
                     */
                    // cek apa dia punya approver 
                    $approver1_nik = $this->employee_m->getApprover_nik($v['nik']); // ambil nik approver 1nya dia
                    // cek apa approver 1 niknya divhead
                    if($this->employee_m->is_divhead($approver1_nik) > 0){ // klo dia divhead
                        $approver_nik = ""; // kosongkan approver nik
                    } else { // kalo bukan divhead
                        if(empty($approver1_nik)){
                            $approver2_nik = $this->employee_m->getApprover_nik($v['nik'], 2); // ambil nik approver 2nya dia
                            if($this->employee_m->is_divhead($approver2_nik) > 0){ // klo dia divhead
                                $approver_nik = "";
                            } else {
                                $approver_nik = $approver2_nik;
                            }
                        } else {
                            $approver_nik = $approver1_nik; // ambil nik approver 1nya dia
                        }
                    }
                    
                    $emp_data = $this->employee_m->getDetails_employee($v['nik']);
                    $counter_new++; // counter new data
                    // prepare data
                    $data_pmk[$x]['id'] = $this->pmk_m->getId_form($result['nik'], $result['contract']);
                    if(!empty($approver_nik)){ // cek approvernya apa ada
                        // yang langsung kirim ke division head ada Functional dan N-1
                        if($emp_data['hirarki_org'] == "Functional-div" || $emp_data['hirarki_org'] == "Functional-adm" ||$emp_data['hirarki_org'] == "N-1"){
                            $data_pmk[$x]['status'] = json_encode([
                                0 => [
                                    'id_status' => 9,
                                    'by' => 'system',
                                    'nik' => '',
                                    'time' => time(),
                                    'text' => 'Form Generated and the assessment for this employee adressed to Division Head.'
                                ]
                            ]);
                            $data_pmk[$x]['status_now_id'] = 9;
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
                    } else { // kalo approver 1 dan 2 nya gaada kirim ke divhead
                        $data_pmk[$x]['status'] = json_encode([
                            0 => [
                                'id_status' => 9,
                                'by' => 'system',
                                'nik' => '',
                                'time' => time(),
                                'text' => 'Form Generated and the assessment for this employee adressed to Division Head.'
                            ]
                        ]);
                        $data_pmk[$x]['status_now_id'] = 9;
                    }
                    $data_pmk[$x]['created'] = time();
                    $data_pmk[$x]['modified'] = time();

                    $data_employee = $this->employee_m->getDetails_employee($v['nik']); // ambil detail data employee
                    $data_pmk[$x]['id_summary'] = date("Ym", strtotime($result['date_end'])).$data_employee['div_id']; // pmk_id nanti setelah hc divhead melakukan pembuatan summary
                    $this->cekPmkSummary($data_pmk[$x]['id_summary'], $result['date_end'], $data_employee['div_id']); // lakukan pemeriksaan summary
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
                
                $this->email_m->general_sendEmail($email, $email_cc, $penerima_nama, $subject_email, $status, $details, $msg, $link, TRUE);
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
            $status_data[$k]['time'] = date("j M Y<~>H:i", $v['time']);
            $status_data[$k]['name_text'] = $el['name_text'];
            $status_data[$k]['css_color'] = $el['css_color'];
            $status_data[$k]['icon'] = $el['icon'];
        }

        echo(json_encode($status_data));
    }

    /**
     * get assessment per employee timeline
     *
     * @return void
     */
    function ajax_getTimeline_summary(){
        $id = $this->input->post('id');

        $this->cekAkses_summary($this->posisi_m->getMyPosition());

        $temp_status_data = $this->pmk_m->getDetail_pmkStatus_summary($id); // ambil data jawaban survey
        $status_data = array_reverse($temp_status_data); // balikkan array biar yang terbaru di atas duluan

        foreach($status_data as $k => $v){
            $el = $this->pmk_m->getDetail_pmkStatusDetailByStatusId_summary($v['id_status']);
            // get status data attribute
            $status_data[$k]['time'] = date("j M Y<~>H:i", $v['time']);
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
            if(($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1) && $position_my['id'] != 196){ // apakah dia admin?
                $where .= "status_now_id='pmksum-02'";
            } elseif($position_my['id'] == 196){ // apakah dia hc divhead?
                // $status = "pmksum-02";
                $where .= "status_now_id='pmksum-03'";
            } elseif($position_my['id'] == 1){ // apakah dia CEO?
                // $status = "pmksum-03";
                $where .= "status_now_id='pmksum-04'";
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
        $data_summary['bulan'] = date('F (m)', strtotime('1-'. $data_summary['bulan'].'-2019'));
        $data_summary['tahun'] = date('Y', strtotime('1-1-'.$data_summary['tahun']));
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
        if($position_my['id'] == 196 || $position_my['id'] == 1){
            if($position_my['div_id'] == $position['div_id']){
                // perbolehkan akses
                $value = 1;
            } else {
                // perbolehkan akses tapi jangan kasih dia buat submit form
                $value = 3;
            }
        } elseif($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1){
            // perbolehkan akses bebas
            $value = 3; // flag bisa akses tapi ga berhak submit
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
    function cekPmkSummary($id_summary, $date_end, $id_div){
        if($this->_general_m->getRow($this->table['summary'], array('id_summary' => $id_summary)) < 1){
            // buat data status summary pmk
            $data['id_summary'] = $id_summary;
            $data['bulan']  = date("m", strtotime($date_end));
            $data['tahun']  = date("Y", strtotime($date_end));
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
            $data['deadline'] = strtotime($date_end);
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
            if($pmk_data['status_now_id'] == 1){ // status draft
                $status_now_id = "1";
                $status_new[array_key_last($status_new)+1] = array(
                    'id_status' => "1",
                    'by' => $penilai['emp_name'],
                    'nik' => $penilai['nik'],
                    'time' => time(),
                    'text' => 'Assessment form was changed.'
                );
            } elseif($pmk_data['status_now_id'] == 2){ // status submitted
                $status_now_id = "2";
                $status_new[array_key_last($status_new)+1] = array(
                    'id_status' => "2",
                    'by' => $penilai['emp_name'],
                    'nik' => $penilai['nik'],
                    'time' => time(),
                    'text' => 'Assessment form was changed.'
                );
            } elseif($pmk_data['status_now_id'] == 9){ // status Draft for Divhead
                $status_now_id = "9";
                $status_new[array_key_last($status_new)+1] = array(
                    'id_status' => "9",
                    'by' => $penilai['emp_name'],
                    'nik' => $penilai['nik'],
                    'time' => time(),
                    'text' => 'Assessment form was changed.'
                );
            } else {
                show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
            }
        } else { // jika actionnya submit
            // load email model
            $this->load->model('email_m');
            if($penilai['hirarki_org'] == "N-2"){
                // cek jika atasannya (N-1) ada atau engga
                if(empty($this->posisi_m->whoIsOnThisPosition($penilai['id_approver1']))){ // kalo N-1nya kosong isi dengan status id 9, kirim ke divhead sebagai draft
                    $status_now_id = "9";
                    $status_new[array_key_last($status_new)+1] = array(
                        'id_status' => "9",
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
                if($penilai['div_id'] == 1){ // cek apa penilainya dari CEO Office Division
                    $status_now_id = "4";
                    $status_new[array_key_last($status_new)+1] = array(
                        'id_status' => "4",
                        'by' => $penilai['emp_name'],
                        'nik' => $penilai['nik'],
                        'time' => time(),
                        'text' => 'Assessment form was submitted by N-1.'
                    );
                    // jadi gua letakin summary updater to OD ke $this->sendEmail_allComplete()
                } else { // jika bukan CEO Office
                    if($penilai['div_id'] == 6){
                        $status_now_id = "4";
                        if($penilai['hirarki_org'] == "N-1"){
                            $status_now_id = "";
                            $status_new[array_key_last($status_new)+1] = array(
                                'id_status' => "4",
                                'by' => $penilai['emp_name'],
                                'nik' => $penilai['nik'],
                                'time' => time(),
                                'text' => 'Assessment form was submitted by N-1.'
                            );
                        } elseif($penilai['hirarki_org'] == "N"){
                            $status_new[array_key_last($status_new)+1] = array(
                                'id_status' => "4",
                                'by' => $penilai['emp_name'],
                                'nik' => $penilai['nik'],
                                'time' => time(),
                                'text' => 'Assessment form was submitted by N.'
                            );
                        } else {
                            show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                        }
                        // jadi gua letakin summary updater to OD ke $this->sendEmail_allComplete()
                    } else {
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
            }
        }

        // masukkan assessment ke database
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

        /* -------------------------------------------------------------------------- */
        /*                              kirim notifikasi                              */
        /* -------------------------------------------------------------------------- */
        // jika actionnya submit
        if($this->input->post('action') == 1){
            // siapkan data untuk ngirim email notifikasi
            if($status_now_id == "2"){ // kirim email ke atasan 1nya dia
                $email_data = $this->posisi_m->whoIsOnThisPosition($penilai['id_approver1']);                
                // email data
                // jika atasan 1 nya lebih dari satu
                if(count($email_data) > 1){
                    $email_penerima = array(); $temp_namaPenerima = array();
                    foreach($email_data as $k => $v){
                        $email_penerima[$k] = $v['email'];
                        $temp_namaPenerima[$k] = $v['emp_name'];
                    }
                    $penerima_nama = implode(', ', $temp_namaPenerima);
                } else { // jika atasan 1 nya cuma satu
                    $email_penerima = $email_data[0]['email'];
                    $penerima_nama = $email_data[0]['emp_name'];
                }
                $email_cc = "";
            } elseif($status_now_id == "3" || $status_now_id == "9"){ // kirim email ke division head
                $email_data = $this->divisi_model->get_divHead($position['div_id']);
                // email data
                $email_penerima = $email_data['email'];
                $email_cc = "";
                $penerima_nama = $email_data['emp_name'];
            } elseif($status_now_id == "4"){ // kirim email ke OD Department atau adminsapp
                $admins_nik = $this->_general_m->getAll('nik', $this->table['admin'], array('id_menu' => $this->id_menu));
                $admins = array();
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
            }

            // ambil status detail
            $status_text = $this->pmk_m->getDetail_pmkStatusDetailByStatusId($status_now_id)['name_text'];
            $email_subject = "[".$this->app_name."] ".$status_text;
            $message = $status_new[array_key_last($status_new)]['text'];
            $data_employee = $this->employee_m->getDetails_employee($nik);
            if($status_now_id == "3" || $status_now_id == "4"){
                $direct_to = "pmk/index?direct=sumhis";
            } else {
                $direct_to = "pmk";
            }

            $token_data = array( // data buat disave di token url
                'direct'            => $direct_to,
                'email_penerima'    => $email_penerima
            );

            // kirim email permberitahuan kalo ada emailnya
            if(!empty($email_penerima)){
                $this->email_m->general_sendNotifikasi_employeeForm($email_penerima, $email_cc, $penerima_nama, $email_subject, $status_text, $message, $data_employee, $token_data);
            }

            // jika status now idnya 9, 4 atau 3, yaitu submitted dari N-2 untuk OD atau division head
            if($status_now_id == '4' || $status_now_id == '3'){
                $this->sendEmail_allComplete($this->input->post("id")); // kirim email bahwa summary ini sudah selesai semua assessmentnya
            }
        }

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
        $action = $this->input->post('action');
        $revise_to = $this->input->post('revise_to');
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
        
        // cek jika actionnya submit atau revise
        if($action == 0){ // jika actionnya revise
            // atur status now
            if($whoami['position_id'] == 196){
                $summary_status_now_id = $revise_to;
                $summary_status_text = "Summary form was Revised by HC Divhead.";
                $summary_notes[$whoami['position_id']]['text'] = $notes;
                $summary_notes[$whoami['position_id']]['by'] = $whoami['position_name'];
                $summary_notes[$whoami['position_id']]['time'] = time();
            } elseif($whoami['position_id'] == 1){
                $summary_status_now_id = $revise_to;
                $summary_status_text = "Summary form was Revised by CEO.";
                $summary_notes[$whoami['position_id']]['text'] = $notes;
                $summary_notes[$whoami['position_id']]['by'] = $whoami['position_name'];
                $summary_notes[$whoami['position_id']]['time'] = time();
            } elseif(($this->userApp_admin == 1 && $whoami['div_id'] == 6) || $this->session->userdata('role_id') == 1){
                // ambil datanya si 196
                $whois196 = $this->posisi_m->getOnceWhere(array('id' => 196)); // ambil keterangan data 196
                $summary_status_now_id = $revise_to;
                $summary_status_text = "Summary form was Revised by OD Department Head.";
                $summary_notes[$whois196['id']]['text'] = $notes;
                $summary_notes[$whois196['id']]['by'] = $whois196['position_name'];
                $summary_notes[$whois196['id']]['time'] = time();
            } else {
                show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
            }
        } else { // jika actionnya approve
            // atur status now
            if($whoami['position_id'] == 196){
                $summary_status_now_id = "pmksum-04";
                $summary_status_text = "Summary form was submitted by HC Divhead.";
                $summary_notes[$whoami['position_id']]['text'] = $notes;
                $summary_notes[$whoami['position_id']]['by'] = $whoami['position_name'];
                $summary_notes[$whoami['position_id']]['time'] = time();
            } elseif($whoami['position_id'] == 1){
                $summary_status_now_id = "pmksum-05";
                $summary_status_text = "Contract Evaluation has been Completed.";
                $summary_notes[$whoami['position_id']]['text'] = $notes;
                $summary_notes[$whoami['position_id']]['by'] = $whoami['position_name'];
                $summary_notes[$whoami['position_id']]['time'] = time();
            } elseif(($this->userApp_admin == 1 && $whoami['div_id'] == 6) || $this->session->userdata('role_id') == 1){
                // ambil datanya si 196
                $whois196 = $this->posisi_m->getOnceWhere(array('id' => 196)); // ambil keterangan data 196
                $summary_status_now_id = "pmksum-03";
                $summary_status_text = "Summary form was submitted by OD Department Head.";
                $summary_notes[$whois196['id']]['text'] = $notes;
                $summary_notes[$whois196['id']]['by'] = $whois196['position_name'];
                $summary_notes[$whois196['id']]['time'] = time();
            } elseif($whoami['hirarki_org'] == "N"){
                $summary_status_now_id = "pmksum-02";
                $summary_status_text = "Summary form was submitted by Division Head.";
                $summary_notes[$whoami['hirarki_org']]['text'] = $notes;
                $summary_notes[$whoami['hirarki_org']]['by'] = $whoami['position_name'];
                $summary_notes[$whoami['hirarki_org']]['time'] = time();
            } else {
                show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
            }
        }

        // ambil data summary
        $data_summary = $this->pmk_m->getDetail_summary($id_summary);

        // kirim email notifikasi berdasarkan action dan status
        if($action == 0){ // jika actionnya revise
            // cek per status
            if($summary_status_now_id == "pmksum-02"){
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
            } elseif($summary_status_now_id == "pmksum-03"){
                // kirim email ke HC Divhead
                $email_data = $this->divisi_model->get_divHead(6);
                // email data
                $email_penerima = $email_data['email'];
                $email_cc = "";
                $penerima_nama = $email_data['emp_name'];
            } elseif($summary_status_now_id == "pmksum-01"){
                // kirim email ke divhead
                $email_data = $this->divisi_model->get_divHead($data_summary['id_div']);
                // email data
                $email_penerima = $email_data['email'];
                $email_cc = "";
                $penerima_nama = $email_data['emp_name'];
            } else {
                show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
            }
        } else { // jika actionnya approve
            // cek per status
            if($summary_status_now_id == "pmksum-02"){
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
            } elseif($summary_status_now_id == "pmksum-03"){
                // kirim email ke HC Divhead dan semua aktor summary
                $ed_hc = $this->divisi_model->get_divHead(6);
                $ed_ceo = $this->divisi_model->get_divHead(1);
                $ed_dh = $this->divisi_model->get_divHead($data_summary['id_div']);
                // email data
                $email_penerima = $ed_hc['email'];
                $penerima_nama = $ed_hc['emp_name'];
                $email_cc = [$ed_ceo['email'], $ed_dh['email']];

                // ambil email admins
                $admins_nik = $this->_general_m->getAll('nik', $this->table['admin'], array('id_menu' => $this->id_menu));
                foreach($admins_nik as $k => $v){ // ambil data admins 1 per 1
                    $admins[$k] = $this->employee_m->getDetails_employee($v['nik']);
                }
                $x = array_key_last($email_cc) + 1;
                foreach($admins as $k => $v){
                    $email_cc[$x] = $v['email'];
                    $x++;
                }
            } elseif($summary_status_now_id == "pmksum-04"){
                // kirim email ke CEO dan semua aktor summary
                $ed_hc = $this->divisi_model->get_divHead(6);
                $ed_ceo = $this->divisi_model->get_divHead(1);
                $ed_dh = $this->divisi_model->get_divHead($data_summary['id_div']);
                // email data
                $email_penerima = $ed_ceo['email'];
                $penerima_nama = $ed_ceo['emp_name'];
                $email_cc = [$ed_hc['email'], $ed_dh['email']];
                // ambil email admins
                $admins_nik = $this->_general_m->getAll('nik', $this->table['admin'], array('id_menu' => $this->id_menu));
                foreach($admins_nik as $k => $v){ // ambil data admins 1 per 1
                    $admins[$k] = $this->employee_m->getDetails_employee($v['nik']);
                }
                $x = array_key_last($email_cc) + 1;
                foreach($admins as $k => $v){
                    $email_cc[$x] = $v['email'];
                    $x++;
                }
            } elseif($summary_status_now_id == "pmksum-05"){
                // kirim email ke semua kecuali CEO
                $ed_hc = $this->divisi_model->get_divHead(6);
                $ed_ceo = $this->divisi_model->get_divHead(1);
                $ed_dh = $this->divisi_model->get_divHead($data_summary['id_div']);
                // email data
                $email_penerima = [$ed_hc['email'], $ed_dh['email']];
                $penerima_nama = "yang Terhormat.";
                $email_cc = [$ed_ceo['email']];
                // ambil email admins
                $admins_nik = $this->_general_m->getAll('nik', $this->table['admin'], array('id_menu' => $this->id_menu));
                foreach($admins_nik as $k => $v){ // ambil data admins 1 per 1
                    $admins[$k] = $this->employee_m->getDetails_employee($v['nik']);
                }
                $x = array_key_last($email_penerima) + 1;
                foreach($admins as $k => $v){
                    $email_penerima[$x] = $v['email'];
                    $x++;
                }
            } else {
                show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
            }
        }

        $detail_divisi = $this->divisi_model->getOnceWhere(array('id' => $data_summary['id_div'])); // ambil informasi divisi

        // email data
        $status_detailText = $this->pmk_m->getDetail_pmkStatusDetailByStatusId_summary($summary_status_now_id)['name_text'];
        $status_text = "Status : ".$status_detailText;
        $email_subject = "[".$this->app_name.'] '.$status_detailText;
        $message = $summary_status_text;
        $message_details = array(
            0 => array(
                'info_name' => 'Divisi',
                'info' => $detail_divisi['division']
            ),
            1 => array(
                'info_name' => 'Date Modified',
                'info' => date('j F Y H:i')
            )
        );
        $token_data = array( // data buat disave di token
            'direct'         => 'pmk/index?direct=sumhis',
            'email_penerima' => $email_penerima
        );

        // jika statusnya buat HC Divhead, tambahkan notes ke email notifikasi message details
        if($summary_status_now_id == "pmksum-03"){
            $x = array_key_last($message_details) + 1;
            foreach($summary_notes as $v){
                if($v['text'] != ""){
                    $message_details[$x] = array(
                        'info_name' => "Message from ".$v['whoami'],
                        'info' => $v['text']
                    );
                    $x++;
                }
            }
        }

        // kirim email permberitahuan kalo ada emailnya
        if(!empty($email_penerima) && $summary_status_now_id != "pmksum-05"){
            $this->email_m->general_sendNotifikasi($email_penerima, $email_cc, $penerima_nama, $email_subject, $status_text, $message, $message_details, $token_data);
        } else {
            $this->email_m->general_sendNotifikasi($email_penerima, $email_cc, $penerima_nama, $email_subject, $status_text, $message, $message_details, $token_data, false);
        }
        
        // update status summary
        $summary_status_new[array_key_last($summary_status_new)+1] = array(
            'id_status' => $summary_status_now_id,
            'by' => $whoami['emp_name'],
            'nik' => $whoami['nik'],
            'time' => time(),
            'text' => $notes."<br/><br/>".$summary_status_text
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
            if($action == 0){ // jika actionnya revise
                // cek kode revise to dan pasangkan statusnya dengan pmk form status
                if($revise_to == "pmksum-01"){
                    $status_now_id = 3;
                } elseif($revise_to == "pmksum-02"){
                    $status_now_id = 4;
                } elseif($revise_to == "pmksum-03"){
                    $status_now_id = 5;
                } elseif($revise_to == "pmksum-04"){
                    $status_now_id = 6;
                } else {
                    show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                }
            } else { // jika actionnya approve
                if($whoami['position_id'] == 196){
                    $status_now_id = 6;
                } elseif($whoami['position_id'] == 1){
                    $status_now_id = 8;
                } elseif(($this->userApp_admin == 1 && $whoami['div_id'] == 6) || $this->session->userdata('role_id') == 1){
                    $status_now_id = 5;
                } elseif($whoami['hirarki_org'] == "N"){
                    $status_now_id = 4;
                } else {
                    show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                }
            }
            $status_text = $notes."<br/><br/>".$summary_status_text; // status text buat di timeline

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

    public function updateSummaryToOD($id_assessment)
    {
        // ambil detail assessment buat ngambil id_summary
        $detail_assessment = $this->_general_m->getOnce('*', $this->table['form'], ['id' => $id_assessment]);
        $id_summary = $detail_assessment['id_summary'];

        // ambil data pribadi
        $whoami = $this->employee_m->getDetails_employee($this->session->userdata('nik'));
        // result for summary
        $result_summary = $this->_general_m->getOnce('status, notes', $this->table['summary'], array('id_summary' => $id_summary));
        // ambil status dari summary
        $summary_status_new = json_decode($result_summary['status'], true);

        // persiapkan data summary status dan notes
        $summary_status_now_id = "pmksum-02";
        $summary_status_text = "Summary form was submitted by Division Head.";

        // update status summary
        $summary_status_new[array_key_last($summary_status_new)+1] = array(
            'id_status' => $summary_status_now_id,
            'by' => $whoami['emp_name'],
            'nik' => $whoami['nik'],
            'time' => time(),
            'text' => $notes."<br/><br/>".$summary_status_text
        );
        // prepare updated summary data
        $update_pmkSummary = array(
            'status' => json_encode($summary_status_new),
            'status_now_id' => $summary_status_now_id,
            'modified' => time()
        );
        // update pmk data form
        $this->pmk_m->updateForm_summary($update_pmkSummary, array('id_summary' => $id_summary));
    }

    function test(){
        $date = strtotime(date('t-m-Y', strtotime("+2 month", time())));
        
        
        print_r($date);
        echo("<br/>".date('d-m-Y', $date));
    }

}

/* End of file Pmk.php */
