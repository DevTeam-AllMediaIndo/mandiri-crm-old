<?php
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
                            *
                        FROM tb_member
                        JOIN tb_racc
                        ON (tb_member.MBR_ID = tb_racc.ACC_MBR)
                        WHERE MD5(MD5(ID_ACC)) = "'.$id.'"
                        LIMIT 1
                    ');
                    if(mysqli_num_rows($SQL_QUERY) > 0){
                        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
                        if(isset($_POST["cpndng"])){
                            $EXEC_SQL = mysqli_query($db, '
                                UPDATE tb_racc SET
                                    tb_racc.ACC_WPCHECK = 0
                                WHERE tb_racc.ID_ACC = '.$RESULT_QUERY["ID_ACC"].'
                                AND tb_racc.ACC_WPCHECK = -5
                            ');
                            $msg = (mysqli_affected_rows($db) > 0) ? 'Berhasil Cancel Pending' : 'Gagal cancel pending';
                            insert_log($RESULT_QUERY['MBR_ID'], 'Cancel Pending '.$id);
                            die("<script>alert('Success Cancel');location.href = 'home.php?page=member_realacc'</script>");
                        }
                    };
                    
                // };
            // };

            $region = 'ap-southeast-1';
            $bucketName = 'allmediaindo-2';
            $folder = 'mandirifx';
            $IAM_KEY = 'AKIASPLPQWHJMMXY2KPR';
            $IAM_SECRET = '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';

        };
?>
<div class="row">
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header font-weight-bold">Aggrement</div>
            <div class="card-body">
                <?php require_once(__DIR__ . "/agreement.php") ?>
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
                            <td>No.NPWP</td>
                            <td>&nbsp;:&nbsp;
                                <?php
                                    if($RESULT_QUERY['ACC_F_APP_PRIBADI_NPWP'] == '' ||$RESULT_QUERY['ACC_F_APP_PRIBADI_NPWP'] == '-') {
                                        echo '-';
                                    } else { echo $RESULT_QUERY['ACC_F_APP_PRIBADI_NPWP']; };
                                ?>
                            </td>
                            <td colspan="3">&nbsp;</td>
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
            <?php if($RESULT_QUERY["ACC_WPCHECK"] == -5){ ?>
                <div class="card-footer">
                    <form method="post">
                        <button type="submit" class="btn btn-warning" name="cpndng">Batal Pending</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php 
    }else{
        die("<script>alert('Kepada ".$user1["ADM_NAME"].", anda tidak ada akses ke halaman ini');location.href = 'home.php?page=member_realacc'</script>");
    };
?>