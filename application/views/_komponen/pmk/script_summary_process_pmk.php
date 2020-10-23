<script>
    var id_summary = "<?= $id_summary; ?>";

    var table = $('#table_summaryProcess').DataTable({
        // responsive: true,
        scrollX:        true,
        scrollCollapse: true,
        // autoWidth: false,
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
        order: [[0, 'asc']],
        fixedColumns:   {
            leftColumns: 2,
            rightColumns: 1
        },
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
            url: '<?= base_url('pmk/ajax_getSummaryListProcess'); ?>',
            type: 'POST',
            data: function(data) {
                // kirim data ke server
                data.id_summary = id_summary;
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

                $('#division').text(data.responseJSON.summary.divisi_name);
                $('#created').text(data.responseJSON.summary.created);
                $('#modified').text(data.responseJSON.summary.modified);
                $('#bulan').text(data.responseJSON.summary.bulan);
                $('#tahun').text(data.responseJSON.summary.tahun);
                
                let vya = JSON.parse(data.responseJSON.summary.status_now);
                $('#status').append('<a href="javascript:showTimeline('+"'"+vya.trigger+"'"+')" ><span class="w-100 badge badge-'+vya.status.css_color+'">'+vya.status.name+'</span></a>');
                
                // ajax data counter
                var ajax_request_time = new Date().getTime() - ajax_start_time;
                toastr["success"]("data retrieved in " + ajax_request_time + "ms", "Completed");
                $('.overlay').fadeOut(); // hapus overlay chart
            }
        },
        columns: [
            {data: 'nik'},
            {data: 'emp_name'},
            {data: 'date_birth'},
            {data: 'date_join'},
            {data: 'emp_stats'},
            {data: 'eoc_probation'},
            {data: 'contract'},
            {data: 'yoc_probation'},
            {data: 'position'},
            {data: 'department'},
            {data: 'divisi'},
            {data: 'entity'},
            {
                className: "",
                data: 'pa1',
                render: (data, type) => {
                    if(type === 'display'){
                        let vya = JSON.parse(data);
                        $('#pa1_score').text(vya.pa_name+" "+vya.pa_data.tahun);
                        return vya.pa_data.score;
                    }
                    return data;
                }
            },
            {
                className: "",
                data: 'pa1',
                render: (data, type) => {
                    if(type === 'display'){
                        let vya = JSON.parse(data);
                        $('#pa1_rating').text(vya.pa_name+" "+vya.pa_data.tahun);
                        return vya.pa_data.rating;
                    }
                    return data;
                }
            },
            {
                className: "",
                data: 'pa2',
                render: (data, type) => {
                    if(type === 'display'){
                        let vya = JSON.parse(data);
                        $('#pa2_score').text(vya.pa_name+" "+vya.pa_data.tahun);
                        return vya.pa_data.score;
                    }
                    return data;
                }
            },
            {
                className: "",
                data: 'pa2',
                render: (data, type) => {
                    if(type === 'display'){
                        let vya = JSON.parse(data);
                        $('#pa2_rating').text(vya.pa_name+" "+vya.pa_data.tahun);
                        return vya.pa_data.rating;
                    }
                    return data;
                }
            },
            {
                className: "",
                data: 'pa3',
                render: (data, type) => {
                    if(type === 'display'){
                        let vya = JSON.parse(data);
                        $('#pa3_score').text(vya.pa_name+" "+vya.pa_data.tahun);
                        return vya.pa_data.score;
                    }
                    return data;
                }
            },
            {
                className: "",
                data: 'pa3',
                render: (data, type) => {
                    if(type === 'display'){
                        let vya = JSON.parse(data);
                        $('#pa3_rating').text(vya.pa_name+" "+vya.pa_data.tahun);
                        return vya.pa_data.rating;
                    }
                    return data;
                }
            },
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
            {
                className: "",
                data: 'action',
                render: (data, type) => {
                    if(type === 'display'){
                        // let vya = JSON.parse(data);
                        return '<input class="form-control" type="text" name="sadsawwd" id="" style="width: 200px;">';
                    }
                    return data;
                }
            },
            {
                className: "",
                data: 'action',
                render: (data, type) => {
                    if(type === 'display'){
                        // let vya = JSON.parse(data);
                        return '<input class="form-control" type="text" name="sadsawwd" id="" style="width: 200px;">';
                        // return '<div. class="container h-100 m-0 px-auto"> <div class="row justify-content-center align-self-center w-100 m-0"><a class="btn btn-primary w-100" href="<?= base_url('pmk/assessment'); ?>?id='+vya.id+'"><i class="fa fa-search mx-auto"></i></a></div></div>';
                    }
                    return data;
                }
            },
            {
                className: "",
                data: 'action',
                render: (data, type) => {
                    if(type === 'display'){
                        let vya = JSON.parse(data);
                        return '<div class="container h-100 m-0 px-auto"> <div class="row justify-content-center align-self-center w-100 m-0"><a class="btn btn-primary w-100" href="<?= base_url('pmk/assessment'); ?>?id='+vya.id+'"><i class="fa fa-search mx-auto"></i></a></div></div>';
                    }
                    return data;
                }
            }
        ]
    });
</script>