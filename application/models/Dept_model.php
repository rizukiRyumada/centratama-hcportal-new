<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Dept_model extends CI_Model {
    protected $table = "master_department";

    /**
     * get dept head information by id
     *
     * @param  mixed $id
     * @return void
     */
    function get_headById($id){
        // ambil nik dephead
        $nik = $this->_general_m->getOnce('dep_head', $this->table, array('id' => $id))['dep_head'];

        // lengkapi data head
        $this->load->model('employee_m');
        if($nik != "#N/A"){ // cek apa ada headnya?
            return $this->employee_m->getDetails_employee($nik);
        } else {
            return "";
        }
    }
    
    /**
     * get All data without where
     *
     * @return void
     */
    public function getAll()
    {
        $this->db->select('master_department.*, master_division.division');
        $this->db->from('master_department');
        $this->db->join('master_division', 'master_division.id = master_department.div_id');
        return $this->db->get()->result_array();
    }
    
    /**
     * get all data with where data
     *
     * @param  mixed $where
     * @return void
     */
    function getAll_where($where){
        return $this->db->get_where($this->table, $where)->result_array();
    }
    
    /**
     * getDeptById
     *
     * @param  mixed $id
     * @return void
     */
    public function getDeptById($id)
    {
        $this->db->select('master_department.id, nama_departemen, div_id');
        $this->db->from('master_department');
        $this->db->join('master_division', 'master_division.id = master_department.div_id', 'left');
        $this->db->where('master_division.division', $id);
        return $this->db->get()->result_array();
    }
    
    /**
     * ambil depthead pada divisi dan department dipilih
     *
     * @param  mixed $id_div
     * @param  mixed $id_dept
     * @return void
     */
    public function getDeptHead($id_div, $id_dept){
        // cek apa departement buat assistant
        $dept_assistant = $this->_general_m->getRow($this->table, array('id' => $id_dept, 'is_assistant' => 1));
        if($dept_assistant < 1){
            // ambil posisi depthead
            $depthead_pos = $this->posisi_m->getOnceWhere(array('div_id' => $id_div, 'dept_id' => $id_dept, 'is_head' => 2));
            $result = $this->posisi_m->whoIsOnThisPosition($depthead_pos['id']);
            if(empty($result)){
                return ""; // ambil karyawan pertama untuk depthead
            } else {
                return $result[0]; // ambil karyawan pertama untuk depthead
            }
        } else {
            return "";
        }
    }
    
    /**
     * getDetailById
     *
     * @param  mixed $dept_id
     * @return void
     */
    function getDetailById($dept_id){
        $this->db->select('id, nama_departemen');
        $this->db->from($this->table);
        $this->db->where('id', $dept_id);
        return $this->db->get()->row_array();
    }
    
    /**
     * updateDept
     *
     * @return void
     */
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

/* -------------------------------------------------------------------------- */
/*                                  for AJAX                                  */
/* -------------------------------------------------------------------------- */

    public function ajaxDeptById($id)
    {
        return $this->db->get_where('master_department', ['id' => $id])->row_array();
    }


}

/* End of file Dept_model.php */

?>