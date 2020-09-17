<?php defined('BASEPATH') OR exit('No direct script access allowed');

class AppSettings extends SuperAdminController {

    public function __construct(){
        // show_error($message, $status_code, $heading = 'An Error Was Encountered')
        // echo($a);
        // show_error('error dah', 404, 'ada errrrororororororo');
        // exit;
        parent::__construct();
    }

/* -------------------------------------------------------------------------- */
/*                                MAIN FUNCTION                               */
/* -------------------------------------------------------------------------- */

    public function index()
    {
        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu', array('url' => $this->uri->uri_string()))['title'];
        $data['load_view'] = 'appsettings/appsettings_v';
        // $data['custom_styles'] = array('survey_styles');
        // $data['custom_script'] = array('survey/script_survey');
        
        $this->load->view('main_v', $data);
    }

    public function jobProfile(){
        // $data = [
        //     'title' => 'Job Profile',
        //     'user' => $this->db->get_where('master_employee', ['nik' => $this->session->userdata('nik')])->row_array(),
        //     'divisi' => $this->Divisi_model->getAll(),
        //     'div_head' => $this->Divisi_model->getDivByOrg(),
        //     'status_time' => $this->Jobpro_model->getDetails('*', 'jobprofile_setting-notifstatus', array())
        // ];
        // $this->load->view('templates/user_header', $data);
        // $this->load->view('templates/user_sidebar', $data);
        // $this->load->view('templates/user_topbar', $data);
        // $this->load->view('settings/job_profile_s', $data);
        // $this->load->view('templates/report_footer');
        
        // jobprofile data
        $data = [
            'status_time' => $this->_general_m->getAll('*', 'jobprofile_setting-notifstatus', array())
        ];

        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['title'];
        $data['load_view'] = 'appsettings/jobprofile_appsettings_v';
        // $data['custom_styles'] = array('survey_styles');
        $data['custom_script'] = array('appsettings/script_appsettings');
        
        $this->load->view('main_v', $data);
    }
    
