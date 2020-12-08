<script>
    function submit_validator(){
        // validate counter and message variable    
        let msg_validate = "<ul>";
        let letScroll = "";
        let counter_validate = 0;

        // Swal.fire({
        //     icon: 'info',
        //     title: 'Checking form validation...',
        //     html: '<p>Validating the form before submitting to the server.<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
        //     showConfirmButton: false,
        //     allowOutsideClick: false,
        //     allowEscapeKey: false,
        //     allowEnterKey: false
        // });

        // cek validasi select form
        $.each(input_select, function(index, value){
            let now_validate = $('select[name="' + value.input + '"]');
            if(now_validate.val() == ""){
                now_validate.addClass('is-invalid'); // add class invalid
                now_validate.parent().append(msg_choose); // show error tooltip
                msg_validate += "<li>"+value.name+" is empty</li>"; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // now_validate.removeClass('is-invalid'); // remove class invalid
                // nothing
            }
        });

        // cek validasi job position
        let msg_validate_jp = "<li>Job Position is empty</li>";
        if($('input[name="budget"]:checked').val() == 0) { // cek jika unbudgeted
            if($(input_jptext).val() == ""){
                $(input_jptext).addClass('is-invalid'); // add class invalid
                $(input_jptext).parent().append(msg_fill); // show error tooltip
                msg_validate += msg_validate_jp; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }
        } else if($('input[name="budget"]:checked').val() == 1 || $('input[name="budget"]:checked').val() == 2) { // cek jika budgeted
            if(input_jpchoose.val() == ""){
                input_jpchoose.addClass('is-invalid'); // add class invalid
                input_jpchoose.parent().append(msg_choose); // show error tooltip
                msg_validate += msg_validate_jp; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }

            if($('input[name="budget"]:checked').val() == 2){
                if(select_replacement_who.val() == ""){
                    select_replacement_who.addClass('is-invalid'); // add class invalid
                    select_replacement_who.parent().append(msg_fill); // show error tooltip
                    msg_validate += "<li>Replacement is empty</li>"; // pesan empty
                    counter_validate++; // validate counter add
                }
            }
        } else {
            input_budget.parent().parent().parent().parent().removeClass('border-gray-light').addClass('border-danger');
            input_budget.addClass('is-invalid');
            $('#unbudgettedRadio').parent().append(msg_choose);
        }

        // validate work location
        let msg_validate_wl = "<li>Work Location is empty</li>"
        if(input_WLtrigger.prop('checked') == true) {
            // jika diceklis, tampilkan input free text work location
            if($(input_WLtext).val() == ""){
                $(input_WLtext).addClass('is-invalid'); // add class invalid
                $(input_WLtext).parent().append(msg_fill); // show error tooltip
                msg_validate += msg_validate_wl; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }
        } else if(input_WLtrigger.prop('checked') == false) {
            // jika tidak diceklis, tampilkan pilihan work location
            if(input_WLchoose.val() == ""){
                input_WLchoose.addClass('is-invalid'); // add class invalid
                input_WLchoose.parent().append(msg_choose); // show error tooltip
                msg_validate += msg_validate_wl; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }
        }

        // validate resource form
        if($('input[name="resources"]:checked').val() == "int") {
            if(input_resource_internalwho.val() == ""){
                input_resource_internalwho.addClass('is-invalid'); // add class invalid
                input_resource_internalwho.parent().append(msg_fill); // show error tooltip
                msg_validate += "<li>Please type an employee name</li>"; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }
        } else if($('input[name="resources"]:checked').val() == "ext"){
            // nothing
        } else {
            input_resource_internal.parent().parent().parent().addClass('border border-danger');
            input_resource_internal.addClass('is-invalid');
            input_resource_internal.parent().append(msg_choose);
            msg_validate += "<li>Resource is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        }

        // validate Man Power Required
        if(input_mpp.val() == ""){
            input_mpp.addClass('is-invalid'); // add class invalid
            input_mpp.parent().append(msg_number); // show error tooltip
            msg_validate += "<li>Man Power Required is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            // nothing
        }

        // cek apa man power requirednya lebih besar dari Number of Incumbent
        let mpp = input_mpp.val();
        let noiReq = $('#noiReq').data('empty');
        if(noiReq != '-'){
            if(mpp > 0 && mpp <= noiReq){
            // nothing
            } else {
                // cek apa budgetnya unbudgetted
                if($('input[name="budget"]:checked').val() != 0){
                    input_mpp.addClass('is-invalid'); // tambah kelas invalid
                    input_mpp.parent().append('<div class="invalid-tooltip">The man power required that you input should be in range one to less or equal to number of incumbent.</div>'); // show error tooltip
                    msg_validate += "<li>Man Power Required is more higher than Number of incumbent.</li>"; // pesan empty
                    counter_validate++; // validate counter add
                }
            }
        }

        // validate Date Required
        if(input_daterequired.val() == ""){
            input_daterequired.addClass('is-invalid'); // tambah kelas invalid
            input_daterequired.parent().append(msg_fill); // tampilkan pesan error
            msg_validate += "<li>Date Required is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            // nothing
        }

        // validate empstats
        if(validate_empstats.val() == ""){
            validate_empstats.addClass('is-invalid'); // tambah kelas invalid
            validate_empstats.parent().append(msg_fill); // tampilkan pesan error
            msg_validate += "<li>empstats is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        }
        if(validate_empstats.val() == "emp_stats-3"){
            if(select_temporary.val() == ""){
                select_temporary.addClass('is-invalid'); // tambah kelas invalid
                select_temporary.parent().append(msg_fill); // tampilkan pesan error
                msg_validate += "<li>Temporary Month is empty</li>"; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }
        }

        // validate education
        if(validate_education.val() == ""){
            validate_education.addClass('is-invalid'); // tambah kelas invalid
            validate_education.parent().append(msg_fill); // tampilkan pesan error
            msg_validate += "<li>empstats is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        }

        // validate sex
        // if(validate_sex.val() == ""){
        //     validate_sex.addClass('is-invalid'); // tambah kelas invalid
        //     validate_sex.parent().append(msg_fill); // tampilkan pesan error
        //     msg_validate += "<li>empstats is empty</li>"; // pesan empty
        //     counter_validate++; // validate counter add
        // }

        // validate majoring
        if(input_majoring.val() == ""){
            input_majoring.addClass('is-invalid'); // tambah kelas invalid
            input_majoring.parent().append(msg_fill); // tampilkan pesan error
            msg_validate += "<li>Majoring is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            // nothing
        }

        // validate preferred age
        if(input_preferage.val() == ""){
            input_preferage.addClass('is-invalid'); // add class invalid
            input_preferage.parent().append(msg_fill); // show error tooltip
            msg_validate += "<li>Preferred Age is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            // nothing
        }

        // validate work experience
        if($('input[name="work_exp"]:checked').val() == 1) { // cek jika cekbox work experience
            if(input_workexp_yearstext.val() == ""){
                input_workexp_yearstext.addClass('is-invalid'); // add class invalid
                input_workexp_yearstext.parent().append(msg_number); // show error tooltip
                msg_validate += "<li>Work Experience Years is empty</li>"; // pesan empty
                counter_validate++; // validate counter add
            }
            if(input_workexp_at.val() == ""){
                input_workexp_at.addClass('is-invalid'); // add class invalid
                input_workexp_at.parent().append(msg_fill); // tambahkan pesan msg_fill
                msg_validate += "<li>Work Experience At is empty</li>"; // pesan empty
                counter_validate++; // validate counter add
            }
        } else if($('input[name="work_exp"]:checked').val() == 0) { // cek jika cekbox fresh graduate
            input_workexp_yearstext.val('0'); // set value sama dengan nol
        } else {
            input_workexp.parent().parent().parent().parent().parent().addClass('border border-danger');
            input_workexp.addClass('is-invalid');
            $('#experiencedRadio').parent().append(msg_choose);
            msg_validate += "<li>Work Experience not choosen</li>"; // pesan empty
            counter_validate++; // validate counter add
        }

        // take data of ckeditor data
        let textarea_ska = CKEDITOR.instances['ska'].getData();
        let textarea_reqspecial = CKEDITOR.instances['req_special'].getData();
        let textarea_outline = CKEDITOR.instances['outline'].getData();
        let textarea_mainrespon = CKEDITOR.instances['main_responsibilities'].getData();
        let textarea_tasks = CKEDITOR.instances['tasks'].getData();
        
        // validate skill, knowledge, and abilities
        let textarea_selector_ska = $('textarea#ska');
        if(textarea_ska == ""){
            textarea_selector_ska.parent().parent().parent().addClass('border border-danger');
            msg_validate += "<li>Skill, Knowledge, and Abilities is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            textarea_selector_ska.parent().parent().parent().removeClass('border border-danger');
        }

        // validate special requirement
        // let textarea_selector_reqspecial = $('textarea#req_special');
        // if(textarea_reqspecial == ""){
        //     textarea_selector_reqspecial.parent().parent().parent().addClass('border border-danger');
        //     msg_validate += "<li>Special Requirement is empty</li>"; // pesan empty
        //     counter_validate++; // validate counter add
        // } else {
        //     textarea_selector_reqspecial.parent().parent().parent().removeClass('border border-danger');
        // }

        // validate outline textarea
        let textarea_selector_outline = $('textarea#outline');
        if(textarea_outline == ""){
            textarea_selector_outline.parent().parent().parent().addClass('border border-danger');
            msg_validate += "<li>Outline is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            textarea_selector_outline.parent().parent().parent().removeClass('border border-danger');
        }

        // validate main responsibilities textarea
        let textarea_selector_mainrespon = $('textarea#main_responsibilities');
        if(textarea_mainrespon == ""){
            textarea_selector_mainrespon.parent().parent().parent().addClass('border border-danger');
            msg_validate += "<li>Main Responsibilities is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            textarea_selector_mainrespon.parent().parent().parent().removeClass('border border-danger');
        }

        // validate tasks textarea
        let textarea_selector_tasks = $('textarea#tasks');
        if(textarea_tasks == ""){
            textarea_selector_tasks.parent().parent().parent().addClass('border border-danger');
            msg_validate += "<li>Tasks is empty</li>"; // pesan empty
            counter_validate++; // validate counter add
        } else {
            textarea_selector_tasks.parent().parent().parent().removeClass('border border-danger');
        }

        // validate jika nama atau posisi di interviewer terisi
        if(input_interviewer_name.val() != ""){
            if(input_interviewer_position.val() == ""){
                input_interviewer_position.addClass('is-invalid'); // tambah kelas invalid
                input_interviewer_position.parent().append(msg_fill); // tampilkan pesan error
                msg_validate += "<li>Interviewer Position 3 is empty</li>"; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }
        } else {
            if(input_interviewer_position.val() != ""){
                if(input_interviewer_name.val() == ""){
                    input_interviewer_name.addClass('is-invalid'); // tambah kelas invalid
                    input_interviewer_name.parent().append(msg_fill); // tampilkan pesan error
                    msg_validate += "<li>Interviewer Name 3 is empty</li>"; // pesan empty
                    counter_validate++; // validate counter add
                } else {
                    // nothing
                }
            }
        }
        // validate jika nama atau posisi di interviewer terisi
        if(input_interviewer_name2.val() != ""){
            if(input_interviewer_position2.val() == ""){
                input_interviewer_position2.addClass('is-invalid'); // tambah kelas invalid
                input_interviewer_position2.parent().append(msg_fill); // tampilkan pesan error
                msg_validate += "<li>Interviewer Position 4 is empty</li>"; // pesan empty
                counter_validate++; // validate counter add
            } else {
                // nothing
            }
        } else {
            if(input_interviewer_position2.val() != ""){
                if(input_interviewer_name2.val() == ""){
                    input_interviewer_name2.addClass('is-invalid'); // tambah kelas invalid
                    input_interviewer_name2.parent().append(msg_fill); // tampilkan pesan error
                    msg_validate += "<li>Interviewer Name 4 is empty</li>"; // pesan empty
                    counter_validate++; // validate counter add
                } else {
                    // nothing
                }
            }
        }

        // tutup list error message validate
        msg_validate += "</ul>";

        // balikkan nilai
        return [counter_validate, msg_validate];
    }
</script>