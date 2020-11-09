<link rel="stylesheet" href="<?= base_url('/assets/vendor/node_modules/knockout-daterangepicker/dist/daterangepicker.min.css'); ?>">
<script src="<?= base_url('/assets/js/knockout-3.5.1.js'); ?>"></script>
<script src="<?= base_url('/assets/vendor/node_modules/moment/dist/moment.js'); ?>"></script>
<script src="<?= base_url('assets/vendor/node_modules/knockout-daterangepicker/dist/daterangepicker.min.js'); ?>"></script>

<script>
    $("#superdatepicker").daterangepicker({
        minDate: moment().subtract(2, 'years')
    }, function (startDate, endDate, period) {
        $(this).val(startDate.format('L') + ' – ' + endDate.format('L'))
    });
</script>