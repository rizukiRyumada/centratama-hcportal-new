<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Divisi_model extends CI_Model {
    protected $table = "master_division";

    public function getAll()
    {
        $this->db->select($this->table.'.*, master_employee.emp_name');
        $this->db->from($this->table);
        $this->db->join('master_employee', 'master_employee.nik = '.$this->table.'.nik_div_head');
        return $this->db->get()->result_array();
    }

    public function getOnceById($id){
        $this->db->select('id, division');
        $this->db->from($this->table);
        $this->db->where('id', $id);
        return $this->db->get()->result_array();
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
        $this->load->model('employee_m');
        $divhead_nik = $this->db->select("nik_div_head")->get_where($this->table, array('id' => $id_div))->row_array()['nik_div_head'];
        return $this->employee_m->getDetails_employee($divhead_nik);
    }

    public function getDIvByOrg()
    {
        $this->db->select('master_employee.*');
        $this->db->from('master_position');
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
