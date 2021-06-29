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

    /**
     * download all position data on current table
     *
     * @return void
     */
    function downloadTableDataAsCsv($table_name) {
        // ambil data posisi dari database
        try {
            $data_head = $this->getFields($table_name);
            $data_row = $this->getAll('*', $table_name);
            $data_posisi = [$data_head, ...$data_row];
            export2Csv($data_posisi, $table_name . date('-Ymd-His') . '.csv');
        } catch(Exception $e) {
            return [
                'code' => 500,
                'message' => 'Caught exception: ' . $e->getMessage() . '\n',
            ];
        }
        return [
            'code' => 200,
            'message' => 'Status: complete',
        ];
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
    public function getAll($select, $table, $where = []){
        $this->db->select($select);
        $this->db->from($table);
        $this->db->where($where);
        return $this->db->get()->result_array();
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
        $this->db->select($select);
        $this->db->from($table);
        $this->db->where($where);
        $this->db->order_by($order);
        return $this->db->get()->result_array();
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
        $this->db->select($select);
        $this->db->from($table);
        $this->db->where($where);
        $this->db->order_by($order, 'desc');
        return $this->db->get()->result_array();
    }
    
    /**
     * get fields name from a table
     *
     * @param  mixed $table
     * @return void
     */
    public function getFields($table){
        return $this->db->list_fields($table);
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
        $this->db->select($select);
        $this->db->join($joinTable, $joinIndex, 'left');
        $this->db->order_by($order);
        return $this->db->get_where($table, $where)->result_array();
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
        $this->db->select($select);
        $this->db->join($joinTable, $joinIndex, 'left');
        $this->db->order_by($order);
        $this->db->order_by($order, 'desc');
        return $this->db->get_where($table, $where)->result_array();
    }

    /**
     * getRow
     *
     * @param  mixed $table
     * @param  mixed $where
     * @return void
     */
    public function getRow($table, $where){
        return $this->db->get_where($table, $where)->num_rows();
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
    
    /**
     * insertAll
     *
     * @param  mixed $table
     * @param  mixed $data
     * @return void
     */
    public function insertAll($table, $data){
        $this->db->insert_batch($table, $data);
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

    /**
     * TRUNCATE
     *
     * @return void
     */
    public function truncate($table){
        $this->db->truncate($table);
    }
    
}

/* End of file _general_m.php */
