<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <div class="text-sm-right">
                            <?php if ($access['tambah']) : ?>
                                <button type="button" class="btn btn-success btn-rounded waves-effect waves-light btn-tambah"><i class="bx bx-plus-circle mr-1"></i> Tambah</button>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" data-pattern="priority-columns">
                    <table class="table table-striped" id="table-data" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th>Nama Menu</th>
                                <th>Link</th>
                                <th>Icon</th>
                                <th>Cross Link</th>
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

<div id="modal-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="label-modal-form" aria-hidden="true">
    <form action="<?= site_url('menu/storeMainMenu') ?>" method="post" id="form-menu">
        <input type="hidden" name="id" id="id">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="label-modal-form">Form Menu Utama</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="name">Nama Menu <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" required placeholder="Masukkan nama menu">
                                <div class="error error-name"></div>
                            </div>
                            <div class="form-group">
                                <label for="route">Link</label>
                                <input type="text" name="route" id="route" class="form-control" placeholder="Masukkan link menu">
                            </div>
                            <div class="form-group">
                                <label for="icon">Icon <span class="text-danger">*</span></label>
                                <input type="text" name="icon" id="icon" class="form-control" required readonly placeholder="Pilih icon di tabel yang tersedia">
                                <div class="error error-icon"></div>
                            </div>
                            <div class="form-group">
                                <label for="menu_group_id">Grup Menu <span class="text-danger">*</span></label>
                                <select name="menu_group_id" id="menu_group_id" class="form-control" required>
                                    <option value="" selected disabled>Pilih Group Menu</option>
                                    <?php foreach ($groups as $key => $group) : ?>
                                        <option value="<?= $group->id ?>"><?= $group->name ?></option>
                                    <?php endforeach ?>
                                </select>
                                <div class="error error-menu_group_id"></div>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <div class="table-responsive" data-priority="priority-columns">
                                <table class="table table-striped table-hover table-icons" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 10%;">Icon</th>
                                            <th>Nama Icon</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($icons as $key => $value) : ?>
                                            <tr>
                                                <td class="text-center" style="width: 10%;"><i class="<?= $value ?> fa-2x text-primary pilih-icon" data-icon_name="<?= $value ?>"></i></td>
                                                <td><span class="pilih-icon" data-icon_name="<?= $value ?>"><?= $value ?></span></td>
                                            </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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