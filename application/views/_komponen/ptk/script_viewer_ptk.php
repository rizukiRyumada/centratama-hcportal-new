<script>
    // get data ptk from ajax
    $(document).ready(function(){
        // CKEDITOR Instances
        CKEDITOR.replace( 'ska' );
        CKEDITOR.replace( 'req_special' );
        CKEDITOR.replace( 'outline' );
        CKEDITOR.replace( 'main_responsibilities' );
        CKEDITOR.replace( 'tasks' );

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
            // beforeSend: function(data){
            //     $('.overlay').fadeIn(); // show overlay 
            // },
            success: function(data){
                data = JSON.parse(data);
                console.log(data);

                // form select option
                $("#entityInput option[value="+data.data.id_entity+"]").attr('selected', 'selected'); // select entity base on data
                $("#jobLevelForm option[value="+data.data.job_level+"]").attr('selected', 'selected'); // select job level base on data
                $("#emp_stats option[value="+data.data.id_employee_status+"]").attr('selected', 'selected'); // select employee status base on data
                $("#education option[value="+data.data.id_ptk_edu+"]").attr('selected', 'selected'); // select education base on data
                $("#sexForm option[value="+data.data.sex+"]").attr('selected', 'selected'); // select sex base on data
                
                // fill free text form
                $('input[name="mpp_req"]').val(data.data.req_mpp);
                $('input[name="date_required"]').val(data.data.req_date);
                $('input[name="majoring"]').val(data.data.majoring);
                $('input[name="preferred_age"]').val(data.data.age);

                // interviewer set data
                // console.log(data.data.interviewer3);
                if(data.data.interviewer3 != null){
                    let interviewer3 = JSON.parse(data.data.interviewer3);
                    
                    $('input[name="interviewer_name3"]').val(interviewer3.name);
                    $('input[name="interviewer_position3"]').val(interviewer3.position);
                }

                // budget selector
                // variable budget
                let input_jptext = $('input[name="job_position_text"]'); // selector job position text
                let input_jpchoose = $('select[name="job_position_choose"]'); // selector job position text
                let input_budget = $('input[name="budget"]');
                let input_budget_checked = $('input[name="budget"]:checked');
                // select budget
                $('input[name="budget"][value="'+data.data.budget+'"]').attr('checked',true);
                $("#budgetAlert").hide(); // sembunyikan overlay job position alert
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
                }

                let input_replacement_who = $('input[name="replacement_who"]');
                let input_replacement = $('input[name="replacement"]');
                // replacement selector
                if(data.data.replacement != ""){
                    input_replacement.attr('checked', true); // check replacement checkbox
                    input_replacement_who.val(data.data.replacement); // isi kotak replacement
                    input_replacement_who.removeAttr('disabled'); // aktifkan form replacement who
                } else {
                    // nothing
                }

                // work location selector
                let input_WLtext = $('input[name="work_location_text"]');
                let input_WLchoose = $('select[name="work_location_choose"]');
                let input_WLtrigger = $('#work_location_otherTrigger');
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
                // variable resource form
                let input_resource = $('input[name="resources"]');
                let input_resource_checked = $('input[name="resources"]:checked');
                let input_resource_internal = $('#internalForm');
                let input_resource_internalwho = $('input[name="internal_who"]');
                $('input[name="resources"][value="'+resources.resources+'"]').attr('checked',true); // select resources
                if(resources.resources == "int"){
                    input_resource_internalwho.slideDown(); // tampilkan input text internal_who
                    input_resource_internalwho.val(resources.internal_who); // tampilkan inpu
                } else if(resources.resources == "ext"){
                    // nothing
                }

                // variabel input name Work Experience
                let input_workexp = $('input[name="work_exp"]');
                let input_workexp_checked = $('input[name="work_exp"]:checked');
                let input_workexp_years = $('#we_years');
                let input_workexp_yearstext = $('input[name="work_exp_years"]');
                // work experience selector
                if(data.data.work_exp > 0) { // cek jika cekbox work experience
                    input_workexp_years.fadeIn(); // tampilkan kotak free text tahun
                    input_workexp_yearstext.val(data.data.work_exp); // set data work experience
                    $('input[name="work_exp"][value="1"]').attr('checked',true); // select work experience
                } else if(data.data.work_exp == 0) { // cek jika cekbox fresh graduate
                    $('input[name="work_exp"][value="0"]').attr('checked',true); // select work experience
                }

                // CKEDITOR set data form
                CKEDITOR.instances['ska'].setData(data.data.req_ska);
                CKEDITOR.instances['req_special'].setData(data.data.req_special);
                CKEDITOR.instances['outline'].setData(data.data.outline);
                CKEDITOR.instances['main_responsibilities'].setData(data.data.main_responsibilities);
                CKEDITOR.instances['tasks'].setData(data.data.tasks);

                // $('.overlay').fadeOut(); // hapus overlay
            }
        })
    });
</script>

<?php $this->load->view('_komponen/ptk/script_validator_ptk'); ?>