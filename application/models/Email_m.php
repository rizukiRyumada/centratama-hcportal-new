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
    

    function general_sendEmail($email, $email_cc, $penerima_nama, $subject_email, $status, $details, $msg, $link){
        $data_penerima_email = array(
            'email'     => $email, // email penerima bisa berupa array apabila mau ngirim lebih dari 1 penerima
            'email_cc'  => $email_cc // email cc bisa berupa array
        );

        $emailText = $this->general_templateNotifikasi($penerima_nama, $status, $details, $msg, $link);

        sendEmail($data_penerima_email, $emailText, $subject_email); // kirim email notifikasi pakai helper
    }

    /* -------------------------------------------------------------------------- */
    /*        Komponen function buat mengirim notifikasi email ke karyawan        */
    /* -------------------------------------------------------------------------- */
    /**
     * notifikasi
     *
     * @param  mixed $nik
     * @param  mixed $job_profile
     * @param  mixed $data_penerima_email
     * @param  mixed $subject_email
     * @return void
     */
    public function notifikasi($nik, $job_profile, $data_penerima_email, $subject_email){
        $data_penerima_email = array(
            'nama'      => implode(" ", $karyawan),
            'email'     => $karyawan_email,
            'email_cc'  => $email_cc,
            'id_posisi' => $id_posisi,
            'msg'       => 'Please revise your Job Profile.!'
        );

        // jika status bukan completed (7)


        if($job_profile['status'] != 4){ // cek jika status approval bukan final
            /* ------------------- create webtoken buat penerima email ------------------ */
            $resep = array( // buat resep token agar unik
                'nik' => $nik,
                'id_posisi' => $data_penerima_email['id_posisi'],
                'date' => date('d-m-Y, H:i:s:v:u', time())
            );
            $token = md5(json_encode($resep)); // md5 encrypt buat id token
            $temp_token  = array( // data buat disave di token
                'direct'    => 'job_profile',
                'id_posisi' => $data_penerima_email['id_posisi']
            );
            if(!empty($data_penerima_email['msg'])){ // sematkan pesan ke data token
                $temp_token['msg'] = $data_penerima_email['msg'];
            }
            $data_token = json_encode($temp_token);

            // masukkan data token ke database
            $this->Jobpro_model->insert(
                'user_token',
                array(
                    'token'        => $token,
                    'data'         => $data_token,
                    'date_created' => date('Y-m-d H:i:s', time())
                )
            ); 
            $url_token = urlencode($token);
            //info penerima email tambahkan url
            $data_penerima_email['link'] = base_url('direct').'?token='.$url_token;
        }
        
        /* --------------------------- buat list karyawan --------------------------- */
        $data_karyawan = $this->Jobpro_model->getDetails('emp_name, email', 'master_employee', array('position_id' => $job_profile['id_posisi']));
        $counter_karyawan = count($data_karyawan);
        $karyawan = array('<ul>'); //buka ul
        foreach($data_karyawan as $key => $value){ //ambil nama karyawan)
            $karyawan[$key + 1] = '<li> -  '. $value['emp_name'] .'</li>';
            
            if($key+1 == $counter_karyawan){ //tutup kode ul
                $karyawan[$key + 2] = '</ul>';
            }
        }
        // info job profile tambahkan karyawan
        $job_profile['karyawan'] = implode(" ", $karyawan);

        $emailText = jobProfileNotif($job_profile, $data_penerima_email); // generate emailText
        //set penerima email adalah approver 1
        // sendEmail($penerima, $emailText, $subject_email)
        sendEmail($data_penerima_email, $emailText, $subject_email); // kirim email notifikasi pakai helper
    }

/* -------------------------------------------------------------------------- */
/*                     TEMPLATE GENERAL FOR SENDING EMAIL                     */
/* -------------------------------------------------------------------------- */
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

}

/* End of file email_m.php */
