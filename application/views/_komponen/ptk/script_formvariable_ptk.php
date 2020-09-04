<script>
    // CKEDITOR Instances
    CKEDITOR.replace( 'ska' );
    CKEDITOR.replace( 'req_special' );
    CKEDITOR.replace( 'outline' );
    CKEDITOR.replace( 'main_responsibilities' );
    CKEDITOR.replace( 'tasks' );

    /* -------------------------------------------------------------------------- */
    /*                            form input variables                            */
    /* -------------------------------------------------------------------------- */

    // message validation
    var choose = "Please choose one.";
    var fill   = "This field is required.";
    var number = "The input is required and should be number.";

    // tooltip validation
    var msg_choose = '<div class="invalid-tooltip">'+choose+'</div>' ;
    var msg_fill   = '<div class="invalid-tooltip">'+fill+'</div>' ;
    var msg_number = '<div class="invalid-tooltip">'+number+'</div>'

    var input_select = [
        {input: "entity", name: "Entity"},
        {input: "job_level", name: "Job Level"},
        {input: "emp_stats", name: "Status of Employement"},
        {input: "education", name: "Education"},
        {input: "sex", name: "Sex"}
    ];

    // Job Position Selector
    var input_jptext = $('input[name="job_position_text"]'); // selector job position text
    var input_jpchoose = $('select[name="job_position_choose"]'); // selector job position text
    var input_budget = $('input[name="budget"]');
    var input_budget_checked = $('input[name="budget"]:checked');

    // work location selector
    var input_WLtext = $('input[name="work_location_text"]');
    var input_WLchoose = $('select[name="work_location_choose"]');
    var input_WLtrigger = $('#work_location_otherTrigger');

    // replacement variable
    var input_replacement = $('input[name="replacement"]');
    var input_replacement_who = $('input[name="replacement_who"]');
    
    // variable resource form
    var input_resource = $('input[name="resources"]');
    var input_resource_checked = $('input[name="resources"]:checked');
    var input_resource_internal = $('#internalForm');
    var input_resource_internalwho = $('input[name="internal_who"]');
    
    // variabel input name Work Experience
    var input_workexp = $('input[name="work_exp"]');
    var input_workexp_checked = $('input[name="work_exp"]:checked');
    var input_workexp_years = $('#we_years');
    var input_workexp_yearstext = $('input[name="work_exp_years"]');

    // validation entity
    var validate_entity = $('select[name="' + input_select[0].input + '"]');

    // validation job_level
    let validate_job_level = $('select[name="' + input_select[1].input + '"]');

    // validation emp_stats
    let validate_empstats = $('select[name="' + input_select[2].input + '"]');

    // validation education
    let validate_education = $('select[name="' + input_select[3].input + '"]');

    // validation sex
    let validate_sex = $('select[name="' + input_select[4].input + '"]');

    // validate Date Required
    var input_daterequired = $('input[name="date_required"]');

    // validate Majoring
    var input_majoring = $('input[name="majoring"]');

    // validate interviewer
    var input_interviewer_name = $('#interviewer_name3');
    var input_interviewer_position = $('#interviewer_position3');

</script>