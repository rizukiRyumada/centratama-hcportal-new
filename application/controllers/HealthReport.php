<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class HealthReport extends MainController {

    public function index()
    {
        redirect('healthReport/healthCheck'); // redirect ke healthCheck
    }

    /* -------------------------------------------------------------------------- */
    /*                                MAIN FUNCTION                               */
    /* -------------------------------------------------------------------------- */
    public function healthCheck(){
        // cek apa dia sudah check kesehatan atau belum dengan nik dan tanggal
        // jika ada datanya ambil, dan buat pengaturan buat tombolnya ga bisa dicek

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
		$data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['title'];
		$data['load_view'] = 'healthreport/healthCheck_healthReport_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		$data['custom_styles'] = array('healthreport_styles');
        $data['custom_script'] = array('plugins/datatables/script_datatables');
        
		$this->load->view('main_v', $data);
    }

    public function report(){

    }

}

/* End of file HealthReport.php */
