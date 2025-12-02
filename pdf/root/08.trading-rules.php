
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
            tb_member.MBR_NAME,
            tb_member.MBR_ZIP,
            tb_racc.ACC_PRODUCT,
            tb_racc.ACC_TYPE,
            tb_racc.ACC_TYPEACC,
            tb_racc.ACC_F_TRDNGRULE_IP,
            tb_racc.ACC_F_TRDNGRULE_DATE
        FROM tb_racc
        JOIN tb_member
        ON(tb_member.MBR_ID = tb_racc.ACC_MBR)
        WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id_acc.'"
        LIMIT 1
    ');
    if(mysqli_num_rows($SQL_QUERY) > 0){
        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        $MBR_NAME = $RESULT_QUERY['MBR_NAME'];
        $ACC_F_TRDNGRULE_DATE = $RESULT_QUERY['ACC_F_TRDNGRULE_DATE'];
        $ACC_F_TRDNGRULE_IP = $RESULT_QUERY['ACC_F_TRDNGRULE_IP'];

        if($RESULT_QUERY['ACC_TYPE'] == 1 && $RESULT_QUERY['ACC_TYPEACC'] == 'Regular'){
            $file_aggrement = 'Forex Regular';
        } else if($RESULT_QUERY['ACC_TYPE'] == 1 && $RESULT_QUERY['ACC_TYPEACC'] == 'Mini'){
            $file_aggrement = 'Forex Mini';
        } else if($RESULT_QUERY['ACC_TYPE'] == 2){
            $file_aggrement = 'Multilateral';
        };
        
        if($RESULT_QUERY["ACC_TYPEACC"] == 'Mini'){
            $file_pdf = 'mini';
        }else{ $file_pdf = 'micro'; };

    } else {
        $file_aggrement = '';
        $ACC_F_TRDNGRULE_DATE = '';
        $ACC_F_TRDNGRULE_IP = '';
    };

    $ARR = explode("/", dirname($_SERVER["PHP_SELF"]));
    

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
                        <h3>PERATURAN PERDAGANGAN <span>(<i>TRADING RULES</i>)</span></h3>
                    </div>
                    
                    <p class="text-justify">TRADING RULES : '.$file_aggrement.'</p>
                    <span>Sistem Perdagangan Yang Digunakan : MT5</span>
                    <div class="text-left" style="margin-top: 10px;"><label style="cursor:pointer;"><input type="checkbox" id="cb_00" onclick="run();" class="form-check-input" style="border: 1.5px solid black !important;" checked value="YA" required /> Saya sudah membaca dan memahami</label></div> 
                    <img <img src="data:image/png;base64,'.base64_encode(file_get_contents("../produk3.jpg")).'" width="100%" height="200"></img>
                    <div class="text-left mb-2"><label style="cursor:pointer;"><input type="checkbox" id="cb_00" onclick="run();" class="form-check-input" style="border: 1.5px solid black !important;" checked value="YA" required /> Saya sudah membaca dan memahami</label></div> 

                    <img <img src="data:image/png;base64,'.base64_encode(file_get_contents("$awsUrl/".$file_pdf."1.jpg")).'" width="100%" height="510"></img>
                    <div class="text-left mb-2"><label style="cursor:pointer;"><input type="checkbox" id="cb_01" onclick="run();" class="form-check-input" style="border: 1.5px solid black !important;" checked value="YA" required /> Saya sudah membaca dan memahami</label></div> 
                    
                    <img <img src="data:image/png;base64,'.base64_encode(file_get_contents("$awsUrl/".$file_pdf."2.jpg")).'" width="100%" height="600"></img>
                    <div class="text-left mb-2"><label style="cursor:pointer;"><input type="checkbox" id="cb_02" onclick="run();" class="form-check-input" style="border: 1.5px solid black !important;" checked value="YA" required disabled="true"/> Saya sudah membaca dan memahami</label></div> 
                    
                    <img <img src="data:image/png;base64,'.base64_encode(file_get_contents("$awsUrl/".$file_pdf."3.jpg")).'" width="100%" height="600"></img>
                    <div class="text-left mb-2"><label style="cursor:pointer;"><input type="checkbox" id="cb_03" onclick="run();" class="form-check-input" style="border: 1.5px solid black !important;" checked value="YA" required disabled="true"/> Saya sudah membaca dan memahami</label></div> 
                    
                    <img <img src="data:image/png;base64,'.base64_encode(file_get_contents("$awsUrl/".$file_pdf."4.jpg")).'" width="100%" height="600"></img>
                    <p>Biaya yang dikenakan setiap pelaksanaan transaksi : Maximal $50</p>
                    <div class="text-left mb-2"><label style="cursor:pointer;"><input type="checkbox" id="cb_04" onclick="run();" class="form-check-input" style="border: 1.5px solid black !important;" checked value="YA" required disabled="true"/> Saya sudah membaca dan memahami</label></div> 
                    
                    <div style="margin-top:25px;text-align:center;">
                        Dengan mengisi kolom “YA” di bawah ini, saya menyatakan bahwa saya 
                        telah membaca tentang PERATURAN PERDAGANGAN (<i>TRADING  RULES</i>), 
                        mengerti dan menerima ketentuan dalam bertransaksi 
                    </div>
                    <div style="text-align:center;margin-top:10px;margin-left:25%">
                        <table>
                            <tr>
                                <td>Pernyataan menerima/tidak</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td><input type="checkbox" style="display: inline;" checked disabled><span>Ya</span></td>
                                <td><input type="checkbox" style="display: inline;" disabled><span>Tidak</span></td>
                            </tr>
                            <tr>
                                <td>Menerima pada tanggal</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td colspan="2"><span>'.date('Y-m-d H:i:s', strtotime($ACC_F_TRDNGRULE_DATE)).'</span></td>
                            </tr>
                            <!-- <tr>
                                <td>IP Address</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td><span>'.$ACC_F_TRDNGRULE_IP.'</span></td>
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
        $dompdf->stream("".$web_name_full." - 107.PBK.06",array("Attachment"=>0));
        exit(0);
    }
    
?>