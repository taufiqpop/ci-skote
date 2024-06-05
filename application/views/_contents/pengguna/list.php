<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <div class="text-sm-right">
                            <?php if ($access['tambah']) : ?>
                                <button type="button" class="btn btn-primary btn-rounded waves-effect waves-light btn-tambah"><i class="bx bx-plus-circle mr-1"></i> Tambah</button>
                            <?php endif ?>
                            <a href="<?= site_url('users/exportExcelUsers') ?>" class="btn btn-success btn-rounded waves-effect waves-light">Export Excel</a>
                            <a href="<?= site_url('users/exportPdfUsers') ?>" class="btn btn-danger btn-rounded waves-effect waves-light">Export PDF</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" data-pattern="priority-columns">
                    <table class="table table-striped" id="table-data" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Otoritas</th>
                                <th>Status Keaktifan</th>
                                <th>Aksi</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-tambah-user" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="label-modal-form" aria-hidden="true">
    <form action="<?= site_url('users/store/' . $menu_id) ?>" method="post" id="form-tambah-user">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="label-modal-form">Form Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="username">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" id="username" class="form-control" required placeholder="Masukkan username">
                        <div class="error error-username"></div>
                    </div>
                    <div class="form-group">
                        <label for="full_name">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" id="full_name" class="form-control" required placeholder="Masukkan nama lengkap">
                        <div class="error error-full_name"></div>
                    </div>
                    <div class="form-group">
                        <label for="password">Kata Sandi <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        <div class="error error-password"></div>
                    </div>
                    <div class="form-group">
                        <label for="conf_password">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                        <input type="password" name="conf_password" id="conf_password" class="form-control" required>
                        <div class="error error-conf_password"></div>
                    </div>
                    <?php if ($default_roles->num_rows() == 1) : ?>
                        <?php $default = $default_roles->row() ?>
                        <div class="alert alert-primary" role="alert">
                            User ini akan mendapat otoritas <b><?= $default->name ?></b> sebagai default.
                        </div>
                    <?php endif ?>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light btn-submit">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->

<div id="modal-ubah-user" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="label-modal-form" aria-hidden="true">
    <form action="<?= site_url('users/update/' . $menu_id) ?>" method="post" id="form-ubah-user">
        <input type="hidden" name="id" id="id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="label-modal-form">Form Pengguna</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ubah-username">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" id="ubah-username" class="form-control" required placeholder="Masukkan username">
                        <div class="error error-update-username"></div>
                    </div>
                    <div class="form-group">
                        <label for="ubah-full_name">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" id="ubah-full_name" class="form-control" required placeholder="Masukkan nama lengkap">
                        <div class="error error-update-full_name"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light btn-submit">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->

<div id="modal-ubah-role" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="label-modal-role" aria-hidden="true">
    <form action="<?= site_url('users/updateRole/' . $menu_id) ?>" method="post" id="form-ubah-role">
        <input type="hidden" name="id" id="ubah_role_id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="label-modal-form">Form Otoritas Pengguna: <span class="full_name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <?php foreach ($roles as $role) : ?>
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mb-3">
                                    <input type="checkbox" class="custom-control-input checkbox-role" id="roles-<?= $role->id ?>" name="role_<?= $role->id ?>" value="<?= $role->id ?>">
                                    <label class="custom-control-label" for="roles-<?= $role->id ?>"><?= $role->name ?></label>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light btn-submit">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->

<input type="hidden" id="edit_access" value="<?= $access['edit'] ?>">
<input type="hidden" id="delete_access" value="<?= $access['delete'] ?>">