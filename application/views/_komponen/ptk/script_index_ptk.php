<script>
    // variable
    var status = '<?= $mytask; ?>';
    var mytask = '<?= $mytask; ?>';
    var status_selected = ""; // status selected buat filter per status
    var tab_clicked = 1; // flag tab clicked

    // table index ptk
    // Tabel HealthReport
    var table = $('#table_indexPTK').DataTable({
        responsive: true,
        // processing: true,
        language : { 
            // processing: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>',
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
            url: '<?= base_url('ptk/ajax_getMyFormList'); ?>',
            type: 'POST',
            data: function(data) {
                // kirim data ke server
                data.status = status
            },
            beforeSend: () => {
                // $('.overlay').removeClass('d-none'); // hapus class d-none
                toastr["warning"]("This will take a few moments.", "Retrieving data...");
                $('.overlay').fadeIn(); // hapus overlay chart

                ajax_start_time = new Date().getTime();
            },
            complete: (data, jqXHR) => { // run function when ajax complete
                table.columns.adjust();

                if(tab_clicked == 1){ // cek jika actionnya tab clicked
                    let eldata = data.responseJSON.statuses;
                    let statusPTK = $("#statusPtk");
                    statusPTK.empty().append('<option value="">Filter Status</option>'); //kosongkan selection value dan tambahkan satu selection option
                    $.each(eldata, function(i, v) {
                        statusPTK.append('<option value="' + v.id + '">' + v.name + '</option>'); //tambahkan 1 per 1 option yang didapatkan
                    });
                }
                
                // ajax data counter
                var ajax_request_time = new Date().getTime() - ajax_start_time;
                toastr["success"]("data retrieved in " + ajax_request_time + "ms", "Completed");
                
                $('.overlay').fadeOut(); // hapus overlay chart
            }
        },
        columns: [
            {data: 'time_modified'},
            {data: 'name_div'},
            {data: 'name_dept'},
            {
                classNmae: "",
                data: 'status_now',
                render: (data, type) => {
                    if(type === 'display'){
                        var status = ''; // status name
                        var cssClass = ''; // class name

                        // switch(data) {
                        //     case '0':
                        //         status = 'Sick';
                        //         cssClass = 'text-danger';
                        //         break;
                        //     case '1':
                        //         status = "Healthy";
                        //         cssClass = 'text-success';
                        //         break;
                        // }
                        process_data = data.split("<~>");
                        hrefData = JSON.parse(process_data[1]);
                        switch(process_data[0]) {
                            <?php foreach($ptk_status as $k => $v): ?>
                                case '<?= $v['id']; ?>':
                                    status = '<?= $v['status_name']; ?>';
                                    cssClass = '<?= $v['css_color']; ?>';
                                    break;
                            <?php endforeach;?>
                        }

                        return '<a href="javascript:showTimeline('+hrefData[0]+', '+hrefData[1]+', '+hrefData[2]+', '+hrefData[3]+', '+hrefData[4]+')" ><span class="w-100 badge badge-'+cssClass+'">'+status+'</span></a>';
                    }
                    return data;
                }
            },
            {
                classNmae: "",
                data: 'href',
                render: (data, type) => {
                    if(type === 'display'){

                        return '<div class="container h-100 m-0 px-auto"> <div class="row justify-content-center align-self-center w-100 m-0"><a class="btn btn-primary w-100" href="'+data+'"><i class="fa fa-search mx-auto"></i></a></div></div>';
                    }
                    return data;
                }
            }
        ]
    });

    // listen ke nav link untuk mengubah datatables
    $('.ptk_tableTrigger').on('click', function(){
        tab_clicked = 1; // flag tab clicked
        status_selected = $(this).data('status'); // set tab selected status
        if($(this).data('status') == "4"){
            status = mytask; // pake variable mytask
            table.ajax.reload(); // reload table
        } else {
            status = $(this).data('status'); // ubah status variable
            table.ajax.reload(); // reload table
        }
    });

    let filterPTK = $('#statusPtk');
    filterPTK.on('change', function(){
        tab_clicked = 0; // flag tab clicked
        if(filterPTK.val() != ""){
            let eldata = JSON.stringify({'my_task': [filterPTK.val()]});
            status = eldata; // pake variable mytask
            table.ajax.reload(); // reload table
        } else {
            if(status_selected == "4"){
                status = mytask; // pake variable mytask
                table.ajax.reload(); // reload table
            } else {
                status = status_selected; // ubah status variable
                table.ajax.reload(); // reload table
            }
        }
    });

    // open timeline
    function showTimeline(id_entity, id_div, id_dept, id_pos, id_time){
        // get data status dari database dan set status history timeline
        set_timelineView(id_entity, id_div, id_dept, id_pos, id_time);

        // show modal
        $('#statusViewer').modal('show');
    }
</script>