<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class HealthReport extends MainController {
    protected $table_position = 'master_position';
    protected $table_hr_category = 'healthreport_category';
    protected $table_hr_reports = 'healthreport_reports';
    
    public function __construct()
    {
        parent::__construct();
        // load library
        $this->load->library(['form_validation', 'tablename']);
        // cari nama table yang terupdate
        $this->table_position = $this->tablename->get($this->table_position);
    }
    
    public function index()
    {
        redirect('healthReport/healthStatus'); // redirect ke healthStatus
    }

    /* -------------------------------------------------------------------------- */
    /*                                MAIN FUNCTION                               */
    /* -------------------------------------------------------------------------- */
    public function healthStatus(){
        // cek apa user udh isi pada tanggal segini
        $checkedIn = $this->_general_m->getOnce('*', $this->table_hr_reports, array('date' => date('Y-m-d', time()), 'nik' => $this->session->userdata('nik')));

        if(!empty($checkedIn)){
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
        // } elseif(date('N', time()) == 6 || date('N', time()) == 7){
        //     // beri penanda dia sudah checkedin tidak tersedia
        //     $data['checkedIn'] = true;
            
        //     // swal notifikasi
        //     $this->session->set_userdata('msg_swal',
        //         array(
        //             'icon' => 'info',
        //             'title' => "It's Weekend",
        //             'msg' => "Hi, Thank You for your participation to checkin your health status, but it is weekend now. we don't take health checkin on weekend."
        //         )
        //     );
        //     // beri warna button
        //     $data['btn_healthy'] = 'bg-gray';
        //     $data['btn_sick']    = 'bg-gray';
        } else {
            // beri penanda dia belum checkedin
            $data['checkedIn'] = false;
        }

        // cek apa dia sudah check kesehatan atau belum dengan nik dan tanggal
        // jika ada datanya ambil, dan buat pengaturan buat tombolnya ga bisa dicek

        // health data
        $data['sick_categories'] = $this->_general_m->getAll('*', $this->table_hr_category, array()); // ambil kategori sakit

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['title'];
        $data['userApp_admin'] = $this->userApp_admin;
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
        $data['dept'] = $this->Jobpro_model->getAllAndOrder('nama_departemen', 'master_department');
        $data['divisi'] = $this->Jobpro_model->getAllAndOrder('division', 'master_division');
        $data['sick_categories'] = $this->_general_m->getAll('*', $this->table_hr_category, array()); // get sick categories

        // cek apa dia hirarki N dan N-1
        if(!empty($this->is_divhead())){
            $data['is_divhead'] = true;
        } else {
            $data['is_divhead'] = false;
        }
        if(!empty($this->is_depthead())){
            $data['is_depthead'] = true;
        } else {
            $data['is_depthead'] = false;
        }

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['title'];
        $data['userApp_admin'] = $this->userApp_admin;
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
                'date'     => date('Y-m-d', time()),
                'nik'      => $this->session->userdata('nik'),
                'id_posisi'=> $this->_general_m->getOnce('position_id', 'master_employee', array('nik' => $this->session->userdata('nik')))['position_id'],
                'time'     => date('H:i:s', time()),
                'status'   => $this->input->post('checkIn'),
                'sickness' => null,
                'notes'    => null
            );
        } else {
            // ambil id_posisi


            // ambil kategori sakit
            $sickness = $this->_general_m->getAll('*', $this->table_hr_category, array());
            
            // ambil status sickness
            $sickness_status = array();
            foreach($sickness as $k => $v){
                $sickness_status[$k]['name'] = $v['input_name'];
                $sickness_status[$k]['status'] = filter_var($this->input->post($v['input_name']), FILTER_VALIDATE_BOOLEAN);

                if(array_key_last($sickness) == $k){
                    $sickness_status[$k+1]['name'] = 'other';
                    $sickness_status[$k+1]['status'] = $this->input->post('other');
                }
            }

            // cek apa user tidak mengisi satupun sickness card ataupun yang other
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
                'date'     => date('Y-m-d', time()),
                'nik'      => $this->session->userdata('nik'),
                'id_posisi'=> $this->_general_m->getOnce('position_id', 'master_employee', array('nik' => $this->session->userdata('nik')))['position_id'],
                'time'     => date('H:i:s', time()),
                'status'   => $this->input->post('checkIn'),
                'sickness' => json_encode($sickness_status),
                'notes'    => $this->input->post('notes')
            );
        }
        
        $this->_general_m->insert($this->table_hr_reports, $data);

        redirect('healthReport/healthStatus');
    }

    /* -------------------------------------------------------------------------- */
    /*                                AJAX METHODS                                */
    /* -------------------------------------------------------------------------- */
    public function ajax_getPieChartData() {
        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1){
            // nothing
        } else {
            show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
        }

        // siapkan variable where
        $where = '';
        
        // ambil divisi
        if(!empty($this->input->post('divisi'))){
            $where .= 'div_id = "'.explode('-', $this->input->post('divisi'))[1].'" AND ';
            $divisi = explode('-', $this->input->post('divisi'))[1];        
        } else {
            $divisi = "";
        }
        // ambil departemen
        if(!empty($this->input->post('departemen'))){
            $where .= 'dept_id = "'.explode('-', $this->input->post('departemen'))[1].'" AND ';
            $departemen = explode('-', $this->input->post('departemen'))[1];        
        } else {
            $departemen = "";
        }

        // kumpulkan semua where
        $where_hs = ' AND '.$where.'date = "'.$this->input->post('date').'"';

        // ambil semua nik karyawan
        $data_nik = $this->_general_m->getAll('nik', 'master_employee', array());

        // ambil data health buat ngitung kategori sakit
        $data_health = $this->getDataHealth(
            $where,
            $data_nik,
            [$this->input->post('date')]
        );
        
        // get populasi chart
        $populasi_hs = $this->getPopulasiHS("", $divisi, $departemen);

        echo(json_encode($this->getChartData($where_hs, $data_health, $populasi_hs)));
    }

    function getDatesFromRange($start, $end, $format = 'Y-m-d') { 
        // Declare an empty array 
        $array = array(); 
          
        // Variable that store the date interval 
        // of period 1 day 
        $interval = new DateInterval('P1D'); 
      
        $realEnd = new DateTime($end); 
        $realEnd->add($interval); 
      
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
      
        // Use loop to store date into array 
        foreach($period as $date) {                  
            $array[] = $date->format($format);  
        } 
      
        // Return the array elements 
        return $array; 
    } 
      
    public function ajax_getReportData(){
        // siapkan variable where
        $where = '';
        
        // ambil divisi
        if(!empty($this->input->post('divisi'))){
            $where .= 'div_id = "'.explode('-', $this->input->post('divisi'))[1].'" AND ';
            $divisi = explode('-', $this->input->post('divisi'))[1];
        } else {
            $divisi = "";
        }
        // ambil departemen
        if(!empty($this->input->post('departemen'))){
            $where .= 'dept_id = "'.explode('-', $this->input->post('departemen'))[1].'" AND ';
            $departemen = explode('-', $this->input->post('departemen'))[1];
        } else {
            $departemen = "";
        }

        // ambil date range
        $daterange = explode(' - ', $this->input->post('daterange'));
        foreach($daterange as $k => $v){
            $daterange[$k] = date('Y-m-d', strtotime($v));
        }
        // ambil tanggal di setiap hari
        $daterange_days = $this->getDatesFromRange($daterange[0], $daterange[1]);

        // langkah khusus bukan admin dan admin
        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1){
            // ambil data semua nik jika admin
            $data_nik = $this->_general_m->getAll('nik', 'master_employee', array());
            
            // ambil data chart buat diagram pie health sick
            $where_hs = ' AND '.$where.'date = "'.$daterange[1].'"';
            // $where_sc = 'date = "'.$daterange[1].'"';
            
            $populasi_hs = $this->getPopulasiHS("", $divisi, $departemen);

            // ambil data health
            $data_health = $this->getDataHealth(
                $where,
                $data_nik,
                $daterange_days
            );
            // ambil data health buat chart
            $data_health_chart = $this->getDataHealth(
                '',
                $data_nik,
                [$daterange[1]]
            );

            // AMBIL DATA buat chart batang per hari
            $data_health_daily = $daterange_days;
            // ambil data health untuk per hari
            foreach($data_health_daily as $k => $v){
                // declare array
                $data_health_daily[$k] = array();
                $data_health_daily[$k]['date'] = $v;
                // ambil data
                // $data_health_daily[$k]['data_sakit'] = $this->_general_m->getRow($this->table_hr_reports, array('status' => 0, 'date' => $v));
                $data_health_daily[$k]['data_sakit'] = count($this->_general_m->getJoin2tables(
                    $this->table_hr_reports .'.date, '. $this->table_hr_reports .'.nik',
                    $this->table_hr_reports,
                    $this->table_position,
                    $this->table_hr_reports .'.id_posisi = '. $this->table_position .'.id',
                    $where.'status = 0 AND date = "'.$v.'"',
                ));
                $data_health_daily[$k]['data_sehat'] = count($this->_general_m->getJoin2tables(
                    $this->table_hr_reports .'.date, '. $this->table_hr_reports .'.nik',
                    $this->table_hr_reports,
                    $this->table_position,
                    $this->table_hr_reports .'.id_posisi = '. $this->table_position .'.id',
                    $where.'status = 1 AND date = "'.$v.'"',
                ));
                // $data_health_daily[$k]['data_sehat'] = $this->_general_m->getRow($this->table_hr_reports, array('status' => 1, 'date' => $v));
                $data_health_daily[$k]['data_kosong'] = $populasi_hs - ($data_health_daily[$k]['data_sakit'] + $data_health_daily[$k]['data_sehat']);
            }
        } else {
            // data nik
            $data_nik = $this->getDataNik();

            // siapkan where
            $where_hs = ' AND nik = "'.$this->session->userdata('nik').'" AND date >= "'.$daterange[0].'" AND date <= "'.$daterange[1].'"';
            // $where_sc = 'nik = "'.$this->session->userdata('nik').'" AND date >= "'.$daterange[0].'" AND date <= "'.$daterange[1].'"';
            
            $populasi_hs = $this->getPopulasiHS($daterange_days);
            // ambil data health
            $data_health = $this->getDataHealth(
                '',
                $data_nik,
                $daterange_days
            );
            // ambil data health buat chart
            $data_health_chart = $this->getDataHealth(
                '',
                $data_nik,
                $daterange_days
            );

            if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1 || !empty($this->is_depthead()) || !empty($this->is_divhead())){
                // AMBIL DATA buat chart batang per hari
                $data_health_daily = $daterange_days;
                // ambil data health untuk per hari
                foreach($data_health_daily as $k => $v){
                    // declare array
                    $data_health_daily[$k] = array();
                    $data_health_daily[$k]['date'] = $v;

                    // cari di setiap nik yang didapat
                    $data_health_daily[$k]['data_sakit'] = $data_health_daily[$k]['data_sehat'] = 0;
                    foreach($data_nik as $value){
                        $data_sakit = $this->_general_m->getJoin2tables(
                            $this->table_hr_reports .'.date, '. $this->table_hr_reports .'.nik',
                            $this->table_hr_reports,
                            $this->table_position,
                            $this->table_hr_reports .'.id_posisi = '. $this->table_position .'.id',
                            'status = 0 AND date = "'.$v.'" AND nik = "'.$value['nik'].'"'
                        );
                        if(!empty($data_sakit)){
                            $data_health_daily[$k]['data_sakit']++;
                        }

                        $data_sehat = $this->_general_m->getJoin2tables(
                            $this->table_hr_reports .'.date, '. $this->table_hr_reports .'.nik',
                            $this->table_hr_reports,
                            $this->table_position,
                            $this->table_hr_reports .'.id_posisi = '. $this->table_position .'.id',
                            'status = 1 AND date = "'.$v.'" AND nik = "'.$value['nik'].'"'
                        );
                        if(!empty($data_sehat)){
                            $data_health_daily[$k]['data_sehat']++;
                        }
                    }
                    
                    // $data_health_daily[$k]['data_sehat'] = $this->_general_m->getRow($this->table_hr_reports, array('status' => 1, 'date' => $v));
                    $data_health_daily[$k]['data_kosong'] = count($data_nik) - ($data_health_daily[$k]['data_sakit'] + $data_health_daily[$k]['data_sehat']);
                }
            }
        }

        // masukkan ke dalam variabel dan kosongkan bila bukan admin
        if(!empty($data_health_daily)){
            $hd_bar = $data_health_daily;
        } else {
            $hd_bar = "";
        }

        // ambil data diagram pie
        $data_chart = $this->getChartData($where_hs, $data_health_chart, $populasi_hs);

        // $where .= 'date >= "'.$daterange[0].'" AND date <= "'.$daterange[1].'"';

        echo(json_encode(array(
            'data' => $data_health,
            'hs_pie' => $data_chart['hs_pie'],
            'sc_pie' => $data_chart['sc_pie'],
            'hd_bar' => $hd_bar
        )));
        exit;
    }

    /* -------------------------------------------------------------------------- */
    /*                                   OTHERS                                   */
    /* -------------------------------------------------------------------------- */
    function getChartData($where_hs, $data_health, $populasi_hs){
        // ambil jumlah row dari masing-masing status
        // $hs_pie['sakit'] = $this->_general_m->getRow($this->table_hr_reports, 'status = 0 '.$where_hs);
        $hs_pie['sakit'] = count($this->_general_m->getJoin2tablesOrder(
            $this->table_hr_reports .'.date, '. $this->table_hr_reports .'.nik, '. $this->table_hr_reports .'.time, '. $this->table_hr_reports .'.status, '. $this->table_hr_reports .'.sickness, '. $this->table_hr_reports .'.notes',
            $this->table_hr_reports,
            $this->table_position,
            $this->table_hr_reports .'.id_posisi = '. $this->table_position .'.id',
            'status = 0 '.$where_hs,
            'date'
        ));
        // $hs_pie['sehat'] = $this->_general_m->getRow($this->table_hr_reports, 'status = 1 '.$where_hs);
        $hs_pie['sehat'] = count($this->_general_m->getJoin2tablesOrder(
            $this->table_hr_reports .'.date, '. $this->table_hr_reports .'.nik, '. $this->table_hr_reports .'.time, '. $this->table_hr_reports .'.status, '. $this->table_hr_reports .'.sickness, '. $this->table_hr_reports .'.notes',
            $this->table_hr_reports,
            $this->table_position,
            $this->table_hr_reports .'.id_posisi = '. $this->table_position .'.id',
            'status = 1 '.$where_hs,
            'date'
        ));
        // ambil jumlah semua employe kemudian kurangi dengan data sakit+data sehat
        $hs_pie['kosong'] = $populasi_hs - ($hs_pie['sakit'] + $hs_pie['sehat']);
        
        // ambil semua kategori sakit
        $sc_pie = $this->_general_m->getAll('input_name, name', $this->table_hr_category, array());
        // inisialisasi dengan angka nol di setiap kategorinya
        foreach($sc_pie as $k => $v){
            $sc_pie[$k]['counter'] = 0;
        }
        // tambah kategori other
        $sc_pie[array_key_last($sc_pie) + 1] = array(
            'name' => 'other',
            'input_name' => 'other',
            'counter' => 0
        );
        
        // cari di setiap data health data kategori sakit
        foreach($data_health as $k => $v){            
            // untuk nama kategori sakit yang terdaftar
            if(!empty($v['sickness'])){
                // pecahkan date dan ambil nik bawa sickness dari database
                $date = explode(' ', $v['date']);
                //decode sickness
                $sickness = json_decode($this->_general_m->getOnce('sickness', $this->table_hr_reports, array('nik' => $v['nik'], 'date' => $date[0]))['sickness'], true);
                // count sickness di tiap sick category
                foreach($sickness as $key => $value){
                    // ambil info kategori sakit terdaftar
                    // if($value['name'] != 'other'){
                    //     $what_sickness = $this->_general_m->getOnce('name', $this->table_hr_category, array('input_name' => $value['name']));
                    // }
                    // untuk nama kategori sakit yang terdaftar
                    if(!empty($value['status']) && $value['name'] != 'other'){
                        // $sicked[$x] = $what_sickness['name'];
                        // $x++;

                        // counter jenis sakit
                        foreach($sc_pie as $k_kategori => $v_kategori){
                            if($v_kategori['input_name'] == $value['name']){
                                $sc_pie[$k_kategori]['counter']++;
                            }  
                        }
                    // kategori sakit other
                    } elseif(!empty($value['status']) && $value['name'] == 'other'){
                        // $sicked[$x] = $value['status'] ;
                        // $x++;

                        // counter jenis sakit
                        foreach($sc_pie as $k_kategori => $v_kategori){
                            if($v_kategori['input_name'] == $value['name']){
                                $sc_pie[$k_kategori]['counter']++;
                            }  
                        }
                    }
                }
                // gabungkan dan beri koma antara nama sakit
                // $sicked = implode(', ', $sicked);
                // $data_health[$k]['sickness'] = $sicked; // replace data sickness

                // count sakit
                // $counter_sakit++;
            } else {
                // count sehat
                // $counter_sehat++;
            }
        }

        // balikkan nilai
        return array(
            'hs_pie' => $hs_pie,
            'sc_pie' => $sc_pie
        );
    }

    function getDataHealth($where, $data_nik, $daterange_days){
        // siapkan variabel data nik
        $data_health = array(); $y = 0;
        // tiap hari
        foreach($daterange_days as $v){
            // tiap nik
            foreach($data_nik as $key => $value){
                $where_data_health = $where.'nik = "'.$value['nik'].'" AND date = "'.$v.'"'; // gabungkan dengan where sebelumnya

                // ambil hasilnya
                $hasil = $this->_general_m->getJoin2tablesOrderDescend(
                    $this->table_hr_reports .'.date, '. $this->table_hr_reports .'.nik, '. $this->table_hr_reports .'.time, '. $this->table_hr_reports .'.status, '. $this->table_hr_reports .'.sickness, '. $this->table_hr_reports .'.notes',
                    $this->table_hr_reports,
                    $this->table_position,
                    $this->table_hr_reports .'.id_posisi = '. $this->table_position .'.id',
                    $where_data_health,
                    'date'
                );

                // jika ada datanya simpan dalam variabel
                if(!empty($hasil)){
                    foreach($hasil as $kunci => $nilai){
                        // ambil detail master_employee
                        $employe = $this->_general_m->getOnce('position_id, emp_name', 'master_employee', array('nik' => $nilai['nik']));
                        $hasil[$kunci]['detail_position'] = $this->getPositionDetails($employe['position_id']);
                        $hasil[$kunci]['emp_name'] = $employe['emp_name'];

                        // gabungkan date dan time
                        $hasil[$kunci]['date'] = $nilai['date']." ".$nilai['time'];

                        // ubah status jadi text healthy dan sick
                        if($nilai['status'] == 0){
                            $hasil[$kunci]['status'] = 'Unwell';
                        } else {
                            $hasil[$kunci]['status'] = 'Healthy';
                        }

                        // hapus array kategori sakit kalo dia tidak ada statusnya dan berarti dia tidak sehat
                        if(!empty($nilai['sickness'])){                
                            //decode sickness
                            $sickness = json_decode($nilai['sickness'], true);
                            // siapkan variable penampung sickness
                            $sicked = array(); $x = 0;
                            foreach($sickness as $sicknesess){
                                // ambil info kategori sakit terdaftar
                                if($sicknesess['name'] != 'other'){
                                    $what_sickness = $this->_general_m->getOnce('name', $this->table_hr_category, array('input_name' => $sicknesess['name']));
                                }
                                // untuk nama kategori sakit yang terdaftar
                                if(!empty($sicknesess['status']) && $sicknesess['name'] != 'other' && !empty($what_sickness)){
                                    $sicked[$x] = $what_sickness['name'];
                                    $x++;

                                // kategori sakit other
                                } elseif(!empty($sicknesess['status']) && $sicknesess['name'] == 'other'){
                                    $sicked[$x] = $sicknesess['status'] ;
                                    $x++;
                                }
                            }
                            // gabungkan dan beri koma antara nama sakit
                            $sicked = implode(', ', $sicked);
                            $hasil[$kunci]['sickness'] = $sicked; // replace data sickness
                        } else {
                            // nothing
                        }

                        // masukkan data
                        $data_health[$y] = $hasil[$kunci];
                        $y++;
                    }
                } else {
                    // // cek apa data karyawan dengan filter dipilih benar
                    // $cekdata = $this->_general_m->getJoin2tables(
                    //     'master_employee.nik',
                    //     'master_employee',
                    //     $this->table_position,
                    //     'master_employee.position_id = '. $this->table_position .'.id',
                    //     $where.'nik = "'.$value['nik'].'"'
                    // );
                    // if(!empty($cekdata)){
                    //     // ambil detail master_employee
                    //     $employe = $this->_general_m->getOnce('position_id, emp_name', 'master_employee', array('nik' => $value['nik']));
                    //     $hasil['detail_position'] = $this->getPositionDetails($employe['position_id']);
                    //     $hasil['emp_name'] = $employe['emp_name'];

                    //     // buat data kosong
                    //     $hasil['date'] = $v;
                    //     $hasil['status'] = '#N/A';
                    //     $hasil['sickness'] = "";
                    //     $hasil['notes'] = "";

                    //     // masukkan data
                    //     $data_health[$y] = $hasil;
                    //     $y++;
                    // } else {
                    //     // nothing
                    // }
                }
            }
        }

        // masukkan jadi satu bentuk array dimensi
        return ($data_health);
        exit;

        // $nik_employee = $this->session->userdata('nik');

        // fillter start date & end date
        // $start_date = "2020-07-18";
        // $end_date = "2020-07-21";

        // ambil data dari table
        // $data_health = $this->_general_m->getAllOrderDescend('*', $this->table_hr_reports, 'date >= "'.$start_date.'" AND date <= "'.$end_date.'"', 'date');

        // dapatkan semua kategori sakit, ambil input_name nya
        $counter_kategori = $this->_general_m->getAll('name, input_name', $this->table_hr_category, array());
        // inisialisasi dengan angka nol di setiap kategorinya
        foreach($counter_kategori as $k => $v){
            $counter_kategori[$k]['counter'] = 0;
        }
        // tambah kategori other
        $counter_kategori[array_key_last($counter_kategori) + 1] = array(
            'name' => 'Lainnya',
            'input_name' => 'other',
            'counter' => 0
        );

        // variable initialisation
        $counter_sehat = 0; $counter_sakit = 0; 
        foreach($data_health as $k => $v){
            // tambah informasi karyawan
            $employe = $this->_general_m->getOnce('position_id, emp_name', 'master_employee', array('nik' => $v['nik']));
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
                    if($value['name'] != 'other'){
                        $what_sickness = $this->_general_m->getOnce('name', $this->table_hr_category, array('input_name' => $value['name']));
                    }
                    // untuk nama kategori sakit yang terdaftar
                    if(!empty($value['status']) && $value['name'] != 'other' && !empty($what_sickness)){
                        $sicked[$x] = $what_sickness['name'];
                        $x++;

                        // counter jenis sakit
                        foreach($counter_kategori as $k_kategori => $v_kategori){
                            if($v_kategori['input_name'] == $value['name']){
                                $counter_kategori[$k_kategori]['counter']++;
                            }  
                        }
                    // kategori sakit other
                    } elseif(!empty($value['status']) && $value['name'] == 'other'){
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
                $data_health[$k]['status'] = 'Unwell';
            } else {
                $data_health[$k]['status'] = 'Healthy';
            }
        }
        
        // bentuk jadi json dan tampilkan
        return array(
            'data' => $data_health,
            'counter_sakit' => $counter_sakit,
            'counter_sehat' => $counter_sehat,
            'counter_kategori' => $counter_kategori
        );
    }

    function getDataHealthAll($where, $data_nik, $daterange_days){
        // siapkan variabel data nik
        $data_health = array(); $y = 0;
        // tiap hari
        foreach($daterange_days as $v){
            // tiap nik
            foreach($data_nik as $key => $value){
                $where_data_health = $where.'nik = "'.$value['nik'].'" AND date = "'.$v.'"'; // gabungkan dengan where sebelumnya

                // ambil hasilnya
                $hasil = $this->_general_m->getJoin2tablesOrderDescend(
                    $this->table_hr_reports .'.date, '. $this->table_hr_reports .'.nik, '. $this->table_hr_reports .'.time, '. $this->table_hr_reports .'.status, '. $this->table_hr_reports .'.sickness, '. $this->table_hr_reports .'.notes',
                    $this->table_hr_reports,
                    $this->table_position,
                    $this->table_hr_reports .'.id_posisi = '. $this->table_position .'.id',
                    $where_data_health,
                    'date'
                );

                // jika ada datanya simpan dalam variabel
                if(!empty($hasil)){
                    foreach($hasil as $kunci => $nilai){
                        unset($hasil[$kunci]);
                        // gabungkan date dan time
                        $hasil[$kunci]['Date'] = $nilai['date']." ".$nilai['time'];
                        $hasil[$kunci]['NIK'] = $value['nik'];
                        // ambil detail master_employee
                        $employe = $this->_general_m->getOnce('position_id, emp_name', 'master_employee', array('nik' => $nilai['nik']));
                        $detail_posisi = $this->getPositionDetails($employe['position_id']);
                        // $hasil[$kunci]['detail_position'] = $this->getPositionDetails($employe['position_id']);
                        $hasil[$kunci]['Name'] = $employe['emp_name'];
                        $hasil[$kunci]['Department'] = $detail_posisi['departement'];
                        $hasil[$kunci]['Division'] = $detail_posisi['divisi'];
                        // $hasil[$kunci]['position'] = $detail_posisi['posisi'];

                        // ubah status jadi text healthy dan sick
                        if($nilai['status'] == 0){
                            $hasil[$kunci]['Health Status'] = 'Unwell';
                        } else {
                            $hasil[$kunci]['Health Status'] = 'Healthy';
                        }

                        // hapus array kategori sakit kalo dia tidak ada statusnya dan berarti dia tidak sehat
                        if(!empty($nilai['sickness'])){                
                            //decode sickness
                            $sickness = json_decode($nilai['sickness'], true);
                            // siapkan variable penampung sickness
                            $sicked = array(); $x = 0;
                            foreach($sickness as $sicknesess){
                                // ambil info kategori sakit terdaftar
                                if($sicknesess['name'] != 'other'){
                                    $what_sickness = $this->_general_m->getOnce('name', $this->table_hr_category, array('input_name' => $sicknesess['name']));
                                }
                                // untuk nama kategori sakit yang terdaftar
                                if(!empty($sicknesess['status']) && $sicknesess['name'] != 'other' && !empty($what_sickness)){
                                    $sicked[$x] = $what_sickness['name'];
                                    $x++;

                                // kategori sakit other
                                } elseif(!empty($sicknesess['status']) && $sicknesess['name'] == 'other'){
                                    $sicked[$x] = $sicknesess['status'] ;
                                    $x++;
                                }
                            }
                            // gabungkan dan beri koma antara nama sakit
                            $sicked = implode(', ', $sicked);
                            $hasil[$kunci]['Sick Category'] = $sicked; // replace data sickness
                        } else {
                            $hasil[$kunci]['Sick Category'] = "";
                            // nothing
                        }

                        $hasil[$kunci]['Notes'] = $nilai['notes'];

                        // masukkan data
                        $data_health[$y] = $hasil[$kunci];
                        $y++;
                    }
                } else {
                    $cekdata = $this->_general_m->getJoin2tables(
                        'master_employee.nik',
                        'master_employee',
                        $this->table_position,
                        'master_employee.position_id = '. $this->table_position .'.id',
                        $where.'nik = "'.$value['nik'].'"'
                    );
                    if(!empty($cekdata)){
                        $hasil['Date'] = $v;
                        $hasil['NIK'] = $value['nik'];

                        // ambil detail employe
                        $employe = $this->_general_m->getOnce('position_id, emp_name', 'master_employee', array('nik' => $value['nik']));
                        $detail_posisi = $this->getPositionDetails($employe['position_id']);
                        // $hasil[$kunci]['detail_position'] = $this->getPositionDetails($employe['position_id']);
                        $hasil['Name'] = $employe['emp_name'];
                        $hasil['Department'] = $detail_posisi['departement'];
                        $hasil['Division'] = $detail_posisi['divisi'];
                        // $hasil['position'] = $detail_posisi['posisi'];

                        // buat data kosong
                        $hasil['Health Status'] = '#N/A';
                        $hasil['Sick Category'] = "";
                        $hasil['Notes'] = "";

                        // masukkan data
                        $data_health[$y] = $hasil;
                        $y++;
                    } else {
                        // nothing
                    }
                }
            }
        }

        // masukkan jadi satu bentuk array dimensi
        return ($data_health);
        exit;
    }

    function getDataNik(){
        // cek apa dia hirarki N dan N-1
        $is_divhead = $this->is_divhead();
        $is_depthead = $this->is_depthead();
        if(!empty($is_divhead)){
            // ambil data dengan divisi id segitu
            $data_nik = $this->_general_m->getJoin2tables(
                'master_employee.nik',
                'master_employee',
                $this->table_position,
                'master_employee.position_id = '. $this->table_position .'.id',
                $this->table_position .'.div_id = "'.$is_divhead[0]['div_id'].'"'
            );
        } elseif(!empty($is_depthead)){
            $data_nik = $this->_general_m->getJoin2tables(
                'master_employee.nik',
                'master_employee',
                $this->table_position,
                'master_employee.position_id = '. $this->table_position .'.id',
                $this->table_position .'.dept_id = "'.$is_depthead[0]['dept_id'].'"'
            );
        } else { // get data nik dia sendiri
            $data_nik[0] = array(
                'nik' => $this->session->userdata('nik')
            );
        }

        return $data_nik;
    }

    // buat dapetin populasi health unwell status
    function getPopulasiHS($daterange_days = "", $divisi = "", $departemen = ""){
        // jika dia admin
        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1){
            // filtering divisi dan departemen checker
            if(empty($divisi) && empty($departemen)){
                $populasi_hs = $this->_general_m->getRow('master_employee', array());
            } elseif(!empty($divisi) && empty($departemen)){
                $populasi_hs = count($this->_general_m->getJoin2tables(
                    'master_employee.nik',
                    $this->table_position,
                    'master_employee',
                    'master_employee.position_id = '. $this->table_position .'.id',
                    array('div_id' => $divisi)
                ));
            } elseif(!empty($divisi) && !empty($departemen)){
                $populasi_hs = count($this->_general_m->getJoin2tables(
                    'master_employee.nik',
                    $this->table_position,
                    'master_employee',
                    'master_employee.position_id = '. $this->table_position .'.id',
                    array(
                        'div_id' => $divisi,
                        'dept_id' => $departemen
                    )
                ));
            }
        } else { // jika bukan admin
            $populasi_hs = count($daterange_days);
        }

        return $populasi_hs;
    }

    function getPositionDetails($id_posisi){
        // load model Job Profile
        $this->load->model('Jobpro_model');

        $temp_posisi = $this->Jobpro_model->getDetail("div_id, dept_id, id", $this->table_position, array('id' => $id_posisi));
        // print_r($temp_posisi);
        foreach ($this->Jobpro_model->getDetail("position_name", $this->table_position, array('id' => $temp_posisi['id'])) as $v){// tambahkan nama posisi
            $detail_posisi['posisi'] = $v;
        }
        foreach($this->Jobpro_model->getDetail("nama_departemen", "master_department", array('id' => $temp_posisi['dept_id'])) as $v){// tambahkan nama master_department
            $detail_posisi['departement'] = $v;
        }
        foreach($this->Jobpro_model->getDetail("id", "master_department", array('id' => $temp_posisi['dept_id'])) as $v){// tambahkan id master_department
            $detail_posisi['id_dept'] = $v;
        }
        foreach($this->Jobpro_model->getDetail("division", "master_division", array('id' => $temp_posisi['div_id'])) as $v){// tambahkan nama master_division
            $detail_posisi['divisi'] = $v;
        }
        foreach($this->Jobpro_model->getDetail("id", "master_division", array('id' => $temp_posisi['div_id'])) as $v){// tambahkan id master_division
            $detail_posisi['id_div'] = $v;
        }
        return $detail_posisi;
    }

    function export2Excel() {
        // siapkan variable where
        $where = '';
        
        // ambil divisi
        if(!empty($this->input->post('divisiSelected'))){
            $where .= 'div_id = "'.explode('-', $this->input->post('divisiSelected'))[1].'" AND ';
            $divisi = explode('-', $this->input->post('divisiSelected'))[1];
        } else {
            $divisi = "";
        }
        // ambil departemen
        if(!empty($this->input->post('departemenSelected'))){
            $where .= 'dept_id = "'.explode('-', $this->input->post('departemenSelected'))[1].'" AND ';
            $departemen = explode('-', $this->input->post('departemenSelected'))[1];
        } else {
            $departemen = "";
        }

        // ambil date range
        $daterange = explode(' - ', $this->input->post('daterangeSelected'));
        foreach($daterange as $k => $v){
            $daterange[$k] = date('Y-m-d', strtotime($v));
        }

        // ambil tanggal di setiap hari
        $daterange_days = $this->getDatesFromRange($daterange[0], $daterange[1]);

        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1){
            // ambil data semua nik jika admin
            $data_nik = $this->_general_m->getAll('nik', 'master_employee', array());
        } else {
            // get data nik
            $data_nik = $this->getDataNik();
        }

        // ambil data health
        $data_health = $this->getDataHealthAll(
            $where,
            $data_nik,
            $daterange_days
        );

        // export ke eksel
        // export2Excel();
        export2Excel($data_health, 'Health Report');
    }

    // function buat cek div head
    function is_divhead(){
        return $this->_general_m->getJoin2tables(
            'master_employee.position_id, '. $this->table_position .'.div_id',
            'master_employee',
            $this->table_position,
            'master_employee.position_id = '. $this->table_position .'.id',
            $this->table_position .'.hirarki_org = "N" AND master_employee.nik = "'.$this->session->userdata('nik').'"'
        );
    }
    // function buat cek dept head
    function is_depthead(){
        return $this->_general_m->getJoin2tables(
            $this->table_position .'.dept_id',
            'master_employee',
            $this->table_position,
            'master_employee.position_id = '. $this->table_position .'.id',
            $this->table_position .'.hirarki_org = "N-1" AND master_employee.nik = "'.$this->session->userdata('nik').'"'
        );
    }

}

/* End of file HealthReport.php */
