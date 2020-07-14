<?php defined('BASEPATH') OR exit('No direct script access allowed');

class AppSettings extends SuperAdminController {

    public function __construct(){
        // show_error($message, $status_code, $heading = 'An Error Was Encountered')
        // echo($a);
        // show_error('error dah', 404, 'ada errrrororororororo');
        // exit;
        parent::__construct();
    }

    public function index()
    {
        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu', array('url' => $this->uri->uri_string()))['title'];
        $data['load_view'] = 'appsettings/appsettings_v';
        // $data['custom_styles'] = array('survey_styles');
        // $data['custom_script'] = array('survey/script_survey');
        
        $this->load->view('main_v', $data);
    }

    public function jobProfile(){
        // $data = [
        //     'title' => 'Job Profile',
        //     'user' => $this->db->get_where('employe', ['nik' => $this->session->userdata('nik')])->row_array(),
        //     'divisi' => $this->Divisi_model->getAll(),
        //     'div_head' => $this->Divisi_model->getDivByOrg(),
        //     'status_time' => $this->Jobpro_model->getDetails('*', 'jobprofile_setting-notifstatus', array())
        // ];
        // $this->load->view('templates/user_header', $data);
        // $this->load->view('templates/user_sidebar', $data);
        // $this->load->view('templates/user_topbar', $data);
        // $this->load->view('settings/job_profile_s', $data);
        // $this->load->view('templates/report_footer');
        
        // jobprofile data
        $data = [
            'status_time' => $this->_general_m->getAll('*', 'jobprofile_setting-notifstatus', array())
        ];

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['title'];
        $data['load_view'] = 'appsettings/jobprofile_appsettings_v';
        // $data['custom_styles'] = array('survey_styles');
        $data['custom_script'] = array('appsettings/script_appsettings');
        
        $this->load->view('main_v', $data);
    }
    
    /** Survey App Settings
     * survey
     *
     * @return void
     */
    public function survey(){
        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => $this->uri->uri_string()))['title'];
        $data['load_view'] = 'appsettings/survey_appsettings_v';
        // $data['custom_styles'] = array('survey_styles');
        // $data['custom_script'] = array('survey/script_survey');
        
        $this->load->view('main_v', $data);
    }

}

/* End of file Settings.php */
