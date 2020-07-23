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
        })
    });

    // Filter Departemen
    // $('#departement').change(() => {
    //     let divisi = $('#divisi').val();
    //     let departemen = $('#departement').val();

    //     $.ajax({
    //         url: "<?= base_url('healthReport/ajaxGetEmployee'); ?>",
    //         data: {
    //             divisi: divisi,
    //             departemen: departemen
    //         },
    //         method: "POST",
    //         success: (data) => {
    //             console.log(data);
    //         }
    //     });
    // });

    <?php
    /* -------------------------------------------------------------------------- */
    /*                        DATATABLE SERVERSIDED SCRIPT                        */
    /* -------------------------------------------------------------------------- */
    ?>
    // Tabel HealthReport
    var table = $('#report_healthCheckIn').DataTable({
        responsive: true,
        processing: true,
        language : { 
            processing: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>',
            zeroRecords: '<p class="m-0 text-danger font-weight-bold">No Data.</p>'
        },
        pagingType: 'full_numbers',
        // serverSide: true,
        dom: 'Bfrtip',
        // custom length menu
        lengthMenu: [
            [ 10, 25, 50, 100, -1 ],
            [ '10 Rows', '25 Rows', '50 Rows', '100 Rows', 'All' ]
        ],
        // buttons
        buttons: [
            'pageLength', // place custom length menu when add buttons
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
                // kirim data ke server
                data.divisi = $('#divisi').val(),
                data.departemen = $('#departement').val(),
                data.daterange = $('input[name="daterange"]').val();
            },
            complete: (data) => { // run function when ajax complete
                table.columns.adjust()

                // place to chart data variable
                statushealth_chartData[0] = data.responseJSON.counter_sakit;
                statushealth_chartData[1] = data.responseJSON.counter_sehat;

                $.each( data.responseJSON.counter_kategori, function( key, value ) {
                    // data chart
                    kategorihealth_chartData[key] = value.counter
                    kategorihealth_labelData[key] = value.name
                    
                    // color for chart
                    color = random_colors();
                    kategorihealth_colorData[key] = color[1];
                    kategorihealth_backgroundcolorData[key] = color[0];
                });

                refreshChart(); // refresh chart
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
</script>

<?php 
/* -------------------------------------------------------------------------- */
/*                               CHART JS SCRIPT                              */
/* -------------------------------------------------------------------------- */
?>
<!-- Health Rasio Chart -->
<script>
var statusHealth_ctx = $('#healthRasio');
var statusHealth_chart = new Chart(statusHealth_ctx, {
    type: 'pie',
    data: {
        labels: ['Sakit', 'Sehat'],
        datasets: [{
            label: '# of Votes',
            data: statushealth_chartData,
            backgroundColor: [
                'rgba(218, 0, 3, 0.2)',
                'rgba(16, 227, 0, 0.2)'
            ],
            borderColor: [
                'rgba(218, 0, 3, 1)',
                'rgba(16, 227, 0, 1)'
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
</script>

<!-- Health Category Rasio -->
<script>
var categorySick_ctx = $('#healthcategoryRasio');
var categorySick_chart = new Chart(categorySick_ctx, {
    type: 'pie',
    data: {
        labels: kategorihealth_labelData,
        datasets: [{
            label: '# of Votes',
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
<?php 
/* -------------------------------------------------------------------------- */
/*                                  FUNCTIONS                                 */
/* -------------------------------------------------------------------------- */
?>
function refreshChart() { // refresh chart
    categorySick_chart.update();
    statusHealth_chart.update();
}

function random_colors() {
    var o = Math.round, r = Math.random, s = 255;
    let color = 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',';
    return [color+'0.2)', color+'1)'];
}

// src: https://jsfiddle.net/GP3z8/2/

<?php 
/* -------------------------------------------------------------------------- */
/*                                filter script                               */
/* -------------------------------------------------------------------------- */
?>



</script>