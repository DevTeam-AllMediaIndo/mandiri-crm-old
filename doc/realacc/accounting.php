
<?php
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


    $region = 'ap-southeast-1';
    $bucketName = 'allmediaindo-2';
    $folder = 'ccftrader';
    
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
        
        if(isset($_GET['release'])){
            $release = form_input($_GET['release']);
            if($release == 'accept'){
                $EXEC_SQL = mysqli_query($db, '
                    UPDATE tb_dpwd SET
                    tb_dpwd.DPWD_STSACC = -1
                    WHERE tb_dpwd.ID_DPWD = '.$RESULT_QUERY['ID_DPWD'].'
                    AND tb_dpwd.DPWD_STSACC = 0
                ') or die (mysqli_error($db));
                $EXEC_SQL = mysqli_query($db, '
                    UPDATE tb_racc SET
                    tb_racc.ACC_WPCHECK = 5
                    WHERE tb_racc.ID_ACC = '.$RESULT_QUERY['ID_ACC'].'
                ') or die (mysqli_error($db));
                insert_log($RESULT_QUERY['MBR_ID'], 'Accept Accounting');
                die ("<script>location.href = 'home.php?page=member_realacc'</script>");

            } else if($release == 'reject'){
                $EXEC_SQL = mysqli_query($db, '
                    UPDATE tb_racc SET
                    tb_racc.ACC_WPCHECK = 3
                    WHERE tb_racc.ID_ACC = '.$RESULT_QUERY['ID_ACC'].'
                ') or die (mysqli_error($db));
                insert_log($RESULT_QUERY['MBR_ID'], 'Reject Accounting');
                die ("<script>location.href = 'home.php?page=member_realacc'</script>");

            };
        };
?>
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
                                <input type="text" class="form-control" value="<?php echo $RESULT_QUERY['DPWD_VOUCHER']; ?>" readonly required autocomplete="off">
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
                            <input type="text" readonly class="form-control" value="<?php echo $RESULT_QUERY['MBR_NAME']; ?>" required autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">The sum of rupiah :</label>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" value="<?php echo penyebut($RESULT_QUERY['DPWD_AMOUNT']); ?>" readonly class="form-control" required autocomplete="off">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-right">Rp :</label>
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
                                    <?php
                                        if($RESULT_QUERY['ACC_RATE'] == 0){ $rate = 10000;}else{$rate = $RESULT_QUERY['ACC_RATE'];}
                                    ?>
                                    <label>Amount in USD</label>
                                    <input type="text" class="form-control" value="<?php echo number_format($RESULT_QUERY['DPWD_AMOUNT']/$rate, 2); ?>" readonly required autocomplete="off">
                                </div>
                                <div class="col-md-4">
                                    <label>Rate</label>
                                    <input type="text" class="form-control" value="<?php echo number_format($RESULT_QUERY['ACC_RATE'], 0); ?>" readonly name="receipt_rate" required autocomplete="off">
                                </div>
                                <div class="col-md-4">
                                    <label>Amount in IDR</label>
                                    <input type="text" class="form-control" value="<?php echo number_format($RESULT_QUERY['DPWD_AMOUNT'], 0); ?>" readonly required autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            body{ 
                padding: 20px;
            }
            #drop_zone {
                background-color: #FEFEFE;
                border: #C6C5C5 5px dashed;
                width: 100%;
                padding : 60px 0;
            }
            #drop_zone p {
                font-size: 20px;
                text-align: center;
            }
            #btn_upload, #selectfile {
                display: none;
            }
        </style>
        <div class="row">
            <div class="col-md-4">
                <div id="drop_zone">
                    <p>Drop file here</p>
                    <p>Bukti Mutasi</p>
                    <p>
                    <button type="button" id="btn_file_pick" class="btn btn-primary">
                        <span class="glyphicon glyphicon-folder-open"></span> Select File </button>
                    </p>
                    <p id="file_info"></p>
                    <input type="file" id="selectfile" required>
                    <p id="message_info"></p>
                </div>
            </div>
        </div>
        <script>
         var fileobj;
         $(document).ready(function() {
         $("#drop_zone").on("dragover", function(event) {
            event.preventDefault();
            event.stopPropagation();
            return false;
         });
         $("#drop_zone").on("drop", function(event) {
            event.preventDefault();
            event.stopPropagation();
            fileobj = event.originalEvent.dataTransfer.files;
            if (fileobj.length > 0) {
               for (var f = 0; f < fileobj.length; f++) {
               var fname = fileobj[f].name;
               var fsize = fileobj[f].size;
               if (fname.length > 0) {
                  document.getElementById('file_info').innerHTML += "File name : " + fname;
               }
               }
            }
            document.getElementById('selectfile').files = fileobj;
            document.getElementById('btn_upload').style.display = "inline";
         });
         $('#btn_file_pick').click(function() {
            /*normal file pick*/
            document.getElementById('selectfile').click();
            document.getElementById('selectfile').onchange = function() {
               fileobj = document.getElementById('selectfile').files;
               if (fileobj.length > 0) {
               for (var f = 0; f < fileobj.length; f++) {
                  var fname = fileobj[f].name;
                  var fsize = fileobj[f].size;
                  if (fname.length > 0) {
                     document.getElementById('file_info').innerHTML += "File name : " + fname;
                  }
               }
               }
               document.getElementById('btn_upload').style.display = "inline";
            };
         });
         $('#btn_upload').click(function() {
            if (fileobj == "" || fileobj == null) {
               alert("Please select a file");
               return false;
            } else {
               ajax_file_upload(fileobj);
            }
         });
         });

         function ajax_file_upload(file_obj) {
         if (file_obj != undefined) {
            var form_data = new FormData();
            if (fileobj.length > 0) {
               for (var f = 0; f < fileobj.length; f++) {
               form_data.append('upload_file[]', file_obj[f]);
               }
            }
            $.ajax({
               type: 'POST',
               url: 'upload.php',
               contentType: false,
               processData: false,
               data: form_data,
               beforeSend: function(response) {
               $('#message_info').html("Uploading your file, please wait...");
               },
               success: function(response) {
               $('#message_info').html(response);
               alert(response);
               $('#selectfile').val('');
               }
            });
         }
         }

         function bytesToSize(bytes) {
         var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
         if (bytes == 0) return '0 Byte';
         var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
         return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
         }
        </script>
    </div>
    <div class="card-footer text-center">
        <a href="home.php?page=member_realacc_detail&action=detail&x=<?php echo $x ?>&sub_page=accounting&release=accept" class="btn btn-success">Accept</a>
        <a href="home.php?page=member_realacc_detail&action=detail&x=<?php echo $x ?>&sub_page=accounting&release=reject" class="btn btn-danger">Reject</a>
        <a href="pdf/root/trans_deposit_detail.php?x=<?php echo $x ?>" class="btn btn-info">Print</a>
    </div>
</div>
<?php 
    }; 
}else{
    die("<script>alert('Kepada ".$user1["ADM_NAME"].", anda tidak ada akses ke halaman ini');location.href = 'home.php?page=member_realacc'</script>");
};
?>