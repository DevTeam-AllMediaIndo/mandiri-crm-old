
<?php
    date_default_timezone_set("Asia/Jakarta");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../../setting.php';
    require_once '../../vendor/autoload.php';
    use Dompdf\Dompdf;
    $dompdf = new Dompdf();

    $id_acc = form_input($_GET["x"]);
    
    $SQL_QUERY = mysqli_query($db, '
        SELECT
            tb_lacc.ACC_01_AGGDATE
        FROM tb_lacc
        JOIN tb_member
        ON(tb_member.MBR_ID = tb_lacc.ACC_MBR)
        WHERE tb_lacc.ACC_LOGIN = LOWER("'.$id_acc.'")
        LIMIT 1
    ');
    if(mysqli_num_rows($SQL_QUERY) > 0){
        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        $ACC_01_AGGDATE = $RESULT_QUERY['ACC_01_AGGDATE'];
    } else {
        $ACC_01_AGGDATE = '';
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
                        <td width="50%" style="vertical-align: top; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://ibftrader.allmediaindo.com/assets/img/logoibf.png")).'" width="75%"></td>
                        <td width="50%" style="text-align:center; ">
                            <h3>PT.International Business Futures</h3>
                            <p>
                                PASKAL HYPER SQUARE BLOK D NO.45-46 JL. H.O.S COKROAMINOTO NO.25-27 BANDUNG, JAWA BARAT – 40181
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
                            <td style="vertical-align: top;">PT.International Business Futures</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Alamat</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">PASKAL HYPER SQUARE BLOK D NO.45-46 JL. H.O.S COKROAMINOTO NO.25-27 BANDUNG, JAWA BARAT – 40181</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Nomor Telepon</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">02286061128</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">No Fax</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">02286061126</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">E-Mail</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">support@ibftrader.com</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Home-Page</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"> https://ibftrader.com</td>
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
                            <td style="vertical-align: top;">Ernawan Sukardi</td>
                        </tr>
                        <tr>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 5px;">2.</div></td>
                            <td style="vertical-align: top;">Direktur Kepatuhan</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">Herlina</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="vertical-align: top;"><div style="margin:0px 3px;"><strong>Dewan Komisaris</strong></div></td>
                        </tr>
                        <tr>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 5px;">1.</div></td>
                            <td style="vertical-align: top;">Komisaris Utama</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">Budiman Wijaya</td>
                        </tr>
                        <tr>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 5px;">2.</div></td>
                            <td style="vertical-align: top;">Komisaris</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">A. Mufti Mardian</td>
                        </tr>
                    </table>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Susunan Pemegang Saham Perusahaan :</strong><br>
                        <ol>
                            <li>Budiman Wijaya</li>
                            <li>A. Mufti Mardian</li>
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
                <br>
                <table style="width:100%">
                    <tr>
                        <td width="50%" style="vertical-align: top; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://ibftrader.allmediaindo.com/assets/img/logoibf.png")).'" width="75%"></td>
                        <td width="50%" style="text-align:center; ">
                            <h3>PT.International Business Futures</h3>
                            <p>
                                PASKAL HYPER SQUARE BLOK D NO.45-46 JL. H.O.S COKROAMINOTO NO.25-27 BANDUNG, JAWA BARAT – 40181
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
                        <div style="margin:0 23px;">(022) 86061125 / Support@ibftrader.com</div>
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
                        <td width="50%" style="vertical-align: top; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://ibftrader.allmediaindo.com/assets/img/logoibf.png")).'" width="75%"></td>
                        <td width="50%" style="text-align:center; ">
                            <h3>PT.International Business Futures</h3>
                            <p>
                                PASKAL HYPER SQUARE BLOK D NO.45-46 JL. H.O.S COKROAMINOTO NO.25-27 BANDUNG, JAWA BARAT – 40181
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
                                <td>Ernawan Sukardi</td>
                                <td>10.</td>
                                <td>Rudi Pandapotan S</td>
                                <td>19.</td>
                                <td>Evriliya Cyti Nurnaini</td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>Beniardi Nugroho</td>
                                <td>11.</td>
                                <td>Tetti Erlinda Gultom</td>
                                <td>20.</td>
                                <td>Fitri Kurnia Sari</td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>Dinan Harjadinata</td>
                                <td>12.</td>
                                <td>Helen Astri Kantinasari</td>
                                <td>21.</td>
                                <td>Andreas Konanjaya</td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>Alvin Hilmansyah</td>
                                <td>13.</td>
                                <td>Adi Nugroho</td>
                                <td>22.</td>
                                <td>Faisal Rahman</td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td>M. Meidy Fazria SH</td>
                                <td>14.</td>
                                <td>Maikona</td>
                                <td>23.</td>
                                <td>Margareth Tuasuun</td>
                            </tr>
                            <tr>
                                <td>6.</td>
                                <td>Muhamad Ramdan Diniarsah</td>
                                <td>15.</td>
                                <td>Resti Ayu Wardhani</td>
                                <td>24.</td>
                                <td>Moch Ali Imron</td>
                            </tr>
                            <tr>
                                <td>7.</td>
                                <td>Muhammat Aris</td>
                                <td>16.</td>
                                <td>Febiyanti</td>
                                <td>25.</td>
                                <td>Endang Yunanda</td>
                            </tr>
                            <tr>
                                <td>8.</td>
                                <td>Novi Asnuriani</td>
                                <td>17.</td>
                                <td>Tega Apria Abdi</td>
                                <td>26.</td>
                                <td>Erwin Ariyanto</td>
                            </tr>
                            <tr>
                                <td>9.</td>
                                <td>Dona Fadhillah</td>
                                <td>18.</td>
                                <td>Romi Hamdani</td>
                                <td>27.</td>
                                <td>Vita Sari Patiska</td>
                            </tr>
                        </table>
                    </div>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Nama – Nama Wakil Pialang Berjangka yang secara khusus ditunjuk oleh Pialang Berjangka untuk melakukanVerifikasi dalam rangka penerimaan Nasabah elektronik on- Line</strong><br>
                        <table width="100%" style="margin:0 20px;">
                            <tr>
                                <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 5px;">1.</div></td>
                                <td style="vertical-align: top;">Alvin Hilmansyah</td>
                            </tr>
                            <tr>
                                <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 5px;">2.</div></td>
                                <td style="vertical-align: top;">Dinan Harjadinata</td>
                            </tr>
                        </table>
                    </div>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                        <strong>Nomor Rekening Terpisah (Segregated Account) Perusahaan Pialang Berjangka:</strong><br>
                        <table width="100%" style="margin:0 20px;">
                            <tr>
                                <td style="vertical-align: top;">Bank Central Asia (Bank BCA)</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">IDR</div></td>
                                <td style="vertical-align: top;">008-3073966</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Bank Central Asia (Bank BCA)</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">USD</div></td>
                                <td style="vertical-align: top;">008-4214210</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Bank Mandiri</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">IDR</div></td>
                                <td style="vertical-align: top;">130-0088881779</td>
                            </tr>
                        </table>
                    </div>
                </div>
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
                    </table>
                </div>
            </body>
        </html>
    ';

    $dompdf->loadHtml($content);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("PT.International Business Futures - 107.PBK.01",array("Attachment"=>0));
    
?>