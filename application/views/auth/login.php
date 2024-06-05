<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Login | Skote - Responsive Bootstrap 4 Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
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
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-soft-primary">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h5 class="text-primary">Selamat Datang !</h5>
                                        <p>Silahkan login untuk melanjutkan.</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="<?= $this->config->item('theme_url') ?>images/profile-img.png" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div>
                                <a href="index.html">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="<?= $this->config->item('theme_url') ?>images/logo.svg" alt="" class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2">
                                <form class="form-horizontal" action="<?= site_url('auth/process') ?>" method="post">
                                    <?php if (!empty($this->session->flashdata('error_messages'))) : ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="mdi mdi-block-helper mr-2"></i>
                                            <?= $this->session->flashdata('error_messages') ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php endif ?>

                                    <input type="hidden" name="<?= $csrf['token_name'] ?>" value="<?= $csrf['hash'] ?>">

                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" name="username" placeholder="Enter username">
                                    </div>

                                    <div class="form-group">
                                        <label for="userpassword">Password</label>
                                        <input type="password" class="form-control" name="password" placeholder="Enter password">
                                    </div>

                                    <div class="mt-3">
                                        <button class="btn btn-primary btn-block waves-effect waves-light" type="submit">Log In</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="mt-5 text-center">

                        <div>
                            <p>© <script>
                                    document.write(new Date().getFullYear())
                                </script> Skote. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand</p>
                        </div>
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

    <!-- App js -->
    <script src="<?= $this->config->item('theme_url') ?>js/app.js"></script>
</body>

</html>