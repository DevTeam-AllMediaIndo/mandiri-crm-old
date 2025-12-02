<?php
    require 'phpmailer/Exception.php';
    require 'phpmailer/PHPMailer.php';
    require 'phpmailer/SMTP.php';
    
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    function ddttotChk($nama, $niknum, $purp){
        require_once 'vendor/autoload.php';
        try {
            // use PhpOffice\PhpSpreadsheet\Spreadsheet;
            // use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

            $inputFileName = 'assets/ddtot.xls';
            //code...
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
            $testAgainstFormats = [
                \PhpOffice\PhpSpreadsheet\IOFactory::READER_XLS,
                \PhpOffice\PhpSpreadsheet\IOFactory::READER_HTML,
            ];

            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            /**  Create a new Reader of the type that has been identified  **/
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $spreadsheet = $reader->load($inputFileName);
            // print_r($spreadsheet->getActiveSheet()->getHighestRow());
            $B = [];
            $A = [];
            foreach($spreadsheet->getActiveSheet()->getRowIterator() as $KEY1 => $row) {
                foreach ($row->getCellIterator() as $key => $value) {
                    if($key == 'A'){
                        $A[] = $value->getValue();
                    }
                    if($key == 'B'){
                        $B[] = $value->getValue();
                    }
                }
            }
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            throw $e;
        }
        $jumPtnsi = 0;
        $name = preg_replace('/^\s+|\s+$/', '', strtoupper($nama));
        $ARR_EQSTR = array_map(function($val){ return strtoupper($val); }, $A);
        $ARR_EXPLD = array_map(function($val){return preg_replace('/^\s+|\s+$/', '', explode('ALIAS', $val)); }, $ARR_EQSTR);
        $name_srch = array_keys(array_filter(array_slice($ARR_EXPLD, 1), function($ARR) use ($name){
            foreach($ARR as $ARR_KEY => $ARR_VAL){
                return (array_search($name,$ARR) !== FALSE) ? ["FKEY" => $ARR_KEY, "SKEY" => array_search($name,$ARR)] : [];
            }
        }));
        $rsltA = (count($name_srch) == 0) ? NULL : $name_srch[0];
        if(!is_null($rsltA)){ $jumPtnsi += 1; }

        $nik        = $niknum;
        $ARR_B      = array_map(function($val){ return strtoupper($val); }, $B);
        $idnum_srch = array_keys(preg_grep("/([^0-9]|^)$nik([^0-9]|$)/", $ARR_B));
        $rsltB      = (count($idnum_srch) == 0) ? NULL : $idnum_srch[0];
        if(!is_null($rsltB)){ $jumPtnsi += 1; }

        if($purp == 'jumlah'){
            return $jumPtnsi;
        }else if($purp == 'link'){
            return '&nm='.base64_encode($nama).'&nms='.base64_encode((!is_null($rsltA)) ? $rsltA : 'nan').'&nkm='.base64_encode($niknum).'&nks='.base64_encode((!is_null($rsltB)) ? $rsltB : 'nan').'';
        }else{ return NULL; }

    }
    function ret_dat($dat_nas, $dat_comp){
        if(!is_null($dat_comp) && $dat_nas > 0){
            $comp_value = preg_replace( '/[^\d+-]/', '',$dat_comp);
            $comp_sign  = preg_replace( '/([^><])+$/', '', $dat_comp );
            if(strpos($comp_value, '-') > 0){
                $ARR_VAL = explode("-",$comp_value);
                if(count($ARR_VAL) == 2){
                    if((int)$dat_nas > (int)$ARR_VAL[0] && (int)$dat_nas <= (int)$ARR_VAL[1]){
                        return $dat_comp;
                    }else{ return NULL; }
                }else{ return false; }
            }else if(strpos($comp_value, '-') == false && $comp_sign == '<'){
                if((int)$dat_nas <= (int)$comp_value){
                    return $dat_comp;
                }else{ return NULL; }
            }else if(strpos($comp_value, '-') == false && $comp_sign == '>'){
                if((int)$dat_nas > (int)$comp_value){
                    return $dat_comp;
                }else{ return NULL; }
            }else { return NULL; }
        }else{ return NULL; }
    }
    function get_prov($kdp, $purp){
        global $db;
        if(!is_null($kdp)){
            $SQL_PROV = mysqli_query($db,'
                SELECT
                    tb_kodepos.KDP_PROV
                FROM tb_kodepos
                WHERE tb_kodepos.KDP_POS = '.$kdp.'
                LIMIT 1
            ');
            if($SQL_PROV && mysqli_num_rows($SQL_PROV) > 0){
                $RSLT_PROV = mysqli_fetch_assoc($SQL_PROV);
                if($purp == 'name'){
                    return '('.$RSLT_PROV["KDP_PROV"].')';
                }else if($purp == 'match'){
                    return $RSLT_PROV["KDP_PROV"];
                }else{ return NULL; }
            }else{ return NULL; }
        }else{ return NULL; }
    }
    function get_prov_nik($kdp, $purp){
        global $db;
        if(!is_null($kdp)){
            if(is_numeric($kdp)){
                $SQL_PROV = mysqli_query($db,'
                    SELECT
                        tb_province_code.PRV_NAME
                    FROM tb_province_code
                    WHERE tb_province_code.PRV_CODE = '.substr($kdp, 0, 2).'
                    LIMIT 1
                ');
                if($SQL_PROV && mysqli_num_rows($SQL_PROV) > 0){
                    $RSLT_PROV = mysqli_fetch_assoc($SQL_PROV);
                    if($purp == 'name'){
                        return '('.$RSLT_PROV["PRV_NAME"].')';
                    }else if($purp == 'match'){
                        return $RSLT_PROV["PRV_NAME"];
                    }else{ return NULL; }
                }else{ return NULL; }
            }else{ return NULL; }
        }else{ return NULL; }
    }
    
    if(isset($_GET["x"])){
        $x = form_input($_GET["x"]);
        $SQL_DT = mysqli_query($db,'
            SELECT
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
                ), "unknown-file.png") AS DPWD_PIC,
                tb_member.MBR_ID,
                tb_member.MBR_EMAIL,
                tb_member.MBR_NAME
            FROM tb_member
            LEFT JOIN tb_racc
            ON(tb_member.MBR_ID = tb_racc.ACC_MBR
            AND tb_racc.ACC_DERE = 1)
            WHERE MD5(MD5(tb_member.MBR_ID)) = "'.$x.'"
            LIMIT 1
        ');
        if($SQL_DT && mysqli_num_rows($SQL_DT) > 0){
            $RSLT_DT = mysqli_fetch_assoc($SQL_DT);
            if(isset($_POST["iser"])){

                $iser = form_input($_POST["iser"]);
                $cttn = (isset($_POST["cttn"])) ? form_input($_POST["cttn"]) : '';
                if($iser == 0 || $iser == 1 ||  $iser == 2){
                    $param1 = (isset($_POST["param1"])) ? form_input($_POST["param1"]) : 'NULL';
                    $param2 = (isset($_POST["param2"])) ? form_input($_POST["param2"]) : 'NULL';
                    $param3 = (isset($_POST["param3"])) ? form_input($_POST["param3"]) : 'NULL';
                    $param4 = (isset($_POST["param4"])) ? form_input($_POST["param4"]) : 'NULL';

                    $ARR_NC = [0, 1];
                    if(in_array($param1, $ARR_NC) && in_array($param2, $ARR_NC) && in_array($param3, $ARR_NC) && in_array($param4, $ARR_NC)){
                        $STS = [ "ditolak",  "dipertimbangkan",  "dilanjutkan"];
                        $lstr = $STS[$iser].'('.$x.')';

                        try {
                            mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);
                            mysqli_begin_transaction($db);

                            mysqli_stmt_execute(mysqli_prepare($db, '
                                INSERT INTO tb_apuppt_evcannas SET
                                tb_apuppt_evcannas.EVCAN_MBR  = '.$RSLT_DT["MBR_ID"].',
                                tb_apuppt_evcannas.EVCAN_VAL1 = '.$param1.',
                                tb_apuppt_evcannas.EVCAN_VAL2 = '.$param2.',
                                tb_apuppt_evcannas.EVCAN_VAL3 = '.$param3.',
                                tb_apuppt_evcannas.EVCAN_VAL4 = '.$param4.',
                                tb_apuppt_evcannas.EVCAN_CONF = '.$iser.',
                                tb_apuppt_evcannas.EVCAN_DATETIME = "'.date("Y-m-d H:i:s").'",
                                tb_apuppt_evcannas.EVCAN_TIMESTAMP = "'.date("Y-m-d H:i:s").'"
                            '));

                            mysqli_stmt_execute(mysqli_prepare($db, '
                                INSERT INTO tb_note SET
                                tb_note.NOTE_MBR = '.$RSLT_DT["MBR_ID"].',
                                tb_note.NOTE_TYPE = "APUPPT EVCANNAS1 : '.$lstr.'",
                                tb_note.NOTE_NOTE = "'.$cttn.'",
                                tb_note.NOTE_DATETIME = "'.date('Y-m-d H:i:s').'"
                            '));

                            if($iser == 0 && (!empty($_GET["xacc"]))){
                                $xacc = form_input($_GET["xacc"]);
                                mysqli_stmt_execute(mysqli_prepare($db, '
                                    UPDATE tb_racc SET
                                        tb_racc.ACC_MBR = CONCAT(tb_racc.ACC_MBR, 0)
                                    WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$xacc.'"
                                '));
                            }

                            
                            $mail = new PHPMailer(true);
                            $mail->isSMTP();
                            $mail->Host       = $setting_email_host_api;
                            $mail->SMTPAuth   = true;
                            $mail->Username   = $setting_email_support_name;
                            $mail->Password   = $setting_email_support_password;
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                            $mail->Port       = $setting_email_port_api;

                            //Recipients
                            $mail->setFrom($setting_email_support_name, $web_name_full);
                            $mail->addAddress($RSLT_DT["MBR_EMAIL"], $RSLT_DT["MBR_NAME"]);
                            
                            //Content
                            $mail->isHTML(true);
                            $mail->Subject = 'Konfirmasi APUPPT | '.date("Y-m-d H:i:s");

                            $mail->Body    = "
                                <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                                <html hola_ext_inject='disabled' xmlns='http://www.w3.org/1999/xhtml'>
                                    <head>
                                        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                                        <title>Konfirmasi APUPPT</title>
                                        <style type='text/css'>
                                            @media only screen and (min-device-width:600px) {
                                                .content {width:600px!important;}
                                            }
                                            @media only screen and (max-device-width:480px) {
                                                .text {font-size:12px!important;-webkit-text-size-adjust:100%!important;-moz-text-size-adjust:100%!important;-ms-text-size-adjust:100%!important;}
                                                .button {font-size:16px!important;-webkit-text-size-adjust:100%!important;-moz-text-size-adjust:100%!important;-ms-text-size-adjust:100%!important;}
                                            }
                                        </style>
                                    </head>
                                    <body style='background-color:#f9f9f9'>
                                        <div style='max-width: 1000px; margin: auto;padding: 20px'>
                                            <center><img src='https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$setting_pdf_logo."' style='height:50px'></center>
                                        </div>
                                        <div style='max-width: 600px; background-color:#ffffff;margin: auto;border: 1px solid #eaeaea;padding: 20px;border-radius: 5px;'>
                                            <div style='background-color:#f9f9f9;padding: 10px;border-radius: 5px;'>
                                                <strong>Info Registrasi</strong>
                                            </div>
                                            <div style='padding: 10px;'>
                                                <div>
                                                    Hasil konfirmasi APUPPT anda:<br>
                                                    Status: ".$STS[$iser]."
                                                </div>
                                                <hr>
                                                <p>
                                                    <small>
                                                        Anda menerima email ini karena Anda mendaftar di ".$setting_front_web_link." jika Anda memiliki<br>
                                                        pertanyaan, silahkan hubungi kami melalui email di ".$setting_email_sp.". Anda juga dapat menghubungi<br>
                                                        nomor ".$setting_number_phone." 
                                                    </small>
                                                    <br>
                                                    <small>
                                                        <a href='".$setting_insta_link."'><img src='".$setting_insta_linksrc."'></a>
                                                        <a href='".$setting_facebook_link."'><img src='".$setting_facebook_linksrc."'></a>
                                                        <a href='".$setting_linkedin_link."'><img src='".$setting_linkedin_linksrc."'></a>
                                                    </small>
                                                </p>
                                                <hr>
                                                <p>
                                                    <small>
                                                        <strong>Phone</strong> : <a href='tel:".$setting_office_number."'>".$setting_office_number."</a><br>
                                                        <strong>Support</strong> : <a href='mailto:".$setting_email_sp."'>".$setting_email_sp."</a><br>
                                                        <strong>Website</strong> : <a href='www.".$setting_front_web_link."'>".$setting_front_web_link."</a><br>
                                                        <strong>Address</strong> : ".$setting_central_office_address."<br>
                                                        <br>
                                                        Resmi dan diatur oleh Badan Pengawas Perdagangan Berjangka Komoditi. Nomor registrasi BAPPEBTI : 912/BAPPEBTI/SI/8/2006.
                                                    </small>
                                                </p>
                                                <hr>
                                                <p style='text-align: justify;'>
                                                    <small>
                                                        <strong>PEMBERITAHUAN RESIKO:</strong><br>
                                                        Semua produk finansial yang ditransaksikan dalam sistem margin mempunyai resiko tinggi terhadap dana Anda. Produk finansial ini tidak diperuntukkan bagi semua investor dan Anda bisa saja kehilangan dana lebih dari deposit awal Anda. Pastikan bahwa Anda benar-benar mengerti resikonya, dan mintalah nasihat independen jika diperlukan. Lihat Pemberitahuan Resiko lengkap kami di Ketentuan Bisnis.
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                    </body>
                                </html>
                            ";
                            // $mail->send();
                            mysqli_commit($db);
                            $word = 'Berhasil';
                        } catch (Exception | mysqli_sql_exception $e) {
                            mysqli_rollback($db);
                            $word = str_replace("'", "", $e->getMessage());
                            // $word = 'Gagal';
                        }
                        // $INS_QUER = mysqli_query($db,) or die("<script>alert('Err DeBe Ins2');location.href='home.php?page=apu_evcannas'</script>");
                        // $word = (mysqli_affected_rows($db) > 0) ? 'Berhasil' : 'Gagal';
                        die("<script>alert('$word Update Data');location.href='home.php?page=apu_evcannas'</script>");

                    }else{ die("<script>alert('Some Data Is not mathces!');location.href='home.php?page=apu_evcannas'</script>"); }
                }else{ die("<script>alert('Some Data Is missing or not mathces5');location.href='home.php?page=apu_evcannas'</script>"); }
            }
?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">APUPPT</a></li>
            <li class="breadcrumb-item"><a href="#">Evaluasi Nasabah</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Evaluasi Nasabah</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-6">
            <div class="card-header font-weight-bold">Informasi Nasabah</div>
            <div class="card card-primary card-outline">
                <div class="card-body box-profile" style="height: 502.73px;">
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Nama Nasabah</b> <a class="float-right"><?php echo $RSLT_DT["ACC_F_APP_PRIBADI_NAMA"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>NIK</b> <a class="float-right"><?php echo $RSLT_DT["ACC_F_APP_PRIBADI_ID"].' '.get_prov_nik($RSLT_DT["ACC_F_APP_PRIBADI_ID"], 'name') ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Alamat</b> <a class="float-right"><?php echo $RSLT_DT["ACC_F_APP_PRIBADI_ALAMAT"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Kode Pos</b> <a class="float-right"><?php echo $RSLT_DT["ACC_F_APP_PRIBADI_ZIP"].' '.get_prov($RSLT_DT["ACC_F_APP_PRIBADI_ZIP"], 'name') ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>No. Accout</b> <a class="float-right"><?php echo $RSLT_DT["ACC_LOGIN"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Tanggal Buka Account</b> <a class="float-right"><?php echo $RSLT_DT["ACC_DATETIME"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Produk Investasi</b> <a class="float-right"><?php echo $RSLT_DT["PRD"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Besaran Investasi Awal</b> <a class="float-right"><?php echo number_format($RSLT_DT["ACC_INITIALMARGIN"], 0) ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Pekerjaan/Profesi Nasabah</b> <a class="float-right"><?php echo $RSLT_DT["ACC_F_APP_KRJ_TYPE"] ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header font-weight-bold">Dokumen Nasabah</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-md-3 mb-3 text-center">
                                <div>
                                    <?php if($RSLT_DT['ACC_F_APP_FILE_IMG'] == ''|| $RSLT_DT['ACC_F_APP_FILE_IMG'] == '-' ){ ?>
                                        <img src="assets/img/unknown-file.png" width="100%">
                                    <?php } else { ?>
                                        <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_DT['ACC_F_APP_FILE_IMG']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_DT['ACC_F_APP_FILE_IMG']; ?>" width="75%"></a>
                                        <hr>
                                    <?php }; ?>
                                    <strong><u>Dokumen Pendukung</u></strong>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 text-center">
                                <div>
                                    <?php if($RSLT_DT['ACC_F_APP_FILE_FOTO'] == ''|| $RSLT_DT['ACC_F_APP_FILE_FOTO'] == '-' ){ ?>
                                        <img src="assets/img/unknown-file.png" width="100%">
                                    <?php } else { ?>
                                        <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_DT['ACC_F_APP_FILE_FOTO']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_DT['ACC_F_APP_FILE_FOTO']; ?>" width="75%"></a>
                                        <hr>
                                    <?php }; ?>
                                    <strong><u>Foto Terbaru</u></strong>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 text-center">
                                <div>
                                    <?php if($RSLT_DT['ACC_F_APP_FILE_ID'] == '' || $RSLT_DT['ACC_F_APP_FILE_ID'] == '-' ){ ?>
                                        <img src="assets/img/unknown-file.png" width="100%">
                                    <?php } else { ?>
                                        <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_DT['ACC_F_APP_FILE_ID']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_DT['ACC_F_APP_FILE_ID']; ?>" width="75%"></a>
                                        <hr>
                                    <?php }; ?>
                                    <strong><u>Foto Identitas</u></strong>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 text-center">
                                <div>
                                    <?php if($RSLT_DT['ACC_F_APP_FILE_IMG2'] == ''|| $RSLT_DT['ACC_F_APP_FILE_IMG2'] == '-' ){ ?>
                                        <img src="assets/img/unknown-file.png" width="100%">
                                    <?php } else { ?>
                                        <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_DT['ACC_F_APP_FILE_IMG2']; ?>">
                                        <img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_DT['ACC_F_APP_FILE_IMG2']; ?>" width="75%"></a>
                                        <hr>
                                    <?php }; ?>
                                    <strong><u>Dokumen Pendukung Lainya</u></strong>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3 text-center"><div>&nbsp;</div></div>
                            <div class="col-md-4 mb-3 text-center">
                                <div>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_DT['DPWD_PIC']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_DT['DPWD_PIC']; ?>" width="75%"></a>
                                    <hr>
                                    <strong><u>Deposit New Account</u></strong>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 text-center"><div>&nbsp;</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form method="post">
                    <div class="card-header font-weight-bold">
                        Faktor-faktor yang di periksa
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" width="100%">
                                <thead class="bg-primary">
                                    <tr>
                                        <th style="vertical-align: middle" class="text-center">No</th>
                                        <th style="vertical-align: middle" class="text-center">Faktor Risiko</th>
                                        <th style="vertical-align: middle" class="text-center">Keterangan Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $ARR_FKTR = array("Risiko DTTOT", "Risiko DPPSPM", "Daftar Watchlist Sipendar", "Risiko PEP");
                                        $no = 1;
                                        foreach($ARR_FKTR as $val){
                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo $no.'.'; ?></td>
                                            <td>
                                                <?php
                                                    // if($val == "Risiko DTTOT"){
                                                    //     echo '
                                                    //         <div class="row">
                                                    //             <div class="col-8">'.$val.'</div>
                                                    //             <div class="col-4"><a target="_blank" href="home.php?page=apu_dttottbl'.ddttotChk($RSLT_DT["ACC_F_APP_PRIBADI_NAMA"],$RSLT_DT["ACC_F_APP_PRIBADI_ID"], "link").'">Lihat Potensi('.ddttotChk($RSLT_DT["ACC_F_APP_PRIBADI_NAMA"],$RSLT_DT["ACC_F_APP_PRIBADI_ID"], "jumlah").')</a></div>
                                                    //         </div>
                                                    //     ';
                                                    // }else{ echo $val; }
                                                    switch ($val) {
                                                        case 'Risiko DTTOT':
                                                            $link = 'https://www.ppatk.go.id/dalam_negeri/read/1400/daftar-terduga-teroris-dan-organisasi-teroris-dttot.html';
                                                            echo '
                                                                <div class="row">
                                                                    <div class="col-8">'.$val.'</div>
                                                                    <div class="col-4">
                                                                        <a target="_blank" href="'.$link.'">
                                                                            Klik disini untuk ke halaman terkait
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            ';
                                                            break;

                                                        case 'Risiko DPPSPM':
                                                            $link = 'https://www.ppatk.go.id/dalam_negeri/read/1400/daftar-terduga-teroris-dan-organisasi-teroris-dttot.html';
                                                            echo '
                                                                <div class="row">
                                                                    <div class="col-8">'.$val.'</div>
                                                                    <div class="col-4">
                                                                        <a target="_blank" href="'.$link.'">
                                                                            Klik disini untuk ke halaman terkait
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            ';
                                                            break;

                                                        case 'Daftar Watchlist Sipendar':
                                                            $link = 'https://www.ppatk.go.id/pengumuman/read/1210/pengumuman-klinik-sipendar.html';
                                                            echo '
                                                                <div class="row">
                                                                    <div class="col-8">'.$val.'</div>
                                                                    <div class="col-4">
                                                                        <a target="_blank" href="'.$link.'">
                                                                            Klik disini untuk ke halaman terkait
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            ';
                                                            break;

                                                        case 'Risiko PEP':
                                                            $link = 'https://www.ppatk.go.id/';
                                                            echo '
                                                                <div class="row">
                                                                    <div class="col-12">'.$val.'</div>
                                                                </div>
                                                            ';
                                                            break;
                                                        
                                                        default:
                                                            $link = 'javascript:void(0)';
                                                            break;
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <select class="form-control text-center slk" name="param<?php echo $no; ?>" required>
                                                    <option value disabled selected>Plih Keterangan Data</option>
                                                    <option value="0">Tidak Termasuk Daftar</option>
                                                    <option value="1">Termasuk Dalam Daftar</option>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php
                                            $no++;
                                        }
                                    ?>
                                    <tr>
                                        <td>Catatan: </td>
                                        <td colspan="2">
                                            <input type="text" id="ct_input" class="form-control">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <input type="hidden" name="cttn" id="cttn">
                        <button type="submit" name="iser" value="0" class="btn btn-lg btn-danger">Ditolak</button>
                        <button type="submit" name="iser" value="1" class="btn btn-lg btn-warning">Dipertimbangkan</button>
                        <button type="submit" name="iser" value="2" class="btn btn-lg btn-success">Dilanjutkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('.slk').on('change', function(ev){
                if($('.slk')[0].value == 1 || $('.slk')[1].value == 1){
                    $('button[name="iser"][value="2"]').hide();
                    $('button[name="iser"][value="1"]').hide();
                    $('button[name="iser"][value="0"]').show();
                }else if($('.slk')[2].value == 1 || $('.slk')[3].value == 1){
                    $('button[name="iser"][value="0"]').hide();
                    $('button[name="iser"][value="2"]').hide();
                    $('button[name="iser"][value="1"]').show();
                }else if(($('.slk')[0].value == 0 && $('.slk')[0].value.length > 0) && ($('.slk')[1].value == 0 && $('.slk')[1].value.length > 0) && ($('.slk')[2].value == 0 && $('.slk')[2].value.length > 0) && ($('.slk')[3].value == 0 && $('.slk')[3].value.length > 0)){
                    $('button[name="iser"][value="2"]').show();
                    $('button[name="iser"][value="1"]').hide();
                    $('button[name="iser"][value="0"]').hide();
                }else{
                    $('button[name="iser"][value="2"]').show();
                    $('button[name="iser"][value="1"]').show();
                    $('button[name="iser"][value="0"]').show();
                }
                console.log($('.slk')[0].value == 0 && $('.slk')[0].value.length > 0);
                console.log($('.slk')[1].value == 0 && $('.slk')[1].value.length > 0);
                console.log($('.slk')[2].value == 0 && $('.slk')[2].value.length > 0);
                console.log($('.slk')[3].value == 0 && $('.slk')[3].value.length > 0);
                
                if($('.slk')[0].value == 1){
                    $('.slk').eq(1).removeAttr('required');
                    $('.slk').eq(2).removeAttr('required');
                    $('.slk').eq(3).removeAttr('required');
                }else if($('.slk')[1].value == 1){
                    $('.slk').eq(2).removeAttr('required');
                    $('.slk').eq(3).removeAttr('required');
                }else if($('.slk')[2].value == 1){
                    $('.slk').eq(3).removeAttr('required');
                }else{
                    $('.slk').eq(1).prop('required',true);
                    $('.slk').eq(2).prop('required',true);
                    $('.slk').eq(3).prop('required',true);
                }
            });
            
            $('#ct_input').on('keyup', (e) => {
                $('#cttn').val($(e.delegateTarget).val());
            });
        });
    </script>
<?php 
    }else{ 
        $SQL_APU = mysqli_query($db,'
            SELECT
                tb_apuppt_evcannas.EVCAN_VAL1,
                tb_apuppt_evcannas.EVCAN_VAL2,
                tb_apuppt_evcannas.EVCAN_VAL3,
                tb_apuppt_evcannas.EVCAN_VAL4,
                tb_apuppt_evcannas.EVCAN_CONF,
                tb_apuppt_evcannas.EVCAN_DATETIME,
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
                tb_member.MBR_EMAIL,
                tb_member.MBR_ID,
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
                ), "unknown-file.png") AS DPWD_PIC,
                tb_apuppt_evcannas.ID_EVCAN
            FROM tb_apuppt_evcannas
            JOIN tb_member ON(tb_member.MBR_ID = tb_apuppt_evcannas.EVCAN_MBR)
            LEFT JOIN tb_racc ON(tb_racc.ACC_MBR = tb_apuppt_evcannas.EVCAN_MBR AND tb_apuppt_evcannas.EVCAN_MBR = tb_member.MBR_ID AND tb_racc.ACC_DERE = 1)
            WHERE MD5(MD5(MD5(tb_apuppt_evcannas.ID_EVCAN))) = "'.$x.'"
            LIMIT 1
        ');
        if($SQL_APU && mysqli_num_rows($SQL_APU) > 0){
            $RSLT_APU = mysqli_fetch_assoc($SQL_APU);

            if(isset($_POST["iser"])){
                // if(isset($_POST["cttn"])){

                    if($user1["ADM_LEVEL"] != 1){
                        die("<script>alert('Anda tidak diperkenankan melakukan aksi ini');location.href = 'home.php?page=apu_evcannas'</script>");
                    }

                    $iser = form_input($_POST["iser"]);
                    $cttn = (isset($_POST["cttn"])) ? form_input($_POST["cttn"]) : '';
                    if($iser == 0 || $iser == 1 ||  $iser == 2){
                        $param1 = (isset($_POST["param1"])) ? form_input($_POST["param1"]) : 'NULL';
                        $param2 = (isset($_POST["param2"])) ? form_input($_POST["param2"]) : 'NULL';
                        $param3 = (isset($_POST["param3"])) ? form_input($_POST["param3"]) : 'NULL';
                        $param4 = (isset($_POST["param4"])) ? form_input($_POST["param4"]) : 'NULL';

                        $ARR_NC = [0, 1];
                        if(in_array($param1, $ARR_NC) && in_array($param2, $ARR_NC) && in_array($param3, $ARR_NC) && in_array($param4, $ARR_NC)){
                            $STS  = [ "ditolak",  "dipertimbangkan",  "dilanjutkan"];
                            $lstr = $STS[$iser].'('.$x.')';
                            try {
                                mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);
                                mysqli_begin_transaction($db);

                                mysqli_stmt_execute(mysqli_prepare($db, '
                                    UPDATE tb_apuppt_evcannas SET
                                        tb_apuppt_evcannas.EVCAN_MBR  = '.$RSLT_APU["MBR_ID"].',
                                        tb_apuppt_evcannas.EVCAN_VAL1 = '.$param1.',
                                        tb_apuppt_evcannas.EVCAN_VAL2 = '.$param2.',
                                        tb_apuppt_evcannas.EVCAN_VAL3 = '.$param3.',
                                        tb_apuppt_evcannas.EVCAN_VAL4 = '.$param4.',
                                        tb_apuppt_evcannas.EVCAN_CONF = '.$iser.'
                                    WHERE tb_apuppt_evcannas.ID_EVCAN = '.$RSLT_APU["ID_EVCAN"].'
                                '));

                                mysqli_stmt_execute(mysqli_prepare($db, '
                                    INSERT INTO tb_note SET
                                    tb_note.NOTE_MBR = '.$RSLT_APU["MBR_ID"].',
                                    tb_note.NOTE_TYPE = "APUPPT EVCANNAS2 : '.$lstr.'",
                                    tb_note.NOTE_NOTE = "'.$cttn.'",
                                    tb_note.NOTE_DATETIME = "'.date('Y-m-d H:i:s').'"
                                '));


                                if($iser == 0 && (!empty($_GET["xacc"]))){
                                    $xacc = form_input($_GET["xacc"]);
                                    mysqli_stmt_execute(mysqli_prepare($db, '
                                        UPDATE tb_racc SET
                                            tb_racc.ACC_MBR = CONCAT(tb_racc.ACC_MBR, 0)
                                        WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$xacc.'"
                                    '));
                                }

                                $mail = new PHPMailer(true);
                                $mail->isSMTP();
                                $mail->Host       = $setting_email_host_api;
                                $mail->SMTPAuth   = true;
                                $mail->Username   = $setting_email_support_name;
                                $mail->Password   = $setting_email_support_password;
                                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                                $mail->Port       = $setting_email_port_api;

                                //Recipients
                                $mail->setFrom($setting_email_support_name, $web_name_full);
                                $mail->addAddress($RSLT_APU["MBR_EMAIL"], $RSLT_APU["ACC_F_APP_PRIBADI_NAMA"]);
                                
                                //Content
                                $mail->isHTML(true);
                                $mail->Subject = 'Konfirmasi APUPPT | '.date("Y-m-d H:i:s");

                                $mail->Body    = "
                                    <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                                    <html hola_ext_inject='disabled' xmlns='http://www.w3.org/1999/xhtml'>
                                        <head>
                                            <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                                            <title>Konfirmasi APUPPT</title>
                                            <style type='text/css'>
                                                @media only screen and (min-device-width:600px) {
                                                    .content {width:600px!important;}
                                                }
                                                @media only screen and (max-device-width:480px) {
                                                    .text {font-size:12px!important;-webkit-text-size-adjust:100%!important;-moz-text-size-adjust:100%!important;-ms-text-size-adjust:100%!important;}
                                                    .button {font-size:16px!important;-webkit-text-size-adjust:100%!important;-moz-text-size-adjust:100%!important;-ms-text-size-adjust:100%!important;}
                                                }
                                            </style>
                                        </head>
                                        <body style='background-color:#f9f9f9'>
                                            <div style='max-width: 1000px; margin: auto;padding: 20px'>
                                                <center><img src='https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$setting_pdf_logo."' style='height:50px'></center>
                                            </div>
                                            <div style='max-width: 600px; background-color:#ffffff;margin: auto;border: 1px solid #eaeaea;padding: 20px;border-radius: 5px;'>
                                                <div style='background-color:#f9f9f9;padding: 10px;border-radius: 5px;'>
                                                    <strong>Info Registrasi</strong>
                                                </div>
                                                <div style='padding: 10px;'>
                                                    <div>
                                                        Hasil konfirmasi APUPPT anda:<br>
                                                        Status: ".$STS[$iser]."
                                                    </div>
                                                    <hr>
                                                    <p>
                                                        <small>
                                                            Anda menerima email ini karena Anda mendaftar di ".$setting_front_web_link." jika Anda memiliki<br>
                                                            pertanyaan, silahkan hubungi kami melalui email di ".$setting_email_sp.". Anda juga dapat menghubungi<br>
                                                            nomor ".$setting_number_phone." 
                                                        </small>
                                                        <br>
                                                        <small>
                                                            <a href='".$setting_insta_link."'><img src='".$setting_insta_linksrc."'></a>
                                                            <a href='".$setting_facebook_link."'><img src='".$setting_facebook_linksrc."'></a>
                                                            <a href='".$setting_linkedin_link."'><img src='".$setting_linkedin_linksrc."'></a>
                                                        </small>
                                                    </p>
                                                    <hr>
                                                    <p>
                                                        <small>
                                                            <strong>Phone</strong> : <a href='tel:".$setting_office_number."'>".$setting_office_number."</a><br>
                                                            <strong>Support</strong> : <a href='mailto:".$setting_email_sp."'>".$setting_email_sp."</a><br>
                                                            <strong>Website</strong> : <a href='www.".$setting_front_web_link."'>".$setting_front_web_link."</a><br>
                                                            <strong>Address</strong> : ".$setting_central_office_address."<br>
                                                            <br>
                                                            Resmi dan diatur oleh Badan Pengawas Perdagangan Berjangka Komoditi. Nomor registrasi BAPPEBTI : 912/BAPPEBTI/SI/8/2006.
                                                        </small>
                                                    </p>
                                                    <hr>
                                                    <p style='text-align: justify;'>
                                                        <small>
                                                            <strong>PEMBERITAHUAN RESIKO:</strong><br>
                                                            Semua produk finansial yang ditransaksikan dalam sistem margin mempunyai resiko tinggi terhadap dana Anda. Produk finansial ini tidak diperuntukkan bagi semua investor dan Anda bisa saja kehilangan dana lebih dari deposit awal Anda. Pastikan bahwa Anda benar-benar mengerti resikonya, dan mintalah nasihat independen jika diperlukan. Lihat Pemberitahuan Resiko lengkap kami di Ketentuan Bisnis.
                                                        </small>
                                                    </p>
                                                </div>
                                            </div>
                                        </body>
                                    </html>
                                ";
                                $mail->send();
                                mysqli_commit($db);
                                $word = 'Berhasil';
                            } catch (Exception | mysqli_sql_exception $e) {
                                mysqli_rollback($db);
                                // $word = str_replace("'", "", $e->getMessage());
                                $word = 'Gagal';
                            }
                            // var_dump($word);die;
                            // $INS_QUER = mysqli_query($db,) or die("<script>alert('Err DeBe Ins2');location.href='home.php?page=apu_evcannas'</script>");
                            // $word = (mysqli_affected_rows($db) > 0) ? 'Berhasil' : 'Gagal';
                            die("<script>alert('$word Update Data');location.href='home.php?page=apu_evcannas'</script>");

                        }else{ die("<script>alert('Some Data Is not mathces!');location.href='home.php?page=apu_evcannas'</script>"); }
                    }else{ die("<script>alert('Some Data Is missing or not mathces4');location.href='home.php?page=apu_evcannas'</script>"); }
                // }else{ die("<script>alert('Some Data Is missing or not mathces5');location.href='home.php?page=apu_evcannas'</script>"); }
            }
?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">APUPPT</a></li>
            <li class="breadcrumb-item"><a href="#">Evaluasi Nasabah</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Evaluasi Nasabah</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-6">
            <div class="card-header font-weight-bold">Informasi Nasabah</div>
            <div class="card card-primary card-outline">
                <div class="card-body box-profile" style="height: 502.73px;">
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Nama Nasabah</b> <a class="float-right"><?php echo $RSLT_APU["ACC_F_APP_PRIBADI_NAMA"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>NIK</b> <a class="float-right"><?php echo $RSLT_APU["ACC_F_APP_PRIBADI_ID"].' '.get_prov_nik($RSLT_APU["ACC_F_APP_PRIBADI_ID"], 'name') ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Alamat</b> <a class="float-right"><?php echo $RSLT_APU["ACC_F_APP_PRIBADI_ALAMAT"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Kode Pos</b> <a class="float-right"><?php echo $RSLT_APU["ACC_F_APP_PRIBADI_ZIP"].' '.get_prov($RSLT_APU["ACC_F_APP_PRIBADI_ZIP"], 'name') ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>No. Accout</b> <a class="float-right"><?php echo $RSLT_APU["ACC_LOGIN"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Tanggal Buka Account</b> <a class="float-right"><?php echo $RSLT_APU["ACC_DATETIME"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Produk Investasi</b> <a class="float-right"><?php echo $RSLT_APU["PRD"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Besaran Investasi Awal</b> <a class="float-right"><?php echo number_format($RSLT_APU["ACC_INITIALMARGIN"], 0) ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Pekerjaan/Profesi Nasabah</b> <a class="float-right"><?php echo $RSLT_APU["ACC_F_APP_KRJ_TYPE"] ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header font-weight-bold">Dokumen Nasabah</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-md-3 mb-3 text-center">
                                <div>
                                    <?php if($RSLT_APU['ACC_F_APP_FILE_IMG'] == ''|| $RSLT_APU['ACC_F_APP_FILE_IMG'] == '-' ){ ?>
                                        <img src="assets/img/unknown-file.png" width="100%">
                                    <?php } else { ?>
                                        <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_APU['ACC_F_APP_FILE_IMG']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_APU['ACC_F_APP_FILE_IMG']; ?>" width="75%"></a>
                                        <hr>
                                    <?php }; ?>
                                    <strong><u>Dokumen Pendukung</u></strong>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 text-center">
                                <div>
                                    <?php if($RSLT_APU['ACC_F_APP_FILE_FOTO'] == ''|| $RSLT_APU['ACC_F_APP_FILE_FOTO'] == '-' ){ ?>
                                        <img src="assets/img/unknown-file.png" width="100%">
                                    <?php } else { ?>
                                        <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_APU['ACC_F_APP_FILE_FOTO']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_APU['ACC_F_APP_FILE_FOTO']; ?>" width="75%"></a>
                                        <hr>
                                    <?php }; ?>
                                    <strong><u>Foto Terbaru</u></strong>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 text-center">
                                <div>
                                    <?php if($RSLT_APU['ACC_F_APP_FILE_ID'] == '' || $RSLT_APU['ACC_F_APP_FILE_ID'] == '-' ){ ?>
                                        <img src="assets/img/unknown-file.png" width="100%">
                                    <?php } else { ?>
                                        <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_APU['ACC_F_APP_FILE_ID']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_APU['ACC_F_APP_FILE_ID']; ?>" width="75%"></a>
                                        <hr>
                                    <?php }; ?>
                                    <strong><u>Foto Identitas</u></strong>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 text-center">
                                <div>
                                    <?php if($RSLT_APU['ACC_F_APP_FILE_IMG2'] == ''|| $RSLT_APU['ACC_F_APP_FILE_IMG2'] == '-' ){ ?>
                                        <img src="assets/img/unknown-file.png" width="100%">
                                    <?php } else { ?>
                                        <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_APU['ACC_F_APP_FILE_IMG2']; ?>">
                                        <img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_APU['ACC_F_APP_FILE_IMG2']; ?>" width="75%"></a>
                                        <hr>
                                    <?php }; ?>
                                    <strong><u>Dokumen Pendukung Lainya</u></strong>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3 text-center"><div>&nbsp;</div></div>
                            <div class="col-md-4 mb-3 text-center">
                                <div>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_APU['DPWD_PIC']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_APU['DPWD_PIC']; ?>" width="75%"></a>
                                    <hr>
                                    <strong><u>Deposit New Account</u></strong>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 text-center"><div>&nbsp;</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Faktor-faktor yang di periksa
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" width="100%">
                            <thead class="bg-primary">
                                <tr>
                                    <th style="vertical-align: middle" class="text-center">No</th>
                                    <th style="vertical-align: middle" class="text-center">Faktor Risiko</th>
                                    <th style="vertical-align: middle" class="text-center">Keterangan Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $ARR_FKTR = array("Risiko DTTOT", "Risiko DPPSPM", "Daftar Watchlist Sipendar", "Risiko PEP");
                                    $no = 1;
                                    foreach($ARR_FKTR as $val){
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no.'.'; ?></td>
                                        <td>
                                            <?php
                                                // if($val == "Risiko DTTOT"){
                                                //     echo '
                                                //         <div class="row">
                                                //             <div class="col-8">'.$val.'</div>
                                                //             <div class="col-4"><a target="_blank" href="home.php?page=apu_dttottbl'.ddttotChk($RSLT_APU["ACC_F_APP_PRIBADI_NAMA"],$RSLT_APU["ACC_F_APP_PRIBADI_ID"], "link").'">Lihat Potensi('.ddttotChk($RSLT_APU["ACC_F_APP_PRIBADI_NAMA"],$RSLT_APU["ACC_F_APP_PRIBADI_ID"], "jumlah").')</a></div>
                                                //         </div>
                                                //     ';
                                                // }else{ echo $val; }
                                                
                                                switch ($val) {
                                                    case 'Risiko DTTOT':
                                                        $link = 'https://www.ppatk.go.id/dalam_negeri/read/1400/daftar-terduga-teroris-dan-organisasi-teroris-dttot.html';
                                                        echo '
                                                            <div class="row">
                                                                <div class="col-8">'.$val.'</div>
                                                                <div class="col-4">
                                                                    <a target="_blank" href="'.$link.'">
                                                                        Klik disini untuk ke halaman terkait
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        ';
                                                        break;

                                                    case 'Risiko DPPSPM':
                                                        $link = 'https://www.ppatk.go.id/dalam_negeri/read/1400/daftar-terduga-teroris-dan-organisasi-teroris-dttot.html';
                                                        echo '
                                                            <div class="row">
                                                                <div class="col-8">'.$val.'</div>
                                                                <div class="col-4">
                                                                    <a target="_blank" href="'.$link.'">
                                                                        Klik disini untuk ke halaman terkait
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        ';
                                                        break;

                                                    case 'Daftar Watchlist Sipendar':
                                                        $link = 'https://www.ppatk.go.id/pengumuman/read/1210/pengumuman-klinik-sipendar.html';
                                                        echo '
                                                            <div class="row">
                                                                <div class="col-8">'.$val.'</div>
                                                                <div class="col-4">
                                                                    <a target="_blank" href="'.$link.'">
                                                                        Klik disini untuk ke halaman terkait
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        ';
                                                        break;

                                                    case 'Risiko PEP':
                                                        $link = 'https://www.ppatk.go.id/';
                                                        echo '
                                                            <div class="row">
                                                                <div class="col-12">'.$val.'</div>
                                                            </div>
                                                        ';
                                                        break;
                                                    
                                                    default:
                                                        $link = 'javascript:void(0)';
                                                        break;
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <select class="form-control text-center" disabled>
                                                <option value disabled selected>Plih Keterangan Data</option>
                                                <option <?php if($RSLT_APU["EVCAN_VAL$no"] == 0){ echo 'selected'; } ?> value="0">Tidak Termasuk Daftar</option>
                                                <option <?php if($RSLT_APU["EVCAN_VAL$no"] == 1){ echo 'selected'; } ?> value="1">Termasuk Dalam Daftar</option>
                                            </select>
                                        </td>
                                    </tr>
                                <?php
                                        $no++;
                                    }
                                ?>
                                <tr>
                                    <td>Catatan: </td>
                                    <td colspan="2">
                                        <input type="text" id="ct_input" class="form-control">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <form method="post">
                        <input type="hidden" name="cttn" id="cttn">
                        <?php
                            $tmbl = '';
                            if($user1["ADM_LEVEL"] == 1){
                                $ARR_BTN = [
                                    '<button type="submit" name="iser" value="0" class="btn btn-lg btn-danger">Ditolak</button>',
                                    '<button type="submit" name="iser" value="1" class="btn btn-lg btn-warning">Dipertimbangkan</button>',
                                    '<button type="submit" name="iser" value="2" class="btn btn-lg btn-success">Dilanjutkan</button>'
                                ];
                                foreach($ARR_BTN as $tmbls){
                                    $tmbl .= $tmbls.' ';
                                }
                            }else{
                                $ARR_BTN = [
                                    '<button type="button" class="btn btn-lg btn-danger">Ditolak</button>',
                                    '<button type="button" class="btn btn-lg btn-warning">Dipertimbangkan</button>',
                                    '<button type="button" class="btn btn-lg btn-success">Dilanjutkan</button>'
                                ];
                                $tmbl = $ARR_BTN[$RSLT_APU["EVCAN_CONF"]];
                            }
                            echo $tmbl;
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(async function(){

            let par = await Array.from(document.getElementsByClassName('par'));
            let nir = await Array.from(document.getElementsByClassName('nir'));
            let bbr = await Array.from(document.getElementsByClassName('bbr'));
            let ttr = await Array.from(document.getElementsByClassName('ttr'));
            let total = 0;
            await par.forEach(function(el,i){
                var val = el.value;
                nir[i].value = val;
                ttr[i].value = (val * bbr[i].value > 0) ? val * bbr[i].value : null;
                total = 0;
                for(let a=0;a<par.length; a++){
                    total += (+ttr[a].value);
                }
                document.getElementById('total').value = total;
                el.addEventListener('change', function(e){
                    disp(e,i,par,nir,ttr,bbr);
                });
                nir[i].value = el.value;
            });
            await $.ajax({
                url      : 'ajax/get_range.php',
                type     : 'GET',
                dataType : 'JSON',
                data     : {
                    val2 : document.getElementById('total').value
                }
            }).done(function(resp){
                document.getElementById('tingResk').value = (resp[0] === undefined) ? null : resp[0];
            });
        });
        function disp(e,i,par,nir,ttr,bbr){
            var val = e.currentTarget.value;
            nir[i].value = val;
            ttr[i].value = val * bbr[i].value;
            total = 0;
            for(let a=0;a<par.length; a++){
                total += (+ttr[a].value);
            }
            document.getElementById('total').value = total;
            $.ajax({
                url      : 'ajax/get_range.php',
                type     : 'GET',
                dataType : 'JSON',
                data     : {
                    val : document.getElementById('total').value
                }
            }).done(function(resp){
                document.getElementById('tingResk').value = resp[0];
            });
        }
        $('#ct_input').on('keyup', (e) => {
            $('#cttn').val($(e.delegateTarget).val());
        });
    </script>
<?php        
            }
        }
    }    
?>