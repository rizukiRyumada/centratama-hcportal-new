<script src="<?= base_url('/assets/vendor/node_modules/daterangepicker/daterangepicker.js'); ?>"></script>
<link rel="stylesheet" href="<?= base_url('/assets/vendor/node_modules/daterangepicker/daterangepicker.css'); ?>">

<script>
    $('input[name="daterange"]').daterangepicker({
        "showDropdowns": true,
        "minYear": 1989,
        "maxYear": 2580,
        "showWeekNumbers": true,
        "showISOWeekNumbers": true,
        "autoApply": true,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "alwaysShowCalendars": true,
        "startDate": "<?= date('m/d/Y', strtotime("-6 Days")) ?>",
        "endDate": "<?= date('m/d/Y', time()); ?>",
        "minDate": "YYYY-MM-DD",
        "maxDate": "YYYY-MM-DD",
        "drops": "auto",
        "applyButtonClasses": "btn-success"
    }, function(start, end, label) {
        console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });

    // $('.datepicker').daterangepicker({
    //     autoUpdateInput: false,
    //     "singleDatePicker": true,
    //     "showDropdowns": true,
    //     "minYear": 1989,
    //     "maxYear": 2580,
    //     "showWeekNumbers": true,
    //     "showISOWeekNumbers": true,
    //     "autoApply": false,
    //     // "startDate": "<?= date('m/d/Y', time()); ?>",
    //     // "endDate": "<?= date('m/d/Y', time()); ?>",
    //     "drops": "auto",
    //     "opens": "center"
    // }, function(start, end, label) {
    //     console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    // });

    // $('input[name="datefilter"]').daterangepicker({
    //     autoUpdateInput: false,
    //     locale: {
    //         cancelLabel: 'Clear'
    //     }
    // });
</script>