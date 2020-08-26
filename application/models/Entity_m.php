<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Entity_m extends CI_Model {
    protected $table = "master_entity" ;

    public function getAll(){
        return $this->db->get_where($this->table)->result_array();
    }

}

/* End of file Entity_m.php */
