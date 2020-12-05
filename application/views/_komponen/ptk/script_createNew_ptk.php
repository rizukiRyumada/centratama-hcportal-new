<!-- <script src="<?php // base_url('/assets/js/iframe-resize/iframeResizer.min.js'); ?>"></script> -->
<script>
    // variable parameter untuk dapetin list file
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
    /*                           Customized Form Validation                       */
    /* -------------------------------------------------------------------------- */
    // $('.submitPTK').on('click', function() {
    //     // ambil data pada tombol save atau submit
    //     $('input[name="action"]').val($(this).data('id'));
    //     let action = $(this).data('id');
        
    //     let validator = submit_validator(); // submit validator taken from .../application/views/_komponen/ptk/script_submitValidator_ptk.php
    //     let counter_validate = validator[0];
    //     let msg_validate = validator[1];
    //     // cek apa ada form error
    //     if(counter_validate != 0){
    //         // List empty form popup
    //         // $(document).Toasts('create', {
    //         //     class: 'bg-danger', 
    //         //     title: 'List of Empty Form',
    //         //     subtitle: 'Lets fill it',
    //         //     position: 'bottomLeft',
    //         //     body: msg_validate + "Please look at red mark or border."
    //         // });
    //         // tampilkan pesan error dalam swal
    //         Swal.fire({
    //             title: 'Form Validation Error',
    //             html: "Please fill the required input form.",
    //             icon: 'warning',
    //             showCancelButton: false,
    //             // confirmButtonColor: '#99FF99',
    //             // cancelButtonColor: '#d33',
    //             confirmButtonText: 'Ok, I wiil check it.',
    //             allowOutsideClick: false,
    //             allowEscapeKey: false,
    //             allowEnterKey: false
    //         }).then((result) => {
    //             if (result.value) {
    //                 var el = $('select#entityInput');
    //                 var elOffset = el.offset().top;
    //                 var elHeight = el.height();
    //                 var windowHeight = $(window).height();
    //                 var offset;

    //                 if (elHeight < windowHeight) {
    //                     offset = elOffset - ((windowHeight / 2) - (elHeight / 2));
    //                 }
    //                 else {
    //                     offset = elOffset;
    //                 }
    //                 $([document.documentElement, document.body]).animate({ //for animation
    //                     scrollTop: offset
    //                 }, 750);
    //             }
    //         });

    //         // batalkan pengiriman form
    //         return false;
    //     } else {
    //         // kirimkan form

    //         <?php if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1 || $position_my['hirarki_org'] == "N-1"): ?>
    //             let text_title = "";
    //             if(action == "save"){
    //                 text_title = 'Saving the form...';
    //             } else {
    //                 text_title = 'Submitting the form...';
    //             }
    //         <?php else: ?>
    //             let text_title = 'Saving the form...';
    //         <?php endif; ?>

    //         // show submitting swal notification
    //         Swal.fire({
    //             icon: 'info',
    //             title: text_title,
    //             html: '<p>Form validation completed, Please Wait.<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
    //             showConfirmButton: false,
    //             // allowOutsideClick: false,
    //             allowEscapeKey: false,
    //             allowEnterKey: false
    //         });
            
    //         $('#ptkForm').submit(); // submit form if validation success
    //     }
    // });

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