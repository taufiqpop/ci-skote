<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Lock screen | Skote - Responsive Bootstrap 4 Admin Dashboard</title>
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
                                        <h5 class="text-primary">Pilih Otoritas Anda</h5>
                                        <p>Pilih otoritas yang ingin anda akses!</p>
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
                                <form class="form-horizontal" id="form-choose-role" action="<?= site_url('auth/choose') ?>" method="post">
                                    <input type="hidden" name="<?= $csrf['name'] ?>" value="<?= $csrf['hash'] ?>">
                                    <input type="hidden" name="user_id" id="user_id" value="<?= encode_id($user_data->id) ?>">

                                    <div class="user-thumb text-center mb-4">
                                        <img src="<?= base_url('assets/img/user icon.png') ?>" class="rounded-circle img-thumbnail avatar-md" alt="thumbnail">
                                        <h5 class="font-size-15 mt-3"><?= $user_data->full_name ?></h5>
                                    </div>

                                    <div class="form-group">
                                        <label for="role_id">Otoritas</label>
                                        <select name="role_id" id="role_id" class="form-control">
                                            <option value="" selected disabled>Pilih Otoritas</option>
                                            <?php foreach ($roles as $item) : ?>
                                                <option value="<?= encode_id($item->role_id) ?>"><?= ucwords($item->name) ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>Bukan anda ? kembali <a href="<?= site_url('auth/logout') ?>" class="font-weight-medium text-primary"> ke login </a> </p>
                        <p>© 2020 Skote. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand</p>
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

    <script src="<?= base_url('assets/js/page/auth/choose_role.js') ?>"></script>
</body>

</html>