<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* -------------------------------------------------------------------------- */
/*                                LOGIN HELPER                                */
/* -------------------------------------------------------------------------- */

/**
 * is_logged_in
 *
 * @return void
 */
function is_logged_in(){
    $CI =& get_instance(); // get codeigniter instance
    
    //cek apa dia sudah login
    if(!$CI->session->userdata('nik')){
        // buat nampilin pesan perintah login
        $CI->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">
                    Please Login to Continue. </div>');
        $CI->session->set_userdata(array('error' => 1));
        
        // arahkan kembali ke base_url
        redirect('login','refresh');
    } else {
        $id_user = $CI->session->userdata('role_id');
        $url = $CI->uri->segment(1);

        $suburl = $CI->uri->segment(2);

        //TODO ubah survey menu ke user menu
        $queryMenu = $CI->db->get_where('survey_user_menu', ['url' => $url])->row_array(); // cari informasi url yang diakses
        $queryMenuSub = $CI->db->get_where('survey_user_menu_sub', array('url' => $url. '/' .$suburl))->row_array(); // cari informasi sub url yang diakses

        // if(empty($queryMenu)){ // jika level menu utama tidak ada, cari di sub menu, access submenu level berbeda, ada menu yg gaada di list menu
        //     $queryMenu = $CI->db->get_where('user_sub_menu', ['title' => $url])->row_array();
        // }else{
        //     //do nothing
        // }

        // cek akses terhadap menu
        $userAccess = $CI->db->get_where('survey_user_menu_access', ['id_user' => $id_user, 'id_menu' => $queryMenu['id_menu']]);
        if($userAccess->num_rows() < 1){ // jika tidak punya akses terhadap menu
            // show_error($message, $status_code, $heading = 'An Error Was Encountered')
            //show_404; // for notfound
            show_error('Sorry you are not allowed to access this menu.', 403, 'Forbidden');
        }

        //cek di suburl apa dia mengakses fungsi dari controller
        if(!empty($suburl)){
            //cek apakag dia submenu dilihat dari hasil query sub menu dari database
            if(!empty($queryMenuSub)){
                //cek akses terhadap submenu
                $userAccessSub = $CI->db->get_where('survey_user_menu_sub_access', array('id_user' => $id_user, 'id_menu_sub' => $queryMenuSub['id_menu_sub']));
                if($userAccessSub->num_rows() < 1){ // jika tidak punya akses terhadap sub menu
                    // show_error($message, $status_code, $heading = 'An Error Was Encountered')
                    //show_404; // for notfound
                    show_error('Sorry you are not allowed to access this submenu.', 403, 'Forbidden');
                }
            }
        }
    }
}

/**
 * check_access
 *
 * @param  mixed $role_id
 * @param  mixed $menu_id
 * @return void
 */
function check_access($role_id, $menu_id){
    $ci =& get_instance();

    $ci->db->where('role_id', $role_id);
    $ci->db->where('menu_id', $menu_id);
    $result = $ci->db->get('user_access_menu');

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}

/**
 * check_surat_access
 *
 * @param  mixed $role_id
 * @param  mixed $surat_id
 * @return void
 */
function check_surat_access($role_id, $surat_id){
    $CI =& get_instance();

    $CI->db->where('role_surat_id', $role_id);
    $CI->db->where('surat_id', $surat_id);
    $result = $CI->db->get('user_access_surat');

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}

/* -------------------------------------------------------------------------- */
/*                                 MAIN HELPER                                */
/* -------------------------------------------------------------------------- */

/**
 * getBreadcrumb
 *
 * @return void
 */
function getBreadcrumb(){
    $CI = get_instance();

    $url = explode('/', $CI->uri->uri_string());
    $urlStep = $url[0];
    foreach($url as $key => $v){
        // $urlData[$key] = $CI->_general_m->getOnce('*', 'survey_user_menu_sub', array('url' => $urlStep));
        $temp_url_data = $CI->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => $urlStep));

        if(!empty($temp_url_data)){
            $urlData[$key]['judul'] = $CI->_general_m->getOnce('title', 'survey_user_menu_sub', array('url' => $urlStep))['title'];
            $urlData[$key]['url'] = base_url($CI->_general_m->getOnce('url', 'survey_user_menu_sub', array('url' => $urlStep)));

        } else {
            $urlData[$key]['judul'] = ucwords($v);
            $urlData[$key]['url'] = '#';
            // $urlData[$key]['url'] = base_url($v);
        }

        $urlNow = $urlStep;
        if(!empty($url[$key+1])){
            $urlStep = $urlNow. '/' .$url[$key+1];
        }
    }
    return $urlData;
}

/**
 * getDetailUser
 * ambil detail informasi user
 * @return void
 */
function getDetailUser(){
    $CI = get_instance();

    return $CI->_general_m->getOnce('emp_name, position_id', 'employe', array('nik' => $CI->session->userdata('nik')));
}

/**
 * getMenu
 *
 * @return void
 */
