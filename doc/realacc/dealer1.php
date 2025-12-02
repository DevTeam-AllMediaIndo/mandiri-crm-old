<?php
    require 'phpmailer/Exception.php';
    require 'phpmailer/PHPMailer.php';
    require 'phpmailer/SMTP.php';
    
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    if(isset($_GET['x'])){
        $x = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_GET['x']))));

        $SQL_QUERY = mysqli_query($db,"
            SELECT
                tb_racc.ID_ACC,
                (tb_acccond.ID_ACCCND) AS ID_ACCCND,
                tb_racc.ACC_TYPE,
                tb_racc.ACC_RATE,
                tb_racc.ACC_PRODUCT,
                tb_member.MBR_ID,
                tb_racc.ACC_F_APP_PRIBADI_NAMA AS MBR_NAME,
                tb_member.MBR_EMAIL,
                tb_racc.ACC_F_APP_PRIBADI_HP AS MBR_PHONE,
                tb_member.MBR_CITY,
                tb_member.MBR_IB_CODE,
                tb_ib.IB_NAME,
                tb_ib.IB_CODE,
                tb_ib.IB_CITY,
                tb_acccond.ACCCND_LOGIN,
                tb_acccond.ACCCND_AMOUNTMARGIN,
                tb_acccond.ACCCND_CASH_FOREX,
                tb_acccond.ACCCND_CASH_LOCO,
                tb_acccond.ACCCND_CASH_OIL,
                tb_acccond.ACCCND_CASH_SILVER,
                tb_acccond.ACCCND_CASH_JPK50,
                tb_acccond.ACCCND_CASH_JPK30,
                tb_acccond.ACCCND_CASH_HK50,
                tb_acccond.ACCCND_CASH_KRJ35,
                tb_note.NOTE_NOTE,
                tb_dpwd.DPWD_AMOUNT,
                (tb_dpwd.ID_DPWD) AS ID_DPWD,
                tb_dpwd.DPWD_VOUCHER
            FROM tb_racc
            JOIN tb_member
            JOIN tb_acccond
            JOIN tb_dpwd
            JOIN tb_ib
            JOIN tb_note
            ON(tb_racc.ACC_MBR = tb_member.MBR_ID
            AND tb_member.MBR_ID = tb_acccond.ACCCND_MBR
            AND tb_racc.ID_ACC = tb_acccond.ACCCND_ACC
            AND tb_dpwd.DPWD_MBR = tb_member.MBR_ID
            AND tb_dpwd.DPWD_RACC = tb_racc.ID_ACC
            AND tb_ib.IB_ID = tb_acccond.ACCCND_IB
            AND tb_note.NOTE_MBR = tb_member.MBR_ID
            AND tb_note.NOTE_RACC = tb_racc.ID_ACC
            AND tb_note.NOTE_ACCDN = tb_acccond.ID_ACCCND
            AND tb_note.NOTE_DPWD = tb_dpwd.ID_DPWD)
            WHERE MD5(MD5(tb_racc.ID_ACC)) = '".$x."'
            ORDER BY tb_dpwd.ID_DPWD DESC, tb_acccond.ID_ACCCND DESC
            LIMIT 1
        ") or die(mysqli_error($db));
    
        if(mysqli_num_rows($SQL_QUERY) > 0){
            $RESULT_QUERY  = mysqli_fetch_assoc($SQL_QUERY);
            if($RESULT_QUERY['ACC_RATE'] == 0){ $rate = 10000;}else{$rate = $RESULT_QUERY['ACC_RATE'];}
            if($RESULT_QUERY['ACC_RATE'] == 0){
                $curr_idr = 0;
                $curr = number_format($RESULT_QUERY['DPWD_AMOUNT'], 2);
                $curr_lg = 'USD';
                $curr_ag = '$';
            }else{
                $curr_idr = number_format($RESULT_QUERY['DPWD_AMOUNT'], 0);
                $curr = number_format($RESULT_QUERY['DPWD_AMOUNT'], 0);
                $curr_lg = 'IDR';
                $curr_ag = 'Rp';
            }
            $MBR_NAME = $RESULT_QUERY['MBR_NAME'];
            $MBR_EMAIL = $RESULT_QUERY['MBR_EMAIL'];
            $MBR_PHONE = $RESULT_QUERY['MBR_PHONE'];
            $MBR_CITY = $RESULT_QUERY['MBR_CITY'];
            $ACCCND_LOGIN = $RESULT_QUERY['ACCCND_LOGIN'];
            $ACCCND_AMOUNTMARGIN = $RESULT_QUERY['ACCCND_AMOUNTMARGIN'];
            $ID_ACCCND = $RESULT_QUERY['ID_ACCCND'];
            $ID_DPWD = $RESULT_QUERY['ID_DPWD'];
            $ID_ACC = $RESULT_QUERY['ID_ACC'];
            $MBR_ID = $RESULT_QUERY['MBR_ID'];
            $MBR_IB_CODE = $RESULT_QUERY['MBR_IB_CODE'];
            $ACC_PRODUCT = $RESULT_QUERY['ACC_PRODUCT'];
        } else {
            $MBR_NAME = '-';
            $MBR_EMAIL = '-';
            $MBR_PHONE = '-';
            $MBR_CITY = '-';
            $ACCCND_LOGIN = '0';
            $ACCCND_AMOUNTMARGIN = 0;
            $ID_ACCCND = 0;
            $ID_DPWD = 0;
            $ID_ACC = 0;
            $MBR_ID = 0;
            $MBR_IB_CODE = '';
            $ACC_PRODUCT = '';
        
        };
        $TGL = getdate();
        if(strtolower(date('F', strtotime($TGL["month"]))) == strtolower('January')){ $date_month = 'Januari';
        } else if(strtolower(date('F', strtotime($TGL["month"]))) == strtolower('February')){ $date_month = 'Februari';
        } else if(strtolower(date('F', strtotime($TGL["month"]))) == strtolower('March')){ $date_month = 'Maret';
        } else if(strtolower(date('F', strtotime($TGL["month"]))) == strtolower('April')){ $date_month = 'April';
        } else if(strtolower(date('F', strtotime($TGL["month"]))) == strtolower('May')){ $date_month = 'Mai';
        } else if(strtolower(date('F', strtotime($TGL["month"]))) == strtolower('June')){ $date_month = 'Juni';
        } else if(strtolower(date('F', strtotime($TGL["month"]))) == strtolower('July')){ $date_month = 'Juli';
        } else if(strtolower(date('F', strtotime($TGL["month"]))) == strtolower('August')){ $date_month = 'Agustus';
        } else if(strtolower(date('F', strtotime($TGL["month"]))) == strtolower('September')){ $date_month = 'September';
        } else if(strtolower(date('F', strtotime($TGL["month"]))) == strtolower('October')){ $date_month = 'Oktober';
        } else if(strtolower(date('F', strtotime($TGL["month"]))) == strtolower('November')){ $date_month = 'November';
        } else if(strtolower(date('F', strtotime($TGL["month"]))) == strtolower('December')){ $date_month = 'Desember';
        };
    
        if(isset($_POST['accept'])){
            if(isset($_POST['password'])){
                if(isset($_POST['investor'])){
                    if(isset($_POST['id_acccnd'])){
                        if(isset($_POST['id_acc'])){
                            //if(isset($_POST['ib'])){
                                if(isset($_POST['note_accept'])){
                                    if(isset($_POST['id_dpwd'])){
                                        $password = form_input($_POST['password']);
                                        $investor = form_input($_POST['investor']);
                                        $id_acccnd = form_input($_POST['id_acccnd']);
                                        $id_dpwd = form_input($_POST['id_dpwd']);
                                        $id_acc = form_input($_POST['id_acc']);
                                        // $ib = form_input($_POST['ib']);
                                        $note_accept = form_input($_POST['note_accept']);
                                        
                                        // $REQ_ARR = (strtolower($ACC_PRODUCT) == strtolower('Forex dan Gold')) ? ["ACCCND_CASH_OIL" => 'oil', "ACCCND_CASH_LOCO" => 'loco', "ACCCND_CASH_SILVER" => 'silver'] : ((strtolower($ACC_PRODUCT) == strtolower('Multilateral')) ? ["ACCCND_CASH_JPK50" => 'jpk50', "ACCCND_CASH_JPK30" => 'jpk30', "ACCCND_CASH_HK50" => 'hk50', "ACCCND_CASH_KRJ35" => 'krj35'] : []);
                                        $REQ_ARR = (strtolower($ACC_PRODUCT) == strtolower('1')) ? ["ACCCND_CASH_OIL" => 'oil', "ACCCND_CASH_LOCO" => 'loco', "ACCCND_CASH_SILVER" => 'silver'] : ((strtolower($ACC_PRODUCT) == strtolower('Multilateral')) ? ["ACCCND_CASH_JPK50" => 'jpk50', "ACCCND_CASH_JPK30" => 'jpk30', "ACCCND_CASH_HK50" => 'hk50', "ACCCND_CASH_KRJ35" => 'krj35'] : []);
                                        $FLTR_AR = array_filter($REQ_ARR, function($v, $k){
                                            return (!empty($_POST["$v"]));
                                        }, ARRAY_FILTER_USE_BOTH);

                                        $ARR2STR = [];
                                        if(count($FLTR_AR) == count($REQ_ARR)){
                                            foreach($REQ_ARR as $ky => $vl){
                                                $ARR2STR[] = "$ky = '".$_POST["$vl"]."'";
                                            }
                                        }else{ die("<script>alert('Field ".implode(',', array_diff($REQ_ARR, $FLTR_AR))." tidak boleh kosong!');location.href = 'home.php?page=member_realacc'</script>"); }
                                        $ext_qwr = implode(', ', $ARR2STR);
                                        mysqli_query($db,"
                                            UPDATE tb_racc SET
                                            tb_racc.ACC_LOGIN = '".$ACCCND_LOGIN."',
                                            tb_racc.ACC_PASS = '".$password."',
                                            tb_racc.ACC_INVESTOR = '".$investor."',
                                            tb_racc.ACC_INITIALMARGIN = ".$ACCCND_AMOUNTMARGIN.",
                                            tb_racc.ACC_WPCHECK = 6
                                            WHERE ((tb_racc.ID_ACC)) = ".$id_acc."
                                        ")or die(mysqli_error($db));
                
                                        mysqli_query($db,"
                                            UPDATE tb_acccond SET
                                            $ext_qwr,
                                            tb_acccond.ACCCND_DATEMARGIN = '".date('Y-m-d H:i:s')."',
                                            tb_acccond.ACCCND_STS = -1
                                            WHERE ((tb_acccond.ID_ACCCND)) = ".$id_acccnd."
                                        ")or die(mysqli_error($db));
                                        mysqli_query($db,'
                                            INSERT INTO tb_note SET
                                            tb_note.NOTE_MBR = (SELECT ACC_MBR FROM tb_racc WHERE ID_ACC = '.$id_acc.' LIMIT 1),
                                            tb_note.NOTE_RACC = '.$id_acc.',
                                            tb_note.NOTE_ACCDN = '.$id_acccnd.',
                                            tb_note.NOTE_DPWD = '.$id_dpwd.',
                                            tb_note.NOTE_TYPE = "DEALER ACCEPT",
                                            tb_note.NOTE_NOTE = "'.$note_accept.'",
                                            tb_note.NOTE_DATETIME = "'.date('Y-m-d H:i:s').'"
                                        ') or die(mysqli_error($db));
                                        insert_log($RESULT_QUERY['MBR_ID'], 'Accept Dealer');
                                        $mail = new PHPMailer(true);
                                        try {
                                            $mail->isSMTP();
                                            $mail->Host       = $setting_email_host_api;
                                            $mail->SMTPAuth   = true;
                                            $mail->Username   = $setting_email_support_name;
                                            $mail->Password   = $setting_email_support_password;
                                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                                            $mail->Port       = $setting_email_port_api;

                                            //Recipients
                                            $mail->setFrom($setting_email_support_name, $web_name);
                                            $mail->addAddress($MBR_EMAIL, $MBR_NAME);
                                            
                                            //Content
                                            $mail->isHTML(true);
                                            $mail->Subject = 'Konfirmasi Pembuatan Real Account(Pasca Deposit) | '.$web_name_full.' '.date('Y-m-d H:i:s');
                                            
                                            $mail->Body    = "
                                            <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                                            <html hola_ext_inject='disabled' xmlns='http://www.w3.org/1999/xhtml'>
                                                <head>
                                                    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                                                    <title>".$web_name_full."</title>
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
                                                <body style='background-color:#cacaca'>
                                                    <div style='max-width: 1000px; margin: auto;padding: 20px'></div>
                                                    <div style='max-width: 600px; background-color:#ffffff;margin: auto;border: 1px solid #eaeaea;padding: 20px;border-radius: 5px;'>
                                                        <center><img src='".$setting_email_logo_linksrc."' style='height:50px'></center>
                                                        <!--
                                                            <div style='padding: 10px;text-align: justify;'>
                                                                <div>Selamat Akun Real Anda Telah Selesai Dibuat</div>
                                                                <strong>Informasi Data Real Account</strong><br>
                                                                Hi <strong>".$MBR_NAME.", </strong><br>
                                                                
                                                                <ul>
                                                                    <li>Login : ".$ACCCND_LOGIN."</li>
                                                                    <li>Password Master : ".$password."</li>
                                                                    <li>Password Investor : ".$investor."</li>
                                                                    <li>IP Server : ".$meta_server."</li>
                                                                </ul>

                                                                Terima Kasih.


                                                                <br><br>
                                                                Dari Kami,<br>
                                                                ".$web_name_full." Team Support
                                                            </div>
                                                        -->
                                                        <div>
                                                            <div style='page-break-before:always; page-break-after:always'>
                                                                <div>
                                                                    <p>&#9733;&#9733;&#2947; &#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9488; <br /> No-Reply Email <br />
                                                                    </p>
                                                                    <p> https://www.mandirifx.co.id/ <br />...&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472; &#2947;&#9733;&#9733; <br />
                                                                    </p>
                                                                    <p>
                                                                        <b>".$web_name_full." ,Graha HSBC lt.9 Jl. Basuki Rahmat 58-60 Surabaya 60262 <br />Telephone: 031 &#8211;33601175 <br />Mail: info@mandirifx.co.id <br />
                                                                        </b>
                                                                    </p>
                                                                    <p>
                                                                        <br />
                                                                    </p>
                                                                    <p>
                                                                        <b>Yth. ".$MBR_NAME." <br />
                                                                        </b>
                                                                    </p>
                                                                    <p>
                                                                        <b>
                                                                            <br />
                                                                        </b>
                                                                    </p>
                                                                    <p>Selamat account Anda telah berhasil dibuat. <br />
                                                                    </p>
                                                                    <p>Di bawah ini adalah rincian pendaftaran Anda: <br />
                                                                    </p>
                                                                    <p style='font-size: xx-large;'>
                                                                        <b style=''>Lampiran : <a target='_blank' style='background: yellow;' href='https://control.techcrm.net/0e03qkuh/pdf/root/all.php?x=".$id_acc."'>[".strtoupper('Klik di sini untuk melihat semua dokumen')."]</a></b>
                                                                    </p>
                                                                    <p>
                                                                        Note: Berikut kami kirimkan attachment/lampiran yang sudah tertera nomor login dan password
                                                                    </p>
                                                                    <p> Meta5 rekening Trader: <br />
                                                                    </p>
                                                                    <p>
                                                                        <b>Login ID : ".$ACCCND_LOGIN." <br />
                                                                        </b>
                                                                    </p>
                                                                    <p>
                                                                        <b>Master Password : ".$password." <br />
                                                                        </b>
                                                                    </p>
                                                                    <p>
                                                                        <b>Investor Password : ".$investor." <br />
                                                                        </b>
                                                                    </p>
                                                                    <p>
                                                                        <br />
                                                                    </p>
                                                                    <p>Keterangan: <br />
                                                                    </p>
                                                                    <p>&#10003; Login ID adalah nomor rekening anda. <br />&#10003; Master password adalah password untuk trading, investor password untuk melihat/ <i>view only</i>. <br />
                                                                    </p>
                                                                    <p>
                                                                        <br />
                                                                    </p>
                                                                    <p>
                                                                        <b>Harap di perhatikan: <br />
                                                                        </b>
                                                                    </p>
                                                                    <p>
                                                                        <b>1. Simpan username dan password Anda di tempat yang aman dan ubah password Anda pada <br />saat pertama login </b>
                                                                        <b>dengan hal yang mudah di ingat demi keamanan dan kenyaman. <br />
                                                                        </b>
                                                                    </p>
                                                                    <p>
                                                                        <b>2. Tanggung jawab nasabah untuk menjaga kerahasiaan password dan tidak memberikan <br />kepada pihak lain. <br />
                                                                        </b>
                                                                    </p>
                                                                    <p>
                                                                        <b>3. $web_name_full  tidak bertanggung jawab atas penyalahgunaan Login dan password. <br />4. Jika mendapatkan masalah dengan Login dan password, silahkan mengirimkan email ke <br />
                                                                        </b>
                                                                    </p>
                                                                    <p>settlement@mandirifx.co.id <b> atau menghubungi tel: 031 3360 1175 <br />
                                                                        </b>
                                                                    </p>
                                                                    <p>
                                                                        <br />
                                                                    </p>
                                                                    <p>Terima kasih telah bergabung dan kami berharap Anda menikmati bertransaksi di platform kami. <br />
                                                                    </p>
                                                                    <p>
                                                                        <br />
                                                                    </p>
                                                                    <p>
                                                                        <b>&#8220;Thanks for joining and we hope you enjoy the experience&#8221; <br />
                                                                            <br />
                                                                        </b>
                                                                    </p>
                                                                    <p>
                                                                        <b>
                                                                            <br />
                                                                        </b>
                                                                    </p>
                                                                    <p>Salam, <br />
                                                                    </p>
                                                                    <p>-- sent via electronic mail &#8211; <br />
                                                                    </p>
                                                                    <p>
                                                                        <b>".$web_name_full." <br />
                                                                        </b>
                                                                    </p>
                                                                    <p></p>
                                                                </div>
                                                            </div>
                                                            <div style='page-break-before:always; page-break-after:always'>
                                                                <div>
                                                                    <p>&#9733;&#9733;&#2947; &#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9488; <br /> No-Reply Email <br />
                                                                    </p>
                                                                    <p> https://www.mandirifx.co.id/ <br />...&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472; &#2947;&#9733;&#9733; <br />
                                                                    </p>
                                                                    <p>
                                                                        <b>PT. ".$web_name_full." ,Graha HSBC lt.9 Jl. Basuki Rahmat 58-60 Surabaya 60262 <br />Telephone: 031 &#8211;33601175 <br />Mail: info@mandirifx.co.id <br />
                                                                        </b>
                                                                    </p>
                                                                    <p>
                                                                        <br />
                                                                    </p>
                                                                    <p></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <p>
                                                            <small>
                                                                Anda menerima email ini karena Anda mendaftar di ".$setting_front_web_link." jika Anda memiliki<br>
                                                                pertanyaan, silahkan hubungi kami melalui email di ".$setting_email_support_name.". Anda juga dapat menghubungi<br>
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
                                                    <div style='max-width: 1000px; margin: auto;padding: 20px'></div>
                                                </body>
                                            </html>
                                            ";
                                            $mail->send();
                                            //echo 'Message has been sent';
                                            
                                            // Message Telegram
                                            $mesg = 'Notif : Penetapan Akun Baru Diterima'.
                                            PHP_EOL.'Date : '.date("Y-m-d").
                                            PHP_EOL.'Time : '.date("H:i:s");
                                            // PHP_EOL.'======== Informasi Akun Baru =========='.
                                            // PHP_EOL.'Nama : '.$MBR_NAME.
                                            // PHP_EOL.'Email : '.$MBR_EMAIL.
                                            // PHP_EOL.'Voucher : '.$RESULT_QUERY['DPWD_VOUCHER'].
                                            // PHP_EOL.'Login : '.$RESULT_QUERY['ACCCND_LOGIN'].
                                            // PHP_EOL.'Margin : '.$curr_ag.' '.$curr.
                                            // PHP_EOL.'Rate : '.$RESULT_QUERY['ACC_RATE'].
                                            // PHP_EOL.'Status : Diterima'.
                                            // PHP_EOL.'Catatan : '.$note_accept.
                                            // PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                                            // Message Telegram
                                            $mesg_othr = 'Notif : Penetapan Akun Baru Diterima'.
                                            PHP_EOL.'Date : '.date("Y-m-d").
                                            PHP_EOL.'Time : '.date("H:i:s").
                                            PHP_EOL.'======================================='.
                                            PHP_EOL.'                        Informasi Akun Baru'.
                                            PHP_EOL.'======================================='.
                                            PHP_EOL.'Nama : '.$MBR_NAME.
                                            PHP_EOL.'Email : '.$MBR_EMAIL.
                                            PHP_EOL.'Voucher : '.$RESULT_QUERY['DPWD_VOUCHER'].
                                            PHP_EOL.'Login : '.$RESULT_QUERY['ACCCND_LOGIN'].
                                            PHP_EOL.'Margin : '.$curr_ag.' '.$curr.
                                            PHP_EOL.'Rate : '.$RESULT_QUERY['ACC_RATE'].
                                            PHP_EOL.'Status : Diterima'.
                                            PHP_EOL.'Catatan : '.$note_accept.
                                            PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                                            $request_params_dlr = [
                                                'chat_id' => $chat_id_dlr,
                                                'text' => $mesg
                                            ];
                                            http_request('https://api.telegram.org/bot'.$token_dlr.'/sendMessage?'.http_build_query($request_params_dlr));
                                            
                                            $request_params_all = [
                                                'chat_id' => $chat_id_all,
                                                'text' => $mesg
                                            ];
                                            http_request('https://api.telegram.org/bot'.$token_all.'/sendMessage?'.http_build_query($request_params_all));

                                            $request_params_othr = [
                                                'chat_id' => $chat_id_othr,
                                                'text' => $mesg_othr
                                            ];
                                            http_request('https://api.telegram.org/bot'.$token_othr.'/sendMessage?'.http_build_query($request_pasrams_othr));

                                            die("<script>alert('Login dan password account telah berhasil terkirim');location.href = 'home.php?page=member_realacc'</script>");
                                        } catch (Exception $e) {
                                            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                            die("<script>alert('Login dan password account tidak berhasil terkirim');location.href = 'home.php?page=member_realacc'</script>");
                                        }
                                        die("<script>alert('Success accept');location.href = 'home.php?page=member_realacc'</script>");
                                    }else{die("<script>alert('No id dpwd');location.href = 'home.php?page=member_realacc'</script>");};
                                }else{die("<script>alert('No id note');location.href = 'home.php?page=member_realacc'</script>");};
                            //}else{die("<script>alert('No id ib');location.href = 'home.php?page=member_realacc'</script>");};
                        }else{die("<script>alert('No id account');location.href = 'home.php?page=member_realacc'</script>");};
                    }else{die("<script>alert('No id account condition');location.href = 'home.php?page=member_realacc'</script>");};
                }else{die("<script>alert('No investor');location.href = 'home.php?page=member_realacc'</script>");};
            }else{die("<script>alert('No pass');location.href = 'home.php?page=member_realacc'</script>");};
        }
        if(isset($_POST["reject"])){
            if(isset($_POST["note_reject"])){
                if(isset($_POST["id_acc"])){
                    if(isset($_POST["id_acccnd"])){
                        if(isset($_POST["id_dpwd"])){
                            $id_acc = form_input($_POST["id_acc"]);
                            $id_acccnd = form_input($_POST["id_acccnd"]);
                            $id_dpwd = form_input($_POST["id_dpwd"]);
                            $note_reject = form_input($_POST["note_reject"]);
                            mysqli_query($db,"
                                UPDATE tb_racc SET
                                tb_racc.ACC_WPCHECK = 4
                                WHERE ((tb_racc.ID_ACC)) = ".$id_acc."
                            ")or die(mysqli_error($db));

                            mysqli_query($db,"
                                UPDATE tb_dpwd SET
                                tb_dpwd.DPWD_STSACC = 0
                                WHERE ((tb_dpwd.ID_DPWD)) = ".$id_dpwd."
                            ")or die(mysqli_error($db));

                            mysqli_query($db,'
                                INSERT INTO tb_note SET
                                tb_note.NOTE_MBR = (SELECT ACC_MBR FROM tb_racc WHERE ID_ACC = '.$id_acc.' LIMIT 1),
                                tb_note.NOTE_RACC = '.$id_acc.',
                                tb_note.NOTE_DPWD = '.$id_dpwd.',
                                tb_note.NOTE_ACCDN = '.$id_acccnd.',
                                tb_note.NOTE_TYPE = "DEALER REJECT",
                                tb_note.NOTE_NOTE = "'.$note_reject.'",
                                tb_note.NOTE_DATETIME = "'.date('Y-m-d H:i:s').'"
                            ') or die(mysqli_error($db));
                            
                            // Message Telegram
                            $mesg = 'Notif : Penetapan Akun Baru Ditolak'.
                            PHP_EOL.'Date : '.date("Y-m-d").
                            PHP_EOL.'Time : '.date("H:i:s");
                            // PHP_EOL.'======== Informasi Akun Baru =========='.
                            // PHP_EOL.'Nama : '.$MBR_NAME.
                            // PHP_EOL.'Email : '.$MBR_EMAIL.
                            // PHP_EOL.'Voucher : '.$RESULT_QUERY['DPWD_VOUCHER'].
                            // PHP_EOL.'Login : '.$RESULT_QUERY['ACCCND_LOGIN'].
                            // PHP_EOL.'Margin : '.$curr_ag.' '.$curr.
                            // PHP_EOL.'Rate : '.$RESULT_QUERY['ACC_RATE'].
                            // PHP_EOL.'Status : Ditolak'.
                            // PHP_EOL.'Alasan Ditolak : '.$note_reject.
                            // PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                            // Message Telegram
                            $mesg_othr = 'Notif : Penetapan Akun Baru Ditolak'.
                            PHP_EOL.'Date : '.date("Y-m-d").
                            PHP_EOL.'Time : '.date("H:i:s").
                            PHP_EOL.'======================================='.
                            PHP_EOL.'                        Informasi Akun Baru'.
                            PHP_EOL.'======================================='.
                            PHP_EOL.'Nama : '.$MBR_NAME.
                            PHP_EOL.'Email : '.$MBR_EMAIL.
                            PHP_EOL.'Voucher : '.$RESULT_QUERY['DPWD_VOUCHER'].
                            PHP_EOL.'Login : '.$RESULT_QUERY['ACCCND_LOGIN'].
                            PHP_EOL.'Margin : '.$curr_ag.' '.$curr.
                            PHP_EOL.'Rate : '.$RESULT_QUERY['ACC_RATE'].
                            PHP_EOL.'Status : Ditolak'.
                            PHP_EOL.'Alasan Ditolak : '.$note_reject.
                            PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                            $request_params_accounting = [
                                'chat_id' => $chat_id_accounnting,
                                'text' => $mesg
                            ];
                            http_request('https://api.telegram.org/bot'.$token_accounnting.'/sendMessage?'.http_build_query($request_params_accounting));

                            $request_params_dlr = [
                                'chat_id' => $chat_id_dlr,
                                'text' => $mesg
                            ];
                            http_request('https://api.telegram.org/bot'.$token_dlr.'/sendMessage?'.http_build_query($request_params_dlr));

                            $request_params_all = [
                                'chat_id' => $chat_id_all,
                                'text' => $mesg
                            ];
                            http_request('https://api.telegram.org/bot'.$token_all.'/sendMessage?'.http_build_query($request_params_all));

                            $request_params_othr = [
                                'chat_id' => $chat_id_othr,
                                'text' => $mesg_othr
                            ];
                            http_request('https://api.telegram.org/bot'.$token_othr.'/sendMessage?'.http_build_query($request_params_othr));

                            insert_log($RESULT_QUERY['MBR_ID'], 'Reject Dealer');
                            die("<script>alert('Berhasil Reject');location.href = 'home.php?page=member_realacc'</script>");
                        }
                    }
                }
            }
        }
    };
    
?>
<form method="post">
    <input type="hidden" name="id_acccnd" class="form-control" readonly value="<?php echo $ID_ACCCND ?>" required>
    <input type="hidden" name="id_acc" class="form-control" readonly value="<?php echo $ID_ACC ?>" required>
    <input type="hidden" name="id_dpwd" class="form-control" readonly value="<?php echo $ID_DPWD ?>" required>
    <div class="card">
        <div class="card-header font-weight-bold">
            ACCOUNT CONDITION
            <?php
                if($RESULT_QUERY['ACC_TYPE'] == '' ||$RESULT_QUERY['ACC_TYPE'] == '-') {
                    echo '-';
                }else{
                    if($RESULT_QUERY['ACC_TYPE'] == 1) {
                        echo 'SPA ';
                    } else if($RESULT_QUERY['ACC_TYPE'] == 2) {
                        echo 'KOMODITI';
                    } else {
                        echo 'Unknown';
                    }
                };
            ?>
            <div><span><i style="font-size: smaller; color: dimgray;">Comisson Charge</i></span></div>
        </div>
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-md-3" style="margin-block: auto;">Kondisi ini efektif bulan</div>
                <div class="col-md-9"><input type="text" class="form-control" readonly value="<?php echo date('m');?> (<?php echo $date_month ;?>)" required></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3" style="margin-block: auto;">No.Account</div>
                <div class="col-md-9"><input type="text" class="form-control" value="<?php echo $ACCCND_LOGIN ?>" required></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3" style="margin-block: auto;">Password</div>
                <div class="col-md-3"><input type="text" class="form-control text-center" name="password" value="-" required autocomplete="off"></div>
                <div class="col-md-3 text-right" style="margin-block: auto;">Investor</div>
                <div class="col-md-3"><input type="text" class="form-control text-center" name="investor" value="-" required autocomplete="off"></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3" style="margin-block: auto;">Nama Investor</div>
                <div class="col-md-9"><input type="text" class="form-control" value="<?php echo $MBR_NAME ?>" readonly required></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3" style="margin-block: auto;">E-Mail Investor</div>
                <div class="col-md-9"><input type="text" class="form-control" value="<?php echo $MBR_EMAIL ?>" readonly required></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3" style="margin-block: auto;">No.Telp</div>
                <div class="col-md-9"><input type="text" class="form-control" value="<?php echo $MBR_PHONE ?>" readonly required></div>
                <!-- <div class="col-md-1 text-right" style="margin-block: auto;">Kota</div>
                <div class="col-md-4"><input type="text" class="form-control text-center" name="" value="<?php if($MBR_CITY == ''){ echo '-';}else { echo $MBR_CITY; }; ?>" required></div> -->
            </div>
            <div class="row mt-2">
                <div class="col-md-3" style="margin-block: auto;">Tanggal Deposit</div>
                <div class="col-md-2"><input type="number" class="form-control" value="<?php echo date('d');?>" name="" required></div>
                <div class="col-md-1 text-right" style="margin-block: auto;">Bulan</div>
                <div class="col-md-4">
                    <select name="" id="" class="form-control" required>
                        <option value="01" <?php if(date('m') == '01'){echo 'selected';}?> >1 (Januari)</option>
                        <option value="02" <?php if(date('m') == '02'){echo 'selected';}?> >2 (Februari)</option>
                        <option value="03" <?php if(date('m') == '03'){echo 'selected';}?> >3 (Maret)</option>
                        <option value="04" <?php if(date('m') == '04'){echo 'selected';}?> >4 (April)</option>
                        <option value="05" <?php if(date('m') == '05'){echo 'selected';}?> >5 (Mei)</option>
                        <option value="06" <?php if(date('m') == '06'){echo 'selected';}?> >6 (Juni)</option>
                        <option value="07" <?php if(date('m') == '07'){echo 'selected';}?> >7 (Juli)</option>
                        <option value="08" <?php if(date('m') == '08'){echo 'selected';}?> >8 (Agustus)</option>
                        <option value="09" <?php if(date('m') == '09'){echo 'selected';}?> >9 (September)</option>
                        <option value="10" <?php if(date('m') == '10'){echo 'selected';}?> >10 (Oktober)</option>
                        <option value="11" <?php if(date('m') == '11'){echo 'selected';}?> >11 (November)</option>
                        <option value="12" <?php if(date('m') == '12'){echo 'selected';}?> >12 (Desember)</option>
                    </select>
                </div>
                <div class="col-md-1 text-right" style="margin-block: auto;">Tahun</div>
                <div class="col-md-1"><input type="number" class="form-control" value="<?php echo date('Y');?>" name="" required></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3" style="margin-block: auto;">Nilai Margin</div>
                <div class="col-md-1" style="margin-block: auto;"><?php if($RESULT_QUERY['ACC_RATE'] == '0'){ echo 'USD'; }else{ echo 'IDR'; }?></div>
                <div class="col-md-2" style="margin-block: auto;">
                    <input type="text" class="form-control" name="initial_margin" id="rupiah" value="<?php if($RESULT_QUERY['ACC_RATE'] == '0'){ echo '$'.number_format($ACCCND_AMOUNTMARGIN, 2, ',', '.'); }else{ echo 'Rp'.number_format($ACCCND_AMOUNTMARGIN, 0, ',', '.'); } ?>" readonly required>
                </div>
                <div class="col-md-1 text-right" style="margin-block: auto;">Fixed Rate</div>
                <div class="col-md-2" style="margin-block: auto;">
                    <select name="" id="" class="form-control text-center">
                        <option value="10.000" <?php if($RESULT_QUERY['ACC_RATE'] == '10000'){echo 'selected';}?>>10.000</option>
                        <option value="12.000" <?php if($RESULT_QUERY['ACC_RATE'] == '12000'){echo 'selected';}?>>12.000</option>
                        <option value="14.000" <?php if($RESULT_QUERY['ACC_RATE'] == '14000'){echo 'selected';}?>>14.000</option>
                        <option value="Custom" <?php if($RESULT_QUERY['ACC_RATE'] == '0'){echo 'selected';}?>>Custom</option>
                    </select>
                </div>
                <div class="col-md-1 text-right" style="margin-block: auto;">Voucher</div>
                <div class="col-md-2"><input type="text" class="form-control" value="<?php echo $RESULT_QUERY['DPWD_VOUCHER'];?>" required readonly></div>
            </div>
            <?php if($RESULT_QUERY['ACC_PRODUCT'] == '1'){?>
                <div class="row mt-2">
                    <div class="col-md-3" style="margin-block: auto;">Commission Charge</div>
                    <div class="col-md-1" style="margin-block: auto;">Oil</div>
                    <div class="col-md-2" style="margin-block: auto;">
                        <input type="number" class="form-control" name="oil" value="<?php echo $RESULT_QUERY['ACCCND_CASH_OIL']?>" required>
                    </div>
                    <div class="col-md-1 text-right" style="margin-block: auto;">Locco</div>
                    <div class="col-md-2" style="margin-block: auto;">
                        <input type="number" class="form-control" name="loco" value="<?php echo $RESULT_QUERY['ACCCND_CASH_LOCO']?>" required>
                    </div>
                    <div class="col-md-1 text-right" style="margin-block: auto;">Silver</div>
                    <div class="col-md-2" style="margin-block: auto;">
                        <input type="number" class="form-control" name="silver" value="<?php echo $RESULT_QUERY['ACCCND_CASH_SILVER']?>" required>
                    </div>
                </div>
            <?php }else if($RESULT_QUERY['ACC_PRODUCT'] == 'Multilateral'){?>
                <div class="row mt-2">
                    <div class="col-md-3" style="margin-block: auto;">Commission Charge</div>
                    <div class="col-md-1" style="margin-block: auto;">JPK50</div>
                    <div class="col-md-3" style="margin-block: auto;">
                        <input type="number" class="form-control" name="jpk50" value="<?php echo $RESULT_QUERY['ACCCND_CASH_JPK50']?>" required>
                    </div>
                    <div class="col-md-1 text-right" style="margin-block: auto;">JPK30</div>
                    <div class="col-md-3" style="margin-block: auto;">
                        <input type="number" class="form-control" name="jpk30" value="<?php echo $RESULT_QUERY['ACCCND_CASH_JPK30']?>" required>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-3" style="margin-block: auto;">&nbsp;</div>
                    <div class="col-md-1" style="margin-block: auto;">HKK50/HKJ50</div>
                    <div class="col-md-3" style="margin-block: auto;">
                        <input type="number" class="form-control" name="hk50" value="<?php echo $RESULT_QUERY['ACCCND_CASH_HK50']?>" required>
                    </div>
                    <div class="col-md-1 text-right" style="margin-block: auto;">KRJ35</div>
                    <div class="col-md-3" style="margin-block: auto;">
                        <input type="number" class="form-control" name="krj35" value="<?php echo $RESULT_QUERY['ACCCND_CASH_KRJ35']?>" required>
                    </div>
                </div>
            <?php }?>
            <div class="row mt-2">
                <div class="col-md-3" style="margin-block: auto;">IB Code</div>
                <div class="col-md-9" style="margin-block: auto;">
                    <input type="text" class="form-control" value="<?php echo $RESULT_QUERY['IB_NAME'].'  '.$RESULT_QUERY['IB_CODE'].' / '.$RESULT_QUERY['IB_CITY']?>" readonly required>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3" style="margin-block: auto;">Note</div>
                <div class="col-md-9" style="margin-block: auto;">
                    <input type="text" class="form-control" value="<?php echo $RESULT_QUERY['NOTE_NOTE']?>" readonly required>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="button" class="btn btn-success" data-target="#modal_accept" data-toggle="modal">Accept</button>
            <button type="button" class="btn btn-danger" data-target="#modal_reject" data-toggle="modal">Reject</button>
            <div class="modal fade" id="modal_accept" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Form untuk catatan accept nasabah</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Note</label>
                                <input type="text" value=" " class="form-control text-center" name="note_accept" required autocomplete="off">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="accept" class="btn btn-success">Submit Accept</button>
                        </div>
                    </div>
                </div>
            </div>  
            <div class="modal fade" id="modal_reject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Form untuk catatan reject nasabah</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Note</label>
                                <input type="text" value=" " class="form-control text-center" name="note_reject" required autocomplete="off">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="reject" class="btn btn-danger">Submit Reject</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
		
		var rupiah = document.getElementById('rupiah');
		rupiah.addEventListener('keyup', function(e){
			// tambahkan 'Rp.' pada saat form di ketik
			// gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
			rupiah.value = formatRupiah(this.value, 'Rp. ');
		});
 
		/* Fungsi formatRupiah */
		function formatRupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
		}
	</script>