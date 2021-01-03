<script>
<?php 
/* -------------------------------------------------------------------------- */
/*                          GLOBAL VARIABLE FOR CHART                         */
/* -------------------------------------------------------------------------- */
?>
// variabel buat health status data
var statushealth_chartData = Array();

// variabel buat chart kategori
var kategorihealth_chartData = Array();
var kategorihealth_labelData = Array();
var kategorihealth_colorData = Array();
var kategorihealth_backgroundcolorData = Array();

// variabel buat dailyhealth chart
var dailyhealth_labelData = Array();
var dailyhealth_chartData = Array(Array(), Array(), Array());
var dailyhealth_backgroundColor = Array(Array(), Array(), Array());
var dailyhealth_borderColor = Array(Array(), Array(), Array());

// variable for ajax stopwatch
var ajax_start_time;

    <?php
    /* -------------------------------------------------------------------------- */
    /*                        DATATABLE SERVERSIDED SCRIPT                        */
    /* -------------------------------------------------------------------------- */
    ?>
    // Tabel HealthReport
    var table = $('#report_healthCheckIn').DataTable({
        responsive: true,
        // processing: true,
        language : { 
            // processing: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>',
            zeroRecords: '<p class="m-0 text-danger font-weight-bold">No Data.</p>'
        },
        pagingType: 'full_numbers',
        // serverSide: true,
        dom: 'Bfrtip',
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
            },
            {
                text: '<i class="fas fa-file-excel" aria-hidden="true"></i> Export to Excel (All)',
                action: ( e, dt, node, config ) => {
                    // $.ajax({
                    //     url: "<?= base_url('healthReport/ajax_export2Excel'); ?>",
                    //     data: {
                    //         daterange: $('input[name="daterange"]').val(),
                    //         divisi: $('#divisi').val(),
                    //         departemen: $('#departement').val()
                    //     },
                    //     method: "POST",
                    //     beforeSend: (data) => {
                    //         Swal.fire({
                    //             icon: 'info',
                    //             title: 'Please Wait...',
                    //             text: 'Preparing the Health Reports data!',
                    //             showConfirmButton: false
                    //         });
                    //     },
                    //     success: () => {
                    //         Swal.fire({
                    //             icon: 'success',
                    //             title: 'Completed',
                    //             text: 'The data is prepared and being downloaded automatically!'
                    //         });
                    //     },
                    //     error: () => {
                    //         Swal.fire({
                    //             icon: 'error',
                    //             title: 'Error',
                    //             text: 'There was an error while processing the data, please contact HC Care!'
                    //         });
                    //     }
                    // });

                    let daterange = $('input[name="daterange"]').val();
                    let divisi = $('#divisi').val();
                    let departement = $('#departement').val();

                    $('input[name="daterangeSelected"]').val(daterange);
                    $('input[name="divisiSelected"]').val(divisi);
                    $('input[name="departemenSelected"]').val(departement);
                    $("#downloadForm").submit();
                }
            }
            // ,
            // {
            //     extend: 'csv',
            //     text: '<i class="fas fa-file-csv" aria-hidden="true"></i> Export to CSV',
            //     title: '',
            //     filename: 'Health Report-<?= date("dmY-Hi"); ?>',
            //     exportOptions: {
            //         modifier: {
            //             //Datatables Core
            //             order: 'index',
            //             page: 'all',
            //             search: 'none'
            //         }
            //         // ,columns: [0,1,2,3,4]
            //     }
            // }
        ],
        ajax: {
            url: '<?= base_url('healthReport/ajax_getReportData'); ?>',
            type: 'POST',
            data: function(data) {
                // kirim data ke server
                data.divisi = $('#divisi').val(),
                data.departemen = $('#departement').val(),
                data.daterange = $('input[name="daterange"]').val();
            },
            beforeSend: () => {
                // $('.overlay').removeClass('d-none'); // hapus class d-none
                toastr["warning"]("This will take a few moments.", "Retrieving data...");
                toastr["error"]("The longer the date range chosen, the longer the time for data to be retrieved. Please be patient.", "Caution!");
                $('.overlay').fadeIn(); // hapus overlay chart

                ajax_start_time = new Date().getTime();
            },
            complete: (data, jqXHR) => { // run function when ajax complete
                table.columns.adjust();

                // place to chart data variable
                statushealth_chartData[0] = data.responseJSON.hs_pie['sehat'];
                statushealth_chartData[1] = data.responseJSON.hs_pie['sakit'];
                statushealth_chartData[2] = data.responseJSON.hs_pie['kosong'];
                // statushealth_chartData[1] = data.responseJSON.counter_sehat;

                // data chart kategori pie
                $.each( data.responseJSON.sc_pie, function( key, value ) {
                    // data chart
                    kategorihealth_chartData[key] = value.counter
                    kategorihealth_labelData[key] = value.name
                    
                    // color for chart
                    color = random_colors();
                    kategorihealth_colorData[key] = color[1];
                    kategorihealth_backgroundcolorData[key] = color[0];
                });

                // diagram btang
                <?php if($this->session->userdata('role_id') == 1 || $userApp_admin == 1 || $is_divhead == true || $is_depthead == true): ?>

                    // reset isi array
                    dailyhealth_labelData.length = 0;
                    dailyhealth_chartData[0].length = 0;
                    dailyhealth_chartData[1].length = 0;
                    dailyhealth_chartData[2].length = 0;
                    dailyhealth_backgroundColor[0].length = 0;
                    dailyhealth_backgroundColor[1].length = 0;
                    dailyhealth_backgroundColor[2].length = 0;
                    dailyhealth_borderColor[0].length = 0;
                    dailyhealth_borderColor[1].length = 0;
                    dailyhealth_borderColor[2].length = 0;

                    // variabel buat dailyhealth chart

                    // inisiasi variabel dropdown
                    let dates_so = Array();
                    // data chart diagram batang
                    $.each(data.responseJSON.hd_bar, (key, value) => {
                        // data chart
                        dailyhealth_labelData[key] = value.date;
                        dailyhealth_chartData[0][key] = value.data_sehat;
                        dailyhealth_chartData[1][key] = value.data_sakit;
                        dailyhealth_chartData[2][key] = value.data_kosong;

                        // color for chart
                        dailyhealth_backgroundColor[0][key] = 'rgba(16, 227, 0, 0.2)';
                        dailyhealth_borderColor[0][key] = 'rgba(16, 227, 0, 1)';
                        dailyhealth_backgroundColor[1][key] = 'rgba(218, 0, 3, 0.2)';
                        dailyhealth_borderColor[1][key] = 'rgba(218, 0, 3, 1)';
                        dailyhealth_backgroundColor[2][key] = 'rgba(111, 111, 111, 0.2)';
                        dailyhealth_borderColor[2][key] = 'rgba(111, 111, 111, 1)';

                        // ambil date buat ditaruh di dropdown
                        dates_so[key] = value.date;
                    });
                    
                    <?php if($this->session->userdata('role_id') == 1 || $userApp_admin == 1): ?>
                        // kosongkan dropdown dulu
                        $('#showOn').empty()
                        // .append('<option value="">Select Dates...</option>'); //kosongkan selection value dan tambahkan satu selection option
                        // tambahkan dates ke dropdown
                        $.each(dates_so.reverse(), (key, value) => {
                            $('#showOn').append('<option value="' + value + '">' + value + '</option>'); //tambahkan 1 per 1 option yang didapatkan
                        });
                    <?php endif; ?>
                <?php endif; ?>

                // ajax data counter
                var ajax_request_time = new Date().getTime() - ajax_start_time;
                toastr["success"]("data retrieved in " + ajax_request_time + "ms", "Completed");
                
                refreshChart(); // refresh chart
                $('.overlay').fadeOut(); // hapus overlay chart
            }
        },
        columns: [
            {data: 'date'},
            {data: 'emp_name'},
            {data: 'detail_position.departement'},
            {data: 'detail_position.divisi'},
            {
                // classNmae: "",
                // data: 'status',
                // render: (data, type) => {
                //     if(type === 'display'){
                //         var status = ''; // status name
                //         var cssClass = ''; // class name

                //         switch(data) {
                //             case '0':
                //                 status = 'Sick';
                //                 cssClass = 'text-danger';
                //                 break;
                //             case '1':
                //                 status = "Healthy";
                //                 cssClass = 'text-success';
                //                 break;
                //         }
                //         return '<p class="m-0 font-weight-bold text-center '+cssClass+'">'+status+'</p>';
                //         // return status;
                //     }
                //     return data;
                // }
                data: 'status'
            },
            {data: 'sickness'},
            {data: 'notes'}
        ]
    });

    // FILTER SCRIPT
    // Filter Divisi
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
        });

        table.ajax.reload(); // reload table
    });

    // Filter Departemen
    $('#departement').change(() => {
        // let divisi = $('#divisi').val();
        // let departemen = $('#departement').val();

        // $.ajax({
        //     url: "<?= base_url('healthReport/ajaxGetEmployee'); ?>",
        //     data: {
        //         divisi: divisi,
        //         departemen: departemen
        //     },
        //     method: "POST",
        //     success: (data) => {
        //         console.log(data);
        //     }
        // });

        table.ajax.reload(); // reload table
    });

    // filter date
    $('#daterange').on('change', () => {
        table.ajax.reload(); // reload table
    });

    // sick category filter
    $('#healthStatus_filter').change(() => {
        let selection = $('#healthStatus_filter').val();
        table.column(4).search(selection).order([4, 'asc']).draw(); //kosongkan filter dom departement
    });

    // sick category select option
    $('#sickCategory_filter').change(() => {
        let selection = $('#sickCategory_filter').val();
        table.column(5).search(selection).order([5, 'asc']).draw(); //kosongkan filter dom departement
    });

    // reset filter on datatable filter
    $('#reset_filter').on('click', () => {
        // balikkan ke default
        table.column(5).search('').draw(); // reset filter status
        table.column(4).search('').draw(); // reset filter status
        table.order([0, 'desc']).draw();
        $('#healthStatus_filter').prop('selectedIndex',0);
        $('#sickCategory_filter').prop('selectedIndex',0);
    });

    // ketika tomboll apply filter diklik di navbar apply button
    $('#apply_table').on('click', () => {
        table.ajax.reload(); // reload table
    })

    $('#showOn').on('change', () => {
        dipilih = $('#showOn').val();

        $.ajax({
            url: "<?= base_url('healthReport/ajax_getPieChartData'); ?>",
            data: {
                date: dipilih,
                divisi: $('#divisi').val(),
                departemen: $('#departement').val()
            },
            method: "POST",
            beforeSend: (data) => {
                $('#overlayPie').fadeIn(); // tampilin overlay
            },
            success: (data) => {
                let parsed_data = JSON.parse(data);

                // place to chart data variable
                statushealth_chartData[0] = parsed_data.hs_pie['sehat'];
                statushealth_chartData[1] = parsed_data.hs_pie['sakit'];
                statushealth_chartData[2] = parsed_data.hs_pie['kosong'];
                // statushealth_chartData[1] = parsed_data.counter_sehat;

                // parsed_data chart kategori pie
                $.each( parsed_data.sc_pie, function( key, value ) {
                    // parsed_data chart
                    kategorihealth_chartData[key] = value.counter
                    kategorihealth_labelData[key] = value.name
                    
                    // color for chart
                    color = random_colors();
                    kategorihealth_colorData[key] = color[1];
                    kategorihealth_backgroundcolorData[key] = color[0];
                });

                refreshChart(); // refresh chart

                $('#overlayPie').fadeOut(); // tampilin overlay
            }
        });
    });
