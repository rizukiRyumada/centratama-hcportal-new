<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pmk extends SpecialUserAppController {
    // page title variable
    protected $page_title = [
        'index' => 'Penilaian Masa Kontrak',
        'assessment' => 'Assessment Form'
    ];

    protected $table = [
        'contract' => 'master_employee_contract',
        'form'     => 'pmk_form',
        'status'   => 'pmk_status'
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
        $data['userApp_admin'] = $this->userApp_admin; // flag apa dia admin atau bukan

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
        // cek akses admin
        $this->cekAkses_admin();
        // ambil bulan setelah 2 bulan lagi
        $date = strtotime("+2 month", time());
        // ambil data contract terakhir
        $data_contract = $this->db->query("SELECT nik, MAX(contract) AS contract FROM ".$this->table['contract']." GROUP BY nik ORDER BY nik")->result_array();
        // cari yg datenya udh beberapa bulan lagi
        $data_pmk = []; $x = 0; $counter_pmk = 0; $counter_new = 0;
        foreach($data_contract as $k => $v){
            // cek apa data sudah ada di ptk_form
            $vya = $this->_general_m->getRow($this->table['form'], array('nik' => $v['nik'], 'contract' => $v['contract']));
            // cek apa kontraknya mau habis dalam 2 bulan
            $result = $this->_general_m->getOnce('nik, contract', $this->table['contract'], "nik = '".$v['nik']."' AND contract = '".$v['contract']."' AND date_end <= ".$date);
            if($result)

            // cek apa ada pada 2 bulan ke depan dengan kontrak terakhir
            if(!empty($result)){
                $counter_pmk++; // counter data yg abis di 2 bulan ke depan
                // cek apa tidak ada datanya di kontrak terakhir
                if($vya == 0){
                    $counter_new++; // counter new data
                    // prepare data
                    $data_pmk[$x] = $result;
                    $data_pmk[$x]['created'] = time();
                    $data_pmk[$x]['status'] = json_encode([
                        0 => [
                            'id_status' => 1,
                            'text' => 'Form generated.'
                        ]
                    ]);
                    $data_pmk[$x]['status_now_id'] = 1;
                    // $data_pmk[$x]['pmk_id'] = ""; // pmk_id nanti setelah hc divhead melakukan pembuatan summary
                    $x++;
                } else {
                    // nothing
                }
            } else {
                //nothing
            }
        }
        // masukkan ke table pmk_form
        if(!empty($data_pmk)){
            $this->_general_m->insertAll($this->table['form'], $data_pmk);
        }

        // ambil status aktif
        $pmk_active = $this->_general_m->getAll('id_status', $this->table['status'], ['is_active' => 1]);
        $counter_active = 0;
        foreach($pmk_active as $v){
            $count_row = $this->_general_m->getRow($this->table['form'], ['status_now_id' => $v['id_status']]);
            $counter_active = $counter_active + $count_row;
        }

        // ambil counter status inactive
        $pmk_inactive = $this->_general_m->getAll('id_status', $this->table['status'], ['is_active' => 0]);
        $counter_inactive = 0;
        foreach($pmk_inactive as $v){
            $count_row = $this->_general_m->getRow($this->table['form'], ['status_now_id' => $v['id_status']]);
            $counter_inactive = $counter_inactive + $count_row;
        }

        echo(json_encode([
            'counter_pmk' => $counter_pmk,
            'counter_active' => $counter_active,
            'counter_inactive' => $counter_inactive,
            'counter_new' => $counter_new
        ]));
        // simpan data pmk di database
    }

/* -------------------------------------------------------------------------- */
/*                               OTHER FUNCTION                               */
/* -------------------------------------------------------------------------- */    
    /**
     * cek akses dengan admin previledge
     *
     * @return void
     */
    function cekAkses_admin(){
        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1){
            // perbolehkan akses
        } else {
            // tolak izin
            show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
            exit;
        }
    }

}

/* End of file Pmk.php */
