<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Error_page extends MY_Controller
{

    public function index()
    {
    }

    public function access_denied()
    {
        $menu = $this->db->where('name', 'beranda')->get('menus')->row();

        $this->load->view('_layouts/_403', [
            'beranda' => site_url($menu->route . '/' . encode_id($menu->id))
        ]);
    }

    public function not_found()
    {
        if ($this->input->is_ajax_request()) {
            $this->_set_not_found();
        }

        $this->_not_found();
    }
}

/* End of file Error.php */
