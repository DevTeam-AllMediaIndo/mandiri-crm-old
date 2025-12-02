
<?php
    date_default_timezone_set("Asia/Jakarta");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../../setting.php';
    require_once 'vendor/autoload.php';
    use Dompdf\Dompdf;
    $dompdf = new Dompdf();
    
    $id_acc = (isset($pdfotpt)) ? form_input($pdfotpt) : ((isset($_GET["x"])) ? form_input($_GET["x"]) : 0);
    
    $SQL_QUERY = mysqli_query($db, '
        SELECT
            tb_racc.ACC_F_APP_PRIBADI_NAMA AS MBR_NAME,
            tb_member.MBR_ZIP,
            tb_racc.ACC_F_APP_PRIBADI_TGLLHR,
            tb_racc.ACC_F_APP_PRIBADI_TMPTLHR,
            tb_racc.ACC_F_APP_PRIBADI_TYPEID,
            tb_racc.ACC_F_APP_PRIBADI_ID,
            tb_member.MBR_ADDRESS,
            tb_member.MBR_ZIP,
            tb_racc.ACC_TYPEACC,
            tb_racc.ACC_RATE,
            tb_racc.ACC_PRODUCT,
            tb_racc.ACC_CHARGE,
            tb_racc.ACC_F_APP_PRIBADI_NPWP,
            tb_racc.ACC_F_APP_PRIBADI_KELAMIN,
            tb_racc.ACC_F_APP_PRIBADI_NAMAISTRI,
            tb_racc.ACC_F_APP_PRIBADI_IBU,
            tb_racc.ACC_F_APP_PRIBADI_STSKAWIN,
            tb_racc.ACC_F_APP_PRIBADI_TLP,
            tb_racc.ACC_F_APP_PRIBADI_FAX,
            tb_racc.ACC_F_APP_PRIBADI_HP,
            tb_racc.ACC_F_APP_PRIBADI_STSRMH,
            tb_racc.ACC_F_APP_TUJUANBUKA,
            tb_racc.ACC_F_APP_PENGINVT,
            tb_racc.ACC_F_PENGLAMAN_PERYT_YA,
            tb_racc.ACC_F_APP_KELGABURSA,
            tb_racc.ACC_F_APP_PAILIT,
            tb_racc.ACC_F_APP_DRRT_NAMA,
            tb_racc.ACC_F_APP_DRRT_ALAMAT,
            tb_racc.ACC_F_APP_DRRT_TLP,
            tb_racc.ACC_F_APP_DRRT_HUB,
            tb_racc.ACC_F_APP_DRRT_ZIP,
            tb_racc.ACC_F_APP_KRJ_TYPE,
            tb_racc.ACC_F_APP_KRJ_NAMA,
            tb_racc.ACC_F_APP_KRJ_BDNG,
            tb_racc.ACC_F_APP_KRJ_JBTN,
            tb_racc.ACC_F_APP_KRJ_LAMA,
            tb_racc.ACC_F_APP_KRJ_LAMASBLM,
            tb_racc.ACC_F_APP_KRJ_ALAMAT,
            tb_racc.ACC_F_APP_KRJ_ZIP,
            tb_racc.ACC_F_APP_KRJ_TLP,
            tb_racc.ACC_F_APP_KRJ_FAX,
            tb_racc.ACC_F_APP_KEKYAN,
            tb_racc.ACC_F_APP_KEKYAN_RMHLKS,
            tb_racc.ACC_F_APP_KEKYAN_NJOP,
            tb_racc.ACC_F_APP_KEKYAN_DPST,
            tb_racc.ACC_F_APP_KEKYAN_NILAI,
            tb_racc.ACC_F_APP_KEKYAN_LAIN,
            tb_racc.ACC_F_APP_BK_1_NAMA,
            tb_racc.ACC_F_APP_BK_1_CBNG,
            tb_racc.ACC_F_APP_BK_1_ACC,
            tb_racc.ACC_F_APP_BK_1_TLP,
            tb_racc.ACC_F_APP_BK_1_JENIS,
            tb_racc.ACC_F_APP_BK_2_NAMA,
            tb_racc.ACC_F_APP_BK_2_CBNG,
            tb_racc.ACC_F_APP_BK_2_ACC,
            tb_racc.ACC_F_APP_BK_2_TLP,
            tb_racc.ACC_F_APP_BK_2_JENIS,
            tb_racc.ACC_F_APP_IP AS ACC_F_APPPEMBUKAAN_IP,
            tb_racc.ACC_F_APP_DATE AS ACC_F_APPPEMBUKAAN_DATE,
            tb_racc.ACC_F_APP_FILE_RKBKCRED,
            tb_racc.ACC_F_APP_FILE_REKLISTLP,
            tb_racc.ACC_F_APP_FILE_IMG,
            tb_racc.ACC_F_APP_FILE_IMG2,
            tb_racc.ACC_F_APP_FILE_FOTO,
            tb_racc.ACC_F_APP_FILE_ID
        FROM tb_racc
        JOIN tb_member
        ON(tb_member.MBR_ID = tb_racc.ACC_MBR)
        WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id_acc.'"
        LIMIT 1
    ');
    if(mysqli_num_rows($SQL_QUERY) > 0){
        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        $MBR_NAME = $RESULT_QUERY['MBR_NAME'];
        $ACC_F_APP_PRIBADI_TGLLHR = $RESULT_QUERY['ACC_F_APP_PRIBADI_TGLLHR'];
        $ACC_F_APP_PRIBADI_TMPTLHR = $RESULT_QUERY['ACC_F_APP_PRIBADI_TMPTLHR'];
        $ACC_F_APP_PRIBADI_TYPEID = $RESULT_QUERY['ACC_F_APP_PRIBADI_TYPEID'];
        $ACC_F_APP_PRIBADI_ID = $RESULT_QUERY['ACC_F_APP_PRIBADI_ID'];
        $MBR_ADDRESS = $RESULT_QUERY['MBR_ADDRESS'];
        $MBR_ZIP = $RESULT_QUERY['MBR_ZIP'];
        $ACC_TYPEACC = $RESULT_QUERY['ACC_TYPEACC'];
        $ACC_RATE = $RESULT_QUERY['ACC_RATE'];
        $ACC_PRODUCT = $RESULT_QUERY['ACC_PRODUCT'];
        $ACC_CHARGE = $RESULT_QUERY['ACC_CHARGE'];
        $ACC_F_APP_PRIBADI_NPWP = $RESULT_QUERY['ACC_F_APP_PRIBADI_NPWP'];
        $ACC_F_APP_PRIBADI_KELAMIN = $RESULT_QUERY['ACC_F_APP_PRIBADI_KELAMIN'];
        $ACC_F_APP_PRIBADI_NAMAISTRI = $RESULT_QUERY['ACC_F_APP_PRIBADI_NAMAISTRI'];
        $ACC_F_APP_PRIBADI_IBU = $RESULT_QUERY['ACC_F_APP_PRIBADI_IBU'];
        $ACC_F_APP_PRIBADI_STSKAWIN = $RESULT_QUERY['ACC_F_APP_PRIBADI_STSKAWIN'];
        $ACC_F_APP_PRIBADI_TLP = $RESULT_QUERY['ACC_F_APP_PRIBADI_TLP'];
        $ACC_F_APP_PRIBADI_FAX = $RESULT_QUERY['ACC_F_APP_PRIBADI_FAX'];
        $ACC_F_APP_PRIBADI_HP = $RESULT_QUERY['ACC_F_APP_PRIBADI_HP'];
        $ACC_F_APP_PRIBADI_STSRMH = $RESULT_QUERY['ACC_F_APP_PRIBADI_STSRMH'];
        $ACC_F_APP_TUJUANBUKA = $RESULT_QUERY['ACC_F_APP_TUJUANBUKA'];
        $ACC_F_APP_PENGINVT = $RESULT_QUERY['ACC_F_APP_PENGINVT'].(($RESULT_QUERY['ACC_F_APP_PENGINVT'] == 'Ya') ? ', Bidang '.$RESULT_QUERY['ACC_F_PENGLAMAN_PERYT_YA'] : '');
        $ACC_F_APP_KELGABURSA = $RESULT_QUERY['ACC_F_APP_KELGABURSA'];
        $ACC_F_APP_PAILIT = $RESULT_QUERY['ACC_F_APP_PAILIT'];
        $ACC_F_APP_DRRT_NAMA = $RESULT_QUERY['ACC_F_APP_DRRT_NAMA'];
        $ACC_F_APP_DRRT_ALAMAT = $RESULT_QUERY['ACC_F_APP_DRRT_ALAMAT'];
        $ACC_F_APP_DRRT_TLP = $RESULT_QUERY['ACC_F_APP_DRRT_TLP'];
        $ACC_F_APP_DRRT_HUB = $RESULT_QUERY['ACC_F_APP_DRRT_HUB'];
        $ACC_F_APP_DRRT_ZIP = $RESULT_QUERY['ACC_F_APP_DRRT_ZIP'];
        $ACC_F_APP_KRJ_TYPE = $RESULT_QUERY['ACC_F_APP_KRJ_TYPE'];
        $ACC_F_APP_KRJ_NAMA = $RESULT_QUERY['ACC_F_APP_KRJ_NAMA'];
        $ACC_F_APP_KRJ_BDNG = $RESULT_QUERY['ACC_F_APP_KRJ_BDNG'];
        $ACC_F_APP_KRJ_JBTN = $RESULT_QUERY['ACC_F_APP_KRJ_JBTN'];
        $ACC_F_APP_KRJ_LAMA = $RESULT_QUERY['ACC_F_APP_KRJ_LAMA'];
        $ACC_F_APP_KRJ_LAMASBLM = $RESULT_QUERY['ACC_F_APP_KRJ_LAMASBLM'];
        $ACC_F_APP_KRJ_ALAMAT = $RESULT_QUERY['ACC_F_APP_KRJ_ALAMAT'];
        $ACC_F_APP_KRJ_ZIP = $RESULT_QUERY['ACC_F_APP_KRJ_ZIP'];
        $ACC_F_APP_KRJ_TLP = $RESULT_QUERY['ACC_F_APP_KRJ_TLP'];
        $ACC_F_APP_KRJ_FAX = $RESULT_QUERY['ACC_F_APP_KRJ_FAX'];
        $ACC_F_APP_KEKYAN = $RESULT_QUERY['ACC_F_APP_KEKYAN'];
        $ACC_F_APP_KEKYAN_RMHLKS = $RESULT_QUERY['ACC_F_APP_KEKYAN_RMHLKS'];
        $ACC_F_APP_KEKYAN_NJOP = $RESULT_QUERY['ACC_F_APP_KEKYAN_NJOP'];
        $ACC_F_APP_KEKYAN_DPST = $RESULT_QUERY['ACC_F_APP_KEKYAN_DPST'];
        $ACC_F_APP_KEKYAN_NILAI = $RESULT_QUERY['ACC_F_APP_KEKYAN_NILAI'];
        $ACC_F_APP_KEKYAN_LAIN = $RESULT_QUERY['ACC_F_APP_KEKYAN_LAIN'];
        $ACC_F_APP_BK_1_NAMA = $RESULT_QUERY['ACC_F_APP_BK_1_NAMA'];
        $ACC_F_APP_BK_1_CBNG = $RESULT_QUERY['ACC_F_APP_BK_1_CBNG'];
        $ACC_F_APP_BK_1_ACC = $RESULT_QUERY['ACC_F_APP_BK_1_ACC'];
        $ACC_F_APP_BK_1_TLP = $RESULT_QUERY['ACC_F_APP_BK_1_TLP'];
        $ACC_F_APP_BK_1_JENIS = $RESULT_QUERY['ACC_F_APP_BK_1_JENIS'];
        $ACC_F_APP_BK_2_NAMA = $RESULT_QUERY['ACC_F_APP_BK_2_NAMA'];
        $ACC_F_APP_BK_2_CBNG = $RESULT_QUERY['ACC_F_APP_BK_2_CBNG'];
        $ACC_F_APP_BK_2_ACC = $RESULT_QUERY['ACC_F_APP_BK_2_ACC'];
        $ACC_F_APP_BK_2_TLP = $RESULT_QUERY['ACC_F_APP_BK_2_TLP'];
        $ACC_F_APP_BK_2_JENIS = $RESULT_QUERY['ACC_F_APP_BK_2_JENIS'];
        $ACC_F_APPPEMBUKAAN_DATE = $RESULT_QUERY['ACC_F_APPPEMBUKAAN_DATE'];
        $ACC_F_APPPEMBUKAAN_IP = $RESULT_QUERY['ACC_F_APPPEMBUKAAN_IP'];
        $ACC_F_APP_FILE_RKBKCRED = $RESULT_QUERY['ACC_F_APP_FILE_RKBKCRED'];
        $ACC_F_APP_FILE_REKLISTLP = $RESULT_QUERY['ACC_F_APP_FILE_REKLISTLP'];
        $ACC_F_APP_FILE_IMG = $RESULT_QUERY['ACC_F_APP_FILE_IMG'];
        $ACC_F_APP_FILE_IMG2 = $RESULT_QUERY['ACC_F_APP_FILE_IMG2'];
        $ACC_F_APP_FILE_FOTO = $RESULT_QUERY['ACC_F_APP_FILE_FOTO'];
        $ACC_F_APP_FILE_ID = $RESULT_QUERY['ACC_F_APP_FILE_ID'];
    } else {
        $MBR_NAME = '';
        $ACC_F_APP_PRIBADI_TGLLHR = '';
        $ACC_F_APP_PRIBADI_TMPTLHR = '';
        $ACC_F_APP_PRIBADI_TYPEID = '';
        $ACC_F_APP_PRIBADI_ID = '';
        $MBR_ADDRESS = '';
        $MBR_ZIP = '';
        $ACC_TYPEACC = '';
        $ACC_RATE = '';
        $ACC_PRODUCT = '';
        $ACC_CHARGE = '';
        $ACC_F_APP_PRIBADI_NPWP = '';
        $ACC_F_APP_PRIBADI_KELAMIN = '';
        $ACC_F_APP_PRIBADI_NAMAISTRI = '';
        $ACC_F_APP_PRIBADI_IBU = '';
        $ACC_F_APP_PRIBADI_STSKAWIN = '';
        $ACC_F_APP_PRIBADI_TLP = '';
        $ACC_F_APP_PRIBADI_FAX = '';
        $ACC_F_APP_PRIBADI_HP = '';
        $ACC_F_APP_PRIBADI_STSRMH = '';
        $ACC_F_APP_TUJUANBUKA = '';
        $ACC_F_APP_PENGINVT = '';
        $ACC_F_APP_KELGABURSA = '';
        $ACC_F_APP_PAILIT = '';
        $ACC_F_APP_DRRT_NAMA = '';
        $ACC_F_APP_DRRT_ALAMAT = '';
        $ACC_F_APP_DRRT_TLP = '';
        $ACC_F_APP_DRRT_HUB = '';
        $ACC_F_APP_DRRT_ZIP = '';
        $ACC_F_APP_KRJ_TYPE = '';
        $ACC_F_APP_KRJ_NAMA = '';
        $ACC_F_APP_KRJ_BDNG = '';
        $ACC_F_APP_KRJ_JBTN = '';
        $ACC_F_APP_KRJ_LAMA = '';
        $ACC_F_APP_KRJ_LAMASBLM = '';
        $ACC_F_APP_KRJ_ALAMAT = '';
        $ACC_F_APP_KRJ_ZIP = '';
        $ACC_F_APP_KRJ_TLP = '';
        $ACC_F_APP_KRJ_FAX = '';
        $ACC_F_APP_KEKYAN = '';
        $ACC_F_APP_KEKYAN_RMHLKS = '';
        $ACC_F_APP_KEKYAN_NJOP = '';
        $ACC_F_APP_KEKYAN_DPST = '';
        $ACC_F_APP_KEKYAN_NILAI = '';
        $ACC_F_APP_KEKYAN_LAIN = '';
        $ACC_F_APP_BK_1_NAMA = '';
        $ACC_F_APP_BK_1_CBNG = '';
        $ACC_F_APP_BK_1_ACC = '';
        $ACC_F_APP_BK_1_TLP = '';
        $ACC_F_APP_BK_1_JENIS = '';
        $ACC_F_APP_BK_2_NAMA    = '-';
        $ACC_F_APP_BK_2_CBNG    = '-';
        $ACC_F_APP_BK_2_ACC = '-';
        $ACC_F_APP_BK_2_TLP = '-';
        $ACC_F_APP_BK_2_JENIS   = '-';
        $ACC_F_APPPEMBUKAAN_DATE = '';
        $ACC_F_APPPEMBUKAAN_IP = '';
        $ACC_F_APP_FILE_RKBKCRED = '';
        $ACC_F_APP_FILE_REKLISTLP = '';
        $ACC_F_APP_FILE_IMG = '';
        $ACC_F_APP_FILE_IMG2 = '';
        $ACC_F_APP_FILE_FOTO = '';
        $ACC_F_APP_FILE_ID = '';
    };

    $rekkor = '';
    $listel = '';
    $docno1 = '';
    $docno2 = '';
    $selfph = '';
    $idphot = '';
    if(!empty($ACC_F_APP_FILE_RKBKCRED)){
        $rekkor = 'checked';
    }
    if(!empty($ACC_F_APP_FILE_REKLISTLP)){
        $listel = 'checked';
    }
    if(!empty($ACC_F_APP_FILE_IMG)){
        $docno1 = 'checked';
    }
    if(!empty($ACC_F_APP_FILE_IMG2)){
        $docno2 = 'checked';
    }
    if(!empty($ACC_F_APP_FILE_FOTO)){
        $selfph = 'checked';
    }
    if(!empty($ACC_F_APP_FILE_ID)){
        $idphot = 'checked';
    }

    $doctype = 'KTP';
    // if($ACC_F_APP_PRIBADI_TYPEID == 'KTP'){
    //     $doctype = 'KTP/<strike>SIM</strike>/<strike>Passport</strike>';
    // }else if($ACC_F_APP_PRIBADI_TYPEID == 'SIM'){
    //     $doctype = '<strike>KTP</strike>/SIM/<strike>Passport</strike>';
    // }else if($ACC_F_APP_PRIBADI_TYPEID == 'Passport'){
    //     $doctype = '<strike>KTP</strike>/<strike>SIM</strike>/Passport';
    // }else{ $doctype = 'KTP/SIM/Passport'; }

    $content = '
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, minimum-scale=1,0, maximum-scale=1.0">
                <style>
                    body { font-family: "Times New Roman", serif; margin-top: 150px; }
                    header { position: fixed; top: 0px; left: 0; right: 0; height: 50px;}
                    .titik_dua {vertical-align: top; text-align:right;width:1%;}
                    .content {vertical-align: top;}
                    .page-break { page-break-before: always; }
                    .judul { border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin-bottom:10px; }
                    .text-center { text-align:center; }
                    .text-justify { text-align:justify; }
                    .text-right { text-align:right; }
                </style>
            </head>
            <body>
                <header>
                    <table style="width:100%">
                        <tr>
                            <td width="47%" style="vertical-align: middle; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$setting_pdf_logo."")).'" width="100%"></td>
                            <td width="6%">&nbsp;</td>
                            <td width="47%" style="text-align:right; vertical-align: top; ">
                                <small>
                                    <h3>'.$web_name_full.'</h3>
                                    '.$setting_central_office_address.'
                                </small>
                            </td>
                        </tr>
                    </table>
                    <hr>
                </header>
                <div class="content">
                    <div style="text-align:center;vertical-align: middle;padding: 10px 0 10px 0;">
                        <h3>APLIKASI PEMBUKAAN REKENING TRANSAKSI<br>SECARA ELEKTRONIK ONLINE</h3>
                    </div>
                    <p>Kode Nasabah:</p>
                    <table width="100%" style="border-spacing: 2px;">
                        <tr><td colspan="3"><div style="border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin-bottom:10px;"><strong>DATA PRIBADI</strong></div></td></tr>
                        <tr>
                            <td>Nama Lengkap</td>
                            <td class="titik_dua"><div style="text-align:center;">:</div></td>
                            <td>'.$MBR_NAME.'</td>
                        </tr>
                        <tr>
                            <td>Tempat/Tanggal Lahir</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_PRIBADI_TMPTLHR.' / '.$ACC_F_APP_PRIBADI_TGLLHR.'</td>
                        </tr>
                        <tr>
                            <td>No. Indentitas<br>'.$doctype.'*)</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align:top;">'.$ACC_F_APP_PRIBADI_ID.'</td>
                        </tr>
                        <tr>
                            <td>No. NPWP *)</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_PRIBADI_NPWP.'</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_PRIBADI_KELAMIN.'</td>
                        </tr>
                        <tr>
                            <td>Nama Istri/Suami *)</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_PRIBADI_NAMAISTRI.'</td>
                        </tr>
                        <tr>
                            <td>Nama Ibu Kandung</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_PRIBADI_IBU.'</td>
                        </tr>
                        <tr>
                            <td>Status Perkawinan</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_PRIBADI_STSKAWIN.'</td>
                        </tr>
                        <tr>
                            <td>Alamat Rumah</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$MBR_ADDRESS.'</td>
                        </tr>
                        <tr>
                            <td>Kode Pos</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$MBR_ZIP.'</td>
                        </tr>
                        <tr>
                            <td>No. Telp Rumah</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_PRIBADI_TLP.'</td>
                        </tr>
                        <tr>
                            <td>No. Faksimili Rumah</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_PRIBADI_FAX.'</td>
                        </tr>
                        <tr>
                            <td>No. Telp Handphone</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_PRIBADI_HP.'</td>
                        </tr>
                        <tr>
                            <td>Status Kepemilikan Rumah</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_PRIBADI_STSRMH.'</td>
                        </tr>
                        <tr>
                            <td>Tujuan Pembukaan Rekening</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_TUJUANBUKA.'</td>
                        </tr>
                        <tr>
                            <td>Pengalaman Investasi</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_PENGINVT.'</td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                Apakah Anda memiliki anggota keluarga yang bekerja di BAPPEBTI/Bursa Berjangka/Kliring Berjangka?
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                            <td>'.$ACC_F_APP_KELGABURSA.'</td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                Apakah Anda telah dinyatakan pailit oleh Pengadilan?
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                            <td>'.$ACC_F_APP_PAILIT.'</td>
                        </tr>
                    </table>
                    <table width="100%" style="border-spacing: 2px;">
                        <tr  style="margin-bottom:200px;"><td colspan="3"><div style="border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin:10px 0px;"><strong>PIHAK YANG DIHUBUNGI DALAM KEADAAN DARURAT</strong></div></td></tr>
                        <tr><td colspan="3">Dalam keadaan darurat, pihak yang dapat dihubungi</td></tr>
                        <tr>
                            <td>Nama Lengkap</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_DRRT_NAMA.'</td>
                        </tr>
                        <tr>
                            <td>Alamat Rumah</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_DRRT_ALAMAT.'</td>
                        </tr>
                        <tr>
                            <td>Kode Pos</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_DRRT_ZIP.'</td>
                        </tr>
                        <tr>
                            <td>No. Telp</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_DRRT_TLP.'</td>
                        </tr>
                        <tr>
                            <td>Hubungan dengan anda</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_DRRT_HUB.'</td>
                        </tr>
                        <tr><td colspan="3"><div style="border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin:10px 0px;"><strong>PEKERJAAN</strong></div></td></tr>
                        <tr>
                            <td>Pekerjaan</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_KRJ_TYPE.'</td>
                        </tr>
                        <tr>
                            <td>Nama Perusahaan</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_KRJ_NAMA.'</td>
                        </tr>
                        <tr>
                            <td>Bidang Usaha</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_KRJ_BDNG.'</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_KRJ_JBTN.'</td>
                        </tr>
                        <tr>
                            <td>Lama Bekerja</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_KRJ_LAMA.'. Kantor Sebelumnya : '.$ACC_F_APP_KRJ_LAMASBLM.'</td>
                        </tr>
                        <tr>
                            <td>Alamat Kantor</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_KRJ_ALAMAT.'</td>
                        </tr>
                        <tr>
                            <td>Kode pos</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_KRJ_ZIP.'</td>
                        </tr>
                        <tr>
                            <td>No. Telp Kantor</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_KRJ_TLP.'</td>
                        </tr>
                        <tr>
                            <td>No. Faksimili</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_KRJ_FAX.'</td>
                        </tr>
                        <tr><td colspan="3"><div style="border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin:10px 0px;"><strong>DAFTAR KEKAYAAN</strong></div></td></tr>
                        <tr>
                            <td>Penghasilan Per tahun</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_KEKYAN.'</td>
                        </tr>
                        <tr>
                            <td>Rumah Lokasi</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_KEKYAN_RMHLKS.'</td>
                        </tr>
                        <tr>
                            <td>Nilai NJOP</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>Rp. '.number_format((int)$ACC_F_APP_KEKYAN_NJOP, 0).'</td>
                        </tr>
                        <tr>
                            <td>Deposit Bank</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>Rp. '.number_format((int)$ACC_F_APP_KEKYAN_DPST, 0).'</td>
                        </tr>
                        <tr>
                            <td>Jumlah</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>Rp. '.number_format((int)$ACC_F_APP_KEKYAN_NILAI, 0).'</td>
                        </tr>
                        <tr>
                            <td>Lainnya</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_KEKYAN_LAIN.'</td>
                        </tr>
                    </table>
                    <div class="page-break"></div>
                    <table width="100%" style="border-spacing: 2px;">
                        <tr><td colspan="3"><div style="border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin:10px 0px;"><strong>REKENING BANK NASABAH UNTUK PENYETORAN DAN PENARIKAN MARGIN</strong></div></td></tr>
                        <tr><td colspan="3">Rekening Bank Nasabah Untuk Penyetoran dan Penarikan Margin (hanya rekening dibawah ini
                        yang dapat Saudara pergunakan untuk lalulintas margin)</td></tr>
                        <tr>
                            <td>Nama Bank</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_BK_1_NAMA.'</td>
                        </tr>
                        <tr>
                            <td>Cabang</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_BK_1_CBNG.'</td>
                        </tr>
                        <tr>
                            <td>Nomor A/C</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_BK_1_ACC.'</td>
                        </tr>
                        <tr>
                            <td>No. Tlp</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_BK_1_TLP.'</td>
                        </tr>
                        <tr>
                            <td>Jenis Rekening</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_BK_1_JENIS.'</td>
                        </tr>
                        <br>
                        <tr>
                            <td>Nama Bank</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_BK_2_NAMA.'</td>
                        </tr>
                        <tr>
                            <td>Cabang</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_BK_2_CBNG.'</td>
                        </tr>
                        <tr>
                            <td>Nomor A/C</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_BK_2_ACC.'</td>
                        </tr>
                        <tr>
                            <td>No. Tlp</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_BK_2_TLP.'</td>
                        </tr>
                        <tr>
                            <td>Jenis Rekening</td>
                            <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                            <td>'.$ACC_F_APP_BK_2_JENIS.'</td>
                        </tr>
                    </table>
                    <table width="100%" style="border-spacing: 2px;">
                        <tr><td colspan="3"><div style="border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin:10px 0px;"><strong>DOKUMEN YANG DILAMPIRKAN</strong></div></td></tr>
                        <tr>
                            <td>Rekening Koran Bank / Tagihan Kartu Kredit</td>
                            <td style="text-align: right;">Hasil Scan/photo</td>
                            <td class="titik_dua"><div style="margin:0px 3px;"><input type="checkbox" '.$rekkor.'></div></td>
                        </tr>
                        <tr>
                            <td>Rekening Listrik/Telepon</td>
                            <td style="text-align: right;">Hasil Scan/photo</td>
                            <td class="titik_dua"><div style="margin:0px 3px;"><input type="checkbox" '.$listel.'></div></td>
                        </tr>
                        <tr>
                            <td>Tambahan dokumen lain 1 (apabila diperlukan)</td>
                            <td style="text-align: right;">Hasil Scan/photo</td>
                            <td class="titik_dua"><div style="margin:0px 3px;"><input type="checkbox" '.$docno1.'></div></td>
                        </tr>
                        <tr>
                            <td>Tambahan dokumen lain 2 (apabila diperlukan)</td>
                            <td style="text-align: right;">Hasil Scan/photo</td>
                            <td class="titik_dua"><div style="margin:0px 3px;"><input type="checkbox" '.$docno2.'></div></td>
                        </tr>
                        <tr>
                            <td>Foto Terkini </td>
                            <td style="text-align: right;">Hasil Scan</td>
                            <td class="titik_dua"><div style="margin:0px 3px;"><input type="checkbox" '.$selfph.'></div></td>
                        </tr>
                        <tr>
                            <!--<td>KTP/SIM/Passpor*)</td>-->
                            <td>KTP*)</td>
                            <td style="text-align: right;">Hasil Scan</td>
                            <td class="titik_dua"><div style="margin:0px 3px;"><input type="checkbox" '.$idphot.'></div></td>
                        </tr>
                        <tr style="margin-bottom:200px;"><td colspan="3"><div style="border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin:10px 0px;"><strong>&nbsp;</strong></div></td></tr>
                    </table>
                    <div style="text-align: center;">
                        <strong>PERNYATAAN KEBENARAN DAN TANGGUNG JAWAB</strong>
                    </div>
                    <div style="margin-top:15px;" class="text-justify">
                        <p>Dengan mengisi kolom "YA" di bawah ini, saya menyatakan bahwa semua informasi dan
                        semua dokumen yang saya lampirkan dalam <span>APLIKASI PEMBUKAAN REKENING
                        TRANSAKSI SECARA ELEKTRONIK ONLINE</span> adalah benar dan tepat, Saya akan
                        bertanggung jawab penuh apabila dikemudian hari terjadi sesuatu hal sehubungan
                        dengan ketidakbenaran data yang saya berikan.</p>
                    </div>
                    <div style="margin-top:15px;">
                        <table>
                            <tr>
                                <td style="text-align:right;">
                                    Pernyataan Kebenaran dan Tanggung Jawab
                                </td>
                                <td style="vertical-align: middle;"><div style="margin:0px 5px;">:</div></td>
                                <td><input type="checkbox" style="display: inline;" checked disabled><span>Ya</span></td>
                                <td><input type="checkbox" style="display: inline;" disabled><span>Tidak</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Menyatakan pada Tanggal</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td colspan="2"><span>'.date('Y-m-d H:i:s', strtotime($ACC_F_APPPEMBUKAAN_DATE)).'</span></td>
                            </tr>
                            <!-- <tr>
                                <td>IP Address</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td><strong>'.$ACC_F_APPPEMBUKAAN_IP.'</strong></td>
                            </tr> -->
                        </table>
                        <div>
                            <p>*) Pilih salah satu </p>
                        </div>
                    </div>
                </div>
            </body>
        </html>
    ';

    $dompdf->loadHtml($content);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    if(isset($pdfotpt)){
        // $output  = $dompdf->output();
        // $fl_name = realpath(dirname(dirname(__FILE__))).'/'.'Pernyataan_simulasi.pdf';
        // file_put_contents($fl_name, $output);
        // if(isset($ALL_PDF_FILES)){
        //     $ALL_PDF_FILES[] = $fl_name;
        // }
        $htmls = $content;
    }else{
        $dompdf->stream("".$web_name_full." - 107.PBK.03",array("Attachment"=>0));
        exit(0);
    }
    
?>