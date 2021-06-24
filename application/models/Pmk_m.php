<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pmk_m extends CI_Model {
    protected $table = [
        "contract" => "master_employee_contract",
        "counter" => "_counter_trans",
        'employee' => 'master_employee',
        "employee_pa" => 'master_employee_pa',
        "form_summary" => "pmk_form_summary",
        "main" => "pmk_form",
        "pertanyaan" => "pmk_survey_pertanyaan",
        "pertanyaan_tipe" => "pmk_survey_pertanyaan_tipe",
        "position" => "master_position",
        "status" => "pmk_status",
        "status_summary" => "pmk_status_summary",
        "summary" => "pmk_form_summary",
        "survey" => "pmk_survey_hasil"
    ];
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['entity_m']); // load model

        // ambil nama table yang terupdate
        $this->load->library('tablename');
        $this->table['position'] = $this->tablename->get($this->table['position']);
    }
    
    
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
        return $this->db->get_where($this->table['pertanyaan'])->result_array();
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
     * get semua pertanyaan assessment
     *
     * @return void
     */
    function getAllWhere_pertanyaan($where){
        return $this->db->get_where($this->table['pertanyaan'], $where)->result_array();
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
     * ambil semua data form dengan where
     *
     * @param  mixed $where
     * @return void
     */
    function getAllWhere_form($where){
        return $this->db->get_where($this->table['main'], $where)->result_array();
    }
    
    /**
     * ambil semua pa employee dengan where
     *
     * @param  mixed $where
     * @return void
     */
    function getAllWhere_pa($where){
        return $this->db->get_where($this->table['employee_pa'], $where)->result_array();
    }

    /**
     * ambil satu item dengan memilih mana yang mau ditampilkan dari form table
     *
     * @param  mixed $select
     * @param  mixed $where
     * @return void
     */
    function getAllWhereSelect_form($select, $where){
        return $this->db->select($select)->get_where($this->table['main'], $where)->result_array();
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
        // siapkan variabel
        $where = "";
        // cek apa dia admin, superadmin, hc divhead, atau CEO
        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1 || $position_my['id'] == 1 || $position_my['id'] == 196){
            // filter divisi dan departemen khusus admin dan hc divhead
            if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1){
                // biarkan where kosong untuk ambil semua data
            } elseif($position_my['id'] == 1 && $showhat == 0){ // jika dia CEO
                $where .= $this->table['position'].".div_id = '1'";
            } elseif($position_my['id'] == 196 && $showhat == 0){ // jika dia HC Division Head
                $where .= $this->table['position'].".div_id = '6'";
            } elseif($position_my['hirarki_org'] == "N" && $showhat == 0){ // ambil data form di divisi dia aja
                $where = $this->table['position'].".div_id = ".$position_my['div_id'];
                // if(!empty($filter_departemen)){
                //     $where .= " AND ".$this->table['position'].".dept_id = ".explode("-", $filter_departemen)[1];
                // }
            } elseif($position_my['hirarki_org'] == "N-1" && $showhat == 0){ // jika hirarki N-1
                // ambil data form di divisi dan departemen dia
                $where = $this->table['position'].".div_id = ".$position_my['div_id']." AND ".$this->table['position'].".dept_id = ".$position_my['dept_id'];
                // ambil data di divisi dan departemen dia
            } elseif($position_my['hirarki_org'] == "N-2" && $showhat == 0){ // jika hirarki N-2
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
                $where = $this->table['employee'].".nik!='".$this->session->userdata('nik')."' AND ".$this->table['position'].".div_id = ".$position_my['div_id'];
                if(!empty($filter_departemen)){
                    $where .= " AND ".$this->table['position'].".dept_id = ".explode("-", $filter_departemen)[1];
                }
                // ambil data form di divisi dia aja
            } elseif($position_my['hirarki_org'] == "N-1"){
                // ambil data form di divisi dan departemen dia
                $where = $this->table['employee'].".nik!='".$this->session->userdata('nik')."' AND ".$this->table['position'].".div_id = ".$position_my['div_id']." AND ".$this->table['position'].".dept_id = ".$position_my['dept_id'];
                // ambil data di divisi dan departemen dia
            } elseif($position_my['hirarki_org'] == "N-2"){
                $where = $this->table['employee'].".nik!='".$this->session->userdata('nik')."' AND ".$this->table['position'].".div_id = ".$position_my['div_id']." AND ".$this->table['position'].".dept_id = ".$position_my['dept_id']." AND ".$this->table['position'].".id_approver1 = ".$position_my['id'];
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
                $where .= " AND status_now_id = '9'";
            } elseif($position_my['hirarki_org'] == "N-1"){
                $where .= " AND (status_now_id = '2' OR status_now_id = '1')";
            } elseif($position_my['hirarki_org'] == "N-2"){
                //cek jika dia admin
                if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1){
                    $where .= " AND (status_now_id = '1' OR status_now_id = '9')";
                } else {
                    $where .= " AND (status_now_id = '1')";
                }
            } else { // ambil yang tidak ada statusnya di database biar hasilnya null
                $where .= " AND status_now_id = '999'";
                // show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                // exit;
            }
        } elseif($showhat == 1){ // ambil data history
            // ada filter status?
            if(!empty($filter_status)){
                $where .= "status_now_id = ".$filter_status;
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

        $dataPmk = $this->detail_assessment($data_pmk, $showhat, $filter_daterange);
        return $dataPmk;
    }
    
    /**
     * function to complete assessment list detail
     *
     * @param  mixed $data_pmk
     * @return void
     */
    function detail_assessment($data_pmk, $showhat, $filter_daterange){
        // lengkapi data pmk
        $dataPmk = array(); $x = 0;
        foreach($data_pmk as $k => $v){
            $data_pos   = $this->employee_m->getDetails_employee(substr($v['id'], 0, 8));
            $divisi     = $this->divisi_model->getOnceWhere(array('id' => $data_pos['div_id']));
            $department = $this->dept_model->getDetailById($data_pos['dept_id']);
            $employee   = $this->employee_m->getDetails_employee(substr($v['id'], 0, 8));
            $status     = $this->pmk_m->getOnceWhere_status(array('id_status' => $v['status_now_id']));
            $contract_last = $this->pmk_m->getOnce_LastContractByNik(substr($v['id'], 0, 8));
            $contract_last_detail = $this->pmk_m->getDetailWhere_contract(array(
                'nik' => $contract_last['nik'],
                'contract' => $contract_last['contract']
            ));

            // cek apakah viewnya untuk admin
            if($showhat == 1){
                // explode filter daterange
                $daterange = explode(" - ", $filter_daterange); // pisahkan dulu daterangenya
                $daterange[0] = date('Y-m-d', strtotime($daterange[0]));
                $daterange[1] = date('Y-m-d', strtotime($daterange[1]));
                // $where .= "created >= '".$daterange[0]."' AND created <= '".$daterange[1]."'"; // tambahkan where tanggal buat ngebatesin view biar ga load lama

                // cek apa contractnya berada di jangkauan filter
                if($contract_last_detail['date_end'] >= $daterange[0] && $contract_last_detail['date_end'] <= $daterange[1]){
                    $flag_add = 1;
                } else {
                    $flag_add = 0;
                }
            } else {
                $flag_add = 1;
            }

            // cek apa datanya bisa untuk ditambahkan
            if($flag_add == 1){
                $dataPmk[$x]['nik']        = substr($v['id'], 0, 8);
                $dataPmk[$x]['divisi']     = $divisi['division'];
                $dataPmk[$x]['department'] = $department['nama_departemen'];
                $dataPmk[$x]['position']   = $data_pos['position_name'];
                $dataPmk[$x]['emp_name']   = $employee['emp_name'];
                $dataPmk[$x]['eoc']        = date('Y-m-d', strtotime($contract_last_detail['date_end']));
                $dataPmk[$x]['status_now'] = json_encode(array('status' => $status, 'trigger' => $v['id']));
                $dataPmk[$x]['action']     = json_encode(array('id' => $v['id']));
                $x++;
            }
        }

        return $dataPmk;
    }
    
    /**
     * Melengkapi data pmk dengan PA dan informasi selengkapnya
     *
     * @param  mixed $data_pmk
     * @return void
     */
    function detail_summary($data_pmk){       
        $time = date('d-m-Y', time()); $counter_data = 2; $pa_year = array(); // variabel untuk mencari pa
        // cek data pa per data pmk
        $counter_pa_employee = 0;
        foreach($data_pmk as $value){
            if($this->_general_m->getRow($this->table['employee_pa'], array('nik' => substr($value['id'], 0, 8))) > 0){
                // $pa_data['pa'.$counter_data] = $this->getOnceWhere_pa(array('nik' => substr($value['id'], 0, 8), 'tahun' => date('Y', strtotime($time)), 'periode' => 'FY'));                        
                $counter_pa_employee++; // tandai kalo counter pa employee ada
            }
        }
        // cek jika di data pmk ini ada data pa nya
        if($counter_pa_employee != 0){
            // lakukan do..while() sampai tidak counter data 3
            do {
                $counter_ada_tahun = 0;
                // cari di masing2 data pmk dengan melihat tahun dan periodenya
                for($x = 2; $x > 0; $x--){
                    if($x == 2){
                        $period = "FY";
                    } else {
                        $period = "H1";
                    }
                    $counter_ada_semester = 0;
                    foreach($data_pmk as $value){
                        if($this->_general_m->getRow($this->table['employee_pa'], array('nik' => substr($value['id'], 0, 8), 'tahun' => date('Y', strtotime($time)), 'periode' => $period)) > 0){
                            // $pa_data['pa'.$counter_data] = $this->getOnceWhere_pa(array('nik' => substr($value['id'], 0, 8), 'tahun' => date('Y', strtotime($time)), 'periode' => 'FY'));                        
                            $counter_ada_semester++; // tandai kalo di tahun ini ada penilaian akhir (pa)
                        }
                    }
                    if($counter_ada_semester != 0 && $counter_data >= 0 || !empty($pa_year)){ // cek kalo ada penilaian akhir dan counter data ada atau tidak kosong pa_year nya
                        $pa_year[$counter_data] = array(
                            'year' => date('Y', strtotime($time)),
                            'periode' => $period
                        );
                        $counter_data--; $counter_ada_tahun++; // kurangi index counter data dan counter tahun
                    }
                }
    
                if($counter_ada_tahun == 0){ // jika flag counter ada adalah nol, pakai tahun sebelumnya
                    $time = date('d-m-Y', strtotime($time.' -1 year'));
                    // $counter
                } elseif($counter_data != 3 && $counter_ada_tahun != 0){
                    $time = date('d-m-Y', strtotime($time.' -1 year'));
                }
            } while ($counter_data >= 0); // jika counter datanya nol akhiri do...while()
        } else {
            // lakukan do..while() sampai tidak counter data 3
            do {
                // cari di masing2 data pmk dengan melihat tahun dan periodenya
                for($x = 2; $x > 0; $x--){
                    if($x == 2){
                        $period = "FY";
                    } else {
                        $period = "H1";
                    }
                    
                    if($counter_data >= 0 || !empty($pa_year)){ // cek kalo ada penilaian akhir dan counter data ada atau tidak kosong pa_year nya
                        $pa_year[$counter_data] = array(
                            'year' => date('Y', strtotime($time)),
                            'periode' => $period
                        );
                        $counter_data--; // kurangi index counter data 
                    }
                }
    
                if($counter_data != 3){
                    $time = date('d-m-Y', strtotime($time.' -1 year'));
                }
            } while ($counter_data >= 0); // jika counter datanya nol akhiri do...while()
        }

        // melengkapi data pmk
        $dataPmk = array(); $x = 0;
        foreach($data_pmk as $k => $v){
            // $data_pos      = $this->employee_m->getDetails_employee(substr($v['id'], 0, 8));
            $employee      = $this->employee_m->getDetails_employee(substr($v['id'], 0, 8));
            $divisi        = $this->divisi_model->getOnceWhere(array('id' => $employee['div_id']));
            $department    = $this->dept_model->getDetailById($employee['dept_id']);
            $status        = $this->pmk_m->getOnceWhere_status(array('id_status' => $v['status_now_id']));

            $vya = 3; // penanda
            foreach($pa_year as $key => $value){
                $pa_result = $this->getOnceWhere_pa(array('nik' => substr($v['id'], 0, 8), 'tahun' => $value['year'], 'periode' => $value['periode']));
                if(!empty($pa_result)){
                    $dataPmk[$x]['pa'.$vya] = $pa_result;
                } else {
                    $dataPmk[$x]['pa'.$vya] = array(
                        'score' => "",
                        'rating' => ""
                    );
                }
                $vya--;
            }

            // data kontrak
            $contract_last = $this->pmk_m->getOnce_LastContractByNik(substr($v['id'], 0, 8));
            $contract_last_detail = $this->pmk_m->getDetailWhere_contract(array(
                'nik' => $contract_last['nik'],
                'contract' => $contract_last['contract']
            ));
            $contract_details = $this->pmk_m->getDetailsWhere_contract(array(
                'nik' => $contract_last['nik']
            ));
            // atur data contract
            $contract_output = array();
            foreach($contract_details as $kunci => $nilai){
                $contract_output[$kunci] = $nilai['contract']." | ".date("j M'y", strtotime($nilai['date_start']))." - ".date("j M'y", strtotime($nilai['date_end']))." | ".$this->entity_m->getOnce(array('id' => $nilai['entity']))['nama_entity'];
            }

            // ambil entity last
            $entity_last = $this->entity_m->getOnce(array('id' => $contract_last_detail['entity']));
            
            // persiapkan detail data
            $dataPmk[$x]['id']         = $v['id'];
            $dataPmk[$x]['nik']        = substr($v['id'], 0, 8);
            $dataPmk[$x]['emp_name']   = $employee['emp_name'];
            $dataPmk[$x]['date_birth'] = $employee['date_birth'];
            $dataPmk[$x]['date_join']  = $employee['date_join'];
            $dataPmk[$x]['emp_stats']  = $employee['emp_stats'];
            $dataPmk[$x]['eoc_probation'] = date("j M'y", strtotime($contract_last_detail['date_end']));
            $dataPmk[$x]['contract']   = $contract_last['contract'];
            $dataPmk[$x]['yoc_probation'] = $contract_output;
            $dataPmk[$x]['position']   = $employee['position_name'];
            $dataPmk[$x]['department'] = $department['nama_departemen'];
            $dataPmk[$x]['divisi']     = $divisi['division'];
            $dataPmk[$x]['divisi_id']  = $divisi['id'];
            $dataPmk[$x]['entity']     = $entity_last['nama_entity'];
            $dataPmk[$x]['entity_last']= $entity_last;
            $dataPmk[$x]['status_now'] = json_encode(array('status' => $status, 'trigger' => $v['id']));
            $dataPmk[$x]['action']     = json_encode(array('id' => $v['id']));

            // jika survey rerata kosong
            if(empty($v['survey_rerata'])){
                $survey_rerata = 0.00;
            } else {
                $survey_rerata = json_decode($v['survey_rerata'], true)['total'];
            }
            $dataPmk[$x]['survey_rerata'] = $survey_rerata;
            
            $position_my = $this->posisi_m->getMyPosition(); // get position my
            $entity = $this->entity_m->getAll(); // get semua entity
            if(!empty($v['recomendation'])){
                $recomendation = json_decode($v['recomendation'], true); // decode summary
                // persiapkan variable untuk interpretasi data
                $dataPmk[$x]['recomendation'] = $recomendation['summary'];
                $dataPmk[$x]['entity_new'] = $recomendation['entity'];
                $dataPmk[$x]['extend_for'] = $recomendation['extend_for'];
            } else {
                $dataPmk[$x]['recomendation'] = "";
                $dataPmk[$x]['entity_new'] = "";
                $dataPmk[$x]['extend_for'] = "";
            }
            $x++; // increament the index
        }

        return array(
            'data_pmk' => $dataPmk,
            'pa_year' => $pa_year
        );
    }
    
    /**
     * get all detail with where parameter
     *
     * @param  mixed $where
     * @return void
     */
    function getDetailWhere_contract($where){
        return $this->db->get_where($this->table['contract'], $where)->row_array();
    }

    /**
     * get all detail with where parameter
     *
     * @param  mixed $where
     * @return void
     */
    function getDetailsWhere_contract($where){
        return $this->db->get_where($this->table['contract'], $where)->result_array();
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
        return $this->db->get_where($this->table['status'], array('id_status' => $id_status))->row_array();
    }

    /**
     * get detail information of status id on summary status
     *
     * @param  mixed $id
     * @return void
     */
    function getDetail_pmkStatusDetailByStatusId_summary($id_status){
        return $this->db->get_where($this->table['status_summary'], array('id' => $id_status))->row_array();
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
    function getDetail_pmkStatus($id){
        $this->db->select('status');
        return json_decode($this->db->get_where($this->table['main'], array(
            'id' => $id
        ))->row_array()['status'], true);
    }

    /**
     * get detail pmk status for summary pmk
     *
     * @param  mixed $id_entity
     * @param  mixed $id_div
     * @param  mixed $id_dept
     * @param  mixed $id_pos
     * @param  mixed $id_time
     * @return void
     */
    function getDetail_pmkStatus_summary($id){
        $this->db->select('status');
        return json_decode($this->db->get_where($this->table['summary'], array(
            'id_summary' => $id
        ))->row_array()['status'], true);
    }
    
    /**
     * get status now summary and its detail with id_summary
     *
     * @param  mixed $id_summary
     * @return data detail status now of summary
     */
    function getDetail_statusNowSummary($id_summary){
        $this->db->select('status_now_id');
        $id_status = $this->db->get_where($this->table['form_summary'], array(
            'id_summary' => $id_summary
        ))->row_array()['status_now_id'];

        return $this->getOnceWhere_statusSummary(array('id' => $id_status));
    }
    
    /**
     * ambil satu data detail summary dengan id
     *
     * @param  mixed $id
     * @return void
     */
    function getDetail_summary($id){
        return $this->db->get_where($this->table['form_summary'], array('id_summary' => $id))->row_array();
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
        if(date("Y", $counter['date_modified']) != date("Y", time())){
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
    
    /**
     * ambil pa karyawan paling akhir
     *
     * @param  mixed $nik
     * @return void
     */
    function getLast_pa($nik){
        return $this->db->query("SELECT nik, MAX(tahun) AS tahun FROM ".$this->table['employee_pa']." WHERE nik = '".$nik."' GROUP BY nik ORDER BY nik")->row_array();
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
     * ambil satu pa employee dengan where
     *
     * @param  mixed $where
     * @return void
     */
    function getOnceWhere_pa($where){
        return $this->db->get_where($this->table['employee_pa'], $where)->row_array();
    }
    
    /**
     * ambil status pmk form
     *
     * @param  mixed $where
     * @return void
     */
    function getOnceWhere_status($where){        
        return $this->db->get_where($this->table['status'], $where)->row_array();
    }

    /**
     * ambil status pmk summary
     *
     * @param  mixed $where
     * @return void
     */
    function getOnceWhere_statusSummary($where){        
        return $this->db->get_where($this->table['status_summary'], $where)->row_array();
    }
    
    /**
     * ambil satu item dengan memilih mana yang mau ditampilkan dari form table
     *
     * @param  mixed $select
     * @param  mixed $where
     * @return void
     */
    function getOnceWhereSelect_form($select, $where){
        return $this->db->select($select)->get_where($this->table['main'], $where)->row_array();
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
     * get status summary before status now id
     *
     * @param  mixed $status_now_id
     * @return void
     */
    function getStatusBefore($status_now_id){
        $result = array(); $y = 0;
        for($x = 1; $x < substr($status_now_id, 8, 1); $x++){ // pecahkan angka terakhir summary status
            $result[$y] = $this->db->select('id, pic_name')->get_where($this->table['status_summary'], array('id' => 'pmksum-0'.$x))->row_array();
            $y++;
        }

        return $result;
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
     * saveForm
     *
     * @param  mixed $data
     * @return void
     */
    function saveSummary($data){
        $this->db->insert($this->table['summary'], $data);
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

    /**
     * updateForm
     *
     * @param  mixed $data
     * @param  mixed $where
     * @return void
     */
    public function updateForm_summary($data, $where){
        $this->db->where($where);
        $this->db->update($this->table['summary'], $data);
    }

}

/* End of file Pmk_m.php */
