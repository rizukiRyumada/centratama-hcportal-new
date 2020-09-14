<script>
    // variable
    var status = '<?= $mytask; ?>';
    var mytask = '<?= $mytask; ?>';

    // table index ptk
    // Tabel HealthReport
    var table = $('#table_indexPTK').DataTable({
        responsive: true,
        // processing: true,
        language : { 
            // processing: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>',
            zeroRecords: '<p class="m-0 text-danger font-weight-bold">No Data.</p>'
        },
        // pagingType: 'full_numbers',
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
        if($(this).data('status') == "4"){
            status = mytask; // pake variable mytask
            table.ajax.reload(); // reload table
        } else {
            status = $(this).data('status'); // ubah status variable
            table.ajax.reload(); // reload table
        }
    });

    // open timeline
    function showTimeline(id_entity, id_div, id_dept, id_pos, id_time){
        // get data status dari database
        $.ajax({
            url: '<?= base_url("ptk/ajax_getStatusData"); ?>',
            data: {
                id_entity: id_entity,
                id_div: id_div,
                id_dept: id_dept,
                id_pos: id_pos,
                id_time: id_time
            },
            method: "POST",
            success: function(data){
                let data_timeline = JSON.parse(data);

                // kosongkan timeline
                $('.timeline').empty();

                // variabel buat penanda
                let date_before = "";
                let id_timeline = "";
                $.each(data_timeline, function(index, value){
                    // split data timeline
                    let el = value.time.split('<~>');
                    let date_now = el[0];
                    let time_now = el[1];

                    // tambah data timeline
                    if(date_before != date_now){
                        // buat label date
                        id_timeline = "timeline-"+index;
                        $('.timeline').prepend('<div id="'+id_timeline+'" class="time-label"><span class="bg-red">'+date_now+'</span></div>');
                    } else {
                        // nothing
                    }
                    date_before = date_now; // set date before dengan date now

                    $('#'+id_timeline).parent().append('<div><i class="'+value.icon+' bg-'+value.css_color+'"></i><div id='+index+' class="timeline-item"><span class="time"><i class="fas fa-clock"></i> '+time_now+'</span><h3 class="timeline-header"><a href="#">'+value.signedby+'</a> '+value.signedbynik+'</h3></div></div>');

                    // jika tidak kosong tampilkan pesan revisi
                    if(value.pesan_revisi != undefined){
                        $('.timeline-item#'+index).append('<div class="timeline-body">'+value.pesan_revisi+'</div>'); // tambah pesan revisi
                    }
                    $('.timeline-item#'+index).append('<div class="timeline-footer"><span class="badge badge-'+value.css_color+'">'+value.status_name+'</span></div>');
                });

                // show modal
                $('#statusViewer').modal('show');
            }
        });

        // atur data timeline
    }
</script>