<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pmk_m extends CI_Model {
    protected $table = [
        "contract" => "master_employee_contract",
        "counter" => "_counter_trans",
        "main" => "pmk_form",
        "pertanyaan" => "pmk_survey_pertanyaan",
        "pertanyaan_tipe" => "pmk_survey_pertanyaan_tipe",
        "position" => "master_position",
        "status" => "pmk_status",
        "survey" => "pmk_survey_hasil"
    ];
    
    /**
     * hapus hasil survey assessment
     * ini digunakan sebelum melakukan save supaya tidak terjadi duplikat data
     *
     * @param  mixed $id
     * @return void
     */
    function delete_assessment($id){
        $this->db->delete($this->table['survey'], array('id' => $id));  // Produces: // DELETE FROM mytable  // WHERE id = $id
    }

    /**
     * get pmk list with status
     *
     * @param  mixed $where
     * @return void
     */
    function get_pmkList($where){
        $this->db->select($this->table['main'].".id_entity, ".$this->table['main'].".id_div, ".$this->table['main'].".id_dept, ".$this->table['main'].".id_pos, ".$this->table['main'].".id_time, ".$this->table['main'].".time_modified, ".$this->table['main'].".status, ".$this->table['main'].".status_now");
        $this->db->join($this->table['status'], $this->table['main'].".status_now = ".$this->table['status'].".id", 'left');
        return $this->db->get_where($this->table['main'], $where)->result_array();
    }
    
    /**
     * getAll pmk form data
     *
     * @return void
     */
    function getAll(){
        return $this->db->get($this->table['main'])->result_array();
    }

    /**
     * ambil semua data contract yang terakhir di setiap karyawan
     *
     * @return void
     */
    function getAll_LastContract(){
        return $this->db->query("SELECT nik, MAX(contract) AS contract FROM ".$this->table['contract']." GROUP BY nik ORDER BY nik")->result_array();
    }
    
    /**
     * get semua pertanyaan assessment
     *
     * @return void
     */
    function getAll_pertanyaan(){
        return $this->db->get($this->table['pertanyaan'])->result_array();
    }
    
    /**
     * get all pmk status
     *
     * @return void
     */
    function getAll_pmkStatus(){
        return $this->db->get_where($this->table['status'])->result_array();
    }
        
    /**
     * getAll_pmkList
     *
     * @return void
     */
    function getAll_pmkList(){
        $this->db->select($this->table['main'].".id_entity, ".$this->table['main'].".id_div, ".$this->table['main'].".id_dept, ".$this->table['main'].".id_pos, ".$this->table['main'].".id_time, ".$this->table['main'].".time_modified, ".$this->table['main'].".status, ".$this->table['main'].".status_now");
        return $this->db->get_where($this->table['main'])->result_array();
    }
    
    /**
     * ambil semua tipe pertanyaan survey assessment dari database
     *
     * @return void
     */
    function getAll_IdSurveyPertanyaanTipe(){
        $result = array();
        foreach($this->db->select('id_pertanyaan_tipe')->get($this->table['pertanyaan_tipe'])->result_array() as $k => $v){
            $result[$k] = $v['id_pertanyaan_tipe'];
        }
        return $result;
    }
    
    /**
     * ambil semua pertanyaan assessment
     *
     * @return void
     */
    function getAll_surveyPertanyaan(){
        return $this->db->get($this->table['pertanyaan'])->result_array();
    }
    
    /**
     * get list of assessment using ajax
     *
     * @param  mixed $position_my
     * @param  mixed $showhat
     * @param  mixed $filter_divisi
     * @param  mixed $filter_departemen
     * @param  mixed $filter_status
     * @param  mixed $filter_daterange
     * @return void
     */
    function getComplete_pmkList($position_my, $showhat, $filter_divisi, $filter_departemen, $filter_status, $filter_daterange){
        // cek apa dia admin, superadmin, hc divhead, atau CEO
        // TODO bagaimana buat divhead HC sama CEO, tampilkan modul assessment
        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1 || $position_my['id'] == 1 || $position_my['id'] == 196){
            $where = ""; // where untuk filtering
            // filter divisi dan departemen khusus admin dan hc divhead
            if($position_my['id'] == 196 && $showhat == 0){
                $where .= $this->table['position'].".div_id = '6'";
            } elseif($position_my['hirarki_org'] == "N" && $showhat == 0){ // ambil data form di divisi dia aja
                $where = $this->table['position'].".div_id = ".$position_my['div_id'];
                // if(!empty($filter_departemen)){
                //     $where .= " AND ".$this->table['position'].".dept_id = ".explode("-", $filter_departemen)[1];
                // }
            } elseif($position_my['hirarki_org'] == "N-1" && $showhat == 0){
                // ambil data form di divisi dan departemen dia
                $where = $this->table['position'].".div_id = ".$position_my['div_id']." AND ".$this->table['position'].".dept_id = ".$position_my['dept_id'];
                // ambil data di divisi dan departemen dia
            } elseif($position_my['hirarki_org'] == "N-2" && $showhat == 0){
                $where = $this->table['position'].".div_id = ".$position_my['div_id']." AND ".$this->table['position'].".dept_id = ".$position_my['dept_id']." AND ".$this->table['position'].".id_approver1 = ".$position_my['id'];
            }

            // ambil semua data form dengan filter
            if(!empty($filter_divisi)){
                if(empty($where)){ // jika where sebelumnya kosong
                    $where .= $this->table['position'].".div_id = ".explode("-", $filter_divisi)[1];
                } else {
                    $where .= " AND ".$this->table['position'].".div_id = ".explode("-", $filter_divisi)[1];
                }
            }
            if(!empty($filter_departemen)){
                $where .= " AND ".$this->table['position'].".dept_id = ".explode("-", $filter_departemen)[1];
            }
            if(!empty($where)){
                $data_emp = $this->employee_m->getAllEmp_where($where); // get data employee dari where yang sudah dibuat
            } else {
                $data_emp = $this->employee_m->getAllEmp(); // get data employee dari where yang sudah dibuat
            }
        } else {
            // ambil data form di divisi dia aja
            if($position_my['hirarki_org'] == "N"){
                $where = $this->table['position'].".div_id = ".$position_my['div_id'];
                if(!empty($filter_departemen)){
                    $where .= " AND ".$this->table['position'].".dept_id = ".explode("-", $filter_departemen)[1];
                }
                // ambil data form di divisi dia aja
            } elseif($position_my['hirarki_org'] == "N-1"){
                // ambil data form di divisi dan departemen dia
                $where = $this->table['position'].".div_id = ".$position_my['div_id']." AND ".$this->table['position'].".dept_id = ".$position_my['dept_id'];
                // ambil data di divisi dan departemen dia
            } elseif($position_my['hirarki_org'] == "N-2"){
                $where = $this->table['position'].".div_id = ".$position_my['div_id']." AND ".$this->table['position'].".dept_id = ".$position_my['dept_id']." AND ".$this->table['position'].".id_approver1 = ".$position_my['id'];
            } else {
                show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                exit;
            }
            $data_emp = $this->employee_m->getAllEmp_where($where); // get data employee dari where yang sudah dibuat
        }

        // siapkan variabel
        $where = "";
        if($showhat == 0){ // ambil data mytask
            // if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1 || $position_my['id'] == 1){
            if($position_my['hirarki_org'] == "N") {
                $where .= " AND status_now_id = '8'";
            } elseif($position_my['hirarki_org'] == "N-1"){
                $where .= " AND (status_now_id = '2' OR status_now_id = '1')";
            } elseif($position_my['hirarki_org'] == "N-2"){
                $where .= " AND status_now_id = '1'";
            } else { // ambil yang tidak ada statusnya di database biar hasilnya null
                $where .= " AND status_now_id = '999'";
                // show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                // exit;
            }
        } elseif($showhat == 1){ // ambil data history
            $daterange = explode(" - ", $filter_daterange); // pisahkan dulu daterangenya
            $daterange[0] = strtotime($daterange[0]);
            $daterange[1] = strtotime($daterange[1]);
            $where .= " AND created >= ".$daterange[0]." AND created <= ".$daterange[1]; // tambahkan where tanggal buat ngebatesin view biar ga load lama
            // ada filter status?
            if(!empty($filter_status)){
                $where .= " AND status_now_id = ".$filter_status;
            }
        } else { // tampilkan error
            show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
            exit;
        }

        // buat data pmk dari data employee di atas
        $data_pmk = array(); $x = 0; // siapkan variabel
        foreach($data_emp as $v){
            $result = $this->pmk_m->getOnceWhere_form("id LIKE '".$v['id_emp']."%'".$where);
            if(!empty($result)){
                $data_pmk[$x] = $result;
                $x++;
            }
        }

        // lengkapi data pmk
        $dataPmk = array(); $x = 0;
        foreach($data_pmk as $k => $v){
            $data_pos   = $this->employee_m->getDetails_employee(substr($v['id'], 0, 8));
            $divisi     = $this->divisi_model->getOnceWhere(array('id' => $data_pos['div_id']));
            $department = $this->dept_model->getDetailById($data_pos['dept_id']);
            $employee   = $this->employee_m->getDetails_employee(substr($v['id'], 0, 8));
            $status     = $this->pmk_m->getOnceWhere_status(array('id_status' => $v['status_now_id']));

            $dataPmk[$x]['nik']        = substr($v['id'], 0, 8);
            $dataPmk[$x]['divisi']     = $divisi['division'];
            $dataPmk[$x]['department'] = $department['nama_departemen'];
            $dataPmk[$x]['position']   = $data_pos['position_name'];
            $dataPmk[$x]['emp_name']   = $employee['emp_name'];
            $dataPmk[$x]['status_now'] = json_encode(array('status' => $status, 'trigger' => $v['id']));
            $dataPmk[$x]['action']     = json_encode(array('id' => $v['id']));
            $x++;
        }

        return $dataPmk;
    }
    
    /**
     * getDetail_pmk
     *
     * @return void
     */
    function getDetail_pmk($id_entity, $id_div, $id_dept, $id_pos, $id_time){
        return $this->db->get_where($this->table['main'], array(
            'id_entity' => $id_entity,
            'id_div'    => $id_div,
            'id_dept'   => $id_dept,
            'id_pos'    => $id_pos,
            'id_time'   => $id_time
        ))->row_array();
    }
    
    /**
     * get detail information of status id
     *
     * @param  mixed $id
     * @return void
     */
    function getDetail_pmkStatusDetailByStatusId($id_status){
        return $this->db->get_where($this->table['status'], array('id' => $id_status))->row_array();
    }
    
    /**
     * getDetail_pmkStatus
     *
     * @param  mixed $id_entity
     * @param  mixed $id_div
     * @param  mixed $id_dept
     * @param  mixed $id_pos
     * @param  mixed $id_time
     * @return void
     */
    function getDetail_pmkStatus($id_entity, $id_div, $id_dept, $id_pos, $id_time){
        $this->db->select('status');
        return json_decode($this->db->get_where($this->table['main'], array(
            'id_entity' => $id_entity,
            'id_div'    => $id_div,
            'id_dept'   => $id_dept,
            'id_pos'    => $id_pos,
            'id_time'   => $id_time
        ))->row_array()['status'], true);
    }

    /**
     * get id status now pmk
     *
     * @param  mixed $id_entity
     * @param  mixed $id_div
     * @param  mixed $id_dept
     * @param  mixed $id_pos
     * @param  mixed $id_time
     * @return void
     */
    function getDetail_pmkStatusNow($id_entity, $id_div, $id_dept, $id_pos, $id_time){
        $this->db->select('status_now');
        return $this->db->get_where($this->table['main'], array(
            'id_entity' => $id_entity,
            'id_div'    => $id_div,
            'id_dept'   => $id_dept,
            'id_pos'    => $id_pos,
            'id_time'   => $id_time
        ))->row_array()['status_now'];
    }

    /**
     * ambil semua data form dengan where
     *
     * @param  mixed $where
     * @return void
     */
    function getAllWhere_form($where){
        return $this->db->get_where($this->table['main'], $where)->result_array();
    }
    
    /**
     * ambil semua data assessment dengan id
     *
     * @param  varchar[16] $id
     * @return void
     */
    function getAllWhere_assessment($id){
        return $this->db->get_where($this->table['survey'], array('id' => $id))->result_array();
    }
    
    /**
     * this function is used for generate pmk id form
     *
     * @param  mixed $nik
     * @param  mixed $contract
     * @return void
     */
    function getId_form($nik, $contract){
        // ambil counter dan update ke table counter
        $counter = $this->db->get_where($this->table['counter'], array("id" => "pmk"))->row_array();
        if(date("o", $counter['date_modified']) != date("o", time())){
            $increment = 1;
        } else {
            $increment = $counter['counter'];
        }
        // bentuk idnya
        $id = $nik.str_pad($contract, 2, "0", STR_PAD_LEFT).date("y", time()).str_pad($increment, 4, "0", STR_PAD_LEFT);
        // update increment counter di database
        $this->db->where(array("id" => "pmk"))->update($this->table['counter'], array(
            'counter' => $increment + 1,
            'date_modified' => time()
        ));

        return $id;
    }

    function getId_summary(){

    }

    function getOnce_contract($nik, $contract){
        return $this->db->get_where($this->table['contract'], array('nik' => $nik, 'contract' => $contract))->row_array();
    }

    /**
     * ambil satu data contract dengan nik
     *
     * @param  mixed[8] $nik
     * @return void
     */
    function getOnce_LastContractByNik($nik){
        return $this->db->query("SELECT nik, MAX(contract) AS contract FROM ".$this->table['contract']." WHERE nik = '".$nik."' GROUP BY nik ORDER BY nik")->row_array();
    }
    
    /**
     * ambil satu data form dengan where
     *
     * @param  mixed $where
     * @return void
     */
    function getOnceWhere_form($where){
        return $this->db->get_where($this->table['main'], $where)->row_array();
    }
    
    /**
     * getOnceWhere_status
     *
     * @param  mixed $where
     * @return void
     */
    function getOnceWhere_status($where){        
        return $this->db->get_where($this->table['status'], $where)->row_array();
    }

    /**
     * getRow_form
     *
     * @param  mixed $nik
     * @param  mixed $contract
     * @return void
     */
    function getRow_form($nik, $contract){
        return $this->db->from($this->table['main'])->like('id', $nik.str_pad($contract, 2, "0", STR_PAD_LEFT), 'after')->get()->num_rows();
    }

    /**
     * simpan semua jawaban survey
     *
     * @param  mixed $table
     * @param  mixed $data
     * @return void
     */
    public function insertAll_surveyHasil($data){
        $this->db->insert_batch($this->table['survey'], $data);
    }
    
    /**
     * saveForm
     *
     * @param  mixed $data
     * @return void
     */
    function saveForm($data){
        $this->db->insert($this->table['main'], $data);
    }
    
    /**
     * updateForm
     *
     * @param  mixed $data
     * @param  mixed $where
     * @return void
     */
    public function updateForm($data, $where){
        $this->db->where($where);
        $this->db->update($this->table['main'], $data);
    }

}

/* End of file Pmk_m.php */
