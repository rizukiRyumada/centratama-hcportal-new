<?php
// TODO unbudgeted form position add freetext, budgeted choose position on div and dept
// TODO Work location ambil api kota di Indonesia
// TODO di tabel position tambah man power kuota => mpp
// TODO interviewer dari atasan 1 dan atasan 2, dan tambahin dari data lain

defined('BASEPATH') OR exit('No direct script access allowed');

class Ptk extends SpecialUserAppController {
    // page title
    protected $page_title = array(
        'index' => "Employee Requisition"
    );
    
    public function __construct() {
        parent::__construct();
        
    }
    

    public function index() {
        
        
        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['index'];
		$data['load_view'] = 'ptk/index_ptk_v';
		// additional styles and custom script
        // $data['additional_styles'] = array();
		// $data['custom_styles'] = array();
        // $data['custom_script'] = array('plugins/jqueryValidation/script_jqueryValidation');
        
		$this->load->view('main_v', $data);
    }

    function createNewForm(){
        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['index'];
		$data['load_view'] = 'ptk/createNew_ptk_v';
		// additional styles and custom script
        // $data['additional_styles'] = array();
		// $data['custom_styles'] = array();
        // $data['custom_script'] = array('plugins/jqueryValidation/script_jqueryValidation');
        
		$this->load->view('main_v', $data);
    }

}

/* End of file Ptk.php */
 