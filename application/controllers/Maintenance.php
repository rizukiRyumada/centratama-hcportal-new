<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Maintenance extends MainController {

    public function index()
    {
        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = 'Maintenance';
		$data['load_view'] = 'maintenance_v';
		// additional styles and custom script
        // $data['additional_styles'] = array();
		$data['custom_styles'] = array();
        $data['custom_script'] = array();
        
		$this->load->view('main_v', $data);
    }

}

/* End of file Maintenance.php */
