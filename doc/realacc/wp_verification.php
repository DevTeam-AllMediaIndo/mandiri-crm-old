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
                    };
                    
                // };
            // };

            $region = 'ap-southeast-1';
            $bucketName = 'allmediaindo-2';
            $folder = 'ccftrader';
            $IAM_KEY = 'AKIASPLPQWHJMMXY2KPR';
            $IAM_SECRET = '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';

        };
        if(isset($_POST['accept'])){
            $EXEC_SQL = mysqli_query($db,"
                UPDATE tb_racc SET
                    tb_racc.ACC_WPCHECK = 1
                WHERE MD5(MD5(tb_racc.ID_ACC)) = '".$id."'
            ")or die(mysqli_error($db));
            insert_log($RESULT_QUERY['MBR_ID'], 'Accept WP Confirmation ');
            die("<script>location.href = 'home.php?page=member_realacc'</script>");
        }
        if(isset($_POST['reject'])){
            $EXEC_SQL = mysqli_query($db,"
                UPDATE tb_racc SET
                    tb_racc.ACC_MBR = CONCAT(tb_racc.ACC_MBR, 0)
                WHERE MD5(MD5(tb_racc.ID_ACC)) = '".$id."'
            ")or die(mysqli_error($db));
            insert_log($RESULT_QUERY['MBR_ID'], 'Reject WP Confirmation ');
            die("<script>location.href = 'home.php?page=member_realacc'</script>");
        }
?>
<div class="row">
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header font-weight-bold">
                Aggrement
                <!-- <a target="_blank" href="pdf/root/10.disclosure-statement.php?x=<?php echo $id ?>" class="btn btn-primary float-right" style="margin-left : auto;">
                    <i class="fa fa-eye"></i>&nbsp;Disclosour Statement
                </a> -->
            </div>
            <div class="card-body">
                <div class="table-responsive">
                
                    <table class="table table-striped table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td>1. </td>
                                <td>Formulir Nomor : 107.PBK.01</td>
                                <td>
                                    Profile Perusahaan<br>
                                    <small><?php echo $setting_title ?> adalah Perusahaan Pialang yang bergerak di bidang perdagangan kontrak derivatif komoditi, Indeks Saham dan Foreign Exchange.</small>
                                </td>
                                <td style="white-space: nowrap"><a target="_blank" href="<?php echo 'pdf/root/02-profil-perusahaaan-pialang-berjangka.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
                            </tr>
                            <tr>
                                <td>2. </td>
                                <td>Formulir Nomor : 107.PBK.02.1</td>
                                <td>
                                    Pernyataan Telah Melakukan Simulasi perdagangan berjangka komoditi<br>
                                    <small>Calon Nasabah diwajibkan untuk memiliki demo account <?php echo $setting_title ?> sebagai sarana untuk melakukan simulasi transaksi di <?php echo $setting_title ?>.</small>
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
                            <tr>
                                <td>9. </td>
                                <td></td>
                                <td>
                                    Bukti Konfirmasi Penerimaan Nasabah<br>
                                </td>
                                <td style="white-space: nowrap"><a target="_blank" href="<?php echo 'pdf/root/09.pernyataan-bertanggung-jawab-atas-kode-transaksi.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
                                    if($RESULT_QUERY['MBR_NAME'] == '' ||$RESULT_QUERY['MBR_NAME'] == '-') {
                                        echo '-';
                                    } else { echo $RESULT_QUERY['MBR_NAME']; };
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
                                    if($RESULT_QUERY['MBR_PHONE'] == '' ||$RESULT_QUERY['MBR_PHONE'] == '-') {
                                        echo '-';
                                    } else { echo $RESULT_QUERY['MBR_PHONE']; };
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
                            <td>No. NPWP</td>
                            <td>&nbsp;:&nbsp;
                                <?php
                                    if($RESULT_QUERY['ACC_F_APP_PRIBADI_NPWP'] == '' ||$RESULT_QUERY['ACC_F_APP_PRIBADI_NPWP'] == '-') {
                                        echo '-';
                                    } else { echo $RESULT_QUERY['ACC_F_APP_PRIBADI_NPWP']; };
                                ?>
                            </td>
                            <td>Jenis Pekerjaan</td>
                            <td>&nbsp;:&nbsp;
                                <?php
                                    if($RESULT_QUERY['ACC_F_APP_KRJ_NAMA'] == '' ||$RESULT_QUERY['ACC_F_APP_KRJ_NAMA'] == '-') {
                                        echo '-';
                                    } else {
                                        echo $RESULT_QUERY['ACC_F_APP_KRJ_NAMA'].' ('.$RESULT_QUERY['ACC_F_APP_KRJ_BDNG'].')';
                                    };
                                ?>
                            </td>
                        </tr>
                    </table>
                    <div class="row mb-3">
                        <div class="col-md-4 text-center">
                            <div style="border: 1px solid #adadad;border-radius: 10px;padding: 10px;">
                                <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG']; ?>">Dokumen Pendukung</a>
                                <hr>
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_IMG'] == ''|| $RESULT_QUERY['ACC_F_APP_FILE_IMG'] == '-' ){ ?>
                                    <img src="assets/img/unknown-file.png" width="75%">
                                <?php } else { ?>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG']; ?>" width="75%"></a>
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
                    <div class="row mb-3 text-center">
                        <div class="col-md-4 text-center">
                        </div>
                        <div class="col-md-4 text-center">
                            <div style="border: 1px solid #adadad;border-radius: 10px;padding: 10px;">
                                <?php if($RESULT_QUERY['ACC_F_APP_FILE_IMG2'] == '' || $RESULT_QUERY['ACC_F_APP_FILE_IMG2'] == '-' ){ ?>
                                    Dokumen Pendukung Lainya
                                <?php } else { ?>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['ACC_F_APP_FILE_IMG2']; ?>">Dokumen Pendukung Lainya</a>
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <form method="post">
                    <button type="submit" name="accept" class="btn btn-success">Accept</button>
                    <button type="submit" name="reject" class="btn btn-danger">Reject</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php 
    }else{
        die("<script>alert('Kepada ".$user1["ADM_NAME"].", anda tidak ada akses ke halaman ini');location.href = 'home.php?page=member_realacc'</script>");
    };
?>