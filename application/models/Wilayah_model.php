<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wilayah_model extends MY_Model
{
    var $table = 'wilayah_kemdagri';
    var $level_provinsi = 1;
    var $level_kabupaten = 2;
    var $level_kecamatan = 3;
    var $level_kelurahan = 4;

    public function getProvinsi($selected = null)
    {
        $this->db->select('kode_bersih, nama');
        $this->db->from($this->table);
        $this->db->where('id_level_wilayah', $this->level_provinsi);

        if (!empty($selected)) {
            $this->db->where('kode_bersih', $selected);
        }

        $row = $this->db->get();

        if (!empty($selected)) {
            return $row->row();
        }

        return $row->result();
    }

    public function getKabupaten($kode_provinsi = null, $selected = null)
    {
        if (empty($kode_provinsi)) {
            return [];
        }
        $this->db->select('kode_bersih, nama');
        $this->db->from($this->table);
        $this->db->where('id_level_wilayah', $this->level_kabupaten);
        $this->db->like('kode_bersih', $kode_provinsi, 'after');


        if (!empty($selected)) {
            $this->db->where('kode_bersih', $selected);
        }

        $row = $this->db->get();

        if (!empty($selected)) {
            return $row->row();
        }

        return $row->result();
    }

    public function getKecamatan($kode_kabupaten = null, $selected = null)
    {
        if (empty($kode_kabupaten)) {
            return [];
        }
        $this->db->select('kode_bersih, nama');
        $this->db->from($this->table);
        $this->db->where('id_level_wilayah', $this->level_kecamatan);

        $this->db->like('kode_bersih', $kode_kabupaten, 'after');

        if (!empty($selected)) {
            $this->db->where('kode_bersih', $selected);
        }

        $row = $this->db->get();

        if (!empty($selected)) {
            return $row->row();
        }

        return $row->result();
    }

    public function getKelurahan($kode_kecamatan = null, $selected = null)
    {
        if (empty($kode_kecamatan)) {
            return [];
        }

        $this->db->select('kode_bersih, nama');
        $this->db->from($this->table);
        $this->db->where('id_level_wilayah', $this->level_kelurahan);
        $this->db->like('kode_bersih', $kode_kecamatan, 'after');


        if (!empty($selected)) {
            $this->db->where('kode_bersih', $selected);
        }

        $row = $this->db->get();

        if (!empty($selected)) {
            return $row->row();
        }

        return $row->result();
    }
}

/* End of file Wilayah_model.php */
