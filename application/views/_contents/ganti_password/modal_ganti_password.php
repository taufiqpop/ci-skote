<div id="modal-ganti-password" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-ganti-passwordLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="<?= base_url('change_password/change') ?>" id="form-ganti-password" onsubmit="return false;">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modal-ganti-passwordLabel">Ganti Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="old_pass">Password Lama *</label>
                        <input type="password" name="old_pass" id="old_pass" class="form-control" required autocomplete="off">
                        <div class="error error-old_pass"></div>
                    </div>
                    <div class="form-group">
                        <label for="new_pass">Password Baru *</label>
                        <input type="password" name="new_pass" id="new_pass" class="form-control" required autocomplete="new-password">
                        <div class="error error-new_pass"></div>
                    </div>
                    <div class="form-group">
                        <label for="pass_conf">Password Baru *</label>
                        <input type="password" name="pass_conf" id="pass_conf" class="form-control" required autocomplete="new-password">
                        <div class="error error-pass_conf"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success w-sm waves-effect btn-save-password">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>