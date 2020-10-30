<script>
    var id_summary = "<?= $id_summary; ?>";

    // untuk menampilkan jawaban approval dari yang sudah diisi
    $(document).ready(function(){
        <?php foreach($data_summary as $v): ?>
            $('#chooser_approval<?= $v['id']; ?>').val('<?= $v['approval']; ?>');
            $('#chooser_entityNew<?= $v['id']; ?>').val('<?= $v['entity_new']; ?>');
        <?php endforeach;?>
    });

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
        ]
    });

    // approval action with ajax
    $('select[name="approval"]').on('change', function () { 
        let id = $(this).data('id');
        let value = $(this).val();

        // untuk mengaktifkan dan menonaktifkan attribute disable
        if(value == 1){
            $('#chooser_entityNew'+id).removeAttr('disabled');
        } else {
            $('#chooser_entityNew'+id).attr('disabled', true);
            pmk_updateApproval(id, value); // update approval summary
        }
    });

    // entity select action 
    $('select[name="entity_new"]').on('change', function(){
        let id = $(this).data('id');
        let value = $('#chooser_approval'+id).val(); // ambil value summary action
        let entity = $(this).val();

        pmk_updateApproval(id, value, entity); // update approval summary
    });

    // function untuk mengupdate approval
    function pmk_updateApproval(id, value, entity = ""){
        $.ajax({
            url: "<?= base_url('pmk/ajax_updateApproval'); ?>",
            data: {
                id: id,
                value: value,
                entity: entity
            },
            method: "POST",
            beforeSend: function(){
                $('select[name="approval"]').hide();
                $('select[name="entity_new"]').hide();
                $('.pmk-indicator').fadeIn();
            },
            success: function(data){
                toastr["success"]("Summary action has been saved.", "Saved");
            },
            error: function(){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!'
                });
            },
            complete: function(){
                $('select[name="approval"]').fadeIn();
                $('select[name="entity_new"]').fadeIn();
                $('.pmk-indicator').hide();
            }
        });
    }
</script>