function getMenu(){
    $CI =& get_instance();

    $CI->load->model('_general_m');

    // $menu = $CI->_general_m->getJoin2tables('*', 'survey_user_menu', 'survey_user_menu_access', 'survey_user_menu.id_menu = survey_user_menu_access.id_menu', array('id_user' => $CI->session->userdata('role_id')));
    if($CI->session->userdata('role_id') == 2){
        // ambil menu dari admins menu
        $menu = $CI->_general_m->getJoin2tables(
            '*', 
            'survey_user_menu', 
            'survey_user_menu_access', 
            'survey_user_menu.id_menu = survey_user_menu_access.id_menu', 
            'id_user = '.$CI->session->userdata('role_id').
            // this is for special menu
            ' AND survey_user_menu.id_menu != 5 
            AND survey_user_menu.id_menu != 7
            AND survey_user_menu.id_menu != 2'
        );

        // cari menu jenisnya yang admin
        $x = 0; 
        foreach($menu as $k => $v){
            if($CI->_general_m->getRow('survey_user_menu_sub_admins', array('id_menu' => $v['id_menu'])) >= 1){
                $menu_admin[$x] = $v; // masukkan menu ke variable menu admin
                unset($menu[$k]); // hapus menu dari variabel menu
                $x++;
            }
        }
        // cek akses menu admin
        if(!empty($menu_admin)){
            foreach($menu_admin as $k => $v){
                if($CI->_general_m->getRow('survey_user_menu_sub_admins', array('id_menu' => $v['id_menu'], 'nik' => $CI->session->userdata('nik'))) < 1){
                    unset($menu_admin[$k]); // hapus menu admin
                }
            }
            // gabungkan menu
            $menu = array_merge($menu, $menu_admin);
        }

        // ambil submenu
        $x = 0; $sub_menu = array();
        foreach($menu as $v){
            $temp_sub_menu = $CI->_general_m->getJoin2tables('*', 'survey_user_menu_sub', 'survey_user_menu_sub_access', 'survey_user_menu_sub.id_menu_sub = survey_user_menu_sub_access.id_menu_sub', array('id_menu' => $v['id_menu'], 'id_user' => $CI->session->userdata('role_id')));
            foreach($temp_sub_menu as $v){
                $sub_menu[$x] = $v;
                $x++;
            }
        }

        // cari menu jenisnya yang admin
        $x = 0; 
        foreach($sub_menu as $k => $v){
            if($CI->_general_m->getRow('survey_user_menu_sub_admins', array('id_menu_sub' => $v['id_menu_sub'])) >= 1){
                $sub_menu_admin[$x] = $v; // masukkan menu ke variable menu admin
                unset($sub_menu[$k]); // hapus menu dari variabel menu
                $x++;
            }
        }
        // cek akses menu admin
        if(!empty($sub_menu_admin)){
            foreach($sub_menu_admin as $k => $v){
                if($CI->_general_m->getRow('survey_user_menu_sub_admins', array('id_menu_sub' => $v['id_menu_sub'], 'nik' => $CI->session->userdata('nik'))) < 1){
                    unset($sub_menu_admin[$k]); // hapus menu admin
                }
            }
            // gabungkan sub menu
            $sub_menu = array_merge($sub_menu, $sub_menu_admin);
        }
    } else {
        // ambil list menu tanpa setting
        $menu = $CI->_general_m->getJoin2tables(
            '*', 
            'survey_user_menu', 
            'survey_user_menu_access', 
            'survey_user_menu.id_menu = survey_user_menu_access.id_menu', 
            'id_user = '.$CI->session->userdata('role_id').
            // this is for special menu
            ' AND survey_user_menu.id_menu != 5 
            AND survey_user_menu.id_menu != 7
            AND survey_user_menu.id_menu != 2'
        );

        // ambil submenu
        $x = 0; $sub_menu = array();
        foreach($menu as $v){
            $temp_sub_menu = $CI->_general_m->getJoin2tables('*', 'survey_user_menu_sub', 'survey_user_menu_sub_access', 'survey_user_menu_sub.id_menu_sub = survey_user_menu_sub_access.id_menu_sub', array('id_menu' => $v['id_menu'], 'id_user' => $CI->session->userdata('role_id')));
            foreach($temp_sub_menu as $v){
                $sub_menu[$x] = $v;
                $x++;
            }
        }
    }
    
    // [[{"id_menu_sub":"6-00","title":"Your Survey","url":"survey","is_active":"1","id_menu":"6","id_user":"2"},{"id_menu_sub":"6-10","title":"Service Excellence","url":"survey\/excellence","is_active":"1","id_menu":"6","id_user":"2"},{"id_menu_sub":"6-20","title":"Service Engagement","url":"survey\/engagement","is_active":"1","id_menu":"6","id_user":"2"},{"id_menu_sub":"6-30","title":"Your 360 Review","url":"survey\/360index","is_active":"1","id_menu":"6","id_user":"2"}]]

    // Array ( [0] => Array ( [id_menu] => 6 [title] => Survey [url] => survey [icon] => fas fa-file-alt [is_active] => 1 [id_user] => 2 ) ) 
    $data = array(
        'menu' => $menu,
        'submenu' => $sub_menu
    );
    return $data;
}

?>