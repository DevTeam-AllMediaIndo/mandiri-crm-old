<?php
    date_default_timezone_set("Asia/Jakarta");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../../setting.php';
    require_once 'vendor/autoload.php';
    use Dompdf\Dompdf;
    use Dompdf\Options;

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $dompdf = new Dompdf($options);

    
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
        $ACC_F_APPPEMBUKAAN_DATE = $RESULT_QUERY["ACC_F_APPPEMBUKAAN_DATE"];
        $ACC_F_APPPEMBUKAAN_IP   = $RESULT_QUERY["ACC_F_APPPEMBUKAAN_IP"];

        $ACC_F_DISC_DATE         = $RESULT_QUERY["ACC_F_DISC_DATE"];
        $ACC_F_DISC_IP           = $RESULT_QUERY["ACC_F_DISC_IP"];

    } else {

        $ACC_F_APPPEMBUKAAN_DATE = '';
        $ACC_F_APPPEMBUKAAN_IP   = '';
        $ACC_F_PENGLAMAN_DATE = '';

        $ACC_F_RESK_DATE = '';
        
        $ACC_F_PERJ_DATE = '';

        $ACC_F_DISC_DATE = '';
        $ACC_F_DISC_IP = '';
    };
    $num = 1;
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
                <style>
                    .titik_dua {vertical-align: top; text-align:center;}
                    ol > li {
                        margin-bottom: 10px !important;
                    }

                    table.bordered {
                        font-size: 13px;
                        width: 100%;
                        border-collapse: collapse; /* Menggabungkan border */
                    }

                    table.bordered tr th, 
                    table.bordered tr td {
                        border: 1px solid black; /* Border hanya untuk sel */
                        padding: 5px;
                        text-align: left;
                    }

                    td.text-center {
                        text-align: center;
                        padding-left: 2px;
                        font-family: "DejaVu Sans Mono", monospace;
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
                <div class="container">
                    <div style="text-align:center;"><h4>VERIFIKASI KELENGKAPAN <br>PROSES PENERIMAAN NASABAH SECARA ELEKTRONIK ONLINE</h4></div>
                    <table width="100%" style="border-collapse: collapse;">
                        <tr align="center" class="text-center">
                            <td style="border: 1px solid black;">NO</td>
                            <td style="border: 1px solid black;">PROSES</td>
                            <td style="border: 1px solid black;">STATUS</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; text-align: center;">'.$num.'</td>
                            <td style="border: 1px solid black;">PROFIL PERUSAHAAN PIALANG BERJANGKA</td>
                            <td align="center" class="text-center" style="border: 1px solid black;">
                                '.htmlspecialchars_decode("&check;").'
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; text-align: center;">'.++$num.'</td>
                            <td style="border: 1px solid black;">PERNYATAAN TELAH  MELAKUKAN SIMULASI PERDAGANGAN BERJANGKA  ATAU PERNYATAAN TELAH BERPENGALAMAN DALAM MELAKSANAKAN TRANSAKSI PERDAGANGAN BERJANGKA</td>
                            <td align="center" class="text-center" style="border: 1px solid black;">
                                '.htmlspecialchars_decode("&check;").'
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; text-align: center;">'.++$num.'</td>
                            <td style="border: 1px solid black;">PERNYATAAN PENGUNGKAPAN <br> (DISCLOSURE STATEMENT) </td>
                            <td align="center" class="text-center" style="border: 1px solid black;">
                                '.htmlspecialchars_decode("&check;").'
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; text-align: center;">'.++$num.'</td>
                            <td style="border: 1px solid black;">APLIKASI PEMBUKAAN  REKENING TRANSAKSI </td>
                            <td align="center" class="text-center" style="border: 1px solid black;">
                                '.htmlspecialchars_decode("&check;").'
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; text-align: center;">'.++$num.'</td>
                            <td style="border: 1px solid black;">PERNYATAAN PENGUNGKAPAN <br> (DISCLOSURE STATEMENT) </td>
                            <td align="center" class="text-center" style="border: 1px solid black;">
                                '.htmlspecialchars_decode("&check;").'
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; text-align: center;">'.++$num.'</td>
                            <td style="border: 1px solid black;">"DOKUMEN PEMBERITAHUAN ADANYA RISIKO"</td>
                            <td align="center" class="text-center" style="border: 1px solid black;">
                                '.htmlspecialchars_decode("&check;").'
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; text-align: center;">'.++$num.'</td>
                            <td style="border: 1px solid black;">PERNYATAAN PENGUNGKAPAN <br> (DISCLOSURE STATEMENT) </td>
                            <td align="center" class="text-center" style="border: 1px solid black;">
                                '.htmlspecialchars_decode("&check;").'
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; text-align: center;">'.++$num.'</td>
                            <td style="border: 1px solid black;">PERJANJIAN PEMBERIAN AMANAT</td>
                            <td align="center" class="text-center" style="border: 1px solid black;">
                                '.htmlspecialchars_decode("&check;").'
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; text-align: center;">'.++$num.'</td>
                            <td style="border: 1px solid black;">
                                DAFTAR KONTRAK 
                                BERJANGKA, KONTRAK 
                                DERIVATIF DAN KONTRAK 
                                DERIVATIF LAINNYA BESERTA 
                                PERATURAN PERDAGANGAN 
                                (TRADING RULES)
                            </td>
                            <td align="center" class="text-center" style="border: 1px solid black;">
                                '.htmlspecialchars_decode("&check;").'
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; text-align: center;">'.++$num.'</td>
                            <td style="border: 1px solid black;">PERNYATAAN BERTANGGUNG JAWAB ATAS KODE AKSES TRANSAKSI NASABAH (Personal Access Password)</td>
                            <td align="center" class="text-center" style="border: 1px solid black;">
                                '.htmlspecialchars_decode("&check;").'
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black; text-align: center;">'.++$num.'</td>
                            <td style="border: 1px solid black;">PERNYATAAN BAHWA DANA YANG DIGUNAKAN SEBAGAI MARGIN MERUPAKAN DANA MILIK NASABAH SENDIRI</td>
                            <td align="center" class="text-center" style="border: 1px solid black;">
                                '.htmlspecialchars_decode("&check;").'
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="margin-top:25px;">
                    <p style="font-weight: bold;">
                        "Dengan mengisi kolom “YA” di bawah ini, saya menyatakan bahwa saya telah 
                        membaca dan memahami seluruh isi dokumen yang disampaikan dalam 
                        FORMULIR NOMOR 1 sampai dengan FORMULIR NOMOR '.($num = 12).'. "		
                    </p>
                    <p>
                        Demikian Pernyataan ini dibuat dengan sebenarnya dalam keadaan sadar, sehat jasmani dan rohani serta tanpa paksaan apapun dari pihak manapun.
                    </p>
                </div>
                <div style="text-align:center;margin-top:25px;margin-left:25%">
                    <table>
                        <tr>
                            <td>Pernyataan menerima/tidak </td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><input type="checkbox" style="display: inline;" checked disabled><span>Ya</span></td>
                            <td><input type="checkbox" style="display: inline;" disabled><span>Tidak</span></td>
                        </tr>
                        <tr>
                            <td>Pernyataan pada tanggal</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td colspan="2"><span>'.date('Y-m-d H:i:s', strtotime($ACC_F_DISC_DATE)).'</span></td>
                        </tr>
                        <!-- <tr>
                            <td>IP Address</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><span>'.$ACC_F_DISC_IP.'</span></td>
                        </tr> -->
                    </table>
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
        $dompdf->stream("".$web_name_full." - Verifikasi Kelengkapan",array("Attachment"=>0));
        exit(0);
    }
    
?>