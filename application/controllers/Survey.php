<?php  
// TODO tambah kolom penilaian N/A = "" = null = kosong
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey extends CI_Controller {
    protected $title_excellence = 'Service Excellence';
    protected $title_engagement = 'Employee Engagement';
    protected $title_f360 = '360° Feedback';
    
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

    public function index(){
        // cek apa survey excellence sudah diisi
        if($this->_general_m->getRow('survey_exc_hasil', array('nik' => $this->session->userdata('nik'))) < 1){
            //nothing
        } else {
            $data['survey_status']['exc'] = 'closed';
        }
        // cek apa survey engagement sudah diisi
        if($this->_general_m->getRow('survey_eng_hasil', array('nik' => $this->session->userdata('nik'))) < 1){
            // nothing
        } else {
            $data['survey_status']['eng'] = 'closed';
        }

        // cek apa survey 360 sudah diisi atau dia tidak memiliki akses
        $data_employe = $this->_general_m->getJoin2tables(
            'position.hirarki_org, position.dept_id, position.div_id, position.id_atasan1', 
            'employe', 
            'position', 
            'position.id = employe.position_id', 
            array('nik' => $this->session->userdata('nik'))
        )[0];
        // cek hirarki apa dia N-1, N-2, atau N-3
        // FIXME
        if($data_employe['hirarki_org'] == 'N-1' || $data_employe['hirarki_org'] == 'N-2' || $data_employe['hirarki_org'] == 'N-3' || $data_employe['hirarki_org'] == 'Functional-div' || $data_employe['hirarki_org'] == 'Functional-dep' || $data_employe['hirarki_org'] == 'Functional') {
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

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu', array('url' => $this->uri->uri_string()))['title'];
        $data['load_view'] = 'survey/survey_v';
        $data['custom_script'] = array('survey/script_survey');
        
        $this->load->view('main_v', $data);
    }

    /* -------------------------------------------------------------------------- */
    /*                          Service Excellence Survey                         */
    /* -------------------------------------------------------------------------- */
    public function excellence(){ // main survey excellence function
        //cek apakah karyawan sudah mengisi Service Excellence Survey
        if($this->_general_m->getRow('survey_exc_hasil', array('nik' => $this->session->userdata('nik'))) < 1 ){
            //ambil departemen yang dinilai
            $departemen = $this->_general_m->getAll('*', 'survey_exc_departemen', array());

            //  ambil id departemen
            $my_departemen = $this->_general_m->getJoin2tables('nama_departemen, departemen.id', 'position', 'departemen', 'position.dept_id = departemen.id', 'position.id='.$this->session->userdata('position_id'))[0];

            // samain id departemen dan hapus yang sama
            // hapus departemen kalo dia itu berada di departemen itu
            $x = 0; // prepare variable
            foreach($departemen as $dept){
                if ($my_departemen['nama_departemen'] != $dept['nama']){
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

        $this->session->set_flashdata('one_survey', 'toastr["success"]("Thank You for completing '.$this->title_excellence.' Survey.", "'.$this->title_excellence.' Survey Complete");');
        header('location: ' . base_url('survey'));
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
        // $data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => $this->uri->uri_string()))['title'];;
        $data['page_title'] = $this->title_engagement;
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
            'position.hirarki_org, position.dept_id, position.div_id, position.id_atasan1', 
            'employe', 
            'position', 
            'position.id = employe.position_id', 
            array('nik' => $this->session->userdata('nik'))
        )[0];

        // cek hirarki apa dia N-1, N-2, N-3, atau functional div
        // FIXME
        if($data_employe['hirarki_org'] == 'N-1' || $data_employe['hirarki_org'] == 'N-2' || $data_employe['hirarki_org'] == 'N-3' || $data_employe['hirarki_org'] == 'Functional-div' || $data_employe['hirarki_org'] == 'Functional-dept' || $data_employe['hirarki_org'] == 'Functional') {
            $data_survey = $this->f360getData($data_employe); // ambil data survey
        } else {
            header('location: ' . base_url('survey/f360limitedUser')); // arahkan ke pesan blocked
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

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        // $data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => $this->uri->uri_string()))['title'];
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
        $data['survey_title'] = $this->_general_m->getOnce('judul', 'survey_page_title', array('id_survey' => 2))['judul'];
        $data['nik_dinilai'] = $nik_dinilai;
        $data['pertanyaan'] = $pertanyaan;

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        // $data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => 'survey/feedback360'))['title'];
        $data['page_title'] = $this->title_f360;
        $data['load_view'] = "survey/f360_survey_v";
        $data['custom_styles'] = array('survey_styles');
        $data['custom_script'] = array('survey/script_survey', 'survey/script_f360_survey');
        
        $this->load->view('main_v', $data);
    }

    // tampilan buat karyawan yang buka N-1, N-2, dan N-3
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
// NOW
    // get data Feedback 360°
    public function f360getData($data_employe) {
        // if($data_employe['hirarki_org'] == 'Functional-div'){
        if($data_employe['hirarki_org'] == 'Functional'){
            //ambil data teman sebaya N-1 di divisi dan deptnya
            $data_peers = $this->f360getEmployeDetail(
                'hirarki_org = "N-1"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND nik != "'.$this->session->userdata('nik').'"'
            );
            // FIXME
            //ambil data teman sebaya Functional-div di divisi dan deptnya
            $data_peers = array_merge($data_peers, $this->f360getEmployeDetail(
                // 'hirarki_org = "Functional-div"'.
                'hirarki_org = "Functional"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND nik != "'.$this->session->userdata('nik').'"'
            ));
            // ambil data N-2 di divisinya
            $data_other_function = $this->f360getEmployeDetail(
                'hirarki_org = "N-2"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND nik != "'.$this->session->userdata('nik').'"'
            );
        } elseif($data_employe['hirarki_org'] == 'Functional-dep'){
            // ambil data atasannya
            $data_atasan = $this->f360getEmployeDetail(array(
                'position.id' => $data_employe['id_atasan1']
            ));
        } elseif($data_employe['hirarki_org'] == 'N-1') {
            //ambil data teman sebaya di divisi dan deptnya
            $data_peers = $this->f360getEmployeDetail(
                'hirarki_org = "N-1"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND nik != "'.$this->session->userdata('nik').'"'
            );
            //ambil data teman sebaya Functional-div di divisi dan deptnya
            $data_peers = array_merge($data_peers, $this->f360getEmployeDetail(
                // 'hirarki_org = "Functional-div"'.
                'hirarki_org = "Functional"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND nik != "'.$this->session->userdata('nik').'"'
            ));
            // ambil data di employe di divisi lain
            $data_other_function = $this->f360getEmployeDetail(
                'hirarki_org = "N-1"'.
                ' AND div_id != "'.$data_employe['div_id'].
                '" AND nik != "'.$this->session->userdata('nik').'"'
            );
        } elseif($data_employe['hirarki_org'] == 'N-2') {
            // ambil atasan di dept dan divisi yang sama N-1
            $data_atasan = $this->f360getEmployeDetail(array(
                'position.id' => $data_employe['id_atasan1']
            ));
            // ambil data teman sebaya di div, dept, dan hirarki yang sama
            $data_peers = $this->f360getEmployeDetail(
                'hirarki_org = "N-2"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND dept_id = "'.$data_employe['dept_id'].
                '" AND nik != "'.$this->session->userdata('nik').
                '" AND position_id != "'.$data_employe['id_atasan1'].'"'
            );
            // ambil data div sama, dept beda, hirarki sama
            $data_other_function = $this->f360getEmployeDetail(
                'hirarki_org = "N-2"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND dept_id != "'.$data_employe['dept_id'].
                '" AND nik != "'.$this->session->userdata('nik').'"'
            );
            //FIXME
            //ambil data teman sebaya Functional-div di divisi dan deptnya
            $data_other_function = array_merge($data_other_function, $this->f360getEmployeDetail(
                // 'hirarki_org = "Functional-div"'.
                'hirarki_org = "Functional"'.
                ' AND div_id = "'.$data_employe['div_id'].
                '" AND nik != "'.$this->session->userdata('nik').'"'
            ));
        } elseif($data_employe['hirarki_org'] == 'N-3') {
            // ambil data atasannya
            $data_atasan = $this->f360getEmployeDetail(array(
                'position.id' => $data_employe['id_atasan1']
            ));
        } else { // jika posisinya bukan N-1, N-2, atau N-3
            // nothing
        }

        // cek status pengisian survey di masing2 variabel data
        if(!empty($data_atasan)){ // data atasan
            foreach($data_atasan as $k => $v){
                // cek status data atasan dengan melihat nik poenilai dan nik dinilai
                $data_atasan[$k]['status'] = $this->f360cekStatus($this->session->userdata('nik'), $v['nik']);
            }
        }
        if(!empty($data_peers)){  // data peers
            foreach($data_peers as $k => $v){
                // cek status dengan melihat nik penilai dan nik dinilai
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
        
        return $data; // balikkan data
    }
    public function f360cekOtoritas($data_penilai, $nik_dinilai) { // cek otoritas terhadap karyawan
        // cek dalam 3 kodisi
        // if($data_penilai['hirarki_org'] == 'Functional-div'){
        if($data_penilai['hirarki_org'] == 'Functional'){
            //ambil data teman sebaya N-1 di divisi dan deptnya
            $data_peers = $this->f360getEmployeDetail(
                'hirarki_org = "N-1"'.
                ' AND div_id = "'.$data_penilai['div_id'].
                '" AND nik = "'.$nik_dinilai.'"'
            );
            // FIXME
            //ambil data teman sebaya Functional-div di divisi dan deptnya
            if(empty($data_peers)){
                $data_peers =$this->f360getEmployeDetail(
                    // 'hirarki_org = "Functional-div"'.
                    'hirarki_org = "Functional"'.
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
                'position.id' => $data_penilai['id_atasan1'],
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
                    // 'hirarki_org = "Functional-div"'.
                    'hirarki_org = "Functional"'.
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
                    // 'hirarki_org = "Functional-div"'.
                    'hirarki_org = "Functional"'.
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
                'position.id' => $data_penilai['id_atasan1'],
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
            'employe',
            'position',
            'position.id = employe.position_id',
            $where,
            'emp_name'
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

