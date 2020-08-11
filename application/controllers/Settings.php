<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends SuperAdminController {

    public function index() {
        echo"adminsApp";
    }

    public function adminsApp(){
        // ambil data aplikasi admin
        $data['adminsapp'] = $this->_general_m->getAll('*', 'user_adminsapp', array());
        // ambil detail icon menu
        foreach($data['adminsapp'] as $k => $v){
            $data['adminsapp'][$k]['icon'] = $this->_general_m->getOnce('icon', 'user_menu', array());
        }

        echo(json_encode($data));
        exit;

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['title'];
        $data['load_view'] = 'settings/adminapp_settings_v';
        // $data['custom_styles'] = array('survey_styles');
        // $data['custom_script'] = array('profile/script_profile');
        
        $this->load->view('main_v', $data);
    }

}

/* End of file Settings.php */
