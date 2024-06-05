<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->_auth();

        $this->load->model('menu/Menu_model', 'menu');
        $this->load->model('menu/Submenu_model', 'submenu');
        $this->load->model('menu/Group_model', 'group');
    }

    public function index($encrypted_menu_id = null)
    {
        $this->checkPermission($encrypted_menu_id, 1);

        $groups = $this->group->all();

        $this->_display([
            'title' => 'Manajemen Menu - Menu Utama',
            'menu_id' => $encrypted_menu_id,
            'menu_active' => 'menu utama',
            'menu_open' => 'manajemen menu',
            'plugins' => ['datatable'],
            'groups' => $groups,
            'script_js' => base_url('assets/js/page/manajemen_menu/main_menu.js'),
            'icons' => icons(),
        ], 'menu/main_menu');
    }

    public function sub($encrypted_menu_id = null)
    {
        $this->checkPermission($encrypted_menu_id, 1);

        $main_menu = $this->db->where([
            'deleted_at' => null,
            'active' => 1,
            'parent_id' => null,
        ])->get('menus')->result();

        $kecamatan = $this->wilayah->getKecamatan(3372);

        $this->_display([
            'title' => 'Manajemen Menu - Sub Menu',
            'menu_id' => $encrypted_menu_id,
            'menu_active' => 'submenu',
            'menu_open' => 'manajemen menu',
            'plugins' => ['datatable'],
            'script_js' => base_url('assets/js/page/manajemen_menu/sub_menu.js'),
            'main_menu_data' => $main_menu,
            'kecamatan' => $kecamatan,
        ], 'menu/sub_menu');
    }

    public function menuData($encrypted_menu_id = null)
    {
        $this->checkAccessAjax($encrypted_menu_id, 1);

        $list = $this->menu->get_datatables();

        $data = [];
        $no = post('start');

        foreach ($list as $field) {
            $no++;

            $field->no = $no;
            $field->id = encode_id($field->id);

            $data[] = $field;
        }

        $output = [
            'draw' => post('draw'),
            'recordsTotal' => $this->menu->count_all(),
            'recordsFiltered' => $this->menu->count_filtered(),
            'data' => $data,
        ];

        $this->_set_success($output);
    }

    public function storeMainMenu()
    {
        $encrypted_menu_id = post('encrypted_menu_id');

        if (empty(post('id'))) {
            $this->form_validation->set_rules('name', 'Nama Menu', 'trim|required|is_unique[menus.name]');
        } else {
            $this->form_validation->set_rules('name', 'Nama Menu', 'trim|required');
        }
        $this->form_validation->set_rules('icon', 'Icon Menu', 'trim|required');
        $this->form_validation->set_rules('menu_group_id', 'Group Menu', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $err = [
                'name',
                'icon',
                'menu_group_id',
            ];

            $this->_set_failed(['status' => false, 'data' => $this->generateFormError($err)], 422);
        }

        $id = post('id');

        if (empty($id)) {
            $this->checkAccessAjax($encrypted_menu_id, 2);
            $data_urutan = $this->db->select('urutan')->where([
                'parent_id' => null,
                'active' => 1
            ])->order_by('urutan', 'desc')->limit(1)->get('menus')->row();

            $row = [
                'id' => uuid(),
                'name' => post('name'),
                'route' => post('route'),
                'icon' => post('icon'),
                'menu_group_id' => post('menu_group_id'),
                'urutan' => $data_urutan->urutan + 1
            ];

            $this->db->insert('menus', $row);
        } else {
            $this->checkAccessAjax($encrypted_menu_id, 3);
            $menu_id = decode_id($id);

            $row = [
                'name' => post('name'),
                'route' => post('route'),
                'icon' => post('icon'),
                'menu_group_id' => post('menu_group_id'),
            ];

            $this->db->where('id', $menu_id);
            $this->db->update('menus', $row);
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

    public function deleteMainMenu()
    {
        $encrypted_menu_id = post('encrypted_menu_id');
        $this->checkAccessAjax($encrypted_menu_id, 4);
        $id = post('id');

        if (empty($id)) {
            $this->_set_failed([
                'status' => false,
                'mssg' => 'Data Tidak Ditemukan',
            ]);

            exit();
            // return false;
        }

        $menu_id = decode_id($id);

        $this->db->where('id', $menu_id);
        $this->db->update('menus', [
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

    public function submenuData($encrypted_menu_id = null)
    {
        $this->checkAccessAjax($encrypted_menu_id, 1);

        $list = $this->submenu->get_datatables();

        $data = [];
        $no = post('start');

        foreach ($list as $field) {
            $no++;

            $field->no = $no;
            $field->id = encode_id($field->id);

            $data[] = $field;
        }

        $output = [
            'draw' => post('draw'),
            'recordsTotal' => $this->submenu->count_all(),
            'recordsFiltered' => $this->submenu->count_filtered(),
            'data' => $data,
        ];

        $this->_set_success($output);
    }

    public function storeSubMenu()
    {
        $encrypted_menu_id = post('encrypted_menu_id');

        if (empty(post('id'))) {
            $this->form_validation->set_rules('name', 'Nama Menu', 'trim|required|is_unique[menus.name]');
        } else {
            $this->form_validation->set_rules('name', 'Nama Menu', 'trim|required');
        }
        $this->form_validation->set_rules('route', 'Link Menu', 'trim|required');
        $this->form_validation->set_rules('parent_id', 'Menu Utama', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $err = [
                'name',
                'route',
                'parent_id'
            ];

            $this->_set_failed(['status' => false, 'data' => $this->generateFormError($err)], 422);
        }

        $id = post('id');

        if (empty($id)) {
            $this->checkAccessAjax($encrypted_menu_id, 2);
            // $data_urutan = $this->db->select('urutan')->where([
            //     'parent_id' => post('parent_id'),
            //     'active' => 1,
            // ])->order_by('urutan', 'desc')->limit(1)->get('menus')->row();

            $row = [
                'id' => uuid(),
                'parent_id' => post('parent_id'),
                'name' => post('name'),
                'route' => post('route'),
                // 'urutan' => $data_urutan->urutan + 1,
            ];

            $this->db->insert('menus', $row);
        } else {
            $this->checkAccessAjax($encrypted_menu_id, 3);
            $menu_id = decode_id($id);

            $row = [
                'parent_id' => post('parent_id'),
                'name' => post('name'),
                'route' => post('route'),
            ];

            $this->db->where('id', $menu_id);
            $this->db->update('menus', $row);
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

    public function deleteSubMenu()
    {
        $encrypted_menu_id = post('encrypted_menu_id');
        $this->checkAccessAjax($encrypted_menu_id, 4);
        $id = post('id');

        if (empty($id)) {
            $this->_set_failed([
                'status' => false,
                'mssg' => 'Data Tidak Ditemukan',
            ]);

            exit();
            // return false;
        }

        $menu_id = decode_id($id);

        $this->db->where('id', $menu_id);
        $this->db->update('menus', [
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

    public function updateCrossLink($encrypted_menu_id = null)
    {
        $this->checkAccessAjax($encrypted_menu_id, 3);

        if (empty(post('id'))) {
            $this->_set_failed([
                'status' => false,
                'msg' => 'Data menu tidak ditemukan'
            ]);
        }
        $id = decode_id(post('id'));

        $this->db->where('id', $id);
        $this->db->update('menus', [
            'cross_link' => post('checked')
        ]);

        if ($this->db->affected_rows() > 0) {
            $this->_set_success([
                'status' => true,
                'msg' => 'Data berhasil diupdate'
            ]);
        }

        $this->_set_failed([
            'status' => false,
            'msg' => 'Terjadi kesalahan saat memperbarui data'
        ]);
    }

    public function group($encrypted_menu_id = null)
    {
        $this->checkPermission($encrypted_menu_id, 1);

        $this->_display([
            'title' => 'Manajemen Menu - Grup Menu',
            'menu_id' => $encrypted_menu_id,
            'menu_active' => 'group menu',
            'menu_open' => 'manajemen menu',
            'plugins' => ['datatable'],
            'script_js' => base_url('assets/js/page/manajemen_menu/group_menu.js'),
        ], 'menu/group');
    }

    public function groupData($encrypted_menu_id = null)
    {
        $this->checkAccessAjax($encrypted_menu_id, 1);

        $list = $this->group->get_datatables();

        $data = [];
        $no = post('start');

        foreach ($list as $field) {
            $no++;

            $field->no = $no;
            $field->id = encode_id($field->id);

            $data[] = $field;
        }

        $output = [
            'draw' => post('draw'),
            'recordsTotal' => $this->group->count_all(),
            'recordsFiltered' => $this->group->count_filtered(),
            'data' => $data,
        ];

        $this->_set_success($output);
    }

    public function storeGroupMenu()
    {
        $encrypted_menu_id = post('encrypted_menu_id');

        if (empty(post('id'))) {
            $this->form_validation->set_rules('name', 'Nama Group Menu', 'trim|required|is_unique[menu_groups.name]');
        } else {
            $this->form_validation->set_rules('name', 'Nama Group Menu', 'trim|required');
        }

        if ($this->form_validation->run() == FALSE) {
            $err = [
                'name',
            ];

            $this->_set_failed(['status' => false, 'data' => $this->generateFormError($err)], 422);
        }

        $id = post('id');

        if (empty($id)) {
            $this->checkAccessAjax($encrypted_menu_id, 2);

            $row = [
                'name' => post('name'),
            ];

            $this->db->insert('menu_groups', $row);
        } else {
            $this->checkAccessAjax($encrypted_menu_id, 3);
            $menu_id = decode_id($id);

            $row = [
                'name' => post('name'),
            ];

            $this->db->where('id', $menu_id);
            $this->db->update('menu_groups', $row);
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

    public function deleteGroupMenu()
    {
        $encrypted_menu_id = post('encrypted_menu_id');
        $this->checkAccessAjax($encrypted_menu_id, 4);
        $id = post('id');

        if (empty($id)) {
            $this->_set_failed([
                'status' => false,
                'mssg' => 'Data Tidak Ditemukan',
            ]);
            // return false;
        }

        $menu_id = decode_id($id);

        $this->db->where('id', $menu_id);
        $this->db->update('menu_groups', [
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        if ($this->db->affected_rows() > 0) {
            $this->_set_success([
                'status' => true,
                'affected' => $this->db->affected_rows(),
                'q' => $this->db->last_query()
            ]);
        } else {
            $this->_set_failed([
                'status' => false,
                'affected' => $this->db->affected_rows(),
                'q' => $this->db->last_query()
            ]);
        }
    }
}

/* End of file Menu.php */
