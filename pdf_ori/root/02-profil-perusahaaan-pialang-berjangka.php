
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
            tb_racc.ACC_F_PROFILE_DATE,
            tb_racc.ACC_F_PROFILE_IP
        FROM tb_racc
        JOIN tb_member
        ON(tb_member.MBR_ID = tb_racc.ACC_MBR)
        WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id_acc.'"
        LIMIT 1
    ');
    if(mysqli_num_rows($SQL_QUERY) > 0){
        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        $ACC_01_AGGDATE = $RESULT_QUERY['ACC_F_PROFILE_DATE'];
        $ACC_F_PROFILE_IP = $RESULT_QUERY['ACC_F_PROFILE_IP'];
    } else {
        $ACC_01_AGGDATE = '';
        $ACC_F_PROFILE_IP = '';
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
            </head>
            <body>
                <table style="width:100%">
                    <tr>
                        <td width="47%" style="vertical-align: middle; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$setting_pdf_logo."")).'" width="100%"></td>
                        <td width="6%">&nbsp;</td>
                        <td width="47%" style="text-align:right; vertical-align: top; ">
                            <p>
                                <h3>'.$web_name_full.'</h3>
                                '.$setting_central_office_address.'
                            </p>
                        </td>
                    </tr>
                </table>
                <hr>
                <table style="width:100%">
                    <tr>
                        <td width="50%" style="vertical-align: top; "><strong><small>Formulir Nomor : 107.PBK.01</small></strong></td>
                        <td width="50%" style="text-align:right; ">
                            <small>
                                Lampiran Peraturan Kepala Badan Pengawas<br>
                                Perdagangan Berjangka Komoditi<br>
                                Nomor : 107/BAPPEBTI/PER/11/2013
                            </small>
                        </td>
                    </tr>
                </table>
                <div style="text-align:center;vertical-align: middle;padding: 10px 0 10px 0;">
                    <h3>PROFIL PERUSAHAAN PIALANG BERJANGKA</h3>
                </div>
                <div style="border:1px solid black;padding:5px;">
                    <table style="width:100%">
                        <tr>
                            <td width="45%" style="vertical-align: top;">Nama perusahaan</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">'.$web_name_full.'</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Alamat</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">'.$setting_central_office_address.'</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Nomor Telepon</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">'.$setting_number_phone.'</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">No Fax</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">'.$setting_fax_number.'</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">E-Mail</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">'.$setting_email_support_name.'</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Home-Page</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"> '.$setting_front_web_link.'</td>
                        </tr>
                    </table>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Susunan Pengurus Perusahaan :</strong>
                    </div>
                    <table style="width:100%">
                        <tr>
                            <td colspan="4" style="vertical-align: top;"><div style="margin:0px 3px;"><strong>Dewan Direksi</strong></div></td>
                        </tr>
                        <tr>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 5px;">1.</div></td>
                            <td style="vertical-align: top;">President Direktur</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">Denny Setia Budhi</td>
                        </tr>
                        <tr>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 5px;">2.</div></td>
                            <td style="vertical-align: top;">Direktur Kepatuhan</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">Beniardi Nugroho, S.Sos</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="vertical-align: top;"><div style="margin:0px 3px;"><strong>Dewan Komisaris</strong></div></td>
                        </tr>
                        <tr>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 5px;">1.</div></td>
                            <td style="vertical-align: top;">Komisaris Utama</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">Nicko Koerniawan</td>
                        </tr>
                        <tr>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 5px;">2.</div></td>
                            <td style="vertical-align: top;">Komisaris</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">Ir. Harjanto Halim</td>
                        </tr>
                    </table>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Susunan Pemegang Saham Perusahaan :</strong><br>
                        <ol>
                            <li> Ir. Harjanto Halim</li>
                            <li> Nicko Koerniawan</li>
                        </ol>
                    </div>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Nomor dan Tanggal Izin Usaha Dari Bappebti :</strong><br>
                        <table width="100%" style="margin:0 20px;">
                            <tr>
                                <td width="40%">No. 912/BAPPEBTI/SI/8/2006</td>
                                <td width="10%">&nbsp;</td>
                                <td>Tanggal : 25 Agustus 2006</td>
                            </tr>
                        </table>
                    </div>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Nomor dan Tanggal Keanggotaan Bursa Berjangka</strong><br>
                        <table width="100%" style="margin:0 20px;">
                            <tr>
                                <td width="40%">No. SPAB - 142/BBJ/08/05</td>
                                <td width="10%">&nbsp;</td>
                                <td>Tanggal : 31 Agustus 2005</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br>
                <table style="width:100%">
                    <tr>
                        <td width="47%" style="vertical-align: middle; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$setting_pdf_logo."")).'" width="100%"></td>
                        <td width="6%">&nbsp;</td>
                        <td width="47%" style="text-align:right; vertical-align: top; ">
                            <p>
                                <h3>'.$web_name_full.'</h3>
                                '.$setting_central_office_address.'
                            </p>
                        </td>
                    </tr>
                </table>
                <hr>
                <div style="border:1px solid black;padding:5px;margin-top:45px;">
                    <div style="text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Nomor dan Tanggal Keanggotaan Lembaga Kliring Berjangka</strong><br>
                        <table width="100%" style="margin:0 20px;">
                            <tr>
                                <td width="40%">No. 70/AK-KBI/I/2011</td>
                                <td width="10%">&nbsp;</td>
                                <td>Tanggal : 28 Januari 2011</td>
                            </tr>
                        </table>
                    </div>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Nomor dan Tanggal Persetujuan Sebagai Peserta Sistem Perdagangan Alternatif</strong><br>
                        <table width="100%" style="margin:0 20px;">
                            <tr>
                                <td width="40%">No. 22/BAPPEBTI/SP/04/2011</td>
                                <td width="10%">&nbsp;</td>
                                <td>Tanggal : 21 April 2011</td>
                            </tr>
                        </table>
                    </div>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Nama Penyelenggara Sistem Perdagangan Alternatif</strong><br>
                        <div style="margin:0 23px;">PT. Real Time Forex Indonesia</div>
                    </div>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Kontrak Berjangka Yang Diperdagangkan *)</strong><br>
                        <div style="margin:0 23px;">Kontrak Gulir Index Emas (KIE),Kontrak Berjangka Emas Gold 100, Kontrak Berjangka Emas Gold 250, Kontrak Berjangka Emas Gold</div>
                    </div>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Kontrak Derivatif Syariah Yang Diperdagangkan *)</strong><br>
                        <div style="margin:0 23px;">-</div>
                    </div>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Kontrak Derivatif dalam Sistem Perdagangan Alternatif *)</strong><br>
                        <div style="margin:0 23px;">CFD Mata Uang Asing ( EUR/USD, GBP/USD, AUD/USD, USD/JPY, USD/CHF ), CFD Indeks Saham ( Hangseng, Nikkei, Kospi), CFD Komoditi Emas ( XAU/USD )</div>
                    </div>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Kontrak Derivatif dalam Sistem Perdagangan Alternatif dengan volume minimum 0,1 (nol koma satu) lot Yang Ddiperdagangkan *)</strong><br>
                        <div style="margin:0 23px;">CFD Mata Uang Asing ( EUR/USD, GBP/USD, AUD/USD, USD/JPY, USD/CHF ), CFD Indeks Saham ( Hangseng, Nikkei, Kospi), CFD Komoditi Emas ( XAU/USD )</div>
                    </div>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Biaya Secara Rinci yang Di Bebankan Pada Nasabah</strong>
                        <div style="margin:0 23px;">Trading Rules (Spesifikasi Kontrak)</div>
                    </div>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Nomor atau alamat email jika terjadi keluhan :</strong><br>
                        <div style="margin:0 23px;">'.$setting_office_number.' / '.$setting_email_support_name.'</div>
                    </div>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Sarana Penyelesaian perselisihan yang dipergunakan apabila terjadi perselisihan :</strong><br>
                        <div style="margin:0 23px;">Penyelesaian Perselisihan Mempergunakan Sarana Melalui Prosedur Sebagai Berikut.</div>
                        <ol>
                            <li>Musyawarah Mufakat/deliberation</li>
                            <li>Pengadilan Negeri/District Court</li>
                            <li>BAKTI/Commodity Futures Trading Arbitration</li>
                        </ol>
                    </div>
                </div>
                <table style="width:100%">
                    <tr>
                        <td width="47%" style="vertical-align: middle; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$setting_pdf_logo."")).'" width="100%"></td>
                        <td width="6%">&nbsp;</td>
                        <td width="47%" style="text-align:right; vertical-align: top; ">
                            <p>
                                <h3>'.$web_name_full.'</h3>
                                '.$setting_central_office_address.'
                            </p>
                        </td>
                    </tr>
                </table>
                <hr>
                <div style="height:1px;"></div>
                <div style="border:1px solid black;padding:5px;margin-top:45px;">
                    <div style="text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Nama-nama Wakil Pialang Berjangka yang Bekerja di Perusahaan Pialang Berjangka</strong><br>
                        <table style="margin-left:20px;width:100%">
                            <tr>
                                <td>1.</td>
                                <td>Denny Setia Budhi</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>Deni Sundawa PK</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>Ridhwan</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>Handra Siagian</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td>Lucy Wulandari</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>6.</td>
                                <td>Indah Rizky Amelia</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>7.</td>
                                <td>Sudjani Sarkawi</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>8.</td>
                                <td>Emilia Lestari</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>9.</td>
                                <td>Scot Delano Kulit</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>10.</td>
                                <td>Peter Gunawan</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>11.</td>
                                <td>Wawan Yuwantono</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>12.</td>
                                <td>Edi Purwanto</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>13.</td>
                                <td>Alwin Anggiatma</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                    </div>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Nama – Nama Wakil Pialang Berjangka yang secara khusus ditunjuk oleh Pialang Berjangka untuk melakukanVerifikasi dalam rangka penerimaan Nasabah elektronik on- Line</strong><br>
                        <table width="100%" style="margin:0 20px;">
                            <tr>
                                <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 5px;">1.</div></td>
                                <td style="vertical-align: top;">Denny Sundawa PK</td>
                            </tr>
                        </table>
                    </div>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Nomor Rekening Terpisah (Segregated Account) Perusahaan Pialang Berjangka:</strong><br>
                        <table width="100%" style="margin:0 20px;">
                            <tr>
                                <td style="vertical-align: top;">Bank Central Asia (Bank BCA)</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">IDR</div></td>
                                <td style="vertical-align: top;">035-311-0583</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Bank Central Asia (Bank BCA)</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">USD</div></td>
                                <td style="vertical-align: top;">035-311-0591</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Bank CIMB NIAGA</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">IDR</div></td>
                                <td style="vertical-align: top;">809.62.626.210.0</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Bank CIMB NIAGA</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">IDR</div></td>
                                <td style="vertical-align: top;">800.01.240.484.0</td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
                <div style="margin-bottom: 100px"></div>
                <table style="width:100%">
                    <tr>
                        <td width="47%" style="vertical-align: middle; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$setting_pdf_logo."")).'" width="100%"></td>
                        <td width="6%">&nbsp;</td>
                        <td width="47%" style="text-align:right; vertical-align: top; ">
                            <p>
                                <h3>'.$web_name_full.'</h3>
                                '.$setting_central_office_address.'
                            </p>
                        </td>
                    </tr>
                </table>
                <hr>
                <div style="text-align:center;margin-top:25px;">
                    <strong>PERNYATAAN TELAH MEMBACA PROFIL PERUSAHAAN PIALANG BERJANGKA</strong><br>
                    <p>Dengan mengisi kolom “YA” di bawah ini, saya menyatakan bahwa saya telah membaca dan menerima informasi
                    <strong>PROFIL PERUSAHAAN PIALANG BERJANGKA</strong>, mengerti dan memahami isinya.</p>
                </div>
                <div style="text-align:center;margin-top:25px;margin-left:25%">
                    <table>
                        <tr>
                            <td>Pernyataan Menerima</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>YA</strong></td>
                        </tr>
                        <tr>
                            <td>Menyatakan pada tanggal</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>'.date('Y-m-d H:i:s', strtotime($ACC_01_AGGDATE)).'</strong></td>
                        </tr>
                        <tr>
                            <td>IP Address</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>'.$ACC_F_PROFILE_IP.'</strong></td>
                        </tr>
                    </table>
                </div>
            </body>
        </html>
    ';

    $dompdf->loadHtml($content);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("".$web_name_full." - 107.PBK.01",array("Attachment"=>false));
    exit(0);
    
?>