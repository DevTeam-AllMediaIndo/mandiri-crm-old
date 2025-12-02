<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;

// Inisialisasi Dompdf
$dompdf = new Dompdf();

// HTML sederhana
$html = '
    <h1>Hello, Alfi!</h1>
    <p>Ini contoh PDF yang simpel dari HTML ke PDF pakai Dompdf.</p>
';

// Load HTML ke Dompdf
$dompdf->loadHtml($html);

// Atur ukuran dan orientasi kertas
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Outputkan ke browser
$dompdf->stream('contoh-pdf.pdf', ['Attachment' => false]);
