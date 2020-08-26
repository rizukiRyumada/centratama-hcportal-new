<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jobpro_model extends CI_Model {
    // list table
    protected $table = array(
        'approval' => 'jobprofile_approval'
    );

    public function getMyprofile($nik)
    {
        foreach($this->getDetail('position_id', 'master_employee', array('nik' => $nik)) as $v){
            $id_position = $v;
        }
            
        $this->db->select('master_employee.*, master_division.division, master_department.nama_departemen, master_position.position_name, master_position.id_atasan1 as posnameatasan1,
                            master_position.id_atasan2, jobprofile_profilejabatan.tujuan_jabatan, jobprofile_profilejabatan.id_posisi');
        $this->db->from('master_position');
		$this->db->join('master_division', 'master_division.id = master_position.div_id', 'left');
		$this->db->join('master_employee', 'master_employee.position_id = master_position.id', 'left');
		$this->db->join('master_department', 'master_department.id = master_position.dept_id', 'left');
		$this->db->join('jobprofile_profilejabatan', 'jobprofile_profilejabatan.id_posisi = master_position.id', 'left');
        
        $this->db->where('master_position.id', $id_position);
        return $this->db->get()->row_array();
    }

    public function getMyDivisi($nik)
    {
        $this->db->select('*');
        $this->db->from('master_division');
        $this->db->join('master_position', 'master_position.div_id = master_division.id');
        $this->db->join('master_employee', 'master_employee.position_id = master_position.id');
        $this->db->where('master_employee.nik', $nik);
        return $this->db->get()->row_array();        
    }
    
    public function getMyDept($nik)
    {
        $this->db->select('*');
        $this->db->from('master_department');
        $this->db->join('master_position', 'master_position.dept_id = master_department.id');
        $this->db->join('master_employee', 'master_employee.position_id = master_position.id');
        $this->db->where('master_employee.nik', $nik);
        return $this->db->get()->row_array();        
    }

    public function getPosisi($nik)
    {
        $this->db->select('*');
        $this->db->from('master_position');
        $this->db->join('master_employee', 'master_employee.position_id = master_position.id');
        $this->db->where('master_employee.nik', $nik);
        return $this->db->get()->row_array();  
    }

    public function getProfileJabatan($id)
    {
        return $this->db->get_where('jobprofile_profilejabatan', ['id_posisi' => $id])->row_array();
    }

    public function getAllPosition()
    {
        $this->db->order_by("position_name", "asc");
        return $this->db->get('master_position')->result_array();
        
    }

    public function getTujabById($id)
    {
        return $this->db->get_where('jobprofile_profilejabatan', ['id_posisi' => $id])->row_array();
    }

    public function getTjById($id)
    {
        return $this->db->get_where('jobprofile_tanggungjawab', ['id_tgjwb' => $id])->row_array();
    }

    public function updateJP()
    {
        $data = [
            'keterangan' => $this->input->post('tanggung_jawab'),
            'list_aktivitas' => $this->input->post('aktivitas'),
            'list_pengukuran' => $this->input->post('pengukuran')
        ];
        $this->db->where('id_tgjwb', $this->input->post('id'));
        $this->db->update('jobprofile_tanggungjawab', $data);
    }

    public function updateTuJab()
    {
        $data = [
            'tujuan_jabatan' => $this->input->post('tujuan_jabatan')
        ];
        $this->db->where('id_posisi', $this->input->post('id'));
        $this->db->update('jobprofile_profilejabatan', $data);
    }

    public function upTuj($id, $tujuan)
    {
        $this->db->where('id_posisi', $id);
        $this->db->update('jobprofile_profilejabatan', ['tujuan_jabatan' => $tujuan]);
    }

    public function updateWen($id, $value, $modul)
    {
        $this->db->where(array("id"=>$id));
        $this->db->update("jobprofile_wewenang",array($modul=>$value));
    }

    public function getKualifikasiById($id)
    {
        return $this->db->get_where('jobprofile_kualifikasi', ['id_posisi' => $id])->row_array();
    }
	public function getStaff($id)
	{
		return $this->db->get_where('jobprofile_jumlahstaff', ['id_posisi' => $id])->row_array();
    }
    
    
//Ryu codes start here ====================================================================================================
    public function delete($table, $where){
        $this->db->where($where['index'], $where['data']);
        $this->db->delete($table);
    }

    public function getAll($table)
    {
        return $this->db->get($table)->result_array();
    }
    public function getAllAndOrder($order, $table)
    {
        $this->db->order_by($order, "asc");
        return $this->db->get($table)->result_array();
    }

    public function getAtasanAssistant($id_atasan1){
        $this->db->select('position_name');
        $this->db->from('master_position');
        $this->db->where(array('id' => $id_atasan1));
        return $this->db->get()->row_array();
    }

    public function getDetail($select, $table, $where){
        $this->db->select($select);
        $this->db->from($table);
        $this->db->where($where);
        return $this->db->get()->row_array();
    }
    public function getDetails($select, $table, $where){
        $this->db->select($select);
        $this->db->from($table);
        $this->db->where($where);
        return $this->db->get()->result_array();
    }
    public function getEmployeDetail($select, $table, $where){
        $this->db->select($select);
        $this->db->from($table);
        $this->db->join('master_position', 'master_position.id = master_employee.position_id', 'left');
        $this->db->where($where);
        return $this->db->get()->row_array();
    }

    public function getMyTask($id_position, $atasan, $status_approval){
        $this->db->join('master_position', 'master_position.id = jobprofile_approval.id_posisi', 'left');
        return $this->db->get_where('jobprofile_approval', [$atasan => $id_position, 'status_approval' => $status_approval])->result_array();
    }

    public function getPositionDetail($id_posisi){
        $this->db->select('*');
        $this->db->from('master_position');
        $this->db->where(array('id' => $id_posisi, 'assistant' => 0));
        return $this->db->get()->row_array();
    }

    public function getPositionDetailAssistant($id_posisi){
        $this->db->select('*');
        $this->db->from('master_position');
        $this->db->where(array('id' => $id_posisi, 'assistant' => 1));
        return $this->db->get()->row_array();
    }

    public function getJoin2tables($select, $table, $join, $where){
        $this->db->select($select);
        $this->db->join($join['table'], $join['index'], $join['position']);
        return $this->db->get_where($table, $where)->result_array();
    }

    public function getWhoisSama($id_atasan1){
        $this->db->select('*');
        $this->db->from('master_position');
        $this->db->where(array('id_atasan1' => $id_atasan1, 'assistant' => 0));
        return $this->db->get()->result_array();
    }

    public function getWhoisSamaAssistant($id_atasan1){
        $this->db->select('*');
        $this->db->from('master_position');
        $this->db->where(array('id_atasan1' => $id_atasan1, 'assistant' => 1));
        return $this->db->get()->result_array();
    }

    public function getWhoisSamaCEOffice($id_atasan1, $div_id){
        $this->db->select('*');
        $this->db->from('master_position');
        $this->db->where(array('id_atasan1' => $id_atasan1, 'assistant' => 0, 'div_id' => $div_id));
        return $this->db->get()->result_array();
    }

    //  ambil data approval
    public function getApproval($id_posisi){
        return $this->db->get_where($this->table['approval'], array('id_posisi' => $id_posisi))->row_array();
    }

    public function updateApproval($data, $id_posisi){
        $this->db->where('id_posisi', $id_posisi);
        $this->db->update('jobprofile_approval', $data);
    }

    public function insert($table, $data){
        $this->db->insert($table, $data);
    }

    public function update($table, $where, $data){
        $this->db->where($where['db'], $where['server']);
        $this->db->update($table, $data);
    }

    // GET JobProfile Data
    public function getJobProfileData($posisi){
        //load model
        // $this->load->model('Jobpro_model');
        $data['posisi']        = $posisi;
        $data['mydiv']         = $this->getDetail("*", 'master_division', array('id' => $posisi['div_id']));
        $data['mydept']        = $this->getDetail('*', 'master_department', array('id' => $posisi['dept_id']));
        $data['staff']         = $this->getStaff($posisi['id']);
        $data['tujuanjabatan'] = $this->getProfileJabatan($posisi['id']);

        $data['tujuanjabatan'] = $this->getProfileJabatan($posisi['id']);                                                     //data tujuan jabatan
        $data['ruangl']        = $this->getDetail('*', 'jobprofile_ruanglingkup', array('id_posisi' => $posisi['id']));       //data ruang lingkup
        $data['tu_mu']         = $this->getDetail('*', 'jobprofile_tantangan', array('id_posisi' => $posisi['id']));          // data tanggung jawab dan masalah utama
        $data['kualifikasi']   = $this->getDetail('*', 'jobprofile_kualifikasi', array('id_posisi' => $posisi['id']));
        $data['jenk']          = $this->getDetail('*', 'jobprofile_jenjangkar', array('id_posisi' => $posisi['id']));
        $data['hub']           = $this->getDetail('*', 'jobprofile_hubkerja', array('id_posisi' => $posisi['id']));
        $data['tgjwb']         = $this->getDetails('*', 'jobprofile_tanggungjawab', array('id_posisi' => $posisi['id']));
        $data['wen']           = $this->getDetails('*', 'jobprofile_wewenang', array('id_posisi' => $posisi['id']));
        $data['atasan']        = $this->getDetail('position_name', 'master_position', array('id' => $posisi['id_atasan1']));

        return $data; // kembalikan data
    }
}

/* End of file Jobpro_model.php */
