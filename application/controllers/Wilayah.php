<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WIlayah extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Wilayah_model', 'wilayah');
    }


    public function index()
    {
        $this->_not_found();
    }

    public function getKabupaten()
    {
        $kode = get('kode');

        $list = $this->wilayah->getKabupaten($kode);

        if (empty($list)) {
            $this->_set_failed([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
            return;
        }

        array_unshift($list, ['kode_bersih' => '', 'nama' => '']);
        $this->_set_success([
            'status' => true,
            'message' => 'Sukses',
            'data' => $list
        ]);
        return;
    }

    public function getKecamatan()
    {
        $kode = get('kode');

        $list = $this->wilayah->getKecamatan($kode);

        if (empty($list)) {
            $this->_set_failed([
                'status' => false,
                'msg' => 'Data tidak ditemukan',
            ]);
            return;
        }

        array_unshift($list, ['kode_bersih' => '', 'nama' => '']);
        $this->_set_success([
            'data' => $list
        ]);
        return;
    }

    public function getKelurahan()
    {
        $kode = get('kode');

        $list = $this->wilayah->getKelurahan($kode);

        if (empty($list)) {
            $this->_set_failed([
                'status' => false,
                'msg' => 'Data tidak ditemukan '
            ]);
            return;
        }

        array_unshift($list, ['kode_bersih' => '', 'nama' => '']);
        $this->_set_success([
            'data' => $list
        ]);
        return;
    }
}

/* End of file WIlayah.php */
