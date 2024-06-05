<script src="<?= $this->config->item('theme_url') ?>libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<!-- Sweet Alerts js -->
<script src="<?= $this->config->item('theme_url') ?>libs/sweetalert2/sweetalert2.min.js"></script>
<?php if (in_array('datatable', $plugins)) : ?>
    <!-- Required datatable js -->
    <script src="<?= $this->config->item('theme_url') ?>libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="<?= $this->config->item('theme_url') ?>libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/jszip/jszip.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

    <!-- Responsive examples -->
    <script src="<?= $this->config->item('theme_url') ?>libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <script>
        $('body').on('draw.dt', function(e, ctx) {
            var api = new $.fn.dataTable.Api(ctx);

            $('[data-toggle="tooltip"]').tooltip({
                trigger: 'hover'
            })
            $('[data-toggle="tooltip"]').tooltip('hide');
            $(document).on('click', '[rel="tooltip"]', function() {
                $(this).tooltip('hide')
            })


            // $('[data-toggle="tooltip"]').tooltip('hide');
        });
    </script>
<?php endif ?>
<?php if (in_array('form_wizard', $plugins)) : ?>
    <!-- twitter-bootstrap-wizard js -->
    <script src="<?= $this->config->item('theme_url') ?>libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

    <script src="<?= $this->config->item('theme_url') ?>libs/twitter-bootstrap-wizard/prettify.js"></script>
<?php endif ?>
<?php if (in_array('leaflet', $plugins)) : ?>
    <!-- leaflet plugin -->
    <script src="<?= $this->config->item('theme_url') ?>libs/leaflet/leaflet.js"></script>
<?php endif ?>
<?php if (in_array('apex_chart', $plugins)) : ?>
    <!-- apexcharts -->
    <script src="<?= $this->config->item('theme_url') ?>libs/apexcharts/apexcharts.min.js"></script>
<?php endif ?>
<?php if (in_array('lightbox', $plugins)) : ?>
    <!-- Magnific Popup-->
    <script src="<?= $this->config->item('theme_url') ?>libs/magnific-popup/jquery.magnific-popup.min.js"></script>
<?php endif ?>
<?php if (in_array('select2', $plugins)) : ?>
    <script src="<?= $this->config->item('theme_url') ?>libs/select2/js/select2.min.js"></script>
<?php endif ?>
<?php if (in_array('tui_chart', $plugins)) : ?>
    <!-- tui charts plugins -->
    <script src="<?= $this->config->item('theme_url') ?>libs/tui-chart/tui-chart-all.min.js"></script>
<?php endif ?>
<?php if (in_array('leaflet', $plugins)) : ?>
    <script src="<?= $this->config->item('front_url') ?>js/leaflet/leaflet.js"></script>
    <script src="<?= $this->config->item('front_url') ?>js/leaflet/leaflet-esri.js"></script>
    <script src="<?= $this->config->item('front_url') ?>js/leaflet/leaflet.ajax.js"></script>
<?php endif ?>
<?php if (in_array('timepicker', $plugins)) : ?>
    <script src="<?= $this->config->item('theme_url') ?>libs/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<?php endif ?>
<?php if (in_array('chart_js', $plugins)) : ?>
    <!-- Chart JS -->
    <script src="<?= $this->config->item('theme_url') ?>libs/chart.js/Chart.bundle.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>js/pages/chartjs.init.js"></script>
<?php endif ?>
<?php if (in_array('moment_js', $plugins)) : ?>
    <!-- Moment JS -->
    <script src="<?= $this->config->item('theme_url') ?>libs/moment/min/moment.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/moment/locale/id.js"></script>
<?php endif ?>
<?php if (in_array('lodash', $plugins)) : ?>
    <!-- Lodash JS -->

<?php endif ?>
<?php if (in_array('touchspin', $plugins)) : ?>
    <script src="<?= $this->config->item('theme_url') ?>libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
<?php endif ?>