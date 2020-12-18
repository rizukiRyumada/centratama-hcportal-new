<?php

// TODO tambah status upload doc

defined('BASEPATH') OR exit('No direct script access allowed');

class Document extends AdminController {
    
    public function __construct() {
        parent::__construct();

        // load model
		$this->load->model('M_nomor');
		
	}
	
	public function index() {
		$data['entity'] = $this->M_nomor->getEntity();
		$data['no'] = $this->M_nomor->getAll();		

		$this->form_validation->set_rules('no', '<b>No</b>', 'required');
		$this->form_validation->set_rules('perihal', '<b>Perihal</b>', 'required');
		if ($this->form_validation->run() == false) {
			
			// main data
			$data['sidebar'] = getMenu(); // ambil menu
			$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
			$data['user'] = getDetailUser(); //ambil informasi user
			$data['page_title'] = $this->_general_m->getOnce('title', 'user_menu', array('url' => $this->uri->uri_string()))['title'];
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
			
			$this->db->insert('document_keluar', $data);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Success Added</div>');
			redirect('document','refresh');
		}
	}

    public function report() {
		// main data
		$data['sidebar'] = getMenu(); // ambil menu
		$data['breadcrumb'] = getBreadCrumb(); // ambil data breadcrumb
		$data['user'] = getDetailUser(); //ambil informasi user
		$data['page_title'] = $this->_general_m->getOnce('title', 'user_menu_sub', array('url' => $this->uri->segment(1).'/'.$this->uri->segment(2)))['title']; // for submenu
		$data['load_view'] = 'document/report_document_v';
		// additional styles and custom script
		$data['additional_styles'] = array('plugins/datatables/styles_datatables');
		// $data['custom_styles'] = array('survey_styles');
		$data['custom_script'] = array('plugins/pdfobject/script_pdfobject.php', 'plugins/datatables/script_datatables', 'document/script_document');

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
			if(empty($noSur->file)){
				$row[] = array(
					'no_surat'	=> $noSur->no_surat,
					'file_name'	=> "",
					'file_type'	=> ""
				);
			} else {
				$filename = explode('.', $noSur->file);

				$row[] = array(
					'no_surat'	=> $noSur->no_surat,
					'file_name'	=> $filename[0],
					'file_type'	=> $filename[1]
				);
			}

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
        $data['user'] = $this->db->get_where('master_employee', ['nik' => $this->session->userdata('nik')])->row_array();
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
		$data['user'] = $this->db->get_where('master_employee', ['nik' => $this->session->userdata('nik')])->row_array();
		$data = [
			'no_surat' => $this->input->post('no'),
			'perihal' => $this->input->post('perihal'),
			'pic' => $this->input->post('pic'),
			'jenis_surat' => $this->input->post('jenis'),
			'note' => $this->input->post('note'),
			'tahun' => date('Y')
		];

		$this->db->insert('document_keluar', $data);
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

/* -------------------------------------------------------------------------- */
/*                                   OTHERS                                   */
/* -------------------------------------------------------------------------- */

	public function attachDocument()
	{
		$filetypes = array('pdf', 'doc', 'docx', 'jpg', 'jpeg');

		$config['upload_path']          = './assets/document/surat/';
		$config['allowed_types']        = implode('|', $filetypes);
		$config['max_size']             = 1024;
		$config['file_name']			= str_replace('/', '_', $this->input->post('no_surat'));
		// $config['file_name']			= date('D, d M Y H:i:s');
		$config['overwrite']			= TRUE;

		$this->load->library('upload', $config);

		// hapus semua file yg ada dulu di berbagai 
		// TODO tambahkan jika dia ga kosong filenya baru unlink cek dulu
		foreach($filetypes as $v){
			unlink('./assets/document/surat/'.$config['file_name'].'.'.$v);
		}

		if ( ! $this->upload->do_upload('document_attach')) {
			// set pesan error
			$this->session->set_userdata('msg_swal', array(
				'icon' => "error",
				'title' => "Upload Error",
				'msg' => $this->upload->display_errors()
			));
		} else {
			// ambil status upload
			$upload_status = $this->upload->data();
			// $this->load->view('upload_success', $data);
			
			// set pesan sukses
			$this->session->set_userdata('msg_swal', array(
				'icon' => "success",
				'title' => "Upload Success",
				'msg' => '<table class="table text-left"><tr><td>File name</td><td>:</td><td>'.$upload_status['file_name'].'</td></tr><tr><td>File type</td><td>:</td><td>'.$upload_status['file_type'].'</td></tr><tr><td>File size</td><td>:</td><td>'.$upload_status['file_size'].'KB</td></tr></table>'
			));

			$this->_general_m->update('document_keluar', 'no_surat', $this->input->post('no_surat'), array(
				'file' => $upload_status['file_name']
			));
		}

		// arahkan balik ke report document
		redirect('document/report');
	}

	public function deleteDocument(){
		$filename = $this->input->get('filename'); // ambil nama file
		
		unlink('./assets/document/surat/'.$filename); // hapus file dari directory
		// hapus nama file dari database
		$this->_general_m->update('document_keluar', 'no_surat', $this->input->get('no_surat'), array('file' => ''));
		
		$this->session->set_userdata('msg_swal', array(
			'title' => 'File Deleted',
			'icon' => 'success',
			'msg' => 'The Document has been successfuly deleted.'
		));
	}
}

/* End of file Document.php */