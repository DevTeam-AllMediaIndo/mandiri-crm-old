<?php
    date_default_timezone_set("Asia/Jakarta");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../../setting.php';
    require_once 'vendor/autoload.php';
    use Dompdf\Dompdf;
    $dompdf = new Dompdf();
    
    $id_acc = (isset($pdfotpt)) ? form_input($pdfotpt) : ((isset($_GET["x"])) ? form_input($_GET["x"]) : 0);
    
    $SQL_QUERY = mysqli_query($db, '
        SELECT
            tb_racc.ACC_F_APP_PRIBADI_NAMA AS MBR_NAME,
            tb_member.MBR_ZIP,
            tb_racc.ACC_F_APP_PRIBADI_TGLLHR,
            IF(tb_racc.ACC_LOGIN = 0, "", tb_racc.ACC_LOGIN) AS ACC_LOGIN,
            tb_member.MBR_ADDRESS,
            tb_racc.ACC_F_APP_PRIBADI_TMPTLHR,
            tb_racc.ACC_F_APP_PRIBADI_TYPEID,
            tb_racc.ACC_F_APP_PRIBADI_ID,
            tb_racc.ACC_F_APP_PRIBADI_KELAMIN,
            tb_racc.ACC_F_DISC_DATE,
            tb_racc.ACC_F_DISC_PERYT,
            tb_racc.ACC_F_PERJ_WPB,
            tb_racc.ACC_F_KODE
        FROM tb_racc
        JOIN tb_member
        ON(tb_member.MBR_ID = tb_racc.ACC_MBR)
        WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id_acc.'"
        LIMIT 1
    ');
    if(mysqli_num_rows($SQL_QUERY) > 0){
        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        $MBR_NAME = $RESULT_QUERY['MBR_NAME'];
        $MBR_ZIP = $RESULT_QUERY['MBR_ZIP'];
        $ACC_S2_DATEBIRTH = $RESULT_QUERY['ACC_F_APP_PRIBADI_TGLLHR'];
        $ACC_LOGIN = $RESULT_QUERY['ACC_LOGIN'];
        $ACC_S6_ADD = $RESULT_QUERY['MBR_ADDRESS'];
        $ACC_S6_TMPTLAHIR = $RESULT_QUERY['ACC_F_APP_PRIBADI_TMPTLHR'];
        $ACC_S6_IDTYPE = $RESULT_QUERY['ACC_F_APP_PRIBADI_TYPEID'];
        $ACC_S6_IDNO = $RESULT_QUERY['ACC_F_APP_PRIBADI_ID'];
        $ACC_F_DISC_DATE = $RESULT_QUERY['ACC_F_DISC_DATE'];
        $ACC_F_DISC_PERYT = $RESULT_QUERY['ACC_F_DISC_PERYT'];
        $ACC_F_APP_PRIBADI_KELAMIN = $RESULT_QUERY['ACC_F_APP_PRIBADI_KELAMIN'];
        $ACC_F_PERJ_WPB = $RESULT_QUERY['ACC_F_PERJ_WPB'];
    } else {
        $MBR_NAME = '';
        $MBR_ZIP = '';
        $ACC_S2_DATEBIRTH = '';
        $ACC_LOGIN = '';
        $ACC_S6_ADD = '';
        $ACC_S6_TMPTLAHIR = '';
        $ACC_S6_IDTYPE = '';
        $ACC_S6_IDNO = '';
        $ACC_F_DISC_DATE = '';
        $ACC_F_DISC_PERYT = "";
        $ACC_F_PERJ_WPB = '';
    };
    $doctype = 'KTP';
    // if($ACC_S6_IDTYPE == 'KTP'){
    //     $doctype =  'KTP/<strike>SIM</strike>/<strike>Passport</strike>';
    // }else if($ACC_S6_IDTYPE == 'SIM'){
    //     $doctype =  '<strike>KTP</strike>/SIM/<strike>Passport</strike>';
    // }else if($ACC_S6_IDTYPE == 'Passport'){
    //     $doctype =  '<strike>KTP</strike>/<strike>SIM</strike>/Passport';
    // }else{ $doctype =  'KTP/SIM/Passport'; }
    if($RESULT_QUERY['ACC_F_APP_PRIBADI_KELAMIN'] == 'Laki-laki'){ 
        $bapakatauibu = 'Bapak/<strike>Ibu</strike>'; 
    } else { $bapakatauibu = '<strike>Bapak</strike>/Ibu'; }

    if(strtolower(date('l', strtotime($ACC_F_DISC_DATE))) == strtolower('Monday')){ $date_day = 'Senin';
    } else if(strtolower(date('l', strtotime($ACC_F_DISC_DATE))) == strtolower('Tuesday')){ $date_day = 'Selasa';
    } else if(strtolower(date('l', strtotime($ACC_F_DISC_DATE))) == strtolower('wednesday')){ $date_day = 'Rabu';
    } else if(strtolower(date('l', strtotime($ACC_F_DISC_DATE))) == strtolower('thursday')){ $date_day = 'Kamis';
    } else if(strtolower(date('l', strtotime($ACC_F_DISC_DATE))) == strtolower('Friday')){ $date_day = 'Jumat';
    } else if(strtolower(date('l', strtotime($ACC_F_DISC_DATE))) == strtolower('Saturday')){ $date_day = 'Sabtu';
    } else if(strtolower(date('l', strtotime($ACC_F_DISC_DATE))) == strtolower('Sunday')){ $date_day = 'Minggu';
    };

    if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('January')){ $date_month = 'Januari';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('February')){ $date_month = 'Februari';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('March')){ $date_month = 'Maret';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('April')){ $date_month = 'April';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('May')){ $date_month = 'Mei';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('June')){ $date_month = 'Juni';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('July')){ $date_month = 'Juli';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('August')){ $date_month = 'Agustus';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('September')){ $date_month = 'September';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('October')){ $date_month = 'Oktober';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('November')){ $date_month = 'November';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('December')){ $date_month = 'Desember';
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
                    <div style="text-align:center;vertical-align: middle;padding: 10px 0 5px 0;">
                        <p>PERNYATAAN BAHWA DANA YANG DIGUNAKAN SEBAGAI MARGIN MERUPAKAN DANA MILIK NASABAH SENDIRI</p>
                    </div>
                    <p>Yang mengisi formulir di bawah ini:</p>
                    <table style="border-spacing: 2px;">
                        <tr>
                            <td width="25%" style="vertical-align: top;">Nama Lengkap</td>
                            <td width="1%" style="vertical-align: top; text-align:center;">&nbsp;:&nbsp;</td>
                            <td>'.$MBR_NAME.'</td>
                        </tr>
                        <tr>
                            <td>Tempat/Tanggal Lahir</td>
                            <td width="1%" style="vertical-align: top; text-align:center;">&nbsp;:&nbsp;</td>
                            <td>'.$ACC_S6_TMPTLAHIR.' / '.date("d F Y", strtotime($ACC_S2_DATEBIRTH)).'</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Alamat Rumah</td>
                            <td width="1%" style="vertical-align: top; text-align:center;">&nbsp;:&nbsp;</td>
                            <td>'.$ACC_S6_ADD.'</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Kode Pos</td>
                            <td width="1%" style="vertical-align: top; text-align:center;">&nbsp;:&nbsp;</td>
                            <td>'.$MBR_ZIP.'</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">No. Identitas<br>'.$doctype.' *)</td>
                            <td width="1%" style="vertical-align: top; text-align:center;">&nbsp;:&nbsp;</td>
                            <td style="vertical-align: top;">'.$ACC_S6_IDNO.'</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">No. Acc.</td>
                            <td width="1%" style="vertical-align: top; text-align:center;">&nbsp;:&nbsp;</td>
                            <td>'.$ACC_LOGIN.'</td>
                        </tr>
                    </table>
                    <p style="text-align:justify">
                        Dengan mengisi kolom “YA” di bawah ini, Bersama ini saya menyatakan bahwa dana yang saya gunakan 
                        untuk bertransaksi di PT. Mandiri Investindo Futures adalah milik saya pribadi dan bukan dana pihak lain, 
                        serta tidak diperoleh dari hasil kejahatan, penipuan, penggelapan, tindak pidana korupsi, tindak pidan
                        narkotika, tindak pidana di bidang kehutanan, hasil pencucian uang, dan perbuatan melawan hukum 
                        lainnya serta tidak dimaksudkan untuk melakukan pencucian uang dan/atau pendanaan terorisme.
                    </p>
                    <p style="text-align:justify">
                        Demikian surat pernyataan ini saya buat dalam keadaan sadar, sehat jasmani dan rohani serta tanpa paksaan dari pihak manapun.
                    </p>

                    <div style="margin-top:25px;"></div>
                    <table width="100%">
                        <tr>
                            <td width="30%" align="left">Pernyataan menerima / Tidak</td>
                            <td width="60%" align="left" style="vertical-align: middle;">
                                <input style="padding: 0px; margin: 0px; display: inline;" type="checkbox" '.(strtolower($ACC_F_DISC_PERYT) == "yes" ? "checked" : "").'>
                                <label style="margin: 0px; !important">Ya</label>
                                
                                <input style="padding: 0px; margin: 0px;" type="checkbox" style="margin-left: 10px; display: inline;" '.(strtolower($ACC_F_DISC_PERYT) == "no" ? "checked" : "").'>
                                <label style="margin: 0px; !important">Tidak</label>
                            </td>
                        </tr>

                        <tr style="margin-top: 20px;">
                            <td width="30%" align="left">Menyatakan Pada Tanggal</td>
                            <td width="60%" align="left" style="vertical-align: middle;">
                                <strong>'.date('Y-m-d H:i:s', strtotime($ACC_F_DISC_DATE)).'</strong>
                            </td>
                        </tr>
                    </table>
                    <div>
                        <p>*) Pilih salah satu </p>
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
        $dompdf->stream("".$web_name_full." - 107.PBK.07",array("Attachment"=>0));
        exit(0);
    }
    
?>