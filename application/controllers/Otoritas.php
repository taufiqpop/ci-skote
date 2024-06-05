<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Otoritas extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->_auth();

        $this->load->model('otoritas/Otoritas_model', 'otoritas');
        $this->load->model('otoritas/Hak_akses_model', 'hak_akses');
        $this->load->model('menu/Menu_model', 'menu');
    }


    public function index($encrypted_menu_id = null)
    {
        $this->checkPermission($encrypted_menu_id, 1);

        $this->_display([
            'title' => 'Otoritas - Daftar Otoritas',
            'menu_id' => $encrypted_menu_id,
            'menu_active' => 'otoritas',
            'plugins' => ['datatable'],
            'script_js' => base_url('assets/js/page/otoritas/roles.js'),
        ], 'otoritas/list');
    }

    public function data($encrypted_menu_id = null)
    {
        $this->checkAccessAjax($encrypted_menu_id, 1);

        $list = $this->otoritas->get_datatables();

        $data = [];
        $no = post('start');

        foreach ($list as $field) {
            $no++;

            $field->no = $no;
            $field->id = encode_id($field->id);
            $field->is_default = (int) $field->is_default;

            $data[] = $field;
        }

        $output = [
            'draw' => post('draw'),
            'recordsTotal' => $this->otoritas->count_all(),
            'recordsFiltered' => $this->otoritas->count_filtered(),
            'data' => $data,
        ];

        $this->_set_success($output);
    }

    public function store()
    {
        $encrypted_menu_id = post('encrypted_menu_id');
        $this->form_validation->set_rules('name', 'Nama Otoritas', 'trim|required|is_unique[roles.name]');

        if ($this->form_validation->run() == false) {
            $err = [
                'name',
            ];

            $this->_set_failed(['status' => false, 'data' => $this->generateFormError($err)], 422);
        }

        $id = post('id');

        if (empty($id)) {
            $this->checkAccessAjax($encrypted_menu_id, 2);
            $row = [
                'id' => uuid(),
                'name' => post('name')
            ];

            $this->db->insert('roles', $row);
        } else {
            $this->checkAccessAjax($encrypted_menu_id, 3);
            $role_id = decode_id($id);

            $row = [
                'name' => post('name'),
            ];

            $this->db->where('id', $role_id);
            $this->db->update('roles', $row);
        }

        if ($this->db->affected_rows() > 0) {
            $this->_set_success([
                'status' => true,
                'affected' => $this->db->affected_rows(),
            ]);
        } else {
            $this->_set_failed([
                'status' => false,
                'affected' => $this->db->affected_rows(),
            ]);
        }
    }

    public function delete()
    {
        $encrypted_menu_id = post('encrypted_menu_id');
        $this->checkAccessAjax($encrypted_menu_id, 4);
        $id = post('id');

        if (empty($id)) {
            $this->_set_failed([
                'status' => false,
                'mssg' => 'Data tidak ditemukan'
            ]);
        }

        $role_id = decode_id($id);

        $this->db->where('id', $role_id);
        $this->db->update('roles', [
            'active' => 0,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        if ($this->db->affected_rows() > 0) {
            $this->_set_success([
                'status' => true,
                'affected' => $this->db->affected_rows(),
            ]);
        } else {
            $this->_set_failed([
                'status' => false,
                'affected' => $this->db->affected_rows(),
            ]);
        }
    }

    public function hak_akses($encrypted_menu_id = null, $encrypted_role_id = null)
    {
        $this->checkPermission($encrypted_menu_id, 3);

        if (empty($encrypted_role_id)) {
            redirect('otoritas/index/' . $encrypted_menu_id, 'refresh');
        }

        $role_id = decode_id($encrypted_role_id);

        $role_data = $this->db->where('id', $role_id)->where([
            'deleted_at' => null,
            'active' => 1
        ])->get('roles')->row();

        $actions = $this->db->where('active', 1)->get('actions')->result();

        $main_menu = $this->hak_akses->getMainMenuOfRole($role_id)->result();

        $list_menu = [];

        foreach ($main_menu as $menu) {
            $item_menu = [
                'id' => $menu->id,
                'parent_id' => $menu->parent_id,
                'name' => $menu->name,
                'role_id' => empty($menu->role_id) ? $role_id : $menu->role_id,
                'route' => $menu->route,
                'actions' => $menu->actions,
                'active' => $menu->menu_role_active
            ];
            $sub_menu = $this->hak_akses->getSubMenuOfRole($role_id, $menu->id);

            if ($sub_menu->num_rows() > 0) {
                foreach ($sub_menu->result() as $sub) {
                    $list_sub_menu[] = [
                        'id' => $sub->id,
                        'parent_id' => $sub->parent_id,
                        'name' => $sub->name,
                        'role_id' => empty($sub->role_id) ? $role_id : $sub->role_id,
                        'route' => $sub->route,
                        'actions' => $sub->actions,
                        'active' => $sub->menu_role_active
                    ];
                }
                $item_menu['child'] = $list_sub_menu;
            } else {
                $item_menu['child'] = null;
            }

            $list_menu[] = $item_menu;
        }

        $this->_display([
            'title' => 'Otoritas - Hak Akses Otoritas "' . $role_data->name . '"',
            'menu_id' => $encrypted_menu_id,
            'menu_active' => 'otoritas',
            'plugins' => ['datatable'],
            'script_js' => base_url('assets/js/page/otoritas/hak_akses.js'),
            'actions' => $actions,
            'list_menu' => $list_menu,
            'role_id' => encode_id($role_id),
        ], 'otoritas/hak_akses');
    }

    public function updateHakAkses($encrypted_menu_id = null)
    {
        $this->checkPermission($encrypted_menu_id, 2);

        $list_all_menus = $this->menu->getAllMenu();
        $list_all_actions = $this->db->where('active', 1)->get('actions')->result();
        $id = post('role_id');

        $role_id = decode_id($id);

        $affected = 0;

        foreach ($list_all_menus as $menu) {
            foreach ($list_all_actions as $action) {
                if (!empty(post($menu->id . '_' . $role_id . '_' . $action->id))) {
                    $affected += $this->hak_akses->updateHakAkses($role_id, $menu->id, $action->id, 1);
                } else {
                    $affected += $this->hak_akses->updateHakAkses($role_id, $menu->id, $action->id, 0);
                }
            }
        }

        $this->session->set_flashdata('update-hak-akses', $affected);

        redirect('otoritas/hak_akses/' . $encrypted_menu_id . '/' . encode_id($role_id), 'refresh');
    }

    public function setDefault($encrypted_menu_id = null)
    {
        if (empty(post('id'))) {
            $this->_set_failed([
                'status' => false,
                'msg' => 'Data otoritas tidak ditemukan'
            ]);
        }

        $value = post('value');
        $id = decode_id(post('id'));

        $this->db->where('is_default', 1);
        $this->db->update('roles', [
            'is_default' => 0
        ]);

        $affected_other = $this->db->affected_rows();

        $this->db->where('id', $id);
        $this->db->update('roles', [
            'is_default' => $value,
        ]);

        $affected_main = $this->db->affected_rows();

        if ($affected_other || $affected_main) {
            $this->_set_success([
                'status' => true,
                'msg' => 'Perubahan data berhasil disimpan'
            ]);
        }

        $this->_set_failed([
            'status' => false,
            'msg' => 'Terjadi kesalahan data saat memperbarui data'
        ]);
    }
}

/* End of file Otoritas.php */
