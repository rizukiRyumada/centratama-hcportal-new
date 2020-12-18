<script src="<?= base_url('/assets/vendor/node_modules/chart.js/dist/Chart.bundle.min.js'); ?>"></script>
<script src="<?= base_url('/assets/vendor/node_modules/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js'); ?>"></script>

<script>
    // Change default options for ALL charts
    Chart.helpers.merge(Chart.defaults.global.plugins.datalabels, {
        color: '#fff',
        backgroundColor: 'rgb(111,111,111)',
        borderRadius: 2
    });
</script>