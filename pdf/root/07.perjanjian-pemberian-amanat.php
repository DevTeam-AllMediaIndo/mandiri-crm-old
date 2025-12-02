
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
            tb_racc.ACC_TYPE,
            tb_racc.ACC_F_APP_KRJ_TYPE,
            tb_racc.ACC_F_APP_KRJ_JBTN,
            tb_racc.ACC_F_PERJ_DATE,
            tb_racc.ACC_F_PERJ_PERSLISIHAN,
            tb_racc.ACC_F_PERJ_KANTOR,
            tb_racc.ACC_F_PERJ_WPB,
            tb_racc.ACC_F_PERJ_IP,
            tb_member.MBR_ADDRESS
        FROM tb_racc
        JOIN tb_member
        ON(tb_member.MBR_ID = tb_racc.ACC_MBR)
        WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id_acc.'"
        LIMIT 1
    ');
    if(mysqli_num_rows($SQL_QUERY) > 0){
        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        $MBR_NAME = $RESULT_QUERY['MBR_NAME'];
        $ACC_TYPE = $RESULT_QUERY['ACC_TYPE'];
        $ACC_F_PERJ_IP = $RESULT_QUERY['ACC_F_PERJ_IP'];
        if($ACC_TYPE == 2){
            $JUDUL = 'PERJANJIAN PEMBERIAN AMANAT SECARA ELEKTRONIK ONLINE<br>
            UNTUK TRANSAKSI KONTRAK BERJANGKA';
            $title = 'berjangka';
            $FOOTER = 'BERJANGKA';
            $DEKA = 'berjangka';
            $pasal3 = '
                <li>
                    <span>3. Antisipasi penyerahan barang</span>
                    <ol class="text-justify">
                        <li seq=" (1)">Untuk kontrak-kontrak tertentu penyelesaian transaksi dapat dilakukan dengan
                        penyerahan atau penerimaan barang (<i>delivery</i>) apabila kontrak jatuh tempo.
                        Nasabah menyadari bahwa penyerahan atau penerimaan barang mengandung
                        risiko yang lebih besar daripada melikuidasi posisi dengan <i>offset</i>. Penyerahan fisik
                        barang memiliki konsekuensi kebutuhan dana yang lebih besar serta tambahan
                        biaya pengelolaan barang.</li>
                        <li seq=" (2)">Pialang Berjangka tidak bertanggung jawab atas klasifikasi mutu (<i>grade</i>), kualitas
                        atau tingkat toleransi atas komoditi yang diserahkan atau akan diserahkan.</li>
                        <li seq=" (3)">Pelaksanaan penyerahan atau penerimaan barang tersebut akan diatur dan
                        dijamin oleh Lembaga Kliring Berjangka.</li>
                    </ol>
                    <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                </li>
            ';
            $pasal2 = '
                <li>
                    <div style="margin-bottom:5px;"><span>2. Pelaksanaan Amanat</span></div>
                    <ol class="text-justify">
                        <li seq=" (1)">
                            Setiap amanat yang disampaikan oleh Nasabah atau kuasanya yang 
                            ditunjuk secara tertulis oleh Nasabah, dianggap sah apabila 
                            diterima oleh Pialang Berjangka sesuai dengan ketentuan yang 
                            berlaku, dapat berupa amanat tertulis yang ditandatangani oleh 
                            Nasabah atau kuasanya, amanat telepon yang direkam, dan/atau 
                            amanat transaksi elektronik lainnya. 
                        </li>
                        <li seq=" (2)">
                            Setiap amanat Nasabah yang diterima dapat langsung dilaksanakan 
                            sepanjang nilai Margin yang tersedia pada rekeningnya mencukupi 
                            dan eksekusinya tergantung pada kondisi dan sistem transaksi yang 
                            berlaku yang mungkin dapat menimbulkan perbedaan waktu 
                            terhadap proses pelaksanaan amanat tersebut. Nasabah harus 
                            mengetahui posisi Margin dan posisi terbuka sebelum memberikan 
                            amanat untuk transaksi berikutnya.
                        </li>
                        <li seq=" (3)">
                            Amanat Nasabah hanya dapat dibatalkan dan/atau diperbaiki 
                            apabila transaksi atas amanat tersebut belum terjadi. Pialang 
                            Berjangka tidak bertanggung jawab atas kerugian yang timbul 
                            akibat tidak terlaksananya pembatalan dan/atau perbaikan 
                            sepanjang bukan karena kelalaian Pialang Berjangka.
                        </li>
                        <li seq=" (4)">
                            Pialang Berjangka berhak menolak amanat Nasabah apabila harga 
                            yang ditawarkan atau diminta tidak wajar. 
                        </li>
                        <li seq=" (5)">
                            Nasabah bertanggung jawab atas keamanan dan penggunaan 
                            <i>username</i> dan <i>password</i> dalam transaksi Perdagangan Berjangka, 
                            oleh karenanya Nasabah dilarang memberitahukan, menyerahkan 
                            atau meminjamkan <i>username</i> dan <i>password</i> kepada pihak lain, 
                            termasuk kepada pegawai Pialang Berjangka.  
                        </li>
                    </ol>
                    <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                </li>
            ';
            $num_pass3 = 4;
            $num_pass4_aftr = 1;
            $pasal64belas = '
                <ol type="1">
                    <li seq="(1)">Nasabah memberikan kuasa kepada Pialang Berjangka untuk menghubungi bank, lembaga keuangan, Pialang Berjangka lain, atau institusi lain yang terkait untuk memperoleh keterangan atau verifikasi mengenai informasi yang diterima dari Nasabah. Nasabah mengerti bahwa penelitian mengenai data hutang pribadi dan bisnis dapat dilakukan oleh Pialang Berjangka apabila diperlukan. Nasabah diberikan kesempatan untuk memberitahukan secara tertulis dalam jangka waktu yang telah disepakati untuk melengkapi persyaratan yang diperlukan.</li>
                    <li seq="(2)">Nasabah dapat juga memberikan kuasa kepada pihak lain (bukan Pengurus Pialang Berjangka, bukan Wakil Pialang Berjangka yang menanda-tangani perjanjian ini dan bukan pegawai Pialang Berjangka yang jabatannya satu tingkat di bawah Direksi) yang ditunjuk oleh Nasabah untuk menjalankan hak-hak yang timbul atas rekening, termasuk memberikan instruksi kepada Pialang Berjangka atas rekening yang dimiliki Nasabah, berdasarkan surat kuasa dalam bentuk dan isi yang tidak bertentangan dengan ketentuan Peraturan Perundang-undangan.</li>
                </ol><br>
            ';
        } else if($ACC_TYPE == 1){
            $JUDUL = 'PERJANJIAN PEMBERIAN AMANAT SECARA ELEKTRONIK ONLINE<br>
            UNTUK TRANSAKSI KONTRAK DERIVATIF<br>
            DALAM SISTEM PERDAGANGAN ALTERNATIF';
            $title = 'Derivatif dalam Sistem Perdagangan Alternatif';
            $FOOTER = 'DERIVATIF SISTEM PERDAGANGAN ALTERNATIF';
            $DEKA = 'derivatif';
            $pasal3 = '';
            $pasal2 = '
                <li>
                    <div style="margin-bottom:15px;"><span>2. Pelaksanaan Transaksi</span></div>
                    <ol class="text-justify">
                        <li seq=" (1)">Setiap transaksi Nasabah dilaksanakan secara elektronik online oleh Nasabah
                        yang bersangkutan;</li>
                        <li seq=" (2)">Setiap amanat Nasabah yang diterima dapat langsung dilaksanakan sepanjang
                        nilai Margin yang tersedia pada rekeningnya mencukupi dan eksekusinya dapat
                        menimbulkan perbedaan waktu terhadap proses pelaksanaan transaksi
                        tersebut. Nasabah harus mengetahui posisi Margin dan posisi terbuka sebelum
                        memberikan amanat untuk transaksi berikutnya.</li>
                        <li seq=" (3)">Setiap transaksi Nasabah secara bilateral dilawankan dengan Penyelenggara Sistem Perdagangan 
                        Alternatif PT. Real Time Forex Indonesia yang bekerjasama dengan Pialang Berjangka.
                        </li>
                        <li seq=" (4)">Nasabah bertanggung jawab atas keamanan dan peggunaan username dan password 
                        dalam transaksi Perdagangan Berjangka, Nasabah dilarang memberitahukan, mnyerahkan atau 
                        meminjamkan username dan password kepada pihak lain, termasuk kepada pegawai Pialang Berjangka.</li>
                    </ol>
                    <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                </li>
            ';
            $num_pass3 = 3;
            $num_pass4_aftr = 0;
            $pasal64belas = '
                <p style="padding-left:5px;">Nasabah memberikan kuasa kepada Pialang Berjangka untuk menghubungi bank, lembaga keuangan, Pialang Berjangka lain, atau institusi lain yang terkait untuk memperoleh keterangan atau verifikasi mengenai informasi yang diterima dari Nasabah. Nasabah mengerti bahwa penelitian mengenai data hutang pribadi dan bisnis dapat dilakukan oleh Pialang Berjangka apabila diperlukan. Nasabah diberikan kesempatan untuk memberitahukan secara tertulis dalam jangka waktu yang telah disepakati untuk melengkapi persyaratan yang diperlukan.<br></p>
            ';
        } else {
            $JUDUL = '';
            $DEKA = '';
            $title = '';
            $pasal3 = '';
            $pasal2 = '';
            $num_pass3 = 3;
            $num_pass4_aftr = 0;
            $pasal64belas = '';
        }
        $ACC_F_APP_KRJ_TYPE = $RESULT_QUERY['ACC_F_APP_KRJ_TYPE'];
        $ACC_F_APP_KRJ_JBTN = $RESULT_QUERY['ACC_F_APP_KRJ_JBTN'];
        $MBR_ADDRESS = $RESULT_QUERY['MBR_ADDRESS'];
        $ACC_F_PERJ_DATE = $RESULT_QUERY['ACC_F_PERJ_DATE'];
        $ACC_F_PERJ_PERSLISIHAN = $RESULT_QUERY['ACC_F_PERJ_PERSLISIHAN'];
        $ACC_F_PERJ_KANTOR = $RESULT_QUERY['ACC_F_PERJ_KANTOR'];
        $ACC_F_PERJ_WPB = $RESULT_QUERY['ACC_F_PERJ_WPB'];
        if($ACC_F_PERJ_KANTOR == 'BAKTI'){
            $CHECK1 = 'checked="checked"';
            $CHECK2 = '';
        } else {
            $CHECK1 = '';
            $CHECK2 = 'checked="checked"';
        }
        if($ACC_F_PERJ_KANTOR == 'BAKTI' || $ACC_F_PERJ_KANTOR == 'Unknown'|| $ACC_F_PERJ_KANTOR == ''){
            $ACC_F_PERJ_KANTOR = 'BANDUNG';
        }
    } else {
        $MBR_NAME = '';
        $ACC_TYPE = '';
        $JUDUL = '';
        $ACC_F_APP_KRJ_TYPE = '';
        $ACC_F_APP_KRJ_JBTN = '';
        $MBR_ADDRESS = '';
        $ACC_F_PERJ_DATE = '';
        $ACC_F_PERJ_PERSLISIHAN = '';
        $ACC_F_PERJ_KANTOR = '';
        $ACC_F_PERJ_IP = '';
    };

    function callExtrTag($typ, $lst){
        global $num_pass4_aftr;
        $retval = '';
        if($typ == 2 && $lst == 6){
            $retval = '
                <li>
                    <span>'.($num_pass4_aftr + 5).'. Penggantian Kerugian Tidak Menyerahkan Barang</span>
                    <p style="padding-left:5px;">
                        Apabila Nasabah tidak mampu menyerahkan komoditi atas Kontrak 
                        Berjangka yang jatuh tempo, Nasabah memberikan kuasa kepada 
                        Pialang Berjangka untuk meminjam atau membeli komoditi untuk 
                        penyerahan tersebut. Nasabah wajib membayar secepatnya semua biaya, 
                        kerugian dan premi yang telah dibayarkan oleh Pialang Berjangka atas 
                        tindakan tersebut. Apabila Pialang Berjangka harus menerima 
                        penyerahan komoditi atau surat berharga maka Nasabah bertanggung 
                        jawab atas penurunan nilai dari komoditi atas surat berharga tersebut. 
                    </p>
                    <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                </li>
            ';
            $num_pass4_aftr++;
        }else if($typ == 1 && $lst == 24){
            $retval = '
                <li>
                    <span>'.($num_pass4_aftr + 22).'. Tanggung Jawab Kepada Nasabah</span>
                    <ol>
                        <li seq=" a.">
                            Penyelenggara Sistem Perdagangan Alternatif yang merupakan pihak yang menguasai dan/atau memiliki sistem perdagangan elektronik bertanggung jawab atas pelanggaran 
                            penyalahgunaan sistem perdagangan elektronik sesuai dengan ketentuan yang diatur dalam Perjanjian Kerjasama (PKS) dan peraturan perdagangan (trading rules) 
                            antara Penyelenggara Sistem Perdagangan Alternatif dan Peserta Sistem Perdagangan Alternatif yang mengakibatkan kerugian Nasabah.
                        </li>
                        <li seq=" b.">
                            Peserta Sistem Perdagangan Alternatif yang merupakan pihak yang menggunakan sistem perdagangan 
                            elektronik bertanggung jawab atas pelanggaran penyalahgunaan sistem perdagangan elektronik 
                            sebagaimana dimaksud pada angka 22 huruf (a) yang mengakibatkan kerugian Nasabah.
                        </li>
                        <li seq=" c.">
                            Dalam pemanfaatan sistem perdagangan elektronik, 
                            Penyelenggara Sistem Perdagangan Alternatif dan/atau Peserta Sistem Perdagangan 
                            Alternatif tidak bertanggung jawab atas kerugian Nasabah diluar hal-hal yang telah diatur pada 
                            angka 22 huruf (a) dan (b), antara lain: kerugian yang diakibatkan oleh risiko-risiko yang 
                            disebutkan di dalam Dokumen Pemberitahuan Adanya Risiko yang telah dimengerti dan disetujui 
                            oleh Nasabah.
                        </li>
                    </ol>
                    <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                </li>
            ';
            $num_pass4_aftr++;
        }
        return $retval;
    }
    
    if(strtolower(date('l', strtotime($ACC_F_PERJ_DATE))) == strtolower('Monday')){ $date_day = 'Senin';
    } else if(strtolower(date('l', strtotime($ACC_F_PERJ_DATE))) == strtolower('Tuesday')){ $date_day = 'Selasa';
    } else if(strtolower(date('l', strtotime($ACC_F_PERJ_DATE))) == strtolower('wednesday')){ $date_day = 'Rabu';
    } else if(strtolower(date('l', strtotime($ACC_F_PERJ_DATE))) == strtolower('thursday')){ $date_day = 'Kamis';
    } else if(strtolower(date('l', strtotime($ACC_F_PERJ_DATE))) == strtolower('Friday')){ $date_day = 'Jumat';
    } else if(strtolower(date('l', strtotime($ACC_F_PERJ_DATE))) == strtolower('Saturday')){ $date_day = 'Sabtu';
    } else if(strtolower(date('l', strtotime($ACC_F_PERJ_DATE))) == strtolower('Sunday')){ $date_day = 'Minggu';
    };

    if(strtolower(date('F', strtotime($ACC_F_PERJ_DATE))) == strtolower('January')){ $date_month = 'Januari';
    } else if(strtolower(date('F', strtotime($ACC_F_PERJ_DATE))) == strtolower('February')){ $date_month = 'Februari';
    } else if(strtolower(date('F', strtotime($ACC_F_PERJ_DATE))) == strtolower('March')){ $date_month = 'Maret';
    } else if(strtolower(date('F', strtotime($ACC_F_PERJ_DATE))) == strtolower('April')){ $date_month = 'April';
    } else if(strtolower(date('F', strtotime($ACC_F_PERJ_DATE))) == strtolower('May')){ $date_month = 'Mei';
    } else if(strtolower(date('F', strtotime($ACC_F_PERJ_DATE))) == strtolower('June')){ $date_month = 'Juni';
    } else if(strtolower(date('F', strtotime($ACC_F_PERJ_DATE))) == strtolower('July')){ $date_month = 'Juli';
    } else if(strtolower(date('F', strtotime($ACC_F_PERJ_DATE))) == strtolower('August')){ $date_month = 'Agustus';
    } else if(strtolower(date('F', strtotime($ACC_F_PERJ_DATE))) == strtolower('September')){ $date_month = 'September';
    } else if(strtolower(date('F', strtotime($ACC_F_PERJ_DATE))) == strtolower('October')){ $date_month = 'Oktober';
    } else if(strtolower(date('F', strtotime($ACC_F_PERJ_DATE))) == strtolower('November')){ $date_month = 'November';
    } else if(strtolower(date('F', strtotime($ACC_F_PERJ_DATE))) == strtolower('December')){ $date_month = 'Desember';
    };

    if($ACC_F_PERJ_PERSLISIHAN == 'Kantor Pusat'){
        $ckota1 = 'checked';
        $ckota2 = ' ';
        $ckota3 = ' ';
    }else if($ACC_F_PERJ_PERSLISIHAN == 'Yogyakarta'){
        $ckota1 = ' ';
        $ckota2 = 'checked';
        $ckota3 = ' ';
    }else if($ACC_F_PERJ_PERSLISIHAN == 'Medan'){
        $ckota1 = ' ';
        $ckota2 = ' ';
        $ckota3 = 'checked';
    }else{
        $ckota1 = ' ';
        $ckota2 = ' ';
        $ckota3 = ' ';
    }

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
                
                <style>
                    ol {
                        list-style: none;
                        margin-left: 0;
                        padding-left: 0;
                    }
                    li {
                        display: block;
                        margin-bottom: .5em;
                        margin-left: 2.5em;
                    }
                    li::before {
                        display: inline-block;
                        content: attr(seq);
                        width: 2em;
                        margin-left: -2em;
                    }
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
                        <p>'.$JUDUL.'</p>
                    </div>
                    
                    <div style="text-align:center;border:3px solid black;vertical-align: middle;padding: 2px;">
                        <div class="text-center" style="border:1px solid black;vertical-align: middle;padding: 10px 0;">
                            <span>PERHATIAN !</span><br>
                            PERJANJIAN INI MERUPAKAN KONTRAK HUKUM. HARAP DIBACA<br>DENGAN SEKSAMA
                        </div>
                    </div>
                    
                    <p class="text-justify">Pada hari ini '.$date_day.', tanggal '.date('d', strtotime($ACC_F_PERJ_DATE)).', bulan '.$date_month.', tahun '.date('Y', strtotime($ACC_F_PERJ_DATE)).', kami
                    yang mengisi perjanjian di bawah ini:</p>
                    <table width="100%">
                        <tr>
                            <td rowspan="3" width="1%" style="padding:0px 5px;white-space:nowrap;vertical-align: top;"> 1. </td>
                            <td width="1%" style="padding:0px 5px;white-space:nowrap;" > Nama </td>
                            <td width="1%" style="padding:0px 5px;white-space:nowrap;"> : </td>
                            <td> '.$MBR_NAME.'</td>
                        </tr>
                        <tr>
                            <td width="1%" style="padding:0px 5px;white-space:nowrap;" > Pekerjaan / Jabatan </td>
                            <td width="1%" style="padding:0px 5px;white-space:nowrap;"> : </td>
                            <td> '.$ACC_F_APP_KRJ_TYPE.' / '.$ACC_F_APP_KRJ_JBTN.'</td>
                        </tr>
                        <tr>
                            <td width="1%" style="padding:0px 5px;white-space:nowrap;vertical-align: top;" > Alamat </td>
                            <td width="1%" style="padding:0px 5px;white-space:nowrap;vertical-align: top;"> : </td>
                            <td> '.$MBR_ADDRESS.'</td>
                        </tr>
                    </table>
                    <p class="text-justify">dalam hal ini bertindak untuk dan atas nama sendiri, yang selanjutnya di sebut Nasabah,</p>
                    <table width="100%">
                        <tr>
                            <td rowspan="3" width="1%" style="padding:0px 5px;white-space:nowrap;vertical-align: top;"> 2. </td>
                            <td width="1%" style="padding:0px 5px;white-space:nowrap;" > Nama </td>
                            <td width="1%" style="padding:0px 5px;white-space:nowrap;"> : </td>
                            <td>'.$ACC_F_PERJ_WPB.'</td>
                        </tr>
                        <tr>
                            <td width="1%" style="padding:0px 5px;white-space:nowrap;" > Pekerjaan / Jabatan </td>
                            <td width="1%" style="padding:0px 5px;white-space:nowrap;"> : </td>
                            <td> (Petugas Wakil Pialang yang Ditunjuk Memverifikasi)</td>
                        </tr>
                        <tr>
                            <td width="1%" style="padding:0px 5px;white-space:nowrap;vertical-align: top;" > Alamat </td>
                            <td width="1%" style="padding:0px 5px;white-space:nowrap;vertical-align: top;"> : </td>
                            <td>'.$setting_central_office_address.'</td>
                        </tr>
                    </table>
                    <p class="text-justify">dalam hal ini bertindak untuk dan atas nama <span>'.$web_name_full.'</span> yang selanjutnya
                    disebut <span>Pialang Berjangka</span>,</p>
                    <p class="text-justify">Nasabah dan Pialang Berjangka secara bersama – sama selanjutnya disebut Para Pihak.</p>
                    <p class="text-justify">Para Pihak sepakat untuk mengadakan Perjanjian Pemberian Amanat untuk melakukan
                    transaksi penjualan maupun pembelian Kontrak '.ucwords($title).' dalam Sistem Perdagangan
                    Alternatif dengan ketentuan sebagai berikut:</p>
                    <ol class="text-justify">
                        <li>
                            <div style="margin-bottom:15px;"><span>1. Margin dan Pembayaran Lainnya</span></div>
                            <ol class="text-justify">
                                <li seq=" (1)">Nasabah menempatkan sejumlah dana (Margin) ke Rekening Terpisah (<i>Segregated Account</i>) Pialang Berjangka sebagai Margin Awal dan wajib mempertahankannya sebagaimana ditetapkan.</li>
                                <li seq=" (2)">membayar biaya-biaya yang diperlukan untuk transaksi, yaitu biaya transaksi, pajak, komisi, dan biaya pelayanan, biaya bunga sesuai tingkat yang berlaku, dan biaya lainnya yang dapat dipertanggungjawabkan berkaitan dengan transaksi sesuai amanat Nasabah, maupun biaya rekening Nasabah.</li>
                            </ol><br>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        '.$pasal2.'
                        '.$pasal3.'
                        <div class="page-break"></div>
                        <li>
                            <div><span>'.$num_pass3.'. Kewajiban Memelihara Margin</span></div>
                            <ol class="text-justify">
                                <li seq=" (1)">Nasabah wajib memelihara/memenuhi tingkat Margin yang harus tersedia di rekening pada Pialang Berjangka sesuai dengan jumlah yang telah ditetapkan baik diminta ataupun tidak oleh Pialang Berjangka.</li>
                                <li seq=" (2)">Apabila jumlah Margin memerlukan penambahan maka Pialang Berjangka wajib memberitahukan dan memintakan kepada Nasabah untuk menambah Margin segera.</li>
                                <li seq=" (3)">Apabila jumlah Margin memerlukan tambahan (<i>Call Margin</i>) maka Nasabah wajib melakukan penyerahan <i>Call Margin</i> selambat-lambatnya sebelum dimulai hari perdagangan berikutnya. Kewajiban Nasabah sehubungan dengan penyerahan <i>Call Margin</i> tidak terbatas pada jumlah Margin awal.</li>
                                <li seq=" (4)">Pialang Berjangka tidak berkewajiban melaksanakan amanat untuk melakukan transaksi yang baru dari Nasabah sebelum <i>Call Margin</i> dipenuhi.</li>
                                <li seq=" (5)">Untuk memenuhi kewajiban <i>Call Margin</i> dan keuangan lainnya dari Nasabah, Pialang Berjangka dapat mencairkan dana Nasabah yang ada di Pialang Berjangka.</li>
                            </ol>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li>
                            <span>'.($num_pass4_aftr + 4).'. Hak Pialang Berjangka Melikuidasi Posisi Nasabah</span>
                            <p style="padding-left:5px;">Nasabah bertanggung jawab memantau/mengetahui posisi terbukanya secara terus- menerus dan memenuhi kewajibannya. Apabila dalam jangka waktu tertentu dana pada rekening Nasabah kurang dari yang dipersyaratkan, Pialang Berjangka dapat menutup posisi terbuka Nasabah secara keseluruhan atau sebagian, membatasi transaksi, atau tindakan lain untuk melindungi diri dalam pemenuhan Margin tersebut dengan terlebih dahulu memberitahu atau tanpa memberitahu Nasabah dan Pialang Berjangka tidak bertanggung jawab atas kerugian yang timbul akibat tindakan tersebut.
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        '.callExtrTag($ACC_TYPE, 6).'
                        <div class="page-break"></div>
                        <li>
                            <span>'.($num_pass4_aftr + 5).'. Penggantian Kerugian Tidak Adanya Penutupan Posisi
                            <p>Apabila Nasabah tidak mampu melakukan penutupan atas transaksi yang jatuh tempo, Pialang Berjangka dapat melakukan penutupan atas transaksi Nasabah yang terjadi. Nasabah wajib membayar biaya-biaya, termasuk biaya kerugian dan premi yang telah dibayarkan oleh Pialang Berjangka, dan apabila Nasabah lalai untuk membayar biaya-biaya tersebut, Pialang Berjangka berhak untuk mengambil pembayaran dari dana Nasabah.</p></span>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li>
                            <span>'.($num_pass4_aftr + 6).'. Pialang Berjangka Dapat Membatasi Posisi</span>
                            <p>Nasabah mengakui hak Pialang Berjangka untuk membatasi posisi terbuka Kontrak dan Nasabah tidak melakukan transaksi melebihi batas yang telah ditetapkan tersebut.</p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li style="margin-bottom: 0px;">
                            <span>'.($num_pass4_aftr + 7).'.Tidak Ada Jaminan atas Informasi atau Rekomendasi</span>
                            <p style="padding-left:5px;">Nasabah mengakui bahwa :</p>
                            <ol>
                                <li seq=" (1)">Informasi dan rekomendasi yang diberikan oleh Pialang Berjangka kepada Nasabah tidak selalu lengkap dan perlu diverifikasi.</li>
                                <li seq=" (2)">Pialang Berjangka tidak menjamin bahwa informasi dan rekomendasi yang diberikan merupakan informasi yang akurat dan lengkap.</li>
                                <li seq=" (3)">Informasi dan rekomendasi yang diberikan oleh Wakil Pialang Berjangka yang satu dengan yang lain mungkin berbeda karena perbedaan analisis fundamental atau teknikal. Nasabah menyadari bahwa ada kemungkinan Pialang Berjangka dan pihak terafiliasinya memiliki posisi di pasar dan memberikan rekomendasi tidak konsisten kepada Nasabah.</li>
                            </ol>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <div class="page-break"></div>
                        <li>
                            <div style="margin-bottom:15px;"><span>'.($num_pass4_aftr + 8).'. Pembatasan Tanggung Jawab Pialang Berjangka.</span></div>
                            <ol>
                                <li seq=" (1)">Pialang Berjangka tidak bertanggung jawab untuk memberikan penilaian kepada Nasabah mengenai iklim, pasar, keadaan politik dan ekonomi nasional dan internasional, nilai Kontrak '.$DEKA.', kolateral, atau memberikan nasihat mengenai keadaan pasar. Pialang Berjangka hanya memberikan pelayanan untuk melakukan transaksi secara jujur serta memberikan laporan atas transaksi tersebut.</li>
                                <li seq=" (2)">Perdagangan sewaktu-waktu dapat dihentikan oleh pihak yang memiliki otoritas (Bappebti/Bursa Berjangka) tanpa pemberitahuan terlebih dahulu kepada Nasabah. Atas posisi terbuka yang masih dimiliki oleh Nasabah pada saat perdagangan tersebut dihentikan, maka akan diselesaikan (likuidasi) berdasarkan pada peraturan/ketentuan yang dikeluarkan dan ditetapkan oleh pihak otoritas tersebut, dan semua kerugian serta biaya yang timbul sebagai akibat dihentikannya transaksi oleh pihak otoritas perdagangan tersebut, menjadi beban dan tanggung jawab Nasabah sepenuhnya.</li>
                            </ol>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li>
                            <span>'.($num_pass4_aftr + 9).'. Transaksi Harus Mematuhi Peraturan Yang Berlaku</span>
                            <p> Semua transaksi dilakukan sendiri oleh Nasabah dan wajib mematuhi peraturan perundang-undangan di bidang Perdagangan Berjangka, kebiasaan dan interpretasi resmi yang ditetapkan oleh Bappebti atau Bursa Berjangka.</p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li>
                            <span>'.($num_pass4_aftr + 10).'. Pialang Berjangka tidak Bertanggung jawab atas Kegagalan Komunikasi</span>
                            <p style="padding-left:5px;">Pialang Berjangka tidak bertanggung jawab atas keterlambatan atau tidak tepat waktunya pengiriman amanat atau informasi lainnya yang disebabkan oleh kerusakan fasilitas komunikasi atau sebab lain diluar kontrol Pialang Berjangka.<br>
                            </p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <div class="page-break"></div>
                        <li>
                            <div style="margin-bottom:15px;"><span>'.($num_pass4_aftr + 11).'. Konfirmasi</span></div>
                            <ol>
                                <li seq=" (1)">Konfirmasi dari Nasabah dapat berupa surat, telex, media lain, surat elektronik, secara tertulis ataupun rekaman suara.</li>
                                <li seq=" (2)">Pialang Berjangka berkewajiban menyampaikan konfirmasi transaksi, laporan rekening, permintaan <i>Call Margin</i>, dan pemberitahuan lainnya kepada Nasabah secara akurat, benar dan secepatnya pada alamat (email) Nasabah sesuai dengan yang tertera dalam rekening Nasabah. Apabila dalam jangka waktu 2 x 24 jam setelah amanat jual atau beli disampaikan, tetapi Nasabah belum menerima konfirmasi melalui alamat email Nasabah dan/atau sistem transaksi, Nasabah segera memberitahukan hal tersebut kepada Pialang Berjangka melalui telepon dan disusul dengan pemberitahuan tertulis.</li>
                                <li seq=" (3)">Jika dalam waktu 2 x 24 jam sejak tanggal penerimaan konfirmasi tersebut tidak ada sanggahan dari Nasabah maka konfirmasi Pialang Berjangka dianggap benar dan sah.</li>
                                <li seq=" (4)">Kekeliruan atas konfirmasi yang diterbitkan Pialang Berjangka akan diperbaiki oleh Pialang Berjangka sesuai keadaan yang sebenarnya dan demi hukum konfirmasi yang lama batal.</li>
                                <li seq=" (5)">Nasabah tidak bertanggung jawab atas transaksi yang dilaksanakan atas rekeningnya apabila konfirmasi tersebut tidak disampaikan secara benar dan akurat.</li>
                            </ol>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li>
                            <span>'.($num_pass4_aftr + 12).'. Kebenaran Informasi Nasabah</span>
                            <p style="padding-left:5px;">Nasabah memberikan informasi yang benar dan akurat mengenai data Nasabah yang diminta oleh Pialang Berjangka dan akan memberitahukan paling lambat dalam waktu 3 (tiga) hari kerja setelah terjadi perubahan, termasuk perubahan kemampuan keuangannya untuk terus melaksanakan transaksi.<br></p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li style="margin-bottom: 0px;">
                            <span>'.($num_pass4_aftr + 13).'. Komisi Transaksi</span>
                            <p style="padding-left:5px;">Nasabah mengetahui dan menyetujui bahwa Pialang Berjangka berhak untuk memungut komisi atas transaksi yang telah dilaksanakan, dalam jumlah sebagaimana akan ditetapkan dari waktu ke waktu oleh Pialang Berjangka. Perubahan beban (fees) dan biaya lainnya harus disetujui secara tertulis oleh Para Pihak.<br></p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <div class="page-break"></div>
                        <li>
                            <span>'.($num_pass4_aftr + 14).'. Pemberian Kuasa</span>
                            '.$pasal64belas.'
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li style="margin-bottom: 0px;">
                            <span>'.($num_pass4_aftr + 15).'. Pemindahan Dana</span>
                            <p style="padding-left:5px;">Pialang Berjangka dapat setiap saat mengalihkan dana dari satu rekening ke rekening lainnya berkaitan dengan kegiatan transaksi yang dilakukan Nasabah seperti pembayaran komisi, pembayaran biaya transaksi, kliring dan keterlambatan dalam memenuhi kewajibannya, tanpa terlebih dahulu memberitahukan kepada Nasabah. Transfer yang telah dilakukan akan segera diberitahukan secara tertulis kepada Nasabah</p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <div class="page-break"></div>
                        <li style="margin-top:0px;">
                            <div style="margin-bottom:0px;"><span>'.($num_pass4_aftr + 16).'. Pemberitahuan</span></div>
                            <ol>
                                <li seq=" (1)">Semua komunikasi, uang, surat berharga, dan kekayaan lainnya harus dikirimkan langsung ke alamat Nasabah seperti tertera dalam rekeningnya atau alamat lain yang ditetapkan/diberitahukan secara tertulis oleh Nasabah.</li>
                                <li seq=" (2)">
                                    Semua uang, harus disetor atau ditransfer langsung oleh Nasabah ke Rekening Terpisah (Segregated Account) Pialang Berjangka:
                                    <table width="100%" style="margin-left:5px;">
                                        <tr>
                                            <td width="36%">Nama</td>
                                            <td width="2%">&nbsp;:&nbsp;</td>
                                            <td>'.$web_name_full.'</td>
                                        </tr>
                                        <tr>
                                            <td valign="top">Alamat</td>
                                            <td valign="top">&nbsp;:&nbsp;</td>
                                            <td>
                                                '.$setting_central_office_address.'
                                            </td>
                                        </tr>
                                        <!-- <tr>
                                            <td>Bank</td>
                                            <td>&nbsp;:&nbsp;</td>
                                            <td>Bank Central Asia (Bank BCA)</td>
                                        </tr> -->
                                        <tr>
                                            <td>Bank</td>
                                            <td>&nbsp;:&nbsp;</td>
                                            <td>Bank Central Asia (Bank BCA)</td>
                                        </tr>
                                        <tr>
                                            <td valign="top">No. Rekening Terpisah</td>
                                            <td valign="top">&nbsp;:&nbsp;</td>
                                            <td>
                                                <ol>
                                                    <li>0105 220222 (IDR)</li>
                                                </ol>
                                                <br>
                                            </td>
                                        </tr>
                                    </table>
                                    <div style="margin-left:30px;"></div>
                                    dan dianggap sudah diterima oleh Pialang Berjangka apabila sudah ada tanda terima bukti setor atau transfer dari pegawai Pialang Berjangka.
                                </li>
                                <li seq=" (3)">
                                    Semua surat berharga, kekayaan lainnya, atau komunikasi harus dikirim kepada Pialang Berjangka:
                                    <table width="100%" style="margin-left:5px;">
                                        <tr>
                                            <td width="36%">Nama</td>
                                            <td width="2%">&nbsp;:&nbsp;</td>
                                            <td>'.$web_name_full.'</td>
                                        </tr>
                                        <tr>
                                            <td valign="top">Alamat</td>
                                            <td valign="top">&nbsp;:&nbsp;</td>
                                            <td>
                                                '.$setting_central_office_address.'
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Telepon</td>
                                            <td>&nbsp;:&nbsp;</td>
                                            <td>'.$setting_office_number.'</td>
                                        </tr>
                                        <tr>
                                            <td>Facsimile</td>
                                            <td>&nbsp;:&nbsp;</td>
                                            <td>'.$setting_fax_number.'</td>
                                        </tr>
                                        <tr>
                                            <td>E-mail</td>
                                            <td>&nbsp;:&nbsp;</td>
                                            <td>'.$setting_email_wp.'</td>
                                        </tr>
                                    </table>
                                    dan dianggap sudah diterima oleh Pialang Berjangka apabila sudah ada tanda bukti penerimaan dari pegawai Pialang Berjangka.
                                </li>
                            </ol>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <div class="page-break"></div>
                        <li style="margin-bottom: 0px;">
                            <span>'.($num_pass4_aftr + 17).'.Dokumen Pemberitahuan Adanya Risiko</span>
                            <p style="padding-left:5px;">Nasabah mengakui menerima dan mengerti Dokumen Pemberitahuan Adanya Risiko.<br></p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li>
                            <div style="margin-bottom:15px;"><span>'.($num_pass4_aftr + 18).'.Jangka Waktu Perjanjian dan Pengakhiran</span></div>
                            <ol>
                                <li seq=" (1)">Perjanjian ini mulai berlaku terhitung sejak tanggal dilakukannya konfirmasi oleh Pialang Berjangka dengan diterimanya Bukti Konfirmasi Penerimaan Nasabah dari Pialang Berjangka oleh Nasabah.</li>
                                <li seq=" (2)">Nasabah dapat mengakhiri Perjanjian ini hanya jika Nasabah sudah tidak lagi memiliki posisi terbuka dan tidak ada kewajiban Nasabah yang diemban oleh atau terhutang kepada Pialang Berjangka.</li>
                                <li seq=" (3)">Pengakhiran tidak membebaskan salah satu Pihak dari tanggung jawab atau kewajiban yang terjadi sebelum pemberitahuan tersebut.</li>
                            </ol>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li style="margin-top:10px;">
                            <span>'.($num_pass4_aftr + 19).'. Berakhirnya Perjanjian</span>
                            <p style="padding-left:5px;">Perjanjian dapat berakhir dalam hal Nasabah:</p>
                            <ol>
                                <li seq=" (1)">dinyatakan pailit, memiliki hutang yang sangat besar, dalam proses peradilan, menjadi hilang ingatan, mengundurkan diri atau meninggal;</li>
                                <li seq=" (2)">tidak dapat memenuhi atau mematuhi perjanjian ini dan/atau melakukan pelanggaran terhadapnya;</li>
                                <li seq=" (3)">
                                    berkaitan dengan butir (1) dan (2) tersebut diatas, Pialang Berjangka dapat :                                                            
                                    <ol>
                                        <li seq=" i)">meneruskan atau menutup posisi Nasabah tersebut setelah mempertimbangkannya secara cermat dan jujur ; dan</li>
                                        <li seq=" ii)">menolak perintah dari  Nasabah atau kuasanya.</li>
                                    </ol>
                                </li>
                                <li seq=" (4)">Pengakhiran Perjanjian sebagaimana dimaksud dengan angka (1) dan (2) tersebut di atas tidak melepaskan kewajiban dari Para Pihak yang berhubungan dengan penerimaan atau kewajiban pembayaran atau pertanggungjawaban kewajiban lainnya yang timbul dari Perjanjian.</li>
                            </ol>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <div class="page-break"></div>
                        <li>
                            <span>'.($num_pass4_aftr + 20).'. <i>Force Majeur</i></span>
                            <p style="padding-left:5px;">
                                Tidak ada satupun pihak di dalam Perjanjian dapat diminta pertanggungjawabannya untuk suatu keterlambatan atau terhalangnya memenuhi kewajiban berdasarkan Perjanjian yang diakibatkan oleh suatu sebab yang berada di luar kemampuannya atau kekuasaannya (<i>force majeur</i>), sepanjang pemberitahuan tertulis mengenai sebab itu disampaikannya kepada pihak lain dalam Perjanjian dalam waktu tidak lebih dari 24 (dua puluh empat) jam sejak timbulnya sebab itu.<br>
                                Yang dimaksud dengan <i>Force Majeur</i> dalam Perjanjian adalah peristiwa kebakaran, bencana alam (seperti gempa bumi, banjir, angin topan, petir), pemogokan umum, huru hara, peperangan, perubahan terhadap peraturan perundang-undangan yang berlaku dan kondisi di bidang ekonomi, keuangan dan Perdagangan Berjangka, pembatasan yang dilakukan oleh otoritas Perdagangan Berjangka dan Bursa Berjangka serta terganggunya sistem perdagangan, kliring dan penyelesaian transaksi Kontrak Berjangka di mana transaksi dilaksanakan yang secara langsung mempengaruhi pelaksanaan pekerjaan berdasarkan Perjanjian.<br>
                            </p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <div class="page-break"></div>
                        <li style="margin-bottom: 0px;">
                            <span>'.($num_pass4_aftr + 21).'. Perubahan atas Isian dalam Perjanjian Pemberian Amanat</span>
                            <p style="padding-left:5px;">Perubahan atas isian dalam Perjanjian ini hanya dapat dilakukan atas persetujuan Para Pihak, atau Pialang Berjangka telah memberitahukan secara tertulis perubahan yang diinginkan, dan Nasabah tetap memberikan perintah untuk transaksi dengan tanpa memberikan tanggapan secara tertulis atas usul perubahan tersebut. Tindakan Nasabah tersebut dianggap setuju atas usul perubahan tersebut.<br>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        '.callExtrTag($ACC_TYPE, 24).'
                        <li style="margin-top: 0px;">
                            <span>'.($num_pass4_aftr + 22).'. Penyelesaian Perselisihan</span>
                            <ol>
                                <li seq=" (1)">Semua perselisihan dan perbedaan pendapat yang timbul dalam pelaksanaan Perjanjian ini wajib diselesaikan terlebih dahulu secara musyawarah untuk mencapai mufakat antara Para Pihak.</li>
                                <li seq=" (2)">Apabila perselisihan dan perbedaan pendapat yang timbul tidak dapat diselesaikan secara musyawarah untuk mencapai mufakat, Para Pihak wajib memanfaatkan sarana penyelesaian perselisihan yang tersedia di Bursa Berjangka.</li>
                                <li seq=" (3)">
                                    Apabila perselisihan dan perbedaan pendapat yang timbul tidak dapat diselesaikan melalui cara sebagaimana dimaksud pada angka (1) dan angka (2), maka Para Pihak sepakat untuk menyelesaikan perselisihan melalui *):
                                    <ol>
                                        <li seq=" a.">
                                            <input type="radio" name="step07_kotapenyelesaian" '.$CHECK1.' value="BAKTI" required />
                                            Badan Arbitrase Perdagangan Berjangka Komoditi (BAKTI) 
                                            berdasarkan Peraturan dan Prosedur Badan Arbitrase 
                                            Perdagangan Berjangka Komoditi (BAKTI); atau
                                        </li>
                                        <li seq=" b.">
                                            <input type="radio" name="step07_kotapenyelesaian" '.$CHECK2.' value="Pengadilan Negeri Surabaya" required /> Pengadilan Negeri Surabaya
                                        </li>
                                    </ol>
                                </li>
                                <li seq=" (4)">Kantor atau kantor cabang Pialang Berjangka terdekat dengan domisili Nasabah tempat penyelesaian dalam hal terjadi perselisihan.1
                                    <table width="75%" style="margin-left:5px;" border="0">
                                        <tr>
                                            <td width="30%" style="vertical-align:top:">Daftar Kantor</td>
                                            <td style="vertical-align:top:">Kantor yang dipilih (salah satu)</td>
                                        </tr>
                                        <tr>
                                            <td style="vertical-align:top:">a.Kantor Pusat</td>
                                            <td style="vertical-align:top:"><input type="radio" name="step07_kantorpenyelesaian" '.$ckota1.'  value="Kantor Pusat" checked required >Gedung Graha HSBC lt.9 Jl. Basuki Rahmat 58-60
                                            Surabaya , Kelurahan Tegalsari, Kecamatan
                                            Tegalsari - Jawa Timur Kode Pos 60262</td>
                                        </tr>
                                        <!-- <tr>
                                            <td>b.Yogyakarta</td>
                                            <td><input type="radio" name="step07_kantorpenyelesaian" '.$ckota2.' value="Yogyakarta" required ></td>
                                        </tr>
                                        <tr>
                                            <td>b.Medan</td>
                                            <td><input type="radio" name="step07_kantorpenyelesaian" '.$ckota3.' value="Medan" required ></td>
                                        </tr> -->
                                    </table>
                                </li>
                            </ol>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <div class="page-break"></div>
                        <li>
                            <span>'.($num_pass4_aftr + 23).'. Bahasa</span>
                            <p style="padding-left:5px;">Perjanjian ini dibuat dalam Bahasa Indonesia.<br></p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                    </ol>
                    <!-- <p class="text-justify">Dokumen Pemberitahuan Adanya Risiko ini disampaikan kepada Anda sesuai
                    dengan Pasal 50 ayat (2) Undang-Undang Nomor 32 Tahun 1997 tentang
                    Perdagangan Berjangka Komoditi sebagaimana telah diubah dengan Undang-Undang
                    Nomor 10 Tahun 2011 tentang Perubahan Atas Undang-Undang Nomor 32 Tahun 1997
                    Tentang Perdagangan Berjangka Komoditi.</p> -->

                    <div style="margin-top:25px;">
                        <p>Demikian Perjanjian Pemberian Amanat ini dibuat oleh Para Pihak dalam
                        keadaan sadar, sehat jasmani rohani dan tanpa unsur paksaan dari pihak
                        manapun.</p>
                        <p style="text-align:center;">"Saya telah membaca, mengerti dan setuju terhadap semua<br>ketentuan yang tercantum dalam perjanjian ini."</p>
                        <p style="text-align:center;">Dengan mengisi kolom <span>"YA"</span> di bawah ini, saya menyatakan bahwa saya telah<br>menerima<br>
                        <span>"PERJANJIAN PEMBERIAN AMANAT TRANSAKSI KONTRAK '.$FOOTER.'"</span><br>
                        mengerti dan menyetujui isinya.</p>
                    </div>
                    <div style="text-align:center;margin-top:25px;margin-left:25%">
                        <table>
                            <tr>
                                <td>Pernyataan menerima/tidak </td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td><input type="checkbox" style="display: inline;" checked disabled><span>Ya</span></td>
                                <td><input type="checkbox" style="display: inline;" disabled><span>Tidak</span></td>
                            </tr>
                            <tr>
                                <td>Menerima pada tanggal</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td colspan="2"><span>'.date('Y-m-d H:i:s', strtotime($ACC_F_PERJ_DATE)).'</span></td>
                            </tr>
                            <!-- <tr>
                                <td>IP Address</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td><span>'.$ACC_F_PERJ_IP.'</span></td>
                            </tr> -->
                        </table>
                    </div>
                    <div>
                        <p>*) Pilih salah satu </p>
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
        $dompdf->stream("".$web_name_full." - 107.PBK.05.0".$ACC_TYPE,array("Attachment"=>0));
        exit(0);
    }
    
?>