<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Dept_model extends CI_Model {

    public function getAll()
    {
        $this->db->select('departemen.*, divisi.division');
        $this->db->from('departemen');
        $this->db->join('divisi', 'divisi.id = departemen.div_id');
        return $this->db->get()->result_array();
    }

    public function getDeptById($id)
    {
        $this->db->select('departemen.id, nama_departemen, div_id');
        $this->db->from('departemen');
        $this->db->join('divisi', 'divisi.id = departemen.div_id', 'left');
        $this->db->where('divisi.division', $id);
        return $this->db->get()->result_array();
    }

    public function ajaxDeptById($id)
    {
        return $this->db->get_where('departemen', ['id' => $id])->row_array();
    }

    public function updateDept()
    {
        $data = [
            'nama_departemen' => $this->input->post('departemen'),
            'dep_head' => $this->input->post('dephead'),
            'div_id' => $this->input->post('div_id')
        ];

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('departemen', $data);
    }

}

/* End of file Dept_model.php */

?>