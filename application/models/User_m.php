<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_m extends CI_Model {
    protected $table = array(
        'userapp_admins' => "user_userapp_admins"
    );

    function get_admin($id_menu){
        $admin_nik = $this->db->select('nik')->get_where($this->table['userapp_admins'], array('id_menu' => $id_menu))->row_array()['nik']; // ambil admin pertama aja
        $this->load->model(['employee_m']);
        return $this->employee_m->getDetails_employee($admin_nik);
    }

}

/* End of file User_m.php */
