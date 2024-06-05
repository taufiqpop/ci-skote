<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Coming Soon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="CI SKOTE" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= $this->config->item('theme_url') ?>images/favicon.ico">

    <!-- Bootstrap Css -->
    <link href="<?= $this->config->item('theme_url') ?>css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?= $this->config->item('theme_url') ?>css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= $this->config->item('theme_url') ?>css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body>
    <div class="home-btn d-none d-sm-block">
        <a href="<?= site_url(strtolower($this->session->userdata('role_name')) . '/dashboard/') ?>" class="text-white"><i class="fas fa-home h2"></i></a>
    </div>

    <div class="my-5 pt-sm-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <a href="<?= site_url(strtolower($this->session->userdata('role_name')) . '/dashboard/') ?>">
                            <img src="<?= $this->config->item('theme_url') ?>images/logo-dark.png" alt="logo" height="24" />
                        </a>
                        <div class="row justify-content-center mt-5">
                            <div class="col-sm-4">
                                <div class="maintenance-img">
                                    <img src="<?= $this->config->item('theme_url') ?>images/maintenance.png" alt="" class="img-fluid mx-auto d-block">
                                </div>
                            </div>
                        </div>
                        <h4 class="mt-5">Halaman ini sedang masa pengembangan.</h4>
                        <p class="text-muted">Nanti ada info lagi dari developer untuk perkembangan lebih lanjut.</p>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="<?= $this->config->item('theme_url') ?>libs/jquery/jquery.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/metismenu/metisMenu.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/simplebar/simplebar.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/node-waves/waves.min.js"></script>

    <!-- Plugins js-->
    <script src="<?= $this->config->item('theme_url') ?>libs/jquery-countdown/jquery.countdown.min.js"></script>

    <!-- Countdown js -->
    <script src="<?= $this->config->item('theme_url') ?>js/pages/coming-soon.init.js"></script>

    <script src="<?= $this->config->item('theme_url') ?>js/app.js"></script>

</body>

</html>