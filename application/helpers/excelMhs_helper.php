<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function generate_excel($data, $filename)
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header
    $sheet->setCellValue('A1', 'NIM');
    $sheet->setCellValue('B1', 'Nama Lengkap');

    // Data
    $row = 2;
    foreach ($data as $item) {
        $sheet->setCellValue('A' . $row, $item->nim);
        $sheet->setCellValue('B' . $row, $item->nama);
        $row++;
    }

    // Save
    $writer = new Xlsx($spreadsheet);
    $writer->save($filename);
}
