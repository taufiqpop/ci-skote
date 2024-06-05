<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends MY_Model
{

    var $table = 'menus';
    var $column_order = [null, 'name', 'route', 'icon', 'cross_link', null, 'created_at'];
    var $column_search = ['name', 'route', 'icon', 'group.name'];
    var $order = ['created_at' => 'desc'];

    public function __construct()
    {
        parent::__construct();
        //Do your magic here
    }

    private function _get_datatables_query()
    {
        $this->db->select('menus.*, group.name as group_name');
        $this->db->from($this->table);
        $this->db->join('menu_groups group', 'menus.menu_group_id = group.id', 'left');

        $this->db->where([
            'menus.deleted_at' => null,
            'menus.active' => 1,
        ]);
        $this->db->group_start();
        $this->db->where('menus.parent_id is null');
        $this->db->or_where('menus.parent_id', '');
        $this->db->group_end();

        $this->datatableSearchSort();
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if (post('length') != -1)
            $this->db->limit(post('length'), post('start'));
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->where('deleted_at is null');
        $this->db->where('active', 1);
        $this->db->group_start();
        $this->db->where('parent_id is null');
        $this->db->or_where('parent_id', '');
        $this->db->group_end();
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function getMainMenu($group_menu_id = null, $role_id = null)
    {
        $this->db->from($this->table);
        $this->db->join('menu_role', 'menus.id = menu_role.menu_id', 'inner');

        if (!empty($role_id)) $this->db->where('menu_role.role_id', $role_id);

        $this->db->where([
            'menu_role.active' => 1,
            'menus.active' => 1,
        ]);

        $this->db->group_start();
        $this->db->where('menus.parent_id is null');
        $this->db->or_where('menus.parent_id', '');
        $this->db->group_end();
        $this->db->where('menu_group_id', $group_menu_id);


        $this->db->select('menus.*');

        $this->db->order_by('menus.urutan', 'asc');
        $this->db->group_by('menus.id');

        $data = $this->db->get();

        return $data->result();
    }

    public function getSubMenu($parent_id, $role_id)
    {
        $this->db->from($this->table);
        $this->db->join('menu_role', 'menus.id = menu_role.menu_id', 'inner');
        $this->db->where([
            'menus.active' => 1,
            'menus.parent_id' => $parent_id,
            'menu_role.role_id' => $role_id,
            'menu_role.active' => 1,
        ]);

        $this->db->select('menus.*');

        $this->db->order_by('menus.urutan', 'asc');
        $this->db->group_by('menus.id');

        $data = $this->db->get();

        return $data->result();
    }

    public function getAllMenu()
    {
        $this->db->where('menus.active', 1);
        $this->db->where('menus.deleted_at is null');
        $this->db->from($this->table);

        $data = $this->db->get();

        return $data->result();
    }
}

/* End of file Menu_model.php */
