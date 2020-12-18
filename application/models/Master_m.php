<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Master_m extends CI_Model {
    protected $table = [
        'role' => 'user_role'
    ];
    
    /**
     * get all user role data
     *
     * @return void
     */
    function getAll_userRole(){
        return $this->db->get_where($this->table['role'])->result_array();
    }

}

/* End of file Master_m.php */
