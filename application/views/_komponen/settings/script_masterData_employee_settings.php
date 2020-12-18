<script>
    // tombol edit employe
    $('.editEmp').click(function() {
        $.ajax({
            url: '<?= base_url('settings/ajax_getDetails_employee') ?>',
            data: {
                nik: $(this).data('nik')
            },
            method: 'POST',
            success: function(data){
                data = JSON.parse(data)
                $('#editEmployeModal').modal('show'); //menampilkan modal

                $('input[id="nik_edit"]').val(data.nik);
                $('input[name="onik"]').val(data.nik); // buat ditaruh di form origin NIK
                $('input[id="name_edit"]').val(data.emp_name);
                $('input[id="departemen_edit"]').val(data.departemen);
                $('input[id="position_edit"]').val(data.position_name);
                $('input[id="email_edit"]').val(data.email);

                // tambah option divisi
                // $('.div').empty(); //hapus dulu optionnya
                // $.each(data.divisi, function(i, v){
                //     $('.div').append('<option value="div-' + v.id + '">' + v.division + '</option>') //tambah 1 per 1 option
                // });
                $('#div_edit').val('div-' + data.div_id); //select value option dari data employe
                // tambah option entity
                // $('.entity').empty(); //hapus optionnya
                // $.each(data.entity, function(i, v){
                //     $('.entity').append('<option value="'+ v.id +'">'+ v.nama_entity +' | '+ v.keterangan +'</option>') //tambah 1 per satu option
                // });
                $('#entity_edit').val(data.id_entity); //select value option sesuai dari data employe

                //  tambah option role
                // $('.role').empty(); // kosongkan dulu optionnya
                // $.each(data.role, function(i, v){
                //     $('.role').append('<option value="'+ v.id +'">'+ v.role +'</option>'); // tambah 1 per 1 option
                // });
                $('#role_edit').val(data.role_id); // select value optiondari data employe
                $('#empstats_edit').val(data.emp_stats); // employee status select
                $('#mlevel_edit').val(data.level_personal); // level personal select
                $('#date_birth_edit').val(data.date_birth); // level personal select
                $('#date_join_edit').val(data.date_join); // level personal select

                // role surat
                if(data.akses_surat_id == 1){
                    $('input[id="role_surat_edit"]').prop('checked', true);
                } else {
                    $('input[id="role_surat_edit"]').prop('checked', false);
                }
            }
        });
    });

    // prepare variable
    let selected_nik = "";
    // action delete employee
    $('.deleteEmp').on('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: false,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#0072c6'
        }).then((result) => {
            if (result.isConfirmed) {
                selected_nik = $(this).data('nik');
                // tampilkan modal tantangan typeit challenge
                $('#typeItModal').modal('show');
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                Swal.fire(
                    'Cancelled',
                    "Okay I'll make everything untounchable.",
                    'error'
                )
            }
        })
    });

    // deklarasi datatables
    mTable = $('#departemen-table, #divisi-table, #employe-table').DataTable({
        "lengthMenu": [10, 25, 50, 75, 100] ,
        "pageLength": 10,
        deferRender: true,
        responsive: true
    });
    
    //filter table dengan DOM untuk divisi dan departemen
    $('#divisi').change(function(){
        mTable.column(3).search(this.value).draw();// filter kolom divisi
        mTable.column(4).search('').draw();// hapus filter kolom departemen
        mTable.order([4, 'asc']).draw(); // order berdasarkan kolom departemen
    });
    $('#departement').change(function(){
        mTable.column(4).search(this.value).order([4, 'asc']).draw(); 
    });
    $('#divisi').change(function(){
        var dipilih = $(this).val(); //ambil value dari yang terpilih
        
        if(dipilih == ""){
            mTable.column(4).search(dipilih).draw(); //kosongkan filter dom departement
            mTable.column(3).search(dipilih).draw(); //kosongkan filter dom departement
            mTable.order([0, 'asc']).draw();
        }
        
        $.ajax({
            url: "<?php echo base_url('settings/ajax_getDepartment'); ?>",
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

    /* -------------------------------------------------------------------------- */
    /*                      Mapping buat di editEmployeModal                      */
    /* -------------------------------------------------------------------------- */
    // mapping divisi select option untuk departemen
    $('#div_edit').change(function(){
        var dipilih = $(this).val(); //ambil value dari yang terpilih
        $.ajax({
            url: "<?php echo base_url('settings/ajax_getDepartment'); ?>",
            data: {
                divisi: dipilih //kirim ke server php
            },
            method: "POST",
            success: function(data) { //jadi nanti dia balikin datanya dengan variable data
                $('input[id="departemen_edit"]').hide();
                $('#dept_edit').empty().show(); //kosongkan selection value dan tambahkan satu selection option
                $('input[id="position_edit"]').show();
                $('#pos_edit').empty().hide(); //kosongkan selection value dan tambahkan satu selection option
                
                $('#dept_edit').append('<option value="">Pilih Departemen...</option>'); // add first option
                $.each(JSON.parse(data), function(i, v) {
                    $('#dept_edit').append('<option value="' + v.id + '">' + v.nama_departemen + '</option>'); //tambahkan 1 per 1 option yang didapatkan
                });
            }
        });
    });
    // mapping departemen select option untuk posisi
    $('#dept_edit').change(function(){
        $.ajax({
            url: "<?= base_url('settings/ajax_getPosition') ?>",
            data: {
                div: $('#div_edit').val(),
                dept: $(this).val()
            },
            method: "POST",
            success: function(data) {
                $('input[id="position_edit"]').hide();
                $('#pos_edit').empty().show(); //kosongkan selection value dan tambahkan satu selection option

                $('#pos_edit').append('<option value="">Pilih Posisi...</option>'); // add first option
                $.each(JSON.parse(data), function(i, v) {
                    $('#pos_edit').append('<option value="' + v.id + '">' + v.position_name + '</option>'); //tambahkan 1 per 1 option yang didapatkan
                });
            }
        })
    });
    /* -------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------- */
    /*                      Mapping buat di tambahEmployeModal                    */
    /* -------------------------------------------------------------------------- */
    // mapping divisi select option untuk departemen
    $('#div_tambah').change(function(){
        var dipilih = $(this).val(); //ambil value dari yang terpilih
        $.ajax({
            url: "<?php echo base_url('settings/ajax_getDepartment'); ?>",
            data: {
                divisi: dipilih //kirim ke server php
            },
            method: "POST",
            success: function(data) { //jadi nanti dia balikin datanya dengan variable data
                $('input[id="departemen_tambah"]').hide();
                $('#dept_tambah').empty().show(); //kosongkan selection value dan tambahkan satu selection option
                $('input[id="position_tambah"]').show();
                $('#pos_tambah').empty().hide(); //kosongkan selection value dan tambahkan satu selection option
                
                $('#dept_tambah').append('<option value="">Pilih Departemen...</option>'); // add first option
                $.each(JSON.parse(data), function(i, v) {
                    $('#dept_tambah').append('<option value="' + v.id + '">' + v.nama_departemen + '</option>'); //tambahkan 1 per 1 option yang didapatkan
                });
            }
        });
    });
    // mapping departemen select option untuk posisi
    $('#dept_tambah').change(function(){
        $.ajax({
            url: "<?= base_url('settings/ajax_getPosition') ?>",
            data: {
                div: $('#div_tambah').val(),
                dept: $(this).val()
            },
            method: "POST",
            success: function(data) {
                $('input[id="position_tambah"]').hide();
                $('#pos_tambah').empty().show(); //kosongkan selection value dan tambahkan satu selection option

                $('#pos_tambah').append('<option value="">Pilih Posisi...</option>'); // add first option
                $.each(JSON.parse(data), function(i, v) {
                    $('#pos_tambah').append('<option value="' + v.id + '">' + v.position_name + '</option>'); //tambahkan 1 per 1 option yang didapatkan
                });
            }
        })
    });
    /* -------------------------------------------------------------------------- */


    // fungsi ketika tambahEmployeModal dihidden
    $('#tambahEmployeModal, #editEmployeModal').on('hidden.bs.modal', function (e) {
        $('input[id="position_edit"], input[id="position_tambah"]').show();
        $('input[id="departemen_edit"], input[id="departemen_tambah"]').show();

        /* ---------------------------- reset form tambahEmploye --------------------------- */
        $("#div_tambah, #role_tambah, #entity_tambah").prop("selectedIndex", 0) //balikan seleksi option ke yg pertama
        $('input[id="is_active_tambah"], input[id="role_surat_tambah"]').prop('checked', false);
        /* -------------------------------------------------------------------------- */

        /* ------------- sembunyikan elemen select departemen dan posisi ------------ */
        $('.dept').hide();
        $('.pos').hide();

        $('#pos_tambah').empty().append('<option value="">Pilih Posisi...</option>').prop("selectedIndex", 0); //kosongkan selection value dan tambahkan satu selection option
        /* -------------------------------------------------------------------------- */
         
        $('input[type="password"]').val(""); //kosongkan input password
        $('#role_add').val('3'); // set default selected role to user di modal tambahEmployeModal ke USER
        $('*').validate().resetForm(); // reset validator pada editEmployeForm
    });

    // set default selected role to user di modal tambahEmployeModal ke USER
    $('#role_add').val('3');

    // edit employe validator
    $('#editEmployeForm').validate({
        rules: {
            nik: {
                required: true,
                minlength: 8
            },
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            password: {
                minlength: 8
            },
            emp_stats: {
                required: true
            },
            master_level: {
                required: true
            },
            date_birth: {
                required: true
            },
            date_join: {
                required: true
            }
        },
        messages: {
            nik: {
                required: "Harap masukkan nik karyawan",
                minlength: "NIK seharusnya terdiri dari 8 karakter"
            },
            name: {
                required: "Harap masukkan nama karyawan",
            },
            email: {
                required: 'Silakan masukkan email karyawan.',
                email: 'Harap masukkan email yang valid (username@provider)'
            },
            password: {
                minlength: "Password harus memiliki minimal 8 karakter"
            },
            emp_stats: {
                required: "Silakan pilih status Employee"
            },
            master_level: {
                required: "Silakan pilih status master level"
            },
            date_birth: {
                required: "Silakan isi tanggal lahir karyawan"
            },
            date_join: {
                required: "Silakan isi tanggal lahir karyawan"
            }
        }
    });

    // tambah employe validator
    $('#tambahEmployeForm').validate({
            rules: {
                nik: {
                    required: true,
                    minlength: 8
                },
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 8
                },
                divisi:{
                    required: true
                },
                departemen: {
                    required: true
                },
                position: {
                    required: true
                },
                entity: {
                    required: true
                },
                role: {
                    required: true
                },
                emp_stats: {
                    required: true
                },
                master_level: {
                    required: true
                },
                date_birth: {
                    required: true
                },
                date_join: {
                    required: true
                }
            },
            messages: {
                nik: {
                    required: "Harap masukkan nik karyawan.",
                    minlength: "NIK seharusnya terdiri dari 8 karakter."
                },
                name: {
                    required: "Harap masukkan nama karyawan.",
                },
                email: {
                    required: 'Silakan masukkan email karyawan.',
                    email: 'Harap masukkan email yang valid (username@provider.mail).'
                },
                password: {
                    required: "Silakan masukkan password untuk login.",
                    minlength: "Password harus memiliki minimal 8 karakter."
                },
                role: {
                    required: "Silakan pilih Role Karyawan."
                },
                entity: {
                    required: "Silakan pilih Entity Karyawan."
                },
                emp_stats: {
                    required: "Silakan pilih status Employee"
                },
                master_level: {
                    required: "Silakan pilih status master level"
                },
                date_birth: {
                    required: "Silakan isi tanggal lahir karyawan"
                },
                date_join: {
                    required: "Silakan isi tanggal lahir karyawan"
                }
            },
            errorElement: 'span',
            errorClass: 'text-right pr-2',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

    // variable preparation
    let input_typeit = $('input[name="typeit"]'); // selector input typeit
    let msg = ['<span id="exampleInputEmail1-error" class="error invalid-feedback">', '</span>'];
    let msg_empty = msg[0]+'Please enter the phare above.'+msg[1];
    let msg_notmatch = msg[0]+'The Phrase you typed is not match, please try again.'+msg[1];
    // on click typeit challenge
    $('#deleteEmp_typeItChallenge').on('click', () => {
        // cek validasi
        if(validate_input_typeit() == true){
            $.ajax({
                url: '<?= base_url('settings/ajax_removeEmployee'); ?>',
                data: {
                    nik: selected_nik
                },
                method: "POST",
                beforeSend: () => {
                    $('#typeItModal').modal('hide'); // hide the modal
                    // remove typeit attribute
                    input_typeit.removeClass('is-invalid is-valid');
                    input_typeit.siblings('.invalid-feedback').remove();
                    input_typeit.val(""); // kosongkan typeit form
                    Swal.fire({
                        icon: 'info',
                        title: 'Please Wait',
                        html: '<p>'+"Please don't close this tab and the browser, the employee data is being archived."+'<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false
                    });
                },
                error: () => {
                    Swal.fire(
                        '500 Error',
                        'There is an error on the server, sorry.',
                        'error'
                    )
                },
                success: (data) => {
                    if(data == 1){
                        Swal.fire({
                            icon: 'success',
                            title: 'Employee Archived to hcportal_archives',
                            html: '<p>'+"Employee data has been archived."+'<br/><br/>Refreshing this page...<br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false
                        });
                        // refresh the page
                        setTimeout(function() {
							location.reload();
						}, 2000);
                    } else {
                        Swal.fire(
                            '404 Unknown Error',
                            'There is an unknown error, please contact HC Care to get more assistance.',
                            'error'
                        )
                    }
                }
            });
        }
    });

    // submit swal loading for add employee
    // $("#submitEmployee").on('click', () => {
    //     Swal.fire({
    //         icon: 'info',
    //         title: 'Please Wait',
    //         html: '<p>'+"Please don't close this tab and the browser, the employee data is being added to the system."+'<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
    //         showConfirmButton: false,
    //         allowOutsideClick: false,
    //         allowEscapeKey: false,
    //         allowEnterKey: false
    //     });
    // });

    //validator input typeit
    input_typeit.on('keyup', function(){
        // validate input type it first
        validate_input_typeit();
    });

    function validate_input_typeit(){
        let typed = input_typeit.val(); // ambil data yang diinput
        input_typeit.removeClass('is-invalid is-valid');
        input_typeit.siblings('.invalid-feedback').remove();
        if(typed == "" || typed == undefined || typed == null){
            input_typeit.addClass('is-invalid');
            input_typeit.parent().append(msg_empty);
            return false;
        } else if(typed != "saya yakin untuk menonaktifkan karyawan ini"){
            input_typeit.addClass('is-invalid');
            input_typeit.parent().append(msg_notmatch);
            return false;
        } else {
            input_typeit.addClass('is-valid');
            return true;
        }
    }

    // settings script
    $('.date-master').datepicker({
        format: "yyyy-mm-dd",
        weekStart: 1,
        startView: 1,
        multidate: false,
        // daysOfWeekDisabled: "0,6",
        // daysOfWeekHighlighted: "0,6",
        autoclose: true,
        todayHighlight: true
    });
</script>