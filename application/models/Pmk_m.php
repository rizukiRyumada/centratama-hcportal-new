<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pmk_m extends CI_Model {
    protected $table = [
        "contract" => "master_employee_contract",
        "counter" => "_counter_trans",
        "main" => "pmk_form",
        "pertanyaan" => "pmk_survey_pertanyaan",
        "status" => "pmk_status",
    ];
    
    /**
     * getAll pmk form data
     *
     * @return void
     */
    function getAll(){
        return $this->db->get($this->table['main'])->result_array();
    }

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

    function getComplete_pmkList($position_my, $showhat, $divisi, $departemen, $status, $daterange){
        // cek apa dia admin, superadmin, hc divhead, atau CEO
        if($this->session->userdata('role_id') == 1 || $this->userApp_admin == 1 || $position_my['id'] == 1 || $position_my['id'] == 196){
            // ambil semua data form
            $data_pmk = $this->pmk_m->getAll();
        } else {
            if($position_my['hirarki_org'] == "N"){
                $where = $this->table['position'].".div_id = ".$position_my['div_id'];
                if(!empty($this->input->post('departemen'))){
                    $where += " AND ".$this->table['position'].".dept_id = ".explode("-", $this->input->post('departemen'));
                }
                // ambil data form di divisi dia aja
                $data_emp = $this->employee_m->getAllEmp_where($where);
            } elseif($position_my['hirarki_org'] == "N-1"){
                // ambil data form di divisi dan departemen dia
                $data_emp = $this->employee_m->getAllEmp_where($this->table['position'].".div_id = ".$position_my['div_id']." AND ".$this->table['position'].".dept_id = ".$position_my['dept_id']);
                // ambil data di divisi dan departemen dia
            } elseif($position_my['hirarki_org'] == "N-2"){
                $data_emp = $this->employee_m->getAllEmp_where($this->table['position'].".div_id = ".$position_my['div_id']." AND ".$this->table['position'].".dept_id = ".$position_my['dept_id']." AND ".$this->table['position'].".id_approver1 = ".$position_my['id']);
            } else {
                show_error("This response is sent when the web server, after performing server-driven content negotiation, doesn't find any content that conforms to the criteria given by the user agent.", 406, 'Not Acceptable');
                exit;
            }

            // buat data pmk dari data employee di atas
            $data_pmk = array(); $x = 0; // siapkan variabel
            foreach($data_emp as $v){
                $result = $this->pmk_m->getOnceWhere_form(array('nik' => $v['id_emp']));
                if(!empty($result)){
                    $data_pmk[$x] = $result;
                    $x++;
                }
            }
        }

        // lengkapi data pmk
        $dataPmk = array(); $x = 0;
        foreach($data_pmk as $k => $v){
            $data_pos   = $this->employee_m->getDetails_employee($v['nik']);
            $divisi     = $this->divisi_model->getOnceWhere(array('id' => $data_pos['div_id']));
            $department = $this->dept_model->getDetailById($data_pos['dept_id']);
            $employee   = $this->employee_m->getDetails_employee($v['nik']);
            $status     = $this->pmk_m->getOnceWhere_status(array('id_status' => $v['status_now_id']));

            $dataPmk[$x]['nik']        = $v['nik'];
            $dataPmk[$x]['divisi']     = $divisi['division'];
            $dataPmk[$x]['department'] = $department['nama_departemen'];
            $dataPmk[$x]['position']   = $data_pos['position_name'];
            $dataPmk[$x]['emp_name']   = $employee['emp_name'];
            $dataPmk[$x]['status_now'] = json_encode(array('status' => $status, 'trigger' => json_encode(array('nik' => $v['nik'], 'contract' => $v['contract']))));
            $dataPmk[$x]['action']     = json_encode(array('nik' => $v['nik'], 'contract' => $v['contract']));
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
     * ambil satu data contract dengan where
     *
     * @param  mixed $where
     * @return void
     */
    function getOnceWhere_contract($where){
        return $this->db->get_where($this->table['contract'], $where)->row_array();
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
