<script>
    // get data ptk from ajax
    $(document).ready(function(){
        // set timeline data and set the view
        set_timelineView(id_entity, id_div, id_dept, id_pos, id_time);

        // ajax function to get data from database and placed it on form
        $.ajax({
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
                console.log(data);

                $('#ptkForm').attr('action', "<?= base_url('ptk/updateStatus'); ?>"); // ganti form action url

                // form select option
                $("#entityInput option[value="+data.data.id_entity+"]").attr('selected', 'selected'); // select entity base on data
                $("#jobLevelForm option[value="+data.data.job_level+"]").attr('selected', 'selected'); // select job level base on data
                // other job level remove if 
                $("#emp_stats option[value="+data.data.id_employee_status+"]").attr('selected', 'selected'); // select employee status base on data
                $("#education option[value="+data.data.id_ptk_edu+"]").attr('selected', 'selected'); // select education base on data
                $("#sexForm option[value="+data.data.sex+"]").attr('selected', 'selected'); // select sex base on data
                
                // fill free text form
                $('input[name="mpp_req"]').val(data.data.req_mpp);
                $('input[name="date_required"]').val(data.data.req_date);
                $('input[name="majoring"]').val(data.data.majoring);
                $('input[name="preferred_age"]').val(data.data.age);

                $('input[name="mpp_req"]').removeAttr('disabled'); // remove disabled attribute from input_mpp
                getNoi(data.data.id_pos); // ambil number of incumbent

                // interviewer set data
                // console.log(data.data.interviewer);
                let interviewer = JSON.parse(data.data.interviewer);
                $('input[name="interviewer_name1"]').val(interviewer[0].name);
                $('input[name="interviewer_position1"]').val(interviewer[0].position);
                $('input[name="interviewer_name2"]').val(interviewer[1].name);
                $('input[name="interviewer_position2"]').val(interviewer[1].position);    
                $('input[name="interviewer_name3"]').val(interviewer[2].name);
                $('input[name="interviewer_position3"]').val(interviewer[2].position);

                // budget selector
                // select budget
                $('input[name="budget"][value="'+data.data.budget+'"]').attr('checked',true);
                $("#budgetAlert").hide(); // sembunyikan overlay job position alert

                // replacement selector
                if(data.data.replacement != ""){
                    input_replacement.attr('checked', true); // check replacement checkbox
                    input_replacement_who.val(data.data.replacement); // isi kotak replacement
                    input_replacement_who.removeAttr('disabled'); // aktifkan form replacement who
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
                    // nothing
                }

                // work experience selector
                if(data.data.work_exp > 0) { // cek jika cekbox work experience
                    input_workexp_years.fadeIn(); // tampilkan kotak free text tahun
                    input_workexp_yearstext.val(data.data.work_exp); // set data work experience
                    $('input[name="work_exp"][value="1"]').attr('checked',true); // select work experience
                } else if(data.data.work_exp == 0) { // cek jika cekbox fresh graduate
                    $('input[name="work_exp"][value="0"]').attr('checked',true); // select work experience
                }

                // taruh data position dan interviewer
                getPositionInterviewer(data.data.id_div, data.data.id_dept, data.data.id_pos);

                <?php if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1): ?>
                    // taruh data division
                    select_divisi.val(data.data.id_div);

                    // get departemen dan pilih valuenya
                    getDepartment("div-"+data.data.id_div, data.data.id_dept);
                <?php endif; ?>
                
                // tampilkan Job Position chooser atau text
                if(data.data.budget == 0) { // cek jika unbudgeted
                    input_jptext.fadeIn(); // tampilkan free text buat nulis nama job 
                    input_jpchoose.hide(); // sembunyikan pilihan posisi job
                    // $('#positionInput').prop('selectedIndex',0);// kembalikan status ke default - position chooser
                    input_jptext.val(data.data.position_other); // isi nama position other
                } else if(data.data.budget == 1) { // cek jika budgeted
                    input_jpchoose.fadeIn(); // tampilkan pilihan job position 
                    input_jptext.hide(); // sembunyikan free text job profile
                    input_jptext.val(''); // kosongkan kotak job_position_text
                    $('select#positionInput option[value="'+ data.data.id_pos +'"]').attr('selected',true); // ubah job position yg dipilih
                    $('input[name="job_position_choose"]').val(data.data.id_pos);
                }

                // CKEDITOR set data form
                CKEDITOR.instances['ska'].setData(data.data.req_ska);
                CKEDITOR.instances['req_special'].setData(data.data.req_special);
                CKEDITOR.instances['outline'].setData(data.data.outline);
                CKEDITOR.instances['main_responsibilities'].setData(data.data.main_responsibilities);
                CKEDITOR.instances['tasks'].setData(data.data.tasks);

                // tampilkan tab job profile viewer dan ambil datanya
                showMeJobProfile(id_pos);

                $('.overlay').fadeOut(); // hapus overlay
            }
        })
    });
</script>