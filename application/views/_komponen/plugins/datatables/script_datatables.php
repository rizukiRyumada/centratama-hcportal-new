<script src="<?= base_url('/assets/vendor/node_modules/jszip/dist/jszip.min.js'); ?>"></script>
<script src="<?= base_url('/assets/vendor/node_modules/datatables.net/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('/assets/vendor/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
<script src="<?= base_url('/assets/vendor/node_modules/datatables.net-buttons/js/dataTables.buttons.min.js'); ?>"></script>
<script src="<?= base_url('/assets/vendor/node_modules/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js'); ?>"></script>
<script src="<?= base_url('/assets/vendor/node_modules/datatables.net-buttons/js/buttons.html5.min.js'); ?>"></script>
<script src="<?= base_url('/assets/vendor/node_modules/datatables.net-responsive/js/dataTables.responsive.min.js'); ?>"></script>
<script src="<?= base_url('/assets/vendor/node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js'); ?>"></script>
<script src="<?= base_url('/assets/vendor/node_modules/datatables.net-fixedcolumns/js/dataTables.fixedColumns.min.js'); ?>"></script>
<script src="<?= base_url('/assets/vendor/node_modules/datatables.net-fixedcolumns-bs4/js/fixedColumns.bootstrap4.min.js'); ?>"></script>
<script>
    var table = $("#mainTable").DataTable({
      responsive: true,
      autoWidth: false,
      buttons: [
            {
              text: '<?php if($this->session->userdata('survey_status') == 1){echo"Activate Export Mode";}else{echo"Deactivate Export Mode";} ?>',
              action: function ( e, dt, button, config ) {
                window.location = '<?= base_url('survey/settings_printModeTable'); ?>?url=survey/settings_status';
              }        
            }
        ]
    });
    
    $("#basicTable").DataTable({
      responsive: true,
      autoWidth: false,
      searching: false,
      paging: false,
      info: false,
      buttons: [
            {
              text: '<?php if($this->session->userdata('survey_status') == 1){echo"Activate Export Mode";}else{echo"Deactivate Export Mode";} ?>',
              action: function ( e, dt, button, config ) {
                window.location = '<?= base_url('survey/settings_printModeTable'); ?>?url=survey/settings_status';
              }        
            }
        ]
    });

    table.buttons().container()
    .appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );
</script>