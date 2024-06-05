<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->_auth();

        $this->load->model('menu/Menu_model', 'menu');
        $this->load->model('menu/Group_model', 'group');
    }

    public function index($encrypted_menu_id)
    {
        $this->checkPermission($encrypted_menu_id, 1);

        $this->_display([
            'title' => 'Selamat Datang',
            'menu_id' => $encrypted_menu_id,
            'menu_active' => 'beranda',
            'menu_open' => 'beranda',
        ], 'dashboard/home');
    }

    public function testStandardModal()
    {
        $this->_response_modal('dashboard/modals/test', []);
    }

    public function loadMenu()
    {
        $role_id = $this->role_id;
        $user_id = $this->current_id;

        $groups = $this->group->all();
        foreach ($groups as $group) {
            $parent = $this->menu->getMainMenu($group->id, $role_id);

            foreach ($parent as $item) {
                $item->child = $this->menu->getSubMenu($item->id, $role_id);
                foreach ($item->child as $row) {
                    $row->id = encode_id($row->id);
                }
                $item->id = encode_id($item->id);
                $item->query = $this->db->last_query();
            }

            $group->parent = $parent;
        }

        $this->_set_success([
            'data' => $groups,
            'status' => true,
        ]);
    }

    public function loadIcon()
    {
        $icons = icons();

        $this->_set_success([
            'data' => $icons
        ]);
    }

    public function changeYear()
    {
        $year = post('year');

        if (empty($year)) {
            return false;
        }

        $array = array(
            'tahun' => $year
        );

        $this->session->set_userdata($array);
        $this->_set_success(['status' => true]);
        return;
    }
}

/* End of file Dashboard.php */
