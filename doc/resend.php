<?php

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
$setting_alias = 'IBFTrades';
$setting_name = 'techcrm';
$setting_ext = 'net';
$setting_protokol = 'https';
$setting_domain = 'control.'.$setting_name.'.'.$setting_ext;

$web_name_full = 'PT. International Business Futures';
$web_name = 'IBF Trader';

$setting_front_web_link = 'ibftrader.com';
$setting_central_office_address = 'PASKAL HYPER SQUARE BLOK D NO.45-46 JL. H.O.S COKROAMINOTO NO.25-27 BANDUNG, JAWA BARAT – 40181';
$setting_playstore_link = 'https://play.google.com/store/apps/details?id=com.allmediaindo.ibftrader&hl=en';
$setting_office_number = '(022) 86061128';
$setting_email_support_name = 'notification@ibftrader.co.id';
$setting_email_support_password = 'ynacgsetzlpyicjm';
$setting_email_logo_linksrc = 'https://ibftrader.allmediaindo.com/assets/img/logoibf.png';
$setting_email_host_api = 'smtp.gmail.com';
$setting_email_port_api = '587';
$setting_email_port_encrypt = 'tls';
$setting_number_phone = '(022) 86061128';
$setting_fax_number = '(022) 86061128';
$setting_insta_link = 'https://www.instagram.com/ibf.trader/?hl=id';
$setting_facebook_link = 'https://www.facebook.com/profile.php?id=100064234740634&mibextid=ZbWKwL';
$setting_linkedin_link = 'https://www.linkedin.com/company/pt-international-business-futures/';
$setting_facebook_linksrc = 'https://mobileibftraders.techcrm.net/assets/img/sosmed/fb.png';
$setting_insta_linksrc = 'https://mobileibftraders.techcrm.net/assets/img/sosmed/ig.png';
$setting_linkedin_linksrc = 'https://mobileibftraders.techcrm.net/assets/img/sosmed/linkedin.png';
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = $setting_email_host_api;
    $mail->SMTPAuth   = true;
    $mail->Username   = $setting_email_support_name;
    $mail->Password   = $setting_email_support_password;
    $mail->SMTPSecure = $setting_email_port_encrypt;
    $mail->Port       = $setting_email_port_api;

    //Recipients
    $mail->setFrom($setting_email_support_name, $web_name);
    $mail->addAddress('irawanprasetyo422@gmail.com', 'IRAWAN PRASETYO');
    
    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Real Account Information '.$web_name_full.' '.date('Y-m-d H:i:s');
    
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

                <div style='padding: 10px;text-align: justify;'>
                    <strong>Informasi Data Real Account</strong><br>

                    Hi <strong>IRAWAN PRASETYO, </strong><br>
                    
                    <ul>
                        <li>Login : 80684</li>
                        <li>Password Master : xggO1dc</li>
                        <li>Password Investor : t6wxzop</li>
                    </ul>

                    Terima Kasih.


                    <br><br>
                    Dari Kami,<br>
                    ".$web_name_full." Team Support
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
                        <strong>Support</strong> : <a href='mailto:".$setting_email_support_name."'>".$setting_email_support_name."</a><br>
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
    die("<script>alert('Login dan password account telah berhasil terkirim');location.href = 'home.php?page=member_realacc'</script>");
} catch (Exception $e) {
    //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    die("<script>alert('Login dan password account tidak berhasil terkirim');location.href = 'home.php?page=member_realacc'</script>");
}
?>