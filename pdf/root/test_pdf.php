<?php
require 'vendor/autoload.php';
require '../../setting.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Inisialisasi Dompdf dengan opsi tertentu
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

// Konten HTML dengan Header di setiap halaman
$html = '
    <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, minimum-scale=1,0, maximum-scale=1.0">
            <style>
                body { font-family: sans-serif; margin-top: 150px; }
                header { position: fixed; top: 0px; left: 0; right: 0; height: 50px;}
                .titik_dua {vertical-align: top; text-align:right;width:1%;}
                .content {vertical-align: top;}
                .page-break { page-break-before: always; }
                .judul { border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin-bottom:10px; }
            </style>
        </head>
        <body>
            <header>
                <table style="width:100%">
                    <tr>
                        <td width="47%" style="vertical-align: middle; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$setting_pdf_logo."")).'" width="100%"></td>
                        <td width="6%">&nbsp;</td>
                        <td width="47%" style="text-align:right; vertical-align: top; ">
                            <small>
                                <h3>'.$web_name_full.'</h3>
                                '.$setting_central_office_address.'
                            </small>
                        </td>
                    </tr>
                </table>
                <hr>
            </header>
            <div class="content">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <div class="page-break"></div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <div class="page-break"></div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia odio vitae vestibulum vestibulum. Cras venenatis euismod malesuada. Curabitur malesuada fermentum purus, eu tincidunt nulla gravida eu. Donec ultrices consequat velit, sed gravida velit faucibus id. Aenean scelerisque, erat quis bibendum tincidunt, ex dui volutpat felis, at varius sapien ligula eget libero. Suspendisse non urna nec eros fermentum blandit.</p>
            </div>
        </body>
    </html>
';

// Memuat konten HTML
$dompdf->loadHtml($html);

// Mengatur ukuran kertas dan orientasi (opsional)
$dompdf->setPaper('A4', 'portrait');

// Merender HTML menjadi PDF
$dompdf->render();

// Menghasilkan file PDF dan menampilkannya di browser
$dompdf->stream("contoh_pdf_dengan_header.pdf", ["Attachment" => false]);
