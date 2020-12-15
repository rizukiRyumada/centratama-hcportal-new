 <!-- TODO pasang login auth ion -->

<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        // load library form_validation
        $this->load->library('form_validation');
    }
    
    public function index(){
        if(empty($this->session->userdata('error'))){ // cek error message
            $this->session->set_userdata(array('error' => 0));
        }

        // BUG cek status login dengan nik aja vurnerable
        if ($this->session->userdata('nik')) { // cek apa sudah login
            if(empty($this->session->userdata('token'))){ // cek apa ada token
                // redirect
                $this->redirect();
            } else {
                //targetkan sesuai token
                header('location: '. base_url('direct/arahkanFromEmail'));
            }
        }
        $this->form_validation->set_rules('nik', 'NIK', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == false){
            // tampilkan pesan login dan buka popup login
            if($this->session->userdata('redirect')){
                // set pesan login
                $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">Please login to continue.</div>');
                // set buat tampilkan otomatis popup login
                $this->session->set_userdata(array('error' => 1));
            }
            // prepare data
            $data['page_title'] = "HC Portal";
            $data['load_view'] = 'login/index_login_v';
            $this->load->view('login/login_v', $data);
        } else {
            // validasi berhasil pake method baru
            $this->logmein();
        }
    }

    public function test(){
        $this->load->view('test');
    }

    public function logmein(){
        $nik      = $this->input->post('nik');
        $password = $this->input->post('password');
        $user     = $this->db->get_where('master_employee', ['nik' => $nik])->row_array();
        // jika usernya ada
        if($user) {
            // cek password
            if(password_verify($password, $user['password'])) {
                $data = [
                    'nik' => $user['nik'],
                    'position_id' => $user['position_id'],
                    'akses_surat_id' => $user['akses_surat_id'],
                    'role_id' => $user['role_id']
                ];
                $this->session->set_userdata($data);

                if(empty($this->session->userdata('token'))){ // cek apa ada token
                    // redirect
                    $this->redirect();
                } else {
                    //targetkan sesuai token
                    header('location: '. base_url('direct/arahkanFromEmail'));
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Wrong Password! </div>');
                $this->session->set_userdata(array('error' => 1));
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Your NIK has not registered! </div>');
            $this->session->set_userdata(array('error' => 1));
            redirect('login');
        }
    }
    
    /**
     * redirect
     *
     * @return void
     */
    public function redirect(){
        if(!empty($this->session->userdata('redirect'))){
            $redirect = $this->session->userdata('redirect');
            // hapus session redirect
            $this->session->unset_userdata('redirect');
            // buat session buat nampilin otomatis popup login
            $this->session->set_userdata(array('error' => 1));
            
            redirect($redirect, 'refresh');
        } else {
            redirect('dashboard', 'refresh');
        }
    }
    
    /**
     * logout
     *
     * @return void
     */
    public function logout(){
        $this->session->sess_destroy(); // hapus data session
        
        // $this->session->unset_userdata('nik');
        // $this->session->unset_userdata('role_id');
        $this->session->set_userdata(array('error' => 1)); // buat munculin modal login form
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Thank You for using HC Portal, have a nice day :)</div>');
        redirect('login');
    }

    // template for view    
    /**
     * testOop
     *
     * @return void
     */
    public function testOop(){
        // load model
        $this->load->model('_general_m');
        
        // prepare general variables
        $data['page_title'] = "test_oop"; // judul halaman
        
        // ambil semua menu dan sub menu dan cek aksesnya
        // $data['sidebar_menu'] = $this->

        //breadcrumb halaman
        $data['breadcrumb'] = array(
            array('judul' => 'Home', 'link' => base_url()),
            array('judul' => 'Test OOP', 'link' => base_url('login/testoop'))
        );

        $this->load->view('testOop', $data); // load the view
    }

    /**
     * convert csv to array associative
     *
     * @return void
     */
    public function convertToCsv(){
        $file = fopen(base_url('mycsv.csv'), 'r'); // take file location

        $arr = array(); $x = 0; $index = array(); // prepare variable for container and index
        while (($line = fgetcsv($file)) !== FALSE) { // iteration on each line of csv
            //$line is an array of the csv elements
            if($x == 0){ // for the first line, use it as index
                foreach($line as $k => $v){ // iterate the first line
                    $index[$k] = $v; // place it to index variable
                }
                $x++; // add flag 
            } else {
                $y = 0; // for indexing line
                foreach($index as $k => $v){ // iterate the index
                    $arr[$x-1][$v] = $line[$y]; 
                    $y++;
                }
                $x++;
            }
        }
        fclose($file); // close the file

        // buat insert contract
        foreach($arr as $k => $v){
            $arr[$k]['date_start'] = date("Y-m-d", ($v['date_start']));
            $arr[$k]['date_end'] = date("Y-m-d", ($v['date_end']));
            // $arr[$k]['entity'] = $this->_general_m->getOnce('id', 'master_entity', array('nama_entity' => $v['entity']))['id'];
            $arr[$k]['entity'] = $v['entity'];
        }

        // print_r($arr); // print array

        $this->_general_m->insertAll('master_employee_contract', $arr);

        // buat update ke database dengan nik master employee
        // foreach($arr as $v){
        //     $this->db->where('nik', $v['nik']);
        //     $this->db->update('master_employee', array(
        //         'date_birth' => date("Y-m-d", strtotime($v['date_birth'])),
        //         'date_join' => date("Y-m-d", strtotime($v['date_join']))
        //     ));
        // }

        // buat update ke database dengan nik master employee
        // foreach($arr as $v){
        //     $this->db->where('nik', $v['nik']);
        //     $this->db->update('master_employee', array(
        //         'emp_stats' => $v['emp_stats'],
        //         'level_personal' => $v['level_personal']
        //     ));
        // }
    }

}

/* End of file Login.php */
