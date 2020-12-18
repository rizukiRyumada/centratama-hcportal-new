<script>
    // variable parameter untuk dapetin list file
    var path = "";
    var path_url = "";
    var session_name = '';
    var files = "";
    var flag_upload_new = 0;
    // script attachment ada di baris paling bawah
</script>
<script>
    // get data ptk from ajax
    $(document).ready(function(){
        // set timeline data and set the view
        set_timelineView(id_entity, id_div, id_dept, id_pos, id_time);
        ajax_getData(); // jalankan function ajax_getData
    });

/* -------------------------------------------------------------------------- */
/*                                  functions                                 */
/* -------------------------------------------------------------------------- */
    // fungsi pengambilan data ptk
    function ajax_getData(){
        // NOTE:  This function must return the value 
        //        from calling the $.ajax() method.
        // ajax function to get data from database and placed it on form
        return $.ajax({
            url: "<?= base_url('ptk/ajax_getPTKdata'); ?>",
            data: {
                id_entity: id_entity,
                id_div: id_div,
                id_dept: id_dept,
                id_pos: id_pos,
                id_time: id_time
            },
            method: "POST",
            beforeSend: function(data){
                $('.overlay').fadeIn(); // show overlay 
            },
            success: function(data){
                data = JSON.parse(data);
                // console.log(data);

                $('#ptkForm').attr('action', "<?= base_url('ptk/updateStatus'); ?>"); // ganti form action url

                let job_level = "";
                // form select option
                if(data.data.job_level == ""){
                    job_level = "";
                } else {
                    job_level = data.data.job_level;
                }
                validate_entity.val(data.data.id_entity); // select entity base on data
                $(".jobLevelForm").val(data.data.job_level); // select job level base on data
                
                // fill free text form
                $('input[name="mpp_req"]').val(data.data.req_mpp);
                $('input[name="date_required"]').val(data.data.req_date);

                if(<?php if(!empty($is_edit)){ echo($is_edit); } else { echo(0); } ?> == 1){
                    $('input[name="mpp_req"]').removeAttr('disabled'); // remove disabled attribute from input_mpp
                }
                getNoi(data.data.id_pos, data.data.budget); // ambil number of incumbent

                // interviewer set data
                // console.log(data.data.interviewer);
                let interviewer = JSON.parse(data.data.interviewer);
                $('input[name="interviewer_name1"]').val(interviewer[0].name);
                $('input[name="interviewer_position1"]').val(interviewer[0].position);
                $('input[name="interviewer_name2"]').val(interviewer[1].name);
                $('input[name="interviewer_position2"]').val(interviewer[1].position);    
                $('input[name="interviewer_name3"]').val(interviewer[2].name);
                $('input[name="interviewer_position3"]').val(interviewer[2].position);
                $('input[name="interviewer_name4"]').val(interviewer[3].name);
                $('input[name="interviewer_position4"]').val(interviewer[3].position);

                // select budget
                $('input[name="budget"][value="'+data.data.budget+'"]').attr('checked',true);
                $("#budgetAlert").hide(); // sembunyikan overlay job position alert

                // jika budgetnya replacement
                if(data.data.budget == 2){
                    $('#replace').slideDown();
                    select_replacement_who.val(data.data.replacement); // isi kotak replacement
                    if(<?= $is_edit; ?> == 1){
                        select_replacement_who.removeAttr('disabled'); // aktifkan form replacement who
                    }
                } else {
                    // nothing
                }

                // work location selector
                let work_location = JSON.parse(data.data.work_location); // parse json work location
                if(work_location.other == true){
                    $('input[name="work_location_otherTrigger"]').attr('checked', true); // cekbox true other location
                    // jika diceklis, tampilkan input free text work location
                    input_WLtext.val(work_location.location); // isi other work location
                    input_WLtext.show();
                    input_WLchoose.hide();
                    // pilih, pilihan pertama selected option location list
                    // input_WLchoose.prop('selectedIndex', 0);
                } else {
                    // jika tidak diceklis, tampilkan pilihan work location
                    $('select[name="work_location_choose"] option[value="'+ work_location.location +'"]').attr('selected',true); // ubah job position yg dipilih
                    // input_WLchoose.show();
                    // input_WLtext.hide();
                    // isi dummy text di input free text work location
                    // input_WLtext.val('');
                }

                // resources selector
                let resources = JSON.parse(data.data.resources); // parse json resources
                $('input[name="resources"][value="'+resources.resources+'"]').attr('checked',true); // select resources
                if(resources.resources == "int"){
                    input_resource_internalwho.slideDown(); // tampilkan input text internal_who
                    input_resource_internalwho.val(resources.internal_who); // tampilkan inpu
                } else if(resources.resources == "ext"){
                    // hapus disabled element
                    validate_empstats.removeAttr('disabled');
                    validate_education.removeAttr('disabled');
                    input_preferage.removeAttr('disabled');
                    validate_sex.removeAttr('disabled');
                    input_majoring.removeAttr('disabled');
                    input_workexp.removeAttr('disabled');
                }

                // set nilai dari masing2 selector
                    // work experience
                if(data.data.work_exp > 0) { // cek jika cekbox work experience
                    input_workexp_years.fadeIn(); // tampilkan kotak free text tahun
                    input_workexp_yearstext.val(data.data.work_exp); // set data work experience
                    input_workexp_at.val(data.data.work_exp_at);
                    input_workexp_at_container.slideDown();
                    $('input[name="work_exp"][value="1"]').attr('checked',true); // select work experience
                } else if(data.data.work_exp == 0) { // cek jika cekbox fresh graduate
                    $('input[name="work_exp"][value="0"]').attr('checked',true); // select work experience
                }
                input_majoring.val(data.data.majoring); // majoring
                input_preferage.val(data.data.age); // prefered age
                $("#education option[value="+data.data.id_ptk_edu+"]").attr('selected', 'selected'); // select education base on data
                // $("#sexForm option[value="+data.data.sex+"]").attr('selected', 'selected'); // select sex base on data

                // empstats
                $("#emp_stats option[value="+data.data.id_employee_status+"]").attr('selected', 'selected'); // select employee status base on data
                if(data.data.id_employee_status == "emp_stats-3"){
                    select_temporary_container.slideDown();
                    select_temporary.val(data.data.empstats_temporary_month); // set value empt stats temporary month
                } else {
                    select_temporary_container.slideUp();
                    select_temporary.val("");
                }

                select_divisi.val(data.data.id_div); // taruh data division
                getDepartment("div-"+data.data.id_div, data.data.id_dept); // get departemen dan pilih valuenya
                getInterviewer(data.data.id_pos)// ambil data interviewer
                
                // tampilkan Job Position chooser atau text
                if(data.data.budget == 0) { // cek jika unbudgeted
                    input_jptext.fadeIn(); // tampilkan free text buat nulis nama job 
                    input_jpchoose.hide(); // sembunyikan pilihan posisi job
                    // $('#positionInput').prop('selectedIndex',0);// kembalikan status ke default - position chooser
                    input_jptext.val(data.data.position_other); // isi nama position other
                } else if(data.data.budget == 1 || data.data.budget == 2) { // cek jika budgeted atau replacement
                    input_jpchoose.select2({theme: 'bootstrap4'}); // inisialisasi kalo job position choose itu select2
                    input_jpchoose.fadeIn(); // tampilkan pilihan job position 
                    input_jptext.hide(); // sembunyikan free text job profile
                    input_jptext.val(''); // kosongkan kotak job_position_text
                    // $('select#positionInput option[value="'+ data.data.id_pos +'"]').attr('selected',true); // ubah job position yg dipilih
                    $('input[name="job_position_choose"]').val(data.data.id_pos);

                    getPosition(data.data.id_div, data.data.id_dept, data.data.id_pos, data.data.budget);

                    // script khusus budget replacement dan select replacement whonya
                    if($('input[name="budget"]:checked').val() == 2){
                        let replacement_who = JSON.parse(data.data.replacement)
                        get_selectReplacementWho(data.data.id_pos, replacement_who.nik); // get employee for replacement_who from position and show it
                    } else {
                        hide_selectReplacementWho(); // hide replacement who
                    }
                }

                // ganti variable for ckeditor
                cke_ska = data.data.req_ska;
                cke_req_special = data.data.req_special;
                cke_outline = data.data.outline;
                cke_main_responsibilities = data.data.main_responsibilities;
                cke_tasks = data.data.tasks;

                // ganti variable untuk attachment tab
                // PRODUCTION ubah pathnya jadi sesuai server windows
                // path = '/assets/document/ptk/'+data.data.files_id;
                path = 'assets/document/ptk/'+data.data.files_id;
                path_url = "<?= base_url('assets/document/ptk/'); ?>"+data.data.files_id+'/'
                files = data.data.files
                // table_files.ajax.reload(); // update list files

                // tampilkan tab job profile viewer dan ambil datanya
                showMeJobProfile(id_pos);

                // $('.overlay').fadeOut(); // hapus overlay
                // the code above is moved to script_formvariable_ptk.php
                // CKEDITOR.replace 'tasks', 
            }
        })
    }
</script>

<!-- script attachment, param(path, session_name, files) -->
<?php $this->load->view('_komponen/ptk/script_attachment_ptk'); ?>

<!-- jalankan kode ini hanya jika mode edit -->
<?php if($is_edit == 1): ?>
    <script>
        // fungsi untuk mengupdate list file ke database
        function updateFilesToDatabase(files_new){
            $.ajax({
                url: "<?= base_url('ptk/ajax_files_update'); ?>",
                data: {
                    id_entity: id_entity,
                    id_div: id_div,
                    id_dept: id_dept,
                    id_pos: id_pos,
                    id_time: id_time,
                    files: files_new
                },
                method: "POST",
                success: function(data){
                    // set ulang variabel files
                    table_files.ajax.reload(); // update list files
                },
                error: function(){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Sorry, something went wrong!, Please Contact HC Care to get help.'
                    });
                }
            })
        }
    </script>
<?php endif; ?>