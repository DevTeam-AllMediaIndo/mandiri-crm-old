<?php
    require 'phpmailer/Exception.php';
    require 'phpmailer/PHPMailer.php';
    require 'phpmailer/SMTP.php';

    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    if(isset($_POST['submit']) && 
    isset($_POST['id']) && 
    isset($_POST['login']) && 
    isset($_POST['password']) && 
    isset($_POST['passphone']) && 
    isset($_POST['initialmargin']) && 
    isset($_POST['investor'])){
        
        $id = mysqli_real_escape_string($db, stripslashes(strip_tags($_POST["id"])));
        $login = mysqli_real_escape_string($db, stripslashes(strip_tags($_POST["login"])));
        $password = mysqli_real_escape_string($db, stripslashes(strip_tags($_POST["password"])));
        $investor = mysqli_real_escape_string($db, stripslashes(strip_tags($_POST["investor"])));
        $passphone = mysqli_real_escape_string($db, stripslashes(strip_tags($_POST["passphone"])));
        $initialmargin = mysqli_real_escape_string($db, stripslashes(strip_tags($_POST["initialmargin"])));

        //if(is_numeric($login)){
            
            $SQL_CHKLOGIN1 = mysqli_query($db, '
                SELECT tb_racc.ACC_LOGIN
                FROM tb_racc
                WHERE tb_racc.ACC_LOGIN = "'.$login.'"
                AND tb_racc.ACC_DERE = 1
                LIMIT 1
            ');
            if(mysqli_num_rows($SQL_CHKLOGIN1) > 0) {
                die ("<script>alert('please check login already use');location.href = 'home.php?page=".$login_page."'</script>");
            } else {
                $SQL_CHKLOGIN2 = mysqli_query($db, '
                    SELECT tb_racc.ACC_TYPE
                    FROM tb_racc
                    WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                    LIMIT 1
                ');
                if(mysqli_num_rows($SQL_CHKLOGIN2) > 0) {
                    $RESULT_CHKLOGIN2 = mysqli_fetch_assoc($SQL_CHKLOGIN2);
                    if($RESULT_CHKLOGIN2['ACC_TYPE'] > 0){
                        $check = 1;
                        if(is_numeric($login) && $RESULT_CHKLOGIN2['ACC_TYPE'] == 1){

                            $SQL_QUERYMETA = mysqli_query($db, '
                                SELECT MT4_USERS.LOGIN
                                FROM MT4_USERS
                                WHERE MT4_USERS.LOGIN = '.$login.'
                                LIMIT 1
                            ');
                            echo mysqli_num_rows($SQL_QUERYMETA);
                            if(mysqli_num_rows($SQL_QUERYMETA) < 1) {
                                die ("<script>alert('Login not exist ".$login." ".$id." ".mysqli_num_rows($SQL_QUERYMETA)."');location.href = 'home.php?page=".$login_page."'</script>");
                            };
                        };
                    } else { $check = 0; };

                    if($check == 1){
                        $SQL_QUERYMBR = mysqli_query($db, '
                            SELECT
                                tb_member.MBR_ID,
                                tb_member.MBR_NAME,
                                tb_member.MBR_PHONE,
                                tb_member.MBR_EMAIL,
                                tb_member.MBR_PASS
                            FROM tb_member
                            JOIN tb_racc
                            ON(tb_member.MBR_ID = tb_racc.ACC_MBR)
                            WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                            LIMIT 1
                        ');
                        if(mysqli_num_rows($SQL_QUERYMBR) > 0){
                            $RESULT_QUERYMBR = mysqli_fetch_assoc($SQL_QUERYMBR);

                            $EXEC_SQL = mysqli_query($db, '
                                UPDATE tb_racc SET
                                tb_racc.ACC_LOGIN = "'.$login.'",
                                tb_racc.ACC_PASS = "'.$password.'",
                                tb_racc.ACC_INVESTOR = "'.$investor.'",
                                tb_racc.ACC_PASSPHONE = "'.$passphone.'",
                                tb_racc.ACC_INITIALMARGIN = '.$initialmargin.'
                                WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                            ') or die (mysqli_error($db));

                            $mail = new PHPMailer(true);
                            try {
                                $mail->isSMTP();
                                $mail->Host       = 'smtp.hostinger.com';
                                $mail->SMTPAuth   = true;
                                $mail->Username   = 'ibftrader@allmediaindo.com';
                                $mail->Password   = 'Ibftrader@allmediaindo.com123';
                                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                                $mail->Port       = 465;
            
                                //Recipients
                                $mail->setFrom('ibftrader@allmediaindo.com', $setting_title);
                                $mail->addAddress($RESULT_QUERYMBR['MBR_EMAIL'], $RESULT_QUERYMBR['MBR_NAME']);
                                
                                //Content
                                $mail->isHTML(true);
                                $mail->Subject = 'Real Account Information '.$setting_site_name.' '.date('Y-m-d H:i:s');
                                
                                $mail->Body    = "
                                <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
                                <html hola_ext_inject='disabled' xmlns='http://www.w3.org/1999/xhtml'>
                                    <head>
                                        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                                        <title>".$setting_site_name."</title>
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
                                            <center><img src='https://ibftrader.allmediaindo.com/assets/img/logoibf.png' style='height:50px'></center>

                                            <div style='padding: 10px;text-align: justify;'>
                                                <strong>Informasi Data Real Account</strong><br>

                                                Hi <strong>".$RESULT_QUERYMBR['MBR_NAME'].", </strong>Ini adalah data Real Account anda:<br><br>

                                                <ul>
                                                    <li>Login : ".$login."</li>
                                                    <li>Password : ".$password."</li>
                                                    <li>Investor : ".$investor."</li>
                                                    <li>Phone Password : ".$passphone."</li>
                                                    <li>Initial Deposit : ".$initialmargin."</li>
                                                </ul><br>
                                                <br>
                                                
                                                Terima Kasih.
                                                

                                                <br><br>
                                                Dari Kami,<br>
                                                ".$setting_site_name." Team Support
                                            </div>
                                            <hr>
                                            <p>
                                                <small>
                                                    <strong>Phone</strong> : <a href='tel:(022) 86061128'>(022) 86061128</a><br>
                                                    <strong>Support</strong> : <a href='mailto:support@ibftrader.com'>support@ibftrader.com</a><br>
                                                    <strong>Website</strong> : <a href='www.ibftrader.com'>ibftrader.com</a><br>
                                                    <strong>Address</strong> : Paskal Hyper Square Blok D No. 45-46 Bandung, Jawa Barat – 40181<br>
                                                    <br>
                                                    Resmi dan diatur oleh Badan Pengawas Perdagangan Berjangka Komoditi. Nomor registrasi BAPPEBTI : 912/BAPPEBTI/SI/8/2006.
                                                </small>
                                            </p><hr>
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
                                echo 'Message has been sent';
                                die("<script>location.href = 'home.php?page=".$login_page."'</script>");
                            } catch (Exception $e) {
                                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                die("<script>location.href = 'home.php?page=".$login_page."'</script>");
                            }
                        } else { die ("<script>alert('client not exits');location.href = 'home.php?page=".$login_page."'</script>"); }
                    }
                }
            }
        //};
    };
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Member</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pending Real Account</li>
    </ol>
</nav>
<div class="card mt-3">
    <div class="card-header font-weight-bold">Request Real Account</div>
    <div class="card-body">
        <div style="width:100%; height:605px;overflow-y: auto;">
            <div class="table-responsive">
                <table class="table table-striped table-hover" width="100%">
                    <thead>
                        <tr>
                            <th style="vertical-align: middle; border:1px solid #ddd;" class="text-center">Date</th>
                            <th style="vertical-align: middle; border:1px solid #ddd;" class="text-center">Username</th>
                            <th style="vertical-align: middle; border:1px solid #ddd;" class="text-center">Name</th>
                            <th style="vertical-align: middle; border:1px solid #ddd;" class="text-center">Type</th>
                            <th style="vertical-align: middle; border:1px solid #ddd;" class="text-center">Product</th>
                            <th style="vertical-align: middle; border:1px solid #ddd;" class="text-center">Rate</th>
                            <th style="vertical-align: middle; border:1px solid #ddd;" class="text-center">Login</th>
                            <th style="vertical-align: middle; border:1px solid #ddd;" class="text-center">Password</th>
                            <th style="vertical-align: middle; border:1px solid #ddd;" class="text-center">Investor</th>
                            <th style="vertical-align: middle; border:1px solid #ddd;" class="text-center">Phone</th>
                            <th style="vertical-align: middle; border:1px solid #ddd;" class="text-center">Initial</th>
                            <th style="vertical-align: middle; border:1px solid #ddd;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $SQL_QUERY = mysqli_query($db, '
                                SELECT
                                    MD5(MD5(tb_racc.ID_ACC)) AS ID_ACC,
                                    tb_racc.ACC_DATETIME,
                                    IF(tb_racc.ACC_TYPE = 1, "SPA", "Multilateral") AS ACC_TYPE,
                                    tb_racc.ACC_TYPEACC,
                                    tb_racc.ACC_PRODUCT,
                                    tb_racc.ACC_RATE,
                                    tb_member.MBR_USER,
                                    tb_member.MBR_NAME
                                FROM tb_member
                                JOIN tb_racc
                                ON(tb_member.MBR_ID = tb_racc.ACC_MBR)
                                WHERE tb_racc.ACC_LOGIN = "0"
                                AND tb_racc.ACC_DERE = 1
                                AND tb_racc.ACC_F_PERJ = 1
                                AND tb_racc.ACC_WPCHECK = 1
                                ORDER BY tb_racc.ACC_DATETIME DESC
                            ');
                            if(mysqli_num_rows($SQL_QUERY) > 0){
                                while($RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY)){
                        ?>
                        <form method="post">
                            <input type="hidden" class="form-control" name="id" value="<?php echo $RESULT_QUERY['ID_ACC'] ?>" required readonly autocomplete="off">
                        <tr>
                            <td class="text-center" style="vertical-align: middle"><?php echo $RESULT_QUERY['ACC_DATETIME'] ?></td>
                            <td style="vertical-align: middle"><a href="home.php?page=acc_real1&action=detail&x=<?php echo $RESULT_QUERY['ID_ACC']; ?>"><?php echo $RESULT_QUERY['MBR_USER'] ?></a></td>
                            <td style="vertical-align: middle"><?php echo $RESULT_QUERY['MBR_NAME'] ?></td>
                            <td style="vertical-align: middle"><?php echo $RESULT_QUERY['ACC_TYPE'] ?></td>
                            <td style="vertical-align: middle"><?php echo $RESULT_QUERY['ACC_TYPEACC'].' - '.$RESULT_QUERY['ACC_PRODUCT'] ?></td>
                            <td style="vertical-align: middle"><?php echo $RESULT_QUERY['ACC_RATE'] ?></td>
                            <td><input type="text" class="form-control" name="login" autocomplete="off" required></td>
                            <td><input type="text" class="form-control" name="password" autocomplete="off" required></td>
                            <td><input type="text" class="form-control" name="investor" autocomplete="off" required></td>
                            <td><input type="text" class="form-control" name="passphone" autocomplete="off" required></td>
                            <td><input type="number" class="form-control" name="initialmargin" autocomplete="off" required></td>
                            <td><button type="submit" class="btn btn-sm btn-success" name="submit">Set and<br>send email</button></td>
                        </tr>
                        </form>
                        <?php };} else { ?>
                            <tr><td class="text-center" colspan="10" style="border-bottom:1px solid black;">No data available in table</td></tr>
                        <?php }; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>