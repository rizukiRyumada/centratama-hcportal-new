<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * this library is used for dynamic table name based on year
 */
class Tablename {
  protected $CI;
  protected $table = 'setting_tablename';
  
  function __construct()
  {
    $this->CI =& get_instance();
    $this->CI->load->library([
      'session',
    ]);
  }
  
  /**
   * get nama table dinamis
   *
   * @param  mixed $tablename -> harus berformat '<jenis_data>'+'_'+'<nama_data>'
   * @return void
   */
  public function get($tablename){
    // cek apa nama tabel udh ada di session?, kalo gaada ambil dari database dan taruh di session
    if($this->CI->session->userdata('tablename') == NULL){
      // get tablename from db
      $tablenameData = $this->CI->db->get_where($this->table, ['tablename' => $tablename])->row_array();
      $tablename = $tablename.'_'.$tablenameData['currentPostfix'];
      // // update tablename to session
      $this->CI->session->set_userdata([
        'tablename' => $tablename,
      ]);
    } else {
      // get tablename from session
      $tablename = $this->CI->session->userdata('tablename');
    }

    return $tablename;
  }
}

?>