<!-- <script src="<?php // base_url('/assets/js/iframe-resize/iframeResizer.min.js'); ?>"></script> -->
<script>
    // variable
    var status = "<?= $status_form; ?>";
    var action = "";

    // document ready function
    $(document).ready(function() {
        CKEDITOR.replace( 'textareaPesanRevisi' );
    });
</script>

<!-- script modal pesan -->
<?php $this->load->view('_komponen/ptk/script_modalPesan_ptk'); ?>