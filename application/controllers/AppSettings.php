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
        '360_kategori' => 'survey_f360_kategoripertanyaan',
        'setting' => 'survey_setting'
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
        // $yearQuarter_now = $yearQuarter;
        if($yearQuarter == 4 || $yearQuarter == 3){
            // tentukan periode buat archive dan last
            if($yearQuarter == 4){
                $yearQuarter_last = 3; // periode terakhir dari sistem
                $period_text = "Q3"; // nama periode
                $yearQuarter_archive = 2; // periode archive dari sistem
            } elseif($yearQuarter == 3){
                $yearQuarter_last = 2; // periode terakhir dari sistem
                $period_text = "H1"; // nama periode
                $yearQuarter_archive = 1; // periode archive dari sistem
            } else {
                show_error('The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request.', 406, 'Not Acceptable');
                exit;
            }
            $year_archive = date('Y', time()); // tentukan tahun archive
            $year_last = $year_archive; // tentukan tahun last quartal
        } elseif($yearQuarter == 2 || $yearQuarter == 1){
            if($yearQuarter == 2){
                $yearQuarter_last = 1; // periode terakhir dari sistem
                $period_text = "Q1"; // nama periode
                $yearQuarter_archive = 4; // periode archive dari sistem
                $year_last = date('Y', time()); // tentukan tahun last quartal
            } elseif($yearQuarter == 1){
                $yearQuarter_last = 4; // periode terakhir dari sistem
                $period_text = "FY"; // nama periode
                $yearQuarter_archive = 3; // periode archive dari sistem
                $year_last = date('Y', strtotime("-1 year", time())); // tentukan tahun last quartal
            } else {
                show_error('The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request.', 406, 'Not Acceptable');
                exit;
            }
            $year_archive = date('Y', strtotime("-1 year", time())); // tahun archive
        } else {
            show_error('The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request.', 406, 'Not Acceptable');
            exit;
        }

        // cek di archives apakah ada data di periode dan tahun ini
        $is_exist = $this->_archives_m->getRow($this->table_survey['exc'], [
            'tahun' => $year_archive,
            'periode' => $yearQuarter_archive
        ]);
        // cek jika datanya ada apa engga
        if($is_exist < 1){
            // ambil data
            $vya = $this->_general_m->getAll('*', $this->table_survey['exc'], []);
            // jika data ga kosong
            if(!empty($vya)){
                // lengkapi data
                foreach($vya as $k => $v){
                    $vya[$k]['tahun'] = $year_archive;
                    $vya[$k]['periode'] = $yearQuarter_archive;
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
                // update judul survey
                $this->_general_m->update($this->table_survey['setting'], 'id_survey', 0, array(
                    'judul' => 'Service Excellence Survey [Periode '.$period_text.' - '.$year_last.']'
                ));

                echo(1); // tanda sukses
            } else {
                echo(2); // beri tanda kalo data kosong
            }
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
        $period_now = ceil($month / 6);
        if($period_now ==  1){ // buat nandain periode sebelumnya
            // tentukan period
            $period_last = 2; // periode terakhir dari sistem
            $period_text = "FY"; // nama periode
            $period_archive = 1; // periode archive dari sistem
            // tentukan tahunnya
            $year_last = date('Y', strtotime("-1 year", time())); // tahun terakhir dari sistem
            $year_archive = $year_last; // tahun archive buat sistem
        } elseif($period_now == 2) { // jika di periode pertama
            // tentukan period
            $period_last = 1; // periode terakhir dari sistem
            $period_text = "H1"; // nama periode
            $period_archive = 2; // periode archive dari sistem
            // tentukan tahunnya
            $year_last = date('Y', time()); // tahun terakhir dari sistem
            $year_archive = date('Y', strtotime("-1 year", time())); // tahun archive buat sistem
        } else {
            show_error('The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request.', 406, 'Not Acceptable');
            exit;
        }

        // cek di archives apakah ada data di periode dan tahun ini
        $is_exist = $this->_archives_m->getRow($this->table_survey['eng'], [
            'tahun' => $year_archive,
            'periode' => $period_archive
        ]);
        // cek jika datanya ada apa engga
        if($is_exist < 1){
            // ambil data
            $vya = $this->_general_m->getAll('*', $this->table_survey['eng'], []);
            // jika data kosong
            if(!empty($vya)){
                // lengkapi data
                foreach($vya as $k => $v){
                    $vya[$k]['tahun'] = $year_archive;
                    $vya[$k]['periode'] = $period_archive;
                    $vya[$k]['pertanyaan'] = $this->_general_m->getOnce('pertanyaan', $this->table_survey['eng_pertanyaan'], array('id' => $v['id_pertanyaan']))['pertanyaan'];
                }
                // masukkan ke database archives
                $this->_archives_m->insertAll($this->table_survey['eng'], $vya);
                //hapus data dari database utama
                $this->_general_m->truncate($this->table_survey['eng']);
                // ubah judul survey
                $this->_general_m->update($this->table_survey['setting'], 'id_survey', 1, array(
                    'judul' => 'Employee Engagement Survey [Periode '.$period_text.' - '.$year_last.']'
                ));

                echo(1); // tanda sukses
            } else {
                echo(2); // tanda data kosong
            }
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
        $period_now = ceil($month / 6);
        if($period_now ==  1){ // buat nandain periode sebelumnya
            // tentukan period
            $period_last = 2; // periode terakhir dari sistem
            $period_text = "FY"; // nama periode
            $period_archive = 1; // periode archive dari sistem
            // tentukan tahunnya
            $year_last = date('Y', strtotime("-1 year", time())); // tahun terakhir dari sistem
            $year_archive = $year_last; // tahun archive buat sistem
        } elseif($period_now == 2) { // jika di periode pertama
            // tentukan period
            $period_last = 1; // periode terakhir dari sistem
            $period_text = "H1"; // nama periode
            $period_archive = 2; // periode archive dari sistem
            // tentukan tahunnya
            $year_last = date('Y', time()); // tahun terakhir dari sistem
            $year_archive = date('Y', strtotime("-1 year", time())); // tahun archive buat sistem
        } else {
            show_error('The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request.', 406, 'Not Acceptable');
            exit;
        }
        // cek di archives apakah ada data di periode dan tahun ini
        $is_exist = $this->_archives_m->getRow($this->table_survey['360'], [
            'tahun' => $year_archive,
            'periode' => $period_archive
        ]);
        // cek jika datanya ada apa engga
        if($is_exist < 1){
            // ambil data
            $vya = $this->_general_m->getAll('*', $this->table_survey['360'], []);
            // jika data kosong
            if(!empty($vya)){
                // lengkapi data
                foreach($vya as $k => $v){
                    $vya[$k]['tahun'] = $year_archive;
                    $vya[$k]['periode'] = $period_archive;
                    $pertanyaan = $this->_general_m->getOnce('id_kategori_pertanyaan, pertanyaan', $this->table_survey['360_pertanyaan'], array('id' => $v['id_pertanyaan']));
                    $vya[$k]['pertanyaan'] = $pertanyaan['pertanyaan'];
                    $vya[$k]['id_kategori_pertanyaan'] = $pertanyaan['id_kategori_pertanyaan'];
                    $vya[$k]['nama_kategori'] = $this->_general_m->getOnce('nama_kategori', $this->table_survey['360_kategori'], array('id_kategori_pertanyaan' => $pertanyaan['id_kategori_pertanyaan']))['nama_kategori'];
                }
                // masukkan ke database archives
                $this->_archives_m->insertAll($this->table_survey['360'], $vya);
                //hapus data dari database utama
                $this->_general_m->truncate($this->table_survey['360']);
                // ubah judul survey
                $this->_general_m->update($this->table_survey['setting'], 'id_survey', 2, array(
                    'judul' => '360° Feedback [Periode '.$period_text.' - '.$year_last.']'
                ));

                echo(1); // tanda sukses
            } else {
                echo(2); // beri tanda data kosong
            }
        } else {
            echo(0); // tanda gagal
        }
    }
    /**
     * get status survey with for ajax
     *
     * @return void
     */
    function ajax_getStatusSuvey(){
        // ambil status masing survey data dengan periode dipilih
        // load models
        $this->load->model('_archives_m');
        // find the quarter of year
        //Get the month number of the date
        $month = date("n", time());
        //Divide that month number by 3 and round up using ceil.
        $period_now = ceil($month / 6);
        $yearQuarter = ceil($month / 3);

        if($yearQuarter == 4 || $yearQuarter == 3){
            // tentukan periode buat archive dan last
            if($yearQuarter == 4){
                $yearQuarter_last = 3; // periode terakhir dari sistem
                $yearQuarter_archive = 2; // periode archive dari sistem
            } elseif($yearQuarter == 3){
                $yearQuarter_last = 2; // periode terakhir dari sistem
                $yearQuarter_archive = 1; // periode archive dari sistem
            } else {
                show_error('The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request.', 406, 'Not Acceptable');
                exit;
            }
            $year_archive_exc = date('Y', time()); // tentukan tahun archive
            $year_last_exc = $year_archive_exc; // tentukan tahun last quartal
        } elseif($yearQuarter == 2 || $yearQuarter == 1){
            if($yearQuarter == 2){
                $yearQuarter_last = 1; // periode terakhir dari sistem
                $yearQuarter_archive = 4; // periode archive dari sistem
                $year_last_exc = date('Y', time()); // tentukan tahun last quartal
            } elseif($yearQuarter == 1){
                $yearQuarter_last = 4; // periode terakhir dari sistem
                $yearQuarter_archive = 3; // periode archive dari sistem
                $year_last_exc = date('Y', strtotime("-1 year", time())); // tentukan tahun last quartal
            } else {
                show_error('The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request.', 406, 'Not Acceptable');
                exit;
            }
            $year_archive_exc = date('Y', strtotime("-1 year", time())); // tahun archive
        } else {
            show_error('The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request.', 406, 'Not Acceptable');
            exit;
        }

        // untuk survey engagement dan f360
        if($period_now ==  1){ // buat nandain periode sebelumnya
            // tentukan period
            $period_last = 2; // periode terakhir dari sistem
            $period_archive = 1; // periode archive dari sistem
            // tentukan tahunnya
            $year_last = date('Y', strtotime("-1 year", time())); // tahun terakhir dari sistem
            $year_archive = $year_last; // tahun archive buat sistem
        } elseif($period_now == 2) { // jika di periode pertama
            // tentukan period
            $period_last = 1; // periode terakhir dari sistem
            $period_archive = 2; // periode archive dari sistem
            // tentukan tahunnya
            $year_last = date('Y', time()); // tahun terakhir dari sistem
            $year_archive = date('Y', strtotime("-1 year", time())); // tahun archive buat sistem
        } else {
            show_error('The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request.', 406, 'Not Acceptable');
            exit;
        }

        // cek di archives apakah ada data di periode dan tahun ini
        $isExist_eng = $this->_archives_m->getRow($this->table_survey['eng'], [
            'tahun' => $year_archive,
            'periode' => $period_archive
        ]);
        // cek di archives apakah ada data di periode dan tahun ini
        $isExist_360 = $this->_archives_m->getRow($this->table_survey['360'], [
            'tahun' => $year_archive,
            'periode' => $period_archive
        ]);
        // cek di archives apakah ada data di periode dan tahun ini
        $isExist_exc = $this->_archives_m->getRow($this->table_survey['exc'], [
            'tahun' => $year_archive_exc,
            'periode' => $yearQuarter_archive
        ]);

        // cek jika data eng ada di archives
        if($isExist_eng < 1){
            $exist_eng = 0;
        } else {
            $exist_eng = 1;
        }
        // cek jika data exc ada di archives
        if($isExist_exc < 1){
            $exist_exc = 0;
        } else {
            $exist_exc = 1;
        }
        // cek jika data 360 ada di archives
        if($isExist_360 < 1){
            $exist_360 = 0;
        } else {
            $exist_360 = 1;
        }

        // balikkan data dalam bentuk json
        echo(json_encode([
            'eng' => $exist_eng,
            'exc' => $exist_exc,
            'f360' => $exist_360
        ]));
    }

    function ajax_periodToggle(){
        // ubah is_period dari database
        $this->_general_m->update($this->table_survey['setting'], 'id_survey', $this->input->post('id_survey'), array('is_period' => $this->input->post('is_period')));
    }

    function ajax_periodToggleIsPeriod(){
        $vya_period = $this->_general_m->getAll("is_period", $this->table_survey['setting'], array());

        echo(json_encode(array(
            'eng' => $vya_period[1]['is_period'],
            'exc' => $vya_period[0]['is_period'],
            'f360' => $vya_period[2]['is_period']
        )));
    }

}

/* End of file Settings.php */
