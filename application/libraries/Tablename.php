<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * this library is used for dynamic table name based on year
 */
class Tablename {
  private $table = [
    'master' => [
      'position' => 'master_position',
    ],
  ];
  
  /**
   * get nama table
   *
   * @param  mixed $tableName -> harus berformat '<jenis_data>'+'_'+'<nama_data>'
   * @return void
   */
  public function get($tableName){
    $getStructureName = explode('_', $tableName);
    $tableName = $this->table[$getStructureName[0]][$getStructureName[1]];
    return $tableName;
  }
}

?>