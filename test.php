<?php 
    
    require 'phpmailer/Exception.php';
    require 'phpmailer/PHPMailer.php';
    require 'phpmailer/SMTP.php';
    
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    require_once('setting.php');
    $password = 'Test';
    $investor = 'Test';
    $meta_server = 'Test';
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
        WHERE MD5(MD5(tb_racc.ID_ACC)) = '8bea34e2aa4c7abe0e3de2d37c3d6728'
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
    try {

        $mail = new PHPMailer(true);
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
        $mail->Subject = 'Informasi Akun Real '.$web_name_full.' '.date('Y-m-d H:i:s');
        
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
        echo 'Please check email '.$MBR_EMAIL;
    } catch (Exception $e) {
        echo 'Exception Occured';

    }

