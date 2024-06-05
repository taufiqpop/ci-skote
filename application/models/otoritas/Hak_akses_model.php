<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hak_akses_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getMainMenuOfRole($role_id)
    {
        $this->db->select('
        menus.*,
        GROUP_CONCAT(CASE WHEN menu_role.active = "1" THEN menu_role.action_id END) as actions,
        menu_role.active as menu_role_active,
        menu_role.role_id
        ');
        $this->db->from('menus');
        $this->db->join('menu_role', 'menus.id = menu_role.menu_id AND menu_role.role_id = "' . $role_id . '"', 'left');
        $this->db->where('menus.parent_id is null');
        $this->db->where('menus.deleted_at is null');
        $this->db->where('menus.active', 1);
        $this->db->group_by('menus.id');
        $this->db->order_by('menus.urutan');

        $data = $this->db->get();

        return $data;
    }

    public function getSubMenuOfRole($role_id, $parent_id)
    {
        $this->db->select('
            menus.*,
            GROUP_CONCAT(CASE WHEN menu_role.active ="1" THEN menu_role.action_id END) as actions,
            menu_role.active as menu_role_active,
            menu_role.role_id
        ');
        $this->db->from('menus');
        $this->db->join('menu_role', 'menus.id = menu_role.menu_id AND menu_role.role_id = "' . $role_id . '"', 'left');
        $this->db->where('menus.parent_id', $parent_id);
        $this->db->where('menus.deleted_at is null');
        $this->db->where('menus.active', 1);
        $this->db->group_by('menus.id');
        $this->db->order_by('menus.urutan');

        $data = $this->db->get();

        return $data;
    }

    public function updateHakAkses($role_id, $menu_id, $action_id, $val)
    {
        $check = $this->checkAksesExist($role_id, $menu_id, $action_id);

        if ($check->num_rows() == 0) {
            if ($val == 1) {
                $this->db->insert('menu_role', [
                    'role_id' => $role_id,
                    'menu_id' => $menu_id,
                    'action_id' => $action_id,
                    'active' => $val
                ]);
            }
        } else {
            $this->db->where([
                'role_id' => $role_id,
                'menu_id' => $menu_id,
                'action_id' => $action_id,
            ]);
            $this->db->update('menu_role', ['active' => $val]);
        }

        return $this->db->affected_rows();
    }

    public function checkAksesExist($role_id, $menu_id, $action_id)
    {
        $this->db->where([
            'role_id' => $role_id,
            'menu_id' => $menu_id,
            'action_id' => $action_id,
        ]);

        $this->db->from('menu_role');
        $row = $this->db->get();

        return $row;
    }
}

/* End of file Hak_akses_model.php */
