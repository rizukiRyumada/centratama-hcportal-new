<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jobpro_model extends CI_Model {
    // list table
    protected $table = array(
        'approval' => 'jobprofile_approval',
        'position' => 'master_position',
    );

    function __construct()
    {
        // ambil nama table yang terupdate
        $this->load->library('tablename');
        $this->table['position'] = $this->tablename->get($this->table['position']);
    }

    public function getMyprofile($nik)
    {
        foreach($this->getDetail('position_id', 'master_employee', array('nik' => $nik)) as $v){
            $id_position = $v;
        }
            
        $this->db->select('master_employee.*, master_division.division, master_department.nama_departemen, '. $this->table['position'] .'.position_name, '. $this->table['position'] .'.id_atasan1 as posnameatasan1,
                            '. $this->table['position'] .'.id_atasan2, jobprofile_profilejabatan.tujuan_jabatan, jobprofile_profilejabatan.id_posisi');
        $this->db->from($this->table['position']);
		$this->db->join('master_division', 'master_division.id = '. $this->table['position'] .'.div_id', 'left');
		$this->db->join('master_employee', 'master_employee.position_id = '. $this->table['position'] .'.id', 'left');
		$this->db->join('master_department', 'master_department.id = '. $this->table['position'] .'.dept_id', 'left');
		$this->db->join('jobprofile_profilejabatan', 'jobprofile_profilejabatan.id_posisi = '. $this->table['position'] .'.id', 'left');
        
        $this->db->where($this->table['position'] .'.id', $id_position);
        return $this->db->get()->row_array();
    }

    public function getMyDivisi($nik)
    {
        $this->db->select('*');
        $this->db->from('master_division');
        $this->db->join($this->table['position'], $this->table['position'] .'.div_id = master_division.id');
        $this->db->join('master_employee', 'master_employee.position_id = '. $this->table['position'] .'.id');
        $this->db->where('master_employee.nik', $nik);
        return $this->db->get()->row_array();        
    }
    
    public function getMyDept($nik)
    {
        $this->db->select('*');
        $this->db->from('master_department');
        $this->db->join($this->table['position'], $this->table['position'] .'.dept_id = master_department.id');
        $this->db->join('master_employee', 'master_employee.position_id = '. $this->table['position'] .'.id');
        $this->db->where('master_employee.nik', $nik);
        return $this->db->get()->row_array();        
    }

    public function getPosisi($nik)
    {
        $this->db->select('*');
        $this->db->from($this->table['position']);
        $this->db->join('master_employee', 'master_employee.position_id = '. $this->table['position'] .'.id');
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
        return $this->db->get($this->table['position'])->result_array();
        
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
        $this->db->from($this->table['position']);
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
        $this->db->join($this->table['position'], $this->table['position'] .'.id = master_employee.position_id', 'left');
        $this->db->where($where);
        return $this->db->get()->row_array();
    }

    public function getMyTask($id_position, $atasan, $status_approval){
        $this->db->join($this->table['position'], $this->table['position'] .'.id = jobprofile_approval.id_posisi', 'left');
        return $this->db->get_where('jobprofile_approval', [$atasan => $id_position, 'status_approval' => $status_approval])->result_array();
    }

    public function getPositionDetail($id_posisi){
        $this->db->select('*');
        $this->db->from($this->table['position']);
        $this->db->where(array('id' => $id_posisi, 'assistant' => 0));
        return $this->db->get()->row_array();
    }

    public function getPositionDetailAssistant($id_posisi){
        $this->db->select('*');
        $this->db->from($this->table['position']);
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
        $this->db->from($this->table['position']);
        $this->db->where(array('id_atasan1' => $id_atasan1, 'assistant' => 0));
        return $this->db->get()->result_array();
    }

    public function getWhoisSamaAssistant($id_atasan1){
        $this->db->select('*');
        $this->db->from($this->table['position']);
        $this->db->where(array('id_atasan1' => $id_atasan1, 'assistant' => 1));
        return $this->db->get()->result_array();
    }

    public function getWhoisSamaCEOffice($id_atasan1, $div_id){
        $this->db->select('*');
        $this->db->from($this->table['position']);
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
        $data['atasan']        = $this->getDetail('position_name', $this->table['position'], array('id' => $posisi['id_atasan1']));

        return $data; // kembalikan data
    }

    /* -------------------------------------------------------------------------- */
    /*        function buat mengolah data chart olahDataChart(id_position)        */
    /* -------------------------------------------------------------------------- */
    public function getOrgChartData($my_positionId) {
        // MENGOLAH DATA Master Position menjadi orgchart data ===========================================================
        //sebelumnya ingat ada beberapa hal yang harus diperhatikan
        // 1. posisi Asistant dan bukan assistant berbeda perlakuannya juga berbeda
        // 2. kode ini digunakan untuk mengolah data dari database menjadi JSON
        // 3. nomor 200 dan 201 itu adalah id_posisi, dijadikan permisalan

        $my_pos_detail = $this->getPositionDetail($my_positionId); //ambil informasi detail posisi saya //200 //bukan assistant
        if(empty($my_pos_detail)){
            $my_pos_detail = $this->getPositionDetailAssistant($my_positionId);
        }
        
        $x = 0; $y = 0; $atasan = 0; //untuk penanda looping
        if(!empty($my_pos_detail)){//if data exist
            $my_atasan[$x]['id_atasan1'] = $my_pos_detail['id_atasan1'];
            $id_atasan1 = $my_pos_detail['id_atasan1'];
            $id_atasan2 = $my_pos_detail['id_atasan2'];

            if($my_pos_detail['id_atasan2'] != 1 && $my_pos_detail['id_atasan2'] != 0 && $my_pos_detail['div_id'] != 1){//apakah atasan 2 nya bukan CEO atau dan dia punya atasan 2
                //cari posisi yang bukan assistant
                $whois_sama[0] = $this->getWhoisSama($id_atasan1); //200 dan 201 ambil data yang sama sama saya yang bukan assistant
                $whois_sama[1] = $this->getWhoisSama($id_atasan2); //200 dan 201 ambil data yang sama sama saya yang bukan assistant
                $my_atasan[0] = $this->getPositionDetail($id_atasan1); //ambil informasi daftar atasan saya yang bukan assistant
                $my_atasan[1] = $this->getPositionDetail($id_atasan2); //ambil informasi daftar atasan saya yang bukan assistant
                //cari posisi yang assistant
                if(!empty($whois_sama_assistant1[$y] = $this->getWhoisSamaAssistant($id_atasan1))){ //cari assistant atasan 1
                    $y++;
                    $assistant_atasan1 = 1; //tandai buat nanti nampilin orgchartnya horizontal di level ke 3
                } else {
                    $assistant_atasan1 = 0; //tandai buat nanti nampilin orgchartnya horizontal di level ke 3
                }
                if(!empty($whois_sama_assistant2[$y] = $this->getWhoisSamaAssistant($id_atasan2))){ //cari assistant atasan 2
                    $y++;
                } else {
                    //nothing
                }
                $atasan = 2; //penanda atasan

            } elseif ($my_pos_detail['id_atasan2'] != 0 && $my_pos_detail['div_id'] == 1){
                //cari posisi yang bukan assistant                
                $whois_sama[0] = $this->getWhoisSamaCEOffice($id_atasan1, '1'); //200 dan 201 ambil data yang sama sama saya yang bukan assistant
                $whois_sama[1] = $this->getWhoisSamaCEOffice($id_atasan2, '1'); //200 dan 201 ambil data yang sama sama saya yang bukan assistant
                $my_atasan[0] = $this->getPositionDetail($id_atasan1); //ambil informasi daftar atasan saya yang bukan assistant
                $my_atasan[1] = $this->getPositionDetail($id_atasan2); //ambil informasi daftar atasan saya yang bukan assistant
                //cari posisi yang assistant
                if(!empty($whois_sama_assistant1[$y] = $this->getWhoisSamaAssistant($id_atasan1))){ //cari assistant atasan 1
                    $y++;
                    $assistant_atasan1 = 1; //tandai buat nanti nampilin orgchartnya horizontal di level ke 3
                } else {
                    $assistant_atasan1 = 0; //tandai buat nanti nampilin orgchartnya horizontal di level ke 3
                }
                if(!empty($whois_sama_assistant2[$y] = $this->getWhoisSamaAssistant($id_atasan2))){ //cari assistant atasan 2
                    $y++;
                } else {
                    //nothing
                }
                $atasan = 2; //penanda atasan

            } elseif($my_pos_detail['id_atasan1'] == 1 && $my_pos_detail['hirarki_org'] == 'N'){
                // cari posisi yang bukan assistant
                $whois_sama[0] = $this->getDetails("*", $this->table['position'], 'id_atasan1 = "1" AND div_id != "1"');
                $my_atasan[0] = $this->getDetail("*", $this->table['position'], array('id' => $my_pos_detail['id_atasan1']));
                
                $assistant_atasan1 = 0; //tandai buat nanti nampilin orgchartnya horizontal di level ke 3
                $atasan = 1; // penanda jumlah atasan
                
            } else {
                if($my_pos_detail['id_atasan1'] != 1 && $my_pos_detail['id_atasan1'] != 0 && $my_pos_detail['div_id'] != 1){ //apakah atasan 1nya bukan CEO atau dia punya atasan

                    //cari posisi yang bukan assistant
                    $whois_sama[0] = $this->getWhoisSama($id_atasan1); //200 dan 201 ambil data yang sama sama saya yang bukan assistant
                    $my_atasan[0] = $this->getPositionDetail($id_atasan1); //ambil informasi daftar atasan saya yang bukan assistant
                    //cari posisi yang assistant
                    if(!empty($whois_sama_assistant1[$y] = $this->getWhoisSamaAssistant($id_atasan1))){ //cari assistant atasan 1
                        $y++;
                    } else {
                        //nothing
                    }
                    $assistant_atasan1 = 0; //tandai buat nanti nampilin orgchartnya horizontal di level ke 3
                    $atasan = 1;//penanda atasan
                } elseif($my_pos_detail['id_atasan1'] == 1 && $my_pos_detail['div_id'] == 1){

                    //cari posisi yang bukan assistant
                    $whois_sama[0] = $this->getWhoisSamaCEOffice($id_atasan1, '1'); //200 dan 201 ambil data yang sama sama saya yang bukan assistant
                    $my_atasan[0] = $this->getPositionDetail($id_atasan1); //ambil informasi daftar atasan saya yang bukan assistant
                    //cari posisi yang assistant
                    if(!empty($whois_sama_assistant1[$y] = $this->getWhoisSamaAssistant($id_atasan1))){ //cari assistant atasan 1
                        $y++;
                    } else {
                        //nothing
                    }

                    $assistant_atasan1 = 0; //tandai buat nanti nampilin orgchartnya horizontal di level ke 3
                    $atasan = 1;//penanda atasan
                } else {
                    //nothing
                    $assistant_atasan1 = 0; //tandai buat nanti nampilin orgchartnya horizontal di level ke 3
                    $atasan = 0;//penanada atasan
                }
            }

            //cari id yang sama dengan $my_pos_detail di $whois_sama, lalu tambahin 'className': 'my-position' //jika my position bukan assistant
            foreach($whois_sama as $k => $v){
                foreach ($v as $key => $value){
                    if($my_pos_detail['id'] == $value['id']){
                        $whois_sama[$k][$key]['className'] = 'my-position';
                    }
                }
            }

            //reverse arraynya dulu
            $whois_sama = array_reverse($whois_sama);
            $my_atasan = array_reverse($my_atasan);

            //gabungkan array $whois_sama dengan $my_atasan
            $org_struktur = $my_atasan;
            foreach($my_atasan as $k => $v){
                $org_struktur[$k]['children'] = $whois_sama[$k];
            }

            if($atasan == 2){ //gabungkan array[0] dengan [1]; kalo dia punya atasan 1 dan 2
                $i = 0;
                foreach($org_struktur[1]['children'] as $key => $value){
                    foreach($org_struktur[0]['children'] as $k => $v){
                        if($org_struktur[1]['id'] == $org_struktur[0]['children'][$k]['id']){
                            $org_struktur[0]['children'][$k]['children'][$i] = $value;
                            $i++;
                        }
                    }
                }
            } else {
                //nothing
            }

            //ASSISTANT DATA
            //keluarkan semua assistant jadi di level teratas
            $org_assistant1 = array(); $x = 0; //initialize assistant atasan 1
            if(!empty($whois_sama_assistant1)){
                foreach($whois_sama_assistant1 as $k => $v){
                    foreach($v as $key => $value){
                        $org_assistant1[$x] = $value; //tambah value ke org_struktur
                        foreach($this->getAtasanAssistant($value['id_atasan1']) as $kunci => $nilai){ //cari atasannya 
    
                            // array_push($org_assistant[$x], $nilai); //tambah nama posisi atasannya
                            $org_assistant1[$x]['atasan_assistant'] = $nilai; //tambah nama posisi atasannya
                        }
                        $x++;
                    }
                }
            }
            if(!empty($whois_sama_assistant2)){
                $org_assistant2 = array(); $x = 0; //initialize assistant atasan 2
                foreach($whois_sama_assistant2 as $k => $v){
                    foreach($v as $key => $value){
                        $org_assistant2[$x] = $value; //tambah value ke org_struktur
                        foreach($this->getAtasanAssistant($value['id_atasan1']) as $kunci => $nilai){ //cari atasannya 
                            // array_push($org_assistant[$x], $nilai); //tambah nama posisi atasannya
                            $org_assistant2[$x]['atasan_assistant'] = $nilai; //tambah nama posisi atasannya
                        }
                        $x++;
                    }
                }
            }else{
                $org_assistant2 = array();
            }

            //jika assistant adalah my-position tambahkan className my-position
            foreach($org_assistant1 as $k => $v){ //cek di assistan atasan 1
                if($my_pos_detail['id'] == $v['id']){
                    $org_assistant1[$k]['className'] = 'my-position';
                }
            }
            foreach($org_assistant2 as $k => $v){ //cek di assistan atasan 2
                if($my_pos_detail['id'] == $v['id']){
                    $org_assistant2[$k]['className'] = 'my-position';
                }
            } 
            
            //simpan data assistant dalam bentuk JSON
            // $data['orgchart_data'] = json_encode($org_struktur[0]); //masukkan data orgchart yang sudah diolah ke JSON
            // $data['orgchart_data_assistant'] = json_encode($org_assistant);
            return array(json_encode($org_struktur[0]), json_encode($org_assistant1), json_encode($org_assistant2), $assistant_atasan1, $atasan);

        } else { //if orgchart data doesn't exist
            // $data['orgchart_data_assistant'] = json_encode("");
            // $data['orgchart_data'] = json_encode("");
            // print_r('gaada bro');
            exit;
        }
        // End of Pengolahan data orgchart ==============================================================================
    }
}

/* End of file Jobpro_model.php */
