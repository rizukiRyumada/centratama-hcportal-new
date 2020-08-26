<?php
// get all details on employee
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_m extends CI_Model {

    // tables
    protected $table = array(
        'employee' => 'master_employee',
        'position' => 'master_position'
    );
    
    /**
     * get dept id and div id from nik
     * getDeptDivFromNik
     *
     * @param  mixed $nik
     * @return void
     */
    function getDeptDivFromNik($nik){
        $this->db->select($this->table['position'].'.div_id, '.$this->table['position'].'.dept_id');
        $this->db->join(
            $this->table['position'], 
            $this->table['position'].'.id = '. $this->table['employee'].'.position_id',
            'left');
        return $this->db->get_where($this->table['employee'], $this->table['employee'].'.nik = "'. $nik .'"')->result_array();
    }

    function getPosFromNik($nik){
        $this->db->select($this->table['position'].'.id, '.$this->table['position'].'.dept_id');
        $this->db->join(
            $this->table['position'], 
            $this->table['position'].'.id = '. $this->table['employee'].'.position_id',
            'left');
        return $this->db->get_where($this->table['employee'], $this->table['employee'].'.nik = "'. $nik .'"')->result_array();
    }
}

/* End of file Employee_m.php */