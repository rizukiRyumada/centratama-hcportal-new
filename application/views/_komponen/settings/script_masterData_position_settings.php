<script>
    // NOW
    // TODO kasih peringatan sebelum save perubahan, sama challenge
    // TODO notifikasi di tiap action
    // TODO tampilkan notifikasi
    const path = 'assets/temp/files/masterData/<?= $this->session->userdata('nik'); ?>';
    const path_url = "<?= base_url('assets/temp/files/masterData/' . $this->session->userdata('nik') . '/'); ?>";
    const session_name = 'ptk_files';
    let files = "";
    let flag_upload_new = 1;
    var department = "";
    var divisi = "";

    var mTable = $('#positionTable').DataTable({
        responsive: true,
        processing: true,
        language: {
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
            [5, 10, 25, 50, 100, -1],
            ['5 Rows', '10 Rows', '25 Rows', '50 Rows', '100 Rows', 'All']
        ],
        order: [
            [0, 'asc']
        ],
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
            url: '<?= base_url('settings/ajax_getDataPosition'); ?>',
            type: 'POST',
            data: function(data) {
                // kirim data ke server
                data.divisi = divisi;
                data.department = department;
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
        columns: [{
                data: 'divisi'
            },
            {
                data: 'department'
            },
            {
                data: 'position_name'
            },
            {
                data: 'hirarki_org'
            }, {
                classNmae: "",
                data: 'id',
                render: (data, type) => {
                    if (type === 'display') {
                        // jika aksesnya edit tampilkan tombol delete files
                        return '<div class="btn-group w-100"><a href="javascript:viewPosition(' + data + ')" class="btn btn-success btn-sm"  type="button"><i class="fas fa-pen"></i></a><a href="javascript:deletePosition(' + data + ')" class="btn btn-danger btn-sm" ><i class="fas fa-trash"></i></a></div>';
                    }
                    return data;
                }
            }
        ]
    });

    // division filtering
    $('#divisi').change(function() {
        var dipilih = $(this).val(); //ambil value dari yang terpilih
        divisi = dipilih; // ubah variabel divisi
        department = "";
        mTable.ajax.reload(); // reload tabel ajax

        $.ajax({
            url: "<?php echo base_url('settings/ajax_getDepartment'); ?>",
            data: {
                divisi: dipilih //kirim ke server php
            },
            method: "POST",
            success: function(data) { //jadi nanti dia balikin datanya dengan variable data
                $('#departement').empty().append('<option value="">All</option>'); //kosongkan selection value dan tambahkan satu selection option

                $.each(JSON.parse(data), function(i, v) {
                    $('#departement').append('<option value="dept-' + v.id + '">' + v.nama_departemen + '</option>'); //tambahkan 1 per 1 option yang didapatkan
                });
            }
        });
    });

    // department filtering
    $('#departement').change(function() {
        department = $(this).val(); // ubah variabel department
        mTable.ajax.reload(); // reload tabel ajax

    });

    $('#dataHistory').select2({
        theme: 'bootstrap4',
    });

    // fileuploader
    $("#fileuploader").uploadFile({
        url: "<?= base_url('upload/ajax_upload'); ?>",
        allowedTypes: "csv",
        autoSubmit: false,
        dragDrop: false,
        maxFileCount: 1,
        // dragdropWidth: "100%",
        statusBarWidth: "100%",
        fileName: "myfile",
        // formData: { 
        //     path: path,
        //     session_name: session_name,
        //     files: files,
        //     flag_upload_new: flag_upload_new
        // },
        dynamicFormData: function() {
            var data = {
                path: path,
                session_name: session_name,
                files: files,
                flag_upload_new: flag_upload_new
            }
            return data;
        },
        multiple: false,
        showStatusAfterSuccess: true,
        showProgress: true,
        sequentialCount: 1,
        onLoad: function(obj) {
            // console.log(files);
        },
        onSubmit: function(files_jqxhr) {
            //files_jqxhr : List of files to be uploaded
            //return flase;   to stop upload

        },
        onSuccess: function(files_jqxhr, data, xhr, pd) {
            //files_jqxhr: list of files
            //data: response from server
            //xhr : jquer xhr object
            let vya = JSON.parse(data);
            // ganti data di table dengan data dari variabel dan update ke database jika bukan dari session files
            if (session_name == undefined || session_name == null || session_name == "") {
                files = JSON.stringify(vya.files_new);
                updateFilesToDatabase(files); // update ke database, fungsi ini ada di script_viewer_ptk
            } else {
                // nothing
            }
        },
        afterUploadAll: function(obj) {
            //You can get data of the plugin using obj
            toastr["success"]("Files was successfully uploaded.", "Upload Success");
        },
        onError: function(files_jqxhr, status, errMsg, pd) {
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

    // fungsi untuk menampilkan posisi
    function viewPosition(id_posisi) {
        $.ajax({
            url: "<?= base_url('settings/ajax_getDetailPosition'); ?>",
            data: {
                id_posisi: id_posisi
            },
            method: "POST",
            success: function(data) {

            },
            error: function() {

            }
        });
    }

    // fungsi untuk menghapus posisi
    function deletePosition(id) {
        console.log(id);
    }
</script>