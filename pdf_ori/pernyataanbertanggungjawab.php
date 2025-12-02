
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
            IF(tb_lacc.ACC_LOGIN = 0, "Waiting Confirmation", tb_lacc.ACC_LOGIN) AS ACC_LOGIN,
            tb_member.MBR_ADDRESS,
            tb_lacc.ACC_02_TMPTLAHIR,
            tb_lacc.ACC_02_IDTYPE,
            tb_lacc.ACC_02_IDNO,
            tb_lacc.ACC_08_AGGDATE
        FROM tb_lacc
        JOIN tb_member
        ON(tb_member.MBR_ID = tb_lacc.ACC_MBR)
        WHERE LOWER(MD5(MD5(tb_lacc.ID_ACC))) = LOWER("'.$id_acc.'")
        LIMIT 1
    ');
    if(mysqli_num_rows($SQL_QUERY) > 0){
        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        $MBR_NAME = $RESULT_QUERY['MBR_NAME'];
        $MBR_ZIP = $RESULT_QUERY['MBR_ZIP'];
        $ACC_S2_DATEBIRTH = $RESULT_QUERY['ACC_TGLLAHIR'];
        $ACC_LOGIN = $RESULT_QUERY['ACC_LOGIN'];
        $ACC_S6_ADD = $RESULT_QUERY['MBR_ADDRESS'];
        $ACC_S6_TMPTLAHIR = $RESULT_QUERY['ACC_02_TMPTLAHIR'];
        $ACC_S6_IDTYPE = $RESULT_QUERY['ACC_02_IDTYPE'];
        $ACC_S6_IDNO = $RESULT_QUERY['ACC_02_IDNO'];
        $ACC_08_AGGDATE = $RESULT_QUERY['ACC_08_AGGDATE'];
    } else {
        $MBR_NAME = '';
        $MBR_ZIP = '';
        $ACC_S2_DATEBIRTH = '';
        $ACC_LOGIN = '';
        $ACC_S6_ADD = '';
        $ACC_S6_TMPTLAHIR = '';
        $ACC_S6_IDTYPE = '';
        $ACC_S6_IDNO = '';
        $ACC_08_AGGDATE = '';
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
                        <td width="50%" style="vertical-align: top; "><strong><small>Formulir Nomor : 107.PBK.07</small></strong></td>
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
                    <h3>PERNYATAAN BERTANGGUNG JAWAB ATAS<br>
                    KODE AKSES TRANSAKSI NASABAH <i>(Personal Access Password)</i></h3>
                </div>
                <p>Yang mengisi formulir di bawah ini:</p>
                <table style="border-spacing: 2px;">
                    <tr>
                        <td>Nama Lengkap</td>
                        <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$MBR_NAME.'</td>
                    </tr>
                    <tr>
                        <td>Tempat/Tanggal Lahir</td>
                        <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_S6_TMPTLAHIR.' / '.$ACC_S2_DATEBIRTH.'</td>
                    </tr>
                    <tr>
                        <td>Alamat Rumah</td>
                        <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_S6_ADD.', '.$MBR_ZIP.'</td>
                    </tr>
                    <tr>
                        <td>'.$ACC_S6_IDTYPE.'</td>
                        <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_S6_IDNO.'</td>
                    </tr>
                    <tr>
                        <td>No. Acc.</td>
                        <td style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                        <td>'.$ACC_LOGIN.'</td>
                    </tr>
                </table>
                <div style="margin-top:25px;">
                    <p>Dengan mengisi kolom "YA" di bawah ini, saya menyatakan bahwa saya bertanggungjawab
                    sepenuhnya terhadap kode akses transaksi Nasabah (Personal Access Password) dan tidak
                    menyerahkan kode akses transaksi Nasabah (Personal Access Password) ke pihak lain, terutama
                    kepada pegawai Pialang Berjangka atau pihak yang memiliki kepentingan dengan Pialang
                    Berjangka.</p>
                </div>
                <div style="margin-top:25px; border:1px solid black;text-align:center;">
                    <p><strong>PERINGATAN !!!<br>
                    Pialang Berjangka, Wakil Pialang Berjangka, pegawai Pialang Berjangka, atau pihak<br>
                    yang memiliki kepentingan dengan dengan Pialang Berjangka dilarang menerima kode<br>
                    akses transaksi Nasabah <i>(Personal Access Password)</i>.</strong></p>
                </div>
                <div style="margin-top:25px;">
                    <p>Demikian Pernyataan ini dibuat dengan sebenarnya dalam keadaan sadar, sehat jasmani dan
                    rohani serta tanpa paksaan apapun dari pihak manapun.</p>
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
                            <td><strong>'.date('Y-m-d H:i:s', strtotime($ACC_08_AGGDATE)).'</strong></td>
                        </tr>
                    </table>
                </div>
            </body>
        </html>
    ';

    $dompdf->loadHtml($content);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("PT.International Business Futures - 107.PBK.07",array("Attachment"=>0));
    
?>