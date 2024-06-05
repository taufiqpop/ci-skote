<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->_auth();

        $this->load->model('User_model', 'user');
        $this->load->model('otoritas/Otoritas_model', 'otoritas');
    }

    public function index($encrypted_menu_id = null)
    {
        $this->checkPermission($encrypted_menu_id, 1);

        $roles = $this->otoritas->all();

        $default_roles = $this->otoritas->default();

        $this->_display([
            'title' => 'Pengguna - Daftar Pengguna',
            'menu_id' => $encrypted_menu_id,
            'menu_active' => 'pengguna',
            'plugins' => ['datatable'],
            'script_js' => base_url('assets/js/page/pengguna/list.js'),
            'roles' => $roles,
            'default_roles' => $default_roles,
        ], 'pengguna/list');
    }

    public function data($encrypted_menu_id = null)
    {
        $this->checkAccess($encrypted_menu_id, 1);

        $list = $this->user->get_datatables();

        $data = [];
        $no = post('start');

        foreach ($list as $field) {
            $no++;

            $field->no = $no;
            $field->its_you = ($this->current_id == $field->id) ? true : false;
            $field->id = encode_id($field->id);
            $field->roles = ($field->roles) ? explode(',', $field->roles) : [];
            $field->role_names = ($field->role_names) ? explode(',', $field->role_names) : [];

            $data[] = $field;
        }

        $output = [
            'draw' => post('draw'),
            'recordsTotal' => $this->user->count_all(),
            'recordsFiltered' => $this->user->count_filtered(),
            'data' => $data,
        ];

        $this->_set_success($output);
        return;
    }

    public function store($encrypted_menu_id = null)
    {
        $this->checkAccess($encrypted_menu_id, 2);

        $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[users.username]');
        $this->form_validation->set_rules('full_name', 'Nama Lengkap', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('conf_password', 'Konfirmasi Password', 'trim|required|matches[password]');

        if ($this->form_validation->run() == false) {
            $err = [
                'username',
                'full_name',
                'password',
                'conf_password',
            ];

            $this->_set_failed(['status' => false, 'data' => $this->generateFormError($err)], 422);
        }

        $username = post('username');
        $password = post('password');
        $full_name = post('full_name');

        $hashed_password = $this->generatePassword($password);

        $this->db->insert('users', [
            'id' => uuid(),
            'username' => $username,
            'password' => $hashed_password,
            'full_name' => $full_name
        ]);

        if ($this->db->affected_rows() > 0) {
            $user_id = $this->db->insert_id();

            $default_roles = $this->otoritas->default();

            if ($default_roles->num_rows() == 1) {

                $role = $default_roles->row();

                $this->db->insert('user_role', [
                    'user_id' => $user_id,
                    'role_id' => $role->id,
                    'active' => 1
                ]);
            }

            $this->_set_success([
                'status' => true,
                'affected' => $this->db->affected_rows()
            ]);
            return;
        } else {
            $this->_set_failed([
                'status' => false,
                'affected' => $this->db->affected_rows()
            ]);
            return;
        }
    }

    public function update($encrypted_menu_id = null)
    {
        $this->checkAccess($encrypted_menu_id, 3);

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('full_name', 'Nama Lengkap', 'trim|required');

        if ($this->form_validation->run() == false) {
            $err = [
                'username',
                'full_name',
                'password',
                'conf_password',
            ];

            $this->_set_failed(['status' => false, 'data' => $this->generateFormError($err)], 422);
        }

        $id = post('id');

        if (empty($id)) {
            $this->_set_success([
                'status' => false,
                'mssg' => 'Data tidak ditemukan'
            ]);
            return;
        }

        $user_id = decode_id($id);
        $username = post('username');
        $full_name = post('full_name');

        $check_username = $this->user->checkUsername($user_id, $username);

        if ($check_username != 0) {
            $this->_set_success([
                'status' => false,
                'mssg' => 'Username sudah dipakai'
            ]);
            return;
        }

        $this->db->where('id', $user_id);
        $this->db->update('users', [
            'username' => $username,
            'full_name' => $full_name,
        ]);

        if ($this->db->affected_rows() > 0) {
            $this->_set_success([
                'status' => true,
                'affected' => $this->db->affected_rows()
            ]);
            return;
        } else {
            $this->_set_failed([
                'status' => false,
                'affected' => $this->db->affected_rows()
            ]);
            return;
        }
    }

    public function changeUserActive($encrypted_menu_id = null)
    {
        $this->checkAccess($encrypted_menu_id, 3);

        $id = post('id');

        if (empty($id)) {
            $this->_set_failed([
                'status' => false,
            ]);

            return;
        }

        $user_id = decode_id($id);
        $val = post('val');

        $this->db->where('id', $user_id);
        $this->db->update('users', [
            'active' => $val,
        ]);

        if ($this->db->affected_rows() > 0) {
            $this->_set_success([
                'status' => true,
                'affected' => $this->db->affected_rows()
            ]);
            return;
        } else {
            $this->_set_failed([
                'status' => false,
                'affected' => $this->db->affected_rows()
            ]);
            return;
        }
    }

    public function resetPassword($encrypted_menu_id = null)
    {
        $this->checkAccess($encrypted_menu_id, 3);

        $id = post('id');

        if (empty($id)) {
            $this->_set_failed([
                'status' => false,
            ]);

            return;
        }

        $user_id = decode_id($id);

        $string_password = $this->config->item('default_reset_password');
        $hashed_password = $this->generatePassword($string_password);

        $this->db->where('id', $user_id);
        $this->db->update('users', ['password' => $hashed_password]);

        if ($this->db->affected_rows() > 0) {
            $this->_set_success([
                'status' => true,
                'affected' => $this->db->affected_rows()
            ]);
            return;
        } else {
            $this->_set_failed([
                'status' => false,
                'affected' => $this->db->affected_rows()
            ]);
            return;
        }
    }

    public function deleteUser($encrypted_menu_id = null)
    {
        $this->checkAccess($encrypted_menu_id, 4);

        $id = post('id');

        if (empty($id)) {
            $this->_set_failed([
                'status' => false,
            ]);

            return;
        }

        $user_id = decode_id($id);

        $this->db->where('id', $user_id);
        $this->db->update('users', ['deleted_at' => date('Y-m-d H:i:s'), 'active' => 0]);

        if ($this->db->affected_rows() > 0) {
            $this->_set_success([
                'status' => true,
                'affected' => $this->db->affected_rows()
            ]);
            return;
        } else {
            $this->_set_failed([
                'status' => false,
                'affected' => $this->db->affected_rows()
            ]);
            return;
        }
    }

    public function updateRole($encrypted_menu_id = null)
    {
        $this->checkAccess($encrypted_menu_id, 3);

        $id = post('id');

        if (empty($id)) {
            $this->_set_failed([
                'status' => false,
            ]);
            return;
        }

        $user_id = decode_id($id);

        $roles = $this->db->where([
            'deleted_at' => null,
            'active' => 1,
        ])->get('roles')->result();

        $affected = 0;

        foreach ($roles as $role) {
            $role_id = post('role_' . $role->id);

            if (!empty($role_id)) {
                $affected += $this->otoritas->updateRole($role->id, $user_id, 1);
            } else {
                $affected += $this->otoritas->updateRole($role->id, $user_id, 0);
            }
        }

        if ($affected) {
            $this->_set_success(['status' => true]);
            return;
        } else {
            $this->_set_failed(['status' => false]);
            return;
        }
    }

    public function exportExcelUsers()
    {
        $data = $this->user->get_datatables();
        $this->load->helper('excelUsers_helper');

        // Generate Excel file
        $filename = 'user_data.xlsx';
        generate_excel($data, $filename);

        // Download the file
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        readfile($filename);
        unlink($filename); // delete file after sending
        exit;
    }

    public function exportPdfUsers()
    {
        $data = $this->user->get_datatables();
        $this->load->helper('pdf_helper');
        $html = $this->load->view('export_pdf', ['data' => $data], true);

        // Generate PDF file
        $filename = 'user_data.pdf';
        generate_pdf($html, $filename);
    }
}

/* End of file Users.php */
