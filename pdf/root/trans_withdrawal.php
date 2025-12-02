
<?php
    date_default_timezone_set("Asia/Jakarta");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../../setting.php';
    require_once 'vendor/autoload.php';
    use Dompdf\Dompdf;
    $dompdf = new Dompdf();

    $x = form_input($_GET["x"]);
    
    function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = penyebut($nilai - 10). " Belas";
		} else if ($nilai < 100) {
			$temp = penyebut($nilai/10)." Puluh". penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " Seratus" . penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = penyebut($nilai/100) . " Ratus" . penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " Seribu" . penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = penyebut($nilai/1000) . " Ribu" . penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = penyebut($nilai/1000000) . " Juta" . penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = penyebut($nilai/1000000000) . " Milyar" . penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = penyebut($nilai/1000000000000) . " Trilyun" . penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}
 
	function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim(penyebut($nilai));
		} else {
			$hasil = trim(penyebut($nilai));
		}     		
		return $hasil;
	}

    $FRMTRS_NO = 0;
    $FRMTRS_MODE = 0;
    
    $SQL_QUERY = mysqli_query($db, '
        SELECT 
            tb_dpwd.DPWD_DATETIME,
            tb_dpwd.DPWD_VOUCHER,
            tb_racc.ACC_LOGIN,
            tb_member.MBR_NAME,
            tb_dpwd.DPWD_AMOUNT,
            tb_racc.ACC_RATE
        FROM tb_member
        JOIN tb_racc
        JOIN tb_dpwd
        ON (tb_member.MBR_ID = tb_racc.ACC_MBR
        AND tb_member.MBR_ID = tb_dpwd.DPWD_MBR)
        WHERE MD5(MD5(tb_dpwd.ID_DPWD)) = "'.$x.'"
        AND tb_racc.ACC_DERE = 1
        AND tb_racc.ACC_LOGIN <> "0"
        LIMIT 1
    ');
    if(mysqli_num_rows($SQL_QUERY) > 0){
        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        $DPWD_DATETIME = date("d-m-Y",strtotime($RESULT_QUERY['DPWD_DATETIME']));
        $DPWD_VOUCHER = $RESULT_QUERY['DPWD_VOUCHER'];
        $ACC_LOGIN = $RESULT_QUERY['ACC_LOGIN'];
        $MBR_NAME = $RESULT_QUERY['MBR_NAME'];
        $AMOUNT_RATE = $RESULT_QUERY['ACC_RATE'];
        $AMOUNT_IDR = $RESULT_QUERY['DPWD_AMOUNT'];
        if($RESULT_QUERY['ACC_RATE'] == 0){ $rate = 10000;} else {$rate = $RESULT_QUERY['ACC_RATE'];}
        $AMOUNT_USD = $RESULT_QUERY['DPWD_AMOUNT']/$rate;
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
                <table width="100%">
                    <tr>
                        <td width="35%" style="vertical-align: top; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$setting_pdf_logo."")).'" width="100%" ></td>
                        <td width="30%" style="vertical-align: top; "></td>
                        <td width="35%" style="vertical-align: top; ">
                            <table width="100%">
                                <tr>
                                    <td>No.</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>'.$DPWD_VOUCHER.'</td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>&nbsp;:&nbsp;</td>
                                    <td>'.$DPWD_DATETIME.'</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <!--<center><strong><u>WITHDRAWAL FORM</u></strong></center>-->
                <center><strong><u>MARGIN OUT</u></strong></center>
                <table style="margin-top:25px;" width="100%">
                    <tr>
                        <td style="width:1%; white-space: nowrap;">A/C. No.</td>
                        <td width="1%">&nbsp;:&nbsp;</td>
                        <td style="border-bottom: 1px solid;">'.$ACC_LOGIN.'</td>
                    </tr>
                    <tr>
                        <td style="width:1%; white-space: nowrap;">Clients Name</td>
                        <td width="1%">&nbsp;:&nbsp;</td>
                        <td style="border-bottom: 1px solid;">'.$MBR_NAME.'</td>
                    </tr>
                    <tr>
                        <td style="width:1%; white-space: nowrap;">The sum of rupiah</td>
                        <td width="1%">&nbsp;:&nbsp;</td>
                        <td>'.terbilang($AMOUNT_IDR).'</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td width="1%">&nbsp;</td>
                        <td style="border-bottom: 1px solid;text-align:left">( Rp '.number_format($AMOUNT_IDR, 0).' )</td>
                    </tr>
                </table>
                <table border="0" style="border-collapse: collapse;margin-top:5px;" width="100%">
                    <tr>
                        <td width="55%">
                            <div style="margin-bottom:5px;">Detailed Description :</div>
                            <table border="0" style="border-collapse: collapse;" width="100%">
                                <tr>
                                    <td style="border: 1px solid black;text-align:center;">Amount in USD</td>
                                    <td style="border: 1px solid black;text-align:center;">Rate</td>
                                    <td style="border: 1px solid black;text-align:center;">Amount in Rp.</td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid black;text-align:right;">'.number_format($AMOUNT_IDR/$rate, 2).'&nbsp;&nbsp;</td>
                                    <td style="border-left: 1px solid black;text-align:right;">'.number_format($AMOUNT_RATE, 0).'&nbsp;&nbsp;</td>
                                    <td style="border-left: 1px solid black;text-align:right;border-right: 1px solid black;">'.number_format($AMOUNT_IDR, 0).'&nbsp;&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black;border-right: 1px solid black;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid black;border-bottom: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black;border-bottom: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="border-left: 1px solid black;border-bottom: 1px solid black;"></td>
                                    <td style="border-bottom: 1px solid black;">Total</td>
                                    <td style="border: 1px solid black;text-align:right;">Rp '.number_format($AMOUNT_IDR, 0).'&nbsp;&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                        <td width="5%">&nbsp;</td>
                        <td width="40%">
                            <p>For and On Behalf Of<br>
                            '.$web_name_full.'</p>
                            <br><br><br>
                            <div style="border-bottom: 1px solid black;"></div>
                        </td>
                    </tr>
                </table>
            </body>
        </html>
    ';
    $dompdf->loadHtml($content);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("Withdrawal Form ".$web_name_short."",array("Attachment"=>0));   