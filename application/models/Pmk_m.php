<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pmk_m extends CI_Model {
    protected $table = [
        "contract" => "master_employee_contract",
        "main" => "pmk_form",
        "pertanyaan" => "pmk_survey_pertanyaan",
        "status" => "pmk_status",
    ];
    
    /**
     * getAll pmk form data
     *
     * @return void
     */
    function getAll(){
        return $this->db->get($this->table['main'])->result_array();
    }

    function getAll_pertanyaan(){
        return $this->db->get($this->table['pertanyaan'])->result_array();
    }
    
    /**
     * get all pmk status
     *
     * @return void
     */
    function getAll_pmkStatus(){
        return $this->db->get_where($this->table['status'])->result_array();
    }
        
    /**
     * getAll_pmkList
     *
     * @return void
     */
    function getAll_pmkList(){
        $this->db->select($this->table['main'].".id_entity, ".$this->table['main'].".id_div, ".$this->table['main'].".id_dept, ".$this->table['main'].".id_pos, ".$this->table['main'].".id_time, ".$this->table['main'].".time_modified, ".$this->table['main'].".status, ".$this->table['main'].".status_now");
        return $this->db->get_where($this->table['main'])->result_array();
    }

    /**
     * get pmk list with status
     *
     * @param  mixed $where
     * @return void
     */
    function get_pmkList($where){
        $this->db->select($this->table['main'].".id_entity, ".$this->table['main'].".id_div, ".$this->table['main'].".id_dept, ".$this->table['main'].".id_pos, ".$this->table['main'].".id_time, ".$this->table['main'].".time_modified, ".$this->table['main'].".status, ".$this->table['main'].".status_now");
        $this->db->join($this->table['status'], $this->table['main'].".status_now = ".$this->table['status'].".id", 'left');
        return $this->db->get_where($this->table['main'], $where)->result_array();
    }
    
    /**
     * getDetail_pmk
     *
     * @return void
     */
    function getDetail_pmk($id_entity, $id_div, $id_dept, $id_pos, $id_time){
        return $this->db->get_where($this->table['main'], array(
            'id_entity' => $id_entity,
            'id_div'    => $id_div,
            'id_dept'   => $id_dept,
            'id_pos'    => $id_pos,
            'id_time'   => $id_time
        ))->row_array();
    }
    
    /**
     * get detail information of status id
     *
     * @param  mixed $id
     * @return void
     */
    function getDetail_pmkStatusDetailByStatusId($id_status){
        return $this->db->get_where($this->table['status'], array('id' => $id_status))->row_array();
    }
    
    /**
     * getDetail_pmkStatus
     *
     * @param  mixed $id_entity
     * @param  mixed $id_div
     * @param  mixed $id_dept
     * @param  mixed $id_pos
     * @param  mixed $id_time
     * @return void
     */
    function getDetail_pmkStatus($id_entity, $id_div, $id_dept, $id_pos, $id_time){
        $this->db->select('status');
        return json_decode($this->db->get_where($this->table['main'], array(
            'id_entity' => $id_entity,
            'id_div'    => $id_div,
            'id_dept'   => $id_dept,
            'id_pos'    => $id_pos,
            'id_time'   => $id_time
        ))->row_array()['status'], true);
    }

    /**
     * get id status now pmk
     *
     * @param  mixed $id_entity
     * @param  mixed $id_div
     * @param  mixed $id_dept
     * @param  mixed $id_pos
     * @param  mixed $id_time
     * @return void
     */
    function getDetail_pmkStatusNow($id_entity, $id_div, $id_dept, $id_pos, $id_time){
        $this->db->select('status_now');
        return $this->db->get_where($this->table['main'], array(
            'id_entity' => $id_entity,
            'id_div'    => $id_div,
            'id_dept'   => $id_dept,
            'id_pos'    => $id_pos,
            'id_time'   => $id_time
        ))->row_array()['status_now'];
    }

    /**
     * ambil semua data form dengan where
     *
     * @param  mixed $where
     * @return void
     */
    function getAllWhere_form($where){
        return $this->db->get_where($this->table['main'], $where)->result_array();
    }

    /**
     * ambil satu data contract dengan where
     *
     * @param  mixed $where
     * @return void
     */
    function getOnceWhere_contract($where){
        return $this->db->get_where($this->table['contract'], $where)->row_array();
    }
    
    /**
     * ambil satu data form dengan where
     *
     * @param  mixed $where
     * @return void
     */
    function getOnceWhere_form($where){
        return $this->db->get_where($this->table['main'], $where)->row_array();
    }
    
    /**
     * getOnceWhere_status
     *
     * @param  mixed $where
     * @return void
     */
    function getOnceWhere_status($where){        
        return $this->db->get_where($this->table['status'], $where)->row_array();
    }

    /**
     * getRow_form
     *
     * @param  mixed $nik
     * @param  mixed $contract
     * @return void
     */
    function getRow_form($nik, $contract){
        return $this->db->get_where($this->table['main'], array(
            'nik'       => $nik,
            'contract'  => $contract
        ))->num_rows();
    }
    
    /**
     * saveForm
     *
     * @param  mixed $data
     * @return void
     */
    function saveForm($data){
        $this->db->insert($this->table['main'], $data);
    }
    
    /**
     * updateForm
     *
     * @param  mixed $data
     * @param  mixed $where
     * @return void
     */
    public function updateForm($data, $where){
        $this->db->where($where);
        $this->db->update($this->table['main'], $data);
    }

}

/* End of file Pmk_m.php */
