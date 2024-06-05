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
                            <a target="_blank" href="<?= site_url('mahasiswa/exportExcelMhs') ?>" class="btn btn-success btn-rounded waves-effect waves-light">Export Excel</a>
                            <a href="<?= site_url('mahasiswa/exportPdfMhs') ?>" class="btn btn-danger btn-rounded waves-effect waves-light">Export PDF</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" data-pattern="priority-columns">
                    <table class="table table-striped" id="table-data" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th>NIM</th>
                                <th>Nama Lengkap</th>
                                <th>Action</th>
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

<div id="modal-tambah-mahasiswa" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="label-modal-form" aria-hidden="true">
    <form action="<?= site_url('mahasiswa/store/' . $menu_id) ?>" method="post" id="form-tambah-mahasiswa">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="label-modal-form">Form Mahasiswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nim">NIM <span class="text-danger">*</span></label>
                        <input type="text" name="nim" id="nim" class="form-control" required placeholder="Masukkan nim">
                        <div class="error error-nim"></div>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control" required placeholder="Masukkan nama lengkap">
                        <div class="error error-nama"></div>
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

<div id="modal-ubah-mahasiswa" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="label-modal-form" aria-hidden="true">
    <form action="<?= site_url('mahasiswa/update/' . $menu_id) ?>" method="post" id="form-ubah-mahasiswa">
        <input type="hidden" name="id" id="id">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="label-modal-form">Form Mahasiswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ubah-nim">NIM <span class="text-danger">*</span></label>
                        <input type="text" name="nim" id="ubah-nim" class="form-control" required placeholder="Masukkan nim">
                        <div class="error error-update-nim"></div>
                    </div>
                    <div class="form-group">
                        <label for="ubah-nama">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="ubah-nama" class="form-control" required placeholder="Masukkan nama lengkap">
                        <div class="error error-update-nama"></div>
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