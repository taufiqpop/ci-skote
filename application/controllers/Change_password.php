<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Change_password extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        //Do your magic here
    }

    private function _validation()
    {
        $this->form_validation->set_rules('old_pass', 'Password Lama', 'trim|required');
        $this->form_validation->set_rules('new_pass', 'Password Baru', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('pass_conf', 'Password Konfirmasi', 'trim|required|min_length[5]|matches[new_pass]');


        if ($this->form_validation->run() === FALSE) {
            $this->_set_failed([
                'status' => false,
                'msg' => 'Terjadi kesalahan',
                'data' => $this->generateFormError([
                    'old_pass',
                    'new_pass',
                    'pass_conf',
                ])
            ], 422);
        }
    }

    public function change()
    {
        $this->_validation();

        $old_pass = post('old_pass');
        $new_pass = post('new_pass');

        $user = $this->db->where('id', $this->current_id)->get('users')->row();

        if (password_verify($old_pass, $user->password) === false) {
            $this->_set_failed([
                'status' => false,
                'msg' => 'Password lama salah'
            ], 400);
        }

        $this->db->trans_begin();
        $this->db->where('id', $this->current_id);
        $this->db->update('users', [
            'password' => $this->auth->generatePassword($new_pass)
        ]);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $this->_set_failed([
                'status' => false,
                'msg' => 'Terjadi kesalahan',
                'text' => $this->db->error(),
            ], 400);
        }

        $this->db->trans_commit();
        $this->session->sess_destroy();
        $this->_set_success([
            'status' => true,
            'msg' => 'Password berhasil diganti'
        ]);
    }
}

/* End of file Change_password.php */
