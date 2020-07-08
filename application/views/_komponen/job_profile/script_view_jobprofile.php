<script>
//masukkin data ke variabel javascript dari php

var assistant_atasan1 = <?= $assistant_atasan1; ?>;
var atasan = <?= $atasan; ?>;
var datasource = <?php echo $orgchart_data; ?>; 
var datasource_assistant1 = <?php echo($orgchart_data_assistant1); ?>;
var datasource_assistant2 = <?php echo($orgchart_data_assistant2); ?>;

$(document).ready(function() { //buat nyembunyiin menu user
    $('a[data-target="#collapseUser"]').addClass('d-none');
});
</script>