<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class HealthReport_datatables_m extends CI_Model {

    // set nama tabel
    protected $table = 'healthReport_reports';
    // list table to join
    protected $table1 = 'position';
    // variabel buat index
    protected $joinIndex = 'healthReport_reports.id_posisi = position.id';
    // set kolom yang bisa di order
    protected $column_order = array('date', 'emp_name', 'departement', 'divisi', 'status', 'sickness', 'notes');
    // set kolom yang bisa disearch
    protected $column_search = array('date', 'emp_name', 'departement', 'divisi', 'status', 'sickness', 'notes');
    // set kolom yang diorder secara default
    protected $order = array('first_name' => 'asc');
    
    /**
     * Fetch data members dari database
     *
     * @param $_POST filter data based on the posted parameters
     * @return void
     */
    public function getRows($postData, $where_data_health){
        $this->_get_datatables_query($postData, $where_data_health);
        if($postData['length'] != -1){
            $this->db->limit($postData['length'], $postData['start']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * count semua records
     *
     * @return void
     */
    public function countAll(){
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    
    /**
     * countFiltered records dari yang disearch
     *
     * @param $_POST filter data based on the posted parameters
     * @return void
     */
    public function countFiltered($postData, $where_data_health){
        $this->_get_datatables_query($postData, $where_data_health);
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    /**
     * private function yang menjalankan query sql ke server
     *
     * @param  mixed $postData
     * @return void
     */
    private function _get_datatables_query($postData, $where_data_health){
        $this->db->from($this->table);

        $i = 0;
        // loop di setiap kolom searchable
        foreach($this->column_search as $item){
            // jika dia melakukan search dengan item POST
            if($postData['search']['value']){
                // loop pertama
                if($i == 0){
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                } else {
                    $this->db->or_like($item, $postData['search']['value']);
                }

                // loop terakhir
                if(count($this->column_search) - 1 == $i){
                    // close bracket
                    $this->db->group_end();
                }
            }
            // increament penanda
            $i++;
        }

        $this->db->join($this->table1, $this->joinIndex, 'left');

        $this->db->where($where_data_health);

        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        } elseif(isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

}

/* End of file _datatables.php */
