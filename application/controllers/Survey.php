<?php  
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey extends MainController {
    protected $title_excellence = 'Service Excellence';
    protected $title_engagement = 'Employee Engagement';
    protected $title_f360 = '360° Feedback';

    protected $table = array(
        'title' => 'survey_setting',
        'position' => 'master_position',
        'department' => 'master_department',
    );
    
    public function __construct(){
        // show_error($message, $status_code, $heading = 'An Error Was Encountered')
        // echo($a);
        // show_error('error dah', 404, 'ada errrrororororororo');
        // exit;
        parent::__construct();
        
        $this->load->model(['posisi_m', 'employee_m']);
        // ambil nama table yang terupdate
        $this->load->library('tablename');
        $this->table['position'] = $this->tablename->get($this->table['position']);
    }

    public function index(){
        $position_my = $this->posisi_m->getMyPosition(); // ambil my position detail

        // tambah pengecualian buat GA Driver untuk survey engagement
        if($this->checkIsActive(0) == 0){
            $data['survey_status']['exc'] = 'closed';
        } elseif($this->session->userdata('position_id') == 184){
            $data['survey_status']['exc'] = 'closed';
        } elseif($this->_general_m->getRow('survey_exc_hasil', array('nik' => $this->session->userdata('nik'))) < 1){ // cek apa survey excellence sudah diisi
            //nothing
        } else {
            $data['survey_status']['exc'] = 'closed';
        }

        if($this->checkIsActive(1) == 0){
            $data['survey_status']['eng'] = 'closed';
        } elseif($position_my['hirarki_org'] == "N"){
            $data['survey_status']['eng'] = 'closed';
        } elseif($this->_general_m->getRow('survey_eng_hasil', array('nik' => $this->session->userdata('nik'))) < 1 ){ // cek apa survey engagement sudah diisi
            // nothing
        } else {
            $data['survey_status']['eng'] = 'closed';
        }

        $data_employe = $this->_general_m->getJoin2tables(
            'master_employee.nik, '. $this->table['position'] .'.hirarki_org, '. $this->table['position'] .'.dept_id, '. $this->table['position'] .'.div_id, '. $this->table['position'] .'.id_atasan1', 
            'master_employee', 
            $this->table['position'], 
            $this->table['position'] .'.id = master_employee.position_id', 
            array('nik' => $this->session->userdata('nik'))
        )[0];
        // cek apa survey 360 sudah diisi atau dia tidak memiliki akses
        if($this->checkIsActive(2) == 0){
            $data['survey_status']['f360'] = 'closed';
        } elseif($data_employe['hirarki_org'] == 'N-1' || $data_employe['hirarki_org'] == 'N-2' || $data_employe['hirarki_org'] == 'N-3' || $data_employe['hirarki_org'] == 'Functional-div' || $data_employe['hirarki_org'] == 'Functional-dep') { // cek hirarki apa dia N-1, N-2, atau N-3
            $data_survey = $this->f360getData($data_employe); // ambil data survey
            $data_survey_complete_f360 = $this->f360counterStatusOF($data_survey); // ambil data counter survey OF
            // cek jika antara counter survey dan counter complete sama
            if($data_survey_complete_f360['counter_survey_f360'] == $data_survey_complete_f360['counter_complete_f360']){
                $data['survey_status']['f360'] = "closed";
            }
        } else { // jika dia bukan dari N-1, N-2, atau N-3
            $data['survey_status']['f360'] = "closed";
        }

        // cek ketiga status survey
        if(!empty($data['survey_status']['exc']) && !empty($data['survey_status']['eng']) && !empty($data['survey_status']['f360'])){
            // set kartu komplit dan notification toastr
            $data['survey_complete'] = 'complete';
            $this->session->set_flashdata('all_survey', 'toastr["info"]("Thank You for completing the survey.", "Survey Complete");');
        }

        // survey title
        $data['survey_title'] = array(
            'excellence' => $this->title_excellence,
            'engagement' => $this->title_engagement,
            'f360' => $this->title_f360
        );

        $data_title = $this->_general_m->getAll('*', $this->table['title'], array());
        $data['survey_title'] = array(
            'excellence' => $this->title_excellence.'<br/>[Periode'.explode('[Periode', $data_title[0]['judul'])[1],
            'engagement' => $this->title_engagement.'<br/>[Periode'.explode('[Periode', $data_title[1]['judul'])[1],
            'f360' => $this->title_f360.'<br/>[Periode'.explode('[Periode', $data_title[2]['judul'])[1]
        );

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu', array('url' => $this->uri->uri_string()))['title'];
        $data['load_view'] = 'survey/survey_v';
        $data['custom_script'] = array('survey/script_survey');
        
        $this->load->view('main_v', $data);
    }

    /* -------------------------------------------------------------------------- */
    /*                          Service Excellence Survey                         */
    /* -------------------------------------------------------------------------- */
    public function excellence(){ // main survey excellence function
        // tambah pengecualian buat GA Driver
        if($this->session->userdata('position_id') == 184){
            show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
        }
        // cek apa survey excellence aktif atau engga
        if($this->checkIsActive(0) == 0){
            // show_error('Sorry, but the survey period has ended, this survey not accept any respondent anymore.', 451, 'Survey Period Ended');
            show_404();
        }
        //cek apakah karyawan sudah mengisi Service Excellence Survey
        if($this->_general_m->getRow('survey_exc_hasil', array('nik' => $this->session->userdata('nik'))) < 1 ){
            //ambil departemen yang dinilai
            $departemen = $this->_general_m->getAll('*', 'survey_exc_departemen', array());

            //  ambil id departemen
            $my_departemen = $this->_general_m->getJoin2tables('nama_departemen, '.$this->table['department'].'.id', $this->table['position'], 'master_department', $this->table['position'] .'.dept_id = master_department.id', $this->table['position'] .'.id='.$this->session->userdata('position_id'))[0];

            // samain id departemen dan hapus yang sama
            // hapus departemen kalo dia itu berada di departemen itu
            $x = 0; // prepare variable
            foreach($departemen as $dept){
                if ($my_departemen['id'] != $dept['id']){
                    $data['departemen'][$x] = $dept;
                    $x++;
                }
            }
            
            // ambil data survey
            $data['survey1'] = $this->_general_m->getAll('*', 'survey_exc_pertanyaan', array('id_tipepertanyaan' => 'A'));
            $data['survey2'] = $this->_general_m->getAll('*', 'survey_exc_pertanyaan', 'id_tipepertanyaan = "B" AND id_departemen != '.$my_departemen['id']);

            // ambil informasi data departemen
            foreach($data['survey2'] as $k => $v){
                $data['survey2'][$k]['nama_departemen'] = $this->_general_m->getOnce('nama', 'survey_exc_departemen', array('id' => $v['id_departemen']))['nama'];
            }

            // main data
            $load_view = 'survey/exc_survey_v';
        } else { // kalo sudah selesai
            // buat notifikasi swal kalo karyawan sudah mengisi survey
            header('location: ' . base_url('survey')); // arahkan ke halaman survey index
        }
        
        // survey data
        $data['survey_title'] = $this->_general_m->getOnce('judul', $this->table['title'], array('id_survey' => 0))['judul'];

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->title_excellence;
        $data['load_view'] = $load_view;
        $data['custom_styles'] = array('survey_styles');
        $data['custom_script'] = array('survey/script_survey', 'survey/script_exc_survey');
        
        $this->load->view('main_v', $data);
    }
    public function excSubmit(){ // submit excellence function
         //ambil nik
        // print_r($this->session->userdata('login'));
        
        $employe_data =  $this->_general_m->getJoin2tables(
            'nik, emp_name, '. $this->table['position'] .'.id, '. $this->table['position'] .'.dept_id, '. $this->table['position'] .'.div_id',
            'master_employee',
            $this->table['position'],
            'master_employee.position_id = '. $this->table['position'] .'.id',
            array('nik' => $this->session->userdata('nik'))
        )[0];
        $employe_data['divisi'] = $this->_general_m->getOnce('division', 'master_division', array('id' => $employe_data['div_id']))['division'];
        $employe_data['departemen'] = $this->_general_m->getOnce('nama_departemen', 'master_department', array('id' => $employe_data['dept_id']))['nama_departemen'];
        // print_r(json_encode($employe_data));
        // exit;

        // $menu = $CI->_general_m->getJoin2tables('', 'user_menu', 'user_menu_access', 'user_menu.id_menu = user_menu_access.id_menu', array('id_user' => $CI->session->userdata('role_id')));

        //ambil id pertanyaan
        $data_pertanyaan = $this->_general_m->getAll('id, judul_pertanyaan, id_tipepertanyaan', 'survey_exc_pertanyaan', array());

        // masukkan semua data dalam 1 variabel
        $x=0; //siapkan pointer
        $jawaban_survey = array(); //siapkan array penampung
        foreach($data_pertanyaan as $value){
            foreach($this->input->post() as $k => $v){
                if(fnmatch($value['id']."*", $k)){
                    //explode key post jawaban user
                    $key_post = explode('_', $k);
                    //masukkan data
                    $jawaban_survey[$x]['nik'] = $employe_data['nik'];
                    $jawaban_survey[$x]['id_tipepertanyaan'] = $value['id_tipepertanyaan'];
                    $jawaban_survey[$x]['id_pertanyaan'] = $value['id'];
                    $jawaban_survey[$x]['judul_pertanyaan'] = $value['judul_pertanyaan'];
                    $jawaban_survey[$x]['id_departemen'] = $key_post[1];
                    $jawaban_survey[$x]['divisi'] = $employe_data['divisi'];
                    $jawaban_survey[$x]['departemen'] = $employe_data['departemen'];
                    $jawaban_survey[$x]['emp_name'] = $employe_data['emp_name'];
                    $jawaban_survey[$x]['jawaban'] = $v;
                    // tambah index
                    $x++;
                }
            }
        }

        //simpan dalam database
        $this->_general_m->insertAll('survey_exc_hasil', $jawaban_survey);

        header('location: ' . base_url('survey'));
    }

    /* -------------------------------------------------------------------------- */
    /*                              Engagement Survey                             */
    /* -------------------------------------------------------------------------- */
    public function engagement(){ // survey engagement main function
        $position_my = $this->posisi_m->getMyPosition();
        // tambah pengecualian buat hirarki N
        if($position_my['hirarki_org'] == "N"){
            show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
        }
        // cek apa survey excellence aktif atau engga
        if($this->checkIsActive(1) == 0){
            // show_error('Sorry, but the survey period has ended, this survey not accept any respondent anymore.', 451, 'Survey Period Ended');
            show_404();
        }
        // cek apa karyawan sudah isi survey
        if($this->_general_m->getRow('survey_eng_hasil', array('nik' => $this->session->userdata('nik'))) < 1){
            // siapkan pertanyaan data survey
            $data['survey_data'] = $this->_general_m->getAll('id, pertanyaan', 'survey_eng_pertanyaan', array());

            // load view
            $load_view = 'survey/eng_survey_v';
        } else { // kalo sudah selesai
            header('location: ' . base_url('survey')); // arahkan ke halaman survey index
        }
        // survey data
        $data['survey_title'] = $this->_general_m->getOnce('judul', $this->table['title'], array('id_survey' => 1))['judul'];

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        // $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu_sub', array('url' => $this->uri->uri_string()))['title'];;
        $data['page_title'] = $this->title_engagement;
        $data['load_view'] = $load_view;
        $data['custom_styles'] = array('survey_styles');
        $data['custom_script'] = array('survey/script_survey', 'survey/script_eng_survey');
        
        $this->load->view('main_v', $data);
    }
    public function engSubmit(){ // submit engagement function
        $employe_data =  $this->_general_m->getJoin2tables(
            'nik, emp_name, '. $this->table['position'] .'.id, '. $this->table['position'] .'.dept_id, '. $this->table['position'] .'.div_id',
            'master_employee',
            $this->table['position'],
            'master_employee.position_id = '. $this->table['position'] .'.id',
            array('nik' => $this->session->userdata('nik'))
        )[0];
        $employe_data['divisi'] = $this->_general_m->getOnce('division', 'master_division', array('id' => $employe_data['div_id']))['division'];
        $employe_data['departemen'] = $this->_general_m->getOnce('nama_departemen', 'master_department', array('id' => $employe_data['dept_id']))['nama_departemen'];
        // print_r(json_encode($employe_data));
        // exit;

        // $menu = $CI->_general_m->getJoin2tables('', 'user_menu', 'user_menu_access', 'user_menu.id_menu = user_menu_access.id_menu', array('id_user' => $CI->session->userdata('role_id')));

        //post semua pertanyaan
        // print_r(json_encode($this->input->post()));
        // exit;

        //ambil id pertanyaan
        $data_pertanyaan = $this->_general_m->getAll('id, judul_pertanyaan', 'survey_eng_pertanyaan', array());

        $x=0; //siapkan pointer
        $jawaban_survey = array(); //siapkan array penampung
        foreach($data_pertanyaan as $value){
            foreach($this->input->post() as $k => $v){
                if(fnmatch($value['id'], $k)){
                    //masukkan data
                    $jawaban_survey[$x]['nik'] = $employe_data['nik'];
                    $jawaban_survey[$x]['id_pertanyaan'] = $value['id'];
                    $jawaban_survey[$x]['judul_pertanyaan'] = $value['judul_pertanyaan'];
                    $jawaban_survey[$x]['divisi'] = $employe_data['divisi'];
                    $jawaban_survey[$x]['departemen'] = $employe_data['departemen'];
                    $jawaban_survey[$x]['emp_name'] = $employe_data['emp_name'];
                    $jawaban_survey[$x]['jawaban'] = $v;
                    // tambah index
                    $x++;
                }
            }
        }

        // masukkan data ke database
        $this->_general_m->insertAll('survey_eng_hasil', $jawaban_survey);

        $this->session->set_flashdata('one_survey', 'toastr["success"]("Thank You for completing '.$this->title_engagement.' Survey.", "'.$this->title_engagement.' Survey Complete");');
        header('location: ' . base_url('survey'));
    }

    /* -------------------------------------------------------------------------- */
    /*                              Feedback 360 Survey                           */
    /* -------------------------------------------------------------------------- */
    public function feedback360(){ // Feedback 360 main function
        // ambil nik
        // cari hirarki org
        // cek apa dia N-1, N-2, N-3
        // kalau bukan diantara ketiga itu tampilkan tampilan maaf

        // N-3 Menilai atasannya N-2
        // N-2 menilai teman sebaya N-2 dan N-1, N-2 di dept lain div sama max 3
        // N-1 menilai teman sebaya N-1, N-1 di div lain max 3

        $data_employe = $this->_general_m->getJoin2tables(
            'master_employee.nik, '. $this->table['position'] .'.hirarki_org, '. $this->table['position'] .'.dept_id, '. $this->table['position'] .'.div_id, '. $this->table['position'] .'.id_atasan1', 
            'master_employee', 
            $this->table['position'], 
            $this->table['position'] .'.id = master_employee.position_id', 
            array('nik' => $this->session->userdata('nik'))
        )[0];

        // cek hirarki apa dia N-1, N-2, N-3, atau functional div
        if($data_employe['hirarki_org'] == 'N-1' || $data_employe['hirarki_org'] == 'N-2' || $data_employe['hirarki_org'] == 'N-3' || $data_employe['hirarki_org'] == 'Functional-div' || $data_employe['hirarki_org'] == 'Functional-dep') {
            $data_survey = $this->f360getData($data_employe); // ambil data survey
        } else {
            header('location: ' . base_url('survey/f360limitedUser')); // arahkan ke pesan blocked
        }

        // cek apa survey excellence aktif atau engga
        if($this->checkIsActive(2) == 0){
            // show_error('Sorry, but the survey period has ended, this survey not accept any respondent anymore.', 451, 'Survey Period Ended');
            show_404();
        }

        //cek status pengisian survey
        $data_survey_complete_f360 = $this->f360counterStatusOF($data_survey); // ambil data counter survey OF
        // cek jika antara counter survey dan counter complete sama
        if($data_survey_complete_f360['counter_survey_f360'] == $data_survey_complete_f360['counter_complete_f360']){
            $this->session->set_flashdata('one_survey', 'toastr["success"]("You have completed the '.$this->title_f360.' Survey, Thank You.", "'.$this->title_f360.' complete");');
            header('location: ' . base_url('survey')); // arahkan ke halaman survey index
        }

        // counter buat max feedback other function
        if(!empty($data_survey['data_other_function'])){
            if(count($data_survey['data_other_function']) <= 5){
                $data['max_feedback_other_peers'] = count($data_survey['data_other_function']);
            } else {
                $data['max_feedback_other_peers'] = 5;
            }
        }

        if(!empty($data_complete_of)){
            $data['max_feedback_other_peers'] = $data['max_feedback_other_peers'] - count($data_complete_of);
        }
        
        // survey data
        if(!empty($data_survey['data_atasan'])){
            $data['data_atasan'] = $data_survey['data_atasan'];
        }
        if(!empty($data_survey['data_peers'])){
            $data['data_peers'] = $data_survey['data_peers'];
        }
        if(!empty($data_survey['data_other_function'])){
            $data['data_other_function'] = $data_survey['data_other_function'];
        }
        // data other function
        if(!empty($data_survey['data_complete_of'])){
            $data['data_complete_of'] = $data_survey['data_complete_of'];
        }
        if(!empty($data_survey['data_notyet_of'])){
            $data['data_notyet_of'] = $data_survey['data_notyet_of'];
        }
        if(!empty($data_survey['data_other_function_penilaian'])){
            $data['data_other_function_penilaian'] = $data_survey['data_other_function_penilaian'];
        }

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        // $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu_sub', array('url' => $this->uri->uri_string()))['title'];
        $data['page_title'] = $this->title_f360;
        $data['load_view'] = "survey/f360_index_survey_v";
        $data['custom_styles'] = array('survey_styles');
        $data['custom_script'] = array('survey/script_survey', 'survey/script_f360_index_survey');
        
        $this->load->view('main_v', $data);
    }

    public function f360survey(){ // Feedback survey
        // ambil nik penilai dan dinilai
        $nik_penilai = $this->session->userdata('nik');
        $nik_dinilai = $this->input->get('nik');
        $data_penilai = $this->f360getEmployeDetail(array('nik' => $nik_penilai))[0]; // ambil data penilai

        //cek otoritas penilaian karyawan
        $this->f360cekOtoritas($data_penilai, $nik_dinilai);

        // cek apa udh diisi survey karyawan ini
        if($this->_general_m->getRow('survey_f360_hasil', array('nik_penilai' => $nik_penilai, 'nik_dinilai' => $nik_dinilai)) > 1){
            show_error('The request has been accepted for processing, but the processing has not been completed. The request might or might not be eventually acted upon, 
            and may be disallowed when processing occurs.', 202, 'Accepted');
        }

        // ambil kategori pertanyaan
        $pertanyaan = $this->_general_m->getAll('*', 'survey_f360_kategoripertanyaan', array());
        //ambil pertanyaan
        foreach($pertanyaan as $key => $value){
            $pertanyaan[$key]['survey_pertanyaan'] = $this->_general_m->getAll('*', 'survey_f360_pertanyaan', array('id_kategori_pertanyaan' => $value['id_kategori_pertanyaan']));        
        }

        // survey data
        $data['survey_title'] = $this->_general_m->getOnce('judul', $this->table['title'], array('id_survey' => 2))['judul'];
        $data['nik_dinilai'] = $nik_dinilai;
        $data['pertanyaan'] = $pertanyaan;

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        // $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu_sub', array('url' => 'survey/feedback360'))['title'];
        $data['page_title'] = $this->title_f360;
        $data['load_view'] = "survey/f360_survey_v";
        $data['custom_styles'] = array('survey_styles');
        $data['custom_script'] = array('survey/script_survey', 'survey/script_f360_survey');
        
        $this->load->view('main_v', $data);
    }

    // tampilan buat karyawan yang bukan N-1, N-2, dan N-3
    public function f360limitedUser() {
        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->title_f360;
        $data['load_view'] = "survey/f360_blocked_survey_v";
        $data['custom_styles'] = array('survey_styles');
        $data['custom_script'] = array('survey/script_survey');
        
        $this->load->view('main_v', $data);
    }

    public function f360Submit(){
        // ambil nik penilai dan dinilai
        $nik_penilai = $this->session->userdata('nik');
        $nik_dinilai = $this->input->post('nik_dinilai');

        // ambil data penilai dan dinilai dan cek otoritasnya
        $data_penilai = $this->f360getEmployeDetail(array('nik' => $nik_penilai))[0]; // ambil data penilai
        $data_dinilai = $this->f360cekOtoritas($data_penilai, $nik_dinilai); // cel otoritas

        // print_r(json_encode($data_penilai[]));
        // echo"<br/>";
        // echo"<br/>";
        // print_r(json_encode($data_dinilai));
        // siapkan variable penampung dan index
        $jawaban_survey = array(); $x = 0;
        $pertanyaan = $this->_general_m->getAll('id, judul_pertanyaan', 'survey_f360_pertanyaan', array());
        foreach($pertanyaan as $k => $v){
            foreach($this->input->post() as $key => $value){
                if($v['id'] == $key){
                    $jawaban_survey[$k]['nik_penilai'] = $nik_penilai;
                    $jawaban_survey[$k]['divisi_penilai'] = $data_penilai['divisi'];
                    $jawaban_survey[$k]['departemen_penilai'] = $data_penilai['departemen'];
                    $jawaban_survey[$k]['emp_name_penilai'] = $data_penilai['emp_name'];
                    $jawaban_survey[$k]['nik_dinilai'] = $nik_dinilai;
                    $jawaban_survey[$k]['divisi_dinilai'] = $data_dinilai['divisi'];
                    $jawaban_survey[$k]['departemen_dinilai'] = $data_dinilai['departemen'];
                    $jawaban_survey[$k]['emp_name_dinilai'] = $data_dinilai['emp_name'];
                    $jawaban_survey[$k]['id_pertanyaan'] = $v['id'];
                    $jawaban_survey[$k]['judul_pertanyaan'] = $v['judul_pertanyaan'];
                    $jawaban_survey[$k]['jawaban'] = $value;
                }
            }
        }

        // masukkan data ke database
        $this->_general_m->insertAll('survey_f360_hasil', $jawaban_survey);

        $this->session->set_flashdata('one_survey', 'toastr["success"]("You have complete give feedback to '. $data_dinilai['emp_name'] .', Thank You.", "'.$this->title_f360.' complete");');
        header('location: ' . base_url('survey/feedback360'));
    }

    /* ----------------------------- f360 Other Functions ---------------------------- */
    public function f360counterStatusOF($data_survey){
        // print_r(json_encode($data_survey));
        // exit;
        // totalkan jumlah karyawan yang mau dinilai dan buat counter complete
        $counter_survey_f360 = 0; $counter_complete_f360 = 0; 
        if(!empty($data_survey['data_atasan'])){
            // jumlahkan survey yang mau dinilai
            $counter_survey_f360 = $counter_survey_f360 + count($data_survey['data_atasan']);
            // jumlahkan data survey yang komplit
            foreach($data_survey['data_atasan'] as $v){
                if($v['status'] == true){
                    $counter_complete_f360++;
                }
            }
        }
        if(!empty($data_survey['data_peers'])){
            // jumlahkan survey yang mau dinilai
            $counter_survey_f360 = $counter_survey_f360 + count($data_survey['data_peers']);
            // jumlahkan data survey yang komplit
            foreach($data_survey['data_peers'] as $v){
                if($v['status'] == true){
                    $counter_complete_f360++;
                }
            }
        }
        if(!empty($data_survey['data_other_function'])){
            // jumlahkan survey yang mau dinilai dengan jumlah survey dari yang other function
            $counter_survey_f360 = $counter_survey_f360 + 5;
        }
        // cek khusus buat $data_other_function
        if(!empty($data_survey['data_complete_of'])){
            $counter_complete_f360 = $counter_complete_f360 + count($data_survey['data_complete_of']);
        }
        $data = array(
            'counter_survey_f360' => $counter_survey_f360,
            'counter_complete_f360' => $counter_complete_f360
        );
        
        return $data;
    }
        // functional sama dengan peers = functional di divisinya sama N-1 di divisinya,
        // other peers function = N-2 di divisinya

        //  jabatan
        
        // functional-div
        // functional-dep
        // functional-adm

        // N-1 menilai functional di divisinya
        // N-1 di funtonal lain tidak muncul
    // get data Feedback 360°
    public function f360getData($data_employe) {
        // cek hirarki karyawan
        if($data_employe['hirarki_org'] == 'Functional-div'){
            //ambil data teman sebaya N-1 di divisi dan deptnya
            $data_peers = $this->f360getEmployeDetail(
                'hirarki_org = "N-1"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND nik != "'.$data_employe['nik'].'"'
            );
            // TODO bedakan Functional-div dan Functional-dept
            //ambil data teman sebaya Functional-div di divisi dan deptnya
            $data_peers = array_merge($data_peers, $this->f360getEmployeDetail(
                'hirarki_org = "Functional-div"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND nik != "'.$data_employe['nik'].'"'
            ));
            // ambil data N-2 di divisinya
            $data_other_function = $this->f360getEmployeDetail(
                'hirarki_org = "N-2"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND nik != "'.$data_employe['nik'].'"'
            );
        } elseif($data_employe['hirarki_org'] == 'Functional-dep'){
            // ambil data atasannya
            $data_atasan = $this->f360getEmployeDetail(array(
                $this->table['position'] .'.id' => $data_employe['id_atasan1']
            ));
        } elseif($data_employe['hirarki_org'] == 'N-1') {
            //ambil data teman sebaya di divisi dan deptnya
            $data_peers = $this->f360getEmployeDetail(
                'hirarki_org = "N-1"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND nik != "'.$data_employe['nik'].'"'
            );
            //ambil data teman sebaya Functional-div di divisi dan deptnya
            $data_peers = array_merge($data_peers, $this->f360getEmployeDetail(
                'hirarki_org = "Functional-div"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND nik != "'.$data_employe['nik'].'"'
            ));
            // ambil data di employe di divisi lain
            $data_other_function = $this->f360getEmployeDetail(
                'hirarki_org = "N-1"'.
                ' AND div_id != "'.$data_employe['div_id'].
                '" AND nik != "'.$data_employe['nik'].'"'
            );
        } elseif($data_employe['hirarki_org'] == 'N-2') {
            // ambil atasan di dept dan divisi yang sama N-1
            $data_atasan = $this->f360getEmployeDetail(array(
                $this->table['position'] .'.id' => $data_employe['id_atasan1']
            ));
            // ambil data teman sebaya di div, dept, dan hirarki yang sama
            $data_peers = $this->f360getEmployeDetail(
                'hirarki_org = "N-2"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND dept_id = "'.$data_employe['dept_id'].
                '" AND nik != "'.$data_employe['nik'].
                '" AND position_id != "'.$data_employe['id_atasan1'].'"'
            );
            // ambil data div sama, dept beda, hirarki sama
            $data_other_function = $this->f360getEmployeDetail(
                'hirarki_org = "N-2"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND dept_id != "'.$data_employe['dept_id'].
                '" AND nik != "'.$data_employe['nik'].'"'
            );
            // TODO bedakan Functional-div dan Functional-dept
            //ambil data teman sebaya Functional-div di divisi dan deptnya
            $data_other_function = array_merge($data_other_function, $this->f360getEmployeDetail(
                'hirarki_org = "Functional-div"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND nik != "'.$data_employe['nik'].'"'
            ));
        } elseif($data_employe['hirarki_org'] == 'N-3') {
            // ambil data atasannya
            $data_atasan = $this->f360getEmployeDetail(array(
                $this->table['position'] .'.id' => $data_employe['id_atasan1']
            ));
        } else { // jika posisinya bukan N-1, N-2, atau N-3
            // nothing
        }

        // cek status pengisian survey di masing2 variabel data
        if(!empty($data_atasan)){ // data atasan
            foreach($data_atasan as $k => $v){
                // cek status data atasan dengan melihat nik poenilai dan nik dinilai
                $data_atasan[$k]['status'] = $this->f360cekStatus($data_employe['nik'], $v['nik']);
            }
        }
        if(!empty($data_peers)){ // data peers
            foreach($data_peers as $k => $v){
                // cek status dengan melihat nik penilai dan nik dinilai
                $data_peers[$k]['status'] = $this->f360cekStatus($data_employe['nik'], $v['nik']);
            }
        }

        // ambil other function dari penilaian yang dipilihin
        $data_other_function_penilaian = array();
        if($data_employe['hirarki_org'] == "N-1"){
            $result_other_function_penilaian = $this->_general_m->getAll('*', 'survey_f360_penilaian', array('nik_penilai' => $this->session->userdata('nik')));
            // lengkapi data
            foreach($result_other_function_penilaian as $k => $v){
                $data_other_function_penilaian[$k] = $this->employee_m->getDetails_employee($v['nik_dinilai']);
            }
        }

        $data_complete_of = array(); $data_notyet_of = array(); $x=0; $y=0;
        if(!empty($data_other_function)){ // data other function
            foreach($data_other_function as $k => $v){
                if($this->f360cekStatus($data_employe['nik'], $v['nik']) == TRUE){
                    // jika niknya udh diisi, kosongkan dari variable penilaian, karena variable penilaian itu yang belum diisi
                    foreach($data_other_function_penilaian as $key => $value){
                        if($v['nik'] == $value['nik']){
                            unset($data_other_function_penilaian[$key]);
                        }
                    }
                    $data_complete_of[$x] = $v;
                    $x++;
                } else {
                    // cek pada data penilaian
                    $flag = 0; // siapkan flag
                    foreach($data_other_function_penilaian as $value){
                        if($v['nik'] == $value['nik']){
                            $flag = 1; // tandai flag
                        }
                    }
                    if($flag == 0){
                        $data_notyet_of[$y] = $v;
                        $y++;
                    }
                }
            }
        }

        //masukkan ke ada jika ada
        if(!empty($data_atasan)){
            $data['data_atasan'] = $data_atasan;
        }
        if(!empty($data_peers)) {
            $data['data_peers'] = $data_peers;
        }
        if(!empty($data_other_function)){
            $data['data_other_function'] = $data_other_function;
        }
        //data other function
        if(!empty($data_complete_of)){
            $data['data_complete_of'] = $data_complete_of;
        }
        if(!empty($data_notyet_of)){
            $data['data_notyet_of'] = $data_notyet_of;
        }
        if(!empty($data_other_function_penilaian)){
            $data['data_other_function_penilaian'] = $data_other_function_penilaian;
        }
        
        //cek apa ada datanya
        if(!empty($data)){
            return $data; // balikkan data
        } else {
            return null; // balikkan kata2 null
        }
    }
    public function f360cekOtoritas($data_penilai, $nik_dinilai) { // cek otoritas terhadap karyawan
        // cek dalam beberapa kondisi 
        if($data_penilai['hirarki_org'] == 'Functional-div'){
            //ambil data teman sebaya N-1 di divisi dan deptnya
            $data_peers = $this->f360getEmployeDetail(
                'hirarki_org = "N-1"'.
                ' AND div_id = "'.$data_penilai['div_id'].
                '" AND nik = "'.$nik_dinilai.'"'
            );
            //ambil data teman sebaya Functional-div di divisi dan deptnya
            if(empty($data_peers)){
                $data_peers =$this->f360getEmployeDetail(
                    'hirarki_org = "Functional-div"'.
                    ' AND div_id = "'.$data_penilai['div_id'].
                    '" AND nik = "'.$nik_dinilai.'"'
                );
            }
            // ambil data N-2 di divisinya
            $data_other_function = $this->f360getEmployeDetail(
                'hirarki_org = "N-2"'.
                ' AND div_id = "'.$data_penilai['div_id'].
                '" AND nik = "'.$nik_dinilai.'"'
            );
        } elseif($data_penilai['hirarki_org'] == 'Functional-dep'){
            // ambil data atasannya
            $data_atasan = $this->f360getEmployeDetail(array(
                $this->table['position'] .'.id' => $data_penilai['id_atasan1'],
                'nik' => $nik_dinilai
            ));
        } elseif($data_penilai['hirarki_org'] == 'N-1') {
            //ambil data teman sebaya di divisi dan deptnya
            $data_peers = $this->f360getEmployeDetail(
                'hirarki_org = "N-1"'.
                ' AND div_id = "'.$data_penilai['div_id'].
                '" AND nik = "'.$nik_dinilai.'"'
            );
            //ambil data teman sebaya Functional-div di divisi dan deptnya
            if(empty($data_peers)){
                $data_peers = $this->f360getEmployeDetail(
                    'hirarki_org = "Functional-div"'.
                    ' AND div_id = "'.$data_penilai['div_id'].
                    '" AND nik = "'.$nik_dinilai.'"'
                );
            }
            // ambil data di employe di divisi lain
            $data_other_function = $this->f360getEmployeDetail(
                'hirarki_org = "N-1"'.
                ' AND div_id != "'.$data_penilai['div_id'].
                '" AND nik = "'.$nik_dinilai.'"'
            );
        } elseif($data_penilai['hirarki_org'] == 'N-2') {
            // ambil atasan di dept dan divisi yang sama N-1 dengan nik penilai
            $data_atasan = $this->f360getEmployeDetail(array(
                $this->table['position'] .'.id' => $data_penilai['id_atasan1'],
                'nik' => $nik_dinilai
            ));
            // ambil data teman sebaya di div, dept, dan hirarki yang sama dengan nik penilai
            $data_peers = $this->f360getEmployeDetail(
                'hirarki_org = "N-2"'.
                ' AND div_id = "'.$data_penilai['div_id'].
                '" AND dept_id = "'.$data_penilai['dept_id'].
                '" AND nik = "'.$nik_dinilai.
                '" AND position_id != "'.$data_penilai['id_atasan1'].'"'
            );
            // ambil data div sama, dept beda, hirarki sama dengan nik penilai
            $data_other_function = $this->f360getEmployeDetail(
                'hirarki_org = "N-2"'.
                ' AND div_id = "'.$data_penilai['div_id'].
                '" AND dept_id != "'.$data_penilai['dept_id'].
                '" AND nik = "'.$nik_dinilai.'"'
            );
            //ambil data teman sebaya Functional-div di divisi dan deptnya
            if(empty($data_other_function)){
                $data_other_function = $this->f360getEmployeDetail(
                    'hirarki_org = "Functional-div"'.
                    ' AND div_id = "'.$data_penilai['div_id'].
                    '" AND nik = "'.$nik_dinilai.'"'
                );
            }
        } elseif($data_penilai['hirarki_org'] == 'N-3') {
            // ambil data atasannya dengan nik penilai
            $data_atasan = $this->f360getEmployeDetail(array(
                // 'hirarki_org' => 'N-2', 
                // 'div_id' => $data_penilai['div_id'],
                // 'dept_id' => $data_penilai['dept_id'],
                $this->table['position'] .'.id' => $data_penilai['id_atasan1'],
                'nik' => $nik_dinilai
            ));
        } else { // jika bukan N-1, N-2, N-3 tampilkan pesan error
            // show_error($message, $status_code, $heading = 'An Error Was Encountered')
            //show_404; // for notfound
            show_error('The server cannot or will not process the request due to an apparent client error.', 400, 'Bad Request');
        }

        // cek ketiga data apa ada
        if(!empty($data_atasan)) {
            $data_dinilai = $data_atasan[0];
        } elseif(!empty($data_peers)) {
            $data_dinilai = $data_peers[0];
        } elseif(!empty($data_other_function)) {
            $data_dinilai = $data_other_function[0];
        } else {
            // show_error($message, $status_code, $heading = 'An Error Was Encountered')
            //show_404; // for notfound
            show_error('The server cannot or will not process the request due to an apparent client error.', 400, 'Bad Request');
        }

        return $data_dinilai;
    }

    public function f360cekStatus($nik_penilai, $nik_dinilai){ // cek status pengisian survey 360 feedback
        if($this->_general_m->getRow('survey_f360_hasil', array('nik_penilai' => $nik_penilai, 'nik_dinilai' => $nik_dinilai)) > 1){ // jika ada data jawabannya
            return TRUE;
        } else { // jika tidak ada
            return FALSE;
        }
    }

    public function f360getEmployeDetail($where){ // dapatkan detail employe
        $data = $this->_general_m->getJoin2tablesOrder(
            'nik, emp_name, position_name, div_id, dept_id, hirarki_org, id_atasan1',
            'master_employee',
            $this->table['position'],
            $this->table['position'] .'.id = master_employee.position_id',
            $where,
            'emp_name'
        );

        // get nama div_id sama dept_id
        foreach($data as $k => $v){
            $data[$k]['departemen'] = $this->_general_m->getOnce('nama_departemen', 'master_department', array('id' => $v['dept_id']))['nama_departemen'];
            $data[$k]['divisi'] = $this->_general_m->getOnce('division', 'master_division', array('id' => $v['div_id']))['division'];
        }

        return $data;
    }

    public function ajaxF360getEmployeDetail(){ // ajax mendapatkan detail employe
        // get data table
        $data = ($this->_general_m->getJoin2tables(
            'nik, emp_name, position_name, div_id, dept_id, hirarki_org',
            'master_employee',
            $this->table['position'],
            $this->table['position'] .'.id = master_employee.position_id',
            array('nik' => $this->input->post('nik'))
        ));

        // get nama div_id sama dept_id
        foreach($data as $k => $v){
            $data[$k]['departemen'] = $this->_general_m->getOnce('nama_departemen', 'master_department', array('id' => $v['dept_id']))['nama_departemen'];
            $data[$k]['divisi'] = $this->_general_m->getOnce('division', 'master_division', array('id' => $v['div_id']))['division'];
        }

        // ambil data pertama aja dan kirim ke ajax
        echo json_encode($data[0]);
    }

    /* -------------------------------------------------------------------------- */
    /*                                  SETTINGS                                  */
    /* -------------------------------------------------------------------------- */
    public function settings_status(){
        // cek apa dia punya role maintenance
        if($this->session->userdata('role_id') != 1){
            // show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
            redirect('maintenance');
        }
        // ambil data karyawan
        $data_karyawan = $this->_general_m->getAll('nik, emp_name, position_id', 'master_employee', array());

        // ambil nama departemen, nama divisi, nama posisi, dan status surveynya
        foreach($data_karyawan as $k => $v){
            // get detail position sama nama departemen
            $temp_position = $this->_general_m->getJoin2tables(
                'position_name, '. $this->table['position'] .'.div_id, '. $this->table['position'] .'.dept_id, nama_departemen, '. $this->table['position'] .'.hirarki_org, '. $this->table['position'] .'.id_atasan1',
                $this->table['position'],
                'master_department',
                'master_department.id = '. $this->table['position'] .'.dept_id',
                array($this->table['position'] .'.id' => $v['position_id'])
            )[0];
            $temp_position['nik'] = $v['nik'];
            // ambil nama divisi
            $temp_divisi = $this->_general_m->getOnce('division', 'master_division', array('id' => $temp_position['div_id']))['division'];
            // ambil data survey f360
            $temp_data_f360s = $this->f360getData($temp_position);

            // cek status survey f360
            if(!empty($temp_data_f360s)){
                $data_survey_complete_f360 = $this->f360counterStatusOF($temp_data_f360s); // ambil data counter survey OF
                // cek jika antara counter survey dan counter complete sama
                if($data_survey_complete_f360['counter_survey_f360'] == $data_survey_complete_f360['counter_complete_f360']){
                    $temp_status_f360s = 1; // jika dia sudah selesai
                } else {
                    $temp_status_f360s = 0; // jika dia belum selesai
                }
            } else {
                $temp_status_f360s = 2; // jika dia tidak berhak mengisi
            }
            
            // cek status survey service excellence
            //untuk excellence cek position_idnya buat GA Driver
            if($v['position_id'] != 184){
                // cek apa survey engagement sudah diisi
                if($this->_general_m->getRow('survey_exc_hasil', array('nik' => $v['nik'])) < 1){
                    $temp_status_exc = 0; // jika belum selesai
                } else {
                    $temp_status_exc = 1; // jika sudah selesai
                }
            } else {
                $temp_status_exc = 2; // jika dia tidak berhak mengisi
            }

            // cek apa survey engagement sudah diisi
            if($this->_general_m->getRow('survey_eng_hasil', array('nik' => $v['nik'])) < 1){
                $temp_status_eng = 0; // jika belum selesai
            } else {
                $temp_status_eng = 1; // jika sudah selesai
            }
                
            // tambah data ke data karyawan
            $data_karyawan[$k]['divisi']     = $temp_divisi;
            $data_karyawan[$k]['departemen'] = $temp_position['nama_departemen'];
            $data_karyawan[$k]['position']   = $temp_position['position_name'];
            $data_karyawan[$k]['f360']       = $temp_status_f360s;
            $data_karyawan[$k]['exc']        = $temp_status_exc;
            $data_karyawan[$k]['eng']        = $temp_status_eng;
        }
        
        // cek buat trigger
        if(empty($this->session->userdata('survey_status'))){
            $this->session->set_userdata('survey_status', 1);
        }

        // survey data
        $data['data_karyawan'] = $data_karyawan;

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = 'Survey Status';
        $data['load_view'] = 'appsettings/survey_status_appsettings_v';

        // custom styles and script
        if($this->session->userdata('survey_status') == 1){ // jika lagi ga print mode sembunyikan tombol export
            $data['additional_styles'] = array('plugins/datatables/styles_datatables');
            // $data['custom_styles'] = array();
            $data['custom_script'] = array('survey/script_survey', 'plugins/datatables/script_datatables');
        } else {
            $data['additional_styles'] = array('plugins/tableexport/styles_tableexport', 'plugins/datatables/styles_datatables');
            // $data['custom_styles'] = array();
            $data['custom_script'] = array('survey/script_survey', 'plugins/tableexport/script_tableexport', 'plugins/datatables/script_datatables');
        }
        
        
        $this->load->view('main_v', $data);
    }

    public function settings_statusDepartemen(){
        // cek apa dia punya role maintenance
        if($this->session->userdata('role_id') != 1){
            show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
        }
        // ambil divisi
        $data_survey = $this->_general_m->getAll('id, division', 'master_division', array());
        // ambil departemen
        foreach($data_survey as $k => $v){
            // ambil departemen
            $data_survey[$k]['departemen'] = $this->_general_m->getAll('id, nama_departemen', 'master_department', array('div_id' => $v['id']));            

            // counter grandtotal survey
            if($k === array_key_first($data_survey)){
                $counter_data_survey['total_eng'] = 0; $counter_data_survey['total_exc'] = 0; $counter_data_survey['total_f360'] = 0;
                $counter_data_survey['total_done_eng'] = 0; $counter_data_survey['total_done_exc'] = 0; $counter_data_survey['total_done_f360'] = 0;
            }

            // ambil data 
            foreach($data_survey[$k]['departemen'] as $key => $value){
                $data_karyawan = $this->_general_m->getJoin2tables(
                    'master_employee.nik, master_employee.position_id, '. $this->table['position'] .'.div_id, '. $this->table['position'] .'.dept_id, '. $this->table['position'] .'.hirarki_org, '. $this->table['position'] .'.id_atasan1',
                    'master_employee',
                    $this->table['position'],
                    $this->table['position'] .'.id = master_employee.position_id',
                    $this->table['position'] .'.div_id = "'.$v['id'].
                    '" AND '. $this->table['position'] .'.dept_id = "'.$value['id'].
                    '" AND '. $this->table['position'] .'.hirarki_org != "N"'
                );

                // counter
                $counter_f360 = 0; $counter_exc = 0; $counter_eng = 0;
                // counter total employee
                $counter_total_f360 = 0; $counter_total_exc = 0; $counter_total_eng = 0;
                // ambil status dari masing-masing karyawan
                foreach($data_karyawan as $nilai){
                    // ambil data survey f360
                    $temp_data_f360s = $this->f360getData($nilai);

                    // cek status survey f360
                    if(!empty($temp_data_f360s)){
                        $data_survey_complete_f360 = $this->f360counterStatusOF($temp_data_f360s); // ambil data counter survey OF
                        // cek jika antara counter survey dan counter complete sama
                        if($data_survey_complete_f360['counter_survey_f360'] == $data_survey_complete_f360['counter_complete_f360']){
                            $temp_status_f360 = 1; // jika dia sudah selesai
                        } else {
                            $temp_status_f360 = 0; // jika dia belum selesai
                        }
                        $counter_total_f360 = $counter_total_f360 + 1; // totla karyawan yang bisa isi f360
                    } else { // jika dia tidak punya hak buat ngisi f360
                        $temp_status_f360 = 0;
                    }
                    
                    // cek status survey service excellence
                    // cek untuk karyawan yang tidak bisa akses excellence
                    if($nilai['position_id'] != 184){
                        // cek apa survey excellence sudah diisi
                        if($this->_general_m->getRow('survey_exc_hasil', array('nik' => $nilai['nik'])) < 1){
                            $temp_status_exc = 0; // jika belum selesai
                        } else {
                            $temp_status_exc = 1; // jika sudah selesai
                        }
                        $counter_total_exc = $counter_total_exc + 1; // total karyaawn yang bisa isi excellence
                    }
                    // cek apa survey engagement sudah diisi
                    if($this->_general_m->getRow('survey_eng_hasil', array('nik' => $nilai['nik'])) < 1){
                        $temp_status_eng = 0; // jika belum selesai
                    } else {
                        $temp_status_eng = 1; // jika sudah selesai
                    }
                    $counter_total_eng = $counter_total_eng + 1; // total karyawan yang bisa isi engagement

                    // tambah ke counter
                    $counter_f360 = $counter_f360 + $temp_status_f360;
                    $counter_exc = $counter_exc + $temp_status_exc;
                    $counter_eng = $counter_eng + $temp_status_eng;
                }

                // kosongkan penanda count departemen
                if($key === array_key_first($data_survey[$k]['departemen'])){
                    $data_survey[$k]['count_departemen'] = 0;

                    $data_survey[$k]['total_exc'] = 0;
                    $data_survey[$k]['total_done_exc'] = 0;

                    $data_survey[$k]['total_eng'] = 0;
                    $data_survey[$k]['total_done_eng'] = 0;

                    $data_survey[$k]['total_f360'] = 0;
                    $data_survey[$k]['total_done_f360'] = 0;
                }

                // tambah ke data survey
                if(!empty(count($data_karyawan))){
                    $data_survey[$k]['departemen'][$key]['total_employee'] = count($data_karyawan);
                    $data_survey[$k]['departemen'][$key]['exc']['done'] = $counter_exc;
                    $data_survey[$k]['departemen'][$key]['exc']['rasio'] = ($counter_exc / $counter_total_exc) * 100;
                    $data_survey[$k]['departemen'][$key]['exc']['total'] = $counter_total_exc;

                    $data_survey[$k]['departemen'][$key]['eng']['done'] = $counter_eng;
                    $data_survey[$k]['departemen'][$key]['eng']['rasio'] = ($counter_eng / $counter_total_eng) * 100;
                    $data_survey[$k]['departemen'][$key]['eng']['total'] = $counter_total_eng;


                    $data_survey[$k]['departemen'][$key]['f360']['done'] = $counter_f360;
                    $data_survey[$k]['departemen'][$key]['f360']['rasio'] = ($counter_f360 / $counter_total_f360) * 100;
                    $data_survey[$k]['departemen'][$key]['f360']['total'] = $counter_total_f360;

                    // jumlahkan departemen
                    $data_survey[$k]['count_departemen'] = $data_survey[$k]['count_departemen'] + 1;

                    // jumlahkan total masing-masing per departemen
                    $data_survey[$k]['total_exc'] = $data_survey[$k]['total_exc'] + $counter_total_exc;
                    $data_survey[$k]['total_done_exc'] = $data_survey[$k]['total_done_exc'] + $counter_exc;

                    $data_survey[$k]['total_eng'] = $data_survey[$k]['total_eng'] + $counter_total_eng;
                    $data_survey[$k]['total_done_eng'] = $data_survey[$k]['total_done_eng'] + $counter_eng;

                    $data_survey[$k]['total_f360'] = $data_survey[$k]['total_f360'] + $counter_total_f360;
                    $data_survey[$k]['total_done_f360'] = $data_survey[$k]['total_done_f360'] + $counter_f360;
                } else { // hapus array jika tidak ada karyawannya
                    unset($data_survey[$k]['departemen'][$key]);
                }
            }

            // buat rasio total masing-masing per departemen
            if(!empty(count($data_karyawan))){
                $data_survey[$k]['total_rasio_exc'] = ($data_survey[$k]['total_done_exc'] / $data_survey[$k]['total_exc']) * 100;
                $data_survey[$k]['total_rasio_eng'] = ($data_survey[$k]['total_done_eng'] / $data_survey[$k]['total_eng']) * 100;
                $data_survey[$k]['total_rasio_f360'] = ($data_survey[$k]['total_done_f360'] / $data_survey[$k]['total_f360']) * 100;
            }

            // buat total masing-masing departemen
            $counter_data_survey['total_eng'] = $counter_data_survey['total_eng'] + $data_survey[$k]['total_eng']; 
            $counter_data_survey['total_exc'] = $counter_data_survey['total_exc'] + $data_survey[$k]['total_exc']; 
            $counter_data_survey['total_f360'] = $counter_data_survey['total_f360'] + $data_survey[$k]['total_f360']; 

            $counter_data_survey['total_done_eng'] = $counter_data_survey['total_done_eng'] + $data_survey[$k]['total_done_eng']; 
            $counter_data_survey['total_done_exc'] = $counter_data_survey['total_done_exc'] + $data_survey[$k]['total_done_exc']; 
            $counter_data_survey['total_done_f360'] = $counter_data_survey['total_done_f360'] + $data_survey[$k]['total_done_f360']; 
        }
        // buat rasio masing-masing survey
        $counter_data_survey['total_rasio_eng'] = ($counter_data_survey['total_done_eng'] / $counter_data_survey['total_eng']) * 100; 
        $counter_data_survey['total_rasio_exc'] = ($counter_data_survey['total_done_exc'] / $counter_data_survey['total_exc']) * 100; 
        $counter_data_survey['total_rasio_f360'] = ($counter_data_survey['total_done_f360'] / $counter_data_survey['total_f360']) * 100; 

        // survey data
        $data['data_survey'] = $data_survey;
        $data['counter_data_survey'] = $counter_data_survey;

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = 'Survey Status';
        $data['load_view'] = 'appsettings/survey_statusDepartemen_appsettings_v';

        // custom styles and script
        $data['additional_styles'] = array('plugins/tableexport/styles_tableexport');
        // $data['custom_styles'] = array();
        $data['custom_script'] = array('survey/script_survey', 'plugins/tableexport/script_tableexport');
        
        $this->load->view('main_v', $data);
    }

    /* ------------------------- SETTINGS OTHER FUNCTION ------------------------ */
    public function settings_printModeTable() {
        if($this->session->userdata('survey_status') == 1){
            $this->session->unset_userdata('survey_status');
            $this->session->set_userdata('survey_status', 2);
        } else {
            $this->session->unset_userdata('survey_status');
            $this->session->set_userdata('survey_status', 1);
        }

        header('location: ' . base_url($this->input->get('url')));
    }

    function checkIsActive($id_survey){
        return $this->_general_m->getOnce('is_period', $this->table['title'], array('id_survey' => $id_survey))['is_period'];
    }

    // test
    public function test(){
        return 'hello';
    }
}

/* End of file Survey.php */

/*
    Wording
    
    Service Excellence
    Engagement
    360 derajat-character feedback
*/

?>

