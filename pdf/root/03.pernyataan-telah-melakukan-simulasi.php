
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
    
    $SQL_QUERY = mysqli_query($db, '
        SELECT
            tb_racc.ACC_F_APP_PRIBADI_NAMA AS MBR_NAME,
            tb_member.MBR_ZIP,
            tb_racc.ACC_F_APP_PRIBADI_ALAMAT AS MBR_ADDRESS,
            tb_racc.ACC_F_APP_PRIBADI_TGLLHR,
            tb_racc.ACC_DEMO,
            tb_racc.ACC_F_APP_PRIBADI_TMPTLHR,
            tb_racc.ACC_F_APP_PRIBADI_TYPEID,
            tb_racc.ACC_F_APP_PRIBADI_ID,
            tb_racc.ACC_F_SIMULASI_DATE,
            tb_racc.ACC_F_SIMULASI_IP,
            tb_racc.ACC_F_APPPEMBUKAAN_DATE,
            tb_racc.ACC_F_SIMULASI_PERSH
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
        $MBR_ADDRESS = $RESULT_QUERY['MBR_ADDRESS'];
        $ACC_F_APP_PRIBADI_TGLLHR = $RESULT_QUERY['ACC_F_APP_PRIBADI_TGLLHR'];
        $ACC_DEMO = $RESULT_QUERY['ACC_DEMO'];
        $ACC_F_APP_PRIBADI_TMPTLHR = $RESULT_QUERY['ACC_F_APP_PRIBADI_TMPTLHR'];
        $ACC_F_APP_PRIBADI_TYPEID = $RESULT_QUERY['ACC_F_APP_PRIBADI_TYPEID'];
        $ACC_F_APP_PRIBADI_ID = $RESULT_QUERY['ACC_F_APP_PRIBADI_ID'];
        $ACC_F_SIMULASI_DATE = $RESULT_QUERY['ACC_F_SIMULASI_DATE'];
        $ACC_F_APPPEMBUKAAN_DATE = $RESULT_QUERY['ACC_F_APPPEMBUKAAN_DATE'];
        $ACC_F_SIMULASI_IP = $RESULT_QUERY['ACC_F_SIMULASI_IP'];
        $ACC_F_SIMULASI_PERSH = $RESULT_QUERY['ACC_F_SIMULASI_PERSH'];
    } else {
        $MBR_NAME = '';
        $MBR_ZIP = '';
        $ACC_F_APP_PRIBADI_TGLLHR = '';
        $ACC_DEMO = '';
        $MBR_ADDRESS = '';
        $ACC_F_APP_PRIBADI_TMPTLHR = '';
        $ACC_F_APP_PRIBADI_TYPEID = '';
        $ACC_F_APP_PRIBADI_ID = '';
        $ACC_F_SIMULASI_DATE = '';
        $ACC_F_APPPEMBUKAAN_DATE = '';
        $ACC_F_SIMULASI_IP = '';
        $ACC_F_SIMULASI_PERSH = '';
    };
    
    // if($ACC_F_APP_PRIBADI_TYPEID == 'KTP'){
    //     $doctype =  'KTP/<strike>SIM</strike>/<strike>Passport</strike>';
    // }else if($ACC_F_APP_PRIBADI_TYPEID == 'SIM'){
    //     $doctype =  '<strike>KTP</strike>/SIM/<strike>Passport</strike>';
    // }else if($ACC_F_APP_PRIBADI_TYPEID == 'Passport'){
    //     $doctype =  '<strike>KTP</strike>/<strike>SIM</strike>/Passport';
    // }else{ $doctype =  'KTP/SIM/Passport'; }
    
    if($ACC_F_APP_PRIBADI_TYPEID == 'KTP'){
        $doctype =  'KTP/<strike>Passport</strike>';
    }else if($ACC_F_APP_PRIBADI_TYPEID == 'SIM'){
        $doctype =  '<strike>KTP</strike>/<strike>Passport</strike>';
    }else if($ACC_F_APP_PRIBADI_TYPEID == 'Passport'){
        $doctype =  '<strike>KTP</strike>/Passport';
    }else{ $doctype =  'KTP/Passport'; }

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
                    <div style="text-align:center;vertical-align: middle;padding: 10px 0 10px 0;">
                        <p>PERNYATAAN TELAH MELAKUKAN SIMULASI<br>PERDAGANGAN BERJANGKA KOMODITI</p>
                    </div>
                    Yang mengisi formulir di bawah ini:
                    <table style="border-spacing: 2px;">
                        <tr>
                            <td>Nama Lengkap</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$MBR_NAME.'</td>
                        </tr>
                        <tr>
                            <td>Tempat/Tanggal Lahir</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_PRIBADI_TMPTLHR.' / '.$ACC_F_APP_PRIBADI_TGLLHR.'</td>
                        </tr>
                        <tr>
                            <td>Alamat Rumah</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$MBR_ADDRESS.'</td>
                        </tr>
                        <tr>
                            <td>
                                No. Identitas<br>
                                ('.$doctype.' *)
                            </td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">'.$ACC_F_APP_PRIBADI_ID.'</td>
                        </tr>
                        <tr>
                            <td>No. Demo Acc.</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_DEMO.'</td>
                        </tr>
                    </table>
                    <div style="margin-top:15px;" class="text-justify">
                        <p>Dengan mengisi kolom "YA" di bawah ini, saya menyatakan bahwa saya telah melakukan simulasi
                        bertransaksi di bidang Perdagangan Berjangka Komoditi pada PT. <strong>'.ucwords($ACC_F_SIMULASI_PERSH).'</strong> **), dan telah
                        memahami tentang tata cara bertransaksi di bidang Perdagangan Berjangka Komoditi.</p>
                        <p>Demikian Pernyataan ini dibuat dengan sebenarnya dalam keadaan sadar, sehat jasmani dan
                        rohani serta tanpa paksaan apapun dari pihak manapun.</p>
                    </div>
                    <div style="text-align:center;margin-top:25px;margin-left:25%">
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
                                <td colspan="2"><strong>'.date('Y-m-d H:i:s', strtotime($ACC_F_SIMULASI_DATE)).'</strong></td>
                            </tr>
                            <!-- <tr>
                                <td>IP Address</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td><strong>'.$ACC_F_SIMULASI_IP.'</strong></td>
                            </tr> -->
                        </table>
                    </div>
                    <div>
                        <p>*) Pilih salah satu<br>
                        **) Isi sesuai dengan nama Pialang Berjangka  </p>
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
        $dompdf->stream("'.$web_name_full.' - 107.PBK.02.01",array("Attachment"=>0));
        exit(0);
    }
    
?>