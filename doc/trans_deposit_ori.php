<?php
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
    if(isset($_GET['action'])){
        if(isset($_GET['x'])){
            $action = form_input($_GET['action']);
            $x = form_input($_GET['x']);

            if($action == 'accept'){
                $DPWD_STS = -1;
            } else if($action == 'reject'){
                $DPWD_STS = 1;
            } else { die ("<script>alert('action ".$action." unknown');location.href = 'Javascript:history.back(1)'</script>"); };

            mysqli_query($db, '
                UPDATE tb_dpwd SET
                tb_dpwd.DPWD_STS = '.$DPWD_STS.'
                WHERE MD5(MD5(tb_dpwd.ID_DPWD)) = "'.$x.'"
                AND tb_dpwd.DPWD_TYPE = 1
                AND tb_dpwd.DPWD_STS = 0
            ') or die ("<script>alert('Please try again, or contact support1');location.href = 'Javascript:history.back(1)'</script>");

            // $SQL_QUERY = mysqli_query($db, '
            //     SELECT
            //         tb_member.MBR_NAME,
            //         tb_member.MBR_USER,
            //         tb_member.MBR_EMAIL,
            //         tb_member.MBR_PHONE,
            //         tb_racc.ACC_LOGIN,
            //         tb_dpwd.DPWD_AMOUNT,
            //         tb_dpwd.DPWD_NOTE
            //     FROM tb_member
            //     JOIN tb_dpwd
            //     JOIN tb_racc
            //     ON(tb_member.MBR_ID = tb_dpwd.DPWD_MBR
            //     AND tb_dpwd.DPWD_LOGIN = tb_racc.ID_ACC)
            //     WHERE MD5(MD5(tb_dpwd.ID_DPWD)) = "'.$x.'"
            //     LIMIT 1
            // ');
            // if(mysqli_num_rows($SQL_QUERY) > 0){
            //     $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);

            //     $mail = new PHPMailer(true);
            //     try {
            //         $mail->isSMTP();
            //         $mail->Host       = 'smtp.hostinger.com';
            //         $mail->SMTPAuth   = true;
            //         $mail->Username   = 'ibftrader@allmediaindo.com';
            //         $mail->Password   = 'Ibftrader@allmediaindo.com123';
            //         $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            //         $mail->Port       = 465;

            //         //Recipients
            //         $mail->setFrom('ibftrader@allmediaindo.com', $setting_title);
            //         $mail->addAddress($RESULT_QUERY['MBR_EMAIL'], $RESULT_QUERY['MBR_NAME']);
                    
            //         //Content
            //         $mail->isHTML(true);
            //         $mail->Subject = 'Deposit Information '.$setting_site_name.' '.date('Y-m-d H:i:s');
                    
            //         $mail->Body    = "
            //             <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
            //             <html hola_ext_inject='disabled' xmlns='http://www.w3.org/1999/xhtml'>
            //                 <head>
            //                     <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
            //                     <title>".$setting_site_name."</title>
            //                     <style type='text/css'>
            //                         @media only screen and (min-device-width:600px) {
            //                             .content {width:600px!important;}
            //                         }
            //                         @media only screen and (max-device-width:480px) {
            //                             .text {font-size:12px!important;-webkit-text-size-adjust:100%!important;-moz-text-size-adjust:100%!important;-ms-text-size-adjust:100%!important;}
            //                             .button {font-size:16px!important;-webkit-text-size-adjust:100%!important;-moz-text-size-adjust:100%!important;-ms-text-size-adjust:100%!important;}
            //                         }
            //                     </style>
            //                 </head>
            //                 <body style='background-color:#cacaca'>
            //                     <div style='max-width: 1000px; margin: auto;padding: 20px'></div>
            //                     <div style='max-width: 600px; background-color:#ffffff;margin: auto;border: 1px solid #eaeaea;padding: 20px;border-radius: 5px;'>
            //                         <center><img src='https://ibftrader.allmediaindo.com/assets/img/logoibf.png' style='height:50px'></center>

            //                         <div style='padding: 10px;text-align: justify;'>
            //                             <strong>Informasi Data Deposit</strong><br>

            //                             Hi <strong>".$RESULT_QUERY['MBR_NAME'].", </strong><br>
            //                             Deposit anda Rp. ".number_format($RESULT_QUERY['DPWD_AMOUNT'], 0)." Telah di ".$action."<br>

            //                             Terima Kasih.


            //                             <br><br>
            //                             Dari Kami,<br>
            //                             ".$setting_site_name." Team Support
            //                         </div>
            //                         <hr>
            //                         <p>
            //                             <small>
            //                                 <strong>Phone</strong> : <a href='tel:(022) 86061128'>(022) 86061128</a><br>
            //                                 <strong>Support</strong> : <a href='mailto:support@ibftrader.com'>support@ibftrader.com</a><br>
            //                                 <strong>Website</strong> : <a href='www.ibftrader.com'>ibftrader.com</a><br>
            //                                 <strong>Address</strong> : Paskal Hyper Square Blok D No. 45-46 Bandung, Jawa Barat – 40181<br>
            //                                 <br>
            //                                 Resmi dan diatur oleh Badan Pengawas Perdagangan Berjangka Komoditi. Nomor registrasi BAPPEBTI : 912/BAPPEBTI/SI/8/2006.
            //                             </small>
            //                         </p><hr>
            //                         <p style='text-align: justify;'>
            //                             <small>
            //                                 <strong>PEMBERITAHUAN RESIKO:</strong><br>
            //                                 Semua produk finansial yang ditransaksikan dalam sistem margin mempunyai resiko tinggi terhadap dana Anda. Produk finansial ini tidak diperuntukkan bagi semua investor dan Anda bisa saja kehilangan dana lebih dari deposit awal Anda. Pastikan bahwa Anda benar-benar mengerti resikonya, dan mintalah nasihat independen jika diperlukan. Lihat Pemberitahuan Resiko lengkap kami di Ketentuan Bisnis.
            //                             </small>
            //                         </p>
            //                     </div>
            //                     <div style='max-width: 1000px; margin: auto;padding: 20px'></div>
            //                 </body>
            //             </html>
            //         ";
            //         $mail->send();
            //         echo 'Message has been sent';
            //         die ("<script>location.href = 'home.php?page=".$login_page."'</script>");
            //     } catch (Exception $e) {
            //         echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            //         die ("<script>location.href = 'home.php?page=".$login_page."'</script>");
            //     }
            // };
            echo 'action '.$action.' success';
            die ("<script>alert('action ".$action." success');location.href = 'Javascript:history.back(1)'</script>");
        } else { die ("<script>alert('Please try again, or contact support2');location.href = 'Javascript:history.back(1)'</script>"); }
    }
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Transaction</a></li>
        <li class="breadcrumb-item active" aria-current="page">Deposit</li>
    </ol>
</nav>
<?php if($user1["ADM_LEVEL"] == 1 || $user1["ADM_LEVEL"] == 2 || $user1["ADM_LEVEL"] == 3 || $user1["ADM_LEVEL"] == 6 || $user1["ADM_LEVEL"] == 4){ ?>
    <div class="card">
        <div class="card-header font-weight-bold">Pending</div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table_pending" class="table table-striped table-hover" width="100%">
                    <thead>
                        <tr>
                            <th style="vertical-align: middle" class="text-center">Date</th>
                            <th style="vertical-align: middle" class="text-center">Username</th>
                            <th style="vertical-align: middle" class="text-center">Name</th>
                            <th style="vertical-align: middle" class="text-center">Login</th>
                            <th style="vertical-align: middle" class="text-center">Amount</th>
                            <th style="vertical-align: middle" class="text-center">Pic</th>
                            <th style="vertical-align: middle" class="text-center">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
<?php }; ?>
<div class="card mt-3">
    <div class="card-header font-weight-bold">History</div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table_history" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th style="vertical-align: middle" class="text-center">Date</th>
                        <th style="vertical-align: middle" class="text-center">Username</th>
                        <th style="vertical-align: middle" class="text-center">Name</th>
                        <th style="vertical-align: middle" class="text-center">Login</th>
                        <th style="vertical-align: middle" class="text-center">Amount</th>
                        <th style="vertical-align: middle" class="text-center">Pic</th>
                        <th style="vertical-align: middle" class="text-center">Status</th>
                        <!-- <th style="vertical-align: middle" class="text-center">Detail</th> -->
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#table_pending').DataTable( {
            dom: 'Blfrtip',
            "processing": true,
            "serverSide": true,
            "ajax": "doc/<?php echo $login_page ?>_pending_ajax.php",
            "deferRender": true,
            "lengthMenu": [[50, 75, 100, -1], [50, 75, 100, "Semua"]],
            "scrollX": true,
            "order": [[ 0, "desc" ]]
        } );
    } );
    $(document).ready(function() {
        $('#table_history').DataTable( {
            dom: 'Blfrtip',
            "processing": true,
            "serverSide": true,
            "ajax": "doc/<?php echo $login_page ?>_history_ajax.php",
            "deferRender": true,
            "lengthMenu": [[50, 75, 100, -1], [50, 75, 100, "Semua"]],
            "scrollX": true,
            "order": [[ 0, "desc" ]]
        } );
    } );
</script>