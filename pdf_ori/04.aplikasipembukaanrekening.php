
<?php
    date_default_timezone_set("Asia/Jakarta");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../setting.php';
    require_once 'vendor/autoload.php';
    use Dompdf\Dompdf;
    $dompdf = new Dompdf();
    
    $id_acc = form_input($_GET["x"]);
    
    $SQL_QUERY = mysqli_query($db, '
        SELECT
            tb_member.MBR_NAME,
            tb_member.MBR_ZIP,
            tb_lacc.ACC_TGLLAHIR,
            tb_lacc.ACC_02_TMPTLAHIR,
            tb_lacc.ACC_02_IDTYPE,
            tb_lacc.ACC_02_IDNO,
            tb_member.MBR_ADDRESS,
            tb_member.MBR_ZIP,
            tb_lacc.ACC_04_0_TYPEACC,
            tb_lacc.ACC_04_0_RATE,
            tb_lacc.ACC_04_0_PRODUCT,
            tb_lacc.ACC_04_0_CHARGE,
            tb_lacc.ACC_04_1_NPWP,
            tb_lacc.ACC_04_1_KELAMIN,
            tb_lacc.ACC_04_1_ISTRISUAMI,
            tb_lacc.ACC_04_1_IBUKANDUNG,
            tb_lacc.ACC_04_1_STSKAWIN,
            tb_lacc.ACC_04_1_TLPRUMAH,
            tb_lacc.ACC_04_1_FAKSIMILI,
            tb_lacc.ACC_04_1_HANDPHONE,
            tb_lacc.ACC_04_1_KEPEMILIKANRUMAH,
            tb_lacc.ACC_04_1_TUJUANBUKAREKENING,
            tb_lacc.ACC_04_1_PENGALAMANINVESTASI,
            tb_lacc.ACC_04_1_KELUARGABURSA,
            tb_lacc.ACC_04_1_PAILIT,
            tb_lacc.ACC_04_2_NAMA,
            tb_lacc.ACC_04_2_ALAMAT,
            tb_lacc.ACC_04_2_TLP,
            tb_lacc.ACC_04_2_HUBUNGAN,
            tb_lacc.ACC_04_3_PEKERJAAN,
            tb_lacc.ACC_04_3_PERUSAHAAN,
            tb_lacc.ACC_04_3_BIDANGUSAHA,
            tb_lacc.ACC_04_3_JABATAN,
            tb_lacc.ACC_04_3_LAMAKERJA,
            tb_lacc.ACC_04_3_LAMAKERJASBLM,
            tb_lacc.ACC_04_3_ALAMAT,
            tb_lacc.ACC_04_3_TLP,
            tb_lacc.ACC_04_3_FAKSIMILI,
            tb_lacc.ACC_04_4_PERTAHUN,
            tb_lacc.ACC_04_4_RUMAHLOKASI,
            tb_lacc.ACC_04_4_NJOP,
            tb_lacc.ACC_04_4_DEPOSITBANK,
            tb_lacc.ACC_04_4_DEPOSITJUMLAH,
            tb_lacc.ACC_04_4_LAINNYA,
            tb_lacc.ACC_04_5_BANKNAMA,
            tb_lacc.ACC_04_5_CABANG,
            tb_lacc.ACC_04_5_NOREK,
            tb_lacc.ACC_04_5_TLP,
            tb_lacc.ACC_04_5_JENISREK,
            tb_lacc.ACC_04_AGGDATE
        FROM tb_lacc
        JOIN tb_member
        ON(tb_member.MBR_ID = tb_lacc.ACC_MBR)
        WHERE tb_lacc.ACC_LOGIN = LOWER("'.$id_acc.'")
        LIMIT 1
    ');
    if(mysqli_num_rows($SQL_QUERY) > 0){
        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        $MBR_NAME = $RESULT_QUERY['MBR_NAME'];
        $ACC_TGLLAHIR = $RESULT_QUERY['ACC_TGLLAHIR'];
        $ACC_02_TMPTLAHIR = $RESULT_QUERY['ACC_02_TMPTLAHIR'];
        $ACC_02_IDTYPE = $RESULT_QUERY['ACC_02_IDTYPE'];
        $ACC_02_IDNO = $RESULT_QUERY['ACC_02_IDNO'];
        $MBR_ADDRESS = $RESULT_QUERY['MBR_ADDRESS'];
        $MBR_ZIP = $RESULT_QUERY['MBR_ZIP'];
        $ACC_04_0_TYPEACC = $RESULT_QUERY['ACC_04_0_TYPEACC'];
        $ACC_04_0_RATE = $RESULT_QUERY['ACC_04_0_RATE'];
        $ACC_04_0_PRODUCT = $RESULT_QUERY['ACC_04_0_PRODUCT'];
        $ACC_04_0_CHARGE = $RESULT_QUERY['ACC_04_0_CHARGE'];
        $ACC_04_1_NPWP = $RESULT_QUERY['ACC_04_1_NPWP'];
        $ACC_04_1_KELAMIN = $RESULT_QUERY['ACC_04_1_KELAMIN'];
        $ACC_04_1_ISTRISUAMI = $RESULT_QUERY['ACC_04_1_ISTRISUAMI'];
        $ACC_04_1_IBUKANDUNG = $RESULT_QUERY['ACC_04_1_IBUKANDUNG'];
        $ACC_04_1_STSKAWIN = $RESULT_QUERY['ACC_04_1_STSKAWIN'];
        $ACC_04_1_TLPRUMAH = $RESULT_QUERY['ACC_04_1_TLPRUMAH'];
        $ACC_04_1_FAKSIMILI = $RESULT_QUERY['ACC_04_1_FAKSIMILI'];
        $ACC_04_1_HANDPHONE = $RESULT_QUERY['ACC_04_1_HANDPHONE'];
        $ACC_04_1_KEPEMILIKANRUMAH = $RESULT_QUERY['ACC_04_1_KEPEMILIKANRUMAH'];
        $ACC_04_1_TUJUANBUKAREKENING = $RESULT_QUERY['ACC_04_1_TUJUANBUKAREKENING'];
        $ACC_04_1_PENGALAMANINVESTASI = $RESULT_QUERY['ACC_04_1_PENGALAMANINVESTASI'];
        $ACC_04_1_KELUARGABURSA = $RESULT_QUERY['ACC_04_1_KELUARGABURSA'];
        $ACC_04_1_PAILIT = $RESULT_QUERY['ACC_04_1_PAILIT'];
        $ACC_04_2_NAMA = $RESULT_QUERY['ACC_04_2_NAMA'];
        $ACC_04_2_ALAMAT = $RESULT_QUERY['ACC_04_2_ALAMAT'];
        $ACC_04_2_TLP = $RESULT_QUERY['ACC_04_2_TLP'];
        $ACC_04_2_HUBUNGAN = $RESULT_QUERY['ACC_04_2_HUBUNGAN'];
        $ACC_04_3_PEKERJAAN = $RESULT_QUERY['ACC_04_3_PEKERJAAN'];
        $ACC_04_3_PERUSAHAAN = $RESULT_QUERY['ACC_04_3_PERUSAHAAN'];
        $ACC_04_3_BIDANGUSAHA = $RESULT_QUERY['ACC_04_3_BIDANGUSAHA'];
        $ACC_04_3_JABATAN = $RESULT_QUERY['ACC_04_3_JABATAN'];
        $ACC_04_3_LAMAKERJA = $RESULT_QUERY['ACC_04_3_LAMAKERJA'];
        $ACC_04_3_LAMAKERJASBLM = $RESULT_QUERY['ACC_04_3_LAMAKERJASBLM'];
        $ACC_04_3_ALAMAT = $RESULT_QUERY['ACC_04_3_ALAMAT'];
        $ACC_04_3_TLP = $RESULT_QUERY['ACC_04_3_TLP'];
        $ACC_04_3_FAKSIMILI = $RESULT_QUERY['ACC_04_3_FAKSIMILI'];
        $ACC_04_4_PERTAHUN = $RESULT_QUERY['ACC_04_4_PERTAHUN'];
        $ACC_04_4_RUMAHLOKASI = $RESULT_QUERY['ACC_04_4_RUMAHLOKASI'];
        $ACC_04_4_NJOP = $RESULT_QUERY['ACC_04_4_NJOP'];
        $ACC_04_4_DEPOSITBANK = $RESULT_QUERY['ACC_04_4_DEPOSITBANK'];
        $ACC_04_4_DEPOSITJUMLAH = $RESULT_QUERY['ACC_04_4_DEPOSITJUMLAH'];
        $ACC_04_4_LAINNYA = $RESULT_QUERY['ACC_04_4_LAINNYA'];
        $ACC_04_5_BANKNAMA = $RESULT_QUERY['ACC_04_5_BANKNAMA'];
        $ACC_04_5_CABANG = $RESULT_QUERY['ACC_04_5_CABANG'];
        $ACC_04_5_NOREK = $RESULT_QUERY['ACC_04_5_NOREK'];
        $ACC_04_5_TLP = $RESULT_QUERY['ACC_04_5_TLP'];
        $ACC_04_5_JENISREK = $RESULT_QUERY['ACC_04_5_JENISREK'];
        $ACC_04_AGGDATE = $RESULT_QUERY['ACC_04_AGGDATE'];
    } else {
        $MBR_NAME = '';
        $ACC_TGLLAHIR = '';
        $ACC_02_TMPTLAHIR = '';
        $ACC_02_IDTYPE = '';
        $ACC_02_IDNO = '';
        $MBR_ADDRESS = '';
        $MBR_ZIP = '';
        $ACC_04_0_TYPEACC = '';
        $ACC_04_0_RATE = '';
        $ACC_04_0_PRODUCT = '';
        $ACC_04_0_CHARGE = '';
        $ACC_04_1_NPWP = '';
        $ACC_04_1_KELAMIN = '';
        $ACC_04_1_ISTRISUAMI = '';
        $ACC_04_1_IBUKANDUNG = '';
        $ACC_04_1_STSKAWIN = '';
        $ACC_04_1_TLPRUMAH = '';
        $ACC_04_1_FAKSIMILI = '';
        $ACC_04_1_HANDPHONE = '';
        $ACC_04_1_KEPEMILIKANRUMAH = '';
        $ACC_04_1_TUJUANBUKAREKENING = '';
        $ACC_04_1_PENGALAMANINVESTASI = '';
        $ACC_04_1_KELUARGABURSA = '';
        $ACC_04_1_PAILIT = '';
        $ACC_04_2_NAMA = '';
        $ACC_04_2_ALAMAT = '';
        $ACC_04_2_TLP = '';
        $ACC_04_2_HUBUNGAN = '';
        $ACC_04_3_PEKERJAAN = '';
        $ACC_04_3_PERUSAHAAN = '';
        $ACC_04_3_BIDANGUSAHA = '';
        $ACC_04_3_JABATAN = '';
        $ACC_04_3_LAMAKERJA = '';
        $ACC_04_3_LAMAKERJASBLM = '';
        $ACC_04_3_ALAMAT = '';
        $ACC_04_3_TLP = '';
        $ACC_04_3_FAKSIMILI = '';
        $ACC_04_4_PERTAHUN = '';
        $ACC_04_4_RUMAHLOKASI = '';
        $ACC_04_4_NJOP = '';
        $ACC_04_4_DEPOSITBANK = '';
        $ACC_04_4_DEPOSITJUMLAH = '';
        $ACC_04_4_LAINNYA = '';
        $ACC_04_5_BANKNAMA = '';
        $ACC_04_5_CABANG = '';
        $ACC_04_5_NOREK = '';
        $ACC_04_5_TLP = '';
        $ACC_04_5_JENISREK = '';
        $ACC_04_AGGDATE = '';
    };

    $content = '
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, minimum-scale=1,0, maximum-scale=1.0">
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous">
                <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
                <style>
                    .titik_dua {vertical-align: top; text-align:center;}
                </style>
            </head>
            <body>
                <table style="width:100%">
                    <tr>
                        <td width="50%" style="vertical-align: top; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://ibftrader.allmediaindo.com/assets/img/logoibf.png")).'" width="75%"></td>
                        <td width="50%" style="text-align:center; ">
                            <h3>PT.International Business Futures</h3>
                            <p>
                                PASKAL HYPER SQUARE BLOK D NO.45-46 JL. H.O.S COKROAMINOTO NO.25-27 BANDUNG, JAWA BARAT – 40181
                            </p>
                        </td>
                    </tr>
                </table>
                <hr>
                <table style="width:100%">
                    <tr>
                        <td width="50%" style="vertical-align: top; "><strong><small>Formulir Nomor : 107.PBK.03</small></strong></td>
                        <td width="50%" style="text-align:right; ">
                            <small>
                                Lampiran Peraturan Kepala Badan Pengawas<br>
                                Perdagangan Berjangka Komoditi<br>
                                Nomor : 107/BAPPEBTI/PER/11/2013
                            </small>
                        </td>
                    </tr>
                </table>
                <div style="text-align:center;vertical-align: middle;padding: 10px 0 10px 0;">
                    <h3>APLIKASI PEMBUKAAN REKENING TRANSAKS<br>SECARA ELEKTRONIK ON-LINE</h3>
                </div>
                <p>Yang mengisi formulir di bawah ini:</p>
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
                        <td>'.$ACC_02_TMPTLAHIR.' / '.$ACC_TGLLAHIR.'</td>
                    </tr>
                    <tr>
                        <td>'.$ACC_02_IDTYPE.'</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_02_IDNO.'</td>
                    </tr>
                    <tr>
                        <td>No. NPWP *)</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_1_NPWP.'</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_1_KELAMIN.'</td>
                    </tr>
                    <tr>
                        <td>Nama Istri/Suami *)</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_1_ISTRISUAMI.'</td>
                    </tr>
                    <tr>
                        <td>Nama Ibu Kandung</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_1_IBUKANDUNG.'</td>
                    </tr>
                    <tr>
                        <td>Status Perkawinan</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_1_STSKAWIN.'</td>
                    </tr>
                    <tr>
                        <td>Alamat Rumah</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$MBR_ADDRESS.'. Kode Pos : '.$MBR_ZIP.'</td>
                    </tr>
                    <tr>
                        <td>No. Telp Rumah</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_1_TLPRUMAH.'</td>
                    </tr>
                    <tr>
                        <td>No. Faksimili Rumah</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_1_FAKSIMILI.'</td>
                    </tr>
                    <tr>
                        <td>No. Telp Handphone</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_1_HANDPHONE.'</td>
                    </tr>
                    <tr>
                        <td>Status Kepemilikan Rumah</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_1_KEPEMILIKANRUMAH.'</td>
                    </tr>
                    <tr>
                        <td>Tujuan Pembukaan Rekening</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_1_TUJUANBUKAREKENING.'</td>
                    </tr>
                    <tr>
                        <td>Pengalaman Investasi</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_1_PENGALAMANINVESTASI.'</td>
                    </tr>
                    <br>
                    <tr>
                        <td>
                            Apakah Anda memiliki anggota keluarga 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            yang bekerja di BAPPEBTI/Bursa
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Berjangka/Kliring Berjangka?
                        </td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_1_KELUARGABURSA.'</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Apakah Anda telah dinyatakan pailit oleh Pengadilan?</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_1_PAILIT.'</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <table style="width:150%">
                        <tr>
                            <td width="50%" style="vertical-align: top; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://ibftrader.allmediaindo.com/assets/img/logoibf.png")).'" width="75%"></td>
                            <td width="50%" style="text-align:center; ">
                                <h3>PT.International Business Futures</h3>
                                <p>
                                    PASKAL HYPER SQUARE BLOK D NO.45-46 JL. H.O.S COKROAMINOTO NO.25-27 BANDUNG, JAWA BARAT – 40181
                                </p>
                            </td>
                        </tr>
                    </table>
                    <hr style="width:147%">
                    <tr  style="margin-bottom:200px;"><td colspan="3"><div style="border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin:10px 0px;"><strong>PIHAK YANG DIHUBUNGI DALAM KEADAAN DARURAT</strong></div></td></tr>
                    <tr>
                        <td>Nama</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_2_NAMA.'</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_2_ALAMAT.'</td>
                    </tr>
                    <tr>
                        <td>No. Telp</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_2_TLP.'</td>
                    </tr>
                    <tr>
                        <td>Hubungan dengan anda</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_2_HUBUNGAN.'</td>
                    </tr>
                    <tr><td colspan="3"><div style="border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin:10px 0px;"><strong>PEKERJAAN</strong></div></td></tr>
                    <tr>
                        <td>Pekerjaan</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_3_PEKERJAAN.'</td>
                    </tr>
                    <tr>
                        <td>Nama Perusahaan</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_3_PERUSAHAAN.'</td>
                    </tr>
                    <tr>
                        <td>Bidang Usaha</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_3_BIDANGUSAHA.'</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_3_JABATAN.'</td>
                    </tr>
                    <tr>
                        <td>Lama Bekerja</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_3_LAMAKERJA.' Tahun. Kantor Sebelumnya : '.$ACC_04_3_LAMAKERJASBLM.' Tahun</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Alamat Kantor</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_3_ALAMAT.'</td>
                    </tr>
                    <tr>
                        <td>No. Telp Kantor</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_3_TLP.'</td>
                    </tr>
                    <tr>
                        <td>No. Faksimili</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_3_FAKSIMILI.'</td>
                    </tr>
                    <tr><td colspan="3"><div style="border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin:10px 0px;"><strong>DAFTAR KEKAYAAN</strong></div></td></tr>
                    <tr>
                        <td>Penghasilan Per tahun</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_4_PERTAHUN.'</td>
                    </tr>
                    <tr>
                        <td>Rumah Lokasi</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_4_RUMAHLOKASI.'</td>
                    </tr>
                    <tr>
                        <td>Nilai NJOP</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_4_NJOP.'</td>
                    </tr>
                    <tr>
                        <td>Deposit Bank</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_4_DEPOSITBANK.'</td>
                    </tr>
                    <tr>
                        <td>Jumlah</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_4_DEPOSITJUMLAH.'</td>
                    </tr>
                    <tr>
                        <td>Lainnya</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_4_LAINNYA.'</td>
                    </tr>
                    <br>
                    <br>
                    <br>
                    <tr style="margin-bottom:200px;"></tr>
                    <table style="width:150%">
                        <tr>
                            <td width="50%" style="vertical-align: top; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://ibftrader.allmediaindo.com/assets/img/logoibf.png")).'" width="75%"></td>
                            <td width="50%" style="text-align:center; ">
                                <h3>PT.International Business Futures</h3>
                                <p>
                                    PASKAL HYPER SQUARE BLOK D NO.45-46 JL. H.O.S COKROAMINOTO NO.25-27 BANDUNG, JAWA BARAT – 40181
                                </p>
                            </td>
                        </tr>
                    </table>
                    <hr style="width:147%">
                    <tr><td colspan="3"><div style="border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin:10px 0px;"><strong>REKENING BANK NASABAH UNTUK PENYETORAN DAN PENARIKAN MARGIN</strong></div></td></tr>
                    <tr><td colspan="3">Rekening Bank Nasabah Untuk Penyetoran dan Penarikan Margin (hanya rekening dibawah ini
                    yang dapat Saudara pergunakan untuk lalulintas margin)</td></tr>
                    <tr>
                        <td>Nama Bank</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_5_BANKNAMA.'</td>
                    </tr>
                    <tr>
                        <td>Cabang</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_5_CABANG.'</td>
                    </tr>
                    <tr>
                        <td>Nomor A/C</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_5_NOREK.'</td>
                    </tr>
                    <tr>
                        <td>No. Tlp</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_5_TLP.'</td>
                    </tr>
                    <tr>
                        <td>Jenis Rekening</td>
                        <td class="titik_dua"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_04_5_JENISREK.'</td>
                    </tr>
                    <tr style="margin-bottom:200px;"><td colspan="3"><div style="border:1px solid black;text-align:center;background-color:#efefef;padding:5px 0px;margin:10px 0px;"><strong>&nbsp;</strong></div></td></tr>
                </table>
                <div style="margin-top:25px;">
                    <p>Dengan mengisi kolom “YA” di bawah ini, saya menyatakan bahwa semua informasi dan
                    semua dokumen yang saya lampirkan dalam <strong>APLIKASI PEMBUKAAN REKENING
                    TRANSAKSI SECARA ELEKTRONIK ON-LINE</strong> adalah benar dan tepat, Saya akan
                    bertanggung jawab penuh apabila dikemudian hari terjadi sesuatu hal sehubungan
                    dengan ketidakbenaran data yang saya berikan.</p>
                </div>
                <div style="text-align:center;margin-top:25px;margin-left:25%">
                    <table>
                        <tr>
                            <td>Pernyataan Menerima</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>YA</strong></td>
                        </tr>
                        <tr>
                            <td>Menyatakan pada tanggal</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>'.date('Y-m-d H:i:s', strtotime($ACC_04_AGGDATE)).'</strong></td>
                        </tr>
                    </table>
                </div>
            </body>
        </html>
    ';

    $dompdf->loadHtml($content);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("PT.International Business Futures - 107.PBK.03",array("Attachment"=>0));
    
?>