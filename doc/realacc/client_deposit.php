<?php
    $region = 'ap-southeast-1';
    $bucketName = 'allmediaindo-2';
    $folder = 'ccftrader';
    if($user1["ADM_LEVEL"] == 7 || $user1["ADM_LEVEL"] == 1 || $user1["ADM_LEVEL"] == 3){
        if(isset($_POST['accept'])){
            if(isset($_POST['mbr_id'])){
                if(isset($_POST['id_dpwd'])){
                    if(isset($_POST['id_acc'])){
                        //if(isset($_POST['voucher'])){
                            $mbr_id = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_POST['mbr_id']))));
                            $id_dpwd = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_POST['id_dpwd']))));
                            $id_acc = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_POST['id_acc']))));
                            //$voucher = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_POST['voucher']))));

                            // mysqli_query($db, '
                            //     UPDATE tb_dpwd SET
                            //     #tb_dpwd.DPWD_VOUCHER = "'.$voucher.'",
                            //     tb_dpwd.DPWD_STS = -1,
                            //     tb_dpwd.DPWD_DATETIME = "'.date('Y-m-d H:i:s').'"
                            //     WHERE tb_dpwd.ID_DPWD = '.$id_dpwd.'
                            // ') or die (mysqli_error($db));
                            mysqli_query($db, '
                                UPDATE tb_racc SET
                                tb_racc.ACC_WPCHECK = 3
                                WHERE tb_racc.ID_ACC = '.$id_acc.'
                            ') or die (mysqli_error($db));
                            insert_log($mbr_id, 'Accept Deposit New Account ');
                            die ("<script>location.href = 'home.php?page=member_realacc'</script>");
                        //}
                    }
                }
            }
        }

        if(isset($_POST['reject'])){
            if(isset($_POST['mbr_id'])){
                if(isset($_POST['id_dpwd'])){
                    if(isset($_POST['id_acc'])){
                        if(isset($_POST['voucher'])){
                            $mbr_id = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_POST['mbr_id']))));
                            $id_dpwd = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_POST['id_dpwd']))));
                            $id_acc = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_POST['id_acc']))));
                            $voucher = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_POST['voucher']))));

                            mysqli_query($db, '
                                UPDATE tb_dpwd SET
                                tb_dpwd.DPWD_STS = 1,
                                tb_dpwd.DPWD_DATETIME = "'.date('Y-m-d H:i:s').'"
                                WHERE tb_dpwd.ID_DPWD = '.$id_dpwd.'
                            ') or die (mysqli_error($db));
                            mysqli_query($db, '
                                UPDATE tb_racc SET
                                tb_racc.ACC_WPCHECK = 1
                                WHERE tb_racc.ID_ACC = '.$id_acc.'
                            ') or die (mysqli_error($db));
                            insert_log($mbr_id, 'Reject Deposit New Account ');
                            die ("<script>location.href = 'home.php?page=member_realacc'</script>");
                        }
                    }
                }
            }
        }
        $data_notfound = '
        <div class="alert alert-danger">
            Data Not Found / Already process.
        </div>';
        if(isset($_GET['x'])){
            $x = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_GET['x']))));
                
                $SQL_QUERY = mysqli_query($db, '
                    SELECT 
                        *
                    FROM tb_member
                    JOIN tb_racc
                    JOIN tb_dpwd
                    JOIN tb_bankadm
                    ON (tb_member.MBR_ID = tb_racc.ACC_MBR
                    AND tb_member.MBR_ID = tb_dpwd.DPWD_MBR
                    AND tb_dpwd.DPWD_BANK = tb_bankadm.ID_BKADM)
                    WHERE MD5(MD5(ID_ACC)) = "'.$x.'"
                    AND tb_dpwd.DPWD_NOTE = "Deposit New Account"
                    AND tb_racc.ACC_DERE = 1
                    AND tb_racc.ACC_LOGIN = "0"
                    AND tb_dpwd.DPWD_STS = 0
                    LIMIT 1
                ');
                if(mysqli_num_rows($SQL_QUERY) > 0){
                    $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
?>
<form method="post">
    <input type="hidden" name="mbr_id" class="form-control" readonly value="<?php echo $RESULT_QUERY['MBR_ID'] ?>" required>
    <input type="hidden" name="id_dpwd" class="form-control" readonly value="<?php echo $RESULT_QUERY['ID_DPWD'] ?>" required>
    <input type="hidden" name="id_acc" class="form-control" readonly value="<?php echo $RESULT_QUERY['ID_ACC'] ?>" required>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Proof of Bank Transfer</strong>
                    <hr>
                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['DPWD_PIC']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['DPWD_PIC']; ?>" width="100%"></a>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Bank Recipient</strong>
                            <hr>
                            <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Bank Currency</label>
                                <input type="text" class="form-control" readonly value="<?php echo $RESULT_QUERY['BKADM_CURR'] ?>" required>
                            </div>
                            <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Bank Name</label>
                                <input type="text" class="form-control" readonly value="<?php echo $RESULT_QUERY['BKADM_NAME'] ?>" required>
                            </div>
                            <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Bank Holder</label>
                                <input type="text" class="form-control" readonly value="<?php echo $RESULT_QUERY['BKADM_HOLDER'] ?>" required>
                            </div>
                            <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Bank Account</label>
                                <input type="text" class="form-control" readonly value="<?php echo $RESULT_QUERY['BKADM_ACCOUNT'] ?>" required>
                            </div>
                        </div>
                        <?php
                            if($RESULT_QUERY['DPWD_BANKSRC'] == 1){
                                $ACC_F_APP_BK_NAMA = $RESULT_QUERY['ACC_F_APP_BK_1_NAMA'];
                                $ACC_F_APP_BK_CBNG = $RESULT_QUERY['ACC_F_APP_BK_1_CBNG'];
                                $ACC_F_APP_BK_ACC = $RESULT_QUERY['ACC_F_APP_BK_1_ACC'];
                                $BANK_SELECTED1 = 'Bank Selected';
                                $BANK_SELECTED2 = '';
                            } else {
                                $ACC_F_APP_BK_NAMA = $RESULT_QUERY['ACC_F_APP_BK_2_NAMA'];
                                $ACC_F_APP_BK_CBNG = $RESULT_QUERY['ACC_F_APP_BK_2_CBNG'];
                                $ACC_F_APP_BK_ACC = $RESULT_QUERY['ACC_F_APP_BK_2_ACC'];
                                $BANK_SELECTED1 = '';
                                $BANK_SELECTED2 = 'Bank Selected';
                            }
                        ?>
                        <div class="col-md-3">
                            <strong>Bank Sender 1 <strong><?php echo $BANK_SELECTED1; ?></strong></strong>
                            <hr>
                            <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Full Name</label>
                                <input type="text" class="form-control" readonly value="<?php echo $RESULT_QUERY['MBR_NAME'] ?>" required>
                            </div>
                            <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Bank Name</label>
                                <input type="text" class="form-control" readonly value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_1_NAMA']; ?>" required>
                            </div>
                            <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Bank Branch</label>
                                <input type="text" class="form-control" readonly value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_1_CBNG']; ?>" required>
                            </div>
                            <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Bank Account</label>
                                <input type="text" class="form-control" readonly value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_1_ACC']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <strong>Bank Sender 2 <strong><?php echo $BANK_SELECTED2; ?></strong></strong>
                            <hr>
                            <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Full Name</label>
                                <input type="text" class="form-control" readonly value="<?php echo $RESULT_QUERY['MBR_NAME'] ?>" required>
                            </div>
                            <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Bank Name</label>
                                <input type="text" class="form-control" readonly value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_2_NAMA']; ?>" required>
                            </div>
                            <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Bank Branch</label>
                                <input type="text" class="form-control" readonly value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_2_CBNG']; ?>" required>
                            </div>
                            <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Bank Account</label>
                                <input type="text" class="form-control" readonly value="<?php echo $RESULT_QUERY['ACC_F_APP_BK_2_ACC']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <strong>Detail Transaction</strong>
                            <hr>
                            <!-- <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Voucher Number</label>
                                <input type="text" class="form-control" value="IBF/NC/M/" name="voucher" required>
                            </div> -->
                            <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Date of Deposit</label>
                                <input type="text" class="form-control" readonly value="<?php echo $RESULT_QUERY['DPWD_DATETIME'] ?>" required>
                            </div>
                            <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Deposit IDR</label>
                                <input type="text" class="form-control" readonly value="<?php echo number_format($RESULT_QUERY['DPWD_AMOUNT'], 0) ?>" required>
                            </div>
                            <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Rate</label>
                                <input type="text" class="form-control" readonly value="<?php echo number_format($RESULT_QUERY['ACC_RATE'], 0) ?>" required>
                            </div>
                            <?php
                                if($RESULT_QUERY['ACC_RATE'] == 0){ $rate = 10000;}else{$rate = $RESULT_QUERY['ACC_RATE'];}
                            ?>
                            <div class="input-wrapper mb-3">
                                <label class="label" for="text4b">Deposit USD</label>
                                <input type="text" class="form-control" readonly value="<?php echo number_format($RESULT_QUERY['DPWD_AMOUNT']/$rate, 2) ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" name="accept" class="btn btn-success">Accept</button>
            <button type="submit" name="reject" class="btn btn-danger">Reject</button>
        </div>
    </div>
</form>
<?php    
        } else { echo $data_notfound; };
    } else { echo $data_notfound; };

    }else{
        die("<script>alert('Kepada ".$user1["ADM_NAME"].", anda tidak ada akses ke halaman ini');location.href = 'home.php?page=member_realacc'</script>");
    };
?>