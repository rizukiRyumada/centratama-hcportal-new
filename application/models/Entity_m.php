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
     * ambil semua data entity kecuali CG dan OS
     *
     * @return void
     */
    function getAll_notAtAll(){
        $result = $this->db->get_where($this->table)->result_array();
        foreach($result as $k => $v){
            if($v['id'] == 1 || $v['id'] == 7){ // hapus entity jika idnya CG atau OS
                unset($result[$k]); 
            }
        }
        return $result;
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
