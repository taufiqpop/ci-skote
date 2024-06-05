<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>CI SKOTE <?= isset($title) ? '- ' . $title : '' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="CI SKOTE" name="description" />
    <meta content="Phicosdev" name="author" />

    <meta name="base_url" content="<?= base_url() ?>">
    <meta name="role_name" content="<?= $this->session->userdata('role_name') ?>">

    <meta name="menu_id" content="<?= $menu_id ?>">
    <meta name="menu_active" content="<?= isset($menu_active) ? $menu_active : null ?>">
    <meta name="menu_open" content="<?= isset($menu_open) ? $menu_open : null ?>">

    <meta name="token_name" content="<?= $csrf['name'] ?>">
    <meta name="token_hash" content="<?= $csrf['hash'] ?>">

    <?php foreach ($access as $key => $value) : ?>

        <meta name="<?= $key ?>_access" content="<?= $value ? 1 : 0 ?>">

    <?php endforeach ?>

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= $this->config->item('theme_url') ?>images/favicon.ico">

    <!-- Bootstrap Css -->
    <link href="<?= $this->config->item('theme_url') ?>css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?= $this->config->item('theme_url') ?>css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= $this->config->item('theme_url') ?>css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?= $this->config->item('theme_url') ?>libs/toastr/build/toastr.min.css">

    <?php require('components/_styles.php') ?>
</head>


<body data-sidebar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">


        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="index.html" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="<?= $this->config->item('theme_url') ?>images/logo.svg" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="<?= $this->config->item('theme_url') ?>images/logo-dark.png" alt="" height="17">
                            </span>
                        </a>

                        <a href="index.html" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="<?= $this->config->item('theme_url') ?>images/logo-light.svg" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="<?= $this->config->item('theme_url') ?>images/logo-light.png" alt="" height="19">
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>

                    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect text-center align-middle" id="pilih-tahun" data-toggle="tooltip" data-placement="right" title="Klik untuk ganti periode">
                        <small>Tahun</small> <?= $this->session->userdata('tahun') ?>
                    </button>

                    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" data-toggle="tooltip" data-placement="right" title="Otoritas anda saat ini">
                        <?= ucwords($this->session->userdata('role_name')) ?>
                    </button>
                </div>

                <div class="d-flex">

                    <div class="dropdown d-none d-lg-inline-block ml-1">
                        <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                            <i class="bx bx-fullscreen"></i>
                        </button>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="d-none d-xl-inline-block ml-1" key="t-henry"><?= $this->session->userdata('full_name') ?></span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <!-- item-->
                            <?php if ($this->session->userdata('multirole')) : ?>
                                <a class="dropdown-item" href="<?= site_url('auth/chooseRole/' . $this->session->userdata('id')) ?>"><i class="bx bx-home-circle font-size-16 align-top mr-1"></i> <span key="t-beranda">Ganti Otoritas</span></a>
                                <div class="dropdown-divider"></div>
                            <?php endif ?>
                            <a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#modal-ganti-password"><i class="bx bx-key font-size-16 align-top mr-1"></i> <span key="t-password">Ganti Password</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger link-logout" href="<?= site_url('auth/logout') ?>"><i class="bx bx-power-off font-size-16 align-top mr-1 text-danger"></i> <span key="t-logout">Logout</span></a>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">

            <div data-simplebar class="h-100">

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu"></ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->



        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 font-size-18">CI SKOTE <?= isset($title) ? '- ' . $title : '' ?>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <?= $content ?>

                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php if (!empty($modal)) : ?>
                <?php if (is_array($modal)) : ?>
                    <?php foreach ($modal as $key => $value) : ?>

                        <?= $value ?>

                    <?php endforeach ?>
                <?php else : ?>

                    <?= $modal ?>

                <?php endif ?>
            <?php endif ?>

            <?php $this->load->view('_layouts/components/_modals'); ?>

            <?php $this->load->view('_contents/ganti_password/modal_ganti_password'); ?>


            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <?= date('Y') ?>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-right d-none d-sm-block">
                                <!-- Design & Develop by Themesbrand -->
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->


    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="<?= $this->config->item('theme_url') ?>libs/jquery/jquery.min.js"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
    <script src="<?= base_url('assets/js/validate/jquery.validate.min.js') ?>"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/metismenu/metisMenu.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/simplebar/simplebar.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/node-waves/waves.min.js"></script>
    <script src="<?= $this->config->item('theme_url') ?>libs/toastr/build/toastr.min.js"></script>

    <script src="<?= base_url('assets/js/validate/additional-methods.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/validate/localization/messages_id.min.js') ?>"></script>

    <script src="<?= $this->config->item('theme_url') ?>js/app.js"></script>

    <?php require('components/_scripts.php') ?>

    <script src="<?= base_url('assets/js/main.js?q=' . time()) ?>"></script>
    <script src="<?= base_url('assets/js/menu.js?q=' . time()) ?>"></script>
    <script src="<?= base_url('assets/js/ganti_password.js?q=' . time()) ?>"></script>
    <script src="<?= base_url('assets/js/wilayah.js?q=' . time()) ?>"></script>
    <script src="<?= base_url('assets/js/modals.js?q=' . time()) ?>"></script>

    <?= $javascript ?>

    <?php if (!empty($script_js)) : ?>
        <?php if (is_array($script_js)) : ?>
            <?php foreach ($script_js as $js) : ?>
                <script src="<?= $js ?>?q=<?= time() ?>"></script>
            <?php endforeach ?>
        <?php else : ?>
            <script src="<?= $script_js ?>?q=<?= time() ?>"></script>
        <?php endif ?>
    <?php endif ?>
</body>

</html>