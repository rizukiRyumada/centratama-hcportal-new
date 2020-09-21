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

                // role surat
                if(data.akses_surat_id == 1){
                    $('input[id="role_surat_edit"]').prop('checked', true);
                } else {
                    $('input[id="role_surat_edit"]').prop('checked', false);
                }

                // is_active aktif karyawan
                if(data.is_active == 1){
                    $('input[id="is_active_edit"]').prop('checked', true);
                } else {
                    $('input[id="is_active_edit"]').prop('checked', false);
                }
            }
        });
    });

    $('.deleteEmp').on('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                Swal.fire(
                    'Cancelled',
                    'Your imaginary file is safe',
                    'error'
                )

                var a = "<?= base_url('master/deleteEmploye') ?>";
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
</script>