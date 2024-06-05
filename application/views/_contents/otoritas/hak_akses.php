<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($this->session->flashdata('update-hak-akses'))) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Perubahan hak akses sudah disimpan. Sejumlah <b><?= $this->session->flashdata('update-hak-akses') ?></b> data dirubah!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif ?>
                <form method="post" action="<?= site_url('otoritas/updateHakAkses/' . $menu_id) ?>">
                    <input type="hidden" name="<?= $csrf['name'] ?>" value="<?= $csrf['hash'] ?>">
                    <input type="hidden" name="role_id" class="role_id" value="<?= $role_id ?>">
                    <?php foreach ($list_menu as $menu) : ?>
                        <div class="form-group col-12">
                            <label><?= $menu['name'] ?></label>
                            <div class="form-group col-12 row">
                                <?php if (empty($menu['child'])) : ?>
                                    <?php $menu_actions = explode(',', $menu['actions']); ?>
                                    <?php foreach ($actions as $action) : ?>
                                        <div class="custom-control custom-checkbox mb-3 mr-2 col-1">
                                            <input type="checkbox" value="1" class="custom-control-input" id="<?= $menu['id'] . '_' . $menu['role_id'] . '_' . $action->id ?>" name="<?= $menu['id'] . '_' . $menu['role_id'] . '_' . $action->id ?>" <?= in_array($action->id, $menu_actions) ? 'checked' : '' ?>>
                                            <label class="custom-control-label" for="<?= $menu['id'] . '_' . $menu['role_id'] . '_' . $action->id ?>"><?= $action->name ?></label>
                                        </div>
                                    <?php endforeach ?>
                                <?php else : ?>
                                    <?php $first_action = reset($actions); ?>
                                    <div class="custom-control custom-checkbox mb-3 col-2">
                                        <input type="checkbox" value="1" class="custom-control-input" id="<?= $menu['id'] . '_' . $menu['role_id'] . '_' . $first_action->id ?>" name="<?= $menu['id'] . '_' . $menu['role_id'] . '_' . $first_action->id ?>" <?= $menu['actions'] == '1' ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="<?= $menu['id'] . '_' . $menu['role_id'] . '_' . $first_action->id ?>"><?= $first_action->name ?></label>
                                    </div>
                                    <div class="col-12 ml-2">
                                        <?php foreach ($menu['child'] as $child) : ?>
                                            <div class="form-group row">
                                                <label class="col-12 ml-1"><?= $child['name'] ?></label>
                                                <?php $child_menu_actions = explode(',', $child['actions']); ?>
                                                <?php foreach ($actions as $action) : ?>
                                                    <div class="custom-control custom-checkbox mb-3 mr-2 ml-3 col-1">
                                                        <input type="checkbox" value="1" class="custom-control-input" id="<?= $child['id'] . '_' . $child['role_id'] . '_' . $action->id ?>" name="<?= $child['id'] . '_' . $child['role_id'] . '_' . $action->id ?>" <?= in_array($action->id, $child_menu_actions) ? 'checked' : '' ?>>
                                                        <label class="custom-control-label" for="<?= $child['id'] . '_' . $child['role_id'] . '_' . $action->id ?>"><?= $action->name ?></label>
                                                    </div>
                                                <?php endforeach ?>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                    <?php endforeach ?>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>