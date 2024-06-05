<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->_auth();

        $this->load->model('Mahasiswa_model', 'mahasiswa');
    }

    public function index($encrypted_menu_id = null)
    {
        $this->checkPermission($encrypted_menu_id, 1);

        $data = [
            'title' => 'Mahasiswa - Daftar Mahasiswa',
            'menu_id' => $encrypted_menu_id,
            'menu_active' => 'mahasiswa',
            'plugins' => ['datatable'],
            'script_js' => base_url('assets/js/page/mahasiswa/list.js'),
        ];

        $this->_display($data, 'mahasiswa/list');
    }

    public function data($encrypted_menu_id = null)
    {
        $this->checkAccess($encrypted_menu_id, 1);

        $list = $this->mahasiswa->get_datatables();

        $data = [];
        $no = post('start');

        foreach ($list as $field) {
            $no++;

            $field->no = $no;
            $field->its_you = ($this->current_id == $field->id) ? true : false;
            $field->id = encode_id($field->id);

            $data[] = $field;
        }

        $output = [
            'draw' => post('draw'),
            'recordsTotal' => $this->mahasiswa->count_all(),
            'recordsFiltered' => $this->mahasiswa->count_filtered(),
            'data' => $data,
        ];

        $this->_set_success($output);
        return;
    }

    public function store($encrypted_menu_id = null)
    {
        $this->checkAccess($encrypted_menu_id, 1);

        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('nim', 'NIM', 'required');

        if ($this->form_validation->run() == false) {
            $err = [
                'nim',
                'nama',
            ];

            $this->_set_failed(['status' => false, 'data' => $this->generateFormError($err)], 422);
        }

        $nim = post('nim');
        $nama = post('nama');

        $data = [
            'id' => uuid(),
            'nim' => $nim,
            'nama' => $nama
        ];

        $this->db->insert('mahasiswa', $data);

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

    public function update($encrypted_menu_id = null)
    {
        $this->checkAccess($encrypted_menu_id, 3);

        $this->form_validation->set_rules('nim', 'NIM', 'required');
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');

        if ($this->form_validation->run() == false) {
            $err = [
                'nim',
                'nama',
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

        $mahasiswa_id = decode_id($id);
        $nim = post('nim');
        $nama = post('nama');

        $data = [
            'nim' => $nim,
            'nama' => $nama,
        ];

        $this->db->where('id', $mahasiswa_id);
        $this->db->update('mahasiswa', $data);

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

    public function deleteMahasiswa($encrypted_menu_id = null)
    {
        $this->checkAccess($encrypted_menu_id, 4);

        $id = post('id');

        if (empty($id)) {
            $this->_set_failed([
                'status' => false,
            ]);

            return;
        }

        $mahasiswa_id = decode_id($id);

        $this->db->where('id', $mahasiswa_id);
        $this->db->update('mahasiswa', ['deleted_at' => date('Y-m-d H:i:s')]);

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

    public function exportExcelMhs()
    {
        $data = $this->mahasiswa->get_datatables();
        $this->load->helper('excelMhs_helper');

        // Generate Excel file
        $filename = 'mhs_data.xlsx';
        generate_excel($data, $filename);

        // Download the file
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        readfile($filename);
        unlink($filename); // delete file after sending
        exit;
    }

    public function exportPdfMhs()
    {
        $data = $this->mahasiswa->get_datatables();
        $this->load->helper('pdf_helper');
        $html = $this->load->view('export_pdf', ['data' => $data], true);

        // Generate PDF file
        $filename = 'mhs_data.pdf';
        generate_pdf($html, $filename);
    }
}

/* End of file Mahasiswa.php */
