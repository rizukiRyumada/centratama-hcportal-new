<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_nomor extends CI_Model {
	var $table = 'jenis_surat';
    var $column_order = array('no_surat','perihal','pic','tanggal','note','surat_keluar.jenis_surat'); //set column field database for datatable orderable
    var $column_search = array('no_surat','perihal','pic','tanggal','note','surat_keluar.jenis_surat'); //set column field database for datatable searchable 
	var $order = array('surat_keluar.id' => 'asc'); // default order 
	
	private function _get_datatables_query()
    {
		$role_id = $this->session->userdata('akses_surat_id');
        //add custom filter here
		$this->db->select('jenis_surat.jenis_surat as jns_surat, surat_keluar.no_surat, surat_keluar.perihal, surat_keluar.tanggal, surat_keluar.id, surat_keluar.pic, surat_keluar.note, surat_keluar.jenis_surat, surat_keluar.jenis_surat as id_jenis');
        if($this->input->post('jenis_surat'))
        {
			$this->db->where('surat_keluar.jenis_surat', $this->input->post('jenis_surat'));
        }
		$this->db->from($this->table);
		$this->db->join('surat_keluar', 'surat_keluar.jenis_surat = jenis_surat.id');
		
		$this->db->join('user_access_surat', 'jenis_surat.id = user_access_surat.surat_id');
		$this->db->where('user_access_surat.role_surat_id', $role_id);
        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function getListDataTables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }


    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

	public function getAll()
	{
		$role_id = $this->session->userdata('akses_surat_id');

		$this->db->select('jenis_surat.jenis_surat, surat_keluar.no_surat, surat_keluar.perihal, surat_keluar.tanggal, surat_keluar.pic, surat_keluar.note');
		$this->db->from('jenis_surat');
		$this->db->join('surat_keluar', 'surat_keluar.jenis_surat = jenis_surat.id');
		$this->db->join('user_access_surat', 'jenis_surat.id = user_access_surat.surat_id');
		$this->db->where('user_access_surat.role_surat_id', $role_id);
		$this->db->order_by('surat_keluar.tanggal', 'desc');
		$this->db->order_by('surat_keluar.no_surat', 'desc');
		$this->db->limit(10);
		
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getEntity()
	{
		return $this->db->get('entity')->result_array();
	}

	public function getJenis()
	{
		return $this->db->get('jenis_surat')->result_array();
	}

	public function getSubjenis($jenis)
	{
		return $this->db->get_where('tipe_surat', ['id_jenis' => $jenis])->result_array();
	}

	public function getJenisbyId($id)
	{
		$this->db->select('jenis_surat.jenis_surat, surat_keluar.no_surat, surat_keluar.perihal, surat_keluar.tanggal, surat_keluar.pic, surat_keluar.note');
		$this->db->from('jenis_surat');
		$this->db->join('surat_keluar', 'surat_keluar.jenis_surat = jenis_surat.id');
		$this->db->where('jenis_surat.id', $id);
		$query = $this->db->get();
		return $query->result_array();		
	}

	public function getNoUrut($jenis)
	{
		$tahun = date('Y');
		$this->db->select('LEFT(no_surat,3) as kode', FALSE);
		$this->db->where('tahun', $tahun);
		$this->db->where('jenis_surat', $jenis);
		$this->db->order_by('no_surat', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('surat_keluar');
		if ($query->num_rows() == 0) {
			$kode = 1;
		}elseif ($query->num_rows() <> 0) {
			//jika kode belum ada      
			$data = $query->row();
			$kode = intval($data->kode) + 1;
		}
		$kodejadi = str_pad($kode, 3, "0", STR_PAD_LEFT);
		return $kodejadi;
	}
}

/* End of file M_nomor.php */
