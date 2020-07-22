<script>
    $('#divisi').change(function(){
        var dipilih = $(this).val(); //ambil value dari yang terpilih

        if(dipilih == ""){
            // mTable.column(1).search(dipilih).order([1, 'asc']).draw(); //kosongkan filter dom departement
        }

        $.ajax({
            url: "<?php echo base_url('job_profile/ajax_getdepartement'); ?>",
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
        })
    });

    $('#departement').change(() => {
        let divisi = $('#divisi').val();
        let departemen = $('#departement').val();

        $.ajax({
            url: "<?= base_url('healthReport/ajaxGetEmployee'); ?>",
            data: {
                divisi: divisi,
                departemen: departemen
            },
            method: "POST",
            success: (data) => {
                console.log(data);
            }
        });
    });

    data = 'oke';
    table = $('#report_healthCheckIn').DataTable({
        responsive: true,
        processing: true,
        language : { processing: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>'},
        pagingType: 'full_numbers',
        // serverSide: true,
        dom: 'Bfrtip',
        lengthMenu: [
            [ 10, 25, 50, 100, -1 ],
            [ '10 Rows', '25 Rows', '50 Rows', '100 Rows', 'All' ]
        ],
        buttons: [
            'pageLength',
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel" aria-hidden="true"></i> Export to EXCEL',
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
            },
            {
                extend: 'csv',
                text: '<i class="fas fa-file-csv" aria-hidden="true"></i> Export to CSV',
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
            url: '<?= base_url('healthReport/ajax_getReportData'); ?>',
            type: 'POST',
            data: function(data) {
                data = data
            }
        },
        columns: [
            {data: 'date'},
            {data: 'emp_name'},
            {data: 'detail_position.departement'},
            {data: 'detail_position.divisi'},
            {
                // classNmae: "",
                data: 'status',
                render: (data, type) => {
                    if(type === 'display'){
                        var status = '';
                        var cssClass = '';

                        switch(data) {
                            case '0':
                                status = 'Sick';
                                cssClass = 'text-danger';
                                break;
                            case '1':
                                status = "Healthy";
                                cssClass = 'text-success';
                                break;
                        }
                        return '<p class="m-0 font-weight-bold text-center '+cssClass+'">'+status+'</p>';
                    }
                    return data;
                }
            },
            {data: 'sickness'},
            {data: 'notes'}
        ]
        // ajax: '<?= base_url('healthReport/ajax_getReportData'); ?>'
    });

    $('#adsa').on('click', () => {
        table.ajax.reload(); // reload table
    });

    // $(document).ready(function() {
    //     $('#example').DataTable( {
    //         "ajax": "data/objects.txt",
    //         "columns": [
    //             { "data": "name" },
    //             { "data": "position" },
    //             { "data": "office" },
    //             { "data": "extn" },
    //             { "data": "start_date" },
    //             { "data": "salary" }
    //         ]
    //     });
    // });

    
</script>