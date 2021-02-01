<script>

    // jika flag_viewer ptk, maka aktifkan baris kode ini
    // jadi ada masalah tabrakan antara files_table dengan fungsi ajax get data
    // untuk memperbaikinya dengan bantuan php script datatables di clone di kondisi viewer dan untuk create new form
    var table_files = "";
    <?php if(!empty($flag_viewer)): ?>
        $.when(ajax_getData()).done(function(a1){
            // the code here will be executed when all four ajax requests resolve.
    <?php endif; ?>
            table_files = $('#files_table').DataTable({
                responsive: true,
                // processing: true,
                language : { 
                    processing: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                    zeroRecords: '<p class="m-0 text-danger font-weight-bold">No Data.</p>'
                },
                pagingType: 'full_numbers',
                autoWidth: false,
                dataSrc: "data",
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
                        filename: 'Health Report-<?= date("dmY-Hi"); ?>',
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
                        data.session_name = session_name;
                        data.files = files;
                    },
                    beforeSend: () => {
                        // $('.overlay').removeClass('d-none'); // hapus class d-none
                        // toastr["warning"]("This will take a few moments.", "Retrieving data...");
                        $('.overlay-tableFiles').fadeIn(); // hapus overlay chart
                        ajax_start_time = new Date().getTime(); // ajax stopwatch
                    },
                    complete: (data, jqXHR) => { // run function when ajax complete
                        table.columns.adjust();
                        $('#file_counter').text(data.responseJSON.file_counter); // set jumlah files
                        // ajax data counter
                        var ajax_request_time = new Date().getTime() - ajax_start_time;
                        // toastr["success"]("data retrieved in " + ajax_request_time + "ms", "Completed");
                        
                        $('.overlay-table-Files').fadeOut(); // hapus overlay chart
                    }
                },
                columns: [
                    {data: 'file_nameOrigin'},
                    {data: 'size'},
                    {data: 'type'},
                    {data: 'time'}
                    ,{
                        classNmae: "",
                        data: 'file_name',
                        render: (data, type) => {
                            if(type === 'display'){
                                // jika aksesnya edit tampilkan tombol delete files
                                return '<div class="btn-group w-100"><a href="'+path_url+data+'" class="btn btn-primary" target="_blank"><i class="fa fa-search"></i></a><?php if($is_edit == 1): ?><a href="javascript:deleteFiles('+"'"+data+"'"+');" class="btn btn-danger"><i class="fa fa-trash"></i></a><?php endif; ?></div>';
                            }
                            return data;
                        }
                    }
                ]
            });

            // script ckeditor
            <?php $this->load->view('_komponen/ptk/script_ckeditor_ptk'); ?>

    // jika flag_viewer ptk, maka aktifkan baris kode ini
    <?php if(!empty($flag_viewer)): ?>
        });
    <?php endif; ?>

    // jika modenya edit, pasangkan skrip file uploader dan delete files
    <?php if($is_edit == 1): ?>
        // script untuk uploader files
        $("#fileuploader").uploadFile({
            url:"<?= base_url('upload/ajax_upload'); ?>",
            allowedTypes: "pdf,doc,docx,ppt,pptx,xps,odt,xls,xlsx,wps,jpg,jpeg,gif,png",
            dragdropWidth: "100%",
            fileName:"myfile",
            // formData: { 
            //     path: path,
            //     session_name: session_name,
            //     files: files,
            //     flag_upload_new: flag_upload_new
            // },
            dynamicFormData: function()
            {
                var data ={ 
                    path: path,
                    session_name: session_name,
                    files: files,
                    flag_upload_new: flag_upload_new
                }
                return data;
            },
            multiple: true,
            showStatusAfterSuccess: false,
            showProgress: true,
            sequentialCount:1,
            onLoad:function(obj)
            {
                    // console.log(files);
            },
            onSubmit:function(files_jqxhr)
            {
                //files_jqxhr : List of files to be uploaded
                //return flase;   to stop upload
                
            },
            onSuccess:function(files_jqxhr,data,xhr,pd)
            {
                //files_jqxhr: list of files
                //data: response from server
                //xhr : jquer xhr object
                let vya = JSON.parse(data);
                // ganti data di table dengan data dari variabel dan update ke database jika bukan dari session files
                if(session_name == undefined || session_name == null || session_name == ""){
                    files = JSON.stringify(vya.files_new);
                    updateFilesToDatabase(files); // update ke database, fungsi ini ada di script_viewer_ptk
                } else {
                    // nothing
                }
            },
            afterUploadAll:function(obj)
            {
                //You can get data of the plugin using obj
                <?php if(empty($flag_viewer)): ?>
                    table_files.ajax.reload(); // update list files
                <?php endif; ?>
                toastr["success"]("Files was successfully uploaded.", "Upload Success");
            },
            onError: function(files_jqxhr,status,errMsg,pd)
            {
                //files_jqxhr: list of files
                //status: error status
                //errMsg: error message
                Swal.fire({
                    icon: 'error',
                    title: 'Oops, Something went wrong!',
                    text: errMsg,
                });
            }
        });

        /* -------------------------------------------------------------------------- */
        /*                                  functions                                 */
        /* -------------------------------------------------------------------------- */

        // this function used to remove files using ajax
        function deleteFiles(filename){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                focusConfirm: false,
                focusCancel: true,
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "<?= base_url('upload/ajax_delete'); ?>",
                        data: {
                            path: path,
                            filename: filename,
                            files: files,
                            session_name: session_name,
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
                            // updateListFiles(vya.file_counter, vya.session_files); // update list files
                            // ganti data di table dengan data dari variabel dan update ke database jika buakan dari session files
                            if(session_name == undefined || session_name == null || session_name == ""){
                                files = JSON.stringify(vya.files_new)
                                updateFilesToDatabase(JSON.stringify(vya.files_new)); // update ke database
                            } else {
                                table_files.ajax.reload(); // update list files
                            }

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
    <?php endif; ?>
</script>