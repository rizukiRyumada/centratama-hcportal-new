<script>
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
        // {input: "job_level", name: "Job Level"},
        // {input: "emp_stats", name: "Status of Employement"},
        // {input: "education", name: "Education"},
        // {input: "sex", name: "Sex"}
    ];

    // Job Position Selector
    var input_jptext = $('input[name="job_position_text"]'); // selector job position text
    var input_jpchoose = $('select#positionInput'); // selector job position text
    var input_budget = $('input[name="budget"]');
    var input_budget_checked = $('input[name="budget"]:checked');

    // work location selector
    var input_WLtext = $('input[name="work_location_text"]');
    var input_WLchoose = $('select[name="work_location_choose"]');
    var input_WLtrigger = $('#work_location_otherTrigger');

    // replacement variable
    var select_replacement_who = $('select[name="replacement_who"]');
    
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
    var input_workexp_at = $('input[name="work_exp_at"]');
    var input_workexp_at_container = $('#experienced_at');

    // validation entity
    var validate_entity = $('select[name="' + input_select[0].input + '"]');

    // validation job_level
    var select_jobLevel = $('select[name="job_level"]');
    var select_jobLevel_view = $('#jobLevelSelectView');

    // variable man power required
    var input_mpp = $('input[name="mpp_req"]');

    // validation emp_stats
    var validate_empstats = $('select[name="emp_stats"]');
    var select_temporary = $('select[name="temporary_month"]');
    var select_temporary_container = $('#temporary_month_container');

    // validation education
    var validate_education = $('select[name="education"]');

    // prefer age
    var input_preferage = $('input[name="preferred_age"]');

    // validation sex
    var validate_sex = $('select[name="sex"]');

    // validate Date Required
    var input_daterequired = $('input[name="date_required"]');

    // validate Majoring
    var input_majoring = $('input[name="majoring"]');

    // validate interviewer
    var input_interviewer_name = $('#interviewer_name3');
    var input_interviewer_position = $('#interviewer_position3');
    var input_interviewer_name2 = $('#interviewer_name4');
    var input_interviewer_position2 = $('#interviewer_position4');

    // division, department, and position filter and validator
    var select_department = $('#departementForm');
    var select_divisi = $('#divisionForm');

    // variable for ckeditor
    var cke_ska = "";
    var cke_req_special = "";
    var cke_outline = "";
    var cke_main_responsibilities = "";
    var cke_tasks = "";

    // $(".select2").select2({
    //     theme: 'bootstrap4'
    // });
</script>