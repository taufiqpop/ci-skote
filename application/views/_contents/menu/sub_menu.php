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
                                <th>Nama Menu Utama</th>
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
    <form action="<?= site_url('menu/storeSubMenu') ?>" method="post" id="form-menu">
        <input type="hidden" name="id" id="id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="label-modal-form">Form Menu Utama</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="parent_id">Menu Utama <span class="text-danger"><b>*</b></span></label>
                                <select name="parent_id" id="parent_id" class="form-control" required>
                                    <option value="" selected disabled>Pilih Menu Utama</option>
                                    <?php foreach ($main_menu_data as $item) : ?>
                                        <option value="<?= $item->id ?>"><?= ucfirst($item->name) ?></option>
                                    <?php endforeach ?>
                                </select>
                                <div class="error error-parent_id"></div>
                            </div>
                            <div class="form-group">
                                <label for="name">Nama Submenu <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" required placeholder="Masukkan nama menu" required>
                                <div class="error error-name"></div>
                            </div>
                            <div class="form-group">
                                <label for="route">Link <span class="text-danger">*</span></label>
                                <input type="text" name="route" id="route" class="form-control" placeholder="Masukkan link menu" required>
                                <div class="error error-route"></div>
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