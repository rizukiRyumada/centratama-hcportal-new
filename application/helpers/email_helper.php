<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * jobProfileNotif
 *
 * @param  mixed $job_profile
 * @param  mixed $data_penerima_email
 * @return void
 */
function jobProfileNotif($job_profile, $data_penerima_email){

    switch($job_profile['status']){
        case 0:
            $job_profile['status'] = 'Create Job Profile';
            $link = '<br/><p>Please click link below:</p><a href="'. $data_penerima_email['link'] .'">'. $data_penerima_email['link'] .'</a><br/>';
            break;
        case 1:
            $job_profile['status'] = 'Need Approval';
            $link = '<br/><p>Please click link below:</p><a href="'. $data_penerima_email['link'] .'">'. $data_penerima_email['link'] .'</a><br/>';
            break;
        case 2:
            $job_profile['status'] = 'Need Approval';
            $link = '<br/><p>Please click link below:</p><a href="'. $data_penerima_email['link'] .'">'. $data_penerima_email['link'] .'</a><br/>';
            break;
        case 3:
            $job_profile['status'] = 'Need Revise';
            $link = '<br/><p>Please click link below:</p><a href="'. $data_penerima_email['link'] .'">'. $data_penerima_email['link'] .'</a><br/>';
            break;
        case 4:
            $job_profile['status'] = 'Approved';
            $link = '<br/>';
            break;
    }

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
                    Dear Bpk/Ibu '. $data_penerima_email['nama'] .'
                </p>
                <br/>
                <p>
                    <b>Job Profile: '. $job_profile['status'] .'</b>
                </p>
                <br/>
                <div style="background-color: #DEDEDE; border-radius: 15px; padding: 20px;">
                    <table>
                        <tr>
                            <td>Position</td>
                            <td>:</td>
                            <td>'. $job_profile['position_name'] .'</td>
                        </tr>
                        <tr>
                            <td>Employee</td>
                            <td>:</td>
                            <td>'. $job_profile['karyawan'] .'</td>
                        </tr>
                    </table>
                </div>
                '. $link .'
                <br/>
                <p>Thank You!,</p>
                <p><b>HC Portal</b></p>
            </div>
        </div>
    </div>
    ';
}

/**
 * sendEmail
 *
 * @param  mixed $data_penerima_email
 * @param  mixed $emailText
 * @param  mixed $subject_email
 * @return void
 */
function sendEmail($data_penerima_email, $emailText, $subject_email){

// echo($subject_email);
// exit;

    $CI =& get_instance();

    // configuration for send email
    // FIXME tambahkan pengaturan smtp_crypto = tls
    $config = $CI->Jobpro_model->getDetail(
        'useragent, protocol, smtp_host, smtp_port, smtp_user, smtp_pass, charset, wordwrap, mailtype', 
        'setting_email', 
        array('id' => 2));
    // configuration for send email
    // $config = $CI->Jobpro_model->getDetail(
    //     'useragent, protocol, smtp_host, smtp_port, smtp_user, smtp_pass, charset, wordwrap, mailtype, smtp_crypto', 
    //     'setting_email', 
    //     array('id' => 1));
    $config['crlf'] = "\r\n";
    $config['newline'] = "\r\n";
    $CI->load->library('email');
    $CI->email->initialize($config);

    // SETTING identitas email
    // FIXME ubah from ke pengaturan server
    // $CI->email->from($config['smtp_user'], $config['smtp_user']);
    $CI->email->from('Ryumada@dev.github', 'Ryumada');
    //FIXME ubah to ke pengatauran server
    $CI->email->to($data_penerima_email['email']);
    // $CI->email->to('asd@ss.id'); //for testing
    // FIXME cc email
    if(!empty($data_penerima_email['email_cc'])){
        $CI->email->cc($data_penerima_email['email_cc']);
    }
    // what to send?
    // $CI->email->subject('Job Profile - Need Approval');
    $CI->email->subject($subject_email);
    // load email text from helper
    // emailText($name_atasan, $name_karyawan, $date, $status){
    $CI->email->message($emailText);
    
    // TODO ubah nama jadi email -> cek email apa dia punya email
    if(!empty($data_penerima_email['nama'])){
        //cek apa email kosong
        if($CI->email->send()){
            echo("success");
        } else {
            echo $CI->email->print_debugger(); //show debugger if error
        }     
    } else {
        // gausah kirim email tapi lempengin aja
    }
       
}

    // public function sendEmail($data_penerima_email, $emailText, $subject_email){
    //     // configuration for send email
    //     $config = $this->Jobpro_model->getDetail(
    //         'useragent, protocol, smtp_host, smtp_port, smtp_user, smtp_pass, charset, wordwrap, mailtype', 
    //         'setting-email', 
    //         array('id' => 1));
    //     $config['crlf'] = "\r\n";
    //     $config['newline'] = "\r\n";
    //     $this->load->library('email');
    //     $this->email->initialize($config);

    //     // SETTING identitas email
    //     $this->email->from('a4a81d98ec-3847f9@inbox.mailtrap.io', 'Ryumada');
    //     // $this->email->to($data_penerima_email['email']);
    //     $this->email->to('asd@ss.id'); //for testing
    //     // cc email
    //     // if(!empty($data_penerima_email['email_cc'])){
    //     //     $this->email->cc($data_penerima_email['email_cc']);
    //     // }
    //     // what to send?
    //     // $this->email->subject('Job Profile - Need Approval');
    //     $this->email->subject($subject_email);
    //     // load email text from helper
    //     // emailText($name_atasan, $name_karyawan, $date, $status){
    //     $this->email->message($emailText);
            
    //     if($this->email->send()){
    //         echo("success");
    //     } else {
    //         echo $this->email->print_debugger(); //show debugger if error
    //     }        
    // }