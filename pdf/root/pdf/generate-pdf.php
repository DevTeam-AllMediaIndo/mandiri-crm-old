<?php
date_default_timezone_set("Asia/Jakarta");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

use Dompdf\Dompdf;

// Tangkap output dari file PHP
ob_start();
include 'layout.php?content=profile-perusahaan';
$html = ob_get_clean();

// Inisialisasi Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Tampilkan di browser
$dompdf->stream('hasil.pdf', ['Attachment' => false]);
