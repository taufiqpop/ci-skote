<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Group_model extends MY_Model
{

    var $table = 'menu_groups';
    var $column_order = [null, 'name', null, 'created_at'];
    var $column_search = ['name'];
    var $order = ['created_at' => 'desc'];


    public function __construct()
    {
        parent::__construct();
        //Do your magic here
    }

    private function _get_datatables_query()
    {
        $this->db->select('*');
        $this->db->where([
            'deleted_at' => null,
        ]);
        $this->db->from($this->table);

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
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function all()
    {
        return $this->db->from($this->table)->where('deleted_at is null')->get()->result();
    }
}

/* End of file Group_model.php */
