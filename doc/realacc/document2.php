<?php

require '../vendor/autoload.php';
use Aws\S3\S3Client;

    if(isset($_GET['x'])){
        $id = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_GET['x']))));
        
        $region = 'ap-southeast-1';
        $bucketName = 'allmediaindo-2';
        $folder = 'ccftrader';
        $IAM_KEY = 'AKIASPLPQWHJMMXY2KPR';
        $IAM_SECRET = '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';

        $s3 = new Aws\S3\S3Client([
            'region'  => $region,
            'version' => 'latest',
            'credentials' => [
                'key'    => $IAM_KEY,
                'secret' => $IAM_SECRET,
            ]
        ]);	

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
            ORDER BY tb_dpwd.ID_DPWD DESC, tb_acccond.ID_ACCCND DESC
            LIMIT 1
        ');
        if(mysqli_num_rows($SQL_QUERY) > 0){
            $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        };

        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png");
        if(isset($_POST['submit_editor_pic'])){
            if(isset($_POST['field_name'])){
                if(isset($_FILES["file_pic"]) && $_FILES["file_pic"]["error"] == 0){
                    $field_name = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_POST['field_name']))));

                    $file_pic_name = $_FILES["file_pic"]["name"];
                    $file_pic_type = $_FILES["file_pic"]["type"];
                    $file_pic_size = $_FILES["file_pic"]["size"];
                    
                    $file_pic_ext = pathinfo($file_pic_name, PATHINFO_EXTENSION);
                    
                    if($file_pic_size < 5000000) {
                        if(array_key_exists($file_pic_ext, $allowed)){
                            if(in_array($file_pic_type, $allowed)){
                                
                                if($field_name == 'ACC_F_APP_FILE_IMG'){
                                    $file_pic_new = 'doc1_';
                                } else if($field_name == 'ACC_F_APP_FILE_FOTO'){
                                    $file_pic_new = 'self_';
                                } else if($field_name == 'ACC_F_APP_FILE_ID'){
                                    $file_pic_new = 'id_';
                                } else if($field_name == 'ACC_F_APP_FILE_IMG2'){
                                    $file_pic_new = 'doc2_';
                                };
                                
                                if(move_uploaded_file($_FILES["file_pic"]["tmp_name"], "upload/" . $file_pic_new)){

                                    if($field_name == 'ACC_F_APP_FILE_IMG' || $field_name == 'ACC_F_APP_FILE_FOTO' || $field_name == 'ACC_F_APP_FILE_ID' || $field_name == 'ACC_F_APP_FILE_IMG2'){
                                        
                                        $file_pic_Path = 'upload/'. $file_pic_new;
                                        $file_pic_key = basename($file_pic_Path);
                                        
                                        try {
                                            $result = $s3->putObject([
                                                'Bucket' => $bucketName,
                                                'Key'    => $folder.'/'.$file_pic_key,
                                                'Body'   => fopen($file_pic_Path, 'r'),
                                                'ACL'    => 'public-read', // make file 'public'
                                            ]);

                                            mysqli_query($db, '
                                                UPDATE tb_racc SET
                                                tb_racc.'.$field_name.' = "'.$file_pic_new.''.$RESULT_QUERY['MBR_ID'].'_'.round(microtime(true)).'.'.$file_pic_ext.'"
                                                WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                                            ') or die (mysqli_error($db));
                                            unlink($file_pic_Path);
                                            //die ("<script>location.href = 'home.php?page=member_realacc'</script>");
                                            
                                        } catch (Aws\S3\Exception\S3Exception $e) {
                                            die ("<script>location.href = 'home.php?page=member_realacc'</script>");
                                        };
                                    };
                                };
                            };
                        };
                    };
                };
            };
        };
        if(isset($_POST['submit_editor'])){
            if(isset($_POST['field_name'])){
                if(isset($_POST['field_value'])){
                    $field_name = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_POST['field_name']))));
                    $field_value = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_POST['field_value']))));

                    if($field_value == 'ACC_F_APP_PRIBADI_ZIP' || $field_value == 'ACC_F_APP_DRRT_ZIP'){
                        mysqli_query($db,'
                            UPDATE tb_racc SET
                            tb_racc.'.$field_name.' = '.$field_value.'
                            WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                        ') or die(mysqli_error($db));
                        die("<script>alert('success edit data');location.href = 'home.php?page=member_realacc_detail&x=".$id."&sub_page=document2'</script>");  
                    } else {
                        mysqli_query($db,'
                            UPDATE tb_racc SET
                            tb_racc.'.$field_name.' = "'.$field_value.'"
                            WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id.'"
                        ') or die(mysqli_error($db));
                        die("<script>alert('success edit data');location.href = 'home.php?page=member_realacc_detail&x=".$id."&sub_page=document2'</script>");  
                    };
                }
            }
        }
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
                                <td>&nbsp;:&nbsp;<?php echo $RESULT_QUERY['ACC_F_APP_PRIBADI_NAMA']; ?></td>
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
                                <td>&nbsp;:&nbsp;<?php echo $RESULT_QUERY['ACC_F_APP_PRIBADI_HP']; ?></td>
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
                        <div class="col-md-3 mb-3 text-center">
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
                        <div class="col-md-3 mb-3 text-center">
                            
                            <div>
                                <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['DPWD_PIC']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RESULT_QUERY['DPWD_PIC']; ?>" width="75%"></a>
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
        <div class="card mb-3">
            <div class="card-header font-weight-bold">Editor</div>
            <form method="post">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <select class="form-control" name="field_name" required>
                                <optgroup label="Data Pribadi">
                                    <option value="ACC_F_APP_PRIBADI_NAMA">Nama</option>
                                    <option value="ACC_F_APP_PRIBADI_TMPTLHR">Tempat Lahir</option>
                                    <option value="ACC_F_APP_PRIBADI_ID">No Identitas</option>
                                    <option value="ACC_F_APP_PRIBADI_ZIP">ZIP Code</option>
                                    <option value="ACC_F_APP_PRIBADI_NPWP">No NPWP</option>
                                </optgroup>
                                <optgroup label="Kontak Darurat">
                                    <option value="ACC_F_APP_DRRT_NAMA">Nama Kontak Darurat</option>
                                    <option value="ACC_F_APP_DRRT_ALAMAT">Alamat Kontak Darurat</option>
                                    <option value="ACC_F_APP_DRRT_ZIP">Kode Pos Kontak Darurat</option>
                                    <option value="ACC_F_APP_DRRT_TLP">Nomor Telephone Kontak Darurat</option>
                                    <option value="ACC_F_APP_DRRT_HUB">Status Hubungan Kontak Darurat</option>
                                </optgroup>
                                <optgroup label="Pekerjaan">
                                    <option value="ACC_F_APP_KRJ_NAMA">Nama Perusahaan</option>
                                    <option value="ACC_F_APP_KRJ_BDNG">Bidang Usaha</option>
                                    <option value="ACC_F_APP_KRJ_LAMA">Lama Kerja</option>
                                    <option value="ACC_F_APP_KRJ_LAMASBLM">Lama Kerja Sebelumnya</option>
                                    <option value="ACC_F_APP_KRJ_ALAMAT">Alamat Kerja</option>
                                    <option value="ACC_F_APP_KRJ_ZIP">Kode Pos</option>
                                    <option value="ACC_F_APP_KRJ_TLP">Nomer Telephone Kantor</option>
                                    <option value="ACC_F_APP_KRJ_FAX">Nomer Faksimili Kantor</option>
                                </optgroup>
                                <optgroup label="Kekayaan">
                                    <option value="ACC_F_APP_KEKYAN_RMHLKS">Lokasi Rumah</option>
                                    <option value="ACC_F_APP_KEKYAN_NJOP">NJOP</option>
                                    <option value="ACC_F_APP_KEKYAN_DPST">Deposit</option>
                                    <option value="ACC_F_APP_KEKYAN_NILAI">Jumlah Kekayaan</option>
                                </optgroup>
                                <optgroup label="Bank">
                                    <option value="ACC_F_APP_BK_1_CBNG">Nama Cabang Bank 1</option>
                                    <option value="ACC_F_APP_BK_1_ACC">Nomer Rekening 1</option>
                                    <option value="ACC_F_APP_BK_1_TLP">Nomer Telephone 1</option>
                                    <option value="ACC_F_APP_BK_2_CBNG">Nama Cabang Bank ke 2</option>
                                    <option value="ACC_F_APP_BK_2_ACC">Nomer Rekening Bank ke 2</option>
                                    <option value="ACC_F_APP_BK_2_TLP">Nomer Telephone Bank ke  2</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" required name="field_value">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" name="submit_editor">Edit Data</button>
                </div>
            </form>
        </div>
        <div class="card mb-3">
            <div class="card-header font-weight-bold">Editor Picture</div>
            <form method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <select class="form-control" name="field_name" required>
                                <option value="ACC_F_APP_FILE_IMG">Dokument Pendukung</option>
                                <option value="ACC_F_APP_FILE_FOTO">Foto Terbaru</option>
                                <option value="ACC_F_APP_FILE_ID">Foto Identitas</option>
                                <option value="ACC_F_APP_FILE_IMG2">Dokumen Pendukung Lainya</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="file" class="form-control" name="file_pic" accept=".png, .jpg, .jpeg" required>
                        </div><br>
                        <small style="color:red;">Maksimal ukuran file 5 MB.</small><br>
                        <small style="color:red;">ext hanya .png, .jpg, .jpeg.</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" name="submit_editor_pic">Edit Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php }; ?>