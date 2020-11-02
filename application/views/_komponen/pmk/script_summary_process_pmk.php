<script>
    // CKEditor Instances
    CKEDITOR.replace('notes', {
		enterMode: CKEDITOR.ENTER_BR
    });
    // variable yang dibutuhkan
    var id_summary = "<?= $id_summary; ?>";

    // untuk menampilkan jawaban summary dari yang sudah diisi
    $(document).ready(function(){
        $('input[name="id_summary"]').val(id_summary); // simpan id summary di form

        // untuk memilih option sesuai dengan isian form
        <?php foreach($data_summary as $v): ?>
            $('#chooser_summary<?= $v['id']; ?>').val('<?= $v['summary']; ?>');
            $('#chooser_entityNew<?= $v['id']; ?>').val('<?= $v['entity_new']; ?>');
            if($('#chooser_entityNew<?= $v['id']; ?>').val() != ""){
                $('#chooser_entityNew<?= $v['id']; ?>').removeAttr('disabled', true);
            }

            // lihat statusnya untuk mengaktifkan atau menonaktifkan approval action
            <?php $status = json_decode($v['status_now'], true); ?>
            if(!(<?= $status['status']['id_status']; ?> == 3 || <?= $status['status']['id_status']; ?> == 4 || <?= $status['status']['id_status']; ?> == 5)){
                $('#chooser_summary<?= $v['id']; ?>').attr('disabled', true);
            }
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

    // summary action with ajax
    $('select[name="summary"]').on('change', function () { 
        let id = $(this).data('id');
        let value = $(this).val();

        // untuk mengaktifkan dan menonaktifkan attribute disable
        if(value == 1){
            $('#chooser_entityNew'+id).removeAttr('disabled');
        } else {
            $('#chooser_entityNew'+id).attr('disabled', true);
            $('#chooser_entityNew'+id).val('');
            pmk_updateApproval(id, value); // update summary summary
        }
    });

    // entity select action 
    $('select[name="entity_new"]').on('change', function(){
        let id = $(this).data('id');
        let value = $('#chooser_summary'+id).val(); // ambil value summary action
        let entity = $(this).val();

        pmk_updateApproval(id, value, entity); // update summary summary
    });

    // button submit summary
    $('#button_submit').on('click', function() {
        // TODO tambah validasi lihat status karyawan apa sudah memenuhi untuk seleksi atau tidak
        // pesan error
        var msg_notes = '<div class="card-footer error-message bg-danger" ><div class="col text-center">Please enter your notes</div></div>';
        // div ckeditor selector
        var cke_notes = $('#cke_notes');
        // ambil note CKEDITOR
        const note = CKEDITOR.instances['notes'].getData();        
        
        // hapus pesan error dulu
        cke_notes.parent().parent().parent().removeClass('border border-danger');
        cke_notes.parent().parent().parent().siblings('.error-message').hide( "blind", 250, function () {
            cke_notes.parent().parent().parent().siblings('.error-message').remove(); // remove error message
        });

        // cek jika note nya kosong
        if(note == "" || note == undefined || note == null){
            toastr["error"]("Notes Should not be Empty.", "Error"); // tampilkan toastr error
            // tambahkan attribute pesan tambahan
            cke_notes.parent().parent().parent().addClass('border border-danger');
            cke_notes.parent().parent().parent().parent().append(msg_notes);

            // scroll ke notes kosongnya
            var $window = $(window),
                $element = cke_notes,
                elementTop = $element.offset().top,
                elementHeight = $element.height(),
                viewportHeight = $window.height(),
                scrollIt = elementTop - ((viewportHeight - elementHeight) / 2);
            $window.scrollTop(scrollIt);
        } else {
            let validate_status = 0;
            // cek jika ada karyawan yang id statusnya bukan 3
            <?php foreach($data_summary as $v): ?>
                <?php $status = json_decode($v['status_now'], true); ?> 
                if(!(<?= $status['status']['id_status']; ?> != 3 || <?= $status['status']['id_status']; ?> != 4 || <?= $status['status']['id_status']; ?> != 5)){
                    validate_status++;
                }
            <?php endforeach;?>
            // cek apa status karyawannya ada yang belum sampai 3
            if(validate_status != 0){
                // munculkan pesan error kalo ada karyawan yang belum submit assessment
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'There is employee/s who have not finished the assessment.'
                });
            } else {
                // tampilkan dialog submitting
                Swal.fire({
                    icon: 'info',
                    title: 'Processing The Summary Form',
                    html: '<p>Please wait, it will take a while.<br/>'+"Please don't close the browser or tab, even move to another tab, to avoid fail form submit."+'<br/><br/><img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80"></div></p>',
                    showConfirmButton: false,
                    // allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false
                });

                $('#form_notes').submit(); // lakukan submit form
            }
        }        
    });

/* -------------------------------------------------------------------------- */
/*                                  function                                  */
/* -------------------------------------------------------------------------- */

    // function untuk mengupdate summary
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
                $('select[name="summary"]').attr('disabled', true);
                $('select[name="entity_new"]').attr('disabled', true);
                toastr["warning"]("Your changes is being saved.", "Saving...");
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
                $('select[name="summary"]').removeAttr('disabled');
                if(entity != ""){
                    $('select[name="entity_new"]').removeAttr('disabled');
                }
                $('.pmk-indicator').hide();
            }
        });
    }
</script>