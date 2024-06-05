<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends MY_Model
{

    var $table = 'users';
    var $column_order = [null, 'username', 'full_name', 'user_role.role_names', 'active', null, 'created_at'];
    var $column_search = ['username', 'full_name', 'user_role.role_names', 'active'];
    var $order = ['created_at' => 'desc'];


    public function __construct()
    {
        parent::__construct();
        //Do your magic here
    }

    private function _get_datatables_query()
    {
        $this->db->select('users.id, users.username, users.full_name, users.active, users.created_at, user_role.roles, user_role.role_names');
        $this->db->where([
            'deleted_at' => null,
        ]);
        $this->db->from($this->table);
        $this->db->join('(
            SELECT
                GROUP_CONCAT( user_role.role_id ) AS roles,
                GROUP_CONCAT( roles.name ) as role_names,
                user_id
            FROM
                user_role
                LEFT JOIN roles ON user_role.role_id = roles.id
            WHERE
                user_role.active = "1"
                AND roles.deleted_at is null
                AND roles.active = "1"
            GROUP BY user_role.user_id
        ) as user_role', 'users.id = user_role.user_id', 'left');

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
        ]);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function checkUsername($user_id, $username)
    {
        $this->db->from($this->table);
        $this->db->where('username', $username);
        $this->db->where('id <>', $user_id);

        $data = $this->db->get();

        return (int) $data->num_rows();
    }

    public function getUserFromRole(array $role_id = [], array $where = [])
    {
        $this->db->select('users.id, users.full_name, users.created_at');
        $this->db->from($this->table);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->join('user_role', 'users.id = user_role.user_id', 'left');
        $this->db->where('users.deleted_at is null');
        $this->db->where('users.active', 1);
        $this->db->where('user_role.active', 1);
        $this->db->where_in('user_role.role_id', $role_id);
        $this->db->group_by('users.id');
        $this->db->order_by('users.created_at', 'desc');

        $list = $this->db->get();

        return $list->result();
    }
}

/* End of file User_model.php */
