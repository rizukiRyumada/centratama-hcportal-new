<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Divisi_model extends CI_Model {
    protected $table = "master_division";
    protected $table_position = 'master_position';

    function __construct()
    {
        // ambil nama table yang terupdate
        $this->load->library('tablename');
        $this->table_position = $this->tablename->get($this->table_position);
    }

    public function getAll()
    {
        $this->db->select($this->table.'.*, master_employee.emp_name');
        $this->db->from($this->table);
        $this->db->join('master_employee', 'master_employee.nik = '.$this->table.'.nik_div_head');
        return $this->db->get()->result_array();
    }

    public function getAll_where($where)
    {
        $this->db->select($this->table.'.*, master_employee.emp_name');
        $this->db->join('master_employee', 'master_employee.nik = '.$this->table.'.nik_div_head');
        return $this->db->get_where($this->table, $where)->result_array();
    }

    public function getOnceById($id){
        $this->db->select('id, division, nik_div_head, color');
        $this->db->from($this->table);
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    public function getOnceWhere($where){
        return $this->db->get_where($this->table, $where)->row_array();
    }

    public function updateDiv()
    {
        $data = [
            'division' => $this->input->post('divisi'),
            'nik_div_head' => $this->input->post('div_head')
        ];

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('master_division', $data);
    }
    
    /**
     * ambil divisi head dengan 1 id
     *
     * @param  mixed $id_div
     * @return void
     */
    function get_divHead($id_div){
        // ambil posisi depthead
        $divhead_pos = $this->posisi_m->getOnceWhere(array('div_id' => $id_div, 'is_head' => 1));
        $result = $this->posisi_m->whoIsOnThisPosition($divhead_pos['id']);
        if(empty($result)){
            return ""; // ambil karyawan pertama untuk depthead
        } else {
            return $result[0]; // ambil karyawan pertama untuk depthead
        }
    }
    
    /**
     * get div head information by id
     *
     * @param  mixed $id
     * @return void
     */
    function get_headById($id){
        // ambil nik divhead
        $nik = $this->_general_m->getOnce('nik_div_head', $this->table, array('id' => $id))['nik_div_head'];

        // lengkapi data head
        $this->load->model('employee_m');
        if($nik != "#N/A"){ // cek apa ada headnya?
            return $this->employee_m->getDetails_employee($nik);
        } else {
            return "";
        }
    }

    public function getDIvByOrg()
    {
        $this->db->select('master_employee.*');
        $this->db->from($this->table_position );
        $this->db->join('master_employee', 'master_employee.position_id = position_id', 'left');
        $this->db->where(array('hirarki_org' => 'N'));
        return $this->db->get()->result_array();
    }

    public function ajaxDIvById($id)
    {
        return $this->db->get_where('master_division', ['id' => $id])->row_array();
    }

    public function getDivisi()
    {
        $this->db->select('*');
        $this->db->from('master_division');
        return $this->db->get()->result_array();
    }

    public function getWhere($where){
        return $this->db->get_where($this->table, $where)->result_array();
    }
    
    /**
     * Ambil data detail divisi
     *
     * @param  mixed $dept_id
     * @return void
     */
    function getDetailById($id_div){
        $this->db->select('id, division');
        $this->db->from($this->table);
        $this->db->where('id', $id_div);
        return $this->db->get()->row_array();
    }
}

/* End of file Divisi_model.php */
