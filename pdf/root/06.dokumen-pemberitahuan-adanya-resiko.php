
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
            tb_member.MBR_NAME,
            tb_member.MBR_ZIP,
            tb_racc.ACC_F_RESK_DATE,
            tb_racc.ACC_F_RESK_IP,
            tb_racc.ACC_TYPE
        FROM tb_racc
        JOIN tb_member
        ON(tb_member.MBR_ID = tb_racc.ACC_MBR)
        WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$id_acc.'"
        LIMIT 1
    ');
    if(mysqli_num_rows($SQL_QUERY) > 0){
        $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);
        $ACC_F_RESK_DATE = $RESULT_QUERY['ACC_F_RESK_DATE'];
        $ACC_TYPE = $RESULT_QUERY['ACC_TYPE'];
        $ACC_F_RESK_IP = $RESULT_QUERY['ACC_F_RESK_IP'];
        
        if($RESULT_QUERY['ACC_TYPE'] == 2){
            $title = 'berjangka';
            $start  = 6;
            $start2 = 10;
            $pasal7 = '
                <li class="text-justify mt-2">
                    <p style="text-align:justify;"><u>Anda dapat diwajibkan untuk menyelesaikan Kontrak '.ucwords($title).'engan
                    penyerahan fisik dari “subjek Kontrak '.ucwords($title).'”.</u>
                    Jika Anda mempertahankan  posisi penyelesaian fisik dalam Kontrak '.ucwords($title).' sampai hari terakhir
                    perdagangan berdasarkan tanggal jatuh tempo Kontrak '.ucwords($title).', Anda akan
                    diwajibkan menyerahkan atau menerima penyerahan “subjek Kontrak '.ucwords($title).'”
                    yang dapat mengakibatkan adanya penambahan biaya. Pengertian penyelesaian
                    dapat berbeda untuk suatu Kontrak '.ucwords($title).' dengan Kontrak '.ucwords($title).' lainnya
                    atau suatu Bursa Berjangka dengan Bursa Berjangka lainnya. Anda harus melihat
                    secara teliti mengenai penyelesaian dan kondisi penyerahan sebelum membeli atau
                    menjual Kontrak '.ucwords($title).'.</p>
                    <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                </li>
            ';
        } else if($RESULT_QUERY['ACC_TYPE'] == 1){
            $title = 'derivatif dalam sistem perdagangan alternatif';
            $pasal7 = '';
            $start  = 6;
            $start2 = 10;
        }
    } else {
        $ACC_F_RESK_DATE = '';
        $ACC_TYPE = '';
        $ACC_F_RESK_IP = '';
    };

    if(str_word_count($title) > 1){
        $ARR_T = explode(" " ,$title);
        $shrt_title = ucfirst($ARR_T[0]);
    }else{
        $shrt_title = ucfirst($title);
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
                        <p>DOKUMEN PEMBERITAHUAN ADANYA RESIKO YANG HARUS<br>DISAMPAIKAN OLEH PIALANG BERJANGKA UNTUK TRANSAKSI KONTRAK<br>'.strtoupper($title).'</p>
                    </div>
                    
                    <p style="text-align:justify;">Dokumen Pemberitahuan Adanya Resiko ini disampaikan kepada Anda sesuai
                    dengan Pasal 50 ayat (2) Undang-Undang Nomor 32 Tahun 1997 tentang
                    Perdagangan Berjangka Komoditi sebagaimana telah diubah dengan Undang-Undang
                    Nomor 10 Tahun 2011 tentang Perubahan Atas Undang-Undang Nomor 32 Tahun 1997
                    Tentang Perdagangan Berjangka Komoditi.</p>

                    <p style="text-align:justify;">Maksud dokumen ini adalah memberitahukan bahwa kemungkinan kerugian atau
                    keuntungan dalam perdagangan kontrak '.ucwords($title).' bisa mencapai jumlah yang sangat
                    besar. Oleh karena itu, Anda harus berhati-hati dalam memutuskan untuk melakukan
                    transaksi, apakah kondisi keuangan Anda mencukupi.</p>
                    <ol>
                        <li style="text-align:justify;">
                            <p><u>
                                <span>
                                    Perdagangan kontrak '.ucwords($title).' belum tentu layak bagi semua investor. Anda dapat menderita kerugian dalam jumlah besar dan dalam jangka waktu
                                    singkat.
                                </span>
                            </u>
                            Jumlah kerugian uang dimungkinkan dapat melebihi jumlah uang yang
                            pertama kali Anda setor (Margin awal) ke Pialang Berjangka Anda.
                            Anda mungkin menderita kerugian seluruh Margin dan Margin tambahan yang
                            ditempatkan pada Pialang Berjangka untuk mempertahankan posisi Kontrak
                            Berjangka Anda. Hal ini disebabkan Perdagangan Berjangka sangat dipengaruhi oleh mekanisme
                            leverage, dimana dengan jumlah investasi dalam bentuk yang relatif kecil dapat
                            digunakan untuk membuka posisi dengan aset yang bernilai jauh lebih tinggi.
                            Apabila Anda tidak siap dengan resiko seperti ini, sebaiknya Anda tidak melakukan
                            perdagangan kontrak '.ucwords($title).'.</p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                    </ol>
                    <ol start="2">
                        <li class="text-justify">
                            <p style="text-align:justify;"><u>
                                <span>
                                    Perdagangan kontrak '.ucwords($title).' mempunyai resiko dan mempunyai
                                    kemungkinan kerugian yang tidak terbatas yang jauh lebih besar dari jumlah
                                    uang yang disetor (Margin) ke Pialang Berjangka.
                                </span>
                            </u>
                            kontrak '.ucwords($title).' sama
                            dengan produk keuangan lainnya yang mempunyai resiko tinggi, Anda sebaiknya
                            tidak menaruh resiko terhadap dana yang Anda tidak siap untuk menderita rugi,
                            seperti tabungan pensiun, dana kesehatan atau dana untuk keadaan darurat,
                            dana yang disediakan untuk pendidikan atau kepemilikan rumah, dana yang
                            diperoleh dari pinjaman pendidikan atau gadai, atau dana yang digunakan untuk
                            memenuhi kebutuhan sehari-hari.</p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li class="text-justify mt-2">
                            <p style="text-align:justify;"><u>Berhati-hatilah terhadap pernyataan bahwa Anda pasti mendapatkan
                            keuntungan besar dari perdagangan kontrak '.ucwords($title).'.</u>
                            Meskipun perdagangan kontrak '.ucwords($title).' dapat memberikan keuntungan yang besar dan cepat, namun
                            hal tersebut tidak pasti, bahkan dapat menimbulkan kerugian yang besar dan
                            cepat juga. Seperti produk keuangan lainnya, tidak ada yang dinamakan "pasti
                            untung".</p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li class="text-justify mt-2">
                            <p style="text-align:justify;"><u>Disebabkan adanya mekanisme leverage dan sifat dari transaksi 
                            Kontrak '.ucwords($title).', Anda dapat 
                            merasakan dampak bahwa Anda menderita kerugian dalam waktu 
                            cepat.</u> Keuntungan maupun kerugian dalam transaksi akan langsung 
                            dikredit atau didebet ke rekening Anda, paling lambat secara harian. 
                            Apabila pergerakan di pasar terhadap Kontrak '.ucwords($title).'
                            menurunkan nilai posisi Anda dalam Kontrak 
                            '.ucwords($title).', Anda diwajibkan untuk 
                            menambah dana untuk pemenuhan kewajiban Margin ke perusahaan 
                            Pialang Berjangka. Apabila rekening Anda berada dibawah minimum 
                            Margin yang telah ditetapkan Lembaga Kliring Berjangka atau Pialang 
                            Berjangka, maka posisi Anda dapat dilikuidasi pada saat rugi, dan 
                            Anda wajib menyelesaikan defisit (jika ada) dalam rekening Anda.</p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li class="text-justify mt-2">
                            <p style="text-align:justify;"><u>Pada saat pasar dalam keadaan tertentu, Anda mungkin akan sulit atau tidak
                            mungkin melikuidasi posisi.</u> Pada umumnya Anda harus 
                            melakukan transaksi mengambil posisi yang berlawanan dengan 
                            maksud melikuidasi posisi (offset) jika ingin melikuidasi posisi dalam 
                            Kontrak '.ucwords($title).'. Apabila Anda 
                            tidak dapat melikuidasi posisi Kontrak '.ucwords($title).', 
                            Anda tidak dapat merealisasikan keuntungan 
                            pada nilai posisi tersebut atau mencegah kerugian yang lebih tinggi. 
                            Kemungkinan tidak dapat melikuidasi dapat terjadi, antara lain: jika 
                            perdagangan berhenti dikarenakan aktivitas perdagangan yang tidak 
                            lazim pada Kontrak '.$shrt_title.' atau subjek Kontrak '.$shrt_title.', atau 
                            terjadi kerusakan sistem pada Pialang Berjangka Peserta Sistem 
                            Perdagangan Alternatif atau Pedagang Berjangka Penyelenggara 
                            Sistem Perdagangan Alternatif. Bahkan apabila Anda dapat 
                            melikuidasi posisi tersebut, Anda mungkin terpaksa melakukannya 
                            pada harga yang menimbulkan kerugian besar.</p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                    </ol>
                    <ol start="'.$start.'">
                        <li class="text-justify mt-2">
                            <p style="text-align:justify;"><u>Pada saat pasar dalam keadaan tertentu, Anda mungkin akan sulit atau tidak
                            mungkin mengelola resiko atas posisi terbuka kontrak '.ucwords($title).' dengan cara
                            membuka posisi dengan nilai yang sama namun dengan posisi yang
                            berlawanan dalam kontrak bulan yang berbeda, dalam pasar yang berbeda atau
                            dalam “subjek kontrak '.ucwords($title).'” yang berbeda.</u> Kemungkinan untuk tidak
                            dapat mengambil posisi dalam rangka membatasi resiko yang timbul, contohnya:
                            jika perdagangan dihentikan pada pasar yang berbeda disebabkan aktivitas
                            perdagangan yang tidak lazim pada kontrak '.ucwords($title).' atau “subjek Kontrak
                            Berjangka”.</p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        '.$pasal7.'
                        <li class="text-justify mt-2">
                            <p style="text-align:justify;"><u>
                            Anda dapat menderita kerugian yang disebabkan kegagalan sistem informasi.
                            Sebagaimana yang terjadi pada setiap transaksi keuangan, 
                            Anda dapat menderita kerugian jika amanat untuk melaksanakan transaksi Kontrak '.ucwords($title).' tidak dapat dilakukan 
                            karena kegagalan sistem informasi di Bursa Berjangka, penyelenggara maupun sistem
informasi di Pialang Berjangka yang mengelola posisi Anda. </u>
                            Kerugian Anda akan semakin besar jika Pialang Berjangka yang mengelola posisi Anda tidak memiliki sistem informasi cadangan atau prosedur yang layak.</p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li class="text-justify mt-2">
                            <p style="text-align:justify;"><u>Semua kontrak '.ucwords($title).' mempunyai resiko, dan tidak ada strategi
                            berdagang yang dapat menjamin untuk menghilangkan resiko tersebut.</u> Strategi
                            dengan menggunakan kombinasi posisi seperti spread, dapat sama beresiko seperti
                            posisi <i>long</i> atau <i>short</i>. Melakukan Perdagangan Berjangka memerlukan
                            pengetahuan mengenai kontrak '.ucwords($title).' dan pasar berjangka.</p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                    </ol>
                    <ol start="'.$start2.'">
                        <li class="text-justify mt-2">
                            <p style="text-align:justify;"><u>Strategi perdagangan harian dalam kontrak '.ucwords($title).' dan produk lainnya
                            memiliki resiko khusus.</u>Seperti pada produk keuangan lainnya, pihak yang ingin
                            membeli atau menjual kontrak '.ucwords($title).' yang sama dalam satu hari untuk
                            mendapat keuntungan dari perubahan harga pada hari tersebut ("<i>day traders</i>")
                            akan memiliki beberapa resiko tertentu antara lain jumlah komisi yang besar,
                            resiko terkena efek pengungkit ("<i>exposure to leverage</i>"), dan persaingan dengan
                            pedagang profesional. Anda harus mengerti resiko tersebut dan memiliki
                            pengalaman yang memadai sebelum melakukan perdagangan harian ("<i>daytrading</i>").</p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li class="text-justify mt-2">
                            <p style="text-align:justify;"><u>Menetapkan amanat bersyarat, seperti Kontrak '.ucwords($title).' dilikuidasi pada
                            keadaan tertentu untuk membatasi rugi (<i>stop loss</i>), mungkin tidak akan dapat
                            membatasi kerugian Anda sampai jumlah tertentu saja.</u>
                            Amanat bersyarat tersebut mungkin tidak dapat dilaksanakan karena terjadi kondisi pasar yang
                            tidak memungkinkan melikuidasi Kontrak '.ucwords($title).'.</p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li class="text-justify mt-2">
                            <p style="text-align:justify;"><u>Anda harus membaca dengan seksama dan memahami Perjanjian Pemberian Amanat</u>
                            dengan Pialang Berjangka Anda sebelum melakukan transaksi    
                            Kontrak '.ucwords($title).'.</p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <div class="page-break"></div>
                        <li class="text-justify mt-2">
                            <p style="text-align:justify;"><u>Pernyataan singkat ini tidak dapat memuat secara rinci seluruh resiko atau
                            aspek penting lainnya tentang Perdagangan Berjangka.</u> Oleh karena itu Anda
                            harus mempelajari kegiatan Perdagangan Berjangka secara cermat sebelum
                            memutuskan melakukan transaksi.</p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                        <li seq="14" class="text-justify mt-2">
                            <p style="text-align:justify;">Dokumen Pemberitahuan Adanya Resiko (<i>Risk Disclosure</i>) ini dibuat dalam
                            Bahasa Indonesia.</p>
                            <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                        </li>
                    </ol>
                    <div style="margin-top:25px;text-align:center;">
                        <p><span>PERNYATAAN MENERIMA PEMBERITAHUAN ADANYA RESIKO</span></p>
                        <p>Dengan mengisi kolom <span>"YA"</span> di bawah ini, saya menyatakan bahwa saya telah menerima<br><br><span>"DOKUMEN PEMBERITAHUAN ADANYA RESIKO"</span><br><br>
                        mengerti dan menyetujui isinya.</p>
                    </div>
                    <div style="text-align:center;margin-top:10px;margin-left:25%">
                        <table>
                            <tr>
                                <td>Pernyataan menerima/tidak</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td><input type="checkbox" style="display: inline;" checked disabled><span>Ya</span></td>
                                <td><input type="checkbox" style="display: inline;" disabled><span>Tidak</span></td>
                            </tr>
                            <tr>
                                <td>Menerima pada tanggal</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td colspan="2"><span>'.date('Y-m-d H:i:s', strtotime($ACC_F_RESK_DATE)).'</span></td>
                            </tr>
                            <!-- <tr>
                                <td>IP Address</td>
                                <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                                <td><span>'.$ACC_F_RESK_IP.'</span></td>
                            </tr> -->
                        </table>
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
        $dompdf->stream("".$web_name_full." - 107.PBK.04.".$ACC_TYPE,array("Attachment"=>0));
        exit(0);
    }
    
?>