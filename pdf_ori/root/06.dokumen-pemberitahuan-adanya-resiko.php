
<?php
    date_default_timezone_set("Asia/Jakarta");
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../../setting.php';
    require_once 'vendor/autoload.php';
    use Dompdf\Dompdf;
    $dompdf = new Dompdf();
    
    $id_acc = form_input($_GET["x"]);
    
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
        
        if($RESULT_QUERY['ACC_TYPE'] == 1){
            $title = 'berjangka';
            $start = 7;
            $pasal7 = '
                <li class="text-justify mt-2">
                    <u>
                        <strong>
                            Anda dapat menderita kerugian yang disebabkan kegagalan sistem informasi.
                        </strong> 
                    </u>
                    Sebagaimana yang terjadi pada setiap transaksi keuangan, 
                    Anda dapat menderita kerugian jika amanat untuk melaksanakan transaksi Kontrak Derivatif dalam Sistem Perdagangan Alternatif tidak dapat dilakukan 
                    karena kegagalan sistem informasi di Bursa Berjangka, Pedagang Berjangka Penyelenggara Sistem Perdagangan Alternatif, 
                    maupun sistem di Pialang Berjangka Peserta Sistem Perdagangan Alternatif yang mengelola posisi Anda. 
                    Kerugian Anda akan semakin besar jika Pialang Berjangka yang mengelola posisi Anda tidak memiliki sistem informasi cadangan atau prosedur yang layak.
                    <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                </li>
            ';
        } else if($RESULT_QUERY['ACC_TYPE'] == 2){
            $title = 'derivatif dalam sistem perdagangan alternatif';
            $pasal7 = '';
            $start = 6;
        }
    } else {
        $ACC_F_RESK_DATE = '';
        $ACC_TYPE = '';
        $ACC_F_RESK_IP = '';
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
                        <td width="47%" style="vertical-align: middle; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$setting_pdf_logo."")).'" width="100%"></td>
                        <td width="6%">&nbsp;</td>
                        <td width="47%" style="text-align:right; vertical-align: top; ">
                            <p>
                                <h3>'.$web_name_full.'</h3>
                                '.$setting_central_office_address.'
                            </p>
                        </td>
                    </tr>
                </table>
                <hr>
                <table style="width:100%">
                    <tr>
                        <td width="50%" style="vertical-align: top; "><strong><small>Formulir Nomor : 107.PBK.04.'.$ACC_TYPE.'</small></strong></td>
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
                    <h3>DOKUMEN PEMBERITAHUAN ADANYA RISIKO<br>YANG HARUS DISAMPAIKAN OLEH PIALANG BERJANGKA<br>UNTUK TRANSAKSI KONTRAK '.strtoupper($title).'</h3>
                </div>
                
                <p class="text-justify">Dokumen Pemberitahuan Adanya Risiko ini disampaikan kepada Anda sesuai
                dengan Pasal 50 ayat (2) Undang-Undang Nomor 32 Tahun 1997 tentang
                Perdagangan Berjangka Komoditi sebagaimana telah diubah dengan Undang-Undang
                Nomor 10 Tahun 2011 tentang Perubahan Atas Undang-Undang Nomor 32 Tahun 1997
                Tentang Perdagangan Berjangka Komoditi.</p>

                <p class="text-justify">Maksud dokumen ini adalah memberitahukan bahwa kemungkinan kerugian atau
                keuntungan dalam perdagangan kontrak '.ucwords($title).' bisa mencapai jumlah yang sangat
                besar. Oleh karena itu, Anda harus berhati-hati dalam memutuskan untuk melakukan
                transaksi, apakah kondisi keuangan Anda mencukupi.</p>
                <ol>
                    <li class="text-justify">
                        <u>
                            <strong>Perdagangan kontrak '.ucwords($title).' belum tentu layak bagi semua investor.</strong>
                        </u><br>
                        <p>Anda dapat menderita kerugian dalam jumlah besar dan dalam jangka waktu
                        singkat. Jumlah kerugian uang dimungkinkan dapat melebihi jumlah uang yang
                        pertama kali Anda setor (Margin awal) ke Pialang Berjangka Anda.</p>
                        <p>Anda mungkin menderita kerugian seluruh Margin dan Margin tambahan yang
                        ditempatkan pada Pialang Berjangka untuk mempertahankan posisi Kontrak
                        Berjangka Anda.</p>
                        <p>Hal ini disebabkan Perdagangan Berjangka sangat dipengaruhi oleh mekanisme
                        leverage, dimana dengan jumlah investasi dalam bentuk yang relatif kecil dapat
                        digunakan untuk membuka posisi dengan aset yang bernilai jauh lebih tinggi.
                        Apabila Anda tidak siap dengan risiko seperti ini, sebaiknya Anda tidak melakukan
                        perdagangan kontrak '.ucwords($title).'.</p>
                        <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                    </li>
                    <li class="text-justify">
                        <u>
                            <strong>
                                Perdagangan kontrak '.ucwords($title).' mempunyai risiko dan mempunyai
                                kemungkinan kerugian yang tidak terbatas yang jauh lebih besar dari jumlah
                                uang yang disetor (Margin) ke Pialang Berjangka.
                            </strong>
                        </u>
                        kontrak '.ucwords($title).' sama
                        dengan produk keuangan lainnya yang mempunyai risiko tinggi, Anda sebaiknya
                        tidak menaruh risiko terhadap dana yang Anda tidak siap untuk menderita rugi,
                        seperti tabungan pensiun, dana kesehatan atau dana untuk keadaan darurat,
                        dana yang disediakan untuk pendidikan atau kepemilikan rumah, dana yang
                        diperoleh dari pinjaman pendidikan atau gadai, atau dana yang digunakan untuk
                        memenuhi kebutuhan sehari-hari.
                        <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                    </li>
                </ol>
                <table style="width:100%">
                    <tr>
                        <td width="47%" style="vertical-align: middle; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$setting_pdf_logo."")).'" width="100%"></td>
                        <td width="6%">&nbsp;</td>
                        <td width="47%" style="text-align:right; vertical-align: top; ">
                            <p>
                                <h3>'.$web_name_full.'</h3>
                                '.$setting_central_office_address.'
                            </p>
                        </td>
                    </tr>
                </table>
                <hr>
                <ol start="3">
                    <li class="text-justify mt-2">
                        <u>
                            <strong>
                                Berhati-hatilah terhadap pernyataan bahwa Anda pasti mendapatkan
                                keuntungan besar dari perdagangan kontrak '.ucwords($title).'.
                            </strong>
                        </u>
                        Meskipun perdagangan
                        kontrak '.ucwords($title).' dapat memberikan keuntungan yang besar dan cepat, namun
                        hal tersebut tidak pasti, bahkan dapat menimbulkan kerugian yang besar dan
                        cepat juga. Seperti produk keuangan lainnya, tidak ada yang dinamakan "pasti
                        untung".
                        <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                    </li>
                    <li class="text-justify mt-2">
                        <u>
                            <strong>
                                Disebabkan adanya mekanisme leverage dan sifat dari transaksi 
                                Kontrak Derivatif dalam Sistem Perdagangan Alternatif, Anda dapat 
                                merasakan dampak bahwa Anda menderita kerugian dalam waktu 
                                cepat.
                            </strong>
                        </u>
                        Keuntungan maupun kerugian dalam transaksi akan langsung 
                        dikredit atau didebet ke rekening Anda, paling lambat secara harian. 
                        Apabila pergerakan di pasar terhadap Kontrak Derivatif dalam Sistem 
                        Perdagangan Alternatif menurunkan nilai posisi Anda dalam Kontrak 
                        Derivatif dalam Sistem Perdagangan Alternatif, <i>dengan kata lain 
                        berlawanan dengan posisi yang Anda ambil</i>, Anda diwajibkan untuk 
                        menambah dana untuk pemenuhan kewajiban Margin ke perusahaan 
                        Pialang Berjangka. Apabila rekening Anda berada dibawah minimum 
                        Margin yang telah ditetapkan Lembaga Kliring Berjangka atau Pialang 
                        Berjangka, maka posisi Anda dapat dilikuidasi pada saat rugi, dan 
                        Anda wajib menyelesaikan defisit (jika ada) dalam rekening Anda.
                        <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                    </li>
                    <li class="text-justify mt-2">
                        <u>
                            <strong>
                                Pada saat pasar dalam keadaan tertentu, Anda mungkin akan sulit atau tidak
                                mungkin melikuidasi posisi.
                            </strong> 
                        </u>
                        Pada umumnya Anda harus 
                        melakukan transaksi mengambil posisi yang berlawanan dengan 
                        maksud melikuidasi posisi (offset) jika ingin melikuidasi posisi dalam 
                        Kontrak Derivatif dalam Sistem Perdagangan Alternatif. Apabila Anda 
                        tidak dapat melikuidasi posisi Kontrak Derivatif dalam Sistem 
                        Perdagangan Alternatif, Anda tidak dapat merealisasikan keuntungan 
                        pada nilai posisi tersebut atau mencegah kerugian yang lebih tinggi. 
                        Kemungkinan tidak dapat melikuidasi dapat terjadi, antara lain: jika 
                        perdagangan berhenti dikarenakan aktivitas perdagangan yang tidak 
                        lazim pada Kontrak Derivatif atau subjek Kontrak Derivatif, atau 
                        terjadi kerusakan sistem pada Pialang Berjangka Peserta Sistem 
                        Perdagangan Alternatif atau Pedagang Berjangka Penyelenggara 
                        Sistem Perdagangan Alternatif. Bahkan apabila Anda dapat 
                        melikuidasi posisi tersebut, Anda mungkin terpaksa melakukannya 
                        pada harga yang menimbulkan kerugian besar.
                        <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                    </li>
                    <li class="text-justify mt-2">
                        <u>
                            <strong>
                                Pada saat pasar dalam keadaan tertentu, Anda mungkin akan sulit atau tidak
                                mungkin mengelola risiko atas posisi terbuka kontrak '.ucwords($title).' dengan cara
                                membuka posisi dengan nilai yang sama namun dengan posisi yang
                                berlawanan dalam kontrak bulan yang berbeda, dalam pasar yang berbeda atau
                                dalam “subjek kontrak '.ucwords($title).'” yang berbeda.
                            </strong> 
                        </u>
                        Kemungkinan untuk tidak
                        dapat mengambil posisi dalam rangka membatasi risiko yang timbul, contohnya:
                        jika perdagangan dihentikan pada pasar yang berbeda disebabkan aktivitas
                        perdagangan yang tidak lazim pada kontrak '.ucwords($title).' atau “subjek Kontrak
                        Berjangka”.
                        <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                    </li>
                </ol>
                <div style="margin-bottom: 100px;">&nbsp;</div>
                <table style="width:100%">
                    <tr>
                        <td width="47%" style="vertical-align: middle; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$setting_pdf_logo."")).'" width="100%"></td>
                        <td width="6%">&nbsp;</td>
                        <td width="47%" style="text-align:right; vertical-align: top; ">
                            <p>
                                <h3>'.$web_name_full.'</h3>
                                '.$setting_central_office_address.'
                            </p>
                        </td>
                    </tr>
                </table>
                <hr>
                <ol start="'.$start.'">
                    '.$pasal7.'
                    <li class="text-justify mt-2">
                        <u>
                            <strong>
                                Anda dapat diwajibkan untuk menyelesaikan kontrak '.ucwords($title).' dengan
                                penyerahan fisik dari “subjek kontrak '.ucwords($title).'”.
                            </strong> 
                        </u>
                        Jika Anda mempertahankan
                        posisi penyelesaian fisik dalam kontrak '.ucwords($title).' sampai hari terakhir
                        perdagangan berdasarkan tanggal jatuh tempo kontrak '.ucwords($title).', Anda akan
                        diwajibkan menyerahkan atau menerima penyerahan “subjek kontrak '.ucwords($title).'”
                        yang dapat mengakibatkan adanya penambahan biaya. Pengertian penyelesaian
                        dapat berbeda untuk suatu kontrak '.ucwords($title).' dengan kontrak '.ucwords($title).' lainnya
                        atau suatu Bursa Berjangka dengan Bursa Berjangka lainnya. Anda harus melihat
                        secara teliti mengenai penyelesaian dan kondisi penyerahan sebelum membeli atau
                        menjual kontrak '.ucwords($title).'.
                        <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                    </li>
                    <li class="text-justify mt-2">
                        <u>
                            <strong>
                                Semua kontrak '.ucwords($title).' mempunyai risiko, dan tidak ada strategi
                                berdagang yang dapat menjamin untuk menghilangkan risiko tersebut.
                            </strong> 
                        </u>
                        Strategi
                        dengan menggunakan kombinasi posisi seperti spread, dapat sama berisiko seperti
                        posisi long atau short. Melakukan Perdagangan Berjangka memerlukan
                        pengetahuan mengenai kontrak '.ucwords($title).' dan pasar berjangka.
                        <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                    </li>
                    <li class="text-justify mt-2">
                        <u>
                            <strong>
                                Strategi perdagangan harian dalam kontrak '.ucwords($title).' dan produk lainnya
                                memiliki risiko khusus.
                            </strong> 
                        </u>
                        Seperti pada produk keuangan lainnya, pihak yang ingin
                        membeli atau menjual kontrak '.ucwords($title).' yang sama dalam satu hari untuk
                        mendapat keuntungan dari perubahan harga pada hari tersebut (“day traders”)
                        akan memiliki beberapa risiko tertentu antara lain jumlah komisi yang besar,
                        risiko terkena efek pengungkit (“exposure to leverage”), dan persaingan dengan
                        pedagang profesional. Anda harus mengerti risiko tersebut dan memiliki
                        pengalaman yang memadai sebelum melakukan perdagangan harian (“day
                        trading”).
                        <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                    </li>
                    <li class="text-justify mt-2">
                        <u>
                            <strong>
                                Menetapkan amanat bersyarat, seperti kontrak '.ucwords($title).' dilikuidasi pada
                                keadaan tertentu untuk membatasi rugi (stop loss), mungkin tidak akan dapat
                                membatasi kerugian Anda sampai jumlah tertentu saja.
                            </strong> 
                        </u>
                        Amanat bersyarat
                        tersebut mungkin tidak dapat dilaksanakan karena terjadi kondisi pasar yang
                        tidak memungkinkan melikuidasi kontrak '.ucwords($title).'.
                        <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                    </li>
                    <li class="text-justify mt-2">
                        <u>
                            <strong>
                                Anda harus membaca dengan seksama dan memahami Perjanjian Pemberian
                                Amanat dengan Pialang Berjangka Anda sebelum melakukan transaksi
                                kontrak '.ucwords($title).'.
                            </strong>
                        </u>
                        <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                    </li>
                    <li class="text-justify mt-2">
                        <strong>Pernyataan singkat ini tidak dapat memuat secara rinci seluruh risiko atau
                        aspek penting lainnya tentang Perdagangan Berjangka. Oleh karena itu Anda
                        harus mempelajari kegiatan Perdagangan Berjangka secara cermat sebelum
                        memutuskan melakukan transaksi.</strong>
                        <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                    </li>
                </ol>
                <table style="width:100%">
                    <tr>
                        <td width="47%" style="vertical-align: middle; "><img src="data:image/png;base64,'.base64_encode(file_get_contents("https://".$bucketName.".s3.".$region.".amazonaws.com/".$folder."/".$setting_pdf_logo."")).'" width="100%"></td>
                        <td width="6%">&nbsp;</td>
                        <td width="47%" style="text-align:right; vertical-align: top; ">
                            <p>
                                <h3>'.$web_name_full.'</h3>
                                '.$setting_central_office_address.'
                            </p>
                        </td>
                    </tr>
                </table>
                <hr>
                <ol start="14">
                    <li seq="14" class="text-justify mt-2">
                        <strong>Dokumen Pemberitahuan Adanya Risiko (Risk Disclosure) ini dibuat dalam
                        Bahasa Indonesia.</strong>
                        <div style="vertical-align: top;"><input type="checkbox" checked="true" value="YA" /> Saya sudah membaca dan memahami</div>
                    </li>
                </ol>
                <div style="margin-top:25px;text-align:center;">
                    <p><strong>PERNYATAAN MENERIMA PEMBERITAHUAN ADANYA RISIKO</strong></p>
                    <p>Dengan mengisi kolom <strong>"YA"</strong> di bawah ini, saya menyatakan bahwa saya telah menerima<br><strong>"DOKUMEN PEMBERITAHUAN ADANYA RISIKO"</strong><br>
                    mengerti dan menyetujui isinya.</p>
                </div>
                <div style="text-align:center;margin-top:10px;margin-left:25%">
                    <table>
                        <tr>
                            <td>Pernyataan Menerima</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>YA</strong></td>
                        </tr>
                        <tr>
                            <td>Menyatakan pada tanggal</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>'.date('Y-m-d H:i:s', strtotime($ACC_F_RESK_DATE)).'</strong></td>
                        </tr>
                        <tr>
                            <td>IP Address</td>
                            <td style="vertical-align: top;"><div style="margin:0px 5px;">:</div></td>
                            <td><strong>'.$ACC_F_RESK_IP.'</strong></td>
                        </tr>
                    </table>
                </div>
            </body>
        </html>
    ';

    $dompdf->loadHtml($content);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("".$web_name_full." - 107.PBK.04.".$ACC_TYPE,array("Attachment"=>0));
    
?>