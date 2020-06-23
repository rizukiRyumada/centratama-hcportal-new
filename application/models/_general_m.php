<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class _general_m extends CI_Model {
    // DELETE a row where index    
    /**
     * delete
     *
     * @param  mixed $table
     * @param  mixed $where
     * @return void
     */
    public function delete($table, $where){
        $this->db->where($where['index'], $where['data']);
        $this->db->delete($table);
    }

    // SELECT one row    
    /**
     * getOnce
     *
     * @param  mixed $select
     * @param  mixed $table
     * @param  mixed $where
     * @return void
     */
    public function getOnce($select, $table, $where){
        $this->db->select($select);
        $this->db->from($table);
        $this->db->where($where);
        return $this->db->get()->row_array();
    }

    // SELECT more row    
    /**
     * getAll
     *
     * @param  mixed $select
     * @param  mixed $table
     * @param  mixed $where
     * @return void
     */
    public function getAll($select, $table, $where){
        $this->db->select($select);
        $this->db->from($table);
        $this->db->where($where);
        return $this->db->get()->result_array();
    }

    // SELECT with join 2 tables    
    /**
     * getJoin2tables
     *
     * @param  mixed $select
     * @param  mixed $table
     * @param  mixed $joinTable
     * @param  mixed $joinIndex
     * @param  mixed $where
     * @return void
     */
    public function getJoin2tables($select, $table, $joinTable, $joinIndex, $where){
        $this->db->select($select);
        $this->db->join($joinTable, $joinIndex, 'left');
        return $this->db->get_where($table, $where)->result_array();
    }

    // INSERT INTO    
    /**
     * insert
     *
     * @param  mixed $table
     * @param  mixed $data
     * @return void
     */
    public function insert($table, $data){
        $this->db->insert($table, $data);
    }

    // UPDATE    
    /**
     * update
     *
     * @param  mixed $table
     * @param  mixed $whereIndex
     * @param  mixed $whereAttrib
     * @param  mixed $data
     * @return void
     */
    public function update($table, $whereIndex, $whereAttrib, $data){
        $this->db->where($whereIndex, $whereAttrib);
        $this->db->update($table, $data);
    }
    
    

}

/* End of file _general_m.php */
