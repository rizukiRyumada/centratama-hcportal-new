<script src="<?= base_url('/assets/js/datepicker/js/bootstrap-datepicker.min.js'); ?>"></script>
<script>
    // settings script
    $('.pickadate').datepicker({
        format: "dd-mm-yyyy",
        weekStart: 1,
        startView: 1,
        multidate: false,
        daysOfWeekDisabled: "0,6",
        daysOfWeekHighlighted: "0,6",
        autoclose: true,
        todayHighlight: true
    });
</script>