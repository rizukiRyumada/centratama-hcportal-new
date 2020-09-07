<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ptk_m extends CI_Model {
    protected $table = [
        "main" => "ptk_form",
        "status" => "ptk_status"
    ];
    
    /**
     * get all ptk status
     *
     * @return void
     */
    function getAll_ptkStatus(){
        return $this->db->get_where($this->table['status'])->result_array();
    }
    
    function getAll_ptkList(){
        $this->db->select($this->table['main'].".id_entity, ".$this->table['main'].".id_div, ".$this->table['main'].".id_dept, ".$this->table['main'].".id_pos, ".$this->table['main'].".id_time, ".$this->table['main'].".time_modified, ".$this->table['main'].".status, ".$this->table['main'].".status_now");
        return $this->db->get_where($this->table['main'])->result_array();
    }

    /**
     * get ptk list with status
     *
     * @param  mixed $where
     * @return void
     */
    function get_ptkList($where){
        $this->db->select($this->table['main'].".id_entity, ".$this->table['main'].".id_div, ".$this->table['main'].".id_dept, ".$this->table['main'].".id_pos, ".$this->table['main'].".id_time, ".$this->table['main'].".time_modified, ".$this->table['main'].".status, ".$this->table['main'].".status_now");
        $this->db->join($this->table['status'], $this->table['main'].".status_now = ".$this->table['status'].".id", 'left');
        return $this->db->get_where($this->table['main'], $where)->result_array();
    }
    
    /**
     * getDetail_ptk
     *
     * @return void
     */
    function getDetail_ptk($id_entity, $id_div, $id_dept, $id_pos, $id_time){
        return $this->db->get_where($this->table['main'], array(
            'id_entity' => $id_entity,
            'id_div'    => $id_div,
            'id_dept'   => $id_dept,
            'id_pos'    => $id_pos,
            'id_time'   => $id_time
        ))->row_array();
    }

    function saveForm($data){
        $this->db->insert($this->table['main'], $data);
    }

}

/* End of file Ptk_m.php */
