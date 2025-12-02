<?php
    $nmr = 0;
?>
<div class="table-responsive">
    <table class="table table-striped table-hover" width="100%">
        <tbody>
            <tr>
                <td><?= ++$nmr; ?> </td>
                <td>Formulir Nomor : 107.PBK.01</td>
                <td>
                    Profile Perusahaan<br>
                    <small> <?php echo $web_name_full ?> adalah Perusahaan Pialang yang bergerak di bidang perdagangan kontrak derivatif komoditi, Indeks Saham dan Foreign Exchange.</small>
                </td>
                <td style="white-space: nowrap"><a target="_blank" href="<?php echo '/0e03qkuh/pdf/root/02-profil-perusahaaan-pialang-berjangka.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
            </tr>
            <tr>
                <td><?= ++$nmr; ?> </td>
                <td>Formulir Nomor : 107.PBK.02.1</td>
                <td>
                    Pernyataan Telah Melakukan Simulasi perdagangan berjangka komoditi<br>
                    <small>Calon Nasabah diwajibkan untuk memiliki demo account <?php echo $web_name_full ?> sebagai sarana untuk melakukan simulasi transaksi di <?php echo $web_name_full ?>.</small>
                </td>
                <td style="white-space: nowrap"><a target="_blank" href="<?php echo '/0e03qkuh/pdf/root/03.pernyataan-telah-melakukan-simulasi.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
            </tr>
            <tr>
                <td><?= ++$nmr; ?> </td>
                <td>Formulir Nomor : 107.PBK.02.2</td>
                <td>
                    Pernyataan telah berpengalaman melaksanakan transaksi perdagangan berjangka komoditi<br>
                    <small>Dalam hal calon nasabah telah berpengalaman dalam melaksanakan transaksi dalam Perdagangan Berjangka Komoditi, Nasabah memberikan pernyataan dengan Surat Pernyataan Telah Berpengalaman Melaksanakan Transaksi Perdagangan Berjangka Komoditi.</small>
                </td>
                <td style="white-space: nowrap"><a target="_blank" href="<?php echo '/0e03qkuh/pdf/root/04.pernyataan-pengalaman-transaksi.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
            </tr>
            <tr>
                <td><?= ++$nmr; ?> </td>
                <td>-</td>
                <td>Disclosure Statement</td>
                <td style="white-space: nowrap"><a target="_blank" href="<?php echo '/0e03qkuh/pdf/root/disclosure-statement-01.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
            </tr>
            <tr>
                <td><?= ++$nmr; ?> </td>
                <td>Formulir Nomor : 107.PBK.03</td>
                <td>
                    Aplikasi Pembukaan Rekening Transaksi secara Elektronik On-line<br>
                    <small>Seluruh data isian dalam Aplikasi Pembukaan Rekening Transaksi Secara Elektronik On-line Dalam Sistem Perdagangan Alternatif wajib di isi sendiri oleh Nasabah, dan Nasabah bertanggung jawab atas kebenaran informasi yang diberikan dalam mengisi dokumen ini.</small>
                </td>
                <td style="white-space: nowrap"><a target="_blank" href="<?php echo '/0e03qkuh/pdf/root/05.aplikasi-pembukaan-rekening.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
            </tr>
            <tr>
                <td><?= ++$nmr; ?> </td>
                <td>-</td>
                <td>Disclosure Statement</td>
                <td style="white-space: nowrap"><a target="_blank" href="<?php echo '/0e03qkuh/pdf/root/disclosure-statement-02.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
            </tr>
            <tr>
                <td><?= ++$nmr; ?> </td>
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
                <td><?= ++$nmr; ?> </td>
                <td>-</td>
                <td>Disclosure Statement</td>
                <td style="white-space: nowrap"><a target="_blank" href="<?php echo '/0e03qkuh/pdf/root/disclosure-statement-03.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
            </tr>
            <tr>
                <td><?= ++$nmr; ?> </td>
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
                <td><?= ++$nmr; ?> </td>
                <td>Formulir Nomor : 107.PBK.06</td>
                <td>
                    Peraturan Perdagangan (Trading Rules)<br>
                    <small>Peraturan Perdagangan (Trading Rules) dalam siste, aplikasi penerimaan nasabah secara elektronik On-Line</small>
                </td>
                <td style="white-space: nowrap"><a target="_blank" href="<?php echo 'pdf/root/08.trading-rules.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
            </tr>
            <tr>
                <td><?= ++$nmr; ?> </td>
                <td>Formulir Nomor : 107.PBK.07</td>
                <td>
                    Pernyataan bertanggung jawab<br>
                    <small>Pernyataan bertanggung jawab atas kode akses transaksi nasabah(Personal Access Password)</small>
                </td>
                <td style="white-space: nowrap"><a target="_blank" href="<?php echo 'pdf/root/09.pernyataan-bertanggung-jawab-atas-kode-transaksi.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
            </tr>
            <tr>
                <td><?= ++$nmr; ?> </td>
                <td>-</td>
                <td>
                    Formulir Pernyataan bahwa dana yang digunakan sebagai margin 
                    merupakan dana milik nasabah sendiri<br>
                </td>
                <td style="white-space: nowrap"><a target="_blank" href="<?php echo 'pdf/root/14.formulir-pernyataan-nasabah.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
            </tr>
            <!-- <tr>
                <td><?= $nmr; ?> </td>
                <td>-</td>
                <td>Disclosure Statement All</td>
                <td style="white-space: nowrap"><a target="_blank" href="<?php echo 'pdf/root/disclosure-statement-all.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
            </tr> -->
            <tr>
                <td><?= ++$nmr; ?> </td>
                <td>-</td>
                <td>
                    Formulir Verifikasi Kelengkapan <br>Proses Penerimaan Nasabah Secara Elektronik Online<br>
                </td>
                <td style="white-space: nowrap"><a target="_blank" href="<?php echo 'pdf/root/verifikasikelengkapan.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
            </tr>
            <tr>
                <td><?= ++$nmr; ?> </td>
                <td>-</td>
                <td>Bukti Penerimaan Nasabah</td>
                <td style="white-space: nowrap"><a target="_blank" href="<?php echo 'pdf/root/13.bukti-konfirmasi-penerimaan-nasabah.php?x='.$id; ?>"><i class="fa fa-eye"></i>&nbsp;View</a></td>
            </tr>
        </tbody>
    </table>
</div>