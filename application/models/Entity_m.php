<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Entity_m extends CI_Model {
    protected $table = "master_entity" ;
    
    /**
     * getAll data entity
     *
     * @return void
     */
    public function getAll(){
        return $this->db->get_where($this->table)->result_array();
    }
    
    /**
     * getOnce entity details with where
     *
     * @param  mixed $where
     * @return void
     */
    function getOnce($where){
        return $this->db->get_where($this->table, $where)->row_array();
    }

}

/* End of file Entity_m.php */
