<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_m extends CI_Model {

    /**  
     * php delete function that deals with directories recursively
     * delete_files('/path/for/the/directory/'); // bisa directory
     * delete_files('/path/for/the/directory/'); // bisa juga files
    */
    function delete_files($target) {
        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

            foreach( $files as $file ){
                unlink( $file );      
            }

            rmdir( $target );
        } elseif(is_file($target)) {
            unlink( $target );  
        }
    }

}

/* End of file Upload_m.php */
