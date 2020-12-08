<!-- <script src="<?php // base_url('/assets/js/iframe-resize/iframeResizer.min.js'); ?>"></script> -->
<script>
    // variable parameter untuk dapetin list file
    // PRODUCTION ubah alamat pathnya sesuai server windows
    // var path = '/assets/temp/files/ptk/<?= $this->session->userdata('nik'); ?>';
    var path = 'assets/temp/files/ptk/<?= $this->session->userdata('nik'); ?>';
    var path_url = "<?= base_url('assets/temp/files/ptk/'.$this->session->userdata('nik').'/'); ?>";
    var session_name = 'ptk_files';
    var files = "";
    var flag_upload_new = 1;
</script>
<!-- script attachment, param(path, session_name, files) -->
<?php $this->load->view('_komponen/ptk/script_attachment_ptk'); ?>
<script>
    $(document).ready(function(){
        // ambil job position jika ada divisi dan posisinya
        let divisi = select_divisi.val();
        let department = select_department.val();

        if(divisi != "" && department != ""){
            getPosition(divisi, department); // get position and interviewer data
        }

        // script untuk merefresh files
        // $.ajax({
        //     url: '<?= base_url('upload/ajax_refresh'); ?>',
        //     success: function(data){
        //         let vya = JSON.parse(data);

        //         // updateListFiles(vya.file_counter, vya.session_files); // update list files
        //         table_files.ajax.reload();
        //     }
        // });
    });

/* -------------------------------------------------------------------------- */
/*                                  functions                                 */
/* -------------------------------------------------------------------------- */

    function updateListFiles(files_counter, files_session){
        $('#file_counter').text(files_counter); // set jumlah files
        $('#list_files').empty(); // kosongkan table terlebih dahulu
        $.each(files_session, function(index, value){
            $('#list_files').append('<tr><td>'+value.file_nameOrigin+'</td><td>'+value.size+'KB</td><td>'+value.type+'</td><td>'+value.time+'</td><td><div class="btn-group w-100"><a href="<?= base_url('assets/temp/files/ptk/'.$this->session->userdata('nik').'/'); ?>'+value.file_name+'" class="btn btn-primary" target="_blank"><i class="fa fa-search"></i></a><a href="javascript:deleteFiles('+"'"+value.file_name+"'"+');" class="btn btn-danger"><i class="fa fa-trash"></i></a></div></td></tr>');
        });
    }
</script>

<!-- script modal pesan -->
<?php $this->load->view('_komponen/ptk/script_modalPesan_ptk'); ?>