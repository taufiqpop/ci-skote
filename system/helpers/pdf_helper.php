<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

function generate_pdf($html, $filename)
{
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);

    // Instantiate Dompdf with our options
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser
    $dompdf->stream($filename);
}
