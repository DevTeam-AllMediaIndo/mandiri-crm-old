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
            MONTH(tb_acccond.ACCCND_DATEMARGIN) AS ACCCND_DATEMARGIN_MONTH,
            tb_racc.ACC_LOGIN,
            tb_racc.ACC_F_APP_PRIBADI_NAMA AS MBR_NAME,
            tb_member.MBR_EMAIL,
            tb_racc.ACC_F_APP_PRIBADI_HP AS MBR_PHONE,
            tb_member.MBR_CITY,
            tb_ib.IB_NAME,
            tb_ib.IB_CODE,
            tb_ib.IB_CITY,
            DAY(tb_acccond.ACCCND_DATEMARGIN) AS ACCCND_DATEMARGIN_DAY,
            YEAR(tb_acccond.ACCCND_DATEMARGIN) AS ACCCND_DATEMARGIN_YEAR,
            tb_racc.ACC_INITIALMARGIN,
            tb_racc.ACC_RATE,
            tb_racc.ACC_F_APP_BK_1_NAMA,
            tb_racc.ACC_F_APP_BK_1_CBNG,
            tb_racc.ACC_F_APP_BK_1_ACC,
            tb_acccond.ACCCND_CASH_FOREX,
            tb_acccond.ACCCND_CASH_LOCO,
            tb_acccond.ACCCND_CASH_JPK50,
            tb_acccond.ACCCND_CASH_JPK30,
            tb_acccond.ACCCND_CASH_HK50,
            tb_acccond.ACCCND_CASH_KRJ35,
            tb_acccond.ACCCND_DATEMARGIN,
            tb_acccond.ACCCND_AMOUNTMARGIN
        FROM tb_racc
        JOIN tb_member
        JOIN tb_acccond
        JOIN tb_ib
        ON(tb_member.MBR_ID = tb_racc.ACC_MBR
        AND tb_acccond.ACCCND_ACC = tb_racc.ID_ACC
        AND tb_acccond.ACCCND_IB = tb_ib.IB_ID)
        WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id_acc.'"
        LIMIT 1
    ');
    if(mysqli_num_rows($SQL_QUERY) > 0){
        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        $ACCCND_DATEMARGIN_MONTH = $RESULT_QUERY['ACCCND_DATEMARGIN_MONTH'];
        $ACC_LOGIN = $RESULT_QUERY['ACC_LOGIN'];
        $MBR_NAME = $RESULT_QUERY['MBR_NAME'];
        $MBR_EMAIL = $RESULT_QUERY['MBR_EMAIL'];
        $MBR_PHONE = $RESULT_QUERY['MBR_PHONE'];
        $MBR_CITY = $RESULT_QUERY['MBR_CITY'];
        $IB_NAME = $RESULT_QUERY['IB_NAME'];
        $IB_CODE = $RESULT_QUERY['IB_CODE'];
        $IB_CITY = $RESULT_QUERY['IB_CITY'];
        $ACCCND_DATEMARGIN_DAY = $RESULT_QUERY['ACCCND_DATEMARGIN_DAY'];
        $ACCCND_DATEMARGIN_YEAR = $RESULT_QUERY['ACCCND_DATEMARGIN_YEAR'];
        $ACC_INITIALMARGIN = $RESULT_QUERY['ACC_INITIALMARGIN'];
        $ACC_RATE = $RESULT_QUERY['ACC_RATE'];
        $ACCCND_CASH_FOREX = $RESULT_QUERY['ACCCND_CASH_FOREX'];
        $ACCCND_CASH_LOCO = $RESULT_QUERY['ACCCND_CASH_LOCO'];
        $ACCCND_CASH_JPK50 = $RESULT_QUERY['ACCCND_CASH_JPK50'];
        $ACCCND_CASH_JPK30 = $RESULT_QUERY['ACCCND_CASH_JPK30'];
        $ACCCND_CASH_HK50 = $RESULT_QUERY['ACCCND_CASH_HK50'];
        $ACCCND_CASH_KRJ35 = $RESULT_QUERY['ACCCND_CASH_KRJ35'];
        $ACC_F_APP_BK_1_NAMA = $RESULT_QUERY['ACC_F_APP_BK_1_NAMA'];
        $ACC_F_APP_BK_1_CBNG = $RESULT_QUERY['ACC_F_APP_BK_1_CBNG'];
        $ACC_F_APP_BK_1_ACC = $RESULT_QUERY['ACC_F_APP_BK_1_ACC'];
        $ACCCND_AMOUNTMARGIN = $RESULT_QUERY['ACCCND_AMOUNTMARGIN'];
        $ACCCND_DATEMARGIN = $RESULT_QUERY['ACCCND_DATEMARGIN'];
    } else {
        $ACCCND_DATEMARGIN_MONTH = 0;
        $ACC_LOGIN = '';
        $MBR_NAME = '';
        $MBR_EMAIL = '';
        $MBR_PHONE = '';
        $MBR_CITY = '';
        $IB_NAME = '';
        $IB_CODE = '';
        $IB_CITY = '';
        $ACCCND_DATEMARGIN_DAY = '';
        $ACCCND_DATEMARGIN_YEAR = '';
        $ACC_INITIALMARGIN = 0;
        $ACC_RATE = 0;
        $ACCCND_CASH_FOREX = 0;
        $ACCCND_CASH_LOCO = 0;
        $ACCCND_CASH_JPK50 = 0;
        $ACCCND_CASH_JPK30 = 0;
        $ACCCND_CASH_HK50 = 0;
        $ACCCND_CASH_KRJ35 = 0;
        $ACC_F_APP_BK_1_NAMA = '-';
        $ACC_F_APP_BK_1_CBNG = '-';
        $ACC_F_APP_BK_1_ACC = '-';
        $ACCCND_AMOUNTMARGIN = '-';
        $ACCCND_DATEMARGIN = '-';
    };
    if($ACCCND_DATEMARGIN_MONTH == 1){
        $ACCCND_DATEMARGIN_MONTH_STRING = 'Januari';
    } else if($ACCCND_DATEMARGIN_MONTH == 2){
        $ACCCND_DATEMARGIN_MONTH_STRING = 'Februari';
    } else if($ACCCND_DATEMARGIN_MONTH == 3){
        $ACCCND_DATEMARGIN_MONTH_STRING = 'Maret';
    } else if($ACCCND_DATEMARGIN_MONTH == 4){
        $ACCCND_DATEMARGIN_MONTH_STRING = 'April';
    } else if($ACCCND_DATEMARGIN_MONTH == 5){
        $ACCCND_DATEMARGIN_MONTH_STRING = 'Mei';
    } else if($ACCCND_DATEMARGIN_MONTH == 6){
        $ACCCND_DATEMARGIN_MONTH_STRING = 'Juni';
    } else if($ACCCND_DATEMARGIN_MONTH == 7){
        $ACCCND_DATEMARGIN_MONTH_STRING = 'Juli';
    } else if($ACCCND_DATEMARGIN_MONTH == 8){
        $ACCCND_DATEMARGIN_MONTH_STRING = 'Agustus';
    } else if($ACCCND_DATEMARGIN_MONTH == 9){
        $ACCCND_DATEMARGIN_MONTH_STRING = 'September';
    } else if($ACCCND_DATEMARGIN_MONTH == 10){
        $ACCCND_DATEMARGIN_MONTH_STRING = 'Oktober';
    } else if($ACCCND_DATEMARGIN_MONTH == 11){
        $ACCCND_DATEMARGIN_MONTH_STRING = 'November';
    } else if($ACCCND_DATEMARGIN_MONTH == 12){
        $ACCCND_DATEMARGIN_MONTH_STRING = 'Desember';
    } else { $ACCCND_DATEMARGIN_MONTH_STRING = ''; }

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
                <div style="text-align:center;vertical-align: middle;padding: 10px 0 10px 0;">
                    <h3>Account Condition</h3>
                </div>
                <table cellpadding="5" cellspacing="2" style="width:100%">
                    <tr>
                        <td>Kondisi ini efektif<br>mulai bulan</td>
                        <td colspan="5" style="vertical-align:top;">: '.$ACCCND_DATEMARGIN_MONTH_STRING.'</td>
                    </tr>
                    <tr>
                        <td>No. Account</td>
                        <td colspan="5">: '.$ACC_LOGIN.'</td>
                    </tr>
                    <tr>
                        <td>Nama Investor</td>
                        <td colspan="5">: '.$MBR_NAME.'</td>
                    </tr>
                    <tr>
                        <td>Email Investor</td>
                        <td colspan="5">: '.$MBR_EMAIL.'</td>
                    </tr>
                    <tr>
                        <td>No. Tlp</td>
                        <td>: '.$MBR_PHONE.'</td>
                        <td style="text-align:right;">Kota</td>
                        <td colspan="3">: '.$MBR_CITY.'</td>
                    </tr>
                    <tr>
                        <td>Introducing Broker</td>
                        <td style="vertical-align:top;">: '.$IB_NAME.'</td>
                        <td style="text-align:right;vertical-align:top;">Kode IB</td>
                        <td colspan="3" style="vertical-align:top;">: '.$IB_CODE.'-'.$IB_CITY.'</td>
                    </tr>
                    <tr>
                        <td>Tanggal Margin</td>
                        <td>: '.$ACCCND_DATEMARGIN_DAY.'</td>
                        <td style="text-align:right;">Bulan</td>
                        <td>: '.$ACCCND_DATEMARGIN_MONTH_STRING.'</td>
                        <td style="text-align:right;">Tahun</td>
                        <td>: '.$ACCCND_DATEMARGIN_YEAR.'</td>
                    </tr>
                    <tr>
                        <td>Nilai Margin</td>
                        <td>: IDR '.number_format($ACCCND_AMOUNTMARGIN, 0).'</td>
                        <td style="text-align:right;">Fixed Rate</td>
                        <td colspan="3">: '.$ACC_RATE.'</td>
                    </tr>
                    <tr>
                        <td>Bank</td>
                        <td colspan="5">: '.$ACC_F_APP_BK_1_NAMA.'</td>
                    </tr>
                    <tr>
                        <td>Branch</td>
                        <td>: '.$ACC_F_APP_BK_1_CBNG.'</td>
                        <td style="text-align:right;">Account</td>
                        <td colspan="3">: '.$ACC_F_APP_BK_1_ACC.'</td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr style="background-color:#efefef;">
                        <td colspan="6"><strong>Commision Charge</strong></td>
                    </tr>
                    <tr>
                        <td>Forex</td>
                        <td>: '.$ACCCND_CASH_FOREX.'</td>
                        <td>JPK50</td>
                        <td colspan="2">: '.$ACCCND_CASH_JPK50.'</td>
                    </tr>
                    <tr>
                        <td>Locco London</td>
                        <td>: '.$ACCCND_CASH_LOCO.'</td>
                        <td>JPK30</td>
                        <td colspan="2">: '.$ACCCND_CASH_JPK30.'</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>HKK50/HKJ50</td>
                        <td colspan="2">: '.$ACCCND_CASH_HK50.'</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>KRJ35</td>
                        <td colspan="2">: '.$ACCCND_CASH_KRJ35.'</td>
                    </tr>
                </table>
                <hr>
                <table width="100%">
                    <tr align="center">
                        <td width="50%">
                            <p class="style1">
                                Accounting<br />
                            </p>
                        </td>
                        <td width="50%">
                            <span class="style1">
                                Direktur Utama<br />
                            </span>
                        </td>
                    </tr>
                    <tr align="center">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr align="center">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr align="center">
                        <td width="50%"><span class="style1"></span> ( Euis Komala )</td>
                        <td width="50%"><span class="style1">( Ernawan Sukardi )</span></td>
                    </tr>
                </table>
                <div style="text-align:center;margin-top:2px;margin-left:25%">
                    <table>
                        <tr>
                            <td>Menyatakan pada tanggal</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>'.date('Y-m-d H:i:s', strtotime($ACCCND_DATEMARGIN)).'</strong></td>
                        </tr>
                    </table>
                </div>
            </body>
        </html>
    ';

    $dompdf->loadHtml($content);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("".$web_name_full." - 107.PBK.07",array("Attachment"=>0));
    
?>