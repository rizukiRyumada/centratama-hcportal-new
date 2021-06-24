<?php
// TODO tambahkan server side validation
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MainController {

    protected $table = [
        'position' => 'master_position',
    ];
    
    public function __construct()
    {
        parent::__construct();
        //Do your magic here

        // ambil nama table yang terupdate
        $this->load->library('tablename');
        $this->table['position'] = $this->tablename->get($this->table['position']);
    }

    public function index() {
        // ambil data karyawan
        $data['data_karyawan'] = $this->_general_m->getJoin2tables(
            'master_employee.nik, master_employee.emp_name, master_employee.position_id, master_employee.email, 
             '. $this->table['position'] .'.position_name, '. $this->table['position'] .'.dept_id, '. $this->table['position'] .'.div_id, '. $this->table['position'] .'.hirarki_org',
            'master_employee',
            $this->table['position'],
            'master_employee.position_id = '. $this->table['position'] .'.id',
            array('nik' => $this->session->userdata('nik'))
        )[0];
        $data['data_karyawan']['departemen'] = $this->_general_m->getOnce('nama_departemen', 'master_department', array('id' => $data['data_karyawan']['dept_id']))['nama_departemen'];
        $data['data_karyawan']['divisi'] = $this->_general_m->getOnce('division', 'master_division', array('id' => $data['data_karyawan']['div_id']))['division'];

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu', array('url' => $this->uri->uri_string()))['title'];
        $data['load_view'] = 'profile/profile_index_v';
        // $data['custom_styles'] = array('survey_styles');
        $data['custom_script'] = array('plugins/jqueryValidation/script_jqueryValidation', 'profile/script_profile');
        
        $this->load->view('main_v', $data);
    }

    public function saveProfile(){
        // load form validation library
        $this->load->library('form_validation');

        // set rules
        $this->form_validation->set_rules('name', 'Name', 'required|min_length[5]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password_current', 'Existing Password', 'required|min_length[8]');
        $this->form_validation->set_rules('password', 'Existing Password', 'min_length[8]');
        $this->form_validation->set_rules('password2', 'Existing Password', 'min_length[8]|matches[password]');

        // cek validasi form
        if($this->form_validation->run() == FALSE){
            // set pesan error
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">'.validation_errors().'</div>');

            // arahkan kembali ke profile
            redirect('profile');
        } else {
            // ambil password dari database
            $user_password = $this->_general_m->getOnce('password', 'master_employee', array('nik' => $this->session->userdata('nik')))['password'];

            // simpan data
            $data = array(
                'emp_name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
            );

            // set session biar gausah input lagi si user
            if($this->session->userdata('form_profile')){
                $this->session->unset_userdata('form_profile');
            }
            $this->session->set_userdata('form_profile', $data);
            
            // cek password
            if(password_verify($this->input->post('password_current'), $user_password)){
                // cek apa karyawan ganti password
                if($this->input->post('password')){
                // $password = password_hash($password_string, PASSWORD_ARGON2I);
                $data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
                }
            } else {
                // siapkan pesan error pakai alert
                // $this->session->set_flashdata(
                //     'msg',
                //     '<div class="alert alert-danger alert-dismissible">
                //         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                //         <h5><i class="icon fas fa-ban"></i> Wrong Password!</h5>
                //         You typed the wrong Password, please try again
                //     </div>'
                // );

                // set toastr notification
                $this->session->set_userdata('msg', array(
                    'icon'  => 'error',
                    'title' => 'Wrong Password',
                    'msg'   => 'You typed the wrong Password, please try again.'
                ));
                redirect('profile'); // arahkan kembali ke halaman profile
            }

            // simpan perubahan ke database
            $this->_general_m->update('master_employee', 'nik', $this->session->userdata('nik'), $data);

            // set toastr notification
            $this->session->set_userdata('msg', array(
                'icon'  => 'success',
                'title' => 'Saved',
                'msg'   => 'Your changes was saved.'
            ));
            redirect('profile'); // arahkan kembali ke halaman profile
        }
    }

}

/* End of file Profile.php */
