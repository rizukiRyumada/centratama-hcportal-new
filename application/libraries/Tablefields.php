<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * this library contains table settings
 */
class Tablefields {
  protected $fields = [];

  function __construct()
  {
    // ambil data dari file json dulu
    // PRODUCTION WINDOWS PATH TROUBLE
    $str = file_get_contents($_SERVER['DOCUMENT_ROOT'] . 'application/libraries/Tablefields_data/fields.json');
    $this->fields = json_decode($str, true);
  }

  function get() {
    return $this->fields;
  }

// foreach ($data_fields as $k => $v) {
//   if ($k == 0) {
//     $data_fields[$k] = [
//       $v => [
//         'type' => 'INT',
//         'auto_increment' => true,
//         'unique' => true,
//       ]
//     ];
//   }
// }
}
