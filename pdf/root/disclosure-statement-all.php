<?php
date_default_timezone_set("Asia/Jakarta");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../setting.php';
require_once 'vendor/autoload.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();

$id_acc = (isset($pdfotpt)) ? form_input($pdfotpt) : ((isset($_GET["x"])) ? form_input($_GET["x"]) : 0);
$SQL_QUERY = mysqli_query($db, '
    SELECT
    *
    FROM tb_racc
    JOIN tb_member
    ON(tb_member.MBR_ID = tb_racc.ACC_MBR)
    WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id_acc.'"
    LIMIT 1
');
if(mysqli_num_rows($SQL_QUERY) > 0){
    $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
    /** Disc 1 & 2 */
    $ACC_F_PENGLAMAN_DATE = $RESULT_QUERY['ACC_F_PENGLAMAN_DATE'];
    $ACC_F_RESK_DATE = $RESULT_QUERY['ACC_F_RESK_DATE'];
    $ACC_F_PERJ_DATE = $RESULT_QUERY['ACC_F_PERJ_DATE'];
    $ACC_F_DISC_DATE = $RESULT_QUERY['ACC_F_DISC_DATE'];
    $ACC_F_DISC_IP = $RESULT_QUERY['ACC_F_DISC_IP'];



} else {
    $ACC_F_PENGLAMAN_DATE = '';
    $ACC_F_RESK_DATE = '';
    $ACC_F_PERJ_DATE = '';
    $ACC_F_DISC_DATE = '';
    $ACC_F_DISC_IP = '';
};

$content = '
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, minimum-scale=1,0, maximum-scale=1.0">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous">
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
            <style>
                body { font-family: "Times New Roman", serif; margin-top: 150px; }
                header { position: fixed; top: 0px; left: 0; right: 0; height: 50px;}
                .titik_dua {vertical-align: top; text-align:right;width:1%;}
                .content {vertical-align: top;}
                .page-break { page-break-before: always; }
                .judul { border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin-bottom:10px; }
                .text-center { text-align:center; }
                .text-justify { text-align:justify; }
                .text-right { text-align:right; }
            </style>
            
            <style>
                ol {
                    list-style: none;
                    margin-left: 0;
                    padding-left: 0;
                }
                li {
                    display: block;
                    margin-bottom: .5em;
                    margin-left: 2.5em;
                }
                li::before {
                    display: inline-block;
                    content: attr(seq);
                    width: 2em;
                    margin-left: -2em;
                }
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
            <div class="conatiner">
                <div style="text-align:center;"><h4>PERNYATAAN PENGUNGKAPAN<br><i>(DISCLOSURE STATEMENT)</i></h4></div>
                <ol>
                    <li>Perdagangan Berjangka BERISIKO SANGAT TINGGI tidak cocok untuk semua orang. Pastikan bahwa anda SEPENUHNYA MEMAHAMI RISIKO ini sebelum melakukan perdagangan.</li>
                    <li>Perdagangan Berjangka merupakan produk keuangan dengan leverage dan dapat menyebabkan KERUGIAN ANDA MELEBIHI setoran awal Anda. Anda harus siap apabila SELURUH DANA ANDA HABIS.</li>
                    <li>TIDAK ADA PENDAPATAN TETAP (FIXED INCOME) dalam Perdagangan Berjangka.</li>
                    <li>Apabila anda PEMULA kami sarankan untuk mempelajari mekanisme transaksinya, PERDAGANGAN BERJANGKA membutuhkan pengetahuan dan pemahaman khusus.</li>
                    <li>ANDA HARUS MELAKUKAN TRANSAKSI SENDIRI, segala risiko yang akan timbul akibat transaksi sepenuhnya akan menjadi tanggung jawab Saudara.</li>
                    <li>User id dan password BERSIFAT PRIBADI DAN RAHASIA, anda bertanggung jawab atas penggunaannya, JANGAN SERAHKAN ke pihak lain terutama Wakil Pialang Berjangka dan pegawai Pialang Berjangka.</li>
                    <li>ANDA berhak menerima LAPORAN ATAS TRANSAKSI yang anda lakukan. Waktu anda 2 X 24 JAM UNTUK MEMBERIKAN SANGGAHAN. Untuk transaksi yang TELAH SELESAI (DONE/SETTLE) DAPAT ANDA CEK melalui system informasi transaksi nasabah yang berfungsi untuk memastikan transaksi anda telah terdaftar di Lembaga Kliring Berjangka.</li>
                </ol>
                <div style="text-align:center;">SECARA DETAIL BACA DOKUMEN PEMBERITAHUAN APLIKASI PEMBUKAAN REKENING TRANSAKSI SECARA ELEKTRONIK ON-LINE</div>
                <div style="text-align:center;margin-top:25px;margin-left:25%">
                    <table>
                        <tr>
                            <td>Pernyataan Menerima</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><input type="checkbox" style="display: inline;" checked disabled><strong>YA</strong></td>
                            <td><input type="checkbox" style="display: inline;" disabled><strong>TIDAK</strong></td>
                        </tr>
                        <tr>
                            <td>Menerima pada tanggal</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>'.date('Y-m-d H:i:s', strtotime("$ACC_F_PENGLAMAN_DATE -4 seconds")).'</strong></td>
                        </tr>
                        <!-- <tr>
                            <td>IP Address</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>'.$ACC_F_DISC_IP.'</strong></td>
                        </tr> -->
                    </table>
                </div>

                <div style="margin-top: 150px;"></div>
                <div style="text-align:center;"><h4>PERNYATAAN PENGUNGKAPAN<br><i>(DISCLOSURE STATEMENT)</i></h4></div>
                <ol>
                    <li>Perdagangan Berjangka BERISIKO SANGAT TINGGI tidak cocok untuk semua orang. Pastikan bahwa anda SEPENUHNYA MEMAHAMI RISIKO ini sebelum melakukan perdagangan.</li>
                    <li>Perdagangan Berjangka merupakan produk keuangan dengan leverage dan dapat menyebabkan KERUGIAN ANDA MELEBIHI setoran awal Anda. Anda harus siap apabila SELURUH DANA ANDA HABIS.</li>
                    <li>TIDAK ADA PENDAPATAN TETAP (FIXED INCOME) dalam Perdagangan Berjangka.</li>
                    <li>Apabila anda PEMULA kami sarankan untuk mempelajari mekanisme transaksinya, PERDAGANGAN BERJANGKA membutuhkan pengetahuan dan pemahaman khusus.</li>
                    <li>ANDA HARUS MELAKUKAN TRANSAKSI SENDIRI, segala risiko yang akan timbul akibat transaksi sepenuhnya akan menjadi tanggung jawab Saudara.</li>
                    <li>User id dan password BERSIFAT PRIBADI DAN RAHASIA, anda bertanggung jawab atas penggunaannya, JANGAN SERAHKAN ke pihak lain terutama Wakil Pialang Berjangka dan pegawai Pialang Berjangka.</li>
                    <li>ANDA berhak menerima LAPORAN ATAS TRANSAKSI yang anda lakukan. Waktu anda 2 X 24 JAM UNTUK MEMBERIKAN SANGGAHAN. Untuk transaksi yang TELAH SELESAI (DONE/SETTLE) DAPAT ANDA CEK melalui system informasi transaksi nasabah yang berfungsi untuk memastikan transaksi anda telah terdaftar di Lembaga Kliring Berjangka.</li>
                </ol>
                <div style="text-align:center;">SECARA DETAIL BACA SELURUH DOKUMEN TRADING RULES DAN DISCLOSURE STATEMENT</div>
                <div style="text-align:center;margin-top:25px;margin-left:25%">
                    <table>
                        <tr>
                            <td>Pernyataan Menerima</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><input type="checkbox" style="display: inline;" checked disabled><strong>YA</strong></td>
                            <td><input type="checkbox" style="display: inline;" disabled><strong>TIDAK</strong></td>
                        </tr>
                        <tr>
                            <td>Menerima pada tanggal</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>'.date('Y-m-d H:i:s', strtotime("$ACC_F_RESK_DATE -4 seconds")).'</strong></td>
                        </tr>
                        <!-- <tr>
                            <td>IP Address</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>'.$ACC_F_DISC_IP.'</strong></td>
                        </tr> -->
                    </table>
                </div>

                <div style="margin-top: 150px;"></div>
                <div style="text-align:center;"><h4>PERNYATAAN PENGUNGKAPAN<br><i>(DISCLOSURE STATEMENT)</i></h4></div>
                <ol>
                    <li>Perdagangan Berjangka BERISIKO SANGAT TINGGI tidak cocok untuk semua orang. Pastikan bahwa anda SEPENUHNYA MEMAHAMI RISIKO ini sebelum melakukan perdagangan.</li>
                    <li>Perdagangan Berjangka merupakan produk keuangan dengan leverage dan dapat menyebabkan KERUGIAN ANDA MELEBIHI setoran awal Anda. Anda harus siap apabila SELURUH DANA ANDA HABIS.</li>
                    <li>TIDAK ADA PENDAPATAN TETAP (FIXED INCOME) dalam Perdagangan Berjangka.</li>
                    <li>Apabila anda PEMULA kami sarankan untuk mempelajari mekanisme transaksinya, PERDAGANGAN BERJANGKA membutuhkan pengetahuan dan pemahaman khusus.</li>
                    <li>ANDA HARUS MELAKUKAN TRANSAKSI SENDIRI, segala risiko yang akan timbul akibat transaksi sepenuhnya akan menjadi tanggung jawab Saudara.</li>
                    <li>User id dan password BERSIFAT PRIBADI DAN RAHASIA, anda bertanggung jawab atas penggunaannya, JANGAN SERAHKAN ke pihak lain terutama Wakil Pialang Berjangka dan pegawai Pialang Berjangka.</li>
                    <li>ANDA berhak menerima LAPORAN ATAS TRANSAKSI yang anda lakukan. Waktu anda 2 X 24 JAM UNTUK MEMBERIKAN SANGGAHAN. Untuk transaksi yang TELAH SELESAI (DONE/SETTLE) DAPAT ANDA CEK melalui system informasi transaksi nasabah yang berfungsi untuk memastikan transaksi anda telah terdaftar di Lembaga Kliring Berjangka.</li>
                </ol>
                <div style="text-align:center;">SECARA DETAIL BACA DOKUMEN PEMBERITAHUAN ADANYA RESIKO DAN DOKUMEN PERJANJIAN PEMBERIAN AMANAT</div>
                <div style="text-align:center;margin-top:25px;margin-left:25%">
                    <table>
                        <tr>
                            <td>Pernyataan Menerima</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><input type="checkbox" style="display: inline;" checked disabled><strong>YA</strong></td>
                            <td><input type="checkbox" style="display: inline;" disabled><strong>TIDAK</strong></td>
                        </tr>
                        <tr>
                            <td>Menerima pada tanggal</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>'.date('Y-m-d H:i:s', strtotime("$ACC_F_PERJ_DATE -4 seconds")).'</strong></td>
                        </tr>
                        <!-- <tr>
                            <td>IP Address</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>'.$ACC_F_DISC_IP.'</strong></td>
                        </tr> -->
                    </table>
                </div>
            </div>
        </body>
    </html>
';

$dompdf->loadHtml($content);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
if(isset($pdfotpt)){
    // $output  = $dompdf->output();
    // $fl_name = realpath(dirname(dirname(__FILE__))).'/'.'Pernyataan_simulasi.pdf';
    // file_put_contents($fl_name, $output);
    // if(isset($ALL_PDF_FILES)){
    //     $ALL_PDF_FILES[] = $fl_name;
    // }
    $htmls = $content;
}else{
    $dompdf->stream("".$web_name_full." - Disclousure Statement",array("Attachment"=>0));
    exit(0);
}