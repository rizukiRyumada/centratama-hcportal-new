<?php
// TODO security checks -> folder upload document sama tem/files bisa eksekusi file php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends MainController {

    public function index()
    {
        
    }

/* -------------------------------------------------------------------------- */
/*                                AJAX Function                               */
/* -------------------------------------------------------------------------- */    
    /**
     * upload file dengan fungsi ajax, untuk jquery upload file dari hayageek, 
     * http://hayageek.com/docs/jquery-upload-file.php
     *
     * @return void
     */
    public function ajax_upload(){
        if(isset($_FILES["myfile"])){
            $path = $_SERVER["DOCUMENT_ROOT"].$this->input->post('path'); // ambil path

            // PRODUCTION masalah folder path windows
            // $path = $_SERVER["DOCUMENT_ROOT"].'/'.$this->input->post('path'); // ambil path

            $session_name = $this->input->post('session_name'); // ambil nama session
            $files = $this->input->post('files');
            $flag_upload_new = $this->input->post('flag_upload_new');

            // cek directory dengan nik apa ada, kalo gaada bikin lah
            if(is_dir($path) == false){
                mkdir($path, 0755, false);
                // mkdir($path, 0777, true); // PRODUCTION buat di windows server, mode is ignored in windows
            }

            $output_dir = $path.'/';

            // PRODUCTION masalah folder path windows
            // $output_dir = str_replace('\\', '/', $output_dir); // pathnya sesuaikan untuk windows

            // single file output
            // Array ( [myfile] => Array ( [name] => REVISI KAMUS PSIKOLOGI II_A5.pdf [type] => application/pdf [tmp_name] => /tmp/phpQQ8pSO [error] => 0 [size] => 844745 ) ) 

            $fileExtension = pathinfo($_FILES['myfile']['name'], PATHINFO_EXTENSION); // ambil file extension
            // generate unique filename
            do {
                $fileName = md5($_FILES['myfile']['name'] . microtime()).".".$fileExtension;
            } while (file_exists($output_dir.$fileName) == true);
            $ret = array();
            
            //	This is for custom errors;	
            /*	$custom_error= array();
                $custom_error['jquery-upload-file-error']="File already exists";
                echo json_encode($custom_error);
                die();
            */
            $error = $_FILES["myfile"]["error"];
            //You need to handle  both cases
            //If Any browser does not support serializing of multiple files using FormData() 
            if(!is_array($_FILES["myfile"]["name"])) //single file
            {
                $fileName_origin = $_FILES["myfile"]["name"];
                move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
                $ret[]= $fileName;
                    
                // jika upload pada new form simpan nama filenya ke session
                if($flag_upload_new == 1){
                    // buat session untuk menyimpan nama files
                    if(empty($this->session->userdata($session_name))){
                        $files_new[0] = array(
                            'file_name' => $fileName,
                            'file_nameOrigin' => $fileName_origin,
                            'size' => $_FILES['myfile']['size'],
                            'type' => $_FILES['myfile']['type'],
                            'time' => date('Y-m-d h:i', time())
                        );
                        $this->session->set_userdata($session_name, $files_new);
                    } else {
                        $files_new = $this->session->userdata($session_name);
                        $files_new[array_key_last($files_new) + 1] = array(
                            'file_name' => $fileName,
                            'file_nameOrigin' => $fileName_origin,
                            'size' => $_FILES['myfile']['size'],
                            'type' => $_FILES['myfile']['type'],
                            'time' => date('Y-m-d h:i', time())
                        );
                        $this->session->set_userdata($session_name, $files_new);
                    }
                } else { // jika engga simpan ke variable files
                    // perbarui file list
                    $files_temp = json_decode($files, true);
                    if(empty($files_temp)){
                        $files_new[0] = array(
                            'file_name' => $fileName,
                            'file_nameOrigin' => $fileName_origin,
                            'size' => $_FILES['myfile']['size'],
                            'type' => $_FILES['myfile']['type'],
                            'time' => date('Y-m-d h:i', time())
                        );
                    } else {
                        // masukkan files remp ke variable files_new
                        $files_new = $files_temp;
                        $files_new[array_key_last($files_temp) + 1] = array(
                            'file_name' => $fileName,
                            'file_nameOrigin' => $fileName_origin,
                            'size' => $_FILES['myfile']['size'],
                            'type' => $_FILES['myfile']['type'],
                            'time' => date('Y-m-d h:i', time())
                        );
                    }
                }
                
            } else { //Multiple files, file[]
                $fileCount = count($_FILES["myfile"]["name"]);
                // jika upload file di create new form
                if($flag_upload_new == 1){
                    $files_new = $this->session->userdata($session_name);
                } else { // jika upload file dari viewer
                    $files_new = json_decode($files, true);
                }

                if(empty($files_new)){
                    $y = 0;
                } else {
                    $y = array_key_last($files_new) + 1;
                }
                for($i=0; $i < $fileCount; $i++){
                    $fileName_origin = $_FILES["myfile"]["name"][$i];
                    move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);
                    $ret[]= $fileName;

                    if(empty($files_new)){
                        $files_new[$i] = array(
                            'file_name' => $fileName,
                            'file_nameOrigin' => $fileName_origin,
                            'size' => $_FILES['myfile']['size'][$i],
                            'type' => $_FILES['myfile']['type'][$i],
                            'time' => date('Y-m-d h:i', time())
                        );
                        $y = $i + 1;
                    } else {
                        $files_new[$y] = array(
                            'file_name' => $fileName,
                            'file_nameOrigin' => $fileName_origin,
                            'size' => $_FILES['myfile']['size'][$i],
                            'type' => $_FILES['myfile']['type'][$i],
                            'time' => date('Y-m-d h:i', time())
                        );
                        $y++;
                    }
                }
                // jika upload dari create new form, masukkan data ke session
                if($flag_upload_new == 1){
                    $this->session->set_userdata($session_name, $files_new);
                } else {
                    // nothing
                }
            }

            echo json_encode(array(
                'file_counter' => count($files_new),
                'files_new' => $files_new
            ));
            // echo json_encode($ret);
        }
    }
    
    /**
     * menghapus file upload dengan ajax
     * $path -> alamat filenya
     * $filename -> nama filenya
     * $file_session -> nama session filenya
     *
     * @return void
     */
    public function ajax_delete(){
        $path = $_SERVER["DOCUMENT_ROOT"].$this->input->post('path'); // ambil path

        // PRODUCTION masalah folder path windows
        // $path = $_SERVER["DOCUMENT_ROOT"].'/'.$this->input->post('path'); // ambil path

        $file_name = $this->input->post('filename'); // ambil filename
        $files = $this->input->post('files');
        $session_name = $this->input->post('session_name');
        $file_session = $this->session->userdata($session_name);

        $dir_file = $path."/".$file_name;
        // $dir_file = str_replace('\\', '/', $dir_file); // PRODUCTION ubah dirpath sesuai windows server
        
        // cek jika ada filenya, hapus
        if(file_exists($dir_file) == true){
            unlink($dir_file); // hapus file dari server

            // jika session name dan file_sessionnya kosong, brarti delete filenya dari fungsi viewer
            // kalo dari fungsi create new form, ubah di session file
            if(!empty($session_name) && !empty($file_session)){
                // perbarui data session file
                $files_new = array(); $x = 0;
                foreach($file_session as $v){
                    if($v['file_name'] != $file_name){
                        $files_new[$x] = $v;
                        $x++;
                    }
                }
                $this->session->set_userdata($session_name, $files_new);
            } else { // kalo dari fungsi viewer, ubah variabel file
                // perbarui file list
                $files_temp = json_decode($files, true);
                $files_new = array(); $x = 0;
                foreach($files_temp as $v){
                    if($v['file_name'] != $file_name){
                        $files_new[$x] = $v;
                        $x++;
                    }
                }
            }

            // checker untuk empty data
            if(empty($files_new)){
                $files_new = "";
                $file_counter = 0;
            } else {
                $file_counter = count($files_new);
            }

            echo json_encode(array(
                'file_counter' => $file_counter,
                'files_new' => $files_new
            ));
        }

    }

