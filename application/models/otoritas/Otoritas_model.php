<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Otoritas_model extends MY_Model
{

    var $table = 'roles';
    var $column_order = [null, 'name', null, 'created_at'];
    var $column_search = ['name'];
    var $order = ['created_at' => 'desc'];


    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->select('*');
        $this->db->where([
            'deleted_at' => null,
            'active' => 1,
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
        $this->db->where([
            'deleted_at' => null,
            'active' => 1,
        ]);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function all()
    {
        return $this->db->where([
            'deleted_at' => null,
            'active' => 1,
        ])->get('roles')->result();
    }

    public function default()
    {
        return $this->db->where([
            'deleted_at' => null,
            'active' => 1,
            'is_default' => 1
        ])->get('roles');
    }

    public function updateRole($role_id, $user_id, $val)
    {
        $check = $this->checkUserRoleExist($role_id, $user_id);

        if ($check->num_rows() == 0) {
            if ($val == 1) {
                $this->db->insert('user_role', [
                    'user_id' => $user_id,
                    'role_id' => $role_id,
                    'active' => $val,
                ]);
            }
        } else {
            $this->db->where([
                'user_id' => $user_id,
                'role_id' => $role_id,
            ]);
            $this->db->update('user_role', ['active' => $val]);
        }

        return $this->db->affected_rows();
    }

    public function checkUserRoleExist($role_id, $user_id)
    {
        $this->db->where([
            'user_id' => $user_id,
            'role_id' => $role_id,
        ]);

        $this->db->from('user_role');
        $row = $this->db->get();

        return $row;
    }

    public function getRoleOfUser($user_id)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where([
            'user_id' => $user_id,
            'active' => 1
        ]);
        $roles = $this->db->get()->result();

        return $roles;
    }
}

/* End of file Otoritas_model.php */
