<?php  
// TODO tambah kolom penilaian N/A = "" = null = kosong
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey extends CI_Controller {
    
    public function __construct(){
        // show_error($message, $status_code, $heading = 'An Error Was Encountered')
        // echo($a);
        // show_error('error dah', 404, 'ada errrrororororororo');
        // exit;
        parent::__construct();
        // main helper
        is_logged_in(); //Cek Login
        date_default_timezone_set('Asia/Jakarta'); // set timezone
    }
    

    // public function index(){
        // main data
        // $data['sidebar'] = getMenu(); // ambil menu
        // $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        // $data['user'] = getDetailUser(); //ambil informasi user
        // $data['page_title'] = "Survey";
        // $data['load_view'] = 'survey/exc_survey_v';
        
        // $this->load->view('main_v', $data);
    // }

    /* -------------------------------------------------------------------------- */
    /*                          Service Excellence Survey                         */
    /* -------------------------------------------------------------------------- */
    public function excellence(){ // main survey excellence function
        //cek apakah karyawan sudah mengisi Service Excellence Survey
        if($this->_general_m->getRow('survey_exc_hasil', array('nik' => $this->session->userdata('nik'))) < 1 ){
            //ambil departemen yang dinilai
            $departemen = $this->_general_m->getAll('*', 'survey_exc_departemen', array());

            // FIXME ambil id departemen
            $my_departemen = $this->_general_m->getJoin2tables('nama_departemen', 'position', 'departemen', 'position.dept_id = departemen.id', 'position.id='.$this->session->userdata('position_id'));

            // FIXME samain id departemen dan hapus yang sama
            // hapus departemen kalo dia itu berada di departemen itu
            $x = 0; // prepare variable
            foreach($departemen as $dept){
                if ($my_departemen[0]['nama_departemen'] != $dept['nama']){
                    $data['departemen'][$x] = $dept;
                    $x++;
                }
            }

            // ambil data survey
            $data['survey1'] = $this->_general_m->getAll('*', 'survey_exc_pertanyaan', array('id_tipepertanyaan' => 'A'));
            $data['survey2'] = $this->_general_m->getAll('*', 'survey_exc_pertanyaan', array('id_tipepertanyaan' => 'B'));

            // ambil informasi data departemen
            foreach($data['survey2'] as $k => $v){
                $data['survey2'][$k]['nama_departemen'] = $this->_general_m->getOnce('nama', 'survey_exc_departemen', array('id' => $v['id_departemen']))['nama'];
            }

            // main data
            $load_view = 'survey/exc_survey_v';
        } else {
            //main data
            $load_view = 'survey/exc_selesai_survey_v';
        }
        
        // survey data
        $data['survey_title'] = $this->_general_m->getOnce('judul', 'survey_page_title', array('id_survey' => 0))['judul'];

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => $this->uri->uri_string()))['title'];;
        $data['load_view'] = $load_view;
        $data['custom_styles'] = array('survey_styles');
        $data['custom_script'] = array('survey/script_survey', 'survey/script_exc_survey');
        
        $this->load->view('main_v', $data);
    }
    public function excSubmit(){ // submit excellence function
         //ambil nik
        // print_r($this->session->userdata('login'));
        
        $employe_data =  $this->_general_m->getJoin2tables(
            'nik, emp_name, position.id, position.dept_id, position.div_id',
            'employe',
            'position',
            'employe.position_id = position.id',
            array('nik' => $this->session->userdata('nik'))
        )[0];
        $employe_data['divisi'] = $this->_general_m->getOnce('division', 'divisi', array('id' => $employe_data['div_id']))['division'];
        $employe_data['departemen'] = $this->_general_m->getOnce('nama_departemen', 'departemen', array('id' => $employe_data['dept_id']))['nama_departemen'];
        // print_r(json_encode($employe_data));
        // exit;

        // $menu = $CI->_general_m->getJoin2tables('', 'survey_user_menu', 'survey_user_menu_access', 'survey_user_menu.id_menu = survey_user_menu_access.id_menu', array('id_user' => $CI->session->userdata('role_id')));

        //post semua pertanyaan
        // print_r(json_encode($this->input->post()));
        // exit;

        //ambil id pertanyaan
        $data_pertanyaan = $this->_general_m->getAll('id, judul_pertanyaan, id_tipepertanyaan', 'survey_exc_pertanyaan', array());

        
        // print_r(json_encode($data_pertanyaan));

        // //ambil id pertanyaan

        // echo("<br/>");
        // echo("<br/>");
        // echo("<br/>");

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

        // foreach($jawaban_survey as $v){
        //     print_r($v);
        //     echo('<br/>');
        // }

        //simpan dalam database
        $this->_general_m->insertAll('survey_exc_hasil', $jawaban_survey);
        //ubah is_done karyawan kalau dia sudah selesai mengisi
        // $this->_general_m->updateOnce('employe', array('nik' => $nik) , array('is_done' => 1));
        
        header('location: ' . base_url('survey/excellence'));
    }

    /* -------------------------------------------------------------------------- */
    /*                              Engagement Survey                             */
    /* -------------------------------------------------------------------------- */
    public function engagement(){ // survey engagement main function
        // cek apa karyawan sudah isi survey
        if($this->_general_m->getRow('survey_eng_hasil', array('nik' => $this->session->userdata('nik'))) < 1){
            // siapkan pertanyaan data survey
            $data['survey_data'] = $this->_general_m->getAll('id, pertanyaan', 'survey_eng_pertanyaan', array());

            // load view
            $load_view = 'survey/eng_survey_v';
        } else {
            // load view
            $load_view = 'survey/eng_selesai_survey_v';
        }
        // survey data
        $data['survey_title'] = $this->_general_m->getOnce('judul', 'survey_page_title', array('id_survey' => 1))['judul'];

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => $this->uri->uri_string()))['title'];;
        $data['load_view'] = $load_view;
        $data['custom_styles'] = array('survey_styles');
        $data['custom_script'] = array('survey/script_survey', 'survey/script_eng_survey');
        
        $this->load->view('main_v', $data);
    }
    public function engSubmit(){ // submit engagement function
        $employe_data =  $this->_general_m->getJoin2tables(
            'nik, emp_name, position.id, position.dept_id, position.div_id',
            'employe',
            'position',
            'employe.position_id = position.id',
            array('nik' => $this->session->userdata('nik'))
        )[0];
        $employe_data['divisi'] = $this->_general_m->getOnce('division', 'divisi', array('id' => $employe_data['div_id']))['division'];
        $employe_data['departemen'] = $this->_general_m->getOnce('nama_departemen', 'departemen', array('id' => $employe_data['dept_id']))['nama_departemen'];
        // print_r(json_encode($employe_data));
        // exit;

        // $menu = $CI->_general_m->getJoin2tables('', 'survey_user_menu', 'survey_user_menu_access', 'survey_user_menu.id_menu = survey_user_menu_access.id_menu', array('id_user' => $CI->session->userdata('role_id')));

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

        header('location: ' . base_url('survey/engagement'));
    }

    /* -------------------------------------------------------------------------- */
    /*                              Feedback 360 Survey                             */
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
            'position.hirarki_org, position.dept_id, position.div_id', 
            'employe', 
            'position', 
            'position.id = employe.position_id', 
            array('nik' => $this->session->userdata('nik'))
        )[0];

        if($data_employe['hirarki_org'] == 'N-1') {
            //ambil data teman sebaya di divisi dan deptnya
            $data_peers = $this->f360getEmployeDetail(
                'hirarki_org = "N-1"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND dept_id = "'.$data_employe['dept_id'].
                '" AND nik != "'.$this->session->userdata('nik').'"'
            );
            // ambil data di employe di divisi lain
            $data_other_function = $this->f360getEmployeDetail(
                'hirarki_org = "N-1"'.
                ' AND div_id != "'.$data_employe['div_id'].
                '" AND nik != "'.$this->session->userdata('nik').'"'
            );
        } elseif($data_employe['hirarki_org'] == 'N-2') {
            // ambil atasan di dept dan divisi yang sama N-1
            $data_atasan = $this->f360getEmployeDetail(array(
                'hirarki_org' => 'N-1', 
                'div_id' => $data_employe['div_id'],
                'dept_id' => $data_employe['dept_id']
            ));
            // ambil data teman sebaya di div, dept, dan hirarki yang sama
            $data_peers = $this->f360getEmployeDetail(
                'hirarki_org = "N-2"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND dept_id = "'.$data_employe['dept_id'].
                '" AND nik != "'.$this->session->userdata('nik').'"'
            );
            // ambil data div sama, dept beda, hirarki sama
            $data_other_function = $this->f360getEmployeDetail(
                'hirarki_org = "N-2"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND dept_id != "'.$data_employe['dept_id'].
                '" AND nik != "'.$this->session->userdata('nik').'"'
            );
        } elseif($data_employe['hirarki_org'] == 'N-3') {
            // ambil data atasannya
            $data_atasan = $this->f360getEmployeDetail(array(
                'hirarki_org' => 'N-2', 
                'div_id' => $data_employe['div_id'],
                'dept_id' => $data_employe['dept_id']
            ));
        } else { // jika posisinya bukan N-1, N-2, atau N-3
            header('location: ' . base_url('survey/f360limitedUser'));
        }

        // cek status pengisian survey di masing2 variabel data
        if(!empty($data_atasan)){ // data atasan
            foreach($data_atasan as $k => $v){
                $data_atasan[$k]['status'] = $this->f360cekStatus($this->session->userdata('nik'), $v['nik']);
            }
        }
        if(!empty($data_peers)){  // data peers
            foreach($data_peers as $k => $v){
                $data_peers[$k]['status'] = $this->f360cekStatus($this->session->userdata('nik'), $v['nik']);
            }
        }
        $data_complete_of = array(); $data_notyet_of = array(); $x=0; $y=0;
        if(!empty($data_other_function)){ // data other function
            foreach($data_other_function as $k => $v){
                if($this->f360cekStatus($this->session->userdata('nik'), $v['nik']) == TRUE){
                    $data_complete_of[$x] = $v;
                    $x++;
                } else {
                    $data_notyet_of[$y] = $v;
                    $y++;
                }
            }
        }

        // print_r(json_encode($data_other_function));
        // exit;

        // counter buat max feedback other function
        $data['max_feedback_other_peers'] = 3;

        if(!empty($data_complete_of)){
            $data['max_feedback_other_peers'] = $data['max_feedback_other_peers'] - count($data_complete_of);
        }
        
        // survey data
        $data['data_atasan'] = $data_atasan;
        $data['data_peers'] = $data_peers;
        $data['data_other_function'] = $data_other_function;
        // data other function
        $data['data_complete_of'] = $data_complete_of;
        $data['data_notyet_of'] = $data_notyet_of;

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => $this->uri->uri_string()))['title'];
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

        $data_dinilai = $this->f360cekOtoritas($data_penilai, $nik_dinilai);

        // cek apa udh diisi survey karyawan ini
        if($this->_general_m->getRow('survey_f360_hasil', array('nik_penilai' => $nik_penilai, 'nik_dinilai' => $nik_dinilai)) > 1){
            show_error('The request has been accepted for processing, but the processing has not been completed. The request might or might not be eventually acted upon, 
            and may be disallowed when processing occurs.', 202, 'Accepted');
        }

        // ambil kategori pertanyaan
        $pertanyaan = $this->_general_m->getAll('*', 'survey_f360_kategoripertanyaan', array());
        foreach($pertanyaan as $key => $value){
            $pertanyaan[$key]['survey_pertanyaan'] = $this->_general_m->getAll('*', 'survey_f360_pertanyaan', array('id_kategori_pertanyaan' => $value['id_kategori_pertanyaan']));        
        }

        // survey data
        $data['survey_title'] = $this->_general_m->getOnce('judul', 'survey_page_title', array('id_survey' => 2))['judul'];
        $data['nik_dinilai'] = $nik_dinilai;
        $data['pertanyaan'] = $pertanyaan;

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => 'survey/feedback360'))['title'];
        $data['load_view'] = "survey/f360_survey_v";
        $data['custom_styles'] = array('survey_styles');
        $data['custom_script'] = array('survey/script_survey', 'survey/script_f360_survey');
        
        $this->load->view('main_v', $data);


        // cek apa dia punya akses untuk menilai karyawan 
        // $data_karyawan = $this->f360getEmployeDetail(
        //     'hirarki_org = "N-1"'.
        //     ' AND div_id = "'.$data_employe['div_id'].
        //     '" AND dept_id = "'.$data_employe['dept_id'].
        //     '" AND nik != "'.$this->session->userdata('nik').'"'
        // );
    }

    public function f360limitedUser() {
        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = "360° Feedback";
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

        header('location: ' . base_url('survey/feedback360'));
    }

    /* ----------------------------- f360 Other Functions ---------------------------- */
    public function f360cekOtoritas($data_penilai, $nik_dinilai) { // cek otoritas terhadap karyawan
        // cek dalam 3 kodisi
        if($data_penilai['hirarki_org'] == 'N-1') {
            //ambil data teman sebaya di divisi dan deptnya dengan nik penilai
            $data_peers = $this->f360getEmployeDetail(
                'hirarki_org = "N-1"'.
                ' AND div_id = "'.$data_penilai['div_id'].
                '" AND dept_id = "'.$data_penilai['dept_id'].
                '" AND nik = "'.$nik_dinilai.'"'
            );
            // ambil data di employe di divisi lain dengan nik penilai
            $data_other_function = $this->f360getEmployeDetail(
                'hirarki_org = "N-1"'.
                ' AND div_id != "'.$data_penilai['div_id'].
                '" AND nik = "'.$nik_dinilai.'"'
            );
        } elseif($data_penilai['hirarki_org'] == 'N-2') {
            // ambil atasan di dept dan divisi yang sama N-1 dengan nik penilai
            $data_atasan = $this->f360getEmployeDetail(array(
                'hirarki_org' => 'N-1', 
                'div_id' => $data_penilai['div_id'],
                'dept_id' => $data_penilai['dept_id'],
                'nik' => $nik_dinilai
            ));
            // ambil data teman sebaya di div, dept, dan hirarki yang sama dengan nik penilai
            $data_peers = $this->f360getEmployeDetail(
                'hirarki_org = "N-2"'.
                ' AND div_id = "'.$data_penilai['div_id'].
                '" AND dept_id = "'.$data_penilai['dept_id'].
                '" AND nik = "'.$nik_dinilai.'"'
            );
            // ambil data div sama, dept beda, hirarki sama dengan nik penilai
            $data_other_function = $this->f360getEmployeDetail(
                'hirarki_org = "N-2"'.
                ' AND div_id = "'.$data_penilai['div_id'].
                '" AND dept_id != "'.$data_penilai['dept_id'].
                '" AND nik = "'.$nik_dinilai.'"'
            );
        } elseif($data_penilai['hirarki_org'] == 'N-3') {
            // ambil data atasannya dengan nik penilai
            $data_atasan = $this->f360getEmployeDetail(array(
                'hirarki_org' => 'N-2', 
                'div_id' => $data_penilai['div_id'],
                'dept_id' => $data_penilai['dept_id'],
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
        $data = $this->_general_m->getJoin2tables(
            'nik, emp_name, position_name, div_id, dept_id, hirarki_org',
            'employe',
            'position',
            'position.id = employe.position_id',
            $where
        );

        // get nama div_id sama dept_id
        foreach($data as $k => $v){
            $data[$k]['departemen'] = $this->_general_m->getOnce('nama_departemen', 'departemen', array('id' => $v['dept_id']))['nama_departemen'];
            $data[$k]['divisi'] = $this->_general_m->getOnce('division', 'divisi', array('id' => $v['div_id']))['division'];
        }

        return $data;
    }

    public function ajaxF360getEmployeDetail(){ // ajax mendapatkan detail employe
        // get data table
        $data = ($this->_general_m->getJoin2tables(
            'nik, emp_name, position_name, div_id, dept_id, hirarki_org',
            'employe',
            'position',
            'position.id = employe.position_id',
            array('nik' => $this->input->post('nik'))
        ));

        // get nama div_id sama dept_id
        foreach($data as $k => $v){
            $data[$k]['departemen'] = $this->_general_m->getOnce('nama_departemen', 'departemen', array('id' => $v['dept_id']))['nama_departemen'];
            $data[$k]['divisi'] = $this->_general_m->getOnce('division', 'divisi', array('id' => $v['div_id']))['division'];
        }

        // ambil data pertama aja dan kirim ke ajax
        echo json_encode($data[0]);
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

