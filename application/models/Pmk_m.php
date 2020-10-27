<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pmk_m extends CI_Model {
    protected $table = [
        "contract" => "master_employee_contract",
        "counter" => "_counter_trans",
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

        $dataPmk = $this->detail_pmk($data_pmk);

        return $dataPmk;
    }

    function detail_pmk($data_pmk){
        // lengkapi data pmk
        $dataPmk = array(); $x = 0;
        foreach($data_pmk as $k => $v){
            // $data_pos      = $this->employee_m->getDetails_employee(substr($v['id'], 0, 8));
            $employee      = $this->employee_m->getDetails_employee(substr($v['id'], 0, 8));
            $divisi        = $this->divisi_model->getOnceWhere(array('id' => $employee['div_id']));
            $department    = $this->dept_model->getDetailById($employee['dept_id']);
            $status        = $this->pmk_m->getOnceWhere_status(array('id_status' => $v['status_now_id']));
            $entity        = $this->entity_m->getOnce(array('id' => $employee['id_entity']));

            // ambil data penilaian
            $pa_last = $this->getLast_pa(substr($v['id'], 0, 8));
            if(!empty($pa_last)){
                $pa_data = $this->getAllWhere_pa(array('nik' => $pa_last['nik'], 'tahun' => $pa_last['tahun']));
                $pa_yearBefore = date('Y', strtotime('1-1-'.$pa_last['tahun'].' -1 year'));
            } else {
                $pa_data = "";
            }

            if(empty($pa_data)){
                $dummy_pa = Array(
                    'nik' => 'CG000309',
                    'periode' => 'H1/FY',
                    'tahun' => '2727',
                    'score' => '4.00',
                    'rating' => 'A-'
                );
                $dataPmk[$x]['pa1'] = json_encode(array('pa_data' => $dummy_pa, 'pa_name' => 'SPSP'));
                $dataPmk[$x]['pa2'] = json_encode(array('pa_data' => $dummy_pa, 'pa_name' => 'SPSP'));
                $dataPmk[$x]['pa3'] = json_encode(array('pa_data' => $dummy_pa, 'pa_name' => 'SPSP'));
            } else {
                $x = 0;
                if(count($pa_data) == 2){
                    $pa_dataBefore = $this->getOnceWhere_pa(array('nik' => $pa_last['nik'], 'tahun' => $pa_yearBefore, 'periode' => 'FY'));
                    $dataPmk[$x]['pa1'] = json_encode(array('pa_data' => $pa_dataBefore, 'pa_name' => 'SPFY'));
                    // ambil 2 data penilaian berikutnya
                    foreach($pa_data as $key => $value){
                        if($value['periode'] == 'H1'){
                            $dataPmk[$x]['pa2'] = json_encode(array('pa_data' => $pa_data[$key], 'pa_name' => 'SPAH'));
                        }
                        if($value['periode'] == 'FY'){
                            $dataPmk[$x]['pa3'] = json_encode(array('pa_data' => $pa_data[$key], 'pa_name' => 'SPFY'));
                        }
                    }
                } elseif(count($pa_data) == 1){
                    $pa_dataBefore = $this->getAllWhere_pa(array('nik' => $pa_last['nik'], 'tahun' => $pa_yearBefore));
                    // ambil 2 data penilaian berikutnya
                    foreach($pa_dataBefore as $key => $value){
                        if($value['periode'] == 'H1'){
                            $dataPmk[$x]['pa1'] = json_encode(array('pa_data' => $pa_dataBefore[$key], 'pa_name' => 'SPAH'));
                        }
                        if($value['periode'] == 'FY'){
                            $dataPmk[$x]['pa2'] = json_encode(array('pa_data' => $pa_dataBefore[$key], 'pa_name' => 'SPFY'));
                        }
                    }
                    $dataPmk[$x]['pa3'] = json_encode(array('pa_data' => $pa_data[0], 'pa_name' => 'SPAH'));
                } else {
                    show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                }
            }

            // if(count($pa_data) == 2){
            //     $pa_dataBefore = $this->getOnceWhere_pa(array('nik' => $pa_last['nik'], 'tahun' => $pa_yearBefore, 'periode' => 'FY'));
            //     $dataPmk[$x]['spfy1']['score'] = $pa_dataBefore['score'];
            //     $dataPmk[$x]['spfy1']['rating'] = $pa_dataBefore['rating'];
            //     $dataPmk[$x]['spfy1']['year'] = $pa_dataBefore['tahun'];
            //     // ambil 2 data penilaian berikutnya
            //     foreach($pa_data as $key => $value){
            //         if($value['periode'] == 'H1'){
            //             $dataPmk[$x]['spfy2']['score'] = $pa_data[$key]['score'];
            //             $dataPmk[$x]['spfy2']['periode'] = $pa_data[$key]['periode'];
            //             $dataPmk[$x]['spfy2']['rating'] = $pa_data[$key]['rating'];
            //             $dataPmk[$x]['spfy2']['year'] = $pa_data[$key]['tahun'];
            //         }
            //         if($value['periode'] == 'FY'){
            //             $dataPmk[$x]['spfy3']['score'] = $pa_data[$key]['score'];
            //             $dataPmk[$x]['spfy3']['periode'] = $pa_data[$key]['periode'];
            //             $dataPmk[$x]['spfy3']['rating'] = $pa_data[$key]['rating'];
            //             $dataPmk[$x]['spfy3']['year'] = $pa_data[$key]['tahun'];
            //         }
            //     }
            // } elseif(count($pa_data) == 1){
            //     $pa_dataBefore = $this->getAllWhere_pa(array('nik' => $pa_last['nik'], 'tahun' => $pa_yearBefore));
            //     // ambil 2 data penilaian berikutnya
            //     foreach($pa_dataBefore as $key => $value){
            //         if($value['periode'] == 'H1'){
            //             $dataPmk[$x]['spfy1']['score'] = $pa_dataBefore[$key]['score'];
            //             $dataPmk[$x]['spfy1']['periode'] = $pa_dataBefore[$key]['periode'];
            //             $dataPmk[$x]['spfy1']['rating'] = $pa_dataBefore[$key]['rating'];
            //             $dataPmk[$x]['spfy1']['year'] = $pa_dataBefore[$key]['tahun'];
            //         }
            //         if($value['periode'] == 'FY'){
            //             $dataPmk[$x]['spfy2']['score'] = $pa_dataBefore[$key]['score'];
            //             $dataPmk[$x]['spfy2']['periode'] = $pa_dataBefore[$key]['periode'];
            //             $dataPmk[$x]['spfy2']['rating'] = $pa_dataBefore[$key]['rating'];
            //             $dataPmk[$x]['spfy2']['year'] = $pa_dataBefore[$key]['tahun'];
            //         }
            //     }
            //     $dataPmk[$x]['spfy3']['score'] = $pa_data[0]['score'];
            //     $dataPmk[$x]['spfy3']['periode'] = $pa_data[0]['periode'];
            //     $dataPmk[$x]['spfy3']['rating'] = $pa_data[0]['rating'];
            //     $dataPmk[$x]['spfy3']['year'] = $pa_data[0]['tahun'];
            // } else {
            //     show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
            // }

            // data kontrak
            $contract_last = $this->pmk_m->getOnce_LastContractByNik(substr($v['id'], 0, 8));
            $contract_detail = $this->pmk_m->getDetailWhere_contract(array(
                'nik' => $contract_last['nik'],
                'contract' => $contract_last['contract']
            ));
            
            $dataPmk[$x]['nik']        = substr($v['id'], 0, 8);
            $dataPmk[$x]['emp_name']   = $employee['emp_name'];
            $dataPmk[$x]['date_birth'] = $employee['date_birth'];
            $dataPmk[$x]['date_join']  = $employee['date_join'];
            $dataPmk[$x]['emp_stats']  = $employee['emp_stats'];
            $dataPmk[$x]['eoc_probation'] = date("j M'y", $contract_detail['date_end']);
            $dataPmk[$x]['contract']   = $contract_last['contract'];
            $dataPmk[$x]['yoc_probation'] = date("j M'y", $contract_detail['date_start'])." - ".date("j M'y", $contract_detail['date_end']);
            $dataPmk[$x]['position']   = $employee['position_name'];
            $dataPmk[$x]['department'] = $department['nama_departemen'];
            $dataPmk[$x]['divisi']     = $divisi['division'];
            $dataPmk[$x]['entity']     = $entity['nama_entity'];
            $dataPmk[$x]['status_now'] = json_encode(array('status' => $status, 'trigger' => $v['id']));
            $dataPmk[$x]['action']     = json_encode(array('id' => $v['id']));
            
            $position_my = $this->posisi_m->getMyPosition(); // get position my
            $entity = $this->entity_m->getAll(); // get semua entity
            if(!empty($v['approval'])){
                $approval = json_decode($v['approval'], true); // decode approval
                // persiapkan data untuk interpretasi data
                if($position_my['id'] == 1){ // jika dia CEO
                    $dataPmk[$x]['approval'] = json_encode(array(
                        'id' => $v['id'],
                        'value' => $approval[0]['approval']
                    ));
                    $dataPmk[$x]['entity_new'] = json_encode(array(
                        'id' => $v['id'],
                        'value' => $approval[0]['entity_new'],
                        'entity' => $entity
                    ));
                } elseif($position_my['id'] == 196){ // jika dia hc division head
                    $dataPmk[$x]['approval'] = json_encode(array(
                        'id' => $v['id'],
                        'value' => $approval[1]['approval']
                    ));
                    $dataPmk[$x]['entity_new'] = json_encode(array(
                        'id' => $v['id'],
                        'value' => $approval[1]['entity_new'],
                        'entity' => $entity
                    ));
                } elseif($position_my['hirarki_org'] == "N" && $position_my['id'] != 196){ // jika dia divhead dan bukan hc divhead
                    $dataPmk[$x]['approval'] = json_encode(array(
                        'id' => $v['id'],
                        'value' => $approval[2]['approval']
                    ));
                    $dataPmk[$x]['entity_new'] = json_encode(array(
                        'id' => $v['id'],
                        'value' => $approval[2]['entity_new'],
                        'entity' => $entity
                    ));
                }
            } else {
                $dataPmk[$x]['approval'] = json_encode(array(
                    'id' => $v['id'],
                    'value' => ""
                ));
                $dataPmk[$x]['entity_new'] = json_encode(array(
                    'id' => $v['id'],
                    'value' => "",
                    'entity' => $entity
                ));
            }
            $x++; // increament the index

            // data yg diperlukan
            /**
             * nik
             * full name
             * BOD (date_birth)
             * Join Date
             * Employee Status
             * 
             * Year of Contract/Probation
             * Contract #
             * Year of Contract
             * 
             * Job Position
             * Departement
             * Division
             * Entity
             * 
             * SPFY 3 perioe
             * 
             * Terminated, Extended, Permanent
             * Extended milih entity baru
             */
        }

        return $dataPmk;
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
     * ambil semua pa employee dengan where
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

}

/* End of file Pmk_m.php */
