<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Posisi_m extends CI_Model {
    protected $table = 'master_position';
    protected $table_employee = "master_employee";
    protected $table_level = 'master_level';

    public function __construct()
    {
        // ambil nama table yang terupdate
        $this->load->library('tablename');
        $this->table = $this->tablename->get($this->table);
    }

    public function getAll(){
        return $this->db->get_where($this->table)->result_array();
    }
    
    /**
     * get Position data using custom where
     * getWhere
     *
     * @param  mixed $where
     * @return void
     */
    public function getAllWhere($where){
        return $this->db->get_where($this->table, $where)->result_array();
    }
    
    /**
     * ambil semua posisi yang mppnya masih tersedia dengan where
     *
     * @param  mixed $where
     * @return void
     */
    function getAllWhere_mppAvailable($where){
        // ambil semua posisi
        $posisi = $this->getAllWhere($where);

        if(!empty($posisi)){
            // pilih posisi yang masih tersedia aja, mppnya masih kosong
            foreach($posisi as $k => $v){
                $posisi_pmk = $this->posisi_m->howMuchOnThisPosition($v['id']);
                // jika jumlah mppnya sama antara yang terisi dengan yang dibutuhkan, hapus datanya
                if($posisi_pmk['filled'] == $posisi_pmk['needed'] || $posisi_pmk['filled'] > $posisi_pmk['needed']){
                    unset($posisi[$k]); // hapus terkain=t
                }
            }
        } else {
            $posisi = "";
        }

        return $posisi;
    }

    /**
     * get Position data using custom where
     * getWhere
     *
     * @param  mixed $where
     * @return void
     */
    public function getAll_whereSelect($select, $where){
        $this->db->select($select);
        return $this->db->get_where($this->table, $where)->result_array();
    }

    public function getMyPosition() {
        // ambil my id dari nik
        $my_id = $this->db->select("position_id")->get_where($this->table_employee, array('nik' => $this->session->userdata('nik')))->row_array()['position_id'];
        // ambil detail position
        return $this->db->get_where($this->table, array('id' => $my_id))->row_array();
    }
    
    /**
     * get salah satu informasi terkait dengan $where
     *
     * @param  mixed $where
     * @return void
     */
    public function getOnceWhere($where){
        return $this->db->get_where($this->table, $where)->row_array();
    }

        
    /**
     * get the table name
     *
     * @return void
     */
    public function getTableName(){
        return $this->table;
    }
    
    /**
     * buat ngecek berapa banyak yang terdaftar dalam suatu posisi
     *
     * @param  mixed $id_posisi
     * @return void
     */
    function howMuchOnThisPosition($id_posisi){
        // ambil data siapa aja yang ada di posisi ini
        $whois = $this->whoIsOnThisPosition($id_posisi);
        $counterHowMuch = count($whois);

        // ambil data mpp
        $posisi_mpp = $this->_general_m->getOnce('mpp', $this->table, array('id' => $id_posisi))['mpp'];

        return(array(
            'filled' => $counterHowMuch,
            'needed' => $posisi_mpp
        ));
    }
    
    /**
     * get master level information on id parameter
     *
     * @param  mixed $id
     * @return void
     */
    function whatLevelIsThis($id){
        return $this->db->get_where($this->table_level, array('id' => $id))->row_array();
    }
    
    /**
     * Siapa yang ada di posisi ini? dan dapatkan detailnya
     *
     * @param  mixed $id
     * @return void
     */
    public function whoIsOnThisPosition($id){
        $this->db->select("id, position_name, dept_id, div_id, id_atasan1, id_atasan2, assistant, id_approver1, id_approver2, mpp, hirarki_org, job_grade, nik, emp_name, position_id, id_entity, role_id, email");
        $this->db->join($this->table_employee, "$this->table_employee.position_id = $this->table.id", 'left');
        return $this->db->get_where($this->table, array("$this->table_employee.position_id" => $id))->result_array();
    }

    /**
     * Siapakah approver dengan id_posisi
     *
     * @param  mixed $id_position
     * @return void
     */
    public function whoApprover($id_position){
        // ambil data my position
        $my_position = $this->getOnceWhere(array("id" => $id_position));
        //ambil data approver detailnya dan orangnya
        $approver1 = $this->whoIsOnThisPosition($my_position['id_approver1']);
        $approver2 = $this->whoIsOnThisPosition($my_position['id_approver2']);
        if(!empty($approver1)){
            $data_approver['approver1'] = $approver1[0];
        } else {
            $data_approver['approver1'] = "";
        }
        if(!empty($approver2)){
            $data_approver['approver2'] = $approver2[0];
        } else {
            $data_approver['approver2'] = "";
        }
        
        return $data_approver;
    }
    
    /**
     * Siapakah atasan saya
     *
     * @return void
     */
    public function whoMyAtasanS(){
        // ambil data my position
        $my_position = $this->getMyPosition();
        //ambil data atasan detailnya dan orangnya
        $data_atasan['atasan1'] = $this->whoIsOnThisPosition($my_position['id_atasan1']);
        $data_atasan['atasan2'] = $this->whoIsOnThisPosition($my_position['id_atasan2']);
        
        return $data_atasan;
    }
    
    /**
     * Siapakah atasan dengan id_posisi
     *
     * @param  mixed $id_position
     * @return void
     */
    public function whoAtasanS($id_position){
        // ambil data my position
        $my_position = $this->getOnceWhere(array("id" => $id_position));
        //ambil data atasan detailnya dan orangnya
        $data_atasan['atasan1'] = $this->whoIsOnThisPosition($my_position['id_atasan1']);
        $data_atasan['atasan2'] = $this->whoIsOnThisPosition($my_position['id_atasan2']);
        
        return $data_atasan;
    }

}

/* End of file Posisi_m.php */
