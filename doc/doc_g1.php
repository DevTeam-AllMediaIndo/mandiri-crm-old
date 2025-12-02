<?php
    $x = addslashes(mysqli_real_escape_string($db, stripslashes(strip_tags($_GET['x']))));
    $SQL_QUERY2 = mysqli_query($db, '
        SELECT
            tb_member.MBR_NAME,
            tb_member.MBR_EMAIL,
            tb_member.MBR_PHONE,
            tb_member.MBR_NO_IDT,
            tb_member.MBR_NAMA_MWPP,
            tb_racc.ACC_F_IBCODE
        FROM tb_schedule
        JOIN tb_member
        JOIN tb_racc
        ON (tb_schedule.SCHD_ID = tb_member.MBR_ID
        AND tb_member.MBR_ID = tb_racc.ACC_MBR)
        WHERE MD5(MD5(tb_schedule.ID_SCHD)) = "'.$x.'"
        LIMIT 1
    ');
    if(mysqli_num_rows($SQL_QUERY2) > 0){
        $RESULT_QUERY2  = mysqli_fetch_assoc($SQL_QUERY2);
        $MBR_NAME       = $RESULT_QUERY2['MBR_NAME'];
        $MBR_EMAIL       = $RESULT_QUERY2['MBR_EMAIL'];
        $MBR_PHONE      = $RESULT_QUERY2['MBR_PHONE'];
        $MBR_NO_IDT     = $RESULT_QUERY2['MBR_NO_IDT'];
        $MBR_NAMA_MWPP  = $RESULT_QUERY2['MBR_NAMA_MWPP'];
        $ACC_F_IBCODE  = $RESULT_QUERY2['ACC_F_IBCODE'];
    } else {
        $MBR_NAME = '-';
        $MBR_EMAIL = '-';
        $MBR_PHONE = '-';
        $MBR_NO_IDT = '-';
        $MBR_NAMA_MWPP = '-';
        $ACC_F_IBCODE = '-';
    };
    if(isset($_POST['accept'])){
        $EXEC_SQL = mysqli_query($db, '
            UPDATE tb_schedule SET
            tb_schedule.SCHD_DETAIL = -1
            WHERE MD5(MD5(tb_schedule.ID_SCHD)) = "'.$x.'"
            AND tb_schedule.SCHD_DETAIL = 0
        ') or die (mysqli_error($db));
    
        $product_ber = form_input($_POST['product_ber']); 
        $pfrl_perusahaan = form_input($_POST['pfrl_perusahaan']); 
        $prj_amanat = form_input($_POST['prj_amanat']); 
        $apk_pbk_akun = form_input($_POST['apk_pbk_akun']); 
        $dly_stmnt = form_input($_POST['dly_stmnt']); 
        $srna_prslhn = form_input($_POST['srna_prslhn']); 
        $prj_dlr_ktn = form_input($_POST['prj_dlr_ktn']); 
        $sharing_prft = form_input($_POST['sharing_prft']); 
        $ver_email = form_input($_POST['ver_email']); 

        $SQL_QUERY = mysqli_query($db,'
            SELECT
                tb_schedule.ID_SCHD,
                tb_schedule.SCHD_JAM,
                tb_schedule.SCHD_TANGGAL,
                tb_member.MBR_ID
            FROM tb_schedule
            JOIN tb_member
            ON (tb_schedule.SCHD_ID = tb_member.MBR_ID)
            WHERE MD5(MD5(tb_schedule.ID_SCHD)) = "'.$x.'"
            LIMIT 1
        ');
        if(mysqli_num_rows($SQL_QUERY) > 0){
            $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);

            mysqli_query($db, '
                INSERT INTO tb_g1 SET
                tb_g1.G1_MBR = '.$RESULT_QUERY['MBR_ID'].',
                tb_g1.G1_ADM = '.$user1['ADM_ID'].',
                tb_g1.G1_SCHD = '.$RESULT_QUERY['ID_SCHD'].',
                tb_g1.G1_PRODUKBER = "'.$product_ber.'",
                tb_g1.G1_PROFILPER = "'.$pfrl_perusahaan.'",
                tb_g1.G1_APLIASIPEM = "'.$apk_pbk_akun.'",
                tb_g1.G1_DAYLISTA = "'.$dly_stmnt.'",
                tb_g1.G1_SARANA = "'.$srna_prslhn.'",
                tb_g1.G1_PERJN = "'.$prj_amanat.'",
                tb_g1.G1_PRJN_DLR_KTN = "'.$prj_dlr_ktn.'",
                tb_g1.G1_JANJIUNTUNG = "'.$sharing_prft.'",
                tb_g1.G1_VER = "'.$ver_email.'",
                tb_g1.G1_DEVICE = "Web",
                tb_g1.G1_IP = "'.$ip_visitors.'",
                tb_g1.G1_STS = -1,
                tb_g1.G1_DATETIME = "'.date("Y-m-d H:i:s").'"
            ') or die(mysqli_error($db));
            
            mysqli_query($db, '
                UPDATE tb_schedule SET
                tb_schedule.SCHD_STS = -1
                WHERE MD5(MD5(tb_schedule.ID_SCHD)) = "'.$x.'"
                AND tb_schedule.SCHD_STS = 0
            ') or die (mysqli_error($db));

            // Message Telegram
            $mesg = 'Notif : Jadwal Temu Diterima'.
            PHP_EOL.'Date : '.date("Y-m-d").
            PHP_EOL.'Time : '.date("H:i:s").
            PHP_EOL.'======== Informasi Jadwal Temu =========='.
            PHP_EOL.'Nama : '.$MBR_NAME.
            PHP_EOL.'Email : '.$MBR_EMAIL.
            PHP_EOL.'Tanggal temu : '.$RESULT_QUERY["SCHD_TANGGAL"].
            PHP_EOL.'Jam temu : '.$RESULT_QUERY["SCHD_JAM"].
            PHP_EOL.'Status : Diterima'.
            PHP_EOL.'By : '.$user1['ADM_NAME'].'';

            $request_params = [
                'chat_id' => $chat_id,
                'text' => $mesg
            ];
            http_request('https://api.telegram.org/bot'.$token1.'/sendMessage?'.http_build_query($request_params));

            $request_params_all = [
                'chat_id' => $chat_id_all,
                'text' => $mesg
            ];
            http_request('https://api.telegram.org/bot'.$token_all.'/sendMessage?'.http_build_query($request_params_all));
            die ("<script>alert('Success');location.href = 'home.php?page=jadwal-temu'</script>");
        };
    };
    
    if(isset($_POST['decline'])){
        if(isset($_POST['reason_text'])){
            $product_ber = form_input($_POST['product_ber']); 
            $pfrl_perusahaan = form_input($_POST['pfrl_perusahaan']); 
            $prj_amanat = form_input($_POST['prj_amanat']); 
            $apk_pbk_akun = form_input($_POST['apk_pbk_akun']); 
            $dly_stmnt = form_input($_POST['dly_stmnt']); 
            $srna_prslhn = form_input($_POST['srna_prslhn']); 
            $prj_dlr_ktn = form_input($_POST['prj_dlr_ktn']); 
            $sharing_prft = form_input($_POST['sharing_prft']); 
            $ver_email = form_input($_POST['ver_email']); 
            $reason_text = mysqli_real_escape_string($db, strip_tags(addslashes($_POST["reason_text"])));

            $SQL_QUERY = mysqli_query($db,'
                SELECT
                    tb_schedule.ID_SCHD,
                    tb_schedule.SCHD_JAM,
                    tb_schedule.SCHD_TANGGAL,
                    tb_member.MBR_ID
                FROM tb_schedule
                JOIN tb_member
                ON (tb_schedule.SCHD_ID = tb_member.MBR_ID)
                WHERE MD5(MD5(tb_schedule.ID_SCHD)) = "'.$x.'"
                LIMIT 1
            ');
            if(mysqli_num_rows($SQL_QUERY) > 0){
                $RESULT_QUERY = mysqli_fetch_assoc($SQL_QUERY);

                mysqli_query($db, '
                    INSERT INTO tb_g1 SET
                    tb_g1.G1_MBR = '.$RESULT_QUERY['MBR_ID'].',
                    tb_g1.G1_ADM = '.$user1['ADM_ID'].',
                    tb_g1.G1_SCHD = '.$RESULT_QUERY['ID_SCHD'].',
                    tb_g1.G1_PRODUKBER = "'.$product_ber.'",
                    tb_g1.G1_PROFILPER = "'.$pfrl_perusahaan.'",
                    tb_g1.G1_APLIASIPEM = "'.$apk_pbk_akun.'",
                    tb_g1.G1_DAYLISTA = "'.$dly_stmnt.'",
                    tb_g1.G1_SARANA = "'.$srna_prslhn.'",
                    tb_g1.G1_PERJN = "'.$prj_amanat.'",
                    tb_g1.G1_PRJN_DLR_KTN = "'.$prj_dlr_ktn.'",
                    tb_g1.G1_JANJIUNTUNG = "'.$sharing_prft.'",
                    tb_g1.G1_VER = "'.$ver_email.'",
                    tb_g1.G1_DEVICE = "Web",
                    tb_g1.G1_IP = "'.$ip_visitors.'",
                    tb_g1.G1_STS = 1,
                    tb_g1.G1_DATETIME = "'.date("Y-m-d H:i:s").'"
                ') or die(mysqli_error($db));
                
                mysqli_query($db, '
                    UPDATE tb_schedule SET
                    tb_schedule.SCHD_STS = 1,
                    tb_schedule.SCHD_REASON = "'.$reason_text.'"
                    WHERE MD5(MD5(tb_schedule.ID_SCHD)) = "'.$x.'"
                    AND tb_schedule.SCHD_STS = 0
                ') or die (mysqli_error($db));
                // Message Telegram
                $mesg = 'Notif : Jadwal Temu Ditolak'.
                PHP_EOL.'Date : '.date("Y-m-d").
                PHP_EOL.'Time : '.date("H:i:s").
                PHP_EOL.'======== Informasi Jadwal Temu =========='.
                PHP_EOL.'Nama : '.$MBR_NAME.
                PHP_EOL.'Email : '.$MBR_EMAIL.
                PHP_EOL.'Tanggal temu : '.$RESULT_QUERY["SCHD_TANGGAL"].
                PHP_EOL.'Jam temu : '.$RESULT_QUERY["SCHD_JAM"].
                PHP_EOL.'Status : Ditolak'.
                PHP_EOL.'Alasan Ditolak : '.$reason_text.
                PHP_EOL.'By : '.$user1['ADM_NAME'].'';
    
                $request_params = [
                    'chat_id' => $chat_id,
                    'text' => $mesg
                ];
                http_request('https://api.telegram.org/bot'.$token1.'/sendMessage?'.http_build_query($request_params));
                
                $request_params_all = [
                    'chat_id' => $chat_id_all,
                    'text' => $mesg
                ];
                http_request('https://api.telegram.org/bot'.$token_all.'/sendMessage?'.http_build_query($request_params_all));
                die ("<script>alert('Success Decline');location.href = 'home.php?page=jadwal-temu'</script>");
            };
        }else{die("<script>alert('no reason');</script>");};
    };
    

    $true = "collapse";
    $false = "visible";

    ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Jadwal Temu</li>
        <li class="breadcrumb-item active" aria-current="page">Apply</li>
    </ol>
</nav>
<form method="post">
    <input type="hidden" value="<?php echo $x ?>" name="x">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header font-weight-bold">Data Pribadi</div>
                <div class="card-body">
                    <table style="width:100%">
                        <tr>
                            <td width="45%" style="vertical-align: top;">Nama</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $user1['ADM_NAME']; ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Nomor Telephone</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php if($user1['ADM_PHONE'] == ""){echo "-";}else{echo $user1['ADM_PHONE'];};  ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Jabatan</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo "WAKIL PIALANG PEMASARAN" ?></td>
                        </tr>
                    </table>
                </div>         
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header font-weight-bold">Profil Nasabah</div>
                <div class="card-body">
                    <table style="width:100%">
                        <tr>
                            <td width="45%" style="vertical-align: top;">Nama</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $MBR_NAME ?></td>
                        </tr>
                        <!-- <tr>
                            <td width="45%" style="vertical-align: top;">Jabatan</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo "WAKIL PIALANG PEMASARAN" ?></td>
                        </tr> -->
                        <tr>
                            <td width="45%" style="vertical-align: top;">Nomor Telephone</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $MBR_PHONE ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Nomor NIK</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $MBR_NO_IDT ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Email</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php echo $MBR_EMAIL ?></td>
                        </tr>
                        <tr>
                            <td width="45%" style="vertical-align: top;">Kode Referal</td>
                            <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                            <td style="vertical-align: top;"><?php if($ACC_F_IBCODE == ""){echo "-";}else{echo $ACC_F_IBCODE;};  ?></td>
                        </tr>
                    </table>
                </div>         
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header font-weight-bold">Data user</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table style="width:100%">
                                    <tr>
                                        <td style="vertical-align: top;"><b>A</b>.</td>
                                        <td width="45%" style="vertical-align: top;">PRODUK BERJANGKA</td>
                                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="product_ber" value="Disampaikan" required  id="product_ber1" onchange="GetSelectedTextValue()">&nbsp;&nbsp;<strong>Disampaikan</strong>
                                        </td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="product_ber" value="Tidak Disampaikan" required id="product_ber2" onchange="GetSelectedTextValue()">&nbsp;&nbsp;<strong>Tidak Disampaikan</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;"><b>B</b>.</td>
                                        <td width="45%" style="vertical-align: top;">PROFIL PERUSAHAAN</td>
                                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="pfrl_perusahaan" value="Disampaikan" required id="pfrl_perusahaan1"  onchange="GetSelectedTextValue()">&nbsp;&nbsp;<strong>Disampaikan</strong>
                                        </td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="pfrl_perusahaan" value="Tidak Disampaikan" required id="pfrl_perusahaan2" onchange="GetSelectedTextValue()">&nbsp;&nbsp;<strong>Tidak Disampaikan</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;"><b>C</b>.</td>
                                        <td width="45%" style="vertical-align: top;">PERJANJIAN AMANAT</td>
                                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="prj_amanat" value="Disampaikan" required id="prj_amanat1"  onchange="GetSelectedTextValue()">&nbsp;&nbsp;<strong>Disampaikan</strong>
                                        </td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="prj_amanat" value="Tidak Disampaikan" required id="prj_amanat2" onchange="GetSelectedTextValue()">&nbsp;&nbsp;<strong>Tidak Disampaikan</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;"><b>D</b>.</td>
                                        <td width="45%" style="vertical-align: top;">APLIKASI PEMBUKAAN AKUN</td>
                                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="apk_pbk_akun" value="Disampaikan" required id="apk_pbk_akun1"  onchange="GetSelectedTextValue()">&nbsp;&nbsp;<strong>Disampaikan</strong>
                                        </td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="apk_pbk_akun" value="Tidak Disampaikan" required id="apk_pbk_akun2" onchange="GetSelectedTextValue()">&nbsp;&nbsp;<strong>Tidak Disampaikan</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;"><b>E</b>.</td>
                                        <td width="45%" style="vertical-align: top;">DAILY STATEMENT</td>
                                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="dly_stmnt" value="Disampaikan" required id="dly_stmnt1"  onchange="GetSelectedTextValue()">&nbsp;&nbsp;<strong>Disampaikan</strong>
                                        </td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="dly_stmnt" value="Tidak Disampaikan" required id="dly_stmnt2" onchange="GetSelectedTextValue()">&nbsp;&nbsp;<strong>Tidak Disampaikan</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table style="width:100%">
                                    <tr>
                                        <td style="vertical-align: top;"><b>F</b>.</td>
                                        <td width="45%" style="vertical-align: top;">SARANA PERSELISIHAN</td>
                                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="srna_prslhn" value="Disampaikan" required id="srna_prslhn1"  onchange="GetSelectedTextValue()">&nbsp;&nbsp;<strong>Disampaikan</strong>
                                        </td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="srna_prslhn" value="Tidak Disampaikan" required id="srna_prslhn2" onchange="GetSelectedTextValue()">&nbsp;&nbsp;<strong>Tidak Disampaikan</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;"><b>G</b>.</td>
                                        <td width="45%" style="vertical-align: top;">PERJANJIAN DILUAR KETENTUAN ANTARA CALON NASABAH DENGAN WPP MAUPUN MWPP</td>
                                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="prj_dlr_ktn" value="Ada" required id="prj_dlr_ktn1" onchange="GetSelectedTextValue()">&nbsp;&nbsp;<strong>Ada</strong>
                                        </td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="prj_dlr_ktn" value="Tidak Ada" required id="prj_dlr_ktn2" onchange="GetSelectedTextValue()">&nbsp;&nbsp;<strong>Tidak Ada</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;"><b>H</b>.</td>
                                        <td width="45%" style="vertical-align: top;">JANJI KEUNTUNGAN ATAU SHARING PROFIT</td>
                                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="sharing_prft" value="Ada" required  id="share_prof1" onchange="GetSelectedTextValue()">&nbsp;&nbsp;<strong>Ada</strong>
                                        </td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="sharing_prft" value="Tidak Ada" required id="share_prof2" onchange="GetSelectedTextValue()">&nbsp;&nbsp;<strong>Tidak Ada</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;"><b>I</b>.</td>
                                        <td width="45%" style="vertical-align: top;">VERIFIKASI EMAIL NASABAH</td>
                                        <td width="1%" style="vertical-align: top; text-align:center;"><div style="margin:0px 3px;">:</div></td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="ver_email" value="Milik Nasabah" required  id="ver_nas1" onchange="GetSelectedTextValue()" >&nbsp;&nbsp;<strong>Milik Nasabah</strong>
                                        </td>
                                        <td style="vertical-align: top; white-space: nowrap;">
                                            <input type="radio" name="ver_email" value="Bukan Milik Nasabah" required id="ver_nas2" onchange="GetSelectedTextValue()" >&nbsp;&nbsp;<strong>Bukan Milik Nasabah</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label>Note</label>
                            <input type="text" class="form-control" name="reason_text" value=" " required>
                            <input type="hidden" class="form-control" value="<?php echo $x ?>" name="reason_id" required>
                        </div>
                    </div>
                </div>         
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col text-center">
            <button type="submit" id="accept" class="btn btn-success" name="accept">Accept</button>
            <button type="submit" name="decline" class="btn btn-danger">Reject</button>
        </div>
    </div>
    <!-- <div class="modal fade" id="modal_reject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reject</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Keterangan Reject</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal_reject" >Decline</button>
                </div>
            </div>
        </div>
    </div> -->
</form>
<script>
    function GetSelectedTextValue(){
        var prj_dlr_ktn, share_prof, ver_nas, product_ber, pfrl_perusahaan, prj_amanat, apk_pbk_akun, dly_stmnt, srna_prslhn;
        if(document.getElementById("prj_dlr_ktn1").checked){prj_dlr_ktn = '2';}
        if(document.getElementById("prj_dlr_ktn2").checked){prj_dlr_ktn = '1';}

        if(document.getElementById("share_prof1").checked){share_prof = '2';}
        if(document.getElementById("share_prof2").checked){share_prof = '1';}

        if(document.getElementById("ver_nas1").checked){ver_nas = '1';}
        if(document.getElementById("ver_nas2").checked){ver_nas = '2'; }

        if(document.getElementById("product_ber1").checked){product_ber = '1'; }
        if(document.getElementById("product_ber2").checked){product_ber = '2';}

        if(document.getElementById("pfrl_perusahaan1").checked){pfrl_perusahaan = '1'; }
        if(document.getElementById("pfrl_perusahaan2").checked){pfrl_perusahaan = '2';}

        if(document.getElementById("prj_amanat1").checked){prj_amanat = '1'; }
        if(document.getElementById("prj_amanat2").checked){prj_amanat = '2';}

        if(document.getElementById("apk_pbk_akun1").checked){apk_pbk_akun = '1'; }
        if(document.getElementById("apk_pbk_akun2").checked){apk_pbk_akun = '2';}

        if(document.getElementById("dly_stmnt1").checked){dly_stmnt = '1'; }
        if(document.getElementById("dly_stmnt2").checked){dly_stmnt = '2';}

        if(document.getElementById("srna_prslhn1").checked){srna_prslhn = '1'; }
        if(document.getElementById("srna_prslhn2").checked){srna_prslhn = '2';}


        if(prj_dlr_ktn == '2'       || 
            share_prof == '2'       || 
            ver_nas == '2'          || 
            product_ber == '2'      || 
            pfrl_perusahaan == '2'  || 
            prj_amanat == '2'       || 
            dly_stmnt == '2'        || 
            apk_pbk_akun == '2'     || 
            srna_prslhn == '2'){

            console.log('accept hilang')
            document.getElementById("accept").style.visibility = "hidden";
            // document.getElementById("prj_dlr_ktn1").required = false;
            // document.getElementById("prj_dlr_ktn2").required = false;
            // document.getElementById("share_prof1").required = false;
            // document.getElementById("share_prof2").required = false;
            // document.getElementById("ver_nas1").required = false;
            // document.getElementById("ver_nas2").required = false;

        } else {
            console.log('accept ada')
            document.getElementById("accept").style.visibility = "visible";
            // document.getElementById("prj_dlr_ktn1").required = true;
            // document.getElementById("prj_dlr_ktn2").required = true;
            // document.getElementById("share_prof1").required = true;
            // document.getElementById("share_prof2").required = true;
            // document.getElementById("ver_nas1").required = true;
            // document.getElementById("ver_nas2").required = true;
        }
    }

</script>