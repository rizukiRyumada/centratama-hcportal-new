<?php

// TODO upload document dalam pdf, doc, excel
// TODO tambah status upload doc

defined('BASEPATH') OR exit('No direct script access allowed');

class Document extends AdminController {
    
    public function __construct() {
        parent::__construct();

        // load model
		$this->load->model('M_nomor');
		
		// cek apa dia punya role admin atau maintenance
        if($this->session->userdata('role_id') != 1 && $this->session->userdata('role_id') != 2){
            show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
		}
	}

	protected function cekAkses(){
		// cek akses menu pada url 1
		$id_menu_sub = $this->_general_m->getOnce('id_menu_sub', 'survey_user_menu_sub', array('url' => $this->uri->segment(1)))['id_menu_sub'];
		// cek akses admin
		if($this->_general_m->getRow('survey_user_menu_sub_admins', array('id_menu_sub' => $id_menu_sub, 'nik' => $this->session->userdata('nik'))) < 1){
			show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
		}

		// jika akses sub menu dibawahnya cek dengan 2 url
		if(!empty($this->uri->segment(2))){
			// cek apa dia punya akses buat sesi ini
			if($this->session->userdata('role_id') == 2){
				// ambil id menu sub
				$id_menu_sub = $this->_general_m->getOnce('id_menu_sub', 'survey_user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['id_menu_sub'];
				// cek akses admin	
				if($this->_general_m->getRow('survey_user_menu_sub_admins', array('id_menu_sub' => $id_menu_sub, 'nik' => $this->session->userdata('nik'))) < 1){
					show_error('Sorry you are not allowed to access this part of application.', 403, 'Forbidden');
				}
			}
		}
	}
	
	public function index() {
		if($this->session->userdata('role_id') == 2){
			$this->cekAkses(); // cek akses
		}
        
		$data['entity'] = $this->M_nomor->getEntity();
		$data['no'] = $this->M_nomor->getAll();		

		$this->form_validation->set_rules('no', '<b>No</b>', 'required');
		$this->form_validation->set_rules('perihal', '<b>Perihal</b>', 'required');
		if ($this->form_validation->run() == false) {
			
			// main data
			$data['sidebar'] = getMenu(); // ambil menu
			$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
			$data['user'] = getDetailUser(); //ambil informasi user
			$data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu', array('url' => $this->uri->uri_string()))['title'];
			$data['load_view'] = 'document/index_document_v';
			// additional styles and custom script
            $data['additional_styles'] = array('plugins/datatables/styles_datatables');
			// $data['custom_styles'] = array('survey_styles');
			$data['custom_script'] = array('document/script_document', 'plugins/datatables/script_datatables');

			$this->load->view('main_v', $data);
		} else {
			$data = [
				'no_surat' => $this->input->post('no'),
				'perihal' => $this->input->post('perihal'),
				'pic' => $this->input->post('pic'),
				'jenis_surat' => $this->input->post('jenis'),
				'note' => $this->input->post('note'),
				'tahun' => date('Y')
			];
			
			$this->db->insert('surat_keluar', $data);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Success Added</div>');
			redirect('document','refresh');
		}
	}

    public function report() {
		if($this->session->userdata('role_id') == 2){
			$this->cekAkses(); // cek akses
		}

		// main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
		$data['page_title'] = $this->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['title']; // for submenu
		$data['load_view'] = 'document/report_document_v';
		// additional styles and custom script
		$data['additional_styles'] = array('plugins/datatables/styles_datatables');
		// $data['custom_styles'] = array('survey_styles');
		$data['custom_script'] = array('document/script_document', 'plugins/datatables/script_datatables');

		$this->load->view('main_v', $data);
	}
	
	public function ajax_no()
    {
        $list = $this->M_nomor->getListDataTables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $noSur) {
            $no++;
            $row = array();
            $row[] = $noSur->no_surat;
            $row[] = $noSur->perihal;
            $row[] = $noSur->pic;
            $row[] = date("d F Y", strtotime($noSur->tanggal));
            $row[] = $noSur->note;
            $row[] = $noSur->jns_surat;

            $data[]= $row;
        } 
        $output = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_nomor->count_all(),
            "recordsFiltered" => $this->M_nomor->count_filtered(),
            "data" => $data,
        ];

        echo json_encode($output);
    }

	public function getSub()
	{
		$jenis = $this->input->post('jenis', true);
		$data = $this->M_nomor->getSubjenis($jenis);
		echo(json_encode($data));
	}

	public function lihatNomor()
	{
        $data['user'] = $this->db->get_where('employe', ['nik' => $this->session->userdata('nik')])->row_array();
		$jenis = $this->input->post('jenis');
		$hasil = $this->M_nomor->getNoUrut($jenis);
		
		$entity = $this->input->post('entity', true);
		$sub = $this->input->post('sub', true);
		$nourut = substr($hasil,0,3);

		$array_bulan = array(1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
		$bulan = $array_bulan[date('n')];

		$tahun = date('Y');
		
		$data = [
				'entity' => $entity,
				'sub' => $sub,
				'bulan' => $bulan,
				'tahun' => $tahun,
				'no' => $nourut
		];
		echo json_encode($data);
	}

	public function simpan()
	{
		$data['user'] = $this->db->get_where('employe', ['nik' => $this->session->userdata('nik')])->row_array();
		$data = [
			'no_surat' => $this->input->post('no'),
			'perihal' => $this->input->post('perihal'),
			'pic' => $this->input->post('pic'),
			'jenis_surat' => $this->input->post('jenis'),
			'note' => $this->input->post('note'),
			'tahun' => date('Y')
		];

		$this->db->insert('surat_keluar', $data);
		redirect('document','refresh');
	}


	public function suratByjns()
	{
		$id = intval($this->input->get('q'));
		if ($id == 'all') {
			$query = $this->M_nomor->getAll();
			foreach ($query as $all){
				echo "<tr>";
				echo "<td>" . $all['no_surat'] . "</td>";
				echo "<td>" . $all['perihal'] . "</td>";
				echo "<td>" . $all['pic'] . "</td>";
				echo "<td>" . date("d F Y", strtotime($all['tanggal'])) . "</td>";
				echo "<td>" . $all['note'] . "</td>";
				echo "<td>" . $all['jenis_surat'] . "</td>";
				echo "</tr>";
			}
			
		} else {
			$sql = $this->M_nomor->getJenisbyId($id);
			foreach ($sql as $row){
				echo "<tr>";
				echo "<td>" . $row['no_surat'] . "</td>";
				echo "<td>" . $row['perihal'] . "</td>";
				echo "<td>" . $row['pic'] . "</td>";
				echo "<td>" . date('d F y', strtotime($row['tanggal'])) . "</td>";
				echo "<td>" . $row['note'] . "</td>";
				echo "<td>" . $row['jenis_surat'] . "</td>";
				echo "</tr>";
			}
		}
	}
}

/* End of file Document.php */
