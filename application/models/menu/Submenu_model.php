<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Submenu_model extends MY_Model
{

    var $table = 'menus';
    var $column_order = [null, 'menus.name', 'menus.route', 'parent.parent_id', 'menus.cross_link', null, 'created_at'];
    var $column_search = ['menus.name', 'menus.route', 'parent.name'];
    var $order = ['created_at' => 'desc'];


    public function __construct()
    {
        parent::__construct();
        //Do your magic here
    }

    private function _get_datatables_query()
    {
        $this->db->select('menus.*, parent.name as parent_name');
        $this->db->from($this->table);
        $this->db->join('menus parent', 'menus.parent_id = parent.id', 'inner');
        $this->db->where([
            'menus.deleted_at' => null,
            'menus.active' => 1,
        ]);
        $this->db->where('menus.parent_id is not null');

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
        $this->db->where([
            'deleted_at' => null,
            'active' => 1,
        ]);
        $this->db->where('parent_id is not null');
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
}

/* End of file Submenu_model.php */