    /** Survey App Settings
     * survey
     *
     * @return void
     */
    public function survey(){
        // main data
        $data['sidebar'] = getMenu(); // ambil menu
        $data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
        $data['user'] = getDetailUser(); //ambil informasi user
        $data['page_title'] = $this->_general_m->getOnce('title', 'user_menu_sub', array('url' => $this->uri->uri_string()))['title'];
        $data['load_view'] = 'appsettings/survey_appsettings_v';
        $data['custom_styles'] = array('appsettings_survey_styles');
        $data['custom_script'] = array('appsettings/script_survey_appsettings');
        
        $this->load->view('main_v', $data);
    }

/* -------------------------------------------------------------------------- */
/*                              Surveys Function                              */
/* -------------------------------------------------------------------------- */
// table name
    protected $table_survey = [
        'exc' => 'survey_exc_hasil',
        'exc_dept' => 'survey_exc_departemen',
        'exc_pertanyaan' => 'survey_exc_pertanyaan',
        'eng' => 'survey_eng_hasil',
        'eng_pertanyaan' => 'survey_eng_pertanyaan',
        '360' => 'survey_f360_hasil',
        '360_pertanyaan' => 'survey_f360_pertanyaan',
        '360_kategori' => 'survey_f360_kategoripertanyaan'
    ];
    public function ajax_survey_newPeriods(){
        // ambil get survey yang mau direset
        $survey = $this->input->post('survey');
        // tentukan yg mana yg mau direset
        if($survey == 'eng'){
            $this->survey_newPeriods_eng();
        } elseif($survey == "exc"){
            $this->survey_newPeriods_exc();
        } elseif($survey == "360"){
            $this->survey_newPeriods_360();
        } else {
            show_404("Error", FALSE);
        }
    }
    // survey excellence new period
    function survey_newPeriods_exc(){
        // load models
        $this->load->model('_archives_m');
        // find the quarter of year
        //Our date.
        
        //Get the month number of the date
        $month = date("n", time());
        //Divide that month number by 3 and round up using ceil.
        $yearQuarter = ceil($month / 3);
        if($yearQuarter > 1){ // buat nandain periode sebelumnya
            $yearQuarter = $yearQuarter - 1;
            $year = date('o', time());
        } elseif($yearQuarter == 1){ // jika di periode pertama
            $yearQuarter = 4;
            $year = date('o', strtotime("-1 year", time()));
        }
        // cek di archives apakah ada data di periode dan tahun ini
        $is_exist = $this->_archives_m->getRow($this->table_survey['exc'], [
            'tahun' => date('o', time()),
            'periode' => $yearQuarter
        ]);
        // cek jika datanya ada apa engga
        if($is_exist < 1){
            // ambil data
            $vya = $this->_general_m->getAll('*', $this->table_survey['exc'], []);
            // lengkapi data
            foreach($vya as $k => $v){
                $vya[$k]['tahun'] = $year;
                $vya[$k]['periode'] = $yearQuarter;
                $vya[$k]['id_departemen_dinilai'] = $v['id_departemen'];
                $vya[$k]['departemen_dinilai'] = $this->_general_m->getOnce('nama', $this->table_survey['exc_dept'], array('id' => $v['id_departemen']))['nama'];
                $vya[$k]['departemen_penilai'] = $v['departemen'];
                $vya[$k]['pertanyaan'] = $this->_general_m->getOnce('pertanyaan', $this->table_survey['exc_pertanyaan'], array('id' => $v['id_pertanyaan']))['pertanyaan'];
                unset($vya[$k]['id_departemen']);
                unset($vya[$k]['departemen']);
            }
            // masukkan ke database archives
            $this->_archives_m->insertAll($this->table_survey['exc'], $vya);
            //hapus data dari database utama
            $this->_general_m->truncate($this->table_survey['exc']);

            echo(1); // tanda sukses
        } else {
            echo(0); // tanda gagal
        }
    }
    // service enggagement new period
    function survey_newPeriods_eng(){
        // load models
        $this->load->model('_archives_m');
        // find the quarter of year
        //Get the month number of the date
        $month = date("n", time());
        //Divide that month number by 3 and round up using ceil.
        $period = ceil($month / 6);
        if($period > 1){ // buat nandain periode sebelumnya
            $period = $period - 1;
            $year = date('o', time());
        } else { // jika di periode pertama
            $period = 2;
            $year = date('o', strtotime("-1 year", time()));
        }
        // cek di archives apakah ada data di periode dan tahun ini
        $is_exist = $this->_archives_m->getRow($this->table_survey['eng'], [
            'tahun' => date('o', time()),
            'periode' => $period
        ]);
        // cek jika datanya ada apa engga
        if($is_exist < 1){
            // ambil data
            $vya = $this->_general_m->getAll('*', $this->table_survey['eng'], []);
            // lengkapi data
            foreach($vya as $k => $v){
                $vya[$k]['tahun'] = $year;
                $vya[$k]['periode'] = $period;
                $vya[$k]['pertanyaan'] = $this->_general_m->getOnce('pertanyaan', $this->table_survey['eng_pertanyaan'], array('id' => $v['id_pertanyaan']))['pertanyaan'];
            }
            // masukkan ke database archives
            $this->_archives_m->insertAll($this->table_survey['eng'], $vya);
            //hapus data dari database utama
            $this->_general_m->truncate($this->table_survey['eng']);

            echo(1); // tanda sukses
        } else {
            echo(0); // tanda gagal
        }
    }
    // survey feedback 360 new period
    function survey_newPeriods_360(){
        // load models
        $this->load->model('_archives_m');
        // find the quarter of year
        //Get the month number of the date
        $month = date("n", time());
        //Divide that month number by 3 and round up using ceil.
        $period = ceil($month / 6);
        if($period > 1){ // buat nandain periode sebelumnya
            $period = $period - 1;
            $year = date('o', time());
        } else {
            $period = 2;
            $year = date('o', strtotime("-1 year", time()));
        }
        // cek di archives apakah ada data di periode dan tahun ini
        $is_exist = $this->_archives_m->getRow($this->table_survey['360'], [
            'tahun' => date('o', time()),
            'periode' => $period
        ]);
        // cek jika datanya ada apa engga
        if($is_exist < 1){
            // ambil data
            $vya = $this->_general_m->getAll('*', $this->table_survey['360'], []);
            // lengkapi data
            foreach($vya as $k => $v){
                $vya[$k]['tahun'] = $year;
                $vya[$k]['periode'] = $period;
                $pertanyaan = $this->_general_m->getOnce('id_kategori_pertanyaan, pertanyaan', $this->table_survey['360_pertanyaan'], array('id' => $v['id_pertanyaan']));
                $vya[$k]['pertanyaan'] = $pertanyaan['pertanyaan'];
                $vya[$k]['id_kategori_pertanyaan'] = $pertanyaan['id_kategori_pertanyaan'];
                $vya[$k]['nama_kategori'] = $this->_general_m->getOnce('nama_kategori', $this->table_survey['360_kategori'], array('id_kategori_pertanyaan' => $pertanyaan['id_kategori_pertanyaan']))['nama_kategori'];
            }
            // masukkan ke database archives
            $this->_archives_m->insertAll($this->table_survey['360'], $vya);
            //hapus data dari database utama
            $this->_general_m->truncate($this->table_survey['360']);

            echo(1); // tanda sukses
        } else {
            echo(0); // tanda gagal
        }
    }

}

/* End of file Settings.php */
