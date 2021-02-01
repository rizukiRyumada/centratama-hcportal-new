<script>
    // prepare variables
    var switchData = 0;
    var summary_divisi = "<?= $chooseDivisi; ?>";
    var filter_summary_status = "";
    var filter_summary_daterange = $('#daterange_summary').val();

    var table_summary = $('#table_indexSummary').DataTable({
        responsive: true,
        autoWidth: false,
        // processing: true,
        language : { 
            processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div><p class="m-0">Retrieving Data...</p>',
            zeroRecords: '<p class="m-0 text-danger font-weight-bold">No Data.</p>'
        },
        pagingType: 'full_numbers',
        // serverSide: true,
        // dom: 'Bfrtip',
        deferRender: true,
        // custom length menu
        lengthMenu: [
            [5, 10, 25, 50, 100, -1 ],
            ['5 Rows', '10 Rows', '25 Rows', '50 Rows', '100 Rows', 'All' ]
        ],
        order: [[1, 'desc']],
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
            url: '<?= base_url('pmk/ajax_getSummaryList'); ?>',
            type: 'POST',
            data: function(data) {
                // kirim data ke server
                data.switchData = switchData;
                data.divisi = summary_divisi;
                data.filter_status = filter_summary_status;
                data.filter_daterange = filter_summary_daterange;
            },
            beforeSend: () => {
                // $('.overlay').removeClass('d-none'); // hapus class d-none
                // toastr["warning"]("This will take a few moments.", "Retrieving data...");
                $('.overlay').fadeIn(); // hapus overlay chart
                ajax_start_time = new Date().getTime(); // ajax stopwatch
                $(".overlay").fadeIn();
            },
            complete: (data, jqXHR) => { // run function when ajax complete
                table_summary.columns.adjust();
                
                // ajax data counter
                var ajax_request_time = new Date().getTime() - ajax_start_time;
                // toastr["success"]("data retrieved in " + ajax_request_time + "ms", "Completed");
                $('.overlay').fadeOut(); // hapus overlay chart
            }
        },
        columns: [
            {data: 'divisi_name'},
            {data: 'date'},
            {data: 'employee_total'},
            {
                className: "",
                data: 'status_now',
                render: (data, type) => {
                    if(type === 'display'){
                        let vya = JSON.parse(data);
                        return '<a href="javascript:showTimeline('+"'"+vya.trigger+"'"+', 2)" ><span class="w-100 badge badge-'+vya.status.css_color+'">'+vya.status.name+'</span></a>';
                    }
                    return data;
                }
            },
            // {data: 'created'},
            // {data: 'modified'},
            {
                className: "",
                data: 'id_summary',
                render: (data, type) => {
                    if(type === 'display'){
                        // let vya = JSON.parse(data);
                        return '<div class="container h-100 m-0 px-auto"> <div class="row justify-content-center align-self-center w-100 m-0"><a class="btn btn-primary w-100" onclick="javascript:summaryLoader()" href="<?= base_url('pmk/summary_process'); ?>?id='+data+'"><i class="fa fa-search mx-auto"></i></a></div></div>';
                    }
                    return data;
                }
            }
        ]
    });

    // switch data button
    // $('.switch-data').on('click', function(){
    //     if($(this).hasClass('active') == false){
    //         switchData = vya; // ganti switchData
    //         // tampilkan sembunyikan filter saat menu history atau my task
    //         if(vya == 0){
    //             $('#filterTools').slideUp();
    //             $('#buttonResetFilter').slideUp();
    //             $('#filter_divider').slideUp();
    //         } else {
    //             $('#filterTools').slideDown();
    //             $('#buttonResetFilter').slideDown();
    //             $('#filter_divider').slideDown();
    //         }

    //         table_summary.ajax.reload();
    //     }
    // });
    $('.switch-data').on('click', function(){
        let vya = $(this).data('choosewhat');
        if($(this).hasClass('active') == false){
            switchData = vya; // ganti switchData
            // tampilkan sembunyikan filter saat menu history atau my task
            if(vya == 0){
                $('#summary_switchData2').removeClass('active');
                $('#summary_switchData1').addClass('active');

                $('#summaryDateChooser').fadeOut(); // summary date chooser
                $('#summary_status').fadeOut(); // filter summary status
                <?php if($position_my['id'] != "1" && $position_my['id'] != "196" && $this->session->userdata('role_id') != 1 && $userApp_admin != 1): ?>
                    $('#summaryButton_resetFilter').slideUp();
                <?php endif; ?>
            } else {
                $('#summary_switchData1').removeClass('active');
                $('#summary_switchData2').addClass('active');

                $('#summaryDateChooser').fadeIn(); // summary date chooser
                $('#summary_status').fadeIn(); // filter summary status
                <?php if($position_my['id'] != "1" && $position_my['id'] != "196" && $this->session->userdata('role_id') != 1 && $userApp_admin != 1): ?>
                    $('#summaryButton_resetFilter').slideDown();
                <?php endif; ?>
            }

            table_summary.ajax.reload();
        }
    });

    // filter divisi 
    $('#divisiSummary').change(function(){
        summary_divisi = $(this).val(); // ubah variable divisi
        // get department from the server
        getDepartemen(summary_divisi, 'departemenSummary');
        if(summary_divisi == ""){
            summary_departemen = "";
        }
        table_summary.ajax.reload(); // reload table
    });
    // filter status
    $("#summary_status").on('change', function() {
        filter_summary_status = $(this).val(); // ubah variabel departemen
        table_summary.ajax.reload(); // reload table_summary
    });
    // filter daterange
    $("#daterange_summary").on('change', function(){
        filter_summary_daterange = $(this).val(); // ubah variabel daterange
        table_summary.ajax.reload(); // reload table_summary
    });
    // button for reset filter
    $('#summaryButton_resetFilter').on('click', function(){
        // reset divisi filter
        $('#divisiSummary').prop('selectedIndex',0);
        summary_divisi = "";
        // reset status filter
        $('#status').prop('selectedIndex',0);
        filter_summary_status = "";
        // reset daterange filter
        filter_summary_daterange = "<?= date('m/01/Y', strtotime("-2 month", time())) ?> - <?= date('m/t/Y', strtotime("+2 month", time())); ?>";
        $('#daterange_summary').val('<?= date('m/01/Y', strtotime("-2 month", time())) ?> - <?= date('m/t/Y', strtotime("+2 month", time())); ?>');
        table_summary.ajax.reload(); // reload table
    });

    /* ---------------------------- daterange script ---------------------------- */
    // moved to script_index_pmk.php
</script>