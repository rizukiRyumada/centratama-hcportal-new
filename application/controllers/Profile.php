<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MainController {
    
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
    }

    public function index() {
        // ambil data karyawan
        $data['data_karyawan'] = $this->_general_m->getJoin2tables(
            'employe.nik, employe.emp_name, employe.position_id, employe.email, 
             position.position_name, position.dept_id, position.div_id, position.hirarki_org',
            'employe',
            'position',
            'employe.position_id = position.id',
            array('nik' => $this->session->userdata('nik'))
        )[0];
        $data['data_karyawan']['departemen'] = $this->_general_m->getOnce('nama_departemen', 'departemen', array('id' => $data['data_karyawan']['dept_id']))['nama_departemen'];
        $data['data_karyawan']['divisi'] = $this->_general_m->getOnce('division', 'divisi', array('id' => $data['data_karyawan']['div_id']))['division'];

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu', array('url' => $this->uri->uri_string()))['title'];
        $data['load_view'] = 'profile/profile_index_v';
        // $data['custom_styles'] = array('survey_styles');
        $data['custom_script'] = array('profile/script_profile', 'plugins/jqueryValidation/script_jqueryValidation');
        
        $this->load->view('main_v', $data);
    }

    public function saveProfile(){
        // ambil password dari database
        $user_password = $this->_general_m->getOnce('password', 'employe', array('nik' => $this->session->userdata('nik')))['password'];

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

            // siapkan pesan error dengan toastr
            $this->session->set_flashdata(
                'msg', 
                'toastr["error"]("You typed the wrong Password, please try again.", 
                "Wrong Password!");'
            );
            redirect('profile'); // arahkan kembali ke halaman profile
        }

        // simpan perubahan ke database
        $this->_general_m->update('employe', 'nik', $this->session->userdata('nik'), $data);

        // siapkan pesan
        $this->session->set_flashdata(
            'msg', 
            'toastr["success"]("Your changes was saved.", 
            "Saved");'
        );
        redirect('profile'); // arahkan kembali ke halaman profile
    }

}

/* End of file Profile.php */
