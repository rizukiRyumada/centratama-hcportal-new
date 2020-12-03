<!-- <script src="<?php // base_url('/assets/js/iframe-resize/iframeResizer.min.js'); ?>"></script> -->
<script>
    var path = './assets/temp/files/ptk/';
    // datatables buat list file attachment
    var table_files = $('#files_table').DataTable({
        responsive: true,
        // processing: true,
        language : { 
            processing: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>',
            zeroRecords: '<p class="m-0 text-danger font-weight-bold">No Data.</p>'
        },
        pagingType: 'full_numbers',
        autoWidth: false,
        // serverSide: true,
        // dom: 'Bfrtip',
        deferRender: true,
        // custom length menu
        lengthMenu: [
            [5, 10, 25, 50, 100, -1 ],
            ['5 Rows', '10 Rows', '25 Rows', '50 Rows', '100 Rows', 'All' ]
        ],
        order: [[0, 'asc']],
        // buttons
        buttons: [
            'pageLength', // place custom length menu when add buttons
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel" aria-hidden="true"></i> Export to Excel',
                title: '',
                filename: 'Health Report-<?= date("dmo-Hi"); ?>',
                exportOptions: {
                    modifier: {
                        //Datatables Core
                        order: 'index',
                        page: 'all',
                        search: 'none'
                    }
                    // ,columns: [0,1,2,3,4]
                }
            }
        ],
        ajax: {
            url: '<?= base_url('ptk/ajax_refreshListFiles'); ?>',
            type: 'POST',
            data: function(data) {
                // kirim data ke server
                // data.status = status
            },
            beforeSend: () => {
                // $('.overlay').removeClass('d-none'); // hapus class d-none
                // toastr["warning"]("This will take a few moments.", "Retrieving data...");
                $('.overlay').fadeIn(); // hapus overlay chart
                ajax_start_time = new Date().getTime(); // ajax stopwatch
            },
            complete: (data, jqXHR) => { // run function when ajax complete
                table.columns.adjust();
                // console.log(data);
                let vya = data.responseJSON;
                // cek apa vya kosong
                // if(vya == undefined || vya == "" || vya == null){
                //     $('#counter_files').text("0"); // set jumlah files
                // } else {
                //     $('#counter_files').text(vya.counter_files); // set jumlah files
                // }

                $('#counter_files').text(vya.counter_files); // set jumlah files

                
                // ajax data counter
                var ajax_request_time = new Date().getTime() - ajax_start_time;
                // toastr["success"]("data retrieved in " + ajax_request_time + "ms", "Completed");
                
                $('.overlay').fadeOut(); // hapus overlay chart
            }
        },
        columns: [
            {data: 'file_nameOrigin'},
            {data: 'size'},
            {data: 'type'},
            {data: 'time'},
            {
                classNmae: "",
                data: 'file_name',
                render: (data, type) => {
                    if(type === 'display'){
                        return '<div class="btn-group w-100"><a href="<?= base_url('assets/temp/files/ptk/'.$this->session->userdata('nik').'/'); ?>'+data+'" class="btn btn-primary" target="_blank"><i class="fa fa-search"></i></a><a href="javascript:deleteFiles('+"'"+data+"'"+');" class="btn btn-danger"><i class="fa fa-trash"></i></a></div>';
                    }
                    return data;
                }
            }
        ]
    });
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

        //         // updateListFiles(vya.counter_files, vya.session_files); // update list files
        //         table_files.ajax.reload();
        //     }
        // });

        // script untuk uploader files
        $("#fileuploader").uploadFile({
            url:"<?= base_url('upload/ajax_upload'); ?>",
            allowedTypes: "pdf,doc,docx,ppt,pptx,xps,odt,xls,xlsx,wps,jpg,jpeg,gif,png",
            dragdropWidth: "100%",
            fileName:"myfile",
            formData: { 
                path: path, 
            },
            multiple: true,
            showStatusAfterSuccess: false,
            showProgress: true,
            sequentialCount:1,
            onSubmit:function(files)
            {
                //files : List of files to be uploaded
                //return flase;   to stop upload

            },
            onSuccess:function(files,data,xhr,pd)
            {
                //files: list of files
                //data: response from server
                //xhr : jquer xhr object
                // let vya = JSON.parse(data);
            },
            afterUploadAll:function(obj)
            {
                //You can get data of the plugin using obj
                table_files.ajax.reload(); // update list files
                toastr["success"]("Files was successfully uploaded.", "Upload Success");
            },
            onError: function(files,status,errMsg,pd)
            {
                //files: list of files
                //status: error status
                //errMsg: error message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops, Something went wrong!',
                    text: errMsg,
                })
            }
        });
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

    // this function used to remove files using ajax
    function deleteFiles(file){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true
            // confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('upload/ajax_delete'); ?>",
                    data: {
                        path: path,
                        filename: file
                    },
                    method: "POST",
                    beforeSend: function(){
                        Swal.fire({
                            icon: 'info',
                            title: 'Please Wait',
                            html: '<p>'+"Please don't close this tab and the browser, your file is being removed."+'<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false
                        });
                    },
                    success: function(data){
                        let vya = JSON.parse(data);
                        // updateListFiles(vya.counter_files, vya.session_files); // update list files
                        table_files.ajax.reload(); // update list files


                        // notifikasi file sudah dihapus
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    }
                })
            }
        });
    }

    function updateListFiles(files_counter, files_session){
        $('#counter_files').text(files_counter); // set jumlah files
        $('#list_files').empty(); // kosongkan table terlebih dahulu
        $.each(files_session, function(index, value){
            $('#list_files').append('<tr><td>'+value.file_nameOrigin+'</td><td>'+value.size+'KB</td><td>'+value.type+'</td><td>'+value.time+'</td><td><div class="btn-group w-100"><a href="<?= base_url('assets/temp/files/ptk/'.$this->session->userdata('nik').'/'); ?>'+value.file_name+'" class="btn btn-primary" target="_blank"><i class="fa fa-search"></i></a><a href="javascript:deleteFiles('+"'"+value.file_name+"'"+');" class="btn btn-danger"><i class="fa fa-trash"></i></a></div></td></tr>');
        });
    }
</script>

<!-- script modal pesan -->
<?php $this->load->view('_komponen/ptk/script_modalPesan_ptk'); ?>