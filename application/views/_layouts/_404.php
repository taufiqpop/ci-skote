<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>404</title>
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

    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5">
                        <h1 class="display-2 font-weight-medium">4<i class="bx bx-buoy bx-spin text-primary display-3"></i>4</h1>
                        <h4 class="text-uppercase">Maaf halaman yang anda tuju tidak ditemukan</h4>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8 col-xl-6">
                    <div>
                        <img src="<?= $this->config->item('theme_url') ?>images/error-img.png" alt="" class="img-fluid">
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

    <script src="<?= $this->config->item('theme_url') ?>js/app.js"></script>

</body>

</html>