<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function __construct(){
        // show_error($message, $status_code, $heading = 'An Error Was Encountered')
        // echo($a);
        // show_error('error dah', 404, 'ada errrrororororororo');
        // exit;
        parent::__construct();
        // main helper
        is_logged_in(); //Cek Login
        date_default_timezone_set('Asia/Jakarta'); // set timezone

        // cek apa dia punya role maintenance
        if($this->session->userdata('role_id') != 1){
            show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
        }
    }

    public function index()
    {
        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu', array('url' => $this->uri->uri_string()))['title'];
        $data['load_view'] = 'settings/settings_v';
        // $data['custom_styles'] = array('survey_styles');
        // $data['custom_script'] = array('survey/script_survey');
        
        $this->load->view('main_v', $data);
    }
    
    public function survey(){
        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => $this->uri->uri_string()))['title'];
        $data['load_view'] = 'settings/survey_settings_v';
        // $data['custom_styles'] = array('survey_styles');
        // $data['custom_script'] = array('survey/script_survey');
        
        $this->load->view('main_v', $data);
    }

}

/* End of file Settings.php */
