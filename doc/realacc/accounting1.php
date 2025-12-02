
<?php

require '../vendor/autoload.php';
use Aws\S3\S3Client;
// AWS Info

$s3 = new Aws\S3\S3Client([
    'region'  => $region,
    'version' => 'latest',
    'credentials' => [
        'key'    => $IAM_KEY,
        'secret' => $IAM_SECRET,
    ]
]);	
if($user1["ADM_LEVEL"] == 7 || $user1["ADM_LEVEL"] == 1 || $user1["ADM_LEVEL"] == 6){
	function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}

    $x = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_GET['x']))));

    
    $SQL_QUERY = mysqli_query($db,'
        SELECT 
            *
        FROM tb_member
        JOIN tb_racc
        JOIN tb_dpwd
        JOIN tb_bankadm
        JOIN tb_acccond
        ON (tb_member.MBR_ID = tb_racc.ACC_MBR
        AND tb_member.MBR_ID = tb_dpwd.DPWD_MBR
        AND tb_dpwd.DPWD_BANK = tb_bankadm.ID_BKADM
        AND tb_member.MBR_ID = tb_acccond.ACCCND_MBR
        AND tb_racc.ID_ACC = tb_acccond.ACCCND_ACC
        AND tb_racc.ID_ACC = tb_dpwd.DPWD_RACC)
        WHERE MD5(MD5(ID_ACC)) = "'.$x.'"
        AND tb_dpwd.DPWD_NOTE = "Deposit New Account"
        AND tb_racc.ACC_DERE = 1
        AND tb_racc.ACC_LOGIN = "0"
        AND tb_dpwd.DPWD_STSACC = 0
        AND tb_dpwd.DPWD_STS = -1
        ORDER BY tb_acccond.ID_ACCCND DESC
        LIMIT 1
    ');
    if(mysqli_num_rows($SQL_QUERY) > 0){
        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        if($RESULT_QUERY['ACC_RATE'] == 0){ $rate = 10000;}else{$rate = $RESULT_QUERY['ACC_RATE'];}
        if($RESULT_QUERY['ACC_RATE'] == 0 && $RESULT_QUERY['ACC_CURR'] == 'IDR'){ 
            $curr_idr = number_format($RESULT_QUERY['DPWD_AMOUNT'], 0);
            $curr = number_format($RESULT_QUERY['DPWD_AMOUNT'], 0);
            $curr_lg = 'IDR';
            $curr_ag = 'Rp';
            $nilai   = $RESULT_QUERY['DPWD_AMOUNT'];
            $rudol   = 'rupiah';
        }else if($RESULT_QUERY['ACC_RATE'] <> 0 && $RESULT_QUERY['ACC_CURR'] == 'IDR'){ 
            $curr_idr = number_format($RESULT_QUERY['DPWD_AMOUNT'], 0);
            $curr = number_format($RESULT_QUERY['DPWD_AMOUNT']/$rate, 0);
            $curr_lg = 'IDR';
            $curr_ag = 'Rp';
            $nilai   = $RESULT_QUERY['DPWD_AMOUNT'];
            $rudol   = 'rupiah';
        }else if($RESULT_QUERY['ACC_RATE'] == 0 && $RESULT_QUERY['ACC_CURR'] == 'USD'){
            $curr_idr = 0;
            $curr = number_format($RESULT_QUERY['DPWD_AMOUNT'], 2);
            $curr_lg = 'USD';
            $curr_ag = '$';
            $rudol   = 'dollar';
        }else{
            $curr_idr = 0;
            $curr = number_format($RESULT_QUERY['DPWD_AMOUNT']/$rate, 2);
            $curr_lg = 'USD';
            $curr_ag = '$';
            $nilai   = $RESULT_QUERY['DPWD_AMOUNT'];
            $rudol   = 'dollar';
        }
        
        if(isset($_POST['accept'])){
            if(isset($_POST['x'])){
                if(isset($_POST['note'])){
                    $x = $_POST['x'];
                    $note = $_POST['note'];
                        
                    $SQL_QUERY2 = mysqli_query($db,'
                        SELECT 
                            *
                        FROM tb_member
                        JOIN tb_racc
                        JOIN tb_dpwd
                        JOIN tb_bankadm
                        JOIN tb_acccond
                        ON (tb_member.MBR_ID = tb_racc.ACC_MBR
                        AND tb_member.MBR_ID = tb_dpwd.DPWD_MBR
                        AND tb_dpwd.DPWD_BANK = tb_bankadm.ID_BKADM
                        AND tb_member.MBR_ID = tb_acccond.ACCCND_MBR
                        AND tb_racc.ID_ACC = tb_acccond.ACCCND_ACC
                        AND tb_racc.ID_ACC = tb_dpwd.DPWD_RACC)
                        WHERE MD5(MD5(ID_ACC)) = "'.$x.'"
                        AND tb_dpwd.DPWD_NOTE = "Deposit New Account"
                        AND tb_racc.ACC_DERE = 1
                        AND tb_racc.ACC_LOGIN = "0"
                        AND tb_dpwd.DPWD_STSACC = 0
                        AND tb_dpwd.DPWD_STS = -1
                        ORDER BY tb_acccond.ID_ACCCND DESC
                        LIMIT 1
                    ');
                    if(mysqli_num_rows($SQL_QUERY) > 0){
                        $RESULT_QUERY2 = mysqli_fetch_assoc($SQL_QUERY2);

                        if(isset($_FILES["file_upload"]) && $_FILES["file_upload"]["error"] == 0){
                            $newfilename1 = round(microtime(true));
    
                            $s5_6_doc1_name = $_FILES["file_upload"]["name"];
                            $s5_6_doc1_type = $_FILES["file_upload"]["type"];
                            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
                            $s5_6_doc1_ext = pathinfo($s5_6_doc1_name, PATHINFO_EXTENSION);
                            if(array_key_exists($s5_6_doc1_ext, $allowed)){
                                if(in_array($s5_6_doc1_type, $allowed)){
                                    $s5_6_doc1_new = 'mts_'.$RESULT_QUERY2['MBR_ID'].'_'.round(microtime(true)).'.'.$s5_6_doc1_ext;
                                    if(move_uploaded_file($_FILES["file_upload"]["tmp_name"], "upload/" . $s5_6_doc1_new)){
                                        $s5_6_doc1_Path = 'upload/'. $s5_6_doc1_new;
                                        $s5_6_doc1_key = basename($s5_6_doc1_Path);
                                        
                                        try {
                                            $result = $s3->putObject([
                                                'Bucket' => $bucketName,
                                                'Key'    => $folder.'/'.$s5_6_doc1_key,
                                                'Body'   => fopen($s5_6_doc1_Path, 'r'),
                                                'ACL'    => 'public-read', // make file 'public'
                                            ]);
                                            unlink($s5_6_doc1_Path);

                                            //die ("<script>location.href = 'home.php?page=member_realacc'</script>");
                                            
                                        } catch (Aws\S3\Exception\S3Exception $e) {
                                            die ("<script>location.href = 'home.php?page=member_realacc'</script>");
                                        };
                                    } else { die("<script>alert('please try again or contact support3');location.href = 'home.php?page=member_realacc'</script>"); };
                                } else { die("<script>alert('please try again or contact support4');location.href = 'home.php?page=member_realacc'</script>"); };
                            } else { die("<script>alert('please try again, select other picture');location.href = 'home.php?page=member_realacc'</script>"); };
                        } else { $s5_6_doc1_new = ''; }
                        
                        $EXEC_SQL = mysqli_query($db, '
                            UPDATE tb_dpwd SET
                                tb_dpwd.DPWD_STSACC = -1,
                                tb_dpwd.DPWD_PIC_MUTASI = "'.$s5_6_doc1_new.'"
                            WHERE tb_dpwd.ID_DPWD = '.$RESULT_QUERY2['ID_DPWD'].'
                            AND tb_dpwd.DPWD_STSACC = 0
                        ') or die (mysqli_error($db));
                        $EXEC_SQL = mysqli_query($db, '
                            UPDATE tb_racc SET
                            tb_racc.ACC_WPCHECK = 5
                            WHERE tb_racc.ID_ACC = '.$RESULT_QUERY2['ID_ACC'].'
                        ') or die (mysqli_error($db));
                        $INSERT_NOTE = mysqli_query($db,'
                            INSERT INTO tb_note SET
                            tb_note.NOTE_MBR = '.$RESULT_QUERY2['MBR_ID'].',
                            tb_note.NOTE_RACC = '.$RESULT_QUERY2['ID_ACC'].',
                            tb_note.NOTE_DPWD = '.$RESULT_QUERY2['ID_DPWD'].',
                            tb_note.NOTE_ACCDN = '.$RESULT_QUERY2['ID_ACCCND'].',
                            tb_note.NOTE_TYPE = "ACCOUNTING ACCEPT",
                            tb_note.NOTE_NOTE = "'.$note.'",
                            tb_note.NOTE_DATETIME = "'.date('Y-m-d H:i:s').'"
                        ') or die(mysqli_error($db));
                        
                        // Message Telegram
                        $mesg = 'Notif : Margin Receipt Deposit New Account Diterima'.
                        PHP_EOL.'Date : '.date("Y-m-d").
                        PHP_EOL.'Time : '.date("H:i:s");
                        // PHP_EOL.'======== Informasi Margin Receipt Deposit New Account =========='.
                        // PHP_EOL.'Nama : '.$RESULT_QUERY['MBR_NAME'].
                        // PHP_EOL.'Email : '.$RESULT_QUERY['MBR_EMAIL'].
                        // PHP_EOL.'Voucher : '.$RESULT_QUERY['DPWD_VOUCHER'].
                        // PHP_EOL.'Login : '.$RESULT_QUERY['ACCCND_LOGIN'].
                        // PHP_EOL.'Margin : '.$curr_ag.' '.$curr.
                        // PHP_EOL.'Rate : '.$RESULT_QUERY['ACC_RATE'].
                        // PHP_EOL.'Status : Diterima'.
                        // PHP_EOL.'Catatan : '.$note.
                        // PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                        // Message Telegram
                        $mesg_othr = 'Notif : Margin Receipt Deposit New Account Diterima'.
                        PHP_EOL.'Date : '.date("Y-m-d").
                        PHP_EOL.'Time : '.date("H:i:s").
                        PHP_EOL.'==================================================='.
                        PHP_EOL.'             Informasi Margin Receipt Deposit New Account'.
                        PHP_EOL.'==================================================='.
                        PHP_EOL.'Nama : '.$RESULT_QUERY['MBR_NAME'].
                        PHP_EOL.'Email : '.$RESULT_QUERY['MBR_EMAIL'].
                        PHP_EOL.'Voucher : '.$RESULT_QUERY['DPWD_VOUCHER'].
                        PHP_EOL.'Login : '.$RESULT_QUERY['ACCCND_LOGIN'].
                        PHP_EOL.'Margin : '.$curr_ag.' '.$curr.
                        PHP_EOL.'Rate : '.$RESULT_QUERY['ACC_RATE'].
                        PHP_EOL.'Status : Diterima'.
                        PHP_EOL.'Catatan : '.$note.
                        PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                        $request_params_dlr = [
                            'chat_id' => $chat_id_dlr,
                            'text' => $mesg
                        ];
                        http_request('https://api.telegram.org/bot'.$token_dlr.'/sendMessage?'.http_build_query($request_params_dlr));

                        $request_params_accounting = [
                            'chat_id' => $chat_id_accounnting,
                            'text' => $mesg
                        ];
                        http_request('https://api.telegram.org/bot'.$token_accounnting.'/sendMessage?'.http_build_query($request_params_accounting));

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
                        insert_log($RESULT_QUERY2['MBR_ID'], 'Accepted New Deposit Account '.$RESULT_QUERY2['ACC_LOGIN']);
                        die ("<script>location.href = 'home.php?page=member_realacc'</script>");
                    } else { die("<script>alert('please try again or contact support6');location.href = 'home.php?page=member_realacc'</script>"); };
                };
            };
        };
        if(isset($_POST['reject'])){
            if(isset($_POST['reject'])){
                if(isset($_POST['reject'])){
                    $x = $_POST['x'];
                    $note = $_POST['note'];
                        
                    $SQL_QUERY2 = mysqli_query($db,'
                        SELECT 
                            *
                        FROM tb_member
                        JOIN tb_racc
                        JOIN tb_dpwd
                        JOIN tb_bankadm
                        JOIN tb_acccond
                        ON (tb_member.MBR_ID = tb_racc.ACC_MBR
                        AND tb_member.MBR_ID = tb_dpwd.DPWD_MBR
                        AND tb_dpwd.DPWD_BANK = tb_bankadm.ID_BKADM
                        AND tb_member.MBR_ID = tb_acccond.ACCCND_MBR
                        AND tb_racc.ID_ACC = tb_acccond.ACCCND_ACC
                        AND tb_racc.ID_ACC = tb_dpwd.DPWD_RACC)
                        WHERE MD5(MD5(ID_ACC)) = "'.$x.'"
                        AND tb_dpwd.DPWD_NOTE = "Deposit New Account"
                        AND tb_racc.ACC_DERE = 1
                        AND tb_racc.ACC_LOGIN = "0"
                        AND tb_dpwd.DPWD_STSACC = 0
                        AND tb_dpwd.DPWD_STS = -1
                        ORDER BY tb_acccond.ID_ACCCND DESC
                        LIMIT 1
                    ');
                    if(mysqli_num_rows($SQL_QUERY) > 0){
                        $RESULT_QUERY2 = mysqli_fetch_assoc($SQL_QUERY2);

                        $EXEC_SQL = mysqli_query($db, '
                            UPDATE tb_racc SET
                            tb_racc.ACC_WPCHECK = 3
                            WHERE tb_racc.ID_ACC = '.$RESULT_QUERY['ID_ACC'].'
                        ') or die (mysqli_error($db));

                        $INSERT_NOTE = mysqli_query($db,'
                            INSERT INTO tb_note SET
                            tb_note.NOTE_MBR = '.$RESULT_QUERY2['MBR_ID'].',
                            tb_note.NOTE_RACC = '.$RESULT_QUERY2['ID_ACC'].',
                            tb_note.NOTE_DPWD = '.$RESULT_QUERY2['ID_DPWD'].',
                            tb_note.NOTE_ACCDN = '.$RESULT_QUERY2['ID_ACCCND'].',
                            tb_note.NOTE_TYPE = "ACCOUNTING REJECT",
                            tb_note.NOTE_NOTE = "'.$note.'",
                            tb_note.NOTE_DATETIME = "'.date('Y-m-d H:i:s').'"
                        ') or die(mysqli_error($db));

                        // Message Telegram
                        $mesg = 'Notif : Margin Receipt Deposit New Account Ditolak'.
                        PHP_EOL.'Date : '.date("Y-m-d").
                        PHP_EOL.'Time : '.date("H:i:s");
                        // PHP_EOL.'======== Informasi Margin Receipt Deposit New Account =========='.
                        // PHP_EOL.'Nama : '.$RESULT_QUERY['MBR_NAME'].
                        // PHP_EOL.'Email : '.$RESULT_QUERY['MBR_EMAIL'].
                        // PHP_EOL.'Voucher : '.$RESULT_QUERY['DPWD_VOUCHER'].
                        // PHP_EOL.'Login : '.$RESULT_QUERY['ACCCND_LOGIN'].
                        // PHP_EOL.'Margin : '.$curr_ag.' '.$curr.
                        // PHP_EOL.'Rate : '.$RESULT_QUERY['ACC_RATE'].
                        // PHP_EOL.'Status : Ditolak'.
                        // PHP_EOL.'Alasan Ditolak : '.$note.
                        // PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                        // Message Telegram
                        $mesg_othr = 'Notif : Margin Receipt Deposit New Account Ditolak'.
                        PHP_EOL.'Date : '.date("Y-m-d").
                        PHP_EOL.'Time : '.date("H:i:s").
                        PHP_EOL.'==================================================='.
                        PHP_EOL.'             Informasi Margin Receipt Deposit New Account'.
                        PHP_EOL.'==================================================='.
                        PHP_EOL.'Nama : '.$RESULT_QUERY['MBR_NAME'].
                        PHP_EOL.'Email : '.$RESULT_QUERY['MBR_EMAIL'].
                        PHP_EOL.'Voucher : '.$RESULT_QUERY['DPWD_VOUCHER'].
                        PHP_EOL.'Login : '.$RESULT_QUERY['ACCCND_LOGIN'].
                        PHP_EOL.'Margin : '.$curr_ag.' '.$curr.
                        PHP_EOL.'Rate : '.$RESULT_QUERY['ACC_RATE'].
                        PHP_EOL.'Status : Ditolak'.
                        PHP_EOL.'Alasan Ditolak : '.$note.
                        PHP_EOL.'By : '.$user1['ADM_NAME'].'';

                        $request_params_stlmnt = [
                            'chat_id' => $chat_id_stllmnt,
                            'text' => $mesg
                        ];
                        http_request('https://api.telegram.org/bot'.$token_stllmnt.'/sendMessage?'.http_build_query($request_params_stlmnt));

                        $request_params_accounting = [
                            'chat_id' => $chat_id_accounnting,
                            'text' => $mesg
                        ];
                        http_request('https://api.telegram.org/bot'.$token_accounnting.'/sendMessage?'.http_build_query($request_params_accounting));

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
                        insert_log($RESULT_QUERY['MBR_ID'], 'Reject Accounting');
                        die ("<script>location.href = 'home.php?page=member_realacc'</script>");
                    };
                };
            };

        };
?>
<form method="post" enctype="multipart/form-data">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['DPWD_PIC']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['DPWD_PIC']; ?>" width="100%"></a>
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nomer :</label>
                                    <input type="text" class="form-control" value="<?php echo $RESULT_QUERY['DPWD_VOUCHER']; ?>" required autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label>Date :</label>
                                    <input type="text" class="form-control" name="receipt_date"  value="<?php echo date_format(date_create($RESULT_QUERY['DPWD_DATETIME']), 'Y-m-d') ?>" readonly required autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="text-center mb-3"><h5><u><strong>MARGIN RECEIPT</strong></u></h5></div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">A/C. No. :</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" value="<?php echo $RESULT_QUERY['ACCCND_LOGIN']; ?>" readonly required autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Client's Name :</label>
                            <div class="col-md-10">
                                <input type="text" readonly class="form-control" value="<?php echo $RESULT_QUERY['ACC_F_APP_PRIBADI_NAMA']; ?>" required autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">The sum of <?php echo $rudol; ?> :</label>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="text" value="<?php echo penyebut($RESULT_QUERY['DPWD_AMOUNT']); ?>" readonly class="form-control" required autocomplete="off">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-md-4 col-form-label text-right"><?php echo $curr_ag;?> :</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" value="<?php echo number_format($RESULT_QUERY['DPWD_AMOUNT'], 0); ?>" name="receipt_amount_idr" required autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Mode of Payment :</label>
                            <div class="col-md-10">
                                <input type="text" value="Deposit New Account" readonly class="form-control" required autocomplete="off">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Amount in USD</label>
                                        <input type="text" class="form-control" value="<?php echo $curr; ?>" readonly required autocomplete="off">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Rate</label>
                                        <input type="text" class="form-control" value="<?php echo number_format($RESULT_QUERY['ACC_RATE'], 0); ?>" readonly name="receipt_rate" required autocomplete="off">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Amount in IDR</label>
                                        <input type="text" class="form-control" value="<?php echo $curr_idr; ?>" readonly required autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <style>
            .uploader {position:relative; overflow:hidden; width:500px; height:350px; background:#FEFEFE; border:5px dashed #e8e8e8;}
            #filePhoto{
                position:absolute;
                width:500px;
                height:350px;
                top:-50px;
                left:0;
                z-index:2;
                opacity:0;
                cursor:pointer;
            }
            .uploader img{
                position:absolute;
                width:500px;
                height:350px;
                top:-1px;
                left:-1px;
                z-index:1;
                border:none;
            }
            p {
                font-size: 20px;
                text-align: center;
            }
            </style>
            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="uploader" onclick="$('#filePhoto').click()">
                        <p style="margin-top: 140px;">
                            Klik atau drop file bukti mutasi disini
                        </p>
                        <img src=""/>
                        <input type="file" name="file_upload"  id="filePhoto" required>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label text-center">Note:</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value=" "  name="note" required autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <script>
                var imageLoader = document.getElementById('filePhoto');
                    imageLoader.addEventListener('change', handleImage, false);

                function handleImage(e) {
                    var reader = new FileReader();
                    reader.onload = function (event) {
                        
                        $('.uploader img').attr('src',event.target.result);
                    }
                    reader.readAsDataURL(e.target.files[0]);
                }
            </script>
        </div>
        <div class="card-footer text-center">
            <input type="hidden" value="<?php echo $x ?>" name="x">
            <button type="submit" name="accept" class="btn btn-success">Accept</button>
            <button type="submit" name="reject" class="btn btn-danger">Reject</button>
            <a href="pdf/root/trans_deposit_detail.php?x=<?php echo $x ?>" class="btn btn-info">Print</a>
        </div>
    </div>
</form>
<?php 
    }; 
}else{
    die("<script>alert('Kepada ".$user1["ADM_NAME"].", anda tidak ada akses ke halaman ini');location.href = 'home.php?page=member_realacc'</script>");
};
?>