<?php
    if(isset($_GET['x'])){
        $id = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_GET['x']))));

        $SQL_QUERY = mysqli_query($db, '
            SELECT 
                *
            FROM tb_member
            JOIN tb_racc
            JOIN tb_dpwd
            JOIN tb_bankadm
            JOIN tb_ib
            JOIN tb_acccond
            ON (tb_member.MBR_ID = tb_racc.ACC_MBR
            AND tb_member.MBR_ID = tb_dpwd.DPWD_MBR
            AND tb_dpwd.DPWD_BANK = tb_bankadm.ID_BKADM
            AND tb_acccond.ACCCND_ACC = tb_racc.ID_ACC
            AND tb_acccond.ACCCND_IB = tb_ib.IB_ID)
            WHERE MD5(MD5(ID_ACC)) = "'.$id.'"
            AND tb_dpwd.DPWD_NOTE = "Deposit New Account"
            AND tb_dpwd.DPWD_STS = -1
            AND tb_dpwd.DPWD_STSACC = -1
            ORDER BY tb_acccond.ID_ACCCND DESC
            LIMIT 1
        ');
        if(mysqli_num_rows($SQL_QUERY) > 0){
            $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        };
?>
<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <a target="_blank" href="pdf/root/trans_deposit_detail1.php?x=<?php echo $id ?>" class="btn btn-primary">Margin Receipt</a>
                <a target="_blank" href="pdf/root/12.account-condition.php?x=<?php echo $id ?>" class="btn btn-primary">Account Condition</a>
                <a target="_blank" href="pdf/root/13.bukti-konfirmasi-penerimaan-nasabah.php?x=<?php echo $id ?>" class="btn btn-primary">WP Confirm</a>
                <a target="_blank" href="pdf/root/10.disclosure-statement.php?x=<?php echo $id ?>" class="btn btn-primary">Disclosure Statement</a>
                <a href="javascript:void(0)" id="dwnldall" class="btn btn-info">Download All</a>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header font-weight-bold"></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td>Login</td>
                                <td>&nbsp;:&nbsp;<?php echo $RESULT_QUERY['ACC_LOGIN']; ?></td>
                                <td>Initial Margin</td>
                                <td>&nbsp;:&nbsp;<?php echo number_format($RESULT_QUERY['DPWD_AMOUNT'], 0, '.', ','); ?></td>
                            </tr>
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
                                <td>Introducer Broker</td>
                                <td>&nbsp;:&nbsp;<?php echo $RESULT_QUERY['IB_NAME'].' - '.$RESULT_QUERY['IB_CODE']; ?></td>
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
                                        } else {
                                            echo $RESULT_QUERY['ACC_F_APP_PRIBADI_HP'];
                                        };
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
                                <td>No.NPWP</td>
                                <td>&nbsp;:&nbsp;
                                    <?php
                                        if($RESULT_QUERY['ACC_F_APP_PRIBADI_NPWP'] == '' ||$RESULT_QUERY['ACC_F_APP_PRIBADI_NPWP'] == '-') {
                                            echo '-';
                                        } else { echo $RESULT_QUERY['ACC_F_APP_PRIBADI_NPWP']; };
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
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-3 mb-3 text-center">
                            <div>
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_RKBKCRED'] == ''|| $RESULT_QUERY['ACC_F_APP_FILE_RKBKCRED'] == '-' ){ ?>
                                    <img src="assets/img/unknown-file.png" width="100%">
                                <?php } else { ?>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_RKBKCRED']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_RKBKCRED']; ?>" width="75%"></a>
                                    <hr>
                                <?php }; ?>
                                <strong><u>Rekening Koran Bank / Tagihan Kartu Kredit</u></strong>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 text-center">
                            <div>
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_REKLISTLP'] == ''|| $RESULT_QUERY['ACC_F_APP_FILE_REKLISTLP'] == '-' ){ ?>
                                    <img src="assets/img/unknown-file.png" width="100%">
                                <?php } else { ?>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_REKLISTLP']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_REKLISTLP']; ?>" width="75%"></a>
                                    <hr>
                                <?php }; ?>
                                <strong><u>Rekening Listrik / Telepon</u></strong>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 text-center">
                            <div>
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_IMG'] == ''|| $RESULT_QUERY['ACC_F_APP_FILE_IMG'] == '-' ){ ?>
                                    <img src="assets/img/unknown-file.png" width="100%">
                                <?php } else { ?>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG']; ?>" width="75%"></a>
                                    <hr>
                                <?php }; ?>
                                <strong><u>Dokumen Pendukung</u></strong>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 text-center">
                            <div>
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_FOTO'] == ''|| $RESULT_QUERY['ACC_F_APP_FILE_FOTO'] == '-' ){ ?>
                                    <img src="assets/img/unknown-file.png" width="100%">
                                <?php } else { ?>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_FOTO']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_FOTO']; ?>" width="75%"></a>
                                    <hr>
                                <?php }; ?>
                                <strong><u>Foto Terbaru</u></strong>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3 text-center">
                            <div>
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_ID'] == '' || $RESULT_QUERY['ACC_F_APP_FILE_ID'] == '-' ){ ?>
                                    <img src="assets/img/unknown-file.png" width="100%">
                                <?php } else { ?>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_ID']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_ID']; ?>" width="75%"></a>
                                    <hr>
                                <?php }; ?>
                                <strong><u>Foto Identitas</u></strong>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 text-center">
                            <div>
                                <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['DPWD_PIC']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['DPWD_PIC']; ?>" width="75%"></a>
                                <hr>
                                <strong><u>Deposit New Account</u></strong>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 text-center">
                            <div>
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_IMG2'] == ''|| $RESULT_QUERY['ACC_F_APP_FILE_IMG2'] == '-' ){ ?>
                                    <img src="assets/img/unknown-file.png" width="100%">
                                <?php } else { ?>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG2']; ?>">
                                    <img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG2']; ?>" width="75%"></a>
                                    <hr>
                                <?php }; ?>
                                <strong><u>Dokumen Pendukung Lainya</u></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header font-weight-bold">Aggrement <?= $user1["ADM_LEVEL"] ?></div>
            <div class="card-body">
                <?php require_once(__DIR__ . "/agreement.php") ?>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('dwnldall').addEventListener('click', function(e){
        Array.from(document.querySelectorAll('a[target="_blank"]')).forEach((el, ix) => {
            var dl = document.createElement('a');
            dl.setAttribute('href', el.href);
            dl.setAttribute('target', '_blank');
            dl.setAttribute('download', '');
            setTimeout(() => {
                dl.click();
            }, 500);
            // console.log(dl);
        });
    });
</script>
<?php }; ?>