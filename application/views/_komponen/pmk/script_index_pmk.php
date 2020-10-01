<script>
    $(document).ready(() => {
        <?php if($this->session->userdata('role_id') == 1 || $userApp_admin == 1): ?>
            let ajax_start_time;
            $.ajax({
                url: '<?= base_url('pmk/pmk_refresh'); ?>',
                beforeSend: () => {
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

                    let ajax_request_time = new Date().getTime() - ajax_start_time;
                    toastr["success"]("PMK Form Data successfully refreshed.<br/><small>Retrieved in "+ajax_request_time+" ms", "Completed</small>")
                },
                error: () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    })
                }
            });
        <?php endif; ?>

        // jika dia divhead, admin, hc divhead, atau ceo jalankan skrip ini
        // var table = $('#table_indexPTK').DataTable({
        //     responsive: true,
        //     // processing: true,
        //     language : { 
        //         // processing: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>',
        //         zeroRecords: '<p class="m-0 text-danger font-weight-bold">No Data.</p>'
        //     },
        //     pagingType: 'full_numbers',
        //     // serverSide: true,
        //     // dom: 'Bfrtip',
        //     deferRender: true,
        //     // custom length menu
        //     lengthMenu: [
        //         [5, 10, 25, 50, 100, -1 ],
        //         ['5 Rows', '10 Rows', '25 Rows', '50 Rows', '100 Rows', 'All' ]
        //     ],
        //     order: [[0, 'desc']],
        //     // buttons
        //     buttons: [
        //         'pageLength', // place custom length menu when add buttons
        //         {
        //             extend: 'excel',
        //             text: '<i class="fas fa-file-excel" aria-hidden="true"></i> Export to Excel',
        //             title: '',
        //             filename: 'Health Report-<?= date("dmo-Hi"); ?>',
        //             exportOptions: {
        //                 modifier: {
        //                     //Datatables Core
        //                     order: 'index',
        //                     page: 'all',
        //                     search: 'none'
        //                 }
        //                 // ,columns: [0,1,2,3,4]
        //             }
        //         }
        //     ],
        //     ajax: {
        //         url: '<?= base_url('ptk/ajax_getMyFormList'); ?>',
        //         type: 'POST',
        //         data: function(data) {
        //             // kirim data ke server
        //             data.status = status
        //         },
        //         beforeSend: () => {
        //             // $('.overlay').removeClass('d-none'); // hapus class d-none
        //             toastr["warning"]("This will take a few moments.", "Retrieving data...");
        //             $('.overlay').fadeIn(); // hapus overlay chart

        //             ajax_start_time = new Date().getTime(); // ajax stopwatch
        //         },
        //         complete: (data, jqXHR) => { // run function when ajax complete
        //             table.columns.adjust();

        //             if(tab_clicked == 1){ // cek jika actionnya tab clicked
        //                 let eldata = data.responseJSON.statuses;
        //                 let statusPTK = $("#statusPtk");
        //                 statusPTK.empty().append('<option value="">Filter Status</option>'); //kosongkan selection value dan tambahkan satu selection option
        //                 $.each(eldata, function(i, v) {
        //                     statusPTK.append('<option value="' + v.id + '">' + v.name + '</option>'); //tambahkan 1 per 1 option yang didapatkan
        //                 });
        //             }
                    
        //             // ajax data counter
        //             var ajax_request_time = new Date().getTime() - ajax_start_time;
        //             toastr["success"]("data retrieved in " + ajax_request_time + "ms", "Completed");
                    
        //             $('.overlay').fadeOut(); // hapus overlay chart
        //         }
        //     },
        //     columns: [
        //         {data: 'time_modified'},
        //         {data: 'name_div'},
        //         {data: 'name_dept'},
        //         {
        //             classNmae: "",
        //             data: 'status_now',
        //             render: (data, type) => {
        //                 if(type === 'display'){
        //                     var status = ''; // status name
        //                     var cssClass = ''; // class name

        //                     // switch(data) {
        //                     //     case '0':
        //                     //         status = 'Sick';
        //                     //         cssClass = 'text-danger';
        //                     //         break;
        //                     //     case '1':
        //                     //         status = "Healthy";
        //                     //         cssClass = 'text-success';
        //                     //         break;
        //                     // }
        //                     process_data = data.split("<~>");
        //                     hrefData = JSON.parse(process_data[1]);
        //                     switch(process_data[0]) {
        //                         <?php foreach($pmk_status as $k => $v): ?>
        //                             case '<?= $v['id_status']; ?>':
        //                                 status = '<?= $v['name_text']; ?>';
        //                                 cssClass = '<?= $v['css_color']; ?>';
        //                                 break;
        //                         <?php endforeach;?>
        //                     }

        //                     return '<a href="javascript:showTimeline('+hrefData[0]+', '+hrefData[1]+', '+hrefData[2]+', '+hrefData[3]+', '+hrefData[4]+')" ><span class="w-100 badge badge-'+cssClass+'">'+status+'</span></a>';
        //                 }
        //                 return data;
        //             }
        //         },
        //         {
        //             classNmae: "",
        //             data: 'href',
        //             render: (data, type) => {
        //                 if(type === 'display'){

        //                     return '<div class="container h-100 m-0 px-auto"> <div class="row justify-content-center align-self-center w-100 m-0"><a class="btn btn-primary w-100" href="'+data+'"><i class="fa fa-search mx-auto"></i></a></div></div>';
        //                 }
        //                 return data;
        //             }
        //         }
        //     ]
        // });
    });
</script>