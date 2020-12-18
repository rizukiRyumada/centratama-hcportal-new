<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ptk_m extends CI_Model {
    protected $table = [
        "main" => "ptk_form",
        "level" => "master_level",
        "status" => "ptk_status"
    ];
    
    /**
     * getAll ptk form data
     *
     * @return void
     */
    function getAll(){
        return $this->db->get_where($this->table['main'])->result_array();
    }
    
    /**
     * get all ptk status
     *
     * @return void
     */
    function getAll_ptkStatus(){
        return $this->db->get_where($this->table['status'])->result_array();
    }
        
    /**
     * getAll_ptkList
     *
     * @return void
     */
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
        $this->db->select($this->table['main'].".id_entity, ".$this->table['main'].".id_div, ".$this->table['main'].".id_dept, ".$this->table['main'].".id_pos, ".$this->table['main'].".position_other, ".$this->table['main'].".id_time, ".$this->table['main'].".time_modified, ".$this->table['main'].".status, ".$this->table['main'].".status_now");
        $this->db->join($this->table['status'], $this->table['main'].".status_now = ".$this->table['status'].".id", 'left');
        return $this->db->get_where($this->table['main'], $where)->result_array();
    }
    
    /**
     * ambil level master dari database
     *
     * @return void
     */
    function get_masterLevel(){
        $level = $this->_general_m->getAll('*', $this->table['level'], array());

        // ambil data nama level dan id levelnya
        $level_return = array(); $x = 0; $flag = ""; // flag untuk menandakan data terakhir yang ada di level
        foreach($level as $v){
            /**  
             * cek jika flag dengan id level pada satu per satu data level return apakah sama atau tidak
             * jika tidak sama flagnya baru tambahkan
             * 
             * gunanya untuk menghapus data level yang sama agar mendapatkan induk data level
             */
            if($flag != $v['id_level']){
                $level_return[$x] = array(
                    'id' => $v['id_level'],
                    'name' => $v['name']
                );
                $x++; // increment index
            }

            $flag = $v['id_level']; // taruh id_level ke dalam flag
        }
        return($level_return);
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
    
    /**
     * get detail information of status id
     *
     * @param  mixed $id
     * @return void
     */
    function getDetail_ptkStatusDetailByStatusId($id_status){
        return $this->db->get_where($this->table['status'], array('id' => $id_status))->row_array();
    }
    
    /**
     * getDetail_ptkStatus
     *
     * @param  mixed $id_entity
     * @param  mixed $id_div
     * @param  mixed $id_dept
     * @param  mixed $id_pos
     * @param  mixed $id_time
     * @return void
     */
    function getDetail_ptkStatus($id_entity, $id_div, $id_dept, $id_pos, $id_time){
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
     * get id status now ptk
     *
     * @param  mixed $id_entity
     * @param  mixed $id_div
     * @param  mixed $id_dept
     * @param  mixed $id_pos
     * @param  mixed $id_time
     * @return void
     */
    function getDetail_ptkStatusNow($id_entity, $id_div, $id_dept, $id_pos, $id_time){
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
     * getRow_form
     *
     * @param  mixed $id_entity
     * @param  mixed $id_div
     * @param  mixed $id_dept
     * @param  mixed $id_pos
     * @param  mixed $id_time
     * @return void
     */
    function getRow_form($id_entity, $id_div, $id_dept, $id_pos, $id_time){
        return $this->db->get_where($this->table['main'], array(
            'id_entity' => $id_entity,
            'id_div'    => $id_div,
            'id_dept'   => $id_dept,
            'id_pos'    => $id_pos,
            'id_time'   => $id_time
        ))->num_rows();
    }

    /**
     * get status summary before status now id
     *
     * @param  mixed $status_now_id
     * @return void
     */
    function getStatusBefore($status_now_id){
        // ambil order status now_id
        $status_detail = $this->_general_m->getOnce('*', $this->table['status'], array('id' => $status_now_id));

        $result = array();
        if(!empty($status_detail['order'])){
            for($x = 1; $x < $status_detail['order']; $x++){
                $result[$x] = $this->_general_m->getOnce('id, pic_name', $this->table['status'], array('order' => $x));
            }
        }

        return $result;
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

/* End of file Ptk_m.php */
