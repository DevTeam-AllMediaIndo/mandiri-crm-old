<?php
    date_default_timezone_set("Asia/Jakarta");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../../setting.php';
    require_once 'vendor/autoload.php';
    use Dompdf\Dompdf;
    use Dompdf\Options;
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('isHtml5ParserEnabled', true);
    $dompdf = new Dompdf($options);
    
    $id_acc = form_input($_GET["x"]);
    
    $SQL_QUERY = mysqli_query($db, '
        SELECT
            tb_racc.ACC_F_APP_PRIBADI_NAMA AS MBR_NAME,
            tb_member.MBR_ZIP,
            tb_racc.ACC_F_APP_PRIBADI_TGLLHR,
            IF(tb_racc.ACC_LOGIN = 0, "", tb_racc.ACC_LOGIN) AS ACC_LOGIN,
            tb_member.MBR_ADDRESS,
            tb_racc.ACC_F_APP_PRIBADI_TMPTLHR,
            tb_racc.ACC_F_APP_PRIBADI_TYPEID,
            tb_racc.ACC_F_APP_PRIBADI_ID,
            tb_racc.ACC_F_APP_PRIBADI_KELAMIN,
            tb_racc.ACC_F_DISC_DATE,
            tb_racc.ACC_F_PERJ_WPB
        FROM tb_racc
        JOIN tb_member
        ON(tb_member.MBR_ID = tb_racc.ACC_MBR)
        WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id_acc.'"
        LIMIT 1
    ');
    if(mysqli_num_rows($SQL_QUERY) > 0){
        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        $MBR_NAME = $RESULT_QUERY['MBR_NAME'];
        $MBR_ZIP = $RESULT_QUERY['MBR_ZIP'];
        $ACC_S2_DATEBIRTH = $RESULT_QUERY['ACC_F_APP_PRIBADI_TGLLHR'];
        $ACC_LOGIN = $RESULT_QUERY['ACC_LOGIN'];
        $ACC_S6_ADD = $RESULT_QUERY['MBR_ADDRESS'];
        $ACC_S6_TMPTLAHIR = $RESULT_QUERY['ACC_F_APP_PRIBADI_TMPTLHR'];
        $ACC_S6_IDTYPE = $RESULT_QUERY['ACC_F_APP_PRIBADI_TYPEID'];
        $ACC_S6_IDNO = $RESULT_QUERY['ACC_F_APP_PRIBADI_ID'];
        $ACC_F_DISC_DATE = $RESULT_QUERY['ACC_F_DISC_DATE'];
        $ACC_F_APP_PRIBADI_KELAMIN = $RESULT_QUERY['ACC_F_APP_PRIBADI_KELAMIN'];
        $ACC_F_PERJ_WPB = $RESULT_QUERY['ACC_F_PERJ_WPB'];
        $ACC_LOGIN = $RESULT_QUERY['ACC_LOGIN'];
    } else {
        $MBR_NAME = '';
        $MBR_ZIP = '';
        $ACC_S2_DATEBIRTH = '';
        $ACC_LOGIN = '';
        $ACC_S6_ADD = '';
        $ACC_S6_TMPTLAHIR = '';
        $ACC_S6_IDTYPE = '';
        $ACC_S6_IDNO = '';
        $ACC_F_DISC_DATE = '';
        $ACC_F_PERJ_WPB = '';
        $ACC_LOGIN = '';
    };
    if($RESULT_QUERY['ACC_F_APP_PRIBADI_KELAMIN'] == 'Laki-laki'){ 
        $bapakatauibu = 'Bapak/<strike>Ibu</strike>'; 
    } else { $bapakatauibu = '<strike>Bapak</strike>/Ibu'; }

    $doctype = 'KTP';
    // if($ACC_S6_IDTYPE == 'KTP'){
    //     $doctype =  'KTP/<strike>SIM</strike>/<strike>Passport</strike>';
    // }else if($ACC_S6_IDTYPE == 'SIM'){
    //     $doctype =  '<strike>KTP</strike>/SIM/<strike>Passport</strike>';
    // }else if($ACC_S6_IDTYPE == 'Passport'){
    //     $doctype =  '<strike>KTP</strike>/<strike>SIM</strike>/Passport';
    // }else{ $doctype =  'KTP/SIM/Passport'; }

    if(strtolower(date('l', strtotime($ACC_F_DISC_DATE))) == strtolower('Monday')){ $date_day = 'Senin';
    } else if(strtolower(date('l', strtotime($ACC_F_DISC_DATE))) == strtolower('Tuesday')){ $date_day = 'Selasa';
    } else if(strtolower(date('l', strtotime($ACC_F_DISC_DATE))) == strtolower('wednesday')){ $date_day = 'Rabu';
    } else if(strtolower(date('l', strtotime($ACC_F_DISC_DATE))) == strtolower('thursday')){ $date_day = 'Kamis';
    } else if(strtolower(date('l', strtotime($ACC_F_DISC_DATE))) == strtolower('Friday')){ $date_day = 'Jumat';
    } else if(strtolower(date('l', strtotime($ACC_F_DISC_DATE))) == strtolower('Saturday')){ $date_day = 'Sabtu';
    } else if(strtolower(date('l', strtotime($ACC_F_DISC_DATE))) == strtolower('Sunday')){ $date_day = 'Minggu';
    };

    if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('January')){ $date_month = 'Januari';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('February')){ $date_month = 'Februari';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('March')){ $date_month = 'Maret';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('April')){ $date_month = 'April';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('May')){ $date_month = 'Mei';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('June')){ $date_month = 'Juni';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('July')){ $date_month = 'Juli';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('August')){ $date_month = 'Agustus';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('September')){ $date_month = 'September';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('October')){ $date_month = 'Oktober';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('November')){ $date_month = 'November';
    } else if(strtolower(date('F', strtotime($ACC_F_DISC_DATE))) == strtolower('December')){ $date_month = 'Desember';
    };

    $content = '
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, minimum-scale=1,0, maximum-scale=1.0">
                <style>
                    body { font-family: sans-serif; margin-top: 150px; }
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
                    <div style="text-align:center;vertical-align: middle;padding: 10px 0 5px 0;">
                        <h3>BUKTI KONFIRMASI PENERIMAAN NASABAH<br>
                        SECARA ELEKTRONIK ON-LINE<br>
                        PADA '.strtoupper($web_name_full).'</h3>
                    </div>
                    <p>Saya yang bertandatangan dibawah ini:</p>
                    <table style="border-spacing: 2px;">
                        <tr>
                            <td width="25%" style="vertical-align: top;">Nama Lengkap</td>
                            <td width="1%" style="vertical-align: top; text-align:center;">&nbsp;:&nbsp;</td>
                            <td>'.$ACC_F_PERJ_WPB.'</td>
                        </tr>
                        <tr>
                            <td>Pekerjaan/Jabatan</td>
                            <td width="1%" style="vertical-align: top; text-align:center;">&nbsp;:&nbsp;</td>
                            <td>Wakil Pialang (Petugas Wakil Pialang yang ditunjuk Memverifikasi)</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Alamat</td>
                            <td width="1%" style="vertical-align: top; text-align:center;">&nbsp;:&nbsp;</td>
                            <td>'.$setting_central_office_address.'</td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <p>
                                    dalam hal ini bertindak untuk dan atas nama '.$web_name_full.'
                                    pada hari ini '.$date_day.',  tanggal '.date('d', strtotime($ACC_F_DISC_DATE)).' '.$date_month.' '.date('Y', strtotime($ACC_F_DISC_DATE)).' mengkonfirmasi kepada:
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Nama Lengkap</td>
                            <td width="1%" style="vertical-align: top; text-align:center;">&nbsp;:&nbsp;</td>
                            <td>'.$MBR_NAME.'</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">Alamat Rumah</td>
                            <td width="1%" style="vertical-align: top; text-align:center;">&nbsp;:&nbsp;</td>
                            <td>'.$ACC_S6_ADD.', '.$MBR_ZIP.'</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">No. Identitas<br>'.$doctype.' *)</td>
                            <td width="1%" style="vertical-align: top; text-align:center;">&nbsp;:&nbsp;</td>
                            <td style="vertical-align:top;">'.$ACC_S6_IDNO.'</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">No. Acc.</td>
                            <td width="1%" style="vertical-align: top; text-align:center;">&nbsp;:&nbsp;</td>
                            <td>'.$ACC_LOGIN.'</td>
                        </tr>
                    </table>
                    <p class="text-justify">
                        Bahwa '.$bapakatauibu.' '.$MBR_NAME.' telah resmi menjadi nasabah '.$web_name_full.' sejak tanggal 
                        '.date('d', strtotime($ACC_F_DISC_DATE)).' '.$date_month.' '.date('Y', strtotime($ACC_F_DISC_DATE)).'. Dengan nomor account '.$ACC_LOGIN.' berdasarkan Perjanjian Pemberian 
                        Amanat yang '.$bapakatauibu.' '.$MBR_NAME.' telah isi dan setujui berdasarkan ketentuan Peraturan 
                        Bappebti Nomor 10 Tahun 2021 tentang Penerimaan Nasabah secara Elektronik 
                        Online dengan Customer Due Diligence (CDD) Sederhana di bidang Perdagangan 
                        Berjangka Komoditi, serta telah mengisi dan menyetujui dokumen sebagai berikut:

                        <!-- Bahwa '.$bapakatauibu.' '.$MBR_NAME.' telah resmi menjadi Nasabah '.$web_name_full.' sejak tanggal '.date('d', strtotime($ACC_F_DISC_DATE)).' '.$date_month.' '.date('Y', strtotime($ACC_F_DISC_DATE)).' dengan nomor account new account berdasarkan Perjanjian Pemberian Amanat yang '.$bapakatauibu.' '.$MBR_NAME.' telah isi dan
                        setujui berdasarkan ketentuan Peraturan Kepala Bappebti Nomor 9/BAPPEBTI/PER/11/2021 pasal 1 ayat 5n Tentang Penerimaan Nasabah Secara Elektronik On-line Di Bidang Perdagangan Berjangka Komoditi sebagaimana telah diubah dengan
                        Peraturan Kepala Bappebti NOMOR 9 TAHUN 2021, serta telah mengisi dan menyetujui dokumen sebagai berikut: -->
                    </p>
                    <ol class="text-justify">
                        <li>Pernyataan telah melakukan simulasi Perdagangan Berjangka atau pernyataan telah berpengalaman dalam melaksanakan transaksi Perdagangan Berjangka;</li>
                        <li>Profil Nasabah dan aplikasi pembukaan rekening;</li>
                        <li>Dokumen Pemberitahuan Adanya Risiko;</li>
                        <li>Perjanjian Pemberian Amanat;</li>
                        <li>Peraturan Perdagangan (trading rules); dan</li>
                        <li>Pernyataan Dari Nasabah Untuk Tidak Menyerahkan Kode Akses Transaksi Nasabah (<i>Personal Access Password</i>) Ke Pihak Lain.</li>
                    </ol>
                    <div style="margin-bottom: 100px;"></div>
                    <p>Dengan membaca, mengisi dan menyetujui dokumen sebagaimana dimaksud di atas, dengan demikian '.$bapakatauibu.' '.$MBR_NAME.' telah:</p>
                    <ol class="text-justify">
                        <li>memahami dan mengerti resiko-resiko yang ada, termasuk kerugian atas seluruh dana yang Disetor;</li>
                        <li>memahami kewajiban dan hak selaku Nasabah Pialang Berjangka;</li>
                        <li>memahami dan mengerti mekanisme dan dan cara Perdagangan Berjangka;</li>
                        <li>
                            memahami untuk tidak membuat perjanjian dalam bentuk apapun baik secara lisan maupun tertulis dengan pegawai Pialang Berjangka atau pihak yang memiliki kepentingan dengan Pialang Berjangka diluar Perjanjian
                            Perdagangan Berjangka dan peraturan perdagangan (trading rules) antara Nasabah dengan '.$web_name_full.'
                        </li>
                        <li>
                            memahami untuk bertanggungjawab sepenuhnya terhadap nama pengguna (user id) dan kode akses transaksi Nasabah (<i>Personal Access Password</i>), dan tidak menyerahkan nama pengguna (user id) dan kode akses transaksi
                            Nasabah (<i>Personal Access Password</i>) ke pihak lain, terutama kepada pegawai Pialang Berjangka atau pihak yang memiliki kepentingan Pialang Berjangka;
                        </li>
                        <li>melakukan simulasi atau mengerti mekanisme transaksi Perdagangan Berjangka;</li>
                        <li>memahami mengenai peraturan perdagangan (trading rules) antara Nasabah dengan '.$web_name_full.';</li>
                        <li>
                            memahami tentang mekanisme penggunaan Rekening Terpisah (segregated account), termasuk penyetoran dan penarikan dana, yakni akun keluar masuk dana wajib sama dengan akun yang didaftarkan dalam aplikasi
                            pembukaan rekening, dan pelaksanaannya wajib dilakukan melalui pindah buku/transfer, serta prosedur penarikan dana; dan
                        </li>
                        <li>
                            memahami dana yang dipergunakan dalam bertransaksi adalah dana milik pribadi, bukan
                            dari dan/atau milik pihak lain, atau berasal dari pencucian uang.
                        </li>
                    </ol>
                    <div class="page-break"></div>
                    <table>
                        <tr>
                            <p class="text-justify">
                                Data yang kami terima dari '.$bapakatauibu.' '.$MBR_NAME.' akan kami rekam dan catat, dan sepenuhnya menjadi milik '.$web_name_full.'. Kami bertanggung jawab untuk menjaga
                                kerahasiaan data dan informasi '.$bapakatauibu.' '.$MBR_NAME.' sesuai dengan peraturan perundang-Undangan.
                            </p>
                        </tr>
                    </table>
                    <table width="100%">
                        <tr align="center">
                            <td width="50%">
                                <p class="style1">
                                    WAKIL PIALANG BERJANGKA<br />
                                    MEMBERITAHUKAN ADANYA RISIKO,
                                </p>
                            </td>
                            <td width="50%">
                                <span class="style1">
                                    Mengetahui<br />
                                    '.$web_name_full.'
                                </span>
                            </td>
                        </tr>
                        <tr align="center">
                            <td><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/ttd_agus.png")).'" width="50%"></td>
                            <td>&nbsp;<img src="data:image/png;base64,'.base64_encode(file_get_contents("https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/ttd_terry.png")).'" width="100%"></td>
                        </tr>
                        <tr align="center">
                            <td width="50%"><span class="style1"></span> ( '.$ACC_F_PERJ_WPB.' )</td>
                            <td width="50%"><span class="style1">( TERRY INDRADI OKTRIAWAN, SE )</span></td>
                        </tr>
                    </table>
                    <div style="margin-top:5px;">
                        <table>
                            <tr>
                                <td>Dikirim melalui email dan Terupload di Klien Kabinet <br> pada : <strong>'.date('Y-m-d H:i:s', strtotime($ACC_F_DISC_DATE)).'</td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        <p>*) Pilih salah satu </p>
                        <p>**) Direktur Utama/Direktur/KEPALA Cabang-isi sesuai pihak yang mengetahui dan berwenang menandatangani </p>
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
        $dompdf->stream("".$web_name_full." - 107.PBK.07.pdf",array("Attachment"=>0));
        exit(0);
    }
	// $output = $dompdf->output();
?>