/* -------------------------------------------------------------------------- */
/*                               OTHER Function                               */
/* -------------------------------------------------------------------------- */

    function unggahin($userfile){
        $CI =& get_instance(); //codeigniter tidak bisa memanggil library di helper, jadi gunakan ini
        
        //set config upload file
        $config = array(
            "upload_path"   => "./temp_document",
            "allowed_types" => "pdf|doc|docx|ppt|pptx|xps|odt|xls|xlsx|wps|jpg|jpeg|gif|png|bmp|tiff",
            "max_size"      => "32384"
        );
        $CI->load->library('upload', $config);//load library upload
        //upload file
        if ($CI->upload->do_upload($userfile)){
            //ambil data upload jika berhasi;
            $upload_data = array('upload_data' => $CI->upload->data());
        }else{
            //ambil data error jika gagal;
            $upload_data = array('error' => $CI->upload->display_errors());
        }
        
        $data = array(
            'nama'        => $upload_data['upload_data']['file_name'],
            'jenis_file'  => $userfile,
            'tipe_file'   => $upload_data['upload_data']['file_type'],
            'ekstensi'    => $upload_data['upload_data']['file_ext'],
            'ukuran'      => $upload_data['upload_data']['file_size'],
            'tanggal'     => date('Y-m-d')
        );
    
        return $data ; //balikkan nilai
    }

    function removeSession(){
        
        $this->session->unset_userdata('ptk_files');
        
    }

    // function siapUnggah($userfile, $session){
    //     $CI =& get_instance(); //codeigniter tidak bisa memanggil library di helper, jadi gunakan ini
        
    //     if($_FILES[$userfile]['error']==0){ //ada file dipilih
    
    //         $data = $CI->session->userdata($session)[$userfile];//panggil isi di dalam session index userfile
            
    //         if(!empty($data['nama'])){// cek apakah nama file kosong
    //             unlink('document/' . $data['nama']); //hapus file yang ada di server
    //         }
            
    //         $upload_data = $this->unggahin($userfile);//unggah file; 
    
    //         return $upload_data;
    
    //     }elseif($_FILES[$userfile]['error']==4){//tidak ada file dipilih, user tidak ingin mengubah file
            
    //         if(!empty($CI->session->userdata($session)[$userfile])) { //cek jika session $session sdh tersedia 
    //             $upload_data = $CI->session->userdata($session)[$userfile];// ambil data dalam session lalu masukkan kembali ke upload_data
    //         }else{
    //             $upload_data = ""; //kosongkan nilai
    //         }
            
    //     }else{ //error lainnya
    //         die('ERROR 404 NOT FOUND :(');
    //     }
        
    //     return $upload_data;
    // }
    
    

}

/* End of file Upload.php */
