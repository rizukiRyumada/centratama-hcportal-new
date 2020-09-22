<?php
// get all details on employee
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_m extends CI_Model {

    // tables
    protected $table = array(
        'employee' => 'master_employee',
        'position' => 'master_position',
        'division' => 'master_division',
        'department' => 'master_department'
    );
    
    /**
     * get All Employee Data with all details
     *
     * @return void
     */
    public function getAllEmp()
    {
        $this->db->select($this->table['employee'].'.nik as id_emp, '.$this->table['employee'].'.emp_name,nik, '.$this->table['position'].'.hirarki_org, position_id, '.$this->table['position'].'.div_id , '.$this->table['position'].'.dept_id, '.$this->table['position'].'.id, position_name, '.$this->table['division'].'.id, division, '.$this->table['department'].'.id, nama_departemen');
        $this->db->from($this->table['position']);
        $this->db->join($this->table['division'], $this->table['division'].'.id = '.$this->table['position'].'.div_id');
        $this->db->join($this->table['department'], $this->table['department'].'.id = '.$this->table['position'].'.dept_id');
        $this->db->join($this->table['employee'], $this->table['employee'].'.position_id = '.$this->table['position'].'.id');
        $this->db->order_by('id_emp', 'asc');
        
        return $this->db->get()->result_array();
    }
    
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
            'left'
        );
        return $this->db->get_where($this->table['employee'], $this->table['employee'].'.nik = "'. $nik .'"')->row_array();
    }

    /**
     * getDetail_employeeAllData
     *
     * @param  mixed $nik
     * @return void
     */
    function getDetail_employeeAllData($nik){
        return $this->db->get_where($this->table['employee'], array('nik' => $nik))->row_array();
    }
    
    /**
     * getDetails_employee
     *
     * @param  mixed $nik
     * @return void
     */
    function getDetails_employee($nik){
        $this->db->select('nik, emp_name, position_name, id_entity, role_id, akses_surat_id, dept_id, div_id, email');
        $this->db->join(
            $this->table['position'], 
            $this->table['employee'].'.position_id='.$this->table['position'].'.id', 
            'left'
        );
        return $this->db->get_where($this->table['employee'], array('nik' => $nik))->row_array();
    }
    
    /**
     * getPosFromNik
     *
     * @param  mixed $nik
     * @return void
     */
    function getPosFromNik($nik){
        $this->db->select($this->table['position'].'.id, '.$this->table['position'].'.dept_id');
        $this->db->join(
            $this->table['position'], 
            $this->table['position'].'.id = '. $this->table['employee'].'.position_id',
            'left');
        return $this->db->get_where($this->table['employee'], $this->table['employee'].'.nik = "'. $nik .'"')->row_array();
    }
    
    /**
     * insert employee data to database
     *
     * @param  mixed $data
     * @return void
     */
    function insert($data){
        $this->db->insert($this->table['employee'], $data);
    }
    
    /**
     * remove employee by nik (DANGEROUS FUNCTION)
     *
     * @param  mixed $nik
     * @return void
     */
    function remove($nik){
        $this->db->where('nik', $nik);
        $this->db->delete($this->table['employee']);
    }
    
    /**
     * update employee data
     *
     * @param  mixed $where
     * @param  mixed $data
     * @return void
     */
    function update($where, $data){
        $this->db->where($where);
        $this->db->update($this->table['employee'], $data);
    }
}

/* End of file Employee_m.php */