<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Download_file extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->load->helper('download');
    }


    public function index()
    {
    }

    public function unduh($path = null)
    {
        if (empty($path)) {
            $this->_not_found();
        }

        $decoded_path = decode_id($path);

        force_download($decoded_path, null);
    }
}

/* End of file Download_file.php */
