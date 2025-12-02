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
    
    $SQL_DT1 = mysqli_query($db, '
        SELECT
            tb_apuppt.ID_APU,
            tb_apuppt.APU_RNGNSB1,
            tb_apuppt.APU_RNGNSB2,
            tb_apuppt.APU_RNGNSB3,
            tb_apuppt.APU_RNGNSB4,
            tb_apuppt.APU_RNGNSB5,
            tb_apuppt.APU_RNGNSB6,
            tb_apuppt.APU_RNGNSB7,
            tb_apuppt.APU_RNGNSB8,
            tb_apuppt.APU_RNGNSB9,
            tb_apuppt.APU_DATETIME,
            tb_apuppt.APU_TIMESTAMP,
            IFNULL(tb_racc.ACC_F_APP_PRIBADI_NAMA, tb_member.MBR_NAME) AS ACC_F_APP_PRIBADI_NAMA,
            IFNULL(tb_racc.ACC_LOGIN, "-") AS ACC_LOGIN,
            IFNULL(tb_racc.ACC_DATETIME, "-") AS ACC_DATETIME,
            IF(tb_racc.ACC_TYPE IS NULL, "-", 
                IF(tb_racc.ACC_TYPE = 1, CONCAT("SPA - ", UPPER(tb_racc.ACC_TYPEACC)), "Multilateral")
            ) AS PRD,
            IFNULL(tb_racc.ACC_INITIALMARGIN, 0) AS ACC_INITIALMARGIN,
            IFNULL(tb_racc.ACC_F_APP_KRJ_TYPE, "-") AS ACC_F_APP_KRJ_TYPE,
            IFNULL(tb_racc.ACC_F_APP_PRIBADI_ALAMAT, tb_member.MBR_ADDRESS) AS ACC_F_APP_PRIBADI_ALAMAT,
            IFNULL(tb_racc.ACC_F_APP_PRIBADI_ZIP, tb_member.MBR_ZIP) AS ACC_F_APP_PRIBADI_ZIP,
            IFNULL(tb_racc.ACC_F_APP_PRIBADI_ID, tb_member.MBR_NO_IDT) ACC_F_APP_PRIBADI_ID,
            tb_racc.ID_ACC,
            tb_racc.ACC_MBR,
            tb_racc.ACC_F_APP_FILE_IMG,
            tb_racc.ACC_F_APP_FILE_FOTO,
            tb_racc.ACC_F_APP_FILE_ID,
            tb_racc.ACC_F_APP_FILE_IMG2,
            IFNULL((
                SELECT
                tb_dpwd.DPWD_PIC
                FROM tb_dpwd
                WHERE tb_dpwd.DPWD_RACC = tb_racc.ID_ACC
                LIMIT 1
            ), "unknown-file.png") AS DPWD_PIC
        FROM tb_apuppt
        JOIN tb_member ON(tb_member.MBR_ID = tb_apuppt.APU_MBR)
        JOIN tb_racc ON(tb_racc.ACC_MBR = tb_member.MBR_ID AND tb_racc.ACC_DERE = 1 AND tb_apuppt.APU_ACC = tb_racc.ID_ACC)
        WHERE MD5(MD5(MD5(MD5(tb_apuppt.ID_APU)))) = "'.$x.'"
        LIMIT 1
    ');
    if($SQL_DT1 && mysqli_num_rows($SQL_DT1) > 0){
        $RSLT_APU = mysqli_fetch_assoc($SQL_DT1);
        $SQL_QUERY = mysqli_query($db,'
            SELECT
                tb_ori.RATYP_NAME AS TP,
                tb_rangensb.NSBR_TYNAME,
                tb_rangensb.NSBR_VAL,
                tb_ori.RATYP_BBR AS NSBR_BBTRISK,
                SUM(tb_rangensb.NSBR_VAL * tb_ori.RATYP_BBR) AS TTL
            FROM tb_rangetype tb_ori
            JOIN tb_rangensb
            JOIN tb_apuppt
            ON(
                tb_rangensb.NSBR_TYPE = tb_ori.ID_RATYP AND 
                (
                    tb_apuppt.APU_RNGNSB1 = tb_rangensb.ID_NSBR
                    OR tb_apuppt.APU_RNGNSB2 = tb_rangensb.ID_NSBR
                    OR tb_apuppt.APU_RNGNSB3 = tb_rangensb.ID_NSBR
                    OR tb_apuppt.APU_RNGNSB4 = tb_rangensb.ID_NSBR
                    OR tb_apuppt.APU_RNGNSB5 = tb_rangensb.ID_NSBR
                    OR tb_apuppt.APU_RNGNSB6 = tb_rangensb.ID_NSBR
                    OR tb_apuppt.APU_RNGNSB7 = tb_rangensb.ID_NSBR
                    OR tb_apuppt.APU_RNGNSB8 = tb_rangensb.ID_NSBR
                    OR tb_apuppt.APU_RNGNSB9 = tb_rangensb.ID_NSBR
                )
            )
            WHERE MD5(MD5(MD5(MD5(tb_apuppt.ID_APU)))) = "'.$x.'"
            GROUP BY tb_ori.ID_RATYP
            ORDER BY CASE NSBR_TYPE
                WHEN 9 THEN 1
                WHEN 8 THEN 2
                WHEN 7 THEN 3
                WHEN 1 THEN 4
                WHEN 2 THEN 5
                WHEN 3 THEN 6
                WHEN 5 THEN 7
                WHEN 4 THEN 8
                WHEN 6 THEN 9
            ELSE 10 END
        ');
        if($SQL_QUERY && mysqli_num_rows($SQL_QUERY) > 0){
            $tr      = '';
            $x       = 1;
            $ttl_all = 0;
            while($RSLT_QUERY = mysqli_fetch_assoc($SQL_QUERY)){
                $tr .= '
                    <tr>
                        <td style="border: 1px solid black;text-align:center;">'.$x.'.</td>
                        <td style="border: 1px solid black;text-align:center;">'.$RSLT_QUERY["TP"].'</td>
                        <td style="border: 1px solid black;text-align:center;">'.$RSLT_QUERY["NSBR_TYNAME"].'</td>
                        <td style="border: 1px solid black;text-align:center;">'.$RSLT_QUERY["NSBR_VAL"].'</td>
                        <td style="border: 1px solid black;text-align:center;">'.$RSLT_QUERY["NSBR_BBTRISK"].'</td>
                        <td style="border: 1px solid black;text-align:center;">'.$RSLT_QUERY["TTL"].'</td>
                    <tr>
                ';
                $ttl_all += $RSLT_QUERY["TTL"];
                $x++;
            }
            $SQL_RANGE = mysqli_query($db,'
                SELECT
                    tb_range.RNG_LEVEL
                FROM tb_range
                WHERE tb_range.RNG_TYPE = 2
                AND '.$ttl_all.' BETWEEN tb_range.RNG_MIN AND CAST(CASE WHEN tb_range.RNG_MAX = -1 THEN ~0 ELSE tb_range.RNG_MAX END AS UNSIGNED)
                LIMIT 1
            ');
            if($SQL_RANGE && mysqli_num_rows($SQL_RANGE) > 0){
                $RSLT_RANGE = mysqli_fetch_assoc($SQL_RANGE);
            }
            
        }
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
                        <h3>Sistem Informasi APU PPT - Penilaian Risiko Nasabah</h3>
                    </div>
                    <div style="border:1px solid black;padding:5px;">
                        <table style="width:100%">
                            <tr>
                                <td width="45%" style="vertical-align: top;">Nama Nasabah</td>
                                <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                                <td style="vertical-align: top;">'.$RSLT_APU["ACC_F_APP_PRIBADI_NAMA"].'</td>
                            </tr>
                            <tr>
                                <td width="45%" style="vertical-align: top;">No. Accout</td>
                                <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                                <td style="vertical-align: top;">'.$RSLT_APU["ACC_LOGIN"].'</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">Tanggal Buka Account</td>
                                <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                                <td style="vertical-align: top;">'.date("Y-m-d", strtotime($RSLT_APU["ACC_DATETIME"])).'</td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    <div style="border:1px solid black;padding:5px;">
                        <table border="0" style="border-collapse: collapse;" width="100%">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid black;text-align:center;">No.</th>
                                    <th style="border: 1px solid black;text-align:center;">Faktor Risiko</th>
                                    <th style="border: 1px solid black;text-align:center;">Keterangan Data</th>
                                    <th style="border: 1px solid black;text-align:center;">Nilai Risiko</th>
                                    <th style="border: 1px solid black;text-align:center;">Bobot Risiko</th>
                                    <th style="border: 1px solid black;text-align:center;">Total Risiko</th>
                                </tr>
                            </thead>
                            <tbody>
                                '.$tr.'
                                <tr>
                                    <td colspan="4" style="border: 1px solid black;text-align:center;">
                                        <h3>Penilaian Risiko Keseluruhan/Total:</h3>
                                    </td>
                                    <td colspan="2" style="border: 1px solid black;text-align:center;">'.$ttl_all.'</td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="border: 1px solid black;text-align:center;">
                                        <h3>Tingkat Risiko:</h3>
                                    </td>
                                    <td colspan="2" style="border: 1px solid black;text-align:center;">'.$RSLT_RANGE["RNG_LEVEL"].'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div style="text-align:center;margin-top:25px;margin-left:25%">
                        <table>
                            <tr>
                                <td>Timestamp Evaluasi</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td><strong>'.date('Y-m-d H:i:s', strtotime($RSLT_APU["APU_DATETIME"])).'</strong></td>
                            </tr>
                        </table>
                    </div>

                </body>
            </html>
        ';
    
        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("".$web_name_full." - Evaluasi Nasabah ".$RSLT_APU["ACC_LOGIN"],array("Attachment"=>0));
    }

    
?>