</script>

<?php 
/* -------------------------------------------------------------------------- */
/*                               CHART JS SCRIPT                              */
/* -------------------------------------------------------------------------- */
?>
<!-- Health Status Chart -->
<script>
var statusHealth_ctx = $('#healthRasio');
var statusHealth_chart = new Chart(statusHealth_ctx, {
    type: 'pie',
    data: {
        labels: ['Healthy', 'Unwell', 'N/A'],
        datasets: [{
            data: statushealth_chartData,
            backgroundColor: [
                'rgba(16, 227, 0, 0.2)',
                'rgba(218, 0, 3, 0.2)',
                'rgba(111, 111, 111, 0.2)'
            ],
            borderColor: [
                'rgba(16, 227, 0, 1)',
                'rgba(218, 0, 3, 1)',
                'rgba(111, 111, 111, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        legend: {
            position: 'bottom'
        }
    }
});

// Health Category Chart
var categorySick_ctx = $('#healthcategoryRasio');
var categorySick_chart = new Chart(categorySick_ctx, {
    type: 'pie',
    data: {
        labels: kategorihealth_labelData,
        datasets: [{
            // label: '# of Votes',
            data: kategorihealth_chartData,
            backgroundColor: kategorihealth_backgroundcolorData,
            borderColor: kategorihealth_colorData,
            borderWidth: 1
        }]
    },
    options: {
        legend: {
            position: 'bottom'
        }
        //, //function to filter datatables using chartjs
        // onClick: (evt, item) => {
        //     let activePoints = myChart.getElementsAtEvent(evt);
        //     if(activePoints[0]){
        //         let chartData = activePoints[0]['_chart'].config.data;
        //         let idx = activePoints[0]['_index'];

        //         let label = chartData.labels[idx];
        //         let value = chartData.datasets[0].data[idx];

        //         let url = "http://example.com/?label=" + label + "&value=" + value;
        //         console.log(url);
        //         alert(url);
        //     }
        // }
        // src: https://jsfiddle.net/u1szh96g/208/
    }
});

// bar diagram buat admin
<?php if($this->session->userdata('role_id') == 1 || $userApp_admin == 1 || $is_divhead == true || $is_depthead == true): ?>
    var periodeChart = new Chart($('#periodeChart'), {
        type: 'bar',
        data: {
            labels: dailyhealth_labelData,
            datasets: [
            {
                label: 'Healthy',
                data: dailyhealth_chartData[0],
                backgroundColor: dailyhealth_backgroundColor[0],
                borderColor: dailyhealth_borderColor[0],
                borderWidth: 1
            },
            {
                label: 'Unwell',
                data: dailyhealth_chartData[1],
                backgroundColor: dailyhealth_backgroundColor[1],
                borderColor: dailyhealth_borderColor[1],
                borderWidth: 1
            },
            {
                label: 'N/A',
                data: dailyhealth_chartData[2],
                backgroundColor: dailyhealth_backgroundColor[2],
                borderColor: dailyhealth_borderColor[2],
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                position: 'bottom'
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    var periodeChart_more = new Chart($('#periodeChart_more'), {
        type: 'bar',
        data: {
            labels: dailyhealth_labelData,
            datasets: [
            {
                label: 'Healthy',
                data: dailyhealth_chartData[0],
                backgroundColor: dailyhealth_backgroundColor[0],
                borderColor: dailyhealth_borderColor[0],
                borderWidth: 1
            },
            {
                label: 'Unwell',
                data: dailyhealth_chartData[1],
                backgroundColor: dailyhealth_backgroundColor[1],
                borderColor: dailyhealth_borderColor[1],
                borderWidth: 1
            },
            {
                label: 'N/A',
                data: dailyhealth_chartData[2],
                backgroundColor: dailyhealth_backgroundColor[2],
                borderColor: dailyhealth_borderColor[2],
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                position: 'bottom'
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
<?php endif; ?>

<?php 
/* -------------------------------------------------------------------------- */
/*                                  FUNCTIONS                                 */
/* -------------------------------------------------------------------------- */
?>
function refreshChart() { // refresh chart
    categorySick_chart.update();
    statusHealth_chart.update();
    <?php if($this->session->userdata('role_id') == 1 || $userApp_admin == 1 || $is_divhead == true || $is_depthead == true): ?>
        periodeChart.update();
        periodeChart_more.update();
    <?php endif; ?>

}

function random_colors() {
    var o = Math.round, r = Math.random, s = 255;
    let color = 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',';
    return [color+'0.2)', color+'1)'];
}

// src: https://jsfiddle.net/GP3z8/2/
</script>