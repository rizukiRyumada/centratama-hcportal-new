<script src="<?= base_url('/assets/vendor/node_modules/xlsx/dist/xlsx.full.min.js'); ?>"></script>
<script src="<?= base_url('/assets/vendor/node_modules/file-saverjs/FileSaver.min.js'); ?>"></script>
<script src="<?= base_url('/assets/vendor/node_modules/tableexport/dist/js/tableexport.js'); ?>"></script>
<script>
    // NOTE NEED 
    // var excelFileName = "for title of document"
    $("table").tableExport({
        headers: true,
        footers: false,
        formats: ['xlsx', 'csv'],
        filename: excelFileName+'-<?= date("dmY-Hi"); ?>',
        bootstrap: false,
        exportButtons: true,
        position: 'bottom',
        ignoreRows: null,
        ignoreCols: null,
        trimWhitespace: true,
        RTL: false,
        sheetname: 'RSS'
    });
</script>