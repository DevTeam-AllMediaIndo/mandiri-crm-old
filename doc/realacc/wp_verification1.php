<?php
    require __DIR__ . '/../../phpmailer/Exception.php';
    require __DIR__ . '/../../phpmailer/PHPMailer.php';
    require __DIR__ . '/../../phpmailer/SMTP.php';

    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    use Aws\S3\S3Client;
    
    if($user1["ADM_LEVEL"] == 3 || $user1["ADM_LEVEL"] == 1){
        if(isset($_GET['x'])){
            $id = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_GET['x']))));
            
            // if(isset($_GET['action'])){
            //     $action = mysqli_real_escape_string($db, stripslashes(strip_tags($_GET["action"])));
        
                // if($action == 'detail'){
                //     $id = mysqli_real_escape_string($db, stripslashes(strip_tags($_GET["x"])));
                    
                    $SQL_QUERY = mysqli_query($db, '
                        SELECT 
                            tb_member.*,
                            tb_racc.*,
                            IFNULL((
                                SELECT CONCAT(tb_ib.IB_CODE, " / ", tb_ib.IB_NAME)
                                FROM tb_ib
                                WHERE tb_ib.IB_CODE = tb_member.MBR_IB_CODE
                                LIMIT 1
                            ),"-") AS IB_CODE,
                            IFNULL((
                                SELECT tb_ib.IB_CITY
                                FROM tb_ib
                                WHERE tb_ib.IB_CODE = tb_member.MBR_IB_CODE
                                LIMIT 1
                            ),"-") AS IB_CITY
                        FROM tb_member
                        JOIN tb_racc
                        ON (tb_member.MBR_ID = tb_racc.ACC_MBR)
                        WHERE MD5(MD5(ID_ACC)) = "'.$id.'"
                        LIMIT 1
                    ');
                    if(mysqli_num_rows($SQL_QUERY) > 0){
                        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
                    };
                    
                // };
            // };

        };
        if(isset($_POST['accept'])){
            if(isset($_POST['note_accept'])){
                // if($RESULT_QUERY['MBR_EMAIL'] != "tumini2@yopmail.com") {
                //     die("maintance");
                // }

                $attachment = file_get_contents("https://control.techcrm.net/0e03qkuh/pdf/root/13.bukti-konfirmasi-penerimaan-nasabah.php?x={$id}");
                if($attachment === FALSE) {
                    die("<script>alert('[Failed] Invalid Attachment'); location.href = 'home.php?page=member_realacc'</script>");
                }

                $uniqid = 'Bukti-konfirmasi-(pra)deposit-'.(date("Ymd")."_".uniqid());
                $filepath = __DIR__."/../../assets/attachment/{$uniqid}.pdf";
                $savePdf = file_put_contents($filepath, $attachment);
                if($savePdf === FALSE) {
                    die("<script>alert('[Failed] pembuatan dokumen file gagal'); location.href = 'home.php?page=member_realacc'</script>");
                }

                $note_accept = form_input($_POST['note_accept']);
                $EXEC_SQL = mysqli_query($db,"
                    UPDATE tb_racc SET
                        tb_racc.ACC_WPCHECK      = 1,
                        tb_racc.ACC_WPCHECK_DATE = '".date("Y-m-d H:i:s")."'
                    WHERE MD5(MD5(tb_racc.ID_ACC)) = '".$id."'
                ")or die(mysqli_error($db));
                $INSERT_NOTE = mysqli_query($db,'
                    INSERT INTO tb_note SET
                    tb_note.NOTE_MBR = '.$RESULT_QUERY["MBR_ID"].',
                    tb_note.NOTE_RACC = '.$RESULT_QUERY["ID_ACC"].',
                    tb_note.NOTE_TYPE = "WP VER ACCEPT",
                    tb_note.NOTE_NOTE = "'.$note_accept.'",
                    tb_note.NOTE_DATETIME = "'.date('Y-m-d H:i:s').'"
                ') or die(mysqli_error($db));

                // Message Telegram
                $mesg = 'Notif : Regol Baru Diterima'.
                PHP_EOL.'Date : '.date("Y-m-d").
                PHP_EOL.'Time : '.date("H:i:s");
                // PHP_EOL.'======== Informasi Regol =========='.
                // PHP_EOL.'Nama : '.$RESULT_QUERY['MBR_NAME'].
                // PHP_EOL.'Email : '.$RESULT_QUERY['MBR_EMAIL'].
                // PHP_EOL.'Status : Diterima'.
                // PHP_EOL.'Catatan : '.$note_accept.
                // PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                // Message Telegram
                $mesg_othr = 'Notif : Regol Baru Diterima'.
                PHP_EOL.'Date : '.date("Y-m-d").
                PHP_EOL.'Time : '.date("H:i:s").
                PHP_EOL.'==================================='.
                PHP_EOL.'                         Informasi Regol'.
                PHP_EOL.'==================================='.
                PHP_EOL.'Nama : '.$RESULT_QUERY['MBR_NAME'].
                PHP_EOL.'Email : '.$RESULT_QUERY['MBR_EMAIL'].
                PHP_EOL.'Status : Diterima'.
                PHP_EOL.'Catatan : '.$note_accept.
                PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                $request_params = [
                    'chat_id' => $chat_id,
                    'text' => $mesg
                ];
                http_request('https://api.telegram.org/bot'.$token1.'/sendMessage?'.http_build_query($request_params));

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

                $mmsg = '';
                $mail = new PHPMailer(true);
                try {
                    //Server settings
                    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    $mail->isSMTP();
                    $mail->Host       = $setting_email_host_api;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $setting_email_support_name;
                    $mail->Password   = $setting_email_support_password;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = $setting_email_port_api;

                    //Recipients
                    $mail->setFrom($setting_email_support_name, $web_name_full);
                    $mail->addAddress($RESULT_QUERY['MBR_EMAIL'], $RESULT_QUERY['MBR_NAME']);
                    $mail->addAttachment($filepath);
                    // $mail->addStringAttachment($nattchstr, 'All_documents.pdf', 'base64', 'application/pdf');
                    
                    //Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Konfirmasi Pembuatan Real Account(Pra Deposit) | '.date("Y-m-d H:i:s");

                    $mail->Body    = "
                        <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html hola_ext_inject='disabled' xmlns='http://www.w3.org/1999/xhtml'>
                            <head>
                                <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                                <title>Konfirmasi Pembuatan Real Account(Pra Deposit)</title>
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
                                    <!--
                                        <div style='background-color:#f9f9f9;padding: 10px;border-radius: 5px;'>
                                            <strong>Registration Info</strong>
                                        </div>
                                    -->
                                    <div style='padding: 10px;'>
                                        <!--
                                            Kepada Yth ".$RESULT_QUERY['MBR_NAME'].", <br>
                                            Pembuatan real akun anda telah diterima oleh WPB, mohon melanjutkan ke tahap berikutnya.<br>
                                            <div>
                                                Status : Diterima
                                            </div>
                                            <br><br>
                                            <div>
                                                Terima Kasih<br><br>
                                                Dari Kami,<br>
                                                ".$web_name_full." Team Support
                                            </div>
                                        -->
                                        <div>
                                            <div style='page-break-before:always; page-break-after:always'>
                                                <div>
                                                    <p>&#9733;&#9733;&#2947; &#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9488; <br /> EMAIL KONFIRMASI REAL ACCOUNT(Pra Deposit)<br /> www.mandirifx.co.id &#61480; 031 3360 1175 Mail: wpb@mandirifx.co.id <br />&#9492;...&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472; &#2947;&#9733;&#9733; <br />
                                                    </p>
                                                    <p>
                                                        DISCLAMER: DENGAN TELAH MENERIMA EMAIL INI MAKA KAMI MENGANGGAP ANDA TELAH MEMBACA, MENGERTI DAN MENYATAKAN SETUJU DENGAN SEMUA <br />
                                                        KETENTUAN TENTANG PEMBUKAAN REKENING SECARA ELEKTRONIK DALAM BIDANG PERDAGANGAN BERJANGKA KOMODITI. <br />

                                                    </p>
                                                    <p>
                                                        <br />
                                                        <br />
                                                    </p>
                                                    <p style='font-size: xx-large;'>
                                                        <b style=''>Lampiran : <a target='_blank' style='background: yellow;' href='https://control.techcrm.net/0e03qkuh/pdf/root/all.php?x=".$id."'>[".strtoupper('Klik di sini untuk melihat semua dokumen')."]</a></b>
                                                    </p>
                                                    <p>
                                                        Note : Bukti Konfirmasi pada attachment/lampiran kami kirimkan tanpa nomor Login dan Password untuk transaksi di MT5. Kami akan kirimkan setelah Anda melakukan Deposit.
                                                    </p>
                                                    <p>
                                                        <b>Yth. Bapak/Ibu ".$RESULT_QUERY['MBR_NAME'].". <br />
                                                        </b>Calon Nasabah Mandiri Investindo Futures, <br />
                                                        <br />
                                                    </p>
                                                    <p>Dengan ini saya <br />
                                                        <b>Nama : Agus Purnawan <br /> Jabatan : Wakil Pialang Berjangka PT. Mandiri Investindo Futures <br /> No. WP : 0010/UPTP/Sl/ 12/2022 </b>
                                                        <br />
                                                    </p>
                                                    <p>
                                                        <b>Menyatakan</b> bahwa hasil dari verifikasi kami: &#8220;Anda telah berhasil melakukan pendaftaran menjadi <br />nasabah secara elektronik di bidang perdagangan berjangka komoditi&#8221;, yaitu telah: <br />
                                                    </p>
                                                    <p>1. Mengisi kelengkapan data aplikasi pembukaan online (Formulir Nomor 107.PBk.03) <br />2. Setuju, mengerti dan memahami tentang Profil PT. Mandiri Investindo Futures, Dokumen <br />
                                                    </p>
                                                    <p>Pemberitahuan adanya resiko, Dokumen Perjanjian Pemberian Amanat secara Elektronik On Line, <br />Dokumen Peraturan Perdagangan / Trading Rules, Pernyataan bertanggung jawab atas kode akses <br />trasaksi nasabah / <i>personal access password</i>. <br />
                                                    </p>
                                                    <p>3. Mengisi dan Menyatakan telah melakukan simulasi transaksi perdagangan berjangka), Dokumen <br />telah berpengalaman dalam melaksanakan Transaksi pedagangan dan Siap bertanggung Jawab <br />atas kode Akses Nasabah serta menyertakan Dokumen bahwa Dana deposit merupakan dana <br />sendir dan bukan berasal dari tindak kejatahatan maupun hasil dari money laudry. <br />
                                                    </p>
                                                    <p>4. Memberikan Self Declaration Video tanpa paksaan dari pihak manapun dan atas kesadarannya <br />sendiri. <br />
                                                    </p>
                                                    <p>
                                                        <b>
                                                            <i>Selanjutnya, <br />
                                                            </i>
                                                        </b>
                                                        <i></i>1. Melakukan transfer dana ke rekening segregate account. <br />
                                                    </p>
                                                    <table style='border-collapse:collapse;' cellspacing='0'>
                                                        <tr style=''>
                                                            <td style='border: 1px solid black;'>
                                                                <p class='s4' style='text-align: center;'>BANK</p>
                                                            </td>
                                                            <td style='border: 1px solid black;'>
                                                                <p class='s4' style='text-align: left;'>NOMOR REKENING</p>
                                                            </td>
                                                            <td style='border: 1px solid black;'>
                                                                <p class='s4' style='text-align: center;'>CABANG</p>
                                                            </td>
                                                        </tr>
                                                        <tr style=''>
                                                            <td style='border: 1px solid black;'>
                                                                <p class='s4' style='text-align: center;'>BCA</p>
                                                            </td>
                                                            <td style='border: 1px solid black;'>
                                                                <p class='s5' style='text-align: left;'>0105220222 (IDR)</p>
                                                            </td>
                                                            <td style='border: 1px solid black;'>
                                                                <p class='s5' style='text-align: center;'>KCU VETERAN</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <p>
                                                        <br />2. Me-reply email ini dengan bukti transfer/deposit. <br />
                                                    </p>
                                                    <p>
                                                        <br />Demikian kami sampaikan. selamat bertransaksi di <b>PT. Mandiri Investindo Futures</b>
                                                        <br />
                                                        <b>Hormat kami, <br />
                                                        </b>-- sent via electronic mail &#8211; <br />
                                                        <b>Terry Indradi Oktriawan,SE <br />
                                                        </b>
                                                            Direktur Utama PT. Mandiri Investindo Futures <br />
                                                            <!--Lampiran : <br />Dokumen Profil Perusahaan Dokumen Trading Rules <br />Dokumen Pernyataan Telah melakukan demo akun Dokumen Tanggung jawab terhadap Hak Akses <br />Dokumen Pernyataan Telah Berpengalaman Transaksi Dokumen Pernyataan Dana yang digunakan <br />Disclosure Statement Bukti Konfirmasi <br />Aplikasi Pembukaan akun <br />Disclosure Statement <br />Dokumen Pemberitahuan Adanya Resiko <br />Disclosure Statement <br />Dokumen Pemberian Amanat <b>-->
                                                            <i></i>
                                                        </b>
                                                    </p>
                                                </div>
                                            </div>
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
                        <script>
                            document.getElementById('dwnldall').addEventListener('click', (e) => {
                                Array.from(document.querySelectorAll('a[download]')).forEach((el) => {
                                    el.click();
                                });
                            });
                        </script>
                    ";
                    $mail->send();
                    $mmsg = 'Sended';
                } catch (Exception $e) {
                    // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    $mmsg = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    die ("<script>location.href = 'home.php?page=member_realacc';</script>");
                }

                insert_log($RESULT_QUERY['MBR_ID'], 'Accept WP Confirmation ');
                die("<script>alert('Success Accept. Mail: $mmsg');location.href = 'home.php?page=member_realacc'</script>");
            };
        }
        if(isset($_POST['reject'])){
            if(isset($_POST['note_reject'])){
                $note_reject = form_input($_POST['note_reject']);
                $EXEC_SQL = mysqli_query($db,"
                    UPDATE tb_racc SET
                        tb_racc.ACC_MBR = CONCAT(tb_racc.ACC_MBR, 0)
                    WHERE MD5(MD5(tb_racc.ID_ACC)) = '".$id."'
                ")or die(mysqli_error($db));
                $INSERT_NOTE = mysqli_query($db,'
                    INSERT INTO tb_note SET
                    tb_note.NOTE_MBR = '.$RESULT_QUERY["MBR_ID"].',
                    tb_note.NOTE_RACC = '.$RESULT_QUERY["ID_ACC"].',
                    tb_note.NOTE_TYPE = "WP VER REJECT",
                    tb_note.NOTE_NOTE = "'.$note_reject.'",
                    tb_note.NOTE_DATETIME = "'.date('Y-m-d H:i:s').'"
                ') or die(mysqli_error($db));
                insert_log($RESULT_QUERY['MBR_ID'], 'Reject WP Confirmation ');
                
                // Message Telegram
                $mesg = 'Notif : Regol Baru Ditolak'.
                PHP_EOL.'Date : '.date("Y-m-d").
                PHP_EOL.'Time : '.date("H:i:s");
                // PHP_EOL.'======== Informasi Regol =========='.
                // PHP_EOL.'Nama : '.$RESULT_QUERY['MBR_NAME'].
                // PHP_EOL.'Email : '.$RESULT_QUERY['MBR_EMAIL'].
                // PHP_EOL.'Status : Ditolak'.
                // PHP_EOL.'Alasan Ditolak : '.$note_reject.
                // PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                // Message Telegram
                $mesg_othr = 'Notif : Regol Baru Ditolak'.
                PHP_EOL.'Date : '.date("Y-m-d").
                PHP_EOL.'Time : '.date("H:i:s").
                PHP_EOL.'==================================='.
                PHP_EOL.'                         Informasi Regol'.
                PHP_EOL.'==================================='.
                PHP_EOL.'Nama : '.$RESULT_QUERY['MBR_NAME'].
                PHP_EOL.'Email : '.$RESULT_QUERY['MBR_EMAIL'].
                PHP_EOL.'Status : Ditolak'.
                PHP_EOL.'Alasan Ditolak : '.$note_reject.
                PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                $request_params = [
                    'chat_id' => $chat_id,
                    'text' => $mesg
                ];
                http_request('https://api.telegram.org/bot'.$token1.'/sendMessage?'.http_build_query($request_params));

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
                die("<script>location.href = 'home.php?page=member_realacc'</script>");
            };
        }

        if(isset($_POST['pending'])) {
            if(isset($_POST['note_pending'])){
                $note_pending = form_input($_POST['note_pending']);
                $EXEC_SQL = mysqli_query($db,"
                    UPDATE tb_racc SET
                        tb_racc.ACC_WPCHECK      = -5,
                        tb_racc.ACC_WPCHECK_DATE = '".date("Y-m-d H:i:s")."'
                    WHERE MD5(MD5(tb_racc.ID_ACC)) = '".$id."'
                ")or die(mysqli_error($db));
                $INSERT_NOTE = mysqli_query($db,'
                    INSERT INTO tb_note SET
                    tb_note.NOTE_MBR = '.$RESULT_QUERY["MBR_ID"].',
                    tb_note.NOTE_RACC = '.$RESULT_QUERY["ID_ACC"].',
                    tb_note.NOTE_TYPE = "WP VER PENDING",
                    tb_note.NOTE_NOTE = "'.$note_pending.'",
                    tb_note.NOTE_DATETIME = "'.date('Y-m-d H:i:s').'"
                ') or die(mysqli_error($db));
                insert_log($RESULT_QUERY['MBR_ID'], 'Pending WP Confirmation ');

                $mmsg = '';
                $mail = new PHPMailer(true);
                try {
                    //Server settings
                    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    $mail->isSMTP();
                    $mail->Host       = $setting_email_host_api;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $setting_email_support_name;
                    $mail->Password   = $setting_email_support_password;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = $setting_email_port_api;

                    //Recipients
                    $mail->setFrom($setting_email_support_name, $web_name_full);
                    $mail->addAddress($RESULT_QUERY['MBR_EMAIL'], $RESULT_QUERY['MBR_NAME']);
                    // $mail->addStringAttachment($nattchstr, 'All_documents.pdf', 'base64', 'application/pdf');
                    
                    //Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Konfirmasi Pembuatan Real Account | '.date("Y-m-d H:i:s");

                    $mail->Body    = "
                        <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                        <html hola_ext_inject='disabled' xmlns='http://www.w3.org/1999/xhtml'>
                            <head>
                                <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                                <title>Konfirmasi Pembuatan Real Account</title>
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
                                    <!--
                                        <div style='background-color:#f9f9f9;padding: 10px;border-radius: 5px;'>
                                            <strong>Registration Info</strong>
                                        </div>
                                    -->
                                    <div style='padding: 10px;'>
                                        <!--
                                            Kepada Yth ".$RESULT_QUERY['MBR_NAME'].", <br>
                                            <div>
                                                Status : Pending
                                            </div>
                                            <br><br>
                                            <div>
                                                Terima Kasih<br><br>
                                                Dari Kami,<br>
                                                ".$web_name_full." Team Support
                                            </div>
                                        -->
                                        <div>
                                            <div style='page-break-before:always; page-break-after:always'>
                                                <div>
                                                    <p>&#9733;&#9733;&#2947; &#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9488; <br /> EMAIL KONFIRMASI REAL ACCOUNT<br /> www.mandirifx.co.id &#61480; 031 3360 1175 Mail: wpb@mandirifx.co.id <br />&#9492;...&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472;&#9472; &#2947;&#9733;&#9733; <br />
                                                    </p>
                                                    <p>
                                                        Kepada Yth ".$RESULT_QUERY['MBR_NAME'].", <br>
                                                        Berikut ini adalah status dan catatan regol anda oleh WPB kami.<br>
                                                        <div>
                                                            Status : Pending<br>
                                                            Catatan : ".$note_pending."
                                                        </div>
                                                        <br><br>
                                                        <div>
                                                            Terima Kasih<br><br>
                                                            Dari Kami,<br>
                                                            ".$web_name_full." Team Support
                                                        </div>

                                                    </p>
                                                    <p>
                                                        <br />
                                                        <br />
                                                    </p>
                                                    <p>
                                                        <b>Yth. Bapak/Ibu ".$RESULT_QUERY['MBR_NAME'].". <br />
                                                        </b>Calon Nasabah Mandiri Investindo Futures, <br />
                                                        <br />
                                                    </p>
                                                    <p>Dengan ini saya <br />
                                                        <b>Nama : Agus Purnawan <br /> Jabatan : Wakil Pialang Berjangka PT. Mandiri Investindo Futures <br /> No. WP : 0010/UPTP/Sl/ 12/2022 </b>
                                                        <br />
                                                    </p>
                                                    <p>
                                                        <b>Menyatakan</b> bahwa hasil dari verifikasi kami: &#8220;Anda telah berhasil melakukan pendaftaran menjadi <br />nasabah secara elektronik di bidang perdagangan berjangka komoditi&#8221;, yaitu telah: <br />
                                                    </p>
                                                    <p>1. Mengisi kelengkapan data aplikasi pembukaan online (Formulir Nomor 107.PBk.03) <br />2. Setuju, mengerti dan memahami tentang Profil PT. Mandiri Investindo Futures, Dokumen <br />
                                                    </p>
                                                    <p>Pemberitahuan adanya resiko, Dokumen Perjanjian Pemberian Amanat secara Elektronik On Line, <br />Dokumen Peraturan Perdagangan / Trading Rules, Pernyataan bertanggung jawab atas kode akses <br />trasaksi nasabah / <i>personal access password</i>. <br />
                                                    </p>
                                                    <p>3. Mengisi dan Menyatakan telah melakukan simulasi transaksi perdagangan berjangka), Dokumen <br />telah berpengalaman dalam melaksanakan Transaksi pedagangan dan Siap bertanggung Jawab <br />atas kode Akses Nasabah serta menyertakan Dokumen bahwa Dana deposit merupakan dana <br />sendir dan bukan berasal dari tindak kejatahatan maupun hasil dari money laudry. <br />
                                                    </p>
                                                    <p>4. Memberikan Self Declaration Video tanpa paksaan dari pihak manapun dan atas kesadarannya <br />sendiri. <br />
                                                    </p>
                                                    <p>
                                                        <b>
                                                            <i>Selanjutnya, <br />
                                                            </i>
                                                        </b>
                                                        <i></i>1. Melakukan transfer dana ke rekening segregate account. <br />
                                                    </p>
                                                    <table style='border-collapse:collapse;' cellspacing='0'>
                                                        <tr style=''>
                                                            <td style='border: 1px solid black;'>
                                                                <p class='s4' style='text-align: center;'>BANK</p>
                                                            </td>
                                                            <td style='border: 1px solid black;'>
                                                                <p class='s4' style='text-align: left;'>NOMOR REKENING</p>
                                                            </td>
                                                            <td style='border: 1px solid black;'>
                                                                <p class='s4' style='text-align: center;'>CABANG</p>
                                                            </td>
                                                        </tr>
                                                        <tr style=''>
                                                            <td style='border: 1px solid black;'>
                                                                <p class='s4' style='text-align: center;'>BCA</p>
                                                            </td>
                                                            <td style='border: 1px solid black;'>
                                                                <p class='s5' style='text-align: left;'>0105220222 (IDR)</p>
                                                            </td>
                                                            <td style='border: 1px solid black;'>
                                                                <p class='s5' style='text-align: center;'>KCU VETERAN</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <p>
                                                        <br />2. Me-reply email ini dengan bukti transfer/deposit. <br />
                                                    </p>
                                                    <p>
                                                        <br />Demikian kami sampaikan. selamat bertransaksi di <b>PT. Mandiri Investindo Futures</b>
                                                        <br />
                                                        <b>Hormat kami, <br />
                                                        </b>-- sent via electronic mail &#8211; <br />
                                                        <b>Terry Indradi Oktriawan,SE <br />
                                                        </b>
                                                            Direktur Utama PT. Mandiri Investindo Futures <br />
                                                            <!--Lampiran : <br />Dokumen Profil Perusahaan Dokumen Trading Rules <br />Dokumen Pernyataan Telah melakukan demo akun Dokumen Tanggung jawab terhadap Hak Akses <br />Dokumen Pernyataan Telah Berpengalaman Transaksi Dokumen Pernyataan Dana yang digunakan <br />Disclosure Statement Bukti Konfirmasi <br />Aplikasi Pembukaan akun <br />Disclosure Statement <br />Dokumen Pemberitahuan Adanya Resiko <br />Disclosure Statement <br />Dokumen Pemberian Amanat <b>-->
                                                            <i></i>
                                                        </b>
                                                        <p>
                                                            <b>Lampiran : <a target='_blank' href='https://control.techcrm.net/0e03qkuh/pdf/root/all.php?x=".$id."'>Klik di sini untuk melihat semua dokumen</a></b>
                                                        </p>
                                                    </p>
                                                </div>
                                            </div>
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
                        <script>
                            document.getElementById('dwnldall').addEventListener('click', (e) => {
                                Array.from(document.querySelectorAll('a[download]')).forEach((el) => {
                                    el.click();
                                });
                            });
                        </script>
                    ";
                    $mail->send();
                    $mmsg = 'Sended';
                } catch (Exception $e) {
                    // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    $mmsg = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    die ("<script>location.href = 'home.php?page=member_realacc';</script>");
                }
                
                // Message Telegram
                $mesg = 'Notif : Regol Baru Pending'.
                PHP_EOL.'Date : '.date("Y-m-d").
                PHP_EOL.'Time : '.date("H:i:s");
                // PHP_EOL.'======== Informasi Regol =========='.
                // PHP_EOL.'Nama : '.$RESULT_QUERY['MBR_NAME'].
                // PHP_EOL.'Email : '.$RESULT_QUERY['MBR_EMAIL'].
                // PHP_EOL.'Status : Ditolak'.
                // PHP_EOL.'Alasan Ditolak : '.$note_reject.
                // PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                // Message Telegram
                $mesg_othr = 'Notif : Regol Baru Pending'.
                PHP_EOL.'Date : '.date("Y-m-d").
                PHP_EOL.'Time : '.date("H:i:s").
                PHP_EOL.'==================================='.
                PHP_EOL.'                         Informasi Regol'.
                PHP_EOL.'==================================='.
                PHP_EOL.'Nama : '.$RESULT_QUERY['MBR_NAME'].
                PHP_EOL.'Email : '.$RESULT_QUERY['MBR_EMAIL'].
                PHP_EOL.'Status : Pending'.
                PHP_EOL.'Alasan Pending : '.$note_pending.
                PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                $request_params = [
                    'chat_id' => $chat_id,
                    'text' => $mesg
                ];
                http_request('https://api.telegram.org/bot'.$token1.'/sendMessage?'.http_build_query($request_params));

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
                die("<script>location.href = 'home.php?page=member_realacc'</script>");
            };
        }

$tanggal_sekarang = date('Y-m-d');
$tanggal_sekarang = date('Y-m-d', strtotime('1 days', strtotime($tanggal_sekarang)));
$tanggal_5_tahun_lalu = date('Y-m-d', strtotime('-5 years', strtotime($tanggal_sekarang)));
?>
<div class="row">
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header font-weight-bold">
                Aggrement
                <!-- <a target="_blank" href="pdf/root/10.disclosure-statement.php?x=<?php echo $id ?>" class="btn btn-primary float-right" style="margin-left : auto;">
                    <i class="fa fa-eye"></i>&nbsp;Disclosure Statement
                </a> -->
            </div>
            <div class="card-body">
                <?php require_once(__DIR__ . "/agreement.php") ?>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header font-weight-bold">
                Demo Account : <?php echo $RESULT_QUERY['ACC_DEMO'] ?>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="openorder-tab" data-toggle="tab" href="#openorder" role="tab" aria-controls="openorder" aria-selected="true">Open Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="closeorder-tab" data-toggle="tab" href="#closeorder" role="tab" aria-controls="closeorder" aria-selected="false">Close Order</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="openorder" role="tabpanel" aria-labelledby="openorder-tab">

                        <table class="table table-striped table-hover table-bordered mt-3">
                            <thead>
                                <tr class="text-center">
                                    <th>Symbol</th>
                                    <th>Ticket</th>
                                    <th>Time</th>
                                    <th>Type</th>
                                    <th>Volume</th>
                                    <th>Price</th>
                                    <th>Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $getOpenedOrdersList = mt5api_demonew_connect('OpenedOrdersList', 'login=' . $RESULT_QUERY['ACC_DEMO']);

                                    if ($getOpenedOrdersList === false) {
                                        echo "<tr><td colspan='7' class='text-center'>Gagal terhubung ke API.</td></tr>";
                                    } else {
                                        $data = json_decode($getOpenedOrdersList, true);

                                        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                                            echo "<tr><td colspan='7' class='text-center'>Gagal memproses data dari API. Kesalahan JSON: " . json_last_error_msg() . "</td></tr>";
                                        } else {
                                            $OpenedOrdersList = json_decode($data['message'], true);

                                            if (is_array($OpenedOrdersList)) {
                                                foreach ($OpenedOrdersList as $key) {
                                                    $timestamp = strtotime($key['openTime']);
                                                    $formattedDate = date('Y-m-d H:i:s', $timestamp);
                                ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo htmlspecialchars($key['symbol']); ?></td>
                                                        <td><?php echo htmlspecialchars($key['positionID']); ?></td>
                                                        <td class="text-center"><?php echo $formattedDate; ?></td>
                                                        <td class="text-center"><?php echo ($key['action'] == 0) ? 'Buy' : 'Sell'; ?></td>
                                                        <td class="text-right"><?php echo htmlspecialchars($key['volume']); ?></td>
                                                        <td class="text-right"><?php echo htmlspecialchars($key['openPrice']); ?></td>
                                                        <td class="text-right"><?php echo number_format($key['profit'], 2); ?></td>
                                                    </tr>
                                <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='7' class='text-center'>Tidak ada data order yang ditemukan.</td></tr>";
                                            }
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="closeorder" role="tabpanel" aria-labelledby="closeorder-tab">

                        <table class="table table-striped table-hover table-bordered mt-3">
                            <thead>
                                <tr class="text-center">
                                    <th>OpenTime</th>
                                    <th>Symbol</th>
                                    <th>Ticket</th>
                                    <th>Type</th>
                                    <th>Volume</th>
                                    <th>OpenPrice</th>
                                    <th>CloseTime</th>
                                    <th>ClosePrice</th>
                                    <th>Profit</th></tr>
                            </thead>
                            <tbody>
                                <?php
                                    $getHistoryRequestList = mt5api_demonew_connect('HistoryRequestList', 'login='.$RESULT_QUERY['ACC_DEMO'].'&date_To='.$tanggal_sekarang.'&date_From='.$tanggal_5_tahun_lalu);
                                    if ($getOpenedOrdersList === false) {
                                        echo "<tr><td colspan='9' class='text-center'>Gagal terhubung ke API.</td></tr>";
                                    } else {
                                        $data = json_decode($getHistoryRequestList, true);
                                        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                                            echo "<tr><td colspan='9' class='text-center'>Gagal memproses data dari API. Kesalahan JSON: " . json_last_error_msg() . "</td></tr>";
                                        } else {
                                            $HistoryRequestList = json_decode($data['message'], true);
                                            
                                            if (is_array($OpenedOrdersList)) {
                                                $positionCounts = array_count_values(array_column($HistoryRequestList, 'positionID'));
                                                $groupedData = [];
                                                
                                                foreach ($HistoryRequestList as $row) {
                                                    $positionID = $row['positionID'];
                                                    $order = $row['order'];
                                                
                                                    if ($positionCounts[$positionID] > 1) { // Hanya proses jika positionID muncul lebih dari sekali
                                                        if (!isset($groupedData[$positionID])) {
                                                            $groupedData[$positionID] = [
                                                                'OpenTime' => '',
                                                                'Symbol' => $row['symbol'],
                                                                'positionID' => $positionID,
                                                                'Volume' => $row['volume']/10000,
                                                                'ContractSize' => $row['contractsize'],
                                                                'TypeCmd' => '',
                                                                'OpenPrice' => '',
                                                                'CloseTime' => '',
                                                                'ClosePrice' => '',
                                                                'Profit' => 0
                                                            ];
                                                        }
                                                
                                                        if ($order == $positionID) {
                                                            $groupedData[$positionID]['TypeCmd'] = $row['cmd'];
                                                            $groupedData[$positionID]['OpenTime'] = $row['closeTime'];
                                                            $groupedData[$positionID]['OpenPrice'] = $row['closePrice'];
                                                        } else {
                                                            $groupedData[$positionID]['TypeCmd'] = $row['cmd'];
                                                            $groupedData[$positionID]['CloseTime'] = $row['closeTime'];
                                                            $groupedData[$positionID]['ClosePrice'] = $row['closePrice'];
                                                        }
                                                    }
                                                }
                                                foreach ($groupedData as $row) {
                                                    $profit = abs(($row['ClosePrice']-$row['OpenPrice'])*$row['Volume']*$row['ContractSize']);
                                                    if($row['TypeCmd'] == 0) {
                                                        $row['TypeCmd'] = 'Sell';
                                                        if($row['ClosePrice'] > $row['OpenPrice']) {
                                                            $profit = $profit * -1;
                                                        } else {
                                                            $profit = $profit;
                                                        }
                                                    } else {
                                                        $row['TypeCmd'] = 'Buy';
                                                        if($row['ClosePrice'] < $row['OpenPrice']) {
                                                            $profit = $profit * -1;
                                                        } else {
                                                            $profit = $profit;
                                                        }
                                                    }
                                                    echo "<tr>";
                                                    echo "<td class='text-center'>" . $row['OpenTime'] . "</td>";
                                                    echo "<td class='text-center'>" . $row['Symbol'] . "</td>";
                                                    echo "<td>" . $row['positionID'] . "</td>";
                                                    echo "<td class='text-center'>" . $row['TypeCmd'] . "</td>";
                                                    echo "<td class='text-right'>" . $row['Volume'] . "</td>";
                                                    echo "<td class='text-right'>" . $row['OpenPrice'] . "</td>";
                                                    echo "<td class='text-center'>" . $row['CloseTime'] . "</td>";
                                                    echo "<td class='text-right'>" . $row['ClosePrice'] . "</td>";
                                                    echo "<td class='text-right'>" . number_format($profit, 2) . "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='9' class='text-center'>Tidak ada data order yang ditemukan.</td></tr>";
                                            }
                                        }
                                    }
                                ?>
                                <?php 
                                    // }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header font-weight-bold">Summary</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" width="100%">
                        <tr>
                            <td>Type</td>
                            <td>&nbsp;:&nbsp;
                                <strong>
                                    <?php
                                        if($RESULT_QUERY['ACC_TYPE'] == '' ||$RESULT_QUERY['ACC_TYPE'] == '-') {
                                            echo '-';
                                        } else {
                                            if($RESULT_QUERY['ACC_TYPE'] == 1) {
                                                echo 'SPA';
                                            } else if($RESULT_QUERY['ACC_TYPE'] == 2) {
                                                echo 'KOMODITI';
                                            } else { echo 'Unknown'; }
                                        };
                                    ?>
                                </strong></td>
                            <td>Charge</td>
                            <td>&nbsp;:&nbsp;
                                <strong>
                                    <?php
                                        if($RESULT_QUERY['ACC_CHARGE'] == '' ||$RESULT_QUERY['ACC_CHARGE'] == '-') {
                                            echo '-';
                                        } else { echo $RESULT_QUERY['ACC_CHARGE']; };
                                    ?>
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td>Rate</td>
                            <td>&nbsp;:&nbsp;
                                <strong>
                                    <?php
                                        if($RESULT_QUERY['ACC_RATE'] == '' ||$RESULT_QUERY['ACC_RATE'] == '-') {
                                            echo '-';
                                        } else { echo $RESULT_QUERY['ACC_RATE']; };
                                    ?>
                                </strong>
                            </td>
                            <td>Product</td>
                            <td>&nbsp;:&nbsp;
                                <strong>
                                    <?php
                                        if($RESULT_QUERY['ACC_PRODUCT'] == '' ||$RESULT_QUERY['ACC_PRODUCT'] == '-') {
                                            echo '-';
                                        } else { echo $RESULT_QUERY['ACC_PRODUCT']; };
                                    ?>
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>&nbsp;:&nbsp;
                                <?php
                                    if($RESULT_QUERY['ACC_F_APP_PRIBADI_NAMA'] == '' ||$RESULT_QUERY['ACC_F_APP_PRIBADI_NAMA'] == '-') {
                                        echo '-';
                                    } else { echo $RESULT_QUERY['ACC_F_APP_PRIBADI_NAMA']; };
                                ?>
                            </td>
                            <td>Email</td>
                            <td>&nbsp;:&nbsp;
                                <?php
                                    if($RESULT_QUERY['MBR_EMAIL'] == '' ||$RESULT_QUERY['MBR_EMAIL'] == '-') {
                                        echo '-';
                                    } else { echo $RESULT_QUERY['MBR_EMAIL']; };
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>No Tlp</td>
                            <td>&nbsp;:&nbsp;
                                <?php
                                    if($RESULT_QUERY['ACC_F_APP_PRIBADI_HP'] == '' ||$RESULT_QUERY['ACC_F_APP_PRIBADI_HP'] == '-') {
                                        echo '-';
                                    } else { echo $RESULT_QUERY['ACC_F_APP_PRIBADI_HP']; };
                                ?>
                            </td>
                            <td>Ibu Kandung</td>
                            <td>&nbsp;:&nbsp;
                                <?php
                                    if($RESULT_QUERY['ACC_F_APP_PRIBADI_IBU'] == '' ||$RESULT_QUERY['ACC_F_APP_PRIBADI_IBU'] == '-') {
                                        echo '-';
                                    } else { echo $RESULT_QUERY['ACC_F_APP_PRIBADI_IBU']; };
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Tempat lahir</td>
                            <td>&nbsp;:&nbsp;
                                <?php
                                    if($RESULT_QUERY['ACC_F_APP_PRIBADI_TMPTLHR'] == '' ||$RESULT_QUERY['ACC_F_APP_PRIBADI_TMPTLHR'] == '-') {
                                        echo '-';
                                    } else {
                                        echo $RESULT_QUERY['ACC_F_APP_PRIBADI_TMPTLHR'];
                                    };
                                ?>
                            </td>
                            <td>Tanggal lahir</td>
                            <td>&nbsp;:&nbsp;
                                <?php
                                    if($RESULT_QUERY['ACC_F_APP_PRIBADI_TGLLHR'] == '' ||$RESULT_QUERY['ACC_F_APP_PRIBADI_TGLLHR'] == '-') {
                                        echo '-';
                                    } else { echo $RESULT_QUERY['ACC_F_APP_PRIBADI_TGLLHR']; };
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Type Identitas</td>
                            <td>&nbsp;:&nbsp;
                                <?php
                                    if($RESULT_QUERY['ACC_F_APP_PRIBADI_TYPEID'] == '' ||$RESULT_QUERY['ACC_F_APP_PRIBADI_TYPEID'] == '-') {
                                        echo '-';
                                    } else { echo $RESULT_QUERY['ACC_F_APP_PRIBADI_TYPEID']; };
                                ?>
                            </td>
                            <td>No Identitas</td>
                            <td>&nbsp;:&nbsp;
                                <?php
                                    if($RESULT_QUERY['ACC_F_APP_PRIBADI_ID'] == '' ||$RESULT_QUERY['ACC_F_APP_PRIBADI_ID'] == '-') {
                                        echo '-';
                                    } else { echo $RESULT_QUERY['ACC_F_APP_PRIBADI_ID']; };
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Product</td>
                            <td>&nbsp;:&nbsp;
                                <?php
                                    if($RESULT_QUERY['ACC_PRODUCT'] == '' ||$RESULT_QUERY['ACC_PRODUCT'] == '-') {
                                        echo '-';
                                    } else { echo $RESULT_QUERY['ACC_PRODUCT']; };
                                ?>
                            </td>
                            <td>Document Type</td>
                            <td>&nbsp;:&nbsp;
                                <?php
                                    if($RESULT_QUERY['ACC_F_APP_FILE_TYPE'] == '' ||$RESULT_QUERY['ACC_F_APP_FILE_TYPE'] == '-') {
                                        echo '-';
                                    } else {
                                        echo $RESULT_QUERY['ACC_F_APP_FILE_TYPE'];
                                    };
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>No. NPWP</td>
                            <td>&nbsp;:&nbsp;
                                <?php
                                    if($RESULT_QUERY['ACC_F_APP_PRIBADI_NPWP'] == '' ||$RESULT_QUERY['ACC_F_APP_PRIBADI_NPWP'] == '-') {
                                        echo '-';
                                    } else { echo $RESULT_QUERY['ACC_F_APP_PRIBADI_NPWP']; };
                                ?>
                            </td>
                            <td>Jenis Pekerjaan</td>
                            <td>&nbsp;:&nbsp;
                                <?php
                                    if($RESULT_QUERY['ACC_F_APP_KRJ_NAMA'] == '' ||$RESULT_QUERY['ACC_F_APP_KRJ_NAMA'] == '-') {
                                        echo '-';
                                    } else {
                                        echo $RESULT_QUERY['ACC_F_APP_KRJ_NAMA'].' ('.$RESULT_QUERY['ACC_F_APP_KRJ_BDNG'].')';
                                    };
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>IB Code</td>
                            <td>&nbsp;:&nbsp;<?php echo $RESULT_QUERY['IB_CODE']; ?></td>
                            <td>IB City</td>
                            <td>&nbsp;:&nbsp;<?php echo $RESULT_QUERY['IB_CITY']; ?></td>
                        </tr>
                        <tr>
                            <td>Bank 1 Name</td>
                            <td>&nbsp;:&nbsp;<?php echo $RESULT_QUERY['ACC_F_APP_BK_1_NAMA']; ?></td>
                            <td>Bank 1 Account</td>
                            <td>&nbsp;:&nbsp;<?php echo $RESULT_QUERY['ACC_F_APP_BK_1_ACC']; ?></td>
                        </tr>
                        <tr>
                            <td>Bank 2 Name</td>
                            <td>&nbsp;:&nbsp;<?php echo $RESULT_QUERY['ACC_F_APP_BK_2_NAMA']; ?></td>
                            <td>Bank 2 Account</td>
                            <td>&nbsp;:&nbsp;<?php echo $RESULT_QUERY['ACC_F_APP_BK_2_ACC']; ?></td>
                        </tr>
                    </table>
                    <div class="row mb-3">
                        <div class="col-md-4 text-center">
                            <div style="border: 1px solid #adadad;border-radius: 10px;padding: 10px;">
                                <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_RKBKCRED']; ?>">Rekening Koran Bank / Tagihan Kartu Kredit</a>
                                <hr>
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_RKBKCRED'] == ''|| $RESULT_QUERY['ACC_F_APP_FILE_RKBKCRED'] == '-' ){ ?>
                                    <img src="assets/img/unknown-file.png" width="75%">
                                <?php } else { ?>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_RKBKCRED']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_RKBKCRED']; ?>" width="75%"></a>
                                <?php }; ?>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div style="border: 1px solid #adadad;border-radius: 10px;padding: 10px;">
                                <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_REKLISTLP']; ?>">Rekening Listrik / Telepon</a>
                                <hr>
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_REKLISTLP'] == ''|| $RESULT_QUERY['ACC_F_APP_FILE_REKLISTLP'] == '-' ){ ?>
                                    <img src="assets/img/unknown-file.png" width="75%">
                                <?php } else { ?>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_REKLISTLP']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_REKLISTLP']; ?>" width="75%"></a>
                                <?php }; ?>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div style="border: 1px solid #adadad;border-radius: 10px;padding: 10px;">
                                <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG']; ?>">Tambahan Dokumen lain 1</a>
                                <hr>
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_IMG'] == ''|| $RESULT_QUERY['ACC_F_APP_FILE_IMG'] == '-' ){ ?>
                                    <img src="assets/img/unknown-file.png" width="75%">
                                <?php } else { ?>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG']; ?>" width="75%"></a>
                                <?php }; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 text-center">
                        <div class="col-md-4 text-center">
                            <div style="border: 1px solid #adadad;border-radius: 10px;padding: 10px;">
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_IMG2'] == '' || $RESULT_QUERY['ACC_F_APP_FILE_IMG2'] == '-' ){ ?>
                                    Tambahan Dokumen lain 2
                                <?php } else { ?>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG2']; ?>">Tambahan Dokumen lain 2</a>
                                <?php }; ?>
                                <hr>
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_IMG2'] == '' || $RESULT_QUERY['ACC_F_APP_FILE_IMG2'] == '-' ){ ?>
                                    <img src="assets/img/unknown-file.png" width="75%">
                                <?php } else { ?>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG2']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG2']; ?>" width="75%"></a>
                                <?php }; ?>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div style="border: 1px solid #adadad;border-radius: 10px;padding: 10px;">
                                <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_FOTO']; ?>">Foto Terbaru</a>
                                <hr>
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_FOTO'] == ''|| $RESULT_QUERY['ACC_F_APP_FILE_FOTO'] == '-' ){ ?>
                                    <img src="assets/img/unknown-file.png" width="75%">
                                <?php } else { ?>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_FOTO']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_FOTO']; ?>" width="75%"></a>
                                <?php }; ?>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div style="border: 1px solid #adadad;border-radius: 10px;padding: 10px;">
                                <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_ID']; ?>">Foto Identitas</a>
                                <hr>
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_ID'] == '' || $RESULT_QUERY['ACC_F_APP_FILE_ID'] == '-' ){ ?>
                                    <img src="assets/img/unknown-file.png" width="75%">
                                <?php } else { ?>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_ID']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_ID']; ?>" width="75%"></a>
                                <?php }; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-success" data-target="#modal_accept" data-toggle="modal">Accept</button>
                <button type="button" class="btn btn-danger" data-target="#modal_reject" data-toggle="modal">Reject</button>
                <button type="button" class="btn btn-warning" data-target="#modal_pending" data-toggle="modal">Pending</button>
                <form method="post">
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
                </form>     
                <form action="" method="post">
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
                </form>
                <form action="" method="post">
                    <div class="modal fade" id="modal_pending" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Form untuk catatan pending nasabah</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Note</label>
                                        <input type="text" value=" " class="form-control text-center" name="note_pending" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name="pending" class="btn btn-warning">Submit Pending</button>
                                </div>
                            </div>
                        </div>
                    </div>  
                </form>  
            </div>
        </div>
    </div>
</div>
<?php 
    }else{
        die("<script>alert('Kepada ".$user1["ADM_NAME"].", anda tidak ada akses ke halaman ini');location.href = 'home.php?page=member_realacc'</script>");
    };
?>