<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class HealthReport extends MainController {

    
    public function __construct()
    {
        parent::__construct();
        // load library
        $this->load->library('form_validation');
    }
    

    public function index()
    {
        redirect('healthReport/healthStatus'); // redirect ke healthStatus
    }

    /* -------------------------------------------------------------------------- */
    /*                                MAIN FUNCTION                               */
    /* -------------------------------------------------------------------------- */
    public function healthStatus(){
        // TODO tambahkan fungsi untuk biar karyawan bisa ngisi di hari kerja aja
        // cek apa user udh isi pada tanggal segini
        $checkedIn = $this->_general_m->getOnce('*', 'healthReport_reports', array('date' => date('o-m-d', time()), 'nik' => $this->session->userdata('nik')));

        if(!empty($checkedIn) && date('N', time()) != 6 && date('N', time()) != 7){
            // beri penanda dia sudah checkedin
            $data['checkedIn'] = true;

            // set notifikasi swal
            if($checkedIn['status'] == 1){ // cek jika sehat
                $this->session->set_userdata('msg_swal',
                    array(
                        'icon' => 'success',
                        'title' => 'Success Checkin',
                        'msg' => 'Thank you! Stay safe & Healthy.'
                    )
                );
                // beri warna button
                $data['btn_healthy'] = 'bg-success';
                $data['btn_sick']    = 'bg-gray-light';
            } else { // cek jika engga
                $this->session->set_userdata('msg_swal',
                    array(
                        'icon' => 'success',
                        'title' => 'Success Checkin',
                        'msg' => 'Thank you! Get Well Soon.'
                    )
                );
                // beri warna button
                $data['btn_healthy'] = 'bg-gray-light';
                $data['btn_sick']    = 'bg-danger';
            }
        } elseif(date('N', time()) == 6 || date('N', time()) == 7){
            // beri penanda dia sudah checkedin tidak tersedia
            $data['checkedIn'] = true;
            
            // swal notifikasi
            $this->session->set_userdata('msg_swal',
                array(
                    'icon' => 'info',
                    'title' => "It's Weekend",
                    'msg' => "Hi, Thank You for your participation to checkin your health status, but it is weekend now. we don't take health checkin on weekend."
                )
            );
            // beri warna button
            $data['btn_healthy'] = 'bg-gray';
            $data['btn_sick']    = 'bg-gray';
        } else {
            // beri penanda dia belum checkedin
            $data['checkedIn'] = false;
        }

        // cek apa dia sudah check kesehatan atau belum dengan nik dan tanggal
        // jika ada datanya ambil, dan buat pengaturan buat tombolnya ga bisa dicek

        // health data
        $data['sick_categories'] = $this->_general_m->getAll('*', 'healthReport_category', array()); // ambil kategori sakit

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
		$data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['title'];
		$data['load_view'] = 'healthreport/healthStatus_healthReport_v';
		// additional styles and custom script
        // $data['additional_styles'] = array();
		$data['custom_styles'] = array('healthreport_styles');
        $data['custom_script'] = array('plugins/jqueryValidation/script_jqueryValidation', 'healthreport/script_index_healthreport');
        
		$this->load->view('main_v', $data);
    }

    public function report(){
        // ambil data divisi dan departemen
        $this->load->model('Jobpro_model');
        $data['dept'] = $this->Jobpro_model->getAllAndOrder('nama_departemen', 'departemen');
        $data['divisi'] = $this->Jobpro_model->getAllAndOrder('division', 'divisi');
        $data['sick_categories'] = $this->_general_m->getAll('*', 'healthReport_category', array()); // get sick categories

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
		$data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['title'];
		$data['load_view'] = 'healthreport/report_healthReport_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		$data['custom_styles'] = array('healthreport_report_styles');
        $data['custom_script'] = array(
            'plugins/datatables/script_datatables', 
            'plugins/chartjs/script_chartjs.php', 
            'plugins/daterange-picker/script_daterange-picker', 
            'healthreport/script_report_healthreport'
        );
        
		$this->load->view('main_v', $data);
    }

    /* -------------------------------------------------------------------------- */
    /*                                MAIN METHODS                                */
    /* -------------------------------------------------------------------------- */
    public function submitCheckIn(){
        if($this->input->post('checkIn') == '1'){
            // siapin data buat dimasukkin ke database
            $data = array(
                'date'     => date('o-m-d', time()),
                'nik'      => $this->session->userdata('nik'),
                'id_posisi'=> $this->_general_m->getOnce('position_id', 'employe', array('nik' => $this->session->userdata('nik')))['position_id'],
                'time'     => date('H:i:s', time()),
                'status'   => $this->input->post('checkIn'),
                'sickness' => null,
                'notes'    => null
            );
        } else {
            // ambil id_posisi


            // ambil kategori sakit
            $sickness = $this->_general_m->getAll('*', 'healthReport_category', array());
            
            // ambil status sickness
            $sickness_status = array();
            foreach($sickness as $k => $v){
                $sickness_status[$k]['name'] = $v['input_name'];
                $sickness_status[$k]['status'] = filter_var($this->input->post($v['input_name']), FILTER_VALIDATE_BOOLEAN);

                if(array_key_last($sickness) == $k){
                    $sickness_status[$k+1]['name'] = 'lainnya';
                    $sickness_status[$k+1]['status'] = $this->input->post('lainnya');
                }
            }

            // cek apa user tidak mengisi satupun sickness card ataupun yang lainnya
            foreach($sickness_status as $v){
                if(!empty($v['status'])){
                    $validate_sickness = 1;
                }
            }
            if(empty($validate_sickness)){ // jika validate sickness kosong
                // set notifikasi swal
                $this->session->set_flashdata('msg_swal',
                    array(
                        'icon' => 'error',
                        'title' => 'Failed Checkin',
                        'msg' => 'Please choose at least one of your sickness or type on other.'
                    )
                );
                redirect('healthReport/healthStatus');
            }


            $this->form_validation->set_rules('notes', 'Notes', 'required');
            if ($this->form_validation->run() == FALSE){
                 // set notifikasi swal
                 $this->session->set_flashdata('msg_swal',
                    array(
                        'icon'  => 'error',
                        'title' => 'Failed Checkin',
                        'msg'   => 'Please write your sick notes.'
                    )
                );
                redirect('healthReport/healthStatus');
            }

            $data = array(
                'date'     => date('o-m-d', time()),
                'nik'      => $this->session->userdata('nik'),
                'id_posisi'=> $this->_general_m->getOnce('position_id', 'employe', array('nik' => $this->session->userdata('nik')))['position_id'],
                'time'     => date('H:i:s', time()),
                'status'   => $this->input->post('checkIn'),
                'sickness' => json_encode($sickness_status),
                'notes'    => $this->input->post('notes')
            );
        }
        
        $this->_general_m->insert('healthReport_reports', $data);

        redirect('healthReport/healthStatus');
    }

    /* -------------------------------------------------------------------------- */
    /*                                AJAX METHODS                                */
    /* -------------------------------------------------------------------------- */
    // public function ajaxGetEmployee(){
    //     // pisahkan tanda - dan ambil id nya
    //     $divisi = explode('-', $this->input->post('divisi'));
    //     $departemen = explode('-', $this->input->post('departemen'));

    //     // cari position id dengan id divisi dan departemen segitu di tabel posisi
    //     $id_posisi = $this->_general_m->getAll('id', 'position', array('div_id' => $divisi[1], 'dept_id' => $departemen[1]));

    //     // cari semua employee di setiap id posisi
    //     $employee = [];
    //     foreach($id_posisi as $k => $v){
    //         $employee[$k] = $this->_general_m->getAll('nik, emp_name', 'employe', array($v['id']));
    //     }

    //     print_r($employee);
    // }
    // TODO tambah penanda buat nampilin data dia doang kalo dia bukan admin dan userapp admin
    public function ajax_getReportData(){
        // siapkan variable where
        $where = '';
        
        // ambil divisi
        if(!empty($this->input->post('divisi'))){
            $where .= 'div_id = "'.explode('-', $this->input->post('divisi'))[1].'" AND ';
        }
        // ambil departemen
        if(!empty($this->input->post('departemen'))){
            $where .= 'dept_id = "'.explode('-', $this->input->post('departemen'))[1].'" AND ';
        }
        // ambil date range
        $daterange = explode(' - ', $this->input->post('daterange'));
        foreach($daterange as $k => $v){
            $daterange[$k] = date('Y-m-d', strtotime($v));
        }

        $where .= 'date >= "'.$daterange[0].'" AND date <= "'.$daterange[1].'"';

        // ambil data health
        $data_health = $this->_general_m->getJoin2tablesOrderDescend(
            'healthReport_reports.date, healthReport_reports.nik, healthReport_reports.time, healthReport_reports.status, healthReport_reports.sickness, healthReport_reports.notes',
            'healthReport_reports',
            'position',
            'healthReport_reports.id_posisi = position.id',
            $where,
            'date'
        );

        // $nik_employee = $this->session->userdata('nik');

        // fillter start date & end date
        // $start_date = "2020-07-18";
        // $end_date = "2020-07-21";

        // ambil data dari table
        // $data_health = $this->_general_m->getAllOrderDescend('*', 'healthReport_reports', 'date >= "'.$start_date.'" AND date <= "'.$end_date.'"', 'date');

        // dapatkan semua kategori sakit, ambil input_name nya
        $counter_kategori = $this->_general_m->getAll('name, input_name', 'healthReport_category', array());
        // inisialisasi dengan angka nol di setiap kategorinya
        foreach($counter_kategori as $k => $v){
            $counter_kategori[$k]['counter'] = 0;
        }
        $counter_kategori[array_key_last($counter_kategori)+1] = array(
            'name' => 'Lainnya',
            'input_name' => 'lainnya',
            'counter' => 0
        );

        // variable initialisation
        $counter_sehat = 0; $counter_sakit = 0; 
        foreach($data_health as $k => $v){
            // tambah informasi karyawan
            $employe = $this->_general_m->getOnce('position_id, emp_name', 'employe', array('nik' => $v['nik']));
            $data_health[$k]['detail_position'] = $this->getPositionDetails($employe['position_id']);
            $data_health[$k]['emp_name'] = $employe['emp_name'];

            // gabungkan date dan time
            $data_health[$k]['date'] = $v['date']." ".$v['time'];

            // hapus array kategori sakit kalo dia tidak ada statusnya dan berarti dia tidak sehat
            if(!empty($v['sickness'])){                
                //decode sickness
                $sickness = json_decode($v['sickness'], true);
                // siapkan variable penampung sickness
                $sicked = array(); $x = 0;
                foreach($sickness as $key => $value){
                    // ambil info kategori sakit terdaftar
                    if($value['name'] != 'lainnya'){
                        $what_sickness = $this->_general_m->getOnce('name', 'healthReport_category', array('input_name' => $value['name']));
                    }
                    // untuk nama kategori sakit yang terdaftar
                    if(!empty($value['status']) && $value['name'] != 'lainnya' && !empty($what_sickness)){
                        $sicked[$x] = $what_sickness['name'];
                        $x++;

                        // counter jenis sakit
                        foreach($counter_kategori as $k_kategori => $v_kategori){
                            if($v_kategori['input_name'] == $value['name']){
                                $counter_kategori[$k_kategori]['counter']++;
                            }  
                        }
                    // kategori sakit lainnya
                    } elseif(!empty($value['status']) && $value['name'] == 'lainnya'){
                        $sicked[$x] = $value['status'] ;
                        $x++;

                        // counter jenis sakit
                        foreach($counter_kategori as $k_kategori => $v_kategori){
                            if($v_kategori['input_name'] == $value['name']){
                                $counter_kategori[$k_kategori]['counter']++;
                            }  
                        }
                    }
                }
                // gabungkan dan beri koma antara nama sakit
                $sicked = implode(', ', $sicked);
                $data_health[$k]['sickness'] = $sicked; // replace data sickness

                // count sakit
                $counter_sakit++;
            } else {
                // count sehat
                $counter_sehat++;
            }
        }

        // ubah status jadi text healthy dan sick
        foreach($data_health as $k => $v){
            if($data_health[$k]['status'] == 0){
                $data_health[$k]['status'] = 'Sick';
            } else {
                $data_health[$k]['status'] = 'Healthy';
            }
        }
        
        // bentuk jadi json dan tampilkan
        echo(json_encode(array(
            'data' => $data_health,
            'counter_sakit' => $counter_sakit,
            'counter_sehat' => $counter_sehat,
            'counter_kategori' => $counter_kategori
        )));
    }

    /* -------------------------------------------------------------------------- */
    /*                                   OTHERS                                   */
    /* -------------------------------------------------------------------------- */
    function getPositionDetails($id_posisi){
        // load model Job Profile
        $this->load->model('Jobpro_model');

        $temp_posisi = $this->Jobpro_model->getDetail("div_id, dept_id, id", "position", array('id' => $id_posisi));
        // print_r($temp_posisi);
        foreach ($this->Jobpro_model->getDetail("position_name", "position", array('id' => $temp_posisi['id'])) as $v){// tambahkan nama posisi
            $detail_posisi['posisi'] = $v;
        }
        foreach($this->Jobpro_model->getDetail("nama_departemen", "departemen", array('id' => $temp_posisi['dept_id'])) as $v){// tambahkan nama departemen
            $detail_posisi['departement'] = $v;
        }
        foreach($this->Jobpro_model->getDetail("id", "departemen", array('id' => $temp_posisi['dept_id'])) as $v){// tambahkan id departemen
            $detail_posisi['id_dept'] = $v;
        }
        foreach($this->Jobpro_model->getDetail("division", "divisi", array('id' => $temp_posisi['div_id'])) as $v){// tambahkan nama divisi
            $detail_posisi['divisi'] = $v;
        }
        foreach($this->Jobpro_model->getDetail("id", "divisi", array('id' => $temp_posisi['div_id'])) as $v){// tambahkan id divisi
            $detail_posisi['id_div'] = $v;
        }
        return $detail_posisi;
    }

    public function test(){
        echo(date("j M o" ,time()));
        // echo(date('j M o', 1594892256))
                        //    9999999999
    }

}

/* End of file HealthReport.php */
