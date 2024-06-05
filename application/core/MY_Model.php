<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
    }

    public function datatableSearchSort()
    {
        $i = 0;

        foreach ($this->column_search as $item) { // looping awal
            if (post('search', false)['value']) { // jika datatable mengirimkan pencarian dengan metode POST

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    $this->db->like($item, post('search', false)['value']);
                } else {
                    $this->db->or_like($item, post('search', false)['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (post('order', false)) {
            $this->db->order_by($this->column_order[post('order', false)['0']['column']], post('order', false)['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
}

/* End of file MY_Model.php */
