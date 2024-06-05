<?php

defined('BASEPATH') or exit('No direct script access allowed');

class GeneratorData extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function generateEncryptionKey()
    {
        $key = bin2hex($this->encryption->create_key(16));
        echo $key;
    }

    public function generateData()
    {
        $actions = $this->generateActions();
        $roles = $this->generateDummyRole();
        $users = $this->generateDummyUser();
        $userToRoles = $this->generateRoleToUser();
        $menus = $this->generateMenu();
        $menuToRoles = $this->generateMenuRole();

        $this->_set_success([
            'actions' => $actions,
            'roles' => $roles,
            'users' => $users,
            'userToRoles' => $userToRoles,
            'menus' => $menus,
            'menuToRoles' => $menuToRoles,
        ]);
    }

    protected function truncateTable($table)
    {
        $this->db->from($table)->truncate();
    }

    public function generateActions()
    {
        $this->truncateTable('actions');
        $data = ['view', 'create', 'edit', 'delete', 'download', 'archive'];

        $affected = 0;
        foreach ($data as $key => $value) {
            $this->db->insert('actions', [
                'name' => $value,
                'active' => 1,
            ]);

            $affected += $this->db->affected_rows();
        }

        return $affected;
    }

    public function generateDummyRole()
    {
        $this->truncateTable('roles');

        $data = [['id' => 'e482a683-7427-45e2-8a57-3a8a7f8a2422', 'name' => 'administrator'], ['id' => '96ffeed7-734e-410f-91a4-20795688e873', 'name' => 'regular']];

        $this->db->insert_batch('roles', $data);

        return $this->db->affected_rows();
    }

    public function generateDummyUser()
    {
        $this->truncateTable('users');

        $data = [[
            'id' => '7e8906ad-249f-40e2-986d-39a71e5e7f94',
            'username' => 'admin',
            'password' => $this->generatePassword('password'),
            'full_name' => 'Adminitrator',
        ], [
            'id' => 'fc5c6015-4b6a-43a3-9772-2db17f6dd707',
            'username' => 'regular',
            'password' => $this->generatePassword('password'),
            'full_name' => 'User Regular',
        ]];

        $this->db->insert_batch('users', $data);

        return $this->db->affected_rows();
    }

    public function generateRoleToUser()
    {
        $this->truncateTable('user_role');

        $roles = $this->db->get('roles')->result();
        $users = $this->db->get('users')->result();

        $data = [];

        foreach ($roles as $k => $role) {
            foreach ($users as $k2 => $user) {
                $data[] = [
                    'user_id' => $user->id,
                    'role_id' => $role->id,
                    'active' => 1
                ];
            }
        }


        $this->db->insert_batch('user_role', $data);

        return $this->db->affected_rows();
    }

    public function generateMenu()
    {
        $this->truncateTable('menus');

        $data = [
            [
                'id' => '25376a9a-2842-4fe4-af25-9874a1f03b9a',
                'menu_group_id' => 1,
                'route' => 'dashboard/index',
                'name' => 'beranda',
                'icon' => 'bx bx-home-alt',
                'urutan' => '1'
            ],
            [
                'id' => 'e85d3a7f-7fd1-4818-bd7f-45c0cc4b0bc5',
                'menu_group_id' => 2,
                'route' => '',
                'name' => 'manajemen menu',
                'icon' => 'bx bx-menu-alt-left',
                'urutan' => '2'
            ],
            [
                'id' => 'a960f92e-7a9a-4b63-983d-6ab3fda1c6fd',
                'menu_group_id' => 2,
                'route' => 'menu/index',
                'name' => 'menu utama',
                'parent_id' => 'e85d3a7f-7fd1-4818-bd7f-45c0cc4b0bc5',
                'urutan' => null,
            ],
            [
                'id' => 'b3054f7c-0d21-4b17-af64-82dbf4e3984e',
                'menu_group_id' => 2,
                'route' => 'menu/sub',
                'name' => 'submenu',
                'parent_id' => 'e85d3a7f-7fd1-4818-bd7f-45c0cc4b0bc5',
                'urutan' => null,
            ],
            [
                'id' => 'f5b3a0ab-0b2d-4d45-9e47-8c3702d56dfe',
                'menu_group_id' => 2,
                'route' => 'otoritas/index',
                'name' => 'otoritas',
                'icon' => 'bx bxs-user-detail',
                'urutan' => '3'
            ],
            [
                'id' => 'd49203ec-924c-4ad4-b3c1-257b3658327e',
                'menu_group_id' => 2,
                'route' => 'users/index',
                'name' => 'pengguna',
                'icon' => 'bx bx-user',
                'urutan' => '4'
            ],
            [
                'id' => '17c3515d-4d70-4e5f-8e50-d9472a61ae2a',
                'menu_group_id' => 2,
                'route' => 'menu/group',
                'name' => 'group menu',
                'parent_id' => 'e85d3a7f-7fd1-4818-bd7f-45c0cc4b0bc5',
                'urutan' => null
            ]
        ];

        $affected = 0;
        foreach ($data as $item) {
            $this->db->insert('menus', $item);

            $affected += $this->db->affected_rows();
        }

        return $this->db->affected_rows();
    }

    public function generateMenuRole()
    {
        $this->truncateTable('menu_role');

        $actions = $this->db->get('actions')->result();
        $menus = $this->db->get('menus')->result();
        $roles = 'e482a683-7427-45e2-8a57-3a8a7f8a2422';

        $data = [];
        foreach ($menus as $menu) {
            $menu_id = $menu->id;

            $count_child = $this->db->where('parent_id', $menu_id)->get('menus')->num_rows();

            if ($count_child > 0) {
                $data[] = [
                    'role_id' => $roles,
                    'menu_id' => $menu_id,
                    'action_id' => '1',
                ];
            } else {
                foreach ($actions as $action) {
                    $action_id = $action->id;

                    $data[] = [
                        'role_id' => $roles,
                        'menu_id' => $menu_id,
                        'action_id' => $action_id,
                    ];
                }
            }
        }


        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die;

        $this->db->insert_batch('menu_role', $data);

        return $this->db->affected_rows();
    }

    public function generateGroupMenu()
    {
        $this->truncateTable('menu_groups');

        $data = [['name' => 'Beranda'], ['name' => 'Manajemen'], ['name' => 'Referensi']];

        $this->db->insert_batch('menu_groups', $data);

        return $this->db->affected_rows();
    }

    public function generateKey()
    {
        $this->load->library('encryption');

        $key = bin2hex($this->encryption->create_key(16));

        echo json_encode($key);
    }
}

/* End of file LoadDummyData.php */
