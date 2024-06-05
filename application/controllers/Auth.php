<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        $this->login();
    }

    public function login()
    {
        $this->load->view('auth/login', [
            'csrf' => [
                'token_name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash(),
            ]
        ]);
    }

    public function process()
    {
        $username = post('username');
        $password = post('password');

        $process = $this->auth->validate($username, $password);

        $this->changeSession($process);
    }

    public function changeSession($data)
    {
        $person = $data['data'];

        $array = array(
            'id' => encode_id($person->id),
            'full_name' => $person->full_name
        );

        $this->session->set_userdata($array);

        $role_check = $this->db->select('roles.*, user_role.role_id, user_role.user_id')
            ->where('user_role.user_id', $person->id)
            ->where('user_role.active', 1)
            ->where('roles.active', 1)
            ->join('roles', 'roles.id = user_role.role_id')
            ->get('user_role');

        $role_count = (int) $role_check->num_rows();

        // echo json_encode([$role_check->result(), $role_count]);
        // die;

        if ($role_count > 0) {
            if ($role_count == 1) {
                $role_data = $role_check->row();

                $session_data = array(
                    'tahun' => date('Y'),
                    'role_id' => encode_id($role_data->role_id),
                    'role_name' => $role_data->name,
                    'multirole' => false,
                );

                $this->session->set_userdata($session_data);
                $menu = $this->db->where([
                    'name' => 'beranda',
                    'route' => 'dashboard/index'
                ])->get('menus')->row();

                redirect($menu->route . '/' . encode_id($menu->id));
            } else {
                redirect('auth/chooseRole/' . encode_id($person->id));
            }
        } else {
            $this->session->set_flashdata('error_messages', 'Pengguna "<b>' . $person->full_name . '</b>" tidak memiliki hak akses di dalam aplikasi.');
            redirect(base_url('auth/login'), 'refresh');
        }

        echo json_encode($array);
    }

    public function chooseRole($user_id)
    {
        $user_id = decode_id($user_id);

        if (empty($this->session->userdata('id'))) {
            $this->session->set_flashdata('error_messages', 'Anda belum login!');
            redirect(base_url('auth/login'), 'refresh');
        }

        $user_data = $this->db->select('id, full_name')->where('id', $user_id)->get('users')->row();

        $this->db->from('user_role');
        $this->db->join('roles', 'user_role.role_id = roles.id', 'left');
        $this->db->select('user_role.*, roles.name');
        $this->db->where([
            'roles.deleted_at' => null,
            'user_role.user_id' => $user_id,
            'user_role.active' => 1
        ]);

        $roles = $this->db->get()->result();

        $this->load->view('auth/roleChange', [
            'user_data' => $user_data,
            'roles' => $roles,
            'csrf' => [
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            ]
        ]);
    }

    public function choose()
    {
        $role_id = decode_id(post('role_id'));
        $user_id = decode_id(post('user_id'));

        if (!empty($this->session->userdata('role_id'))) $this->session->unset_userdata('role_id');

        if ($user_id != decode_id($this->session->userdata('id'))) {
            $this->session->set_flashdata('error_messages', 'Id pengguna tidak sesuai dengan yang dimasukkan.');
            redirect(base_url('auth/login'), 'refresh');
        }

        if (empty($role_id)) $this->chooseRole(encode_id($user_id));

        $role_data = $this->db->where('id', $role_id)->where('deleted_at is null')->get('roles')->row();

        $session_data = array(
            'tahun' => date('Y'),
            'role_id' => encode_id($role_id),
            'role_name' => $role_data->name,
            'multirole' => true,
        );

        $this->session->set_userdata($session_data);

        $menu = $this->db->where('name', 'beranda')->get('menus')->row();

        redirect($menu->route . '/' . encode_id($menu->id));
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}

/* End of file Auth.php */
