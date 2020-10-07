<script>
    $(document).ready(() => {
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
                    // data.status = status
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
                            return '<a href="javascript:showTimeline('+vya_trigger.nik+', '+vya_trigger.contract+')" ><span class="w-100 badge badge-'+vya.status.css_color+'">'+status+'</span></a>';
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
</script>