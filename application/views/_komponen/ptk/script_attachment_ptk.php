<script>
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
                data.session_name = session_name;
                data.files = files;
            },
            beforeSend: () => {
                // $('.overlay').removeClass('d-none'); // hapus class d-none
                // toastr["warning"]("This will take a few moments.", "Retrieving data...");
                $('.overlay').fadeIn(); // hapus overlay chart
                ajax_start_time = new Date().getTime(); // ajax stopwatch
            },
            complete: (data, jqXHR) => { // run function when ajax complete
                table.columns.adjust();
                let vya = data.responseJSON;

                $('#file_counter').text(vya.file_counter); // set jumlah files

                
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

/* -------------------------------------------------------------------------- */
/*                                  functions                                 */
/* -------------------------------------------------------------------------- */

    // this function used to remove files using ajax
    function deleteFiles(filename){
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
                            // nothing
                        } else {
                            files = JSON.stringify(vya.files_new);
                            updateFilesToDatabase(); // update ke database
                        }
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
</script>