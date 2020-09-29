<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pmk extends SpecialUserAppController {
    // page title variable
    protected $page_title = [
        'index' => 'Penilaian Masa Kontrak',
        'assessment' => 'Assessment Form'
    ];

    protected $table = [
        'contract' => 'master_employee_contract'
    ];
    
    public function __construct()
    {
        parent::__construct();
        // load models
        $this->load->model(['divisi_model', 'employee_m']);
    }
    

/* -------------------------------------------------------------------------- */
/*                                MAIN FUNCTION                               */
/* -------------------------------------------------------------------------- */
    
    /**
     * index page of PMK Module
     *
     * @return void
     */
    public function index()
    {
        // pmk data
        $data_divisi = $this->divisi_model->getAll(); // get all data divisi
        foreach($data_divisi as $k => $v){
            $data_divisi[$k]['emp_total'] = $this->employee_m->count_where(['div_id' => $v['id']]);
        }

        $data['divisi'] = $data_divisi;

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['index'];
		$data['load_view'] = 'pmk/index_pmk_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		// $data['custom_styles'] = array();
        $data['custom_script'] = array(
            'plugins/datatables/script_datatables',
            'pmk/script_index_pmk'
        );
        
		$this->load->view('main_v', $data);
    }

    public function assessment(){
        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->page_title['assessment'];
		$data['load_view'] = 'pmk/assessment_pmk_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		$data['custom_styles'] = array('pmk_styles', 'survey_styles');
        $data['custom_script'] = array(
            'plugins/datatables/script_datatables'
            // 'pmk/script_index_pmk',
        );
        
		$this->load->view('main_v', $data);
    }
    
    /**
     * summary function to view per division
     *
     * @return void
     */
    function summary(){
        echo($this->input->get('div'));
    }

/* -------------------------------------------------------------------------- */
/*                                AJAX FUNCTION                               */
/* -------------------------------------------------------------------------- */
    
    /**
     * refresh karyawan kontrak yang bulan -2 selesai
     *
     * @return void
     */
    function pmk_refresh() {
        // ambil bulan setelah 2 bulan lagi
        $date = strtotime("+2 month", time());
        // ambil data contract terakhit dan yg setelah 2 bulan lagi selesai
        // SELECT MAX(Price) AS LargestPrice FROM Products;
        $data_contract = $this->db->query("SELECT nik, MAX(contract) AS contract FROM ".$this->table['contract']." GROUP BY nik ORDER BY nik")->result_array();
        // cari yg datenya udh beberapa bulan lagi
        $data_pmk = [];
        // foreach($data_contract as $k => $v){
        //     $data_pmk[$k] = $this->_general_m->getOnce('nik');
        // }
        // liat di
        // WHERE date_end <= ".$date." GROUP BY nik ORDER BY nik"
        // ambil nik dan buatkan id pmk
        print_r($data_contract);
        
        // simpan data pmk di database
    }

}

/* End of file Pmk.php */
