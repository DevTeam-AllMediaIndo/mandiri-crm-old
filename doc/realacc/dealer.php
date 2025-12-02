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
                tb_acccond.ID_ACCCND,
                tb_racc.ACC_TYPE,
                tb_racc.ACC_RATE,
                tb_member.MBR_ID,
                tb_member.MBR_NAME,
                tb_member.MBR_EMAIL,
                tb_member.MBR_PHONE,
                tb_member.MBR_CITY,
                tb_acccond.ACCCND_LOGIN,
                tb_acccond.ACCCND_AMOUNTMARGIN
            FROM tb_racc
            JOIN tb_member
            JOIN tb_acccond
            ON(tb_racc.ACC_MBR = tb_member.MBR_ID
            AND tb_member.MBR_ID = tb_acccond.ACCCND_MBR
            AND tb_racc.ID_ACC = tb_acccond.ACCCND_ACC)
            WHERE MD5(MD5(tb_racc.ID_ACC)) = '".$x."'
        ") or die(mysqli_error($db));
    
        if(mysqli_num_rows($SQL_QUERY) > 0){
            $RESULT_QUERY  = mysqli_fetch_assoc($SQL_QUERY);
            $MBR_NAME = $RESULT_QUERY['MBR_NAME'];
            $MBR_EMAIL = $RESULT_QUERY['MBR_EMAIL'];
            $MBR_PHONE = $RESULT_QUERY['MBR_PHONE'];
            $MBR_CITY = $RESULT_QUERY['MBR_CITY'];
            $ACCCND_LOGIN = $RESULT_QUERY['ACCCND_LOGIN'];
            $ACCCND_AMOUNTMARGIN = $RESULT_QUERY['ACCCND_AMOUNTMARGIN'];
            $ID_ACCCND = $RESULT_QUERY['ID_ACCCND'];
            $ID_ACC = $RESULT_QUERY['ID_ACC'];
            $MBR_ID = $RESULT_QUERY['MBR_ID'];
        } else {
            $MBR_NAME = '-';
            $MBR_EMAIL = '-';
            $MBR_PHONE = '-';
            $MBR_CITY = '-';
            $ACCCND_LOGIN = '0';
            $ACCCND_AMOUNTMARGIN = 0;
            $ID_ACCCND = 0;
            $ID_ACC = 0;
            $MBR_ID = 0;
        
        };
        $TGL = date('now');
        if(strtolower(date('F', strtotime($TGL))) == strtolower('January')){ $date_month = 'Januari';
        } else if(strtolower(date('F', strtotime($TGL))) == strtolower('February')){ $date_month = 'Februari';
        } else if(strtolower(date('F', strtotime($TGL))) == strtolower('March')){ $date_month = 'Maret';
        } else if(strtolower(date('F', strtotime($TGL))) == strtolower('April')){ $date_month = 'April';
        } else if(strtolower(date('F', strtotime($TGL))) == strtolower('May')){ $date_month = 'Mai';
        } else if(strtolower(date('F', strtotime($TGL))) == strtolower('June')){ $date_month = 'Juni';
        } else if(strtolower(date('F', strtotime($TGL))) == strtolower('July')){ $date_month = 'Juli';
        } else if(strtolower(date('F', strtotime($TGL))) == strtolower('August')){ $date_month = 'Agustus';
        } else if(strtolower(date('F', strtotime($TGL))) == strtolower('September')){ $date_month = 'September';
        } else if(strtolower(date('F', strtotime($TGL))) == strtolower('October')){ $date_month = 'Oktober';
        } else if(strtolower(date('F', strtotime($TGL))) == strtolower('November')){ $date_month = 'November';
        } else if(strtolower(date('F', strtotime($TGL))) == strtolower('December')){ $date_month = 'Desember';
        };
    
        if(isset($_POST['accept'])){
            if(isset($_POST['password'])){
                if(isset($_POST['investor'])){
                    if(isset($_POST['id_acccnd'])){
                        if(isset($_POST['id_acc'])){
                            if(isset($_POST['ib'])){
                                $password = form_input($_POST['password']);
                                $investor = form_input($_POST['investor']);
                                $id_acccnd = form_input($_POST['id_acccnd']);
                                $id_acc = form_input($_POST['id_acc']);
                                $ib = form_input($_POST['ib']);
        
                                mysqli_query($db,"
                                    UPDATE tb_racc SET
                                    tb_racc.ACC_LOGIN = '".$ACCCND_LOGIN."',
                                    tb_racc.ACC_PASS = '-',
                                    tb_racc.ACC_INVESTOR = '-',
                                    tb_racc.ACC_INITIALMARGIN = ".$ACCCND_AMOUNTMARGIN.",
                                    tb_racc.ACC_WPCHECK = 6
                                    WHERE ((tb_racc.ID_ACC)) = ".$id_acc."
                                ")or die(mysqli_error($db));
        
                                mysqli_query($db,"
                                    UPDATE tb_acccond SET
                                    tb_acccond.ACCCND_DATEMARGIN = '".date('Y-m-d H:i:s')."',
                                    tb_acccond.ACCCND_IB = ".$ib.",
                                    tb_acccond.ACCCND_STS = -1
                                    WHERE ((tb_acccond.ID_ACCCND)) = ".$id_acccnd."
                                ")or die(mysqli_error($db));
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
                                    die("<script>alert('Login dan password account telah berhasil terkirim');location.href = 'home.php?page=member_realacc'</script>");
                                } catch (Exception $e) {
                                    //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                    die("<script>alert('Login dan password account tidak berhasil terkirim');location.href = 'home.php?page=member_realacc'</script>");
                                }
                                //die("<script>alert('Success accept');location.href = 'home.php?page=member_realacc'</script>");
                            }
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
    <div class="card">
        <div class="card-header font-weight-bold">
            ACCOUNT CONDITION
            <?php
                if($RESULT_QUERY['ACC_TYPE'] == '' ||$RESULT_QUERY['ACC_TYPE'] == '-') {
                    echo '-';
                }else{
                    if($RESULT_QUERY['ACC_TYPE'] == 1) {
                        echo 'SPA';
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
                <div class="col-md-9"><input type="text" class="form-control" readonly value="<?php echo date('m');?>(<?php echo $date_month ;?>)" required></div>
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
                <div class="col-md-3" style="margin-block: auto;">Introducing Broker</div>
                <div class="col-md-9">
                    <select name="ib" id="" class="form-control" required>
                        <option disabled selected value>Pilih Salah Satu</option>
                        <?php
                            $SQL_QUERY = mysqli_query($db,'
                                SELECT
                                    tb_ib.IB_ID,
                                    tb_ib.IB_NAME,
                                    tb_ib.IB_CODE,
                                    tb_ib.IB_CITY
                                FROM tb_ib
                                WHERE tb_ib.IB_STS = -1
                            ') or die(mysqli_error($db));
                            if(mysqli_num_rows($SQL_QUERY)){
                                while($RESULT_QUERY2 = mysqli_fetch_assoc($SQL_QUERY)){
                        ?>
                        <option value="<?php echo $RESULT_QUERY2['IB_ID'] ?>">
                            <?php echo $RESULT_QUERY2['IB_NAME'] ?>-<?php echo $RESULT_QUERY2['IB_CODE'] ?>-<?php echo $RESULT_QUERY2['IB_CITY'] ?>
                        </option>
                        <?php
                                };
                            };
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3" style="margin-block: auto;">Tanggal Deposit</div>
                <div class="col-md-2"><input type="number" class="form-control" value="<?php echo date('d');?>" name="" required></div>
                <div class="col-md-1 text-right" style="margin-block: auto;">Bulan</div>
                <div class="col-md-4">
                    <select name="" id="" class="form-control" required>
                        <option value="01" <?php if(date('m', strtotime($TGL))== '01'){echo 'selected';}?> >1 (Januari)</option>
                        <option value="02" <?php if(date('m', strtotime($TGL))== '02'){echo 'selected';}?> >2 (Februari)</option>
                        <option value="03" <?php if(date('m', strtotime($TGL))== '03'){echo 'selected';}?> >3 (Maret)</option>
                        <option value="04" <?php if(date('m', strtotime($TGL))== '04'){echo 'selected';}?> >4 (April)</option>
                        <option value="05" <?php if(date('m', strtotime($TGL))== '05'){echo 'selected';}?> >5 (Mei)</option>
                        <option value="06" <?php if(date('m', strtotime($TGL))== '06'){echo 'selected';}?> >6 (Juni)</option>
                        <option value="07" <?php if(date('m', strtotime($TGL))== '07'){echo 'selected';}?> >7 (Juli)</option>
                        <option value="08" <?php if(date('m', strtotime($TGL))== '08'){echo 'selected';}?> >8 (Agustus)</option>
                        <option value="09" <?php if(date('m', strtotime($TGL))== '09'){echo 'selected';}?> >9 (September)</option>
                        <option value="10" <?php if(date('m', strtotime($TGL))== '10'){echo 'selected';}?> >10 (Oktober)</option>
                        <option value="11" <?php if(date('m', strtotime($TGL))== '11'){echo 'selected';}?> >11 (November)</option>
                        <option value="12" <?php if(date('m', strtotime($TGL))== '12'){echo 'selected';}?> >12 (Desember)</option>
                    </select>
                </div>
                <div class="col-md-1 text-right" style="margin-block: auto;">Tahun</div>
                <div class="col-md-1"><input type="number" class="form-control" value="<?php echo date('Y');?>" name="" required></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3" style="margin-block: auto;">Nilai Margin</div>
                <div class="col-md-1" style="margin-block: auto;">IDR</div>
                <div class="col-md-3" style="margin-block: auto;">
                    <input type="text" class="form-control" name="initial_margin" id="rupiah" value="Rp. <?php echo number_format($ACCCND_AMOUNTMARGIN, 0, ',', '.') ?>" readonly required>
                </div>
                <div class="col-md-1 text-right" style="margin-block: auto;">Fixed Rate</div>
                <div class="col-md-3" style="margin-block: auto;">
                    <select name="" id="" class="form-control text-center">
                        <option value="10.000" <?php if($RESULT_QUERY['ACC_RATE'] == '10000'){echo 'selected';}?>>10.000</option>
                        <option value="12.000" <?php if($RESULT_QUERY['ACC_RATE'] == '12000'){echo 'selected';}?>>12.000</option>
                        <option value="14.000" <?php if($RESULT_QUERY['ACC_RATE'] == '14000'){echo 'selected';}?>>14.000</option>
                        <option value="Custom" <?php if($RESULT_QUERY['ACC_RATE'] == '0'){echo 'selected';}?>>Custom</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-success" name="accept">Accept</button>
            <a class="btn btn-danger" href="home.php?page=member_realacc">Reject</a>
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