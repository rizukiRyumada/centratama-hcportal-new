<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Divisi_model extends CI_Model {

    public function getAll()
    {
        $this->db->select('divisi.*, employe.emp_name');
        $this->db->from('divisi');
        $this->db->join('employe', 'employe.nik = divisi.nik_div_head');
        return $this->db->get()->result_array();
    }

    public function updateDiv()
    {
        $data = [
            'division' => $this->input->post('divisi'),
            'nik_div_head' => $this->input->post('div_head')
        ];

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('divisi', $data);
    }

    public function getDIvByOrg()
    {
        $this->db->select('employe.*');
        $this->db->from('position');
        $this->db->join('employe', 'employe.position_id = position_id', 'left');
        $this->db->where(array('hirarki_org' => 'N'));
        return $this->db->get()->result_array();
    }

    public function ajaxDIvById($id)
    {
        return $this->db->get_where('divisi', ['id' => $id])->row_array();
    }

    public function getDivisi()
    {
        $this->db->select('*');
        $this->db->from('divisi');
        return $this->db->get()->result_array();
    }
}

/* End of file Divisi_model.php */
