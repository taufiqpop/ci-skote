<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa_model extends MY_Model
{

    var $table = 'mahasiswa';
    var $column_order = [null, 'nama', 'nim', 'created_at'];
    var $column_search = ['nama', 'nim'];
    var $order = ['created_at' => 'desc'];

    private function _get_datatables_query()
    {
        $this->db->select('mahasiswa.id, mahasiswa.nama, mahasiswa.nim, mahasiswa.created_at');
        $this->db->where([
            'deleted_at' => null,
        ]);
        $this->db->from($this->table);
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
}

/* End of file Mahasiswa_model.php */
