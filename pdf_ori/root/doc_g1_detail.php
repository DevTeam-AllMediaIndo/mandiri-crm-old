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

    $SQL_QUERY = mysqli_query($db,'
        SELECT
            tb_admin.ADM_NAME,
            tb_admin.ADM_PHONE,
            tb_member.MBR_EMAIL,
            tb_member.MBR_NAME,
            tb_member.MBR_PHONE,
            tb_member.MBR_NO_IDT,
            tb_g1.G1_PRODUKBER,
            tb_g1.G1_PROFILPER,
            tb_g1.G1_APLIASIPEM,
            tb_g1.G1_DAYLISTA,
            tb_g1.G1_SARANA,
            tb_g1.G1_PERJN,
            tb_g1.G1_PRJN_DLR_KTN,
            tb_g1.G1_JANJIUNTUNG,
            tb_g1.G1_VER,
            tb_racc.ACC_F_IBCODE,
            tb_schedule.SCHD_REASON
        FROM tb_schedule
        JOIN tb_member
        JOIN tb_g1
        JOIN tb_admin
        JOIN tb_racc
        ON (tb_schedule.SCHD_ID = tb_member.MBR_ID
        AND tb_g1.G1_MBR = tb_member.MBR_ID
        AND tb_admin.ADM_ID = tb_g1.G1_ADM
        AND tb_racc.ACC_MBR = tb_member.MBR_ID)
        WHERE MD5(MD5(tb_schedule.ID_SCHD)) = "'.$x.'"
        LIMIT 1
    ');
    if($SQL_QUERY && mysqli_num_rows($SQL_QUERY) > 0){
        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        $ADM_NAME = $RESULT_QUERY['ADM_NAME'];
        $ADM_PHONE = $RESULT_QUERY['ADM_PHONE'];
        $MBR_EMAIL = $RESULT_QUERY['MBR_EMAIL'];
        $MBR_NAME = $RESULT_QUERY['MBR_NAME'];
        $MBR_PHONE = $RESULT_QUERY['MBR_PHONE'];
        $MBR_NO_IDT = $RESULT_QUERY['MBR_NO_IDT'];
        $G1_PRODUKBER = $RESULT_QUERY['G1_PRODUKBER'];
        $G1_PROFILPER = $RESULT_QUERY['G1_PROFILPER'];
        $G1_APLIASIPEM = $RESULT_QUERY['G1_APLIASIPEM'];
        $G1_DAYLISTA = $RESULT_QUERY['G1_DAYLISTA'];
        $G1_SARANA = $RESULT_QUERY['G1_SARANA'];
        $G1_PERJN = $RESULT_QUERY['G1_PERJN'];
        $G1_PRJN_DLR_KTN = $RESULT_QUERY['G1_PRJN_DLR_KTN'];
        $G1_JANJIUNTUNG = $RESULT_QUERY['G1_JANJIUNTUNG'];
        $G1_VER = $RESULT_QUERY['G1_VER'];
        $ACC_F_IBCODE = $RESULT_QUERY['ACC_F_IBCODE'];
        $SCHD_REASON = $RESULT_QUERY['SCHD_REASON'];
    } else {
        $SQL_QUERY1 = mysqli_query($db,'
            SELECT
                tb_admin.ADM_NAME,
                tb_admin.ADM_PHONE,
                tb_member.MBR_EMAIL,
                tb_member.MBR_NAME,
                tb_member.MBR_PHONE,
                tb_member.MBR_NO_IDT,
                tb_g1.G1_PRODUKBER,
                tb_g1.G1_PROFILPER,
                tb_g1.G1_APLIASIPEM,
                tb_g1.G1_DAYLISTA,
                tb_g1.G1_SARANA,
                tb_g1.G1_PERJN,
                tb_g1.G1_PRJN_DLR_KTN,
                tb_g1.G1_JANJIUNTUNG,
                tb_g1.G1_VER,
                tb_racc.ACC_F_IBCODE,
                tb_schedule1.SCHD_REASON
            FROM tb_schedule1
            JOIN tb_member
            JOIN tb_g1
            JOIN tb_admin
            JOIN tb_racc
            ON (tb_schedule1.SCHD_ID = tb_member.MBR_ID
            AND tb_g1.G1_MBR = tb_member.MBR_ID
            AND tb_admin.ADM_ID = tb_g1.G1_ADM
            AND tb_racc.ACC_MBR = tb_member.MBR_ID)
            WHERE MD5(MD5(tb_schedule1.ID_SCHD)) = "'.$x.'"
            LIMIT 1
        ');
        if($SQL_QUERY1 && mysqli_num_rows($SQL_QUERY1) > 0){
            $RESULT_QUERY1 = mysqli_fetch_assoc($SQL_QUERY1);
            $ADM_NAME =         $RESULT_QUERY1['ADM_NAME'];
            $ADM_PHONE =        $RESULT_QUERY1['ADM_PHONE'];
            $MBR_EMAIL =        $RESULT_QUERY1['MBR_EMAIL'];
            $MBR_NAME =         $RESULT_QUERY1['MBR_NAME'];
            $MBR_PHONE =        $RESULT_QUERY1['MBR_PHONE'];
            $MBR_NO_IDT =       $RESULT_QUERY1['MBR_NO_IDT'];
            $G1_PRODUKBER =     $RESULT_QUERY1['G1_PRODUKBER'];
            $G1_PROFILPER =     $RESULT_QUERY1['G1_PROFILPER'];
            $G1_APLIASIPEM =    $RESULT_QUERY1['G1_APLIASIPEM'];
            $G1_DAYLISTA =      $RESULT_QUERY1['G1_DAYLISTA'];
            $G1_SARANA =        $RESULT_QUERY1['G1_SARANA'];
            $G1_PERJN =         $RESULT_QUERY1['G1_PERJN'];
            $G1_PRJN_DLR_KTN =  $RESULT_QUERY1['G1_PRJN_DLR_KTN'];
            $G1_JANJIUNTUNG =   $RESULT_QUERY1['G1_JANJIUNTUNG'];
            $G1_VER =           $RESULT_QUERY1['G1_VER'];
            $ACC_F_IBCODE =     $RESULT_QUERY1['ACC_F_IBCODE'];
            $SCHD_REASON =      $RESULT_QUERY1['SCHD_REASON'];
        } else {
            $ADM_NAME = '';
            $ADM_PHONE = '-';
            $MBR_EMAIL = '';
            $MBR_NAME = '';
            $MBR_PHONE = '';
            $MBR_NO_IDT = '';
            $G1_PRODUKBER = '';
            $G1_PROFILPER = '';
            $G1_APLIASIPEM = '';
            $G1_DAYLISTA = '';
            $G1_SARANA = '';
            $G1_PERJN = '';
            $G1_PRJN_DLR_KTN = '';
            $G1_JANJIUNTUNG = '';
            $G1_VER = '';
            $ACC_F_IBCODE = '-';
            $SCHD_REASON = '';
        };
    };

    if($G1_PRODUKBER == 'Disampaikan'){
        $CHECKDIS = 'checked="checked"';
        $CHECKNODIS = '';
    }else if($G1_PRODUKBER == 'Tidak Disampaikan'){
        $CHECKDIS = '';
        $CHECKNODIS = 'checked="checked"';
    }else{
        $CHECKDIS = '';
        $CHECKNODIS = '';
    };
    if($G1_PROFILPER == 'Disampaikan'){
        $CHECKDIS2 = 'checked="checked"';
        $CHECKNODIS2 = '';
    }else if($G1_PROFILPER == 'Tidak Disampaikan'){
        $CHECKDIS2 = '';
        $CHECKNODIS2 = 'checked="checked"';
    }else{
        $CHECKDIS2 = '';
        $CHECKNODIS2 = '';
    };
    if($G1_PERJN == 'Disampaikan'){
        $CHECKDIS3 = 'checked="checked"';
        $CHECKNODIS3 = '';
    }else if($G1_PERJN == 'Tidak Disampaikan'){
        $CHECKDIS3 = '';
        $CHECKNODIS3 = 'checked="checked"';
    }else{
        $CHECKDIS3 = '';
        $CHECKNODIS3 = '';
    };
    if($G1_APLIASIPEM == 'Disampaikan'){
        $CHECKDIS4 = 'checked="checked"';
        $CHECKNODIS4 = '';
    }else if($G1_APLIASIPEM == 'Tidak Disampaikan'){
        $CHECKDIS4 = '';
        $CHECKNODIS4 = 'checked="checked"';
    }else{
        $CHECKDIS4 = '';
        $CHECKNODIS4 = '';
    };
    if($G1_DAYLISTA == 'Disampaikan'){
        $CHECKDIS5 = 'checked="checked"';
        $CHECKNODIS5 = '';
    }else if($G1_DAYLISTA == 'Tidak Disampaikan'){
        $CHECKDIS5 = '';
        $CHECKNODIS5 = 'checked="checked"';
    }else{
        $CHECKDIS5 = '';
        $CHECKNODIS5 = '';
    };
    if($G1_SARANA == 'Disampaikan'){
        $CHECKDIS6 = 'checked="checked"';
        $CHECKNODIS6 = '';
    }else if($G1_SARANA == 'Tidak Disampaikan'){
        $CHECKDIS6 = '';
        $CHECKNODIS6 = 'checked="checked"';
    }else{
        $CHECKDIS6 = '';
        $CHECKNODIS6 = '';
    };
    if($G1_PRJN_DLR_KTN == 'Ada'){
        $CHECKDIS7 = 'checked="checked"';
        $CHECKNODIS7 = '';
    }else if($G1_PRJN_DLR_KTN == 'Tidak Ada'){
        $CHECKDIS7 = '';
        $CHECKNODIS7 = 'checked="checked"';
    }else{
        $CHECKDIS7 = '';
        $CHECKNODIS7 = '';
    };
    if($G1_JANJIUNTUNG == 'Ada'){
        $CHECKDIS8 = 'checked="checked"';
        $CHECKNODIS8 = '';
    }else if($G1_JANJIUNTUNG == 'Tidak Ada'){
        $CHECKDIS8 = '';
        $CHECKNODIS8 = 'checked="checked"';
    }else{
        $CHECKDIS8 = '';
        $CHECKNODIS8 = '';
    };
    if($G1_VER == 'Milik Nasabah'){
        $CHECKDIS9 = 'checked="checked"';
        $CHECKNODIS9 = '';
    }else if($G1_VER == 'Bukan Milik Nasabah'){
        $CHECKDIS9 = '';
        $CHECKNODIS9 = 'checked="checked"';
    }else{
        $CHECKDIS9 = '';
        $CHECKNODIS9 = '';
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
                    <h3>DOKUMEN G1</h3>
                </div>
                <div style="border:1px solid black;padding:5px;">
                    <table style="width:100%">
                        <tr>
                            <td width="45%" style="vertical-align: top;">Nama</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">'.$ADM_NAME.'</td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Nomor Telephone</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">'.$ADM_PHONE.'</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Jabatan</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">WAKIL PIALANG PEMASARAN</td>
                        </tr>
                    </table>
                </div>
                <br>
                <div style="border:1px solid black;padding:5px;">
                    <table style="width:100%">
                        <tr>
                            <td width="45%" style="vertical-align: top;">Nama</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">'.$MBR_NAME.'</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Nomor Telephone</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">'.$MBR_PHONE.'</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Nomor NIK</td>
                            <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">'.$MBR_NO_IDT.'</td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Email</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">'.$MBR_EMAIL.'</td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Kode Referal</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;">'.$ACC_F_IBCODE.'</td>
                        </tr>
                    </table>
                </div>
                <br>
                <div style="border:1px solid black;padding:5px;">
                    <table style="width:100%">
                        <tr>
                            <td style="vertical-align: top;"><b>A</b>.</td>
                            <td width="45%" style="vertical-align: top;">PRODUK BERJANGKA</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="product_ber" value="Disampaikan" '.$CHECKDIS.'>&nbsp;&nbsp;<strong>Disampaikan</strong>
                            </td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="product_ber" value="Tidak Disampaikan" '.$CHECKNODIS.'>&nbsp;&nbsp;<strong>Tidak Disampaikan</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;"><b>B</b>.</td>
                            <td width="45%" style="vertical-align: top;">PROFIL PERUSAHAAN</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="pfrl_perusahaan" value="Disampaikan" required  '.$CHECKDIS2.'>&nbsp;&nbsp;<strong>Disampaikan</strong>
                            </td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="pfrl_perusahaan" value="Tidak Disampaikan" required '.$CHECKNODIS2.'>&nbsp;&nbsp;<strong>Tidak Disampaikan</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;"><b>C</b>.</td>
                            <td width="45%" style="vertical-align: top;">PERJANJIAN AMANAT</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;" >:</div></td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="prj_amanat" value="Disampaikan" required  '.$CHECKDIS3.'>&nbsp;&nbsp;<strong>Disampaikan</strong>
                            </td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="prj_amanat" value="Tidak Disampaikan" required '.$CHECKNODIS3.'>&nbsp;&nbsp;<strong>Tidak Disampaikan</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;"><b>D</b>.</td>
                            <td width="45%" style="vertical-align: top;">APLIKASI PEMBUKAAN AKUN</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="apk_pbk_akun" value="Disampaikan" required  '.$CHECKDIS4.'>&nbsp;&nbsp;<strong>Disampaikan</strong>
                            </td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="apk_pbk_akun" value="Tidak Disampaikan" required '.$CHECKNODIS4.'>&nbsp;&nbsp;<strong>Tidak Disampaikan</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;"><b>E</b>.</td>
                            <td width="45%" style="vertical-align: top;">DAILY STATEMENT</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="dly_stmnt" value="Disampaikan" required  '.$CHECKDIS5.'>&nbsp;&nbsp;<strong>Disampaikan</strong>
                            </td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="dly_stmnt" value="Tidak Disampaikan" required '.$CHECKNODIS5.'>&nbsp;&nbsp;<strong>Tidak Disampaikan</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;"><b>F</b>.</td>
                            <td width="45%" style="vertical-align: top;">SARANA PERSELISIHAN</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="srna_prslhn" value="Disampaikan" required  '.$CHECKDIS6.'>&nbsp;&nbsp;<strong>Disampaikan</strong>
                            </td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="srna_prslhn" value="Tidak Disampaikan" required '.$CHECKNODIS6.'>&nbsp;&nbsp;<strong>Tidak Disampaikan</strong>
                        </td>
                        <tr>
                            <td style="vertical-align: top;"><b>G</b>.</td>
                            <td width="45%" style="vertical-align: top;">PERJANJIAN DILUAR KETENTUAN ANTARA CALON NASABAH DENGAN WPP MAUPUN MWPP</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="prj_dlr_ktn" value="Ada" required id="prj_dlr_ktn1" onchange="GetSelectedTextValue()" '.$CHECKDIS7.'>&nbsp;&nbsp;<strong>Ada</strong>
                            </td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="prj_dlr_ktn" value="Tidak Ada" required id="prj_dlr_ktn2" onchange="GetSelectedTextValue()" '.$CHECKNODIS7.'>&nbsp;&nbsp;<strong>Tidak Ada</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;"><b>H</b>.</td>
                            <td width="45%" style="vertical-align: top;">JANJI KEUNTUNGAN ATAU SHARING PROFIT</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="sharing_prft" value="Ada" required  id="share_prof1" onchange="GetSelectedTextValue()" '.$CHECKDIS8.'>&nbsp;&nbsp;<strong>Ada</strong>
                            </td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="sharing_prft" value="Tidak Ada" required id="share_prof2" onchange="GetSelectedTextValue()" '.$CHECKNODIS8.'>&nbsp;&nbsp;<strong>Tidak Ada</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;"><b>I</b>.</td>
                            <td width="45%" style="vertical-align: top;">VERIFIKASI EMAIL NASABAH</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="ver_email" value="Milik Nasabah" required  id="ver_nas1" onchange="GetSelectedTextValue()" '.$CHECKDIS9.'>&nbsp;&nbsp;<strong>Milik Nasabah</strong>
                            </td>
                            <td style="vertical-align: top; white-space: nowrap;">
                                <input type="radio" name="ver_email" value="Bukan Milik Nasabah" required id="ver_nas2" onchange="GetSelectedTextValue()" '.$CHECKNODIS9.'>&nbsp;&nbsp;<strong>Bukan Milik Nasabah</strong>
                            </td>
                        </tr>
                    </table>
                </div>
                <br>
                <div style="border:1px solid black;padding:5px;">
                    Note :<br>'.$SCHD_REASON.'
                </div>
            </body>
        </html>
    ';

    $dompdf->loadHtml($content);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("".$web_name_full." - Document G1",array("Attachment"=>0));
    
?>