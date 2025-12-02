<?php
    date_default_timezone_set("Asia/Jakarta");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../../setting.php';
    require_once 'vendor/autoload.php';
    use Dompdf\Dompdf;
    $dompdf = new Dompdf();
    
    $id_acc = form_input($_GET["x"]);
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
                    .titik_dua {vertical-align: top; text-align:center;}
                    ol > li {
                        margin-bottom: 10px !important;
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
                <div style="text-align:center;"><h4>PERNYATAAN PENGUNGKAPAN<br><i>(DISCLOSURE STATEMENT)</i></h4></div>
                <ol class="text-justify">
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
                            <td>Pernyataan menerima/tidak *)</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><input type="checkbox" style="display: inline;" checked disabled><strong>Ya</strong></td>
                            <td><input type="checkbox" style="display: inline;" disabled><strong>Tidak</strong></td>
                        </tr>
                        <tr>
                            <td>Pernyataan pada tanggal</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td colspan="2"><strong>'.date('Y-m-d H:i:s', strtotime("$ACC_F_PENGLAMAN_DATE -4 seconds")).'</strong></td>
                        </tr>
                        <!-- <tr>
                            <td>IP Address</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>'.$ACC_F_DISC_IP.'</strong></td>
                        </tr> -->
                    </table>
                </div>
                <p>*) Pilih salah satu</p>
                <div class="page-break"></div>
                <div style="text-align:center;"><h4>PERNYATAAN PENGUNGKAPAN<br><i>(DISCLOSURE STATEMENT)</i></h4></div>
                <ol class="text-justify">
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
                            <td>Pernyataan menerima/tidak *)</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><input type="checkbox" style="display: inline;" checked disabled><strong>Ya</strong></td>
                            <td><input type="checkbox" style="display: inline;" disabled><strong>Tidak</strong></td>
                        </tr>
                        <tr>
                            <td>Pernyataan pada tanggal</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td colspan="2"><strong>'.date('Y-m-d H:i:s', strtotime("$ACC_F_RESK_DATE -4 seconds")).'</strong></td>
                        </tr>
                        <!-- <tr>
                            <td>IP Address</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>'.$ACC_F_DISC_IP.'</strong></td>
                        </tr> -->
                    </table>
                </div>
                <p>*) Pilih salah satu</p>
                <div class="page-break"></div>
                <div style="text-align:center;"><h4>PERNYATAAN PENGUNGKAPAN<br><i>(DISCLOSURE STATEMENT)</i></h4></div>
                <ol class="text-justify">
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
                            <td>Pernyataan menerima/tidak *)</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><input type="checkbox" style="display: inline;" checked disabled><strong>Ya</strong></td>
                            <td><input type="checkbox" style="display: inline;" disabled><strong>Tidak</strong></td>
                        </tr>
                        <tr>
                            <td>Pernyataan pada tanggal</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td colspan="2"><strong>'.date('Y-m-d H:i:s', strtotime("$ACC_F_PERJ_DATE -4 seconds")).'</strong></td>
                        </tr>
                        <!-- <tr>
                            <td>IP Address</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>'.$ACC_F_DISC_IP.'</strong></td>
                        </tr> -->
                    </table>
                </div>
                <p>*) Pilih salah satu</p>
                <!-- 
                <div class="page-break"></div>
                <div style="text-align:center;"><h4>PERNYATAAN PENGUNGKAPAN<br><i>(DISCLOSURE STATEMENT)</i></h4></div>
                <ol class="text-justify">
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
                            <td>Pernyataan menerima/tidak *)</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><input type="checkbox" style="display: inline;" checked disabled><strong>Ya</strong></td>
                            <td><input type="checkbox" style="display: inline;" disabled><strong>Tidak</strong></td>
                        </tr>
                        <tr>
                            <td>Pernyataan pada tanggal</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td colspan="2"><strong>'.date('Y-m-d H:i:s', strtotime($ACC_F_DISC_DATE)).'</strong></td>
                        </tr>
                        <tr>
                            <td>IP Address</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>'.$ACC_F_DISC_IP.'</strong></td>
                        </tr>
                    </table>
                </div>
                <p>*) Pilih salah satu</p> -->
            </body>
        </html>
    ';

    $dompdf->loadHtml($content);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("".$web_name_full." - Disclousure Statement",array("Attachment"=>0));
    
?>