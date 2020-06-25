<?php  
// TODO tambah kolom penilaian N/A = "" = null = kosong
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey extends CI_Controller {
    
    public function __construct()
    {
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
        $data['page_title'] = "Service Excellence Survey";
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
        $data['page_title'] = "Employee Engagement Survey";
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
/*                              Review 360 Survey                             */
/* -------------------------------------------------------------------------- */
    public function review360(){ // Review 360 main function
        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = "360 Review";
        $data['load_view'] = 'survey/index_survey_v';
        
        $this->load->view('main_v', $data);
    }
}

/* End of file Survey.php */


?>