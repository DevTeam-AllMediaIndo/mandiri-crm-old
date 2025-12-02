<?php

use Aws\S3\S3Client;
    if(isset($_GET['x'])){
        $x = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_GET['x']))));
        
        if(isset($_GET['action'])){
            $action = mysqli_real_escape_string($db, stripslashes(strip_tags($_GET["action"])));
    
            if($action == 'verification'){
                $id = mysqli_real_escape_string($db, stripslashes(strip_tags($_GET["x"])));
                
                $SQL_QUERY = mysqli_query($db, '
                    SELECT MBR_STS
                    FROM tb_member
                    WHERE MD5(MD5(MBR_ID)) = "'.$id.'"
                    LIMIT 1
                ');
                if(mysqli_num_rows($SQL_QUERY) > 0) {
                    $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
                    if($RESULT_QUERY['MBR_STS'] == 0 || $RESULT_QUERY['MBR_STS'] == 1){
                        $MBR_STS = -1;
                        $EXEC_SQL = mysqli_query($db, '
                            UPDATE tb_member SET
                            tb_member.MBR_STS = "'.$MBR_STS.'"
                            WHERE MD5(MD5(tb_member.MBR_ID)) = "'.$id.'"
                            AND tb_member.MBR_STS = "'.$RESULT_QUERY['MBR_STS'].'"
                        ') or die (mysqli_error($db));
                        die ("<script>alert('success change status user');location.href = 'home.php?page=member_user_detail&x=".$x."'</script>");
                    } else {
                        $MBR_STS = 0;
                        $EXEC_SQL = mysqli_query($db, '
                            UPDATE tb_member SET
                            tb_member.MBR_STS = "'.$MBR_STS.'"
                            WHERE MD5(MD5(tb_member.MBR_ID)) = "'.$id.'"
                            AND tb_member.MBR_STS = "'.$RESULT_QUERY['MBR_STS'].'"
                        ') or die (mysqli_error($db));
                        die ("<script>alert('success change status user');location.href = 'home.php?page=member_user_detail&x=".$x."'</script>");
                    };
                } else { die ("<script>alert('User Unknown');location.href = 'home.php?page=".$login_page."'</script>"); };
            };
        };
        
        $SQL_QUERY1 = mysqli_query($db, '
            SELECT
                tb_member.MBR_STS,
                tb_member.MBR_EMAIL,
                tb_member.MBR_NAME,
                tb_member.MBR_TGLLAHIR,
                tb_member.MBR_ADDRESS,
                tb_member.MBR_ZIP,
                tb_member.MBR_TYPE_IDT,
                tb_member.MBR_NO_IDT,
                tb_member.MBR_DATETIME,
                tb_member.MBR_PHONE,
                tb_member.MBR_IB_CODE
            FROM tb_member
            WHERE MD5(MD5(tb_member.MBR_ID)) = "'.$x.'"
            LIMIT 1
        ');
        if(mysqli_num_rows($SQL_QUERY1) > 0){
            $RESULT_QUERY1 = mysqli_fetch_assoc($SQL_QUERY1);
            $MBR_IB_CODE = $RESULT_QUERY1['MBR_IB_CODE'];
        }else{
            $MBR_IB_CODE = '-';
        };
        $SQL_QUERY2 = mysqli_query($db, '
            SELECT
                tb_racc.ACC_DEVICE,
                tb_racc.ACC_F_APP_PRIBADI_KELAMIN,
                tb_racc.ACC_F_APP_PRIBADI_IBU,
                tb_racc.ACC_F_APP_PRIBADI_STSRMH,
                tb_racc.ACC_F_APP_KRJ_TYPE,
                tb_racc.ACC_F_APP_KRJ_ALAMAT,
                tb_racc.ACC_F_APP_KRJ_BDNG,
                tb_racc.ACC_F_APP_KRJ_JBTN,
                tb_racc.ACC_F_APP_KRJ_LAMA,
                tb_racc.ACC_F_APP_BK_1_NAMA,
                tb_racc.ACC_F_APP_BK_1_CBNG,
                tb_racc.ACC_F_APP_BK_1_ACC,
                tb_racc.ACC_F_APP_BK_1_JENIS,
                tb_racc.ACC_F_APP_KEKYAN_NJOP,
                tb_racc.ACC_F_APP_KEKYAN_DPST,
                tb_racc.ACC_F_APP_KEKYAN,
                tb_racc.ACC_F_APP_KEKYAN_LAIN,
                tb_racc.ACC_F_APP_DRRT_NAMA,
                tb_racc.ACC_F_APP_DRRT_ALAMAT,
                tb_racc.ACC_F_APP_DRRT_ZIP,
                tb_racc.ACC_F_APP_DRRT_TLP,
                tb_racc.ACC_F_APP_FILE_IMG,
                tb_racc.ACC_F_APP_FILE_FOTO,
                tb_racc.ACC_F_APP_FILE_ID,
                tb_racc.ACC_F_APP_FILE_TYPE,
                tb_racc.ACC_F_APP_DRRT_HUB
            FROM tb_racc
            WHERE MD5(MD5(tb_racc.ACC_MBR)) = "'.$x.'"
            AND tb_racc.ACC_TYPE = 1
            AND tb_racc.ACC_DERE = 1
            ORDER BY tb_racc.ID_ACC DESC
            LIMIT 1
        ');
        if(mysqli_num_rows($SQL_QUERY2) > 0){
            $RESULT_QUERY2              = mysqli_fetch_assoc($SQL_QUERY2);
            $ACC_DEVICE                 = $RESULT_QUERY2['ACC_DEVICE'];
            $ACC_F_APP_PRIBADI_KELAMIN  = $RESULT_QUERY2['ACC_F_APP_PRIBADI_KELAMIN'];
            $ACC_F_APP_PRIBADI_IBU      = $RESULT_QUERY2['ACC_F_APP_PRIBADI_IBU'];
            $ACC_F_APP_PRIBADI_STSRMH   = $RESULT_QUERY2['ACC_F_APP_PRIBADI_STSRMH'];
            $ACC_F_APP_KRJ_TYPE         = $RESULT_QUERY2['ACC_F_APP_KRJ_TYPE'];
            $ACC_F_APP_KRJ_ALAMAT       = $RESULT_QUERY2['ACC_F_APP_KRJ_ALAMAT'];
            $ACC_F_APP_KRJ_BDNG         = $RESULT_QUERY2['ACC_F_APP_KRJ_BDNG'];
            $ACC_F_APP_KRJ_JBTN         = $RESULT_QUERY2['ACC_F_APP_KRJ_JBTN'];
            $ACC_F_APP_KRJ_LAMA         = $RESULT_QUERY2['ACC_F_APP_KRJ_LAMA'];
            $ACC_F_APP_BK_1_NAMA        = $RESULT_QUERY2['ACC_F_APP_BK_1_NAMA'];
            $ACC_F_APP_BK_1_CBNG        = $RESULT_QUERY2['ACC_F_APP_BK_1_CBNG'];
            $ACC_F_APP_BK_1_ACC         = $RESULT_QUERY2['ACC_F_APP_BK_1_ACC'];
            $ACC_F_APP_BK_1_JENIS       = $RESULT_QUERY2['ACC_F_APP_BK_1_JENIS'];
            $ACC_F_APP_KEKYAN_NJOP      = $RESULT_QUERY2['ACC_F_APP_KEKYAN_NJOP'];
            $ACC_F_APP_KEKYAN_DPST      = $RESULT_QUERY2['ACC_F_APP_KEKYAN_DPST'];
            $ACC_F_APP_KEKYAN           = $RESULT_QUERY2['ACC_F_APP_KEKYAN'];
            $ACC_F_APP_KEKYAN_LAIN      = $RESULT_QUERY2['ACC_F_APP_KEKYAN_LAIN'];
            $ACC_F_APP_DRRT_NAMA        = $RESULT_QUERY2['ACC_F_APP_DRRT_NAMA'];
            $ACC_F_APP_DRRT_ALAMAT      = $RESULT_QUERY2['ACC_F_APP_DRRT_ALAMAT'];
            $ACC_F_APP_DRRT_ZIP         = $RESULT_QUERY2['ACC_F_APP_DRRT_ZIP'];
            $ACC_F_APP_DRRT_TLP         = $RESULT_QUERY2['ACC_F_APP_DRRT_TLP'];
            $ACC_F_APP_FILE_IMG         = $RESULT_QUERY2['ACC_F_APP_FILE_IMG'];
            $ACC_F_APP_FILE_FOTO        = $RESULT_QUERY2['ACC_F_APP_FILE_FOTO'];
            $ACC_F_APP_FILE_ID          = $RESULT_QUERY2['ACC_F_APP_FILE_ID'];
            $ACC_F_APP_FILE_TYPE        = $RESULT_QUERY2['ACC_F_APP_FILE_TYPE'];
            $ACC_F_APP_DRRT_HUB         = $RESULT_QUERY2['ACC_F_APP_DRRT_HUB'];
        } else {
            $ACC_DEVICE = '-';
            $ACC_F_APP_PRIBADI_KELAMIN = '-';
            $ACC_F_APP_PRIBADI_IBU = '-';
            $ACC_F_APP_PRIBADI_STSRMH = '-';
            $ACC_F_APP_KRJ_TYPE = '-';
            $ACC_F_APP_KRJ_ALAMAT = '-';
            $ACC_F_APP_KRJ_BDNG = '-';
            $ACC_F_APP_KRJ_JBTN = '-';
            $ACC_F_APP_KRJ_LAMA = '-';
            $ACC_F_APP_BK_1_NAMA = '-';
            $ACC_F_APP_BK_1_CBNG = '-';
            $ACC_F_APP_BK_1_ACC = '-';
            $ACC_F_APP_BK_1_JENIS = '-';
            $ACC_F_APP_KEKYAN_NJOP = '-';
            $ACC_F_APP_KEKYAN_DPST = '-';
            $ACC_F_APP_KEKYAN = '-';
            $ACC_F_APP_KEKYAN_LAIN = '-';
            $ACC_F_APP_DRRT_NAMA = '-';
            $ACC_F_APP_DRRT_ALAMAT = '-';
            $ACC_F_APP_DRRT_ZIP = '-';
            $ACC_F_APP_DRRT_TLP = '-';
            $ACC_F_APP_FILE_IMG = '-';
            $ACC_F_APP_FILE_FOTO = '-';
            $ACC_F_APP_FILE_ID = '-';
            $ACC_F_APP_FILE_TYPE = '-';
            $ACC_F_APP_DRRT_HUB = '-';
        };

       
        
    };


    $region = 'ap-southeast-1';
    $bucketName = 'allmediaindo-2';
    $folder = 'ccftrader';
    $IAM_KEY = 'AKIASPLPQWHJMMXY2KPR';
    $IAM_SECRET = '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';


?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Member</li>
        <li class="breadcrumb-item active" aria-current="page">User</li>
        <li class="breadcrumb-item active" aria-current="page">Detail</li>
    </ol>
</nav>
<div class="row">
    <div class="col-md-7">
        <div class="card">
            <form method="post">
                <div class="card-header font-weight-bold">Data user</div>
                <div class="card-body">
                    <table style="width:100%">
                        <h5>Data Pribadi</h5>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Email</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $RESULT_QUERY1["MBR_EMAIL"] ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Nama Lengkap</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $RESULT_QUERY1["MBR_NAME"] ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Tanggal Lahir</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $RESULT_QUERY1["MBR_TGLLAHIR"] ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Alamat</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $RESULT_QUERY1["MBR_ADDRESS"] ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Kode Pos</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $RESULT_QUERY1["MBR_ZIP"] ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Identitas</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $RESULT_QUERY1["MBR_TYPE_IDT"] ?> | <?php echo $RESULT_QUERY1["MBR_NO_IDT"] ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Jenis Kelamin</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $ACC_F_APP_PRIBADI_KELAMIN ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Nomor telepon</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $RESULT_QUERY1["MBR_PHONE"] ?></td>
                        </tr>
                        <br>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Nama Ibu Kandung</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $ACC_F_APP_PRIBADI_IBU ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Status Kepemilikan Rumah</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $ACC_F_APP_PRIBADI_STSRMH ?></td>
                        </tr>
                    </table>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;"></div>
                    <table style="width:100%">
                        <h5>Keterangan Pekerjaan</h5>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Pekerjaan</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $ACC_F_APP_KRJ_TYPE ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Tempat kerja</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $ACC_F_APP_KRJ_ALAMAT ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Bidang Usaha</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $ACC_F_APP_KRJ_BDNG ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Posisi</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $ACC_F_APP_KRJ_JBTN ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Lama Bekerja</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $ACC_F_APP_KRJ_LAMA ?></td>
                        </tr>
                    </table>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;"></div>
                    <table style="width:100%">
                        <h5>Informasi Bank</h5>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Nama Bank</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $ACC_F_APP_BK_1_NAMA ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Cabang</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $ACC_F_APP_BK_1_CBNG ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Nomor Rekening</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $ACC_F_APP_BK_1_ACC ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Jenis Rekening</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $ACC_F_APP_BK_1_JENIS ?></td>
                        </tr>
                    </table>
                    <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;"></div>
                    <table style="width:100%">
                        <h5>Alasan Investasi</h5>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Nilai NJOP</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php if($ACC_F_APP_KEKYAN_NJOP == 0){echo '-';}else{echo number_format($ACC_F_APP_KEKYAN_NJOP, 0);}  ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Jumlah Deposit</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php if($ACC_F_APP_KEKYAN_DPST == 0){echo '-';}else{echo number_format($ACC_F_APP_KEKYAN_DPST, 0);} ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Total Harta</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $ACC_F_APP_KEKYAN ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Harta lainya</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $ACC_F_APP_KEKYAN_LAIN ?></td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="card">
            <div class="card-header font-weight-bold">Keterangan Tambahan</div>
            <div class="card-body">
                <table style="width:100%">
                    <h5>Kontak Darurat</h5>
                    <tr>
                        <td width="45%" style="vertical-align: top;">Nama</td>
                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                        <td style="vertical-align: top;"><?php echo $ACC_F_APP_DRRT_NAMA ?></td>
                    </tr>
                    <tr>
                        <td width="45%" style="vertical-align: top;">Alamat</td>
                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                        <td style="vertical-align: top;"><?php echo $ACC_F_APP_DRRT_ALAMAT ?></td>
                    </tr>
                    <tr>
                        <td width="45%" style="vertical-align: top;">Kode Pos</td>
                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                        <td style="vertical-align: top;"><?php echo $ACC_F_APP_DRRT_ZIP ?></td>
                    </tr>
                    <tr>
                        <td width="45%" style="vertical-align: top;">Nomor Telepon</td>
                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                        <td style="vertical-align: top;"><?php echo $ACC_F_APP_DRRT_TLP ?></td>
                    </tr>
                    <tr>
                        <td width="45%" style="vertical-align: top;">Hubungan</td>
                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                        <td style="vertical-align: top;"><?php echo $ACC_F_APP_DRRT_HUB ?></td>
                    </tr>
                </table>
                <div style="border-top:1px solid black;text-align:left;vertical-align: middle;padding: 10px 0 10px 0;"></div>
                <div class="row">
                    <div class="col-sm-4 text-center">
                        <?php if($ACC_F_APP_FILE_IMG == ''|| $ACC_F_APP_FILE_IMG == '-' ){ ?>
                            <img src="assets/img/unknown-file.png" width="100%">
                        <?php } else { ?>
                            <img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$ACC_F_APP_FILE_IMG; ?>" width="100%">
                            <hr>
                        <?php }; ?>
                        <br>
                        <small>Dokumen Pendukung <br> (<?php echo $ACC_F_APP_FILE_TYPE ?>)</small>
                    </div>
                    <div class="col-sm-4 text-center">
                        <?php if($ACC_F_APP_FILE_FOTO == ''|| $ACC_F_APP_FILE_FOTO == '-' ){ ?>
                            <img src="assets/img/unknown-file.png" width="100%">
                        <?php } else { ?>
                            <img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$ACC_F_APP_FILE_FOTO; ?>" width="100%">
                            <hr>
                        <?php }; ?>
                        <br>
                        <small>Foto Terbaru</small>
                    </div>
                    <div class="col-sm-4 text-center">
                        <?php if($ACC_F_APP_FILE_ID == '' || $ACC_F_APP_FILE_ID == '-' ){ ?>
                            <img src="assets/img/unknown-file.png" width="100%">
                        <?php } else { ?>
                            <img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$ACC_F_APP_FILE_ID; ?>" width="100%">
                            <hr>
                        <?php }; ?>
                        <br>
                        <small>Foto Identitas</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header font-weight-bold">Akun User</div>
            <div class="card-body">
                <table style="width:100%">
                    <h5>Kontak Darurat</h5>
                    <tr>
                        <td width="45%" style="vertical-align: top;">IB Code</td>
                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                        <td style="vertical-align: top;"><?php echo $MBR_IB_CODE?></td>
                    </tr>
                    <tr>
                        <td width="45%" style="vertical-align: top;">Email</td>
                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                        <td style="vertical-align: top;"><?php echo $RESULT_QUERY1["MBR_EMAIL"] ?></td>
                    </tr>
                    <tr>
                        <td width="45%" style="vertical-align: top;">Role</td>
                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                        <td style="vertical-align: top;"><?php echo $RESULT_QUERY1["MBR_DATETIME"] ?></td>
                    </tr>
                    <tr>
                        <td width="45%" style="vertical-align: top;">Status</td>
                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                        <td style="vertical-align: top;">
                            <?php if($RESULT_QUERY1['MBR_STS'] == '-1'){ ?>
                                <a href="#"><span class="badge badge-success">Active</span></a>
                            <?php } elseif($RESULT_QUERY1['MBR_STS'] == '0'){ ?> 
                                <a href="#"><span class="badge badge-warning">Not Active</span></a>
                            <?php } elseif($RESULT_QUERY1['MBR_STS'] == '1'){ ?> 
                                <a href="home.php?page=<?= $login_page ?>&x=<?= $_GET["x"] ?>&action=verification"><span class="badge badge-danger">Blocked</span></a>
                            <?php }; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="45%" style="vertical-align: top;">Tanggal Verif Email</td>
                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                        <td style="vertical-align: top;"><?php echo $RESULT_QUERY1["MBR_DATETIME"] ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>