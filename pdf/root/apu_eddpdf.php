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
            tb2.ID_ACC,
            tb2.ACC_DATETIME,
            tb2.ACC_F_APP_PRIBADI_NAMA,
            tb2.ACC_F_APP_PRIBADI_ID,
            (
                SELECT
                    tb_member.MBR_EMAIL
                FROM tb_member
                WHERE tb_member.MBR_ID = tb2.ACC_MBR
                LIMIT 1
            ) AS EMAIL,
            tb2.ACC_LOGIN,
            (
                SELECT
                    IFNULL(
                        (
                    SELECT
                        CONCAT(tb_range.RNG_LEVEL, "(",SUM(tb_rangensb.NSBR_VAL * tb_rangetype.RATYP_BBR),")")
                    FROM tb_range
                    WHERE tb_range.RNG_TYPE = 2 
                    AND SUM(tb_rangensb.NSBR_VAL * tb_rangetype.RATYP_BBR) >= tb_range.RNG_MIN 
                    AND SUM(tb_rangensb.NSBR_VAL * tb_rangetype.RATYP_BBR) <= CAST(CASE WHEN tb_range.RNG_MAX = -1 THEN ~0 ELSE tb_range.RNG_MAX END AS UNSIGNED)
                    )
                    , NULL)
                FROM tb_apuppt	
                JOIN tb_rangensb
                ON(tb_apuppt.APU_RNGNSB1 = tb_rangensb.ID_NSBR
                OR tb_apuppt.APU_RNGNSB2 = tb_rangensb.ID_NSBR
                OR tb_apuppt.APU_RNGNSB3 = tb_rangensb.ID_NSBR
                OR tb_apuppt.APU_RNGNSB4 = tb_rangensb.ID_NSBR
                OR tb_apuppt.APU_RNGNSB5 = tb_rangensb.ID_NSBR
                OR tb_apuppt.APU_RNGNSB6 = tb_rangensb.ID_NSBR
                OR tb_apuppt.APU_RNGNSB7 = tb_rangensb.ID_NSBR
                OR tb_apuppt.APU_RNGNSB8 = tb_rangensb.ID_NSBR
                OR tb_apuppt.APU_RNGNSB9 = tb_rangensb.ID_NSBR)
                JOIN tb_rangetype ON(tb_rangetype.ID_RATYP = tb_rangensb.NSBR_TYPE)
                WHERE tb_apuppt.APU_ACC = tb2.ID_ACC
            ) AS RNG,
            IFNULL(
                (
                    SELECT
                        SUM(IFNULL(tb_dpwd.DPWD_AMOUNT,0))
                    FROM tb_dpwd
                    WHERE tb_dpwd.DPWD_LOGIN = tb2.ID_ACC
                    AND tb_dpwd.DPWD_TYPE = 1
                    AND tb_dpwd.DPWD_STS = -1
                    AND tb_dpwd.DPWD_STSACC = -1
                    AND tb_dpwd.DPWD_STSVER = -1
                    AND DATE(tb_dpwd.DPWD_DATETIME) = DATE(tb_apuppt_edd.ADD_DATTIME)
                )
            ,0) AS TOTAL_DP,
            (
                SELECT
                    SUM(
                        IF(MT4_TRADES.CMD = 6 AND MT4_TRADES.PROFIT > 0, MT4_TRADES.PROFIT, 0) +
                            (
                                IF(MT4_TRADES.CMD = 1 OR MT4_TRADES.CMD = 0, MT4_TRADES.PROFIT, 0) +
                                (
                                    IF(MT4_TRADES.CMD = 1 OR MT4_TRADES.CMD = 0, MT4_TRADES.COMMISSION, 0) +
                                    IF(MT4_TRADES.CMD = 1 OR MT4_TRADES.CMD = 0, MT4_TRADES.`SWAPS`, 0)
                                )
                            ) +
                        IF(MT4_TRADES.CMD = 6 AND MT4_TRADES.PROFIT < 0, MT4_TRADES.PROFIT, 0)
                    )
                FROM MT4_TRADES
                WHERE MT4_TRADES.LOGIN = tb2.ACC_LOGIN
                AND DATE(MT4_TRADES.CLOSE_TIME) BETWEEN DATE("1970-01-01") AND DATE(tb_apuppt_edd.ADD_DATTIME)
            ) AS EQT,
            tb_apuppt_edd.ADD_DATTIME
        FROM (tb_racc tb1, tb_racc tb2)
        JOIN tb_apuppt_edd ON(tb_apuppt_edd.ADD_MBR = tb1.ACC_MBR)
        WHERE tb1.ACC_F_APP_PRIBADI_ID = tb2.ACC_F_APP_PRIBADI_ID 
        AND MD5(MD5(MD5(tb_apuppt_edd.ID_ADD))) = "'.$x.'"
        AND (tb2.ACC_LOGIN != "0" AND tb2.ACC_LOGIN IS NOT NULL)
        AND tb2.ACC_WPCHECK = 6
        AND (DATE(tb2.ACC_DATETIME) BETWEEN DATE("1970-01-01") AND DATE(tb_apuppt_edd.ADD_DATTIME))
        GROUP BY tb2.ACC_LOGIN
    ');
    if($SQL_DT1 && mysqli_num_rows($SQL_DT1) > 0){
        $tr                     = '';
        $ttl_dp                 = 0;
        $ttl_eq                 = 0;
        $ADD_DATTIME            = '' ;
        $ACC_F_APP_PRIBADI_NAMA = '' ;
        $ttl_ac = mysqli_num_rows($SQL_DT1);
        while($RSLT_DT1 = mysqli_fetch_assoc($SQL_DT1)){
            $tr .= '
                <tr>
                    <td style="border: 1px solid black;text-align:center;">'.$RSLT_DT1["ACC_DATETIME"].'</td>
                    <td style="border: 1px solid black;text-align:center;">'.$RSLT_DT1["ACC_F_APP_PRIBADI_NAMA"].'</td>
                    <td style="border: 1px solid black;text-align:center;">'.$RSLT_DT1["ACC_F_APP_PRIBADI_ID"].'</td>
                    <td style="border: 1px solid black;text-align:center;">'.$RSLT_DT1["EMAIL"].'</td>
                    <td style="border: 1px solid black;text-align:center;">'.$RSLT_DT1["ACC_LOGIN"].'</td>
                    <td style="border: 1px solid black;text-align:center;">'.$RSLT_DT1["RNG"].'</td>
                    <td style="border: 1px solid black;text-align:center;">Rp.'.number_format($RSLT_DT1["TOTAL_DP"], 0).'.</td>
                    <td style="border: 1px solid black;text-align:center;">$.'.number_format($RSLT_DT1["EQT"], 2).'.</td>
                </tr>
            ';
            $ttl_dp                += $RSLT_DT1["TOTAL_DP"];
            $ttl_eq                += $RSLT_DT1["EQT"];
            $ADD_DATTIME            = $RSLT_DT1["ADD_DATTIME"];
            $ACC_F_APP_PRIBADI_NAMA = $RSLT_DT1["ACC_F_APP_PRIBADI_NAMA"];
        }
        $SQL_EDD = mysqli_query($db,'
            SELECT
                tb_range_edd.ID_EDD,
                tb_range_edtype.EDTYPE_DESC,
                tb_range_edd.EDD_DESC,
                tb_range_edd.EDD_LV,
                tb_range_edd.EDD_TYPE,
                tb_apuppt_edd.ADD_ARF
            FROM tb_range_edd
            JOIN tb_apuppt_edd
            ON (tb_range_edd.ID_EDD = tb_apuppt_edd.ADD_VAL1
            OR tb_range_edd.ID_EDD = tb_apuppt_edd.ADD_VAL2
            OR tb_range_edd.ID_EDD = tb_apuppt_edd.ADD_VAL3)
            JOIN tb_range_edtype ON(tb_range_edtype.ID_EDTYPE = tb_range_edd.EDD_TYPE)
            WHERE MD5(MD5(MD5(tb_apuppt_edd.ID_ADD))) = "'.$x.'"
        ');
        $tr2 = '';
        if($SQL_EDD && mysqli_num_rows($SQL_EDD) > 0){
            $arf = '';
            while($RLST_EDD = mysqli_fetch_assoc($SQL_EDD)){
                $arf = $RLST_EDD["ADD_ARF"];
                $col_val = (($RLST_EDD["EDD_TYPE"] == 1) ? $ttl_ac.' account' : (($RLST_EDD["EDD_TYPE"] == 2) ? 'Rp.'.number_format($ttl_dp, 0) : (($RLST_EDD["EDD_TYPE"] == 3) ? '$.'.number_format($ttl_eq, 2) : NULL)));
                $tr2 .= '
                    <tr>
                        <td style="border: 1px solid black;text-align:center;">'.$RLST_EDD["EDTYPE_DESC"].'</td>
                        <td style="border: 1px solid black;text-align:center;">'.$RLST_EDD["EDD_DESC"].'</td>
                        <td style="border: 1px solid black;text-align:center;">'.$col_val.'</td>
                        <td style="border: 1px solid black;text-align:center;">'.$RLST_EDD["EDD_LV"].'</td>
                    </tr>
                ';
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
                        <h3>Sistem Informasi APU PPT - Enhanced Due Dilligence (EDD)</h3>
                    </div>
                    <br>
                    <div style="border:1px solid black;padding:5px;">
                        <table border="0" style="border-collapse: collapse;" width="100%">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid black;text-align:center;">TGL</th>
                                    <th style="border: 1px solid black;text-align:center;">Nama</th>
                                    <th style="border: 1px solid black;text-align:center;">NIK</th>
                                    <th style="border: 1px solid black;text-align:center;">Email</th>
                                    <th style="border: 1px solid black;text-align:center;">Login</th>
                                    <th style="border: 1px solid black;text-align:center;">Konfirmasi APUPPT</th>
                                    <th style="border: 1px solid black;text-align:center;">Deposit Per Hari</th>
                                    <th style="border: 1px solid black;text-align:center;">Equity</th>
                                </tr>
                            </thead>
                            <tbody>
                                '.$tr.'
                                <tr>
                                    <td colspan="6" style="border: 1px solid black;text-align:center;">Total Dari ('.$ttl_ac.') Akun</td>
                                    <td style="border: 1px solid black;text-align:center;">Rp.'.number_format($ttl_dp, 0).'</td>
                                    <td style="border: 1px solid black;text-align:center;">$.'.number_format($ttl_eq, 2).'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div style="border:1px solid black;padding:5px;">
                        <table border="0" style="border-collapse: collapse;" width="100%">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid black;text-align:center;">Parameter</th>
                                    <th style="border: 1px solid black;text-align:center;">Keterangan Data</th>
                                    <th style="border: 1px solid black;text-align:center;">Data Nasabah</th>
                                    <th style="border: 1px solid black;text-align:center;">TIngkat Risiko</th>
                                </tr>
                            </thead>
                            <tbody>
                                '.$tr2.'
                                <tr>
                                    <td style="border: 1px solid black;text-align:center;">Analisa Dan Rekomendasi, Faktor Lainnya</td>
                                    <td style="border: 1px solid black;text-align:center;" colspan="3">'.$arf.'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div style="text-align:center;margin-top:25px;margin-left:35%">
                        <table>
                            <tr>
                                <td>Timestamp Evaluasi</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td><strong>'.date('Y-m-d H:i:s', strtotime($ADD_DATTIME)).'</strong></td>
                            </tr>
                        </table>
                    </div>

                </body>
            </html>
        ';
    
        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("".$web_name_full." - Enhanced Due Dilligence (EDD) - $ACC_F_APP_PRIBADI_NAMA",array("Attachment"=>0));
    }
