<script>
    var showhat =  0; var divisi = ""; var departemen = ""; var status = ""; var daterange = "<?= date('m/d/o', strtotime("-2 month", time())) ?> - <?= date('m/d/o', strtotime("+2 month", time())); ?>";
    // jika dia divhead, admin, hc divhead, atau ceo jalankan skrip ini
    var table = $('#table_indexPMK').DataTable({
        responsive: true,
        processing: true,
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
            url: '<?= base_url('pmk/ajax_getList'); ?>',
            type: 'POST',
            data: function(data) {
                // kirim data ke server
                data.showhat = showhat;
                data.divisi = divisi;
                data.departemen = departemen;
                data.status = status;
                data.daterange = daterange;
            },
            beforeSend: () => {
                // $('.overlay').removeClass('d-none'); // hapus class d-none
                toastr["warning"]("This will take a few moments.", "Retrieving data...");
                $('.overlay').fadeIn(); // hapus overlay chart
                ajax_start_time = new Date().getTime(); // ajax stopwatch
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
            {data: 'nik'},
            {data: 'divisi'},
            {data: 'department'},
            {data: 'position'},
            {data: 'emp_name'},
            {
                className: "",
                data: 'status_now',
                render: (data, type) => {
                    if(type === 'display'){
                        let vya = JSON.parse(data);
                        let vya_trigger = JSON.parse(vya.trigger);
                        return '<a href="javascript:showTimeline('+vya_trigger.nik+', '+vya_trigger.contract+')" ><span class="w-100 badge badge-'+vya.status.css_color+'">'+vya.status.name_text+'</span></a>';
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
                        return '<div class="container h-100 m-0 px-auto"> <div class="row justify-content-center align-self-center w-100 m-0"><a class="btn btn-primary w-100" href="<?= base_url('pmk/assessment'); ?>?nik='+vya.nik+'&contract='+vya.contract+'"><i class="fa fa-search mx-auto"></i></a></div></div>';
                    }
                    return data;
                }
            }
        ]
    });

    $(document).ready(() => {
        // table.ajax.reload();

    /* -------------------------------------------------------------------------- */
    /*                    Assessment Survey Filtering Function                    */
    /* -------------------------------------------------------------------------- */

        // filter divisi 
        $('#divisi').change(function(){
            divisi = $(this).val(); // ubah variable divisi
            // get department from the server
            $.ajax({
                url: "<?php echo base_url('job_profile/ajax_getdepartement'); ?>",
                data: { //kirim ke server php
                    divisi: divisi,
                },
                method: "POST",
                beforeSend: () => {
                    $('#departemen').empty().append('<option value="">Loading...</option>'); //kosongkan selection value dan tambahkan satu selection option
                },
                success: function(data) { //jadi nanti dia balikin datanya dengan variable data
                    if(divisi == ""){
                        $('#departemen').empty().append('<option value="">Please choose division first</option>'); //kosongkan selection value dan tambahkan satu selection option
                    } else {
                        $('#departemen').empty().append('<option value="">All</option>'); //kosongkan selection value dan tambahkan satu selection option
                        $.each(JSON.parse(data), function(i, v) {
                            $('#departemen').append('<option value="dept-' + v.id + '">' + v.nama_departemen + '</option>'); //tambahkan 1 per 1 option yang didapatkan
                        });
                    }
                }
            });
            table.ajax.reload(); // reload table
        });
        // filter departemen
        $("#departemen").on('change', function() {
            departemen = $(this).val(); // ubah variabel departemen
            table.ajax.reload(); // reload table
        });
        // filter status
        $("#status").on('change', function() {
            status = $(this).val(); // ubah variabel departemen
            table.ajax.reload(); // reload table
        });
        // tombol reset filter
        $('#resetFilterAsses').on('click', function(){
            // reset division filter
            $('#divisi').prop('selectedIndex',0);
            divisi = "";
            // reset departemen filter
            $('#departemen').empty().append('<option value="">Please choose division first</option>'); //kosongkan selection value dan tambahkan satu selection option
            departemen = "";
            // reset status filter
            $('#status').prop('selectedIndex',0);
            status = "";
            table.ajax.reload(); // reload table
        });

        <?php if($this->session->userdata('role_id') == 1 || $userApp_admin == 1): ?>
            $("#buttonRefreshPMK").on('click', () => {
                let ajax_start_time;
                $.ajax({
                    url: '<?= base_url('pmk/pmk_refresh'); ?>',
                    beforeSend: () => {
                        $("#iconRefreshPMK").addClass('fa-spin'); // spin icon font awesome
                        $('#eoc').empty().append('<i class="fa fa-circle-notch fa-spin text-primary"></i>');
                        $('#act').empty().append('<i class="fa fa-circle-notch fa-spin text-primary"></i>');
                        $('#cpt').empty().append('<i class="fa fa-circle-notch fa-spin text-primary"></i>');

                        toastr["warning"]("While the PMK data is being refreshed.", "Please Wait...");
                        ajax_start_time = new Date().getTime(); // ajax stopwatch
                    },
                    success: (data) => {
                        let vya = JSON.parse(data);
                        // ubah spinner jadi data angka
                        $('#eoc').empty().append(vya.counter_pmk);
                        $('#act').empty().append(vya.counter_active);
                        $('#cpt').empty().append(vya.counter_inactive);
                        if(vya.counter_new != ""){
                            toastr["info"]("There is "+vya.counter_new+" new employe that will reach out the end of contract.", "New Data Added")
                        } else {
                            // nothing
                        }

                        $("#iconRefreshPMK").removeClass('fa-spin'); // spin icon font awesome

                        let ajax_request_time = new Date().getTime() - ajax_start_time;
                        toastr["success"]("PMK Form Data successfully refreshed.<br/><small>Retrieved in "+ajax_request_time+" ms", "Completed</small>")
                        table.ajax.reload(); // reload table
                    },
                    error: () => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        })
                    }
                });
            });
        <?php endif; ?>
    });

/* -------------------------------------------------------------------------- */
/*                           assessment data chooser                          */
/* -------------------------------------------------------------------------- */

    // fungsi setiap tombol
    $('#chooserData1').on('click', function(){
        if($(this).hasClass('active') == false){
            $('#chooserData2').removeClass('active');
            $('#chooserData1').addClass('active');
            $('#daterangeChooser').slideUp();

            chooseIt($(this).data('choosewhat'));
        }
    });
    $('#chooserData2').on('click', function(){ // chooser history
        if($(this).hasClass('active') == false){
            $('#chooserData1').removeClass('active');
            $('#chooserData2').addClass('active');
            $('#daterangeChooser').slideDown();
            
            chooseIt($(this).data('choosewhat'));
        }
    });
    // function to take action after choosing
    function chooseIt(choose){
        showhat = choose; // ganti flag view data
        table.ajax.reload(); // reload table
    }

/* -------------------------------------------------------------------------- */
/*                              daterange picker                              */
/* -------------------------------------------------------------------------- */

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
        "startDate": "<?= date('m/d/o', strtotime("-2 month", time())) ?>",
        "endDate": "<?= date('m/d/o', strtotime("+2 month", time())); ?>",
        "minDate": "YYYY-MM-DD",
        "maxDate": "YYYY-MM-DD",
        "drops": "auto",
        "applyButtonClasses": "btn-success"
    }, function(start, end, label) {
        console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });
</script>