<?php
    function ret_dat($dat_nas, $dat_comp){
        if(!is_null($dat_comp) && $dat_nas > 0){
            $comp_value = preg_replace( '/[^\d+-]/', '',$dat_comp);
            $comp_sign  = preg_replace( '/([^><])+$/', '', $dat_comp );
            if(strpos($comp_value, '-') > 0){
                $ARR_VAL = explode("-",$comp_value);
                if(count($ARR_VAL) == 2){
                    if((int)$dat_nas > (int)$ARR_VAL[0] && (int)$dat_nas <= (int)$ARR_VAL[1]){
                        return $dat_comp;
                    }else{ return NULL; }
                }else{ return false; }
            }else if(strpos($comp_value, '-') == false && $comp_sign == '<'){
                if((int)$dat_nas <= (int)$comp_value){
                    return $dat_comp;
                }else{ return NULL; }
            }else if(strpos($comp_value, '-') == false && $comp_sign == '>'){
                if((int)$dat_nas > (int)$comp_value){
                    return $dat_comp;
                }else{ return NULL; }
            }else { return NULL; }
        }else{ return NULL; }
    }
    function get_prov($kdp){
        global $db;
        if(!is_null($kdp)){
            $SQL_PROV = mysqli_query($db,'
                SELECT
                    tb_kodepos.KDP_PROV
                FROM tb_kodepos
                WHERE tb_kodepos.KDP_POS = '.$kdp.'
                LIMIT 1
            ');
            if($SQL_PROV && mysqli_num_rows($SQL_PROV) > 0){
                $RSLT_PROV = mysqli_fetch_assoc($SQL_PROV);
                return $RSLT_PROV["KDP_PROV"];
            }else{ return NULL; }
        }else{ return NULL; }
    }
    if(isset($_GET["x"])){
        $x = form_input($_GET["x"]);

        $SQL_DT = mysqli_query($db,'
            SELECT
                tb_racc.ID_ACC,
                tb_racc.ACC_F_APP_PRIBADI_NAMA,
                tb_racc.ACC_LOGIN,
                tb_racc.ACC_DATETIME,
                IF(tb_racc.ACC_TYPE = 1, CONCAT("SPA - ", UPPER(tb_racc.ACC_TYPEACC)), "Multilateral") AS PRD,
                tb_racc.ACC_INITIALMARGIN,
                tb_racc.ACC_F_APP_KRJ_TYPE,
                tb_racc.ACC_F_APP_PRIBADI_ALAMAT,
                tb_racc.ACC_F_APP_PRIBADI_ZIP,
                tb_racc.ACC_F_APP_FILE_IMG,
                tb_racc.ACC_F_APP_FILE_FOTO,
                tb_racc.ACC_F_APP_FILE_ID,
                tb_racc.ACC_F_APP_FILE_IMG2,
                tb_racc.ACC_MBR,
                (
                    SELECT
                        tb_dpwd.DPWD_PIC
                    FROM tb_dpwd
                    WHERE tb_dpwd.DPWD_RACC = tb_racc.ID_ACC
                    LIMIT 1
                ) AS DPWD_PIC
            FROM tb_racc
            WHERE MD5(MD5(tb_racc.ID_ACC)) = "'.$x.'"
            AND tb_racc.ACC_DERE = 1
            LIMIT 1
        ');
        if($SQL_DT && mysqli_num_rows($SQL_DT) > 0){
            $DT_RSLT = mysqli_fetch_assoc($SQL_DT);
            if(isset($_POST["iser"])){
                $x1 = (strlen($_POST["x1"]) > 0) ? form_input(base64_decode($_POST["x1"])) : 'NULL';
                $x2 = (strlen($_POST["x2"]) > 0) ? form_input(base64_decode($_POST["x2"])) : 'NULL';
                $x3 = (strlen($_POST["x3"]) > 0) ? form_input(base64_decode($_POST["x3"])) : 'NULL';
                $x4 = (strlen($_POST["x4"]) > 0) ? form_input(base64_decode($_POST["x4"])) : 'NULL';
                $x5 = (strlen($_POST["x5"]) > 0) ? form_input(base64_decode($_POST["x5"])) : 'NULL';
                $x6 = (strlen($_POST["x6"]) > 0) ? form_input(base64_decode($_POST["x6"])) : 'NULL';
                $x7 = (strlen($_POST["x7"]) > 0) ? form_input(base64_decode($_POST["x7"])) : 'NULL';
                $x8 = (strlen($_POST["x8"]) > 0) ? form_input(base64_decode($_POST["x8"])) : 'NULL';
                $x9 = (strlen($_POST["x9"]) > 0) ? form_input(base64_decode($_POST["x9"])) : 'NULL';

                $SQL_INS = mysqli_query($db, '
                    INSERT INTO tb_apuppt SET 
                    tb_apuppt.APU_ADM       = '.$user1["ADM_ID"].',
                    tb_apuppt.APU_MBR       = '.$DT_RSLT["ACC_MBR"].',
                    tb_apuppt.APU_ACC       = '.$DT_RSLT["ID_ACC"].',
                    tb_apuppt.APU_RNGNSB1   = '.$x1.',
                    tb_apuppt.APU_RNGNSB2   = '.$x2.',
                    tb_apuppt.APU_RNGNSB3   = '.$x3.',
                    tb_apuppt.APU_RNGNSB4   = '.$x4.',
                    tb_apuppt.APU_RNGNSB5   = '.$x5.',
                    tb_apuppt.APU_RNGNSB6   = '.$x6.',
                    tb_apuppt.APU_RNGNSB7   = '.$x7.',
                    tb_apuppt.APU_RNGNSB8   = '.$x8.',
                    tb_apuppt.APU_RNGNSB9   = '.$x9.',
                    tb_apuppt.APU_DATETIME  = "'.date("Y-m-d H:i:s").'",
                    tb_apuppt.APU_TIMESTAMP = "'.date("Y-m-d H:i:s").'"
                ') or die("<script>alert('Err DeBe Ins2');location.href='home.php?page=apu_evnas'</script>");
                die("<script>alert('Success Apply Nasabah');location.href='home.php?page=apu_evnas'</script>");
            }
?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">APUPPT</a></li>
            <li class="breadcrumb-item"><a href="#">Evaluasi Nasabah</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Evaluasi Nasabah</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-6">
            <div class="card-header font-weight-bold">Informasi Nasabah</div>
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Nama Nasabah</b> <a class="float-right"><?php echo $DT_RSLT["ACC_F_APP_PRIBADI_NAMA"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>No. Accout</b> <a class="float-right"><?php echo $DT_RSLT["ACC_LOGIN"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Tanggal Buka Account</b> <a class="float-right"><?php echo $DT_RSLT["ACC_DATETIME"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Produk Investasi</b> <a class="float-right"><?php echo $DT_RSLT["PRD"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Besaran Investasi Awal</b> <a class="float-right"><?php echo number_format($DT_RSLT["ACC_INITIALMARGIN"], 0) ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Pekerjaan/Profesi Nasabah</b> <a class="float-right"><?php echo $DT_RSLT["ACC_F_APP_KRJ_TYPE"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Alamat</b> <a class="float-right"><?php echo $DT_RSLT["ACC_F_APP_PRIBADI_ALAMAT"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Kode Pos</b> <a class="float-right"><?php echo $DT_RSLT["ACC_F_APP_PRIBADI_ZIP"].' ('.get_prov($DT_RSLT["ACC_F_APP_PRIBADI_ZIP"]).')'?></a>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header font-weight-bold">Dokumen Nasabah</div>
                <div class="card-body" style="height: 415px;">
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-md-3 mb-3 text-center">
                                <div>
                                    <?php if($DT_RSLT['ACC_F_APP_FILE_IMG'] == ''|| $DT_RSLT['ACC_F_APP_FILE_IMG'] == '-' ){ ?>
                                        <img src="assets/img/unknown-file.png" width="100%">
                                    <?php } else { ?>
                                        <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$DT_RSLT['ACC_F_APP_FILE_IMG']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$DT_RSLT['ACC_F_APP_FILE_IMG']; ?>" width="75%"></a>
                                        <hr>
                                    <?php }; ?>
                                    <strong><u>Dokumen Pendukung</u></strong>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 text-center">
                                <div>
                                    <?php if($DT_RSLT['ACC_F_APP_FILE_FOTO'] == ''|| $DT_RSLT['ACC_F_APP_FILE_FOTO'] == '-' ){ ?>
                                        <img src="assets/img/unknown-file.png" width="100%">
                                    <?php } else { ?>
                                        <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$DT_RSLT['ACC_F_APP_FILE_FOTO']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$DT_RSLT['ACC_F_APP_FILE_FOTO']; ?>" width="75%"></a>
                                        <hr>
                                    <?php }; ?>
                                    <strong><u>Foto Terbaru</u></strong>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 text-center">
                                <div>
                                    <?php if($DT_RSLT['ACC_F_APP_FILE_ID'] == '' || $DT_RSLT['ACC_F_APP_FILE_ID'] == '-' ){ ?>
                                        <img src="assets/img/unknown-file.png" width="100%">
                                    <?php } else { ?>
                                        <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$DT_RSLT['ACC_F_APP_FILE_ID']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$DT_RSLT['ACC_F_APP_FILE_ID']; ?>" width="75%"></a>
                                        <hr>
                                    <?php }; ?>
                                    <strong><u>Foto Identitas</u></strong>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 text-center">
                                <div>
                                    <?php if($DT_RSLT['ACC_F_APP_FILE_IMG2'] == ''|| $DT_RSLT['ACC_F_APP_FILE_IMG2'] == '-' ){ ?>
                                        <img src="assets/img/unknown-file.png" width="100%">
                                    <?php } else { ?>
                                        <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$DT_RSLT['ACC_F_APP_FILE_IMG2']; ?>">
                                        <img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$DT_RSLT['ACC_F_APP_FILE_IMG2']; ?>" width="75%"></a>
                                        <hr>
                                    <?php }; ?>
                                    <strong><u>Dokumen Pendukung Lainya</u></strong>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3 text-center"><div>&nbsp;</div></div>
                            <div class="col-md-4 mb-3 text-center">
                                <div>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$DT_RSLT['DPWD_PIC']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$DT_RSLT['DPWD_PIC']; ?>" width="75%"></a>
                                    <hr>
                                    <strong><u>Deposit New Account</u></strong>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 text-center"><div>&nbsp;</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Faktor-faktor yang di periksa
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" width="100%">
                            <thead class="bg-success">
                                <tr>
                                    <th style="vertical-align: middle" class="text-center">No.</th>
                                    <th style="vertical-align: middle" class="text-center">Faktor Risiko</th>
                                    <th style="vertical-align: middle" class="text-center">Keterangan Data</th>
                                    <th style="vertical-align: middle" class="text-center">Nilai Risiko</th>
                                    <th style="vertical-align: middle" class="text-center">Bobot Risiko</th>
                                    <th style="vertical-align: middle" class="text-center">Total Risiko</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $SQL_RNG = mysqli_query($db,'
                                        SELECT
                                            tb_rangetype.RATYP_NAME AS TP,
                                            tb_rangetype.ID_RATYP AS NSBR_TYPE,
                                            tb_rangetype.RATYP_BBR AS NSBR_BBTRISK
                                        FROM tb_rangetype
                                        ORDER BY CASE NSBR_TYPE
                                            WHEN 9 THEN 1
                                            WHEN 8 THEN 2
                                            WHEN 7 THEN 3
                                            WHEN 1 THEN 4
                                            WHEN 2 THEN 5
                                            WHEN 3 THEN 6
                                            WHEN 5 THEN 7
                                            WHEN 4 THEN 8
                                            WHEN 6 THEN 9
                                        ELSE 10 END
                                    ');
                                    if($SQL_RNG && mysqli_num_rows($SQL_RNG) > 0){
                                        $i = 1;
                                        while($RSLT_RNG = mysqli_fetch_assoc($SQL_RNG)){
                                ?>
                                    <tr>
                                        <td><?php echo $i.'.'; ?></td>
                                        <td><?php echo $RSLT_RNG["TP"] ?></td>
                                        <td>
                                            <select class="form-control par" name="param" required>
                                                <option value disabled selected>Plih Keterangan Data</option>
                                                <?php
                                                    $SQL_SEL = mysqli_query($db,'
                                                        SELECT
                                                            tb_rangensb.ID_NSBR,
                                                            tb_rangensb.NSBR_TYNAME,
                                                            tb_rangensb.NSBR_VAL
                                                        FROM tb_rangensb
                                                        WHERE tb_rangensb.NSBR_TYPE = '.$RSLT_RNG["NSBR_TYPE"].'
                                                    ');
                                                    if($SQL_SEL && mysqli_num_rows($SQL_SEL) > 0){
                                                        while($SEL_RSLT = mysqli_fetch_assoc($SQL_SEL)){
                                                ?>
                                                    <option value="<?php echo $SEL_RSLT["NSBR_VAL"] ?>" data-x="<?php echo base64_encode($SEL_RSLT["ID_NSBR"]) ?>"
                                                        <?php 
                                                            if(
                                                                $DT_RSLT["PRD"]                == $SEL_RSLT["NSBR_TYNAME"] ||
                                                                $DT_RSLT["ACC_F_APP_KRJ_TYPE"] == $SEL_RSLT["NSBR_TYNAME"] ||
                                                                ret_dat(
                                                                    $DT_RSLT["ACC_INITIALMARGIN"], 
                                                                    ($RSLT_RNG["NSBR_TYPE"] == 2) ? $SEL_RSLT["NSBR_TYNAME"] : NULL
                                                                ) == $SEL_RSLT["NSBR_TYNAME"] ||
                                                                get_prov(
                                                                    ($RSLT_RNG["NSBR_TYPE"] == 6) ? $DT_RSLT["ACC_F_APP_PRIBADI_ZIP"] : NULL
                                                                ) == strtoupper($SEL_RSLT["NSBR_TYNAME"])
                                                            ){ echo 'selected'; } 
                                                        
                                                        ?>
                                                    >
                                                        <?php echo $SEL_RSLT["NSBR_TYNAME"] ?>
                                                    </option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control text-center nir" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control text-center bbr" value="<?php echo $RSLT_RNG["NSBR_BBTRISK"] ?>" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control text-center ttr" readonly>
                                        </td>
                                    </tr>
                                <?php       
                                            $i++;            
                                        }
                                    }
                                ?>
                                <tr>
                                    <td class="text-center" colspan="4">
                                        <h3>Penilaian Risiko Keseluruhan/Total:</h3>
                                    </td>
                                    <td colspan="2"><input type="number" class="form-control text-center" id="total" readonly></td>
                                </tr>
                                <tr>
                                    <td class="text-center" colspan="4">
                                        <h3>Tingkat Risiko:</h3>
                                    </td>
                                    <td colspan="2"><input type="text" class="form-control text-center" id="tingResk" readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <form method="post">
                        <button type="submit" name="iser" class="btn btn-lg btn-success">Evaluasi</button>
                        <?php
                            for($n = 1; $n < $i; $n++){
                                echo '<input type="hidden" name="x'.$n.'" class="text-center hid" readonly>';
                            }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(async function(){
            let par = await Array.from(document.getElementsByClassName('par'));
            let nir = await Array.from(document.getElementsByClassName('nir'));
            let bbr = await Array.from(document.getElementsByClassName('bbr'));
            let ttr = await Array.from(document.getElementsByClassName('ttr'));
            let hid = await Array.from(document.getElementsByClassName('hid'));
            let total = 0;
            await par.forEach(function(el,i){
                var val = el.value;
                nir[i].value = val;
                ttr[i].value = (val * bbr[i].value > 0) ? val * bbr[i].value : null;
                hid[i].value = (el.options[el.selectedIndex].dataset.x === undefined) ? null : el.options[el.selectedIndex].dataset.x;
                total = 0;
                for(let a=0;a<par.length; a++){
                    total += (+ttr[a].value);
                }
                document.getElementById('total').value = total;
                el.addEventListener('change', function(e){
                    disp(e,i,par,nir,ttr,bbr,hid);
                });
                nir[i].value = el.value;
            });
            await $.ajax({
                url      : 'ajax/get_range.php',
                type     : 'GET',
                dataType : 'JSON',
                data     : {
                    val2 : document.getElementById('total').value
                }
            }).done(function(resp){
                document.getElementById('tingResk').value = (resp[0] === undefined) ? null : resp[0];
            });
        });
        function disp(e,i,par,nir,ttr,bbr,hid){
            var val = e.currentTarget.value;
            nir[i].value = val;
            ttr[i].value = val * bbr[i].value;
            hid[i].value = e.currentTarget.options[e.currentTarget.selectedIndex].dataset.x;
            total = 0;
            for(let a=0;a<par.length; a++){
                total += (+ttr[a].value);
            }
            document.getElementById('total').value = total;
            $.ajax({
                url      : 'ajax/get_range.php',
                type     : 'GET',
                dataType : 'JSON',
                data     : {
                    val : document.getElementById('total').value
                }
            }).done(function(resp){
                document.getElementById('tingResk').value = resp[0];
            });
        }
    </script>
<?php
        }
    }else if(isset($_GET["x2"])){
        $x2 = form_input($_GET["x2"]);
        $SQL_D2 = mysqli_query($db,'
            SELECT
                tb_apuppt.APU_RNGNSB1,
                tb_apuppt.APU_RNGNSB2,
                tb_apuppt.APU_RNGNSB3,
                tb_apuppt.APU_RNGNSB4,
                tb_apuppt.APU_RNGNSB5,
                tb_apuppt.APU_RNGNSB6,
                tb_apuppt.APU_RNGNSB7,
                tb_apuppt.APU_RNGNSB8,
                tb_apuppt.APU_RNGNSB9,
                tb_racc.ACC_F_APP_PRIBADI_NAMA,
                tb_racc.ACC_LOGIN,
                tb_racc.ACC_DATETIME,
                IF(tb_racc.ACC_TYPE = 1, CONCAT("SPA - ", UPPER(tb_racc.ACC_TYPEACC)), "Multilateral") AS PRD,
                tb_racc.ACC_INITIALMARGIN,
                tb_racc.ACC_F_APP_KRJ_TYPE,
                tb_racc.ACC_F_APP_PRIBADI_ALAMAT,
                tb_racc.ACC_F_APP_PRIBADI_ZIP,
                tb_racc.ACC_F_APP_FILE_IMG,
                tb_racc.ACC_F_APP_FILE_FOTO,
                tb_racc.ACC_F_APP_FILE_ID,
                tb_racc.ACC_F_APP_FILE_IMG2,
                tb_racc.ACC_MBR,
                (
                    SELECT
                        tb_dpwd.DPWD_PIC
                    FROM tb_dpwd
                    WHERE tb_dpwd.DPWD_RACC = tb_racc.ID_ACC
                    LIMIT 1
                ) AS DPWD_PIC
            FROM tb_apuppt
            JOIN tb_racc
            ON(tb_apuppt.APU_ACC = tb_racc.ID_ACC)
            WHERE MD5(MD5(tb_apuppt.ID_APU)) = "'.$x2.'"
        ');
        if($SQL_D2 && mysqli_num_rows($SQL_D2) > 0){
            $RSLT_D2 = mysqli_fetch_assoc($SQL_D2);
            
?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">APUPPT</a></li>
            <li class="breadcrumb-item"><a href="#">Evaluasi Nasabah</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Evaluasi Nasabah</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-6">
            <div class="card-header font-weight-bold">Informasi Nasabah</div>
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Nama Nasabah</b> <a class="float-right"><?php echo $RSLT_D2["ACC_F_APP_PRIBADI_NAMA"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>No. Accout</b> <a class="float-right"><?php echo $RSLT_D2["ACC_LOGIN"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Tanggal Buka Account</b> <a class="float-right"><?php echo $RSLT_D2["ACC_DATETIME"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Produk Investasi</b> <a class="float-right"><?php echo $RSLT_D2["PRD"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Besaran Investasi Awal</b> <a class="float-right"><?php echo number_format($RSLT_D2["ACC_INITIALMARGIN"], 0) ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Pekerjaan/Profesi Nasabah</b> <a class="float-right"><?php echo $RSLT_D2["ACC_F_APP_KRJ_TYPE"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Alamat</b> <a class="float-right"><?php echo $RSLT_D2["ACC_F_APP_PRIBADI_ALAMAT"] ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Kode Pos</b> <a class="float-right"><?php echo $RSLT_D2["ACC_F_APP_PRIBADI_ZIP"].' ('.get_prov($RSLT_D2["ACC_F_APP_PRIBADI_ZIP"]).')'?></a>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header font-weight-bold">Dokumen Nasabah</div>
                <div class="card-body" style="height: 415px;">
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-md-3 mb-3 text-center">
                                <div>
                                    <?php if($RSLT_D2['ACC_F_APP_FILE_IMG'] == ''|| $RSLT_D2['ACC_F_APP_FILE_IMG'] == '-' ){ ?>
                                        <img src="assets/img/unknown-file.png" width="100%">
                                    <?php } else { ?>
                                        <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_D2['ACC_F_APP_FILE_IMG']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_D2['ACC_F_APP_FILE_IMG']; ?>" width="75%"></a>
                                        <hr>
                                    <?php }; ?>
                                    <strong><u>Dokumen Pendukung</u></strong>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 text-center">
                                <div>
                                    <?php if($RSLT_D2['ACC_F_APP_FILE_FOTO'] == ''|| $RSLT_D2['ACC_F_APP_FILE_FOTO'] == '-' ){ ?>
                                        <img src="assets/img/unknown-file.png" width="100%">
                                    <?php } else { ?>
                                        <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_D2['ACC_F_APP_FILE_FOTO']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_D2['ACC_F_APP_FILE_FOTO']; ?>" width="75%"></a>
                                        <hr>
                                    <?php }; ?>
                                    <strong><u>Foto Terbaru</u></strong>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 text-center">
                                <div>
                                    <?php if($RSLT_D2['ACC_F_APP_FILE_ID'] == '' || $RSLT_D2['ACC_F_APP_FILE_ID'] == '-' ){ ?>
                                        <img src="assets/img/unknown-file.png" width="100%">
                                    <?php } else { ?>
                                        <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_D2['ACC_F_APP_FILE_ID']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_D2['ACC_F_APP_FILE_ID']; ?>" width="75%"></a>
                                        <hr>
                                    <?php }; ?>
                                    <strong><u>Foto Identitas</u></strong>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3 text-center">
                                <div>
                                    <?php if($RSLT_D2['ACC_F_APP_FILE_IMG2'] == ''|| $RSLT_D2['ACC_F_APP_FILE_IMG2'] == '-' ){ ?>
                                        <img src="assets/img/unknown-file.png" width="100%">
                                    <?php } else { ?>
                                        <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_D2['ACC_F_APP_FILE_IMG2']; ?>">
                                        <img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_D2['ACC_F_APP_FILE_IMG2']; ?>" width="75%"></a>
                                        <hr>
                                    <?php }; ?>
                                    <strong><u>Dokumen Pendukung Lainya</u></strong>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3 text-center"><div>&nbsp;</div></div>
                            <div class="col-md-4 mb-3 text-center">
                                <div>
                                    <a target="_blank" href="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_D2['DPWD_PIC']; ?>"><img src="<?php echo 'https://'.$bucketName.'.s3.'.$region.'.amazonaws.com/'.$folder.'/'.$RSLT_D2['DPWD_PIC']; ?>" width="75%"></a>
                                    <hr>
                                    <strong><u>Deposit New Account</u></strong>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 text-center"><div>&nbsp;</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Faktor-faktor yang di periksa
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" width="100%">
                            <thead class="bg-success">
                                <tr>
                                    <th style="vertical-align: middle" class="text-center">No.</th>
                                    <th style="vertical-align: middle" class="text-center">Faktor Risiko</th>
                                    <th style="vertical-align: middle" class="text-center">Keterangan Data</th>
                                    <th style="vertical-align: middle" class="text-center">Nilai Risiko</th>
                                    <th style="vertical-align: middle" class="text-center">Bobot Risiko</th>
                                    <th style="vertical-align: middle" class="text-center">Total Risiko</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $SQL_RNG = mysqli_query($db,'
                                        SELECT
                                            tb_rangetype.RATYP_NAME AS TP,
                                            tb_rangetype.ID_RATYP AS NSBR_TYPE,
                                            tb_rangetype.RATYP_BBR AS NSBR_BBTRISK
                                        FROM tb_rangetype
                                        ORDER BY CASE NSBR_TYPE
                                            WHEN 9 THEN 1
                                            WHEN 8 THEN 2
                                            WHEN 7 THEN 3
                                            WHEN 1 THEN 4
                                            WHEN 2 THEN 5
                                            WHEN 3 THEN 6
                                            WHEN 5 THEN 7
                                            WHEN 4 THEN 8
                                            WHEN 6 THEN 9
                                        ELSE 10 END
                                    ');
                                    if($SQL_RNG && mysqli_num_rows($SQL_RNG) > 0){
                                        $i = 1;
                                        while($RSLT_RNG = mysqli_fetch_assoc($SQL_RNG)){
                                ?>
                                    <tr>
                                        <td><?php echo $i.'.'; ?></td>
                                        <td><?php echo $RSLT_RNG["TP"] ?></td>
                                        <td>
                                            <select class="form-control par" name="param" required>
                                                <option value disabled selected>Plih Keterangan Data</option>
                                                <?php
                                                    $SQL_SEL = mysqli_query($db,'
                                                        SELECT
                                                            tb_rangensb.ID_NSBR,
                                                            tb_rangensb.NSBR_TYNAME,
                                                            tb_rangensb.NSBR_VAL
                                                        FROM tb_rangensb
                                                        WHERE tb_rangensb.NSBR_TYPE = '.$RSLT_RNG["NSBR_TYPE"].'
                                                    ');
                                                    if($SQL_SEL && mysqli_num_rows($SQL_SEL) > 0){
                                                        while($SEL_RSLT = mysqli_fetch_assoc($SQL_SEL)){
                                                ?>
                                                    <option value="<?php echo $SEL_RSLT["NSBR_VAL"] ?>" data-x="<?php echo base64_encode($SEL_RSLT["ID_NSBR"]) ?>"
                                                        <?php 
                                                            if(
                                                                $RSLT_D2["APU_RNGNSB1"] == $SEL_RSLT["ID_NSBR"] ||
                                                                $RSLT_D2["APU_RNGNSB2"] == $SEL_RSLT["ID_NSBR"] ||
                                                                $RSLT_D2["APU_RNGNSB3"] == $SEL_RSLT["ID_NSBR"] ||
                                                                $RSLT_D2["APU_RNGNSB4"] == $SEL_RSLT["ID_NSBR"] ||
                                                                $RSLT_D2["APU_RNGNSB5"] == $SEL_RSLT["ID_NSBR"] ||
                                                                $RSLT_D2["APU_RNGNSB6"] == $SEL_RSLT["ID_NSBR"] ||
                                                                $RSLT_D2["APU_RNGNSB7"] == $SEL_RSLT["ID_NSBR"] ||
                                                                $RSLT_D2["APU_RNGNSB8"] == $SEL_RSLT["ID_NSBR"] ||
                                                                $RSLT_D2["APU_RNGNSB9"] == $SEL_RSLT["ID_NSBR"]
                                                            ){ echo 'selected'; } 
                                                        
                                                        ?>
                                                    >
                                                        <?php echo $SEL_RSLT["NSBR_TYNAME"] ?>
                                                    </option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control text-center nir" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control text-center bbr" value="<?php echo $RSLT_RNG["NSBR_BBTRISK"] ?>" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control text-center ttr" readonly>
                                        </td>
                                    </tr>
                                <?php       
                                            $i++;            
                                        }
                                    }
                                ?>
                                <tr>
                                    <td class="text-center" colspan="4">
                                        <h3>Penilaian Risiko Keseluruhan/Total:</h3>
                                    </td>
                                    <td colspan="2"><input type="number" class="form-control text-center" id="total" readonly></td>
                                </tr>
                                <tr>
                                    <td class="text-center" colspan="4">
                                        <h3>Tingkat Risiko:</h3>
                                    </td>
                                    <td colspan="2"><input type="text" class="form-control text-center" id="tingResk" readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(async function(){
            let par = await Array.from(document.getElementsByClassName('par'));
            let nir = await Array.from(document.getElementsByClassName('nir'));
            let bbr = await Array.from(document.getElementsByClassName('bbr'));
            let ttr = await Array.from(document.getElementsByClassName('ttr'));
            let total = 0;
            await par.forEach(function(el,i){
                var val = el.value;
                nir[i].value = val;
                ttr[i].value = (val * bbr[i].value > 0) ? val * bbr[i].value : null;
                total = 0;
                for(let a=0;a<par.length; a++){
                    total += (+ttr[a].value);
                }
                document.getElementById('total').value = total;
                el.addEventListener('change', function(e){
                    disp(e,i,par,nir,ttr,bbr);
                });
                nir[i].value = el.value;
            });
            await $.ajax({
                url      : 'ajax/get_range.php',
                type     : 'GET',
                dataType : 'JSON',
                data     : {
                    val2 : document.getElementById('total').value
                }
            }).done(function(resp){
                document.getElementById('tingResk').value = (resp[0] === undefined) ? null : resp[0];
            });
        });
        function disp(e,i,par,nir,ttr,bbr){
            var val = e.currentTarget.value;
            nir[i].value = val;
            ttr[i].value = val * bbr[i].value;
            total = 0;
            for(let a=0;a<par.length; a++){
                total += (+ttr[a].value);
            }
            document.getElementById('total').value = total;
            $.ajax({
                url      : 'ajax/get_range.php',
                type     : 'GET',
                dataType : 'JSON',
                data     : {
                    val : document.getElementById('total').value
                }
            }).done(function(resp){
                document.getElementById('tingResk').value = resp[0];
            });
        }
    </script>
<?php
        }
    }else{ die("<script>alert('Data Not Found');location.href='home.php?page=apu_evnas'</script>"); }
?>