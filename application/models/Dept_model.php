<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Dept_model extends CI_Model {

    public function getAll()
    {
        $this->db->select('master_department.*, master_division.division');
        $this->db->from('master_department');
        $this->db->join('master_division', 'master_division.id = master_department.div_id');
        return $this->db->get()->result_array();
    }

    public function getDeptById($id)
    {
        $this->db->select('master_department.id, nama_departemen, div_id');
        $this->db->from('master_department');
        $this->db->join('master_division', 'master_division.id = master_department.div_id', 'left');
        $this->db->where('master_division.division', $id);
        return $this->db->get()->result_array();
    }

    public function ajaxDeptById($id)
    {
        return $this->db->get_where('master_department', ['id' => $id])->row_array();
    }

    public function updateDept()
    {
        $data = [
            'nama_departemen' => $this->input->post('master_department'),
            'dep_head' => $this->input->post('dephead'),
            'div_id' => $this->input->post('div_id')
        ];

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('master_department', $data);
    }

}

/* End of file Dept_model.php */

?>