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
        // cek apa user udh isi pada tanggal segini
        $checkedIn = $this->_general_m->getOnce('*', 'healthReport_reports', array('date' => date('o-m-d', time()), 'nik' => $this->session->userdata('nik')));

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
		$data['load_view'] = 'healthreport/healthCheck_healthReport_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		$data['custom_styles'] = array('healthreport_styles');
        $data['custom_script'] = array('plugins/datatables/script_datatables', 'plugins/jqueryValidation/script_jqueryValidation', 'healthreport/script_index_healthreport');
        
		$this->load->view('main_v', $data);
    }

    public function report(){

        // main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
		$data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['title'];
		$data['load_view'] = 'healthreport/report_healthReport_v';
		// additional styles and custom script
        $data['additional_styles'] = array('plugins/datatables/styles_datatables');
		$data['custom_styles'] = array('healthreport_styles');
        $data['custom_script'] = array('plugins/datatables/script_datatables', 'plugins/jqueryValidation/script_jqueryValidation', 'healthreport/script_index_healthreport');
        
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
                'status'   => $this->input->post('checkIn'),
                'sickness' => null,
                'notes'    => null
            );
        } else {
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
                redirect('healthReport/healthCheck');
            }

            $data = array(
                'date'     => date('o-m-d', time()),
                'nik'      => $this->session->userdata('nik'),
                'status'   => $this->input->post('checkIn'),
                'sickness' => json_encode($sickness_status),
                'notes'    => $this->input->post('notes')
            );
        }
        
        $this->_general_m->insert('healthReport_reports', $data);

        redirect('healthReport/healthCheck');
    }

    public function test(){
        echo(date("j M o" ,time()));
        // echo(date('j M o', 1594892256))
                        //    9999999999
    }

}

/* End of file HealthReport.php */
