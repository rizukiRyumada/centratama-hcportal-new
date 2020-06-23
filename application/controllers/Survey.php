<?php  
// TODO tambah kolom penilaian N/A = "" = null = kosong
// TODO departemen yang dia jajaki jangan di tampilkan di penilaian
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

        //load model
        
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

    // NOW
    public function excellence(){
        $departemen = $this->_general_m->getAll('*', 'survey_exc_departemen', array());

        // FIXME ambil id departemen
        $my_departemen = $this->_general_m->getJoin2tables('nama_departemen', 'position', 'departemen', 'position.dept_id = departemen.id', 'position.id='.$this->session->userdata('position_id'));
        
        // $menu = $CI->_general_m->getJoin2tables('', 'survey_user_menu', 'survey_user_menu_access', 'survey_user_menu.id_menu = survey_user_menu_access.id_menu', array('id_user' => $CI->session->userdata('role_id')));


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
        $data['survey1'] = $this->_general_m->getAll('*', 'survey_exc_pertanyaan', array('id_tipesurvey' => 'A'));
        $data['survey2'] = $this->_general_m->getAll('*', 'survey_exc_pertanyaan', array('id_tipesurvey' => 'B'));

        foreach($data['survey2'] as $k => $v){
            $data['survey2'][$k]['nama_departemen'] = $this->_general_m->getOnce('nama', 'survey_exc_departemen', array('id' => $v['id_departemen']))['nama'];
        }

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = "Survey";
        $data['load_view'] = 'survey/exc_survey_v';
        $data['custom_styles'] = array('survey_styles');
        $data['custom_script'] = array('survey/script_survey');
        
        $this->load->view('main_v', $data);
    }
    public function engagement(){
        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = "Survey";
        $data['load_view'] = 'survey/index_survey_v';
        
        $this->load->view('main_v', $data);
    }
    public function review360(){
        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = "Survey";
        $data['load_view'] = 'survey/index_survey_v';
        
        $this->load->view('main_v', $data);
    }
}

/* End of file Survey.php */


?>