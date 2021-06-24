<?php
// get all details on employee
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_m extends CI_Model {

    // tables
    protected $table = array(
        'employee' => 'master_employee',
        'position' => 'master_position',
        'division' => 'master_division',
        'department' => 'master_department'
    );

    function __construct()
    {
        // ambil nama table yang terupdate
        $this->load->library('tablename');
        $this->table['position'] = $this->tablename->get($this->table['position']);
    }
    
    /**
     * count employee with where parameter
     * 
     * @param  array $where
     * @return integer $count_result
     */
    function count_where($where){
        $this->db->from($this->table['employee']);
        $this->db->join($this->table['position'], $this->table['position'].'.id = '.$this->table['employee'].'.position_id', 'left');
        $this->db->where($where);
        return $this->db->count_all_results(); // Produces an integer, like 17
    }
    
    /**
     * get All Employee Data with all details
     *
     * @return void
     */
    public function getAllEmp()
    {
        $this->db->select($this->table['employee'].'.nik as id_emp, '.$this->table['employee'].'.emp_name,nik, '.$this->table['position'].'.hirarki_org, position_id, '.$this->table['position'].'.div_id , '.$this->table['position'].'.dept_id, '.$this->table['position'].'.id, position_name, '.$this->table['division'].'.id, division, '.$this->table['department'].'.id, nama_departemen');
        $this->db->from($this->table['position']);
        $this->db->join($this->table['division'], $this->table['division'].'.id = '.$this->table['position'].'.div_id');
        $this->db->join($this->table['department'], $this->table['department'].'.id = '.$this->table['position'].'.dept_id');
        $this->db->join($this->table['employee'], $this->table['employee'].'.position_id = '.$this->table['position'].'.id');
        $this->db->order_by('id_emp', 'asc');
        
        return $this->db->get()->result_array();
    }

    /**
     * get All Employee Data with all details PLUS WHERE
     *
     * @return void
     */
    public function getAllEmp_where($where)
    {
        $this->db->select($this->table['employee'].'.nik as id_emp, '.$this->table['employee'].'.emp_name,nik, '.$this->table['position'].'.hirarki_org, position_id, '.$this->table['position'].'.div_id , '.$this->table['position'].'.dept_id, '.$this->table['position'].'.id, position_name, '.$this->table['division'].'.id, division, '.$this->table['department'].'.id, nama_departemen');
        $this->db->join($this->table['division'], $this->table['division'].'.id = '.$this->table['position'].'.div_id');
        $this->db->join($this->table['department'], $this->table['department'].'.id = '.$this->table['position'].'.dept_id');
        $this->db->join($this->table['employee'], $this->table['employee'].'.position_id = '.$this->table['position'].'.id');
        $this->db->order_by('id_emp', 'asc');
        
        return $this->db->get_where($this->table['position'], $where)->result_array();
    }
    
    /**
     * get approver 1 or 2 nik with my nik
     *
     * @param  mixed $nik
     * @param  mixed $approver1or2 
     * @return void
     */
    function getApprover_nik($nik, $approver1or2 = 1){
        // buat ngecek ada nik yang ada di master employee contract tapi gaada di master employee
        // if(empty($this->db->select('position_id')->get_where($this->table['employee'], array('nik' => $nik))->row_array())){
        //     print_r($nik);
        //     exit;
        // }
        $id_pos = $this->db->select('position_id')->get_where($this->table['employee'], array('nik' => $nik))->row_array()['position_id'];
        $approver = $this->db->select('id_approver'.$approver1or2)->get_where($this->table['position'], array('id' => $id_pos))->row_array()['id_approver'.$approver1or2];
        $result = $this->db->select('nik')->get_where($this->table['employee'], array('position_id' => $approver))->row_array();
        if(!empty($result)){
            $return = $result['nik'];
        } else {
            $return = "";
        }
        return $return;
    }
    
    /**
     * get dept id and div id from nik
     * getDeptDivFromNik
     *
     * @param  mixed $nik
     * @return void
     */
    function getDeptDivFromNik($nik){
        $this->db->select($this->table['position'].'.div_id, '.$this->table['position'].'.dept_id');
        $this->db->join(
            $this->table['position'], 
            $this->table['position'].'.id = '. $this->table['employee'].'.position_id',
            'left'
        );
        return $this->db->get_where($this->table['employee'], $this->table['employee'].'.nik = "'. $nik .'"')->row_array();
    }

    /**
     * getDetail_employeeAllData
     *
     * @param  mixed $nik
     * @return void
     */
    function getDetail_employeeAllData($nik){
        return $this->db->get_where($this->table['employee'], array('nik' => $nik))->row_array();
    }
    
    /**
     * getDetails_employee
     *
     * @param  mixed $nik
     * @return void
     */
    function getDetails_employee($nik){
        // load models
        $this->load->model(['divisi_model', 'dept_model']);

        $this->db->select('nik, emp_name, position_name, position_id, id_entity, role_id, akses_surat_id, level_personal, date_birth, date_join, emp_stats, dept_id, div_id, email, hirarki_org, id_atasan1, id_atasan2, id_approver1, id_approver2');
        $this->db->join(
            $this->table['position'], 
            $this->table['employee'].'.position_id='.$this->table['position'].'.id', 
            'left'
        );
        $result = $this->db->get_where($this->table['employee'], array('nik' => $nik))->row_array();
        if(!empty($result)){
            $result['divisi'] = $this->divisi_model->getDetailById($result['div_id'])['division'];
            $result['departemen'] = $this->dept_model->getDetailById($result['dept_id'])['nama_departemen'];
        }
        return $result;
    }
    
    /**
     * dapatkan email approve 1 dan 2 dari karyawan hanya dengan NIK
     *
     * @param  mixed $nik
     * @return void
     */
    function getEmail_approver12($nik){
        $id_pos = $this->db->select('position_id')->get_where($this->table['employee'], array('nik' => $nik))->row_array()['position_id'];

        $approver = $this->db->select('id_approver1, id_approver2')->get_where($this->table['position'], array('id' => $id_pos))->row_array();

        $email = array(); $x = 0;
        foreach($approver as $v){
            $email[$x] = $this->db->select('email')->get_where($this->table['employee'], array('position_id' => $v))->row_array()['email'];
            $x++;
        }

        return($email);
    }

    /**
     * dapatkan email approve 1 dari karyawan hanya dengan NIK
     *
     * @param  mixed $nik
     * @return void
     */
    function getEmail_approver1($nik){
        $id_pos = $this->db->select('position_id')->get_where($this->table['employee'], array('nik' => $nik))->row_array()['position_id'];

        $approver = $this->db->select('id_approver1')->get_where($this->table['position'], array('id' => $id_pos))->row_array();

        $email = array(); $x = 0;
        foreach($approver as $v){
            $email[$x] = $this->db->select('email')->get_where($this->table['employee'], array('position_id' => $v))->row_array()['email'];
            $x++;
        }

        return($email);
    }
    
    /**
     * getPosFromNik
     *
     * @param  mixed $nik
     * @return void
     */
    function getPosFromNik($nik){
        $this->db->select($this->table['position'].'.id, '.$this->table['position'].'.dept_id');
        $this->db->join(
            $this->table['position'], 
            $this->table['position'].'.id = '. $this->table['employee'].'.position_id',
            'left');
        return $this->db->get_where($this->table['employee'], $this->table['employee'].'.nik = "'. $nik .'"')->row_array();
    }
    
    /**
     * insert employee data to database
     *
     * @param  mixed $data
     * @return void
     */
    function insert($data){
        $this->db->insert($this->table['employee'], $data);
    }
    
    /**
     * cek apa dia divhead atau bukan
     *
     * @param  mixed $nik
     * @return void
     */
    function is_divhead($nik){
        return $this->db->get_where($this->table['division'], array('nik_div_head' => $nik))->num_rows();
    }
    
    /**
     * remove employee by nik (DANGEROUS FUNCTION)
     *
     * @param  mixed $nik
     * @return void
     */
    function remove($nik){
        $this->db->where('nik', $nik);
        $this->db->delete($this->table['employee']);
    }
    
    /**
     * update employee data
     *
     * @param  mixed $where
     * @param  mixed $data
     * @return void
     */
    function update($where, $data){
        $this->db->where($where);
        $this->db->update($this->table['employee'], $data);
    }

/* -------------------------------------------------------------------------- */
/*                               OTHER FUNCTION                               */
/* -------------------------------------------------------------------------- */    
    /**
     * cek apa foto tersedia atau tidak denga url file
     *
     * @param  mixed $nik
     * @return void
     */
    function check_empPhoto($nik){
        // PRODUCTION error sometimes file_headers empty
        $file = base_url('/assets/img/employee/'.$nik.'.jpg');
        // PRODUCTION get employee images on user-img path
        // $file = base_url('/user-img/'.$nik.'.jpg');
        $file_headers = @get_headers($file);

        // echo(is_readable($file));
        // echo('<br>');
        // echo('<br>');
        // echo($file);
        // exit;
        // if(is_readable($file)) {
        //     return true;
        // }
        // else {
        //     return false;
        // }

        // cek alamat tidak error
        if($file_headers[0] == 'HTTP/1.1 404 Not Found' || $file_headers[0] == 'HTTP/1.0 500 Internal Server Error' || $file_headers[0] == 'HTTP/1.1 500 Internal Server Error') {
            return false;
        }
        else {
            return true;
        }

        // $file_headers output
        // Array
        // (
        //     [0] => HTTP/1.1 500 Internal Server Error
        //     [1] => Content-Type: text/html; charset=UTF-8
        //     [2] => Server: Microsoft-IIS/8.5
        //     [3] => X-Powered-By: PHP/7.4.1
        //     [4] => X-Powered-By: ASP.NET
        //     [5] => Date: Wed, 11 Nov 2020 09:03:10 GMT
        //     [6] => Connection: close
        //     [7] => Content-Length: 207112
        // )

        // $filename = base_url('/assets/img/employee/'.$nik.'.jpeg');

        // print_r($filename);
        // exit;

        // // cek apa filenya ada atau engga
        // if (file_exists($filename)) {
        //     return 1; // jika ada beri tanda ada
        // } else {
        //     return 0; // jika tidak ada beri tanda tidak ada}
        // }
    }

}

/* End of file Employee_m.php */