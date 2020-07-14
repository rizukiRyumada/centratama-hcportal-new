<!-- orgchart -->
<script src="<?= base_url('assets/'); ?>vendor/node_modules/orgchart/dist/js/jquery.orgchart.min.js"></script>
<!-- <script src="<?php base_url(); ?>assets/js/orgchart/jquery.orgchart.min.js"></script> -->
<!-- <script src="<?php base_url(); ?>assets/js/orgchart/jquery.orgchart.min.js"></script> -->
<script src="<?= base_url('assets/'); ?>vendor/node_modules/html2canvas/dist/html2canvas.min.js"></script>
<script src="<?= base_url('assets/'); ?>js/dom-to-image.min.js"></script>

<script src="<?= base_url('assets/'); ?>vendor/jquery-tabledit/jquery.tabledit.min.js"></script>
<script src="<?= base_url('assets/'); ?>js/chartorg.js"></script>

<script>
    var currentZoom = parseFloat($('#chart-container').css('zoom'));

    // $('#zoomOut').on('click', function () {
    //     $('.orgchart').css('zoom', currentZoom -= 0.1);
    //     console.log('got it');
    // });
    // $('#zoomIn').on('click', function () {
    //     $('.orgchart').css('zoom', currentZoom += 0.1);
    // });

    $('#zoomOut').on('click',function(){
        var e = new WheelEvent("wheel", {deltaY: 100});
        document.getElementById('chart-container').dispatchEvent(e);
    });
    $('#zoomIn').on('click',function(){
        var e = new WheelEvent("wheel", {deltaY: -100});
        document.getElementById('chart-container').dispatchEvent(e);
        console.log('oke');
    });
</script>