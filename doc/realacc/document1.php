<?php

    if(isset($_GET['x'])){
        $id = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_GET['x']))));
        
        $SQL_QUERY = mysqli_query($db, '
            SELECT 
                *,
                IFNULL((
                    SELECT tb_ib.IB_NAME
                    FROM tb_ib
                    JOIN tb_acccond
                    ON(tb_acccond.ACCCND_IB = tb_ib.IB_ID)
                    WHERE tb_acccond.ACCCND_MBR = tb_member.MBR_ID
                    AND tb_acccond.ACCCND_ACC = tb_racc.ID_ACC
                ),"-" ) AS IB_NAME,
                IFNULL((
                    SELECT tb_ib.IB_CODE
                    FROM tb_ib
                    JOIN tb_acccond
                    ON(tb_acccond.ACCCND_IB = tb_ib.IB_ID)
                    WHERE tb_acccond.ACCCND_MBR = tb_member.MBR_ID
                    LIMIT 1
                ),"-" ) AS IB_CODE,
                IFNULL((
                    SELECT tb_dpwd.DPWD_AMOUNT
                    FROM tb_dpwd
                    WHERE tb_dpwd.DPWD_MBR = tb_member.MBR_ID
                    AND tb_dpwd.DPWD_NOTE = "Deposit New Account"
                    LIMIT 1
                ), 0) AS DPWD_AMOUNT,
                IFNULL((
                    SELECT tb_dpwd.DPWD_PIC
                    FROM tb_dpwd
                    WHERE tb_dpwd.DPWD_MBR = tb_member.MBR_ID
                    AND tb_dpwd.DPWD_NOTE = "Deposit New Account"
                    LIMIT 1
                ), 0) AS DPWD_PIC,
                tb_racc.ACC_F_APP_PRIBADI_TMPTLHR AS MBR_NAME
            FROM tb_member
            JOIN tb_racc
            ON (tb_member.MBR_ID = tb_racc.ACC_MBR)
            WHERE MD5(MD5(ID_ACC)) = "'.$id.'"
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
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header font-weight-bold">Summary</div>
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
                                        if($RESULT_QUERY['MBR_NAME'] == '' ||$RESULT_QUERY['MBR_NAME'] == '-') {
                                            echo '-';
                                        } else {
                                            echo $RESULT_QUERY['MBR_NAME'];
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
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-3 mb-3 text-center">
                            <div>
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_IMG'] == ''|| $RESULT_QUERY['ACC_F_APP_FILE_IMG'] == '-' ){ ?>
                                    <img src="assets/img/unknown-file.png" width="100%">
                                <?php } else { ?>
                                    <img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG']; ?>" width="75%">
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
                                    <img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_FOTO']; ?>" width="75%">
                                    <hr>
                                <?php }; ?>
                                <strong><u>Foto Terbaru</u></strong>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 text-center">
                            <div>
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_ID'] == '' || $RESULT_QUERY['ACC_F_APP_FILE_ID'] == '-' ){ ?>
                                    <img src="assets/img/unknown-file.png" width="100%">
                                <?php } else { ?>
                                    <img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_ID']; ?>" width="75%">
                                    <hr>
                                <?php }; ?>
                                <strong><u>Foto Identitas</u></strong>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3 text-center">
                            
                            <div>
                                <img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['DPWD_PIC']; ?>" width="75%">
                                <hr>
                                <strong><u>Deposit New Account</u></strong>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 mb-3 text-center">
                        </div>
                        <div class="col-sm-2 mb-3 text-center">
                            <div>
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
                        <div class="col-md-3 mb-3 text-center">
                            <div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header font-weight-bold">Aggrement</div>
            <div class="card-body">
                <div class="table-responsive">
                
                    <table class="table table-striped table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td>1. </td>
                                <td>Formulir Nomor : 107.PBK.01</td>
                                <td>
                                    Profile Perusahaan<br>
                                    <small><?php echo $web_name_full ?> adalah Perusahaan Pialang yang bergerak di bidang perdagangan kontrak derivatif komoditi, Indeks Saham dan Foreign Exchange.</small>
                                </td>
                                <td style="white-space: nowrap"><a target="_blank" href="<?php echo 'pdf/root/02-profil-perusahaaan-pialang-berjangka.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
                            </tr>
                            <tr>
                                <td>2. </td>
                                <td>Formulir Nomor : 107.PBK.02.1</td>
                                <td>
                                    Pernyataan Telah Melakukan Simulasi perdagangan berjangka komoditi<br>
                                    <small>Calon Nasabah diwajibkan untuk memiliki demo account <?php echo $web_name_full ?> sebagai sarana untuk melakukan simulasi transaksi di <?php echo $web_name_full ?>.</small>
                                </td>
                                <td style="white-space: nowrap"><a target="_blank" href="<?php echo 'pdf/root/03.pernyataan-telah-melakukan-simulasi.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
                            </tr>
                            <tr>
                                <td>3. </td>
                                <td>Formulir Nomor : 107.PBK.02.2</td>
                                <td>
                                    Pernyataan telah berpengalaman melaksanakan transaksi perdagangan berjangka komoditi<br>
                                    <small>Dalam hal calon nasabah telah berpengalaman dalam melaksanakan transaksi dalam Perdagangan Berjangka Komoditi, Nasabah memberikan pernyataan dengan Surat Pernyataan Telah Berpengalaman Melaksanakan Transaksi Perdagangan Berjangka Komoditi.</small>
                                </td>
                                <td style="white-space: nowrap"><a target="_blank" href="<?php echo 'pdf/root/04.pernyataan-pengalaman-transaksi.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
                            </tr>
                            <tr>
                                <td>4. </td>
                                <td>Formulir Nomor : 107.PBK.03</td>
                                <td>
                                    Aplikasi Pembukaan Rekening Transaksi secara Elektronik On-line<br>
                                    <small>Seluruh data isian dalam Aplikasi Pembukaan Rekening Transaksi Secara Elektronik On-line Dalam Sistem Perdagangan Alternatif wajib di isi sendiri oleh Nasabah, dan Nasabah bertanggung jawab atas kebenaran informasi yang diberikan dalam mengisi dokumen ini.</small>
                                </td>
                                <td style="white-space: nowrap"><a target="_blank" href="<?php echo 'pdf/root/05.aplikasi-pembukaan-rekening.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
                            </tr>
                            <tr>
                                <td>5. </td>
                                <td>
                                    <?php if($RESULT_QUERY['ACC_TYPE'] == 1){ ?>
                                        Formulir Nomor : 107.PBK.04.1
                                    <?php } else if($RESULT_QUERY['ACC_TYPE'] == 2){ ?>
                                        Formulir Nomor : 107.PBK.04.2
                                    <?php } ?>
                                </td>
                                <td>
                                    Document pemberitahuan adanya resiko<br>
                                    <?php if($RESULT_QUERY['ACC_TYPE'] == 1){ ?>
                                        <small>Maksud dokumen ini adalah memberitahukan bahwa kemungkinan kerugian atau keuntungan dalam perdagangan Kontrak Berjangka bisa mencapai jumlah yang sangat besar. Oleh karena itu, Anda harus berhati-hati dalam memutuskan untuk melakukan transaksi, apakah kondisi keuangan Anda mencukupi.</small>
                                    <?php } else if($RESULT_QUERY['ACC_TYPE'] == 2){ ?>
                                        <small>Maksud dokumen ini adalah memberitahukan bahwa kemungkinan kerugian atau keuntungan dalam perdagangan Kontrak derifatif bisa mencapai jumlah yang sangat besar. Oleh karena itu, Anda harus berhati-hati dalam memutuskan untuk melakukan transaksi, apakah kondisi keuangan Anda mencukupi.</small>
                                    <?php } ?><br>
                                    
                                </td>
                                <td style="white-space: nowrap"><a target="_blank" href="<?php echo 'pdf/root/06.dokumen-pemberitahuan-adanya-resiko.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
                            </tr>
                            <tr>
                                <td>6. </td>
                                <td>
                                    <?php if($RESULT_QUERY['ACC_TYPE'] == 1){ ?>
                                        Formulir Nomor : 107.PBK.05.1
                                    <?php } else if($RESULT_QUERY['ACC_TYPE'] == 2){ ?>
                                        Formulir Nomor : 107.PBK.05.2
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if($RESULT_QUERY['ACC_TYPE'] == 1){ ?>
                                        Perjanjian pemberian amanat secara elektronik on-line untuk transaksi kontrak berjangka
                                    <?php } else if($RESULT_QUERY['ACC_TYPE'] == 2){ ?>
                                        Perjanjian pemberian amanat secara elektronik on-line untuk transaksi kontrak derifatif
                                    <?php } ?><br>
                                    <small>Perjanjian kontrak berjangka dan sepakat untuk mengadakan Perjanjian Pemberian Amanat untuk melakukan transaksi penjualan maupun pembelian Kontrak</small>
                                </td>
                                <td style="white-space: nowrap"><a target="_blank" href="<?php echo 'pdf/root/07.perjanjian-pemberian-amanat.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
                            </tr>
                            <tr>
                                <td>7. </td>
                                <td>Formulir Nomor : 107.PBK.06</td>
                                <td>
                                    Peraturan Perdagangan (Trading Rules)<br>
                                    <small>Peraturan Perdagangan (Trading Rules) dalam siste, aplikasi penerimaan nasabah secara elektronik On-Line</small>
                                </td>
                                <td style="white-space: nowrap"><a target="_blank" href="<?php echo 'pdf/root/08.trading-rules.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
                            </tr>
                            <tr>
                                <td>8. </td>
                                <td>Formulir Nomor : 107.PBK.07</td>
                                <td>
                                    Pernyataan bertanggung jawab<br>
                                    <small>Pernyataan bertanggung jawab atas kode akses transaksi nasabah(Personal Access Password)</small>
                                </td>
                                <td style="white-space: nowrap"><a target="_blank" href="<?php echo 'pdf/root/09.pernyataan-bertanggung-jawab-atas-kode-transaksi.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }; ?>