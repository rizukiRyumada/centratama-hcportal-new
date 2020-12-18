<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Emp_resign extends AdminController {

    public function index()
    {
        redirect('maintenance');
        // main data
		// $data['sidebar'] = getMenu(); // ambil menu
		// $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		// $data['user'] = getDetailUser(); //ambil informasi user
		// $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['title']; // for submenu
		// $data['load_view'] = 'document/report_document_v';
		// additional styles and custom script
		// $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		// $data['custom_styles'] = array('survey_styles');
		// $data['custom_script'] = array('plugins/pdfobject/script_pdfobject.php', 'plugins/datatables/script_datatables', 'document/script_document');

		// $this->load->view('main_v', $data);
    }

}

/* End of file Emp_resign.php */
