<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class _archives_m extends CI_Model {

    
    public function __construct()
    {
        parent::__construct();
        // load archives database model
        $this->db_arc = $this->load->database('archives', TRUE);
    }
    

    // DELETE a row where index    
    /**
     * delete
     *
     * @param  mixed $table
     * @param  mixed $where
     * @return void
     */
    public function delete($table, $where){
        $this->db_arc->where($where['index'], $where['data']);
        $this->db_arc->delete($table);
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
        $this->db_arc->select($select);
        $this->db_arc->from($table);
        $this->db_arc->where($where);
        return $this->db_arc->get()->row_array();
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
        $this->db_arc->select($select);
        $this->db_arc->from($table);
        $this->db_arc->where($where);
        return $this->db_arc->get()->result_array();
    }

    // SELECT more row and order
    /**
     * getAllOrder
     *
     * @param  mixed $select
     * @param  mixed $table
     * @param  mixed $where
     * @return void
     */
    public function getAllOrder($select, $table, $where, $order){
        $this->db_arc->select($select);
        $this->db_arc->from($table);
        $this->db_arc->where($where);
        $this->db_arc->order_by($order);
        return $this->db_arc->get()->result_array();
    }

    // SELECT more row and order
    /**
     * getAllOrderDescend
     *
     * @param  mixed $select
     * @param  mixed $table
     * @param  mixed $where
     * @return void
     */
    public function getAllOrderDescend($select, $table, $where, $order){
        $this->db_arc->select($select);
        $this->db_arc->from($table);
        $this->db_arc->where($where);
        $this->db_arc->order_by($order, 'desc');
        return $this->db_arc->get()->result_array();
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
        $this->db_arc->select($select);
        $this->db_arc->join($joinTable, $joinIndex, 'left');
        return $this->db_arc->get_where($table, $where)->result_array();
    }
        
    /**
     * getJoin2tablesOrder
     *
     * @param  mixed $select
     * @param  mixed $table
     * @param  mixed $joinTable
     * @param  mixed $joinIndex
     * @param  mixed $where
     * @param  mixed $order
     * @return void
     */
    public function getJoin2tablesOrder($select, $table, $joinTable, $joinIndex, $where, $order){
        $this->db_arc->select($select);
        $this->db_arc->join($joinTable, $joinIndex, 'left');
        $this->db_arc->order_by($order);
        return $this->db_arc->get_where($table, $where)->result_array();
    }
    
    /**
     * getJoin2tablesOrderDescend
     *
     * @param  mixed $select
     * @param  mixed $table
     * @param  mixed $joinTable
     * @param  mixed $joinIndex
     * @param  mixed $where
     * @param  mixed $order
     * @return void
     */
    public function getJoin2tablesOrderDescend($select, $table, $joinTable, $joinIndex, $where, $order){
        $this->db_arc->select($select);
        $this->db_arc->join($joinTable, $joinIndex, 'left');
        $this->db_arc->order_by($order);
        $this->db_arc->order_by($order, 'desc');
        return $this->db_arc->get_where($table, $where)->result_array();
    }

    /**
     * getRow
     *
     * @param  mixed $table
     * @param  mixed $where
     * @return void
     */
    public function getRow($table, $where){
        return $this->db_arc->get_where($table, $where)->num_rows();
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
        $this->db_arc->insert($table, $data);
    }
    
    /**
     * insertAll
     *
     * @param  mixed $table
     * @param  mixed $data
     * @return void
     */
    public function insertAll($table, $data){
        $this->db_arc->insert_batch($table, $data);
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
        $this->db_arc->where($whereIndex, $whereAttrib);
        $this->db_arc->update($table, $data);
    }

}

/* End of file _archives_m.php */
