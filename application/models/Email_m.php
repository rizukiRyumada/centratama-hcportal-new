<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class email_m extends CI_Model {

    
    public function __construct()
    {
        parent::__construct();
        // load email helper
        $mci = get_instance();
        $mci->load->helper('email_helper');
    }
        
    /**
     * fungsi general_sendEmail
     * mengirim email dengan details employee diproses yang sudah disiapkan
     *
     * @param  mixed $email
     * @param  mixed $email_cc
     * @param  mixed $penerima_nama
     * @param  mixed $subject_email
     * @param  mixed $status
     * @param  mixed $details
     * @param  mixed $msg
     * @param  mixed $link
     * @return void
     */
    function general_sendEmail($email, $email_cc, $penerima_nama, $subject_email, $status, $details, $msg, $link = "", $is_link){
        $data_penerima_email = array(
            'email'     => $email, // email penerima bisa berupa array apabila mau ngirim lebih dari 1 penerima
            'email_cc'  => $email_cc // email cc bisa berupa array
        );

        if($is_link == true && $link != ""){
            $emailText = $this->general_templateNotifikasi($penerima_nama, $status, $details, $msg, $link);
        } else {
            $emailText = $this->general_templateNotifikasiWithoutLink($penerima_nama, $status, $details, $msg);
        }

        sendEmail($data_penerima_email, $emailText, $subject_email); // kirim email notifikasi pakai helper
    }

    /**
     * fungsi untuk mengirim email notifikasi dengan template general, apa yang ada di fungsi ini?
     * - informasi karyawan terkait form
     * - status form
     * - mengirim dengan link atau tidak
     *
     * @param  string~array $email_penerima = alamat email "employee@centratamagroup.com" atau dengan array jika ingin mengirim lebih dari satu orang
     * @param  string~array $email_cc       = alamat email "employee@centratamagroup.com" atau dengan array jika ingin mengirim CC dari satu orang
     * @param  string $penerima_nama        = nama karyawan untuk ditaruh di kata2 "Dear bpk/ibu ".$penerima_nama
     * @param  string $email_subject        = subjek email, ngirim email apa? notifikasi? peringatan? pemberitahuan
     * @param  string $status_text          = teks terkait status dari form
     * @param  string $message              = pesan teks tambahan untuk pelengkap
     * @param  array $message_details       = detail pesan card isinya array 2 dimensi
     * @param  array $data_employee         = variabel ini harus didapetin dari $this->employee_m->getDetails_employee($nik) supaya lengkap datanya
     * @param  array $token_data            = alamat link buat dibawa langsung ke sub modul hcportal
     * @param  boolean $is_link             = penanda apa emailnya ada linknya
     * @return void
     */
    function general_sendNotifikasi($email_penerima, $email_cc, $penerima_nama, $email_subject, $status_text, $message, $message_details, $token_data, $is_link = true){
        // detail employee terkait form, bentuknya card
        /** bentuk array message_details
         * array(
         *     0 => array(
         *         'info_name' => 'name',
         *         'info' => 'information'
         *     )
         * )
         */
        $details = "";
        foreach($message_details as $v){
            $details .= '<tr>
                <td>'.$v['info_name'].'</td>
                <td>:</td>
                <td>'. $v['info'] .'</td>
            </tr>';
        }
        
        /* ------------------- create webtoken buat penerima email ------------------ */
        $resep = array( // buat resep token agar unik
            'random_string' => $email_penerima,
            'random_string2' => $penerima_nama,
            'random_string3' => $status_text,
            'date' => microtime()
        );
        $token = md5(json_encode($resep)); // md5 encrypt buat id token
        
        // bentuk array $token_data, harus ada 'direct'
        // array( // data buat disave di token
        //     'direct'    => $direct_to
        // );
        $token_data = json_encode($token_data);

        // masukkan data token ke database
        $this->_general_m->insert(
            'user_token',
            array(
                'token'        => $token,
                'data'         => $token_data,
                'date_created' => date('Y-m-d H:i:s', time())
            )
        ); 

        /* ----------------- create url token untuk ditaruh di email ---------------- */
        if($is_link == true){
            $url_token = urlencode($token);
            $link = base_url('direct').'?token='.$url_token;
        } else {
            $link = "";
        }
        
        $this->email_m->general_sendEmail($email_penerima, $email_cc, $penerima_nama, $email_subject, $status_text, $details, $message, $link, $is_link);
    }
    
    /**
     * fungsi untuk mengirim email notifikasi dengan template general, apa yang ada di fungsi ini?
     * - informasi karyawan terkait form
     * - status form
     *
     * @param  string~array $email_penerima = alamat email "employee@centratamagroup.com" atau dengan array jika ingin mengirim lebih dari satu orang
     * @param  string~array $email_cc       = alamat email "employee@centratamagroup.com" atau dengan array jika ingin mengirim CC dari satu orang
     * @param  string $penerima_nama        = nama karyawan untuk ditaruh di kata2 "Dear bpk/ibu ".$penerima_nama
     * @param  string $email_subject        = subjek email, ngirim email apa? notifikasi? peringatan? pemberitahuan
     * @param  string $status_text          = teks terkait status dari form
     * @param  string $message              = pesan teks tambahan untuk pelengkap
     * @param  array $data_employee         = variabel ini harus didapetin dari $this->employee_m->getDetails_employee($nik) supaya lengkap datanya
     * @param  array $token_data            = alamat link buat dibawa langsung ke sub modul hcportal
     * @return void
     */
    function general_sendNotifikasi_employeeForm($email_penerima, $email_cc, $penerima_nama, $email_subject, $status_text, $message, $data_employee, $token_data){
        // detail employee terkait form, bentuknya card
        $employee_details = '<tr>
                        <td>Employee Name</td>
                        <td>:</td>
                        <td>'. $data_employee['emp_name'] .'</td>
                    </tr>
                    <tr>
                        <td>NIK</td>
                        <td>:</td>
                        <td>'. $data_employee['nik'] .'</td>
                    </tr>
                    <tr>
                        <td>Division</td>
                        <td>:</td>
                        <td>'. $data_employee['divisi'] .'</td>
                    </tr>
                    <tr>
                        <td>Department</td>
                        <td>:</td>
                        <td>'. $data_employee['departemen'] .'</td>
                    </tr>
                    <tr>
                        <td>Position</td>
                        <td>:</td>
                        <td>'. $data_employee['position_name'] .'</td>
                    </tr>';
        /* ------------------- create webtoken buat penerima email ------------------ */
        $resep = array( // buat resep token agar unik
            'nik' => $data_employee['nik'],
            'id_posisi' => $data_employee['position_id'],
            'date' => microtime()
        );
        $token = md5(json_encode($resep)); // md5 encrypt buat id token
        
        // bentuk array $token_data, harus ada 'direct'
        // array( // data buat disave di token
        //     'direct'    => $direct_to
        // );
        $token_data = json_encode($token_data);

        // masukkan data token ke database
        $this->_general_m->insert(
            'user_token',
            array(
                'token'        => $token,
                'data'         => $token_data,
                'date_created' => date('Y-m-d H:i:s', time())
            )
        ); 

        /* ----------------- create url token untuk ditaruh di email ---------------- */
        $url_token = urlencode($token);
        $link = base_url('direct').'?token='.$url_token;
        
        $this->email_m->general_sendEmail($email_penerima, $email_cc, $penerima_nama, $email_subject, $status_text, $employee_details, $message, $link, TRUE);
    }

/* -------------------------------------------------------------------------- */
/*                     TEMPLATE GENERAL FOR SENDING EMAIL                     */
/* -------------------------------------------------------------------------- */    
    /**
     * general_templateNotifikasi
     *
     * @param  mixed $penerima_nama
     * @param  mixed $status
     * @param  mixed $details
     * @param  mixed $msg
     * @param  mixed $link
     * @return void
     */
    function general_templateNotifikasi($penerima_nama, $status, $details, $msg, $link){
        return '
        <style>
            .body-message p{
                margin: 0;
            }
            table tr td{
                vertical-align: top;
            }
            ul{
                padding-left: 1em;
                margin: 0 0.5em;
                list-style-type: none;
                margin: 0;
                padding: 0;
            }
        </style>

        <div> <!-- container -->
            <div style="margin: 0 auto; width: 600px;">
                <div class="body-message">
                    <br/>
                    <p>
                        Dear Bpk/Ibu '. $penerima_nama .'
                    </p>
                    <br/>
                    <p>
                        <b>'.$status.'</b>
                    </p>
                    <br/>
                    <div style="background-color: #DEDEDE; border-radius: 15px; padding: 20px;">
                        <table>
                            '.$details.'
                        </table>
                    </div><br/>
                    '.$msg.'<br/><br/>
                    '. '<p>Please click link below:</p><a href="'. $link .'">'. $link .'</a><br/>' .'
                    <br/>
                    <p>Thank You!,</p>
                    <p><b>HC Portal</b></p>
                </div>
            </div>
        </div>
        ';
    }
    
    /**
     * general_templateNotifikasiWithoutLink
     *
     * @param  mixed $penerima_nama
     * @param  mixed $status
     * @param  mixed $details
     * @param  mixed $msg
     * @return void
     */
    function general_templateNotifikasiWithoutLink($penerima_nama, $status, $details, $msg){
        return '
        <style>
            .body-message p{
                margin: 0;
            }
            table tr td{
                vertical-align: top;
            }
            ul{
                padding-left: 1em;
                margin: 0 0.5em;
                list-style-type: none;
                margin: 0;
                padding: 0;
            }
        </style>

        <div> <!-- container -->
            <div style="margin: 0 auto; width: 600px;">
                <div class="body-message">
                    <br/>
                    <p>
                        Dear Bpk/Ibu '. $penerima_nama .'
                    </p>
                    <br/>
                    <p>
                        <b>'.$status.'</b>
                    </p>
                    <br/>
                    <div style="background-color: #DEDEDE; border-radius: 15px; padding: 20px;">
                        <table>
                            '.$details.'
                        </table>
                    </div><br/>
                    '.$msg.'
                    <br/>
                    <br/>
                    <p>Thank You!,</p>
                    <p><b>HC Portal</b></p>
                </div>
            </div>
        </div>
        ';
    }

}

/* End of file email_m.php */
