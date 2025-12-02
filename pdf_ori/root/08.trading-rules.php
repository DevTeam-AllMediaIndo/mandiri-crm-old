
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
        
        if($RESULT_QUERY["ACC_PRODUCT"] == 'Forex dan Gold'){
            $file_pdf = 'forex';
        }else{ $file_pdf = 'index'; };

    } else {
        $file_aggrement = '';
        $ACC_F_TRDNGRULE_DATE = '';
        $ACC_F_TRDNGRULE_IP = '';
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
                        <td width="50%" style="vertical-align: top; "><strong><small>Formulir Nomor : 107.PBK.06</small></strong></td>
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
                    <h3>PERATURAN PERDAGANGAN <u>(TRADING RULES)</u></h3>
                </div>
                
                <p class="text-justify">TRADING RULES : '.$file_aggrement.'</p>
                <img <img src="data:image/png;base64,'.base64_encode(file_get_contents("https://control.techcrm.net/m7p4jvq4/pdf/".$file_pdf."1.jpg")).'" width="100%" height="600"></img>
                <div class="text-left mb-2"><label style="cursor:pointer;"><input type="checkbox" id="cb_01" onclick="run();" class="form-check-input" style="border: 1.5px solid black !important;" checked value="YA" required /> Saya sudah membaca dan memahami</label></div> 
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
                <img <img src="data:image/png;base64,'.base64_encode(file_get_contents("https://control.techcrm.net/m7p4jvq4/pdf/".$file_pdf."2.jpg")).'" width="100%" height="600"></img>
                <div class="text-left mb-2"><label style="cursor:pointer;"><input type="checkbox" id="cb_02" onclick="run();" class="form-check-input" style="border: 1.5px solid black !important;" checked value="YA" required disabled="true"/> Saya sudah membaca dan memahami</label></div> 
                <br><br><br><br><br><br>
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
                <img <img src="data:image/png;base64,'.base64_encode(file_get_contents("https://control.techcrm.net/m7p4jvq4/pdf/".$file_pdf."3.jpg")).'" width="100%" height="600"></img>
                <div class="text-left mb-2"><label style="cursor:pointer;"><input type="checkbox" id="cb_03" onclick="run();" class="form-check-input" style="border: 1.5px solid black !important;" checked value="YA" required disabled="true"/> Saya sudah membaca dan memahami</label></div> 
                <br><br><br><br><br><br>
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
                <img <img src="data:image/png;base64,'.base64_encode(file_get_contents("https://control.techcrm.net/m7p4jvq4/pdf/".$file_pdf."4.jpg")).'" width="100%" height="600"></img>
                <div class="text-left mb-2"><label style="cursor:pointer;"><input type="checkbox" id="cb_04" onclick="run();" class="form-check-input" style="border: 1.5px solid black !important;" checked value="YA" required disabled="true"/> Saya sudah membaca dan memahami</label></div> 
                <br><br><br><br><br><br>
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
                <img <img src="data:image/png;base64,'.base64_encode(file_get_contents("https://control.techcrm.net/m7p4jvq4/pdf/".$file_pdf."5.jpg")).'" width="100%" height="600"></img>
                <div class="text-left mb-2"><label style="cursor:pointer;"><input type="checkbox" id="cb_05" onclick="run();" class="form-check-input" style="border: 1.5px solid black !important;" checked value="YA" required disabled="true"/> Saya sudah membaca dan memahami</label></div> 
                <br><br><br><br><br><br>
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
                <img <img src="data:image/png;base64,'.base64_encode(file_get_contents("https://control.techcrm.net/m7p4jvq4/pdf/".$file_pdf."6.jpg")).'" width="100%" height="600"></img>
                <div class="text-left mb-2"><label style="cursor:pointer; margin-top:none;"><input type="checkbox" id="cb_06" onclick="run();" class="form-check-input" style="border: 1.5px solid black !important;" checked value="YA" required disabled="true"/> Saya sudah membaca dan memahami</label></div> 
                <br><br><br><br><br><br>
            </body>
        </html>
    ';

    $dompdf->loadHtml($content);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("".$web_name_full." - 107.PBK.06",array("Attachment"=>0));
    
?>