
<?php
    date_default_timezone_set("Asia/Jakarta");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // if(!isset($pdfotpt)){
        require_once '../../setting.php';
        require_once 'vendor/autoload.php';
    // }else{
    //     require_once '../vendor/autoload.php';
    // }
    use Dompdf\Dompdf;
    $dompdf = new Dompdf();

    $id_acc = (isset($pdfotpt)) ? form_input($pdfotpt) : ((isset($_GET["x"])) ? form_input($_GET["x"]) : 0);
    $htmls  = '';

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
                
                <style>
                    body { font-family: sans-serif; margin-top: 150px; }
                    header { position: fixed; top: 0px; left: 0; right: 0; height: 50px;}
                    .content { margin-top: 5px; }
                    .titik_dua {vertical-align: top; text-align:center;}
                    .content_atas {vertical-align: top;}
                    .page-break { page-break-before: always; }
                    .judul { border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin-bottom:10px; }
                    .tabless {
                        display: table;
                        border-collapse: separate;
                        border-spacing: 2px;
                        border-color: black;
                    }

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
                <div class="content">
                    <div style="text-align:center;vertical-align: middle;padding: 10px 0 10px 0;">
                        <h3>PROFIL PERUSAHAAN PIALANG BERJANGKA</h3>
                    </div>
                    <div style="border:1px solid black;padding:5px;">
                        <table style="width:100%">
                            <tr>
                                <td width="45%" style="vertical-align: top;">Nama</td>
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
                                <td style="vertical-align: top;">'.$setting_email_sp.'</td>
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
                                <td style="vertical-align: top;">TERRY INDRADI OKTRIAWAN, SE</td>
                            </tr>
                            <tr>
                                <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 5px;">2.</div></td>
                                <td style="vertical-align: top;">Direktur Kepatuhan</td>
                                <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                                <td style="vertical-align: top;">S. HARTATI KUSUMASARI</td>
                            </tr>
                            <tr>
                                <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 5px;">3.</div></td>
                                <td style="vertical-align: top;">Direktur</td>
                                <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                                <td style="vertical-align: top;">AGUS PURNAWAN</td>
                            </tr>
                            <tr>
                                <td colspan="4" style="vertical-align: top;"><div style="margin:0px 3px;"><strong>Dewan Komisaris</strong></div></td>
                            </tr>
                            <tr>
                                <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 5px;">1.</div></td>
                                <td style="vertical-align: top;">Komisaris Utama</td>
                                <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                                <td style="vertical-align: top;">ERICK SUWANDY</td>
                            </tr>
                            <tr>
                                <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 5px;">2.</div></td>
                                <td style="vertical-align: top;">Komisaris</td>
                                <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                                <td style="vertical-align: top;">TJUNG DAVID</td>
                            </tr>
                        </table>
                        <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                            <strong>Susunan Pemegang Saham Perusahaan :</strong><br>
                            <ol>
                                <li> ERICK SUWANDY </li>
                                <li> TJUNG DAVID</li>
                            </ol>
                        </div>
                        <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                            <strong>Nomor dan Tanggal Izin Usaha Dari Bappebti :</strong><br>
                            <table width="100%" style="margin:0 20px;">
                                <tr>
                                    <td width="40%">01/ BAPPEBTI/SI/01/2023</td>
                                    <td width="10%">&nbsp;</td>
                                    <td>Tanggal : 6 Januari 2023</td>
                                </tr>
                            </table>
                        </div>
                        <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                            <strong>Nomor dan Tanggal Keanggotaan Bursa Berjangka</strong><br>
                            <table width="100%" style="margin:0 20px;">
                                <tr>
                                    <td width="40%">No. 257/SPKB/ICDX/DIR/VIII/2022</td>
                                    <td width="10%">&nbsp;</td>
                                    <td>Tanggal : 10 Agustus  2022</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="page-break"></div>
                    <div style="border:1px solid black;padding:5px;margin-top:45px;">
                        <div style="text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                            <strong>Nomor dan Tanggal Keanggotaan Lembaga Kliring Berjangka</strong><br>
                            <table width="100%" style="margin:0 20px;">
                                <tr>
                                    <td width="40%">No. 235/SPKK/ICH/VIII/2022</td>
                                    <td width="10%">&nbsp;</td>
                                    <td>Tanggal : 10 Agustus  2022</td>
                                </tr>
                            </table>
                        </div>
                        <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                            <strong>Nomor dan Tanggal Persetujuan Sebagai Peserta Sistem Perdagangan Alternatif</strong><br>
                            <table width="100%" style="margin:0 20px;">
                                <tr>
                                </tr>
                            </table>
                        </div>
                        <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                            <strong>Nama Penyelenggara Sistem Perdagangan Alternatif</strong><br>
                            <div style="margin:0 23px;"></div>
                        </div>
                        <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                            <strong>Kontrak Berjangka Yang Diperdagangkan *)</strong><br>
                            <div style="margin:0 23px;">
                                <!--Kontrak Gulir Valuta Asing (Foreign Exchange Margin Trading) <br>
                                Kontrak Berjangka index saham asing (Stock Index Futures Contract) <br>
                                Kontrak Berjangka Emas (XAU) <br>-->
                                Kontrak Komoditi Berjangka OLEINTR: 10 TON <br>
                                COFR (MINYAK MENTAH): 100 BAREL <br>
                                KONTRAK GULIR EMAS : (GOLDUD) 10 TROY OUNCE (DALAM USD) (GOLDID) 10TROY OUNCE,(DALAM IDR)
                            </div>
                        </div>
                        <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                            <strong>Kontrak Derivatif Syariah Yang Diperdagangkan *)</strong><br>
                            <div style="margin:0 23px;">-</div>
                        </div>
                        <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                            <strong>Kontrak Derivatif dalam Sistem Perdagangan Alternatif *)</strong><br>
                            <div style="margin:0 23px;"></div>
                        </div>
                        <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                            <strong>Kontrak Derivatif dalam Sistem Perdagangan Alternatif dengan volume minimum 0,1 (nol koma satu) lot Yang Ddiperdagangkan *)</strong><br>
                            <div style="margin:0 23px;"></div>
                        </div>
                        <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                            <strong>Biaya Secara Rinci yang Di Bebankan Pada Nasabah</strong>
                            <div style="margin:0 23px;">Trading Rules (Spesifikasi Kontrak)</div>
                        </div>
                        <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                            <strong>Nomor atau alamat email jika terjadi keluhan :</strong><br>
                            <div style="margin:0 23px;">'.$setting_office_number.' / '.$setting_email_pgdu.'</div>
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
                    <div class="page-break"></div>
                    <div style="border:1px solid black;padding:5px;margin-top:45px;">
                        <div style="text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                            <strong>Nama-nama Wakil Pialang Berjangka yang Bekerja di Perusahaan Pialang Berjangka</strong><br>
                            <table style="margin-left:20px;width:100%">
                                <tr>
                                    <td>1.</td>
                                    <td>TERRY INDRADI OKTRIAWAN, SE</td>
                                </tr>
                                <tr>
                                    <td>2.</td>
                                    <td>AGUS PURNAWAN</td>
                                </tr>
                                <tr>
                                    <td>3.</td>
                                    <td>TETUKO BASUKI RAHARDJO</td>
                                </tr>
                                <tr>
                                    <td>4.</td>
                                    <td>'.strtoupper('Margareth Tuuasun').'</td>
                                </tr>
                            </table>
                        </div>
                        <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                            <strong>Nama - Nama Wakil Pialang Berjangka yang secara khusus ditunjuk oleh Pialang Berjangka untuk melakukanVerifikasi dalam rangka penerimaan Nasabah elektronik on- Line</strong><br>
                            <table width="100%" style="margin:0 20px;">
                                <tr>
                                    <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 5px;">1.</div></td>
                                    <!--<td style="vertical-align: top;">Margareth Tuuasun</td>-->
                                    <td style="vertical-align: top;">'.strtoupper('AGUS PURNAWAN').'</td>
                                </tr>
                            </table>
                        </div>
                        <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;">
                            <strong>Nomor Rekening Terpisah (Segregated Account) Perusahaan Pialang Berjangka:</strong><br>
                            <table width="100%" style="margin:0 20px;">
                                <tr>
                                    <td style="vertical-align: top;">Bank Central Asia (Bank BCA)</td>
                                    <td style="vertical-align: top;"><div style="margin:0px 5px;">IDR</div></td>
                                    <td style="vertical-align: top;">0105220222</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div style="margin-top:15px;">
                        <p class="text-center">PERNYATAAN TELAH MEMBACA PROFIL PERUSAHAAN PIALANG BERJANGKA</p>
                        <p class="text-justify">Dengan mengisi kolom "YA" di bawah ini, saya menyatakan bahwa saya telah membaca dan menerima informasi PROFIL PERUSAHAAN PIALANG BERJANGKA, mengerti dan memahami isinya.</p>
                    </div>
                    <div style="text-align:center;margin-top:15px;margin-left:25%">
                        <table>
                            <tr>
                                <td>Pernyataan menerima/tidak</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td><input type="checkbox" style="display: inline;" checked disabled><strong>Ya</strong></td>
                                <td><input type="checkbox" style="display: inline;" disabled><strong>Tidak</strong></td>
                            </tr>
                            <tr>
                                <td>Menerima pada tanggal</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td colspan="2"><strong>'.date('Y-m-d H:i:s', strtotime($ACC_01_AGGDATE)).'</strong></td>
                            </tr>
                            <!-- <tr>
                                <td>IP Address</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td><strong>'.$ACC_F_PROFILE_IP.'</strong></td>
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
        // $fl_name = realpath(dirname(dirname(__FILE__))).'/'.'Profile_perusahaan.pdf';
        // file_put_contents($fl_name, $output);
        // if(isset($ALL_PDF_FILES)){
        //     $ALL_PDF_FILES[] = $fl_name;
        // }
        $htmls = $content;
    }else{
        $dompdf->stream("".$web_name_full." - 107.PBK.01",array("Attachment"=>false));
        exit(0);
    }
    
?>