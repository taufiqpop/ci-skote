<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function generate_excel($data, $filename)
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header
    $sheet->setCellValue('A1', 'Username');
    $sheet->setCellValue('B1', 'Nama Lengkap');

    // Data
    $row = 2;
    foreach ($data as $item) {
        $sheet->setCellValue('A' . $row, $item->username);
        $sheet->setCellValue('B' . $row, $item->full_name);
        $row++;
    }

    // Save
    $writer = new Xlsx($spreadsheet);
    $writer->save($filename);
}
