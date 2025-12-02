<?php
    if(isset($_GET["x"])){
        $x = form_input($_GET["x"]);
        $ttl_dps = 0;
        $ttl_eqt = 0;
        $jum_acc = 0;
        $tgl_evl = '';
?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">APUPPT</a></li>
            <li class="breadcrumb-item"><a href="#">EDD</a></li>
            <li class="breadcrumb-item"><a href="#">History EDD</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail History EDD</li>
        </ol>
    </nav>
    <div class="row mb-4">
        <?php
            $SQL_EDPAR = mysqli_query($db,'SELECT tb_range_edtype.ID_EDTYPE,tb_range_edtype.EDTYPE_DESC FROM tb_range_edtype');
            if($SQL_EDPAR && mysqli_num_rows($SQL_EDPAR) > 0){
                function thrplce($str, $ord){
                    $patt = ($ord == 3) ? "/(risiko\s+)/i" : "/(risiko\s+|\s+nasabah)/i";
                    return preg_replace($patt, '', $str);
                }
                while($RSLT_EDPAR = mysqli_fetch_assoc($SQL_EDPAR)){
        ?>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header font-weight-bold">Keterangan Parameter <?php echo $RSLT_EDPAR["EDTYPE_DESC"] ?></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" width="100%">
                                <thead class="bg-secondary text-dark">
                                    <tr>
                                        <th style="vertical-align: middle" class="text-center">No</th>
                                        <th style="vertical-align: middle" class="text-center"><?php echo thrplce($RSLT_EDPAR["EDTYPE_DESC"], $RSLT_EDPAR["ID_EDTYPE"]) ?></th>
                                        <th style="vertical-align: middle" class="text-center">Tingkat Risiko</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $SQL_EDPARVAL = mysqli_query($db,'
                                            SELECT
                                                tb_range_edd.EDD_DESC,
                                                tb_range_edd.EDD_LV
                                            FROM tb_range_edd
                                            WHERE tb_range_edd.EDD_TYPE = '.$RSLT_EDPAR["ID_EDTYPE"].'
                                        ');
                                        if($SQL_EDPARVAL && mysqli_num_rows($SQL_EDPARVAL) > 0){
                                            $nm = 1;
                                            while($RSTL_EDPARVAL = mysqli_fetch_assoc($SQL_EDPARVAL)){
                                    ?>
                                        <tr>
                                            <td class="text-center"><?php echo $nm.'.'; ?></td>
                                            <td class="text-center"><?php echo $RSTL_EDPARVAL["EDD_DESC"] ?></td>
                                            <td class="text-center"><?php echo $RSTL_EDPARVAL["EDD_LV"] ?></td>
                                        </tr>
                                    <?php
                                                $nm++;
                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php
                }
            }
        ?>
    </div>
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header font-weight-bold">Data Nasabah</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" width="100%">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th style="vertical-align: middle" class="text-center">TGL</th>
                                    <th style="vertical-align: middle" class="text-center">Nama</th>
                                    <th style="vertical-align: middle" class="text-center">NIK</th>
                                    <th style="vertical-align: middle" class="text-center">Email</th>
                                    <th style="vertical-align: middle" class="text-center">Login</th>
                                    <th style="vertical-align: middle" class="text-center">Konfirmasi APUPPT</th>
                                    <th style="vertical-align: middle" class="text-center">Deposit Per Hari</th>
                                    <th style="vertical-align: middle" class="text-center">Equity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $SQL_QWRY = mysqli_query($db,'
                                        SELECT
                                            tb2.ID_ACC,
                                            tb2.ACC_DATETIME,
                                            tb2.ACC_F_APP_PRIBADI_NAMA,
                                            tb2.ACC_F_APP_PRIBADI_ID,
                                            (
                                                SELECT
                                                    tb_member.MBR_EMAIL
                                                FROM tb_member
                                                WHERE tb_member.MBR_ID = tb2.ACC_MBR
                                                LIMIT 1
                                            ) AS EMAIL,
                                            tb2.ACC_LOGIN,
                                            (
                                                SELECT
                                                    IFNULL(
                                                        (
                                                    SELECT
                                                        CONCAT(tb_range.RNG_LEVEL, "(",SUM(tb_rangensb.NSBR_VAL * tb_rangetype.RATYP_BBR),")")
                                                    FROM tb_range
                                                    WHERE tb_range.RNG_TYPE = 2 
                                                    AND SUM(tb_rangensb.NSBR_VAL * tb_rangetype.RATYP_BBR) >= tb_range.RNG_MIN 
                                                    AND SUM(tb_rangensb.NSBR_VAL * tb_rangetype.RATYP_BBR) <= CAST(CASE WHEN tb_range.RNG_MAX = -1 THEN ~0 ELSE tb_range.RNG_MAX END AS UNSIGNED)
                                                    )
                                                    , NULL)
                                                FROM tb_apuppt	
                                                JOIN tb_rangensb
                                                ON(tb_apuppt.APU_RNGNSB1 = tb_rangensb.ID_NSBR
                                                OR tb_apuppt.APU_RNGNSB2 = tb_rangensb.ID_NSBR
                                                OR tb_apuppt.APU_RNGNSB3 = tb_rangensb.ID_NSBR
                                                OR tb_apuppt.APU_RNGNSB4 = tb_rangensb.ID_NSBR
                                                OR tb_apuppt.APU_RNGNSB5 = tb_rangensb.ID_NSBR
                                                OR tb_apuppt.APU_RNGNSB6 = tb_rangensb.ID_NSBR
                                                OR tb_apuppt.APU_RNGNSB7 = tb_rangensb.ID_NSBR
                                                OR tb_apuppt.APU_RNGNSB8 = tb_rangensb.ID_NSBR
                                                OR tb_apuppt.APU_RNGNSB9 = tb_rangensb.ID_NSBR)
                                                JOIN tb_rangetype ON(tb_rangetype.ID_RATYP = tb_rangensb.NSBR_TYPE)
                                                WHERE tb_apuppt.APU_ACC = tb2.ID_ACC
                                            ) AS RNG,
                                            IFNULL(
                                                (
                                                    SELECT
                                                        SUM(IFNULL(tb_dpwd.DPWD_AMOUNT,0))
                                                    FROM tb_dpwd
                                                    WHERE tb_dpwd.DPWD_LOGIN = tb2.ID_ACC
                                                    AND tb_dpwd.DPWD_TYPE = 1
                                                    AND tb_dpwd.DPWD_STS = -1
                                                    AND tb_dpwd.DPWD_STSACC = -1
                                                    AND tb_dpwd.DPWD_STSVER = -1
                                                    AND DATE(tb_dpwd.DPWD_DATETIME) = DATE(tb_apuppt_edd.ADD_DATTIME)
                                                )
                                            ,0) AS TOTAL_DP,
                                            (
                                                SELECT
                                                    SUM(
                                                        IF(MT4_TRADES.CMD = 6 AND MT4_TRADES.PROFIT > 0, MT4_TRADES.PROFIT, 0) +
                                                            (
                                                                IF(MT4_TRADES.CMD = 1 OR MT4_TRADES.CMD = 0, MT4_TRADES.PROFIT, 0) +
                                                                (
                                                                    IF(MT4_TRADES.CMD = 1 OR MT4_TRADES.CMD = 0, MT4_TRADES.COMMISSION, 0) +
                                                                    IF(MT4_TRADES.CMD = 1 OR MT4_TRADES.CMD = 0, MT4_TRADES.`SWAPS`, 0)
                                                                )
                                                            ) +
                                                        IF(MT4_TRADES.CMD = 6 AND MT4_TRADES.PROFIT < 0, MT4_TRADES.PROFIT, 0)
                                                    )
                                                FROM MT4_TRADES
                                                WHERE MT4_TRADES.LOGIN = tb2.ACC_LOGIN
                                                AND DATE(MT4_TRADES.CLOSE_TIME) BETWEEN DATE("1970-01-01") AND DATE(tb_apuppt_edd.ADD_DATTIME)
                                            ) AS EQT,
                                            tb_apuppt_edd.ADD_DATTIME
                                        FROM (tb_racc tb1, tb_racc tb2)
                                        JOIN tb_apuppt_edd ON(tb_apuppt_edd.ADD_MBR = tb1.ACC_MBR)
                                        WHERE tb1.ACC_F_APP_PRIBADI_ID = tb2.ACC_F_APP_PRIBADI_ID 
                                        AND MD5(MD5(MD5(tb_apuppt_edd.ID_ADD))) = "'.$x.'"
                                        AND (tb2.ACC_LOGIN != "0" AND tb2.ACC_LOGIN IS NOT NULL)
                                        AND tb2.ACC_WPCHECK = 6
                                        AND (DATE(tb2.ACC_DATETIME) BETWEEN DATE("1970-01-01") AND DATE(tb_apuppt_edd.ADD_DATTIME))
                                        GROUP BY tb2.ACC_LOGIN
                                    ');
                                    if($SQL_QWRY && mysqli_num_rows($SQL_QWRY) > 0){
                                        $jum_acc = mysqli_num_rows($SQL_QWRY);
                                        while($RSLT_QWRY = mysqli_fetch_assoc($SQL_QWRY)){
                                            $clr = ((strpos($RSLT_QWRY["RNG"], "Rendah") !== FALSE) ? 'success' : ((strpos($RSLT_QWRY["RNG"], "Menengah") !== FALSE) ? 'warning' : ((strpos($RSLT_QWRY["RNG"], "Tinggi") !== FALSE) ? 'danger' : 'secondary')));
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $RSLT_QWRY["ACC_DATETIME"] ?></td>
                                        <td class="text-center"><?php echo $RSLT_QWRY["ACC_F_APP_PRIBADI_NAMA"] ?></td>
                                        <td class="text-center"><?php echo $RSLT_QWRY["ACC_F_APP_PRIBADI_ID"] ?></td>
                                        <td class="text-center"><?php echo $RSLT_QWRY["EMAIL"] ?></td>
                                        <td class="text-center"><?php echo $RSLT_QWRY["ACC_LOGIN"] ?></td>
                                        <td class="text-center">
                                            <?php
                                                if(!is_null($RSLT_QWRY["RNG"])){
                                                    echo '<span class="badge bg-'.$clr.' h-50 d-inline-block bg-opacity-15 text-white" style="font-size: 12px;">'.$RSLT_QWRY["RNG"].'</span>';
                                                }
                                            ?>
                                        </td>
                                        <td class="text-center"><?php echo 'Rp.'.number_format($RSLT_QWRY["TOTAL_DP"], 0) ?></td>
                                        <td class="text-center"><?php echo '$.'.number_format($RSLT_QWRY["EQT"], 2) ?></td>
                                    </tr>
                                <?php
                                            $ttl_dps += $RSLT_QWRY["TOTAL_DP"];
                                            $ttl_eqt += $RSLT_QWRY["EQT"];
                                            $tgl_evl = $RSLT_QWRY["ADD_DATTIME"];
                                        }
                                    }
                                ?>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <h3><?php echo "Total Dari ($jum_acc) Akun:"; ?></h3>
                                    </td>
                                    <td class="text-center"><?php echo 'Rp.'.number_format($ttl_dps, 0); ?></td>
                                    <td class="text-center"><?php echo '$.'.number_format($ttl_eqt, 2); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Summary/Ringkasan
                    <div class="float-right row" style="margin-left: auto; justify-content: flex-end;">
                        <div class="col-12" style="flex-basis: max-content;">
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label" style="font-weight: 600;">Tanggal Dievaluasi:</label>
                                <div class="col-sm-7">
                                    <input type="date" disabled value="<?php echo date("Y-m-d", strtotime($tgl_evl))?>" class="form-control text-center">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" width="100%">
                            <thead class="bg-info text-white">
                                <tr>
                                    <th style="vertical-align: middle" class="text-center">Parameter</th>
                                    <th style="vertical-align: middle" class="text-center">Keterangan Data</th>
                                    <th style="vertical-align: middle" class="text-center">Data Nasabah</th>
                                    <th style="vertical-align: middle" class="text-center">Tingkat Risiko</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    function comp($ordr, $nasval, $compval){
                                        if($ordr == 1){
                                            $pn = preg_replace('/[^0-9]+/', '', $nasval);
                                            $pc = preg_replace('/[^0-9]+/', '', $compval);
                                            $dc = true;
                                            if($pn == $pc){
                                                return $compval;
                                                $dc = false; 
                                            }else if($dc){
                                                if($pn > $pc){
                                                    return $compval;
                                                }else{ return NULL; }
                                            }
                                            // return ($pn == $pc) ? $compval : (($compval != 0) ? $compval : NULL);
                                        }elseif($ordr == 2){
                                            $nasval2 = preg_replace('/[^0-9]/', '', $nasval);
                                            if(!is_null($compval) && $nasval2 >= 0){
                                                $comp_value = preg_replace( '/[^\d+-]/', '',$compval);
                                                $comp_sign  = preg_replace( '/([^><])+/', '', $compval );
                                                if(strpos($comp_value, '-') > 0){
                                                    $ARR_VAL = explode("-",$comp_value);
                                                    if(count($ARR_VAL) == 2){
                                                        if((int)$nasval2 > (int)$ARR_VAL[0] && (int)$nasval2 <= (int)$ARR_VAL[1]){
                                                            return $compval;
                                                        }else{ return NULL; }
                                                    }else{ return NULL; }
                                                }else if(strpos($comp_value, '-') == false && $comp_sign == '<'){
                                                    if((int)$nasval2 <= (int)$comp_value){
                                                        return $compval;
                                                    }else{ return NULL; }
                                                }else if(strpos($comp_value, '-') == false && $comp_sign == '>'){
                                                    if((int)$nasval2 > (int)$comp_value){
                                                        return $compval;
                                                    }else{ return NULL; }
                                                }else { return NULL; }
                                            }else{ return NULL; }
                                            
                                        }else{ return NULL; }
                                    }
                                    $SQL_EDPAR2 = mysqli_query($db,'SELECT tb_range_edtype.ID_EDTYPE,tb_range_edtype.EDTYPE_DESC FROM tb_range_edtype');
                                    $nx = 0;
                                    $arf = '';
                                    if($SQL_EDPAR2 && mysqli_num_rows($SQL_EDPAR2) > 0){
                                        while($RSLT_EPAR = mysqli_fetch_assoc($SQL_EDPAR2)){
                                            $vl = (($RSLT_EPAR["ID_EDTYPE"] == 1) ?  $jum_acc.' account' : (($RSLT_EPAR["ID_EDTYPE"] == 2) ? 'Rp.'.number_format($ttl_dps, 0) : (($RSLT_EPAR["ID_EDTYPE"] == 3) ? '$'.number_format($ttl_eqt, 2) : 0)));
                                ?>
                                    <tr>
                                        <td><?php echo $RSLT_EPAR["EDTYPE_DESC"] ?></td>
                                        <td>
                                        <select class="form-control par" name="param" required readonly>
                                                <?php
                                                    $SQL_SEL = mysqli_query($db,'
                                                        SELECT
                                                            tb_range_edd.ID_EDD,
                                                            tb_range_edd.EDD_DESC,
                                                            tb_range_edd.EDD_LV,
                                                            tb_apuppt_edd.ADD_ARF
                                                        FROM tb_range_edd
                                                        JOIN tb_apuppt_edd
                                                        ON (tb_range_edd.ID_EDD = tb_apuppt_edd.ADD_VAL1
                                                        OR tb_range_edd.ID_EDD = tb_apuppt_edd.ADD_VAL2
                                                        OR tb_range_edd.ID_EDD = tb_apuppt_edd.ADD_VAL3)
                                                        WHERE tb_range_edd.EDD_TYPE = '.$RSLT_EPAR["ID_EDTYPE"].'
                                                        AND MD5(MD5(MD5(tb_apuppt_edd.ID_ADD))) = "'.$x.'"
                                                    ');
                                                    if($SQL_SEL && mysqli_num_rows($SQL_SEL) > 0){
                                                        while($SEL_RSLT = mysqli_fetch_assoc($SQL_SEL)){
                                                            $arf = $SEL_RSLT["ADD_ARF"];
                                                ?>
                                                    <option value="<?php echo $SEL_RSLT["EDD_LV"] ?>" disabled selected data-x="<?php echo base64_encode($SEL_RSLT["ID_EDD"]) ?>">
                                                        <?php echo $SEL_RSLT["EDD_DESC"]; ?>
                                                    </option>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control text-center" readonly value="<?php echo $vl ?>">
                                        </td>
                                        <td>
                                            <input type="text" readonly class="form-control text-center lev">
                                        </td>
                                    </tr>
                                <?php
                                            $nx++;
                                        }
                                    }
                                ?>
                                <tr>
                                    <td>Analisa Dan Rekomendasi, Faktor Lainnya</td>
                                    <td colspan="3"><input type="text" class="form-control text-center" value="<?php echo $arf;?>" disabled></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <form method="post" id="frm">
                        <?php
                            for($n = 1; $n <= $nx; $n++){
                                echo '<input type="hidden" name="x'.$n.'" class="text-center hid" readonly required>';
                            }
                        ?>
                        <a target="_blank" href="<?php echo 'pdf/root/apu_eddpdf.php?x='.$x ?>" class="btn btn-lg btn-info">Print</a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
    <script>
        let par = Array.from(document.getElementsByClassName('par'));
        let lev = Array.from(document.getElementsByClassName('lev'));
        let hid = Array.from(document.getElementsByClassName('hid'));
        par.forEach(function(el, i){
            lev[i].value = el.options[el.selectedIndex].value;
            hid[i].value = (el.options[el.selectedIndex].dataset.x !== undefined) ? el.options[el.selectedIndex].dataset.x : null;
            el.addEventListener('change', function(e){
                lev[i].value = e.currentTarget.value;
                hid[i].value = e.currentTarget.options[e.currentTarget.selectedIndex].dataset.x;
            });
        });
        document.getElementById('frm').addEventListener('submit', function(e){
            e.preventDefault();
            let ARR_VAL = [];
            hid.forEach(function(elem, i){ARR_VAL.push(elem.value);});
            
            if(!ARR_VAL.includes('')){
                e.target.submit();
            }else{
                alert(`Harap Pilih Keterangan Data Pada Baris ${lev[ARR_VAL.indexOf('')].parentElement.previousElementSibling.previousElementSibling.previousElementSibling.innerText}`); 
            }
        });
    </script>
<?php
    }
?>