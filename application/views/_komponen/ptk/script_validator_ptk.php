<script>
    // Job Position Selector
    var input_jptext = $('input[name="job_position_text"]'); // selector job position text
    var input_jpchoose = $('select[name="job_position_choose"]'); // selector job position text
    var input_budget = $('input[name="budget"]');
    var input_budget_checked = $('input[name="budget"]:checked');
    // Job Position Form trigger from budget radio button
    input_budget.on('change', function() {
        $("#budgetAlert").hide(); // sembunyikan overlay job position alert
        // setting buat jquery validate
        input_budget.parent().parent().parent().parent().addClass('border-gray-light').removeClass('border-danger');
        input_budget.removeClass('is-invalid');
        $('#unbudgettedRadio').siblings('.invalid-tooltip').remove(); // hapus tooltip invalid 
        
        console.log($('input[name="budget"]:checked').val());
        
        if($('input[name="budget"]:checked').val() == 0) { // cek jika unbudgeted
            input_jptext.fadeIn(); // tampilkan free text buat nulis nama job 
            input_jpchoose.hide(); // sembunyikan pilihan posisi job
            $('#positionInput').prop('selectedIndex',0);// kembalikan status ke default - position chooser

            // sembunyikan tab job Profile dan orgChart
            $('#tab_jobProfile').fadeOut();
            $('#tab_orgChart').fadeOut();

            // remove valid and invalid class
            input_jpchoose.removeClass('is-valid').removeClass('is-invalid'); // remove class invalid
            input_jpchoose.siblings('.invalid-tooltip').remove(); // remove class invalid
        } else if($('input[name="budget"]:checked').val() == 1) { // cek jika budgeted
            input_jpchoose.fadeIn(); // tampilkan pilihan job position 
            input_jptext.hide(); // sembunyikan free text job profile
            input_jptext.val(''); // kosongkan kotak job_position_text

            // remove valid and invalid class
            input_jptext.removeClass('is-valid').removeClass('is-invalid'); // remove class invalid
            input_jptext.siblings('.invalid-tooltip').remove(); // remove class invalid
        }
    });
    // validate job profile free text
    input_jptext.on('keyup', function() {
        input_jptext.removeClass('is-invalid'); // remove class invalid
        // input_jptext.removeClass('is-valid'); // remove class invalid
        input_jptext.siblings('.invalid-tooltip').remove(); // remove class invalid
        if($(this).val() != ""){
            // input_jptext.addClass('is-valid'); // remove class invalid
            input_jptext.siblings('.invalid-tooltip').remove(); // remove class invalid
        } else {
            input_jptext.addClass('is-invalid'); // remove class invalid
            input_jptext.parent().append(msg_fill); // show error tooltip
        }
    });
    // validate job profile chooser
    input_jpchoose.on('change', function() {
        input_jpchoose.removeClass('is-invalid'); // remove class invalid
        input_jpchoose.removeClass('is-valid'); // remove class invalid
        input_jpchoose.siblings('.invalid-tooltip').remove(); // remove class invalid
        if($(this).val() != ""){
            input_jpchoose.addClass('is-valid'); // remove class invalid
            input_jpchoose.siblings('.invalid-tooltip').remove(); // remove class invalid
        } else {
            input_jpchoose.addClass('is-invalid'); // remove class invalid
            input_jpchoose.parent().append(msg_choose); // show error tooltip
        }
    });
</script>