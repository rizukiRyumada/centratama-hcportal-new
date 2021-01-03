<script>
    var department = ""; var divisi = "";
    var mTable = $('#positionTable').DataTable({
        responsive: true,
        processing: true,
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
        columns: [
            {data: 'divisi'},
            {data: 'department'},
            {data: 'position_name'},
            {data: 'hirarki_org'}
            ,{
                classNmae: "",
                data: 'id',
                render: (data, type) => {
                    if(type === 'display'){
                        // jika aksesnya edit tampilkan tombol delete files
                        return '<div class="btn-group w-100"><a href="javascript:viewPosition('+data+')" class="btn btn-success btn-sm"  type="button"><i class="fas fa-pen"></i></a><a href="javascript:deletePosition('+data+')" class="btn btn-danger btn-sm" ><i class="fas fa-trash"></i></a></div>';
                    }
                    return data;
                }
            }
        ]
    });

    // division filtering
    $('#divisi').change(function(){
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
    $('#departement').change(function(){
        department = $(this).val(); // ubah variabel department
        mTable.ajax.reload(); // reload tabel ajax

    });

    // fungsi untuk menampilkan posisi
    function viewPosition(id_posisi){
        $.ajax({
            url: "<?= base_url('settings/ajax_getDetailPosition'); ?>",
            data: {
                id_posisi: id_posisi
            },
            method: "POST",
            success: function(data){

            },
            error: function(){
                
            }
        });
    }

    // fungsi untuk menghapus posisi
    function deletePosition(id){
        console.log(id);
    }
</script>