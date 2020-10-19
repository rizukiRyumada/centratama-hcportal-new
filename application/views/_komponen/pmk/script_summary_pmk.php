<script>
    // prepare variables
    var switchData = 0;
    var divisi = "<?= $id_div; ?>";
    var filter_status = "";
    var filter_daterange = $('#daterange').val();

    var table = $('#table_indexSummary').DataTable({
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
        order: [[0, 'desc']],
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
            url: '<?= base_url('pmk/ajax_getSummaryList'); ?>',
            type: 'POST',
            data: function(data) {
                // kirim data ke server
                data.switchData = switchData;
                data.divisi = divisi;
                data.filter_status = filter_status;
                data.filter_daterange = filter_daterange;
            },
            beforeSend: () => {
                // $('.overlay').removeClass('d-none'); // hapus class d-none
                toastr["warning"]("This will take a few moments.", "Retrieving data...");
                $('.overlay').fadeIn(); // hapus overlay chart
                ajax_start_time = new Date().getTime(); // ajax stopwatch
                $(".overlay").fadeIn();
            },
            complete: (data, jqXHR) => { // run function when ajax complete
                table.columns.adjust();
                
                // ajax data counter
                var ajax_request_time = new Date().getTime() - ajax_start_time;
                toastr["success"]("data retrieved in " + ajax_request_time + "ms", "Completed");
                $('.overlay').fadeOut(); // hapus overlay chart
            }
        },
        columns: [
            {data: 'tahun'},
            {data: 'bulan'},
            {
                className: "",
                data: 'status_now',
                render: (data, type) => {
                    if(type === 'display'){
                        let vya = JSON.parse(data);
                        return '<a href="javascript:showTimeline('+"'"+vya.trigger+"'"+')" ><span class="w-100 badge badge-'+vya.status.css_color+'">'+vya.status.name+'</span></a>';
                    }
                    return data;
                }
            },
            {data: 'created'},
            {data: 'modified'},
            {
                className: "",
                data: 'id_summary',
                render: (data, type) => {
                    if(type === 'display'){
                        // let vya = JSON.parse(data);
                        return '<div class="container h-100 m-0 px-auto"> <div class="row justify-content-center align-self-center w-100 m-0"><a class="btn btn-primary w-100" href="<?= base_url('pmk/summary_process'); ?>?id='+data+'"><i class="fa fa-search mx-auto"></i></a></div></div>';
                    }
                    return data;
                }
            }
        ]
    });

    // switch data button
    $('.switch-data').on('click', function(){
        if($(this).hasClass('active') == false){
            let vya = $(this).data('switch');
            switchData = vya; // ganti switchData
            // tampilkan sembunyikan filter saat menu history atau my task
            if(vya == 0){
                $('#filterTools').slideUp();
                $('#buttonResetFilter').slideUp();
                $('#filter_divider').slideUp();
            } else {
                $('#filterTools').slideDown();
                $('#buttonResetFilter').slideDown();
                $('#filter_divider').slideDown();
            }

            table.ajax.reload();
        }
    });

    // filter status
    // filter status
    $("#status").on('change', function() {
        filter_status = $(this).val(); // ubah variabel departemen
        table.ajax.reload(); // reload table
    });
    // filter daterange
    $("#daterange").on('change', function(){
        filter_daterange = $(this).val(); // ubah variabel daterange
        table.ajax.reload(); // reload table
    });

    /* ---------------------------- daterange script ---------------------------- */
    $('#daterange').daterangepicker({
        "showDropdowns": true,
        "minYear": 1989,
        "maxYear": 2580,
        "showWeekNumbers": true,
        "showISOWeekNumbers": true,
        "autoApply": true,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "alwaysShowCalendars": true,
        "startDate": "<?= date('m/01/o', strtotime("-2 month", time())) ?>",
        "endDate": "<?= date('m/t/o', strtotime("+2 month", time())); ?>",
        "minDate": "YYYY-MM-DD",
        "maxDate": "YYYY-MM-DD",
        "drops": "auto",
        "applyButtonClasses": "btn-success"
    }, function(start, end, label) {
        console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });
</script>