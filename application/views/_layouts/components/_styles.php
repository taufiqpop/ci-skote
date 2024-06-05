<link href="<?= $this->config->item('theme_url') ?>libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
<style>
    .datepicker {
        z-index: 99999999 !important;
    }
</style>
<!-- Sweet Alert-->
<link href="<?= $this->config->item('theme_url') ?>libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<?php if (in_array('datatable', $plugins)) : ?>
    <!-- DataTables -->
    <link href="<?= $this->config->item('theme_url') ?>libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= $this->config->item('theme_url') ?>libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="<?= $this->config->item('theme_url') ?>libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<?php endif ?>
<?php if (in_array('form_wizard', $plugins)) : ?>
    <!-- twitter-bootstrap-wizard css -->
    <link rel="stylesheet" href="<?= $this->config->item('theme_url') ?>libs/twitter-bootstrap-wizard/prettify.css">
<?php endif ?>
<?php if (in_array('leaflet', $plugins)) : ?>
    <!-- leaflet Css -->
    <link href="<?= $this->config->item('theme_url') ?>libs/leaflet/leaflet.css" rel="stylesheet" type="text/css" />
<?php endif ?>
<?php if (in_array('lightbox', $plugins)) : ?>
    <!-- Lightbox css -->
    <link href="<?= $this->config->item('theme_url') ?>libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />
<?php endif ?>
<?php if (in_array('select2', $plugins)) : ?>
    <link href="<?= $this->config->item('theme_url') ?>libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<?php endif ?>
<?php if (in_array('tui_chart', $plugins)) : ?>
    <!-- tui charts Css -->
    <link href="<?= $this->config->item('theme_url') ?>libs/tui-chart/tui-chart.min.css" rel="stylesheet" type="text/css" />
<?php endif ?>
<?php if (in_array('leaflet', $plugins)) :  ?>
    <link rel="stylesheet" href="<?= $this->config->item('front_url') ?>css/leaflet/leaflet.css">
<?php endif ?>
<?php if (in_array('timepicker', $plugins)) : ?>
    <link href="<?= $this->config->item('theme_url') ?>libs/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css">
<?php endif ?>

<?php if (in_array('touchspin', $plugins)) : ?>
    <link href="<?= $this->config->item('theme_url') ?>libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" type="text/css" />
<?php endif ?>