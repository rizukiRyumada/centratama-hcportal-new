<script>
    // CKEditor Instances
    CKEDITOR.replace('notes', {
        enterMode: CKEDITOR.ENTER_BR,
        on: {
            instanceReady: function(evt) {
                $('#ckeditor_loader').slideUp(); // sembunyikan loader
            }
        }
    });
    // variable yang dibutuhkan
    var id_summary = "<?= $id_summary; ?>";
    // untuk memilih option sesuai dengan isian form
    var counter_summary = <?= count($data_summary); ?>;
    var counter_summaryEmployee = 0;
    // variable selector
    var select_recomendation = $('select[name="recomendation"]');

    // buat penanda untuk menaruh informasi sudah atau belum di setiap id
    var flag_recomendation = Array(); x = 0;
    // untuk menampilkan jawaban summary dari yang sudah diisi
    $(document).ready(function(){
        $('input[name="id_summary"]').val(id_summary); // simpan id summary di form
        <?php foreach($data_summary as $v): ?>
            // ganti option select sesuai dengan valuenya
            $('#chooser_recomendation<?= $v['id']; ?>').val('<?= $v['recomendation']; ?>');
            $('#chooser_entityNew<?= $v['id']; ?>').val('<?= $v['entity_new']; ?>');
            $('#chooser_extendfor<?= $v['id']; ?>').val('<?= $v['extend_for']; ?>');
            // penanda saved untuk nandain kalo udh ada di database
            $('#chooser_entityNew<?= $v['id']; ?>').data('saved', '<?= $v['entity_new']; ?>');
            // cek jika chooser recomendation sudah keisi
            if($('#chooser_recomendation<?= $v['id']; ?>').val() != ""){
                $('#chooser_recomendation<?= $v['id']; ?>').data('saved', 1);
            }
            <?php $status = json_decode($v['status_now'], true); ?>
            // lihat statusnya untuk mengaktifkan atau menonaktifkan approval action
            if((<?= $status['status']['id_status']; ?> == 3 && "<?= $position_my['hirarki_org']; ?>" == "N" && <?= $position_my['id']; ?> != 196 && <?= $position_my['id']; ?> != 1) || (<?= $status['status']['id_status']; ?> == 3 && <?= $position_my['id']; ?> == 196 && <?= $v['divisi_id']; ?> == 6) || (<?= $status['status']['id_status']; ?> == 4 && <?= $position_my['id']; ?> == 196) || (<?= $status['status']['id_status']; ?> == 5 && <?= $position_my['id']; ?> == 1)){
                $('#chooser_recomendation<?= $v['id']; ?>').removeAttr('disabled');

                // beri flag recomendation kalo sudah melalui tahap assessment
                flag_recomendation[x] = {
                    id: '<?= $v['id']; ?>',
                    status: 1
                };
                x++;
            } else {
                // beri flag recomendation kalo belum melalui tahap assessment
                flag_recomendation[x] = {
                    id: '<?= $v['id']; ?>',
                    status: 0
                };
                x++;
            }
            // untuk menampilkan entity new dropdown
            /**
             * Penjelasan if
             * 1. dilihat valuenya apakah kosong
             * 2. dilihat data contractnya apa dia genap ganjil dengan aritmatika modulus
             * 3. dilihat dari status per karyawan form pmk nya dan hirarki karyawan
             */
            if(($('#chooser_entityNew<?= $v['id']; ?>').val() != "" && $('#chooser_entityNew<?= $v['id']; ?>').data('contract') % 2 == 0) && ((<?= $status['status']['id_status']; ?> == 3 && "<?= $position_my['hirarki_org']; ?>" == "N" && <?= $position_my['id']; ?> != 196 && <?= $position_my['id']; ?> != 1) || (<?= $status['status']['id_status']; ?> == 3 && <?= $position_my['id']; ?> == 196 && <?= $v['divisi_id']; ?> == 6) || (<?= $status['status']['id_status']; ?> == 4 && <?= $position_my['id']; ?> == 196) || (<?= $status['status']['id_status']; ?> == 5 && <?= $position_my['id']; ?> == 1))){
                $('#chooser_entityNew<?= $v['id']; ?>').removeAttr('disabled');
            }
            // untuk mengaktifkan atau menonaktifkan extend dropdown list
            /**
             * jika recomendasinya ituS bukan extend nonaktifkan extendfor
             */
            if($('#chooser_extendfor<?= $v['id']; ?>').val() != "" && ((<?= $status['status']['id_status']; ?> == 3 && "<?= $position_my['hirarki_org']; ?>" == "N" && <?= $position_my['id']; ?> != 196 && <?= $position_my['id']; ?> != 1) || (<?= $status['status']['id_status']; ?> == 3 && <?= $position_my['id']; ?> == 196 && <?= $v['divisi_id']; ?> == 6) || (<?= $status['status']['id_status']; ?> == 4 && <?= $position_my['id']; ?> == 196) || (<?= $status['status']['id_status']; ?> == 5 && <?= $position_my['id']; ?> == 1))){
                $('#chooser_extendfor<?= $v['id']; ?>').removeAttr('disabled');
            }
            /**
             * penjelasan if
             * jika memenuhi id statusnya dengan hirarki orgnya
             */
            if((<?= $status['status']['id_status']; ?> == 3 && "<?= $position_my['hirarki_org']; ?>" == "N" && <?= $position_my['id']; ?> != 1) || (<?= $status['status']['id_status']; ?> == 4 && <?= $position_my['id']; ?> == 196) || (<?= $status['status']['id_status']; ?> == 5 && <?= $position_my['id']; ?> == 1)){
                counter_summaryEmployee++;
            }
        <?php endforeach;?>

        // sembunyikan tombol submit dan notes jika ada employee yg masih belum selesai assessmentnya
        if(counter_summary != counter_summaryEmployee){
            $('#button_container').hide();
            $('#notes_container').hide();
            $('#messages_container').show(); // tampilkan pesan peringatan
        }
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
            leftColumns: 2
            // ,rightColumns: 1
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
    select_recomendation.on('change', function () { 
        let id = $(this).data('id');
        let value = $(this).val();

        // untuk mengaktifkan dan menonaktifkan attribute disable
        if(value == 1){ // jika pilihannya extend
            let contract = $('#chooser_entityNew'+id).data('contract');
            if(contract % 2 == 0){
                $('#chooser_entityNew'+id).removeAttr('disabled');
            }
            $('#chooser_extendfor'+id).removeAttr('disabled');
            toastr["warning"]("Please choose extend for.", "Attention!"); // tampilkan toastr error
        } else {
            // matikan extend value dan entity
            $('#chooser_entityNew'+id).attr('disabled', true);
            $('#chooser_extendfor'+id).attr('disabled', true);
            // kosongkan value entity dan extend for
            $('#chooser_entityNew'+id).val('');
            $('#chooser_extendfor'+id).val('');
            pmk_updateApproval(id, value); // update summary summary
        }
    });

    // entity select action 
    $('select[name="entity_new"]').on('change', function(){
        let id = $(this).data('id');
        let value = $('#chooser_recomendation'+id).val(); // ambil value summary action
        let entity = $(this).val();
        let extend_for = $('#chooser_extendfor'+id).val();
        let contract = $(this).data('contract');

        if(entity != ""){ // cek apakah entity terpilih tidak kosong
            if(value == 1){ // apakah recomendationnya extend
                if(contract % 2 == 0){ // apakah contractnya genap
                    if(extend_for != ""){ // apakah extend fornya kosong
                        pmk_updateApproval(id, value, entity, extend_for); // update summary summary
                    } else {
                        toastr["warning"]("Please choose extend for to save your change.", "Attention!"); // tampilkan toastr error
                    }
                }
            }
        }
    });

    // extend for on change
    $('select[name="extend_for"]').on('change', function(){
        let id = $(this).data('id');
        let value = $('#chooser_recomendation'+id).val(); // ambil value summary action
        let entity = $('#chooser_entityNew'+id).val();
        let extend_for = $(this).val();
        let contract = $('#chooser_entityNew'+id).data('contract');

        if(extend_for != ""){ // cek apakah extend for terpilih tidak kosong
            if(value == 1){ // apakah recomendationnya extend
                if(contract % 2 == 0){
                    if(entity == ""){
                        toastr["warning"]("Please choose New Entity to save your change.", "Attention!"); // tampilkan toastr error
                    } else {
                        pmk_updateApproval(id, value, entity, extend_for); // update summary summary
                    }
                } else {
                    pmk_updateApproval(id, value, entity, extend_for); // update summary summary
                }
            }
        }
    });

    // button submit summary
    $('#button_submit').on('click', function() {
        // TODO tambah validasi lihat status karyawan apa sudah memenuhi untuk seleksi atau tidak
        // pesan error
        var msg_notes = '<div class="row error-message bg-danger py-2" ><div class="col text-center">Please fill your notes and the notes must contain atleast 2 character</div></div>';
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
        if((note == "" || note == undefined || note == null || note.length < 2)){
            toastr["error"]("Notes Should not be Empty and must contain atleast 2 characters.", "Error"); // tampilkan toastr error
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
            let validate_status = 0; let element_scroll = "";
            // cek jika ada yang valuenya actionnya belum diisi
            <?php foreach($data_summary as $v): ?>
                if($('#chooser_recomendation<?= $v['id']; ?>').data('saved') == ""){
                    validate_status++;
                    element_scroll = $('#chooser_recomendation<?= $v['id']; ?>');
                }
            <?php endforeach;?>
            // cek apa status karyawannya ada yang belum sampai 3
            if(counter_summary != counter_summaryEmployee){
                // munculkan pesan error kalo ada karyawan yang belum submit assessment
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'There is employee/s who have not finished the assessment.'
                });
            } else if(validate_status != 0 ){ // cek apa validasinya gagal
                // tampilkan pesan error
                toastr["error"]("There is an empty recomendation on employee data.", "Oops...");
                // scroll ke atas
                var $window = $(window),
                    $element = element_scroll,
                    elementTop = $element.offset().top,
                    elementHeight = $element.height(),
                    viewportHeight = $window.height(),
                    scrollIt = elementTop - ((viewportHeight - elementHeight) / 2);
                $window.scrollTop(scrollIt);
            } else {
                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // tampilkan dialog submitting
                        Swal.fire({
                            icon: 'info',
                            title: 'Processing The Summary Form',
                            html: '<p>Please wait, it will take a while.<br/>'+"Please don't close the browser or tab, even move to another tab, to avoid fail form submit."+'<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></div></p>',
                            showConfirmButton: false,
                            // allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false
                        });
                        $('#form_notes').submit(); // lakukan submit form
                    }
                })
            }
        }        
    });

/* -------------------------------------------------------------------------- */
/*                                  function                                  */
/* -------------------------------------------------------------------------- */

    // function untuk mengupdate summary
    function pmk_updateApproval(id, value, entity = "", extend_for = ""){
        $.ajax({
            url: "<?= base_url('pmk/ajax_updateApproval'); ?>",
            data: {
                id: id,
                value: value,
                entity: entity,
                extend_for: extend_for
            },
            method: "POST",
            beforeSend: function(){
                // aktifkan dan noaktifkan recomendation, entity, dan extend for dengan flag recomendation
                $.each(flag_recomendation, function(index, nilai){
                    if(nilai.status == 1){ // lihat apakah id ini sudah memenuhi assessment complete
                        $('#chooser_recomendation'+nilai.id).attr('disabled', true);
                        if(entity != ""){ // jika entitynya tidak kosong
                            $('#chooser_entityNew'+nilai.id).attr('disabled', true);
                        }
                        if(value == 1){ // jika valuenya extend
                            $('#chooser_extendfor'+nilai.id).attr('disabled', true);
                        }
                    }
                });
                toastr["warning"]("Your changes is being saved.", "Saving...");
            },
            success: function(data){
                toastr["success"]("Summary action has been saved.", "Saved");
                // ganti data saved di select option recomendation untuk validasi form
                $('#chooser_recomendation'+id).data('saved', 1);
                // penanda counter recomendation summary kalo sudah diisi
            },
            error: function(){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!'
                });
            },
            complete: function(){
                $.each(flag_recomendation, function(index, nilai){
                    if(nilai.status == 1){ // lihat apakah id ini sudah memenuhi assessment complete
                        $('#chooser_recomendation'+nilai.id).removeAttr('disabled');
                        if(entity != ""){ // jika entitynya tidak kosong
                            $('#chooser_entityNew'+nilai.id).removeAttr('disabled');
                        }
                        if(value == 1){ // jika valuenya extend
                            $('#chooser_extendfor'+nilai.id).removeAttr('disabled');
                        }
                    }
                });
            }
        });
    }
</script>