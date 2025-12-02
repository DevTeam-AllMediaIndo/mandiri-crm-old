<?php
    require_once('setting.php');
    if(isset($_GET["table1"])){
        if($_GET["table1"] == "true"){
            $dt->query('
                SELECT
                    tb_racc.ACC_DATETIME,
                    tb_racc.ACC_F_APP_PRIBADI_NAMA,
                    tb_racc.ACC_F_APP_PRIBADI_ID,
                    DATE(tb_racc.ACC_F_APP_PRIBADI_TGLLHR) AS TGL,
                    tb_member.MBR_EMAIL,
                    tb_racc.ACC_LOGIN,
                    IF(tb_apuppt_evcannas.EVCAN_CONF = 0, "Ditolak",
                        IF(tb_apuppt_evcannas.EVCAN_CONF = 1, "Dipertimbangkan",
                            IF(tb_apuppt_evcannas.EVCAN_CONF = 2, "Dilanjutkan", NULL)
                        )
                    ) AS EVCAN_CONF,
                    (
                        SELECT
                            (
                                SELECT
                                    CONCAT(tb_range.RNG_LEVEL, "(", SUM(tb_rangensb.NSBR_VAL * tb_rangetype.RATYP_BBR), ")")
                                FROM tb_range
                                WHERE tb_range.RNG_TYPE = 2 
                                AND SUM(tb_rangensb.NSBR_VAL * tb_rangetype.RATYP_BBR) >= tb_range.RNG_MIN 
                                AND SUM(tb_rangensb.NSBR_VAL * tb_rangetype.RATYP_BBR) <= CAST(CASE WHEN tb_range.RNG_MAX = -1 THEN ~0 ELSE tb_range.RNG_MAX END AS UNSIGNED)
                                LIMIT 1
                            )
                        FROM tb_apuppt
                        JOIN tb_rangensb ON(tb_rangensb.ID_NSBR = tb_apuppt.APU_RNGNSB1
                        OR tb_rangensb.ID_NSBR = tb_apuppt.APU_RNGNSB2
                        OR tb_rangensb.ID_NSBR = tb_apuppt.APU_RNGNSB3
                        OR tb_rangensb.ID_NSBR = tb_apuppt.APU_RNGNSB4
                        OR tb_rangensb.ID_NSBR = tb_apuppt.APU_RNGNSB5
                        OR tb_rangensb.ID_NSBR = tb_apuppt.APU_RNGNSB6
                        OR tb_rangensb.ID_NSBR = tb_apuppt.APU_RNGNSB7
                        OR tb_rangensb.ID_NSBR = tb_apuppt.APU_RNGNSB8
                        OR tb_rangensb.ID_NSBR = tb_apuppt.APU_RNGNSB9)		
                        JOIN tb_rangetype ON(tb_rangetype.ID_RATYP = tb_rangensb.NSBR_TYPE)
                        WHERE tb_apuppt.APU_MBR = tb_racc.ACC_MBR AND tb_apuppt.APU_ACC = tb_racc.ID_ACC
                    ) AS RESK,
                    IFNULL(
                        (
                            SELECT
                                MD5(MD5(MD5(MD5(MD5(tb_apuppt.ID_APU)))))
                            FROM tb_apuppt
                            WHERE tb_apuppt.APU_MBR = tb_racc.ACC_MBR AND tb_apuppt.APU_ACC = tb_racc.ID_ACC
                        ), MD5(MD5(MD5(MD5(tb_racc.ID_ACC))))
                    ) AS ID_ACC
                FROM tb_racc
                JOIN tb_member
                JOIN tb_apuppt_evcannas
                ON(tb_racc.ACC_MBR = tb_member.MBR_ID
                AND tb_racc.ACC_MBR = tb_apuppt_evcannas.EVCAN_MBR)
                WHERE tb_racc.ACC_DERE = 1
                AND tb_racc.ACC_LOGIN = "0"
                AND tb_racc.ACC_WPCHECK < 6
                AND tb_racc.ACC_F_APP_PERYT IS NOT NULL
            ');
            $dt->edit('EVCAN_CONF', function($data){
                $ARR_CLR = [
                    "Ditolak"         => "danger",
                    "Dipertimbangkan" => "warning",
                    "Dilanjutkan"     => "success",
                    "Unknown"         => "secondary"
                ];
                return '
                    <div class="text-center">
                        <span class="badge bg-'.$ARR_CLR[$data["EVCAN_CONF"]].' h-50 d-inline-block bg-opacity-15 text-white" style="font-size: 12px;">'.$data["EVCAN_CONF"].'</span>
                    </div>
                ';
            });
            $dt->edit('RESK', function($data){
                if(!is_null($data["RESK"])){
                    $clr = ((strpos($data["RESK"], "Rendah") !== FALSE) ? 'success' : ((strpos($data["RESK"], "Menengah") !== FALSE) ? 'warning' : ((strpos($data["RESK"], "Tinggi") !== FALSE) ? 'danger' : 'secondary')));
                    return '
                        <div class="text-center">
                            <span class="badge bg-'.$clr.' h-50 d-inline-block bg-opacity-15 text-white" style="font-size: 12px;">'.$data["RESK"].'</span>
                        </div>
                    ';
                }
            });
            $dt->edit('ID_ACC', function($data){
                return '
                    <div class="text-center">
                        <a href="home.php?page=apu_evnasdtl&x='.$data["ID_ACC"].'" class="btn btn-sm btn-primary">Detail</a>
                    </div>
                ';
            });
            echo $dt->generate()->toJson();
        }else{
            $dt->query('
                SELECT
                    IFNULL(tb_apuppt.APU_DATETIME, tb_racc.ACC_F_PROFILE_DATE) AS TGL,
                    tb_racc.ACC_F_APP_PRIBADI_NAMA,
                        tb_racc.ACC_F_APP_PRIBADI_ID,
                        tb_racc.ACC_F_APP_PRIBADI_TGLLHR,
                    LOWER(tb_member.MBR_EMAIL) AS MBR_EMAIL,
                    tb_racc.ACC_LOGIN,
                    IF(tb_racc.ACC_LOGIN = 0 AND tb_racc.ACC_WPCHECK = 0, "REGISTER",
                        IF(tb_racc.ACC_LOGIN = 0 AND tb_racc.ACC_WPCHECK = 1, "Verified",
                            IF(tb_racc.ACC_LOGIN = 0 AND tb_racc.ACC_WPCHECK = 2, "Deposit New Account",
                                IF(tb_racc.ACC_LOGIN = 0 AND tb_racc.ACC_WPCHECK = 3, "Waiting Depo",
                                    IF(tb_racc.ACC_LOGIN = 0 AND tb_racc.ACC_WPCHECK = 4, "Waiting Depo.",
                                        IF(tb_racc.ACC_LOGIN = 0 AND tb_racc.ACC_WPCHECK = 5, "GoodFund",
                                            IF(tb_racc.ACC_LOGIN <> 0 AND tb_racc.ACC_WPCHECK = 6, "Active", "Unknown")
                                        )
                                    )
                                )
                            )
                        )
                    ) AS ACC_STATUS,
                    (
                        SELECT
                            (
                                SELECT
                                    CONCAT(tb_range.RNG_LEVEL, "(",SUM(tb_rangensb.NSBR_VAL * tb_rangetype.RATYP_BBR),")")
                                FROM tb_range
                                WHERE tb_range.RNG_TYPE = 2 
                                AND SUM(tb_rangensb.NSBR_VAL * tb_rangetype.RATYP_BBR) >= tb_range.RNG_MIN 
                                AND SUM(tb_rangensb.NSBR_VAL * tb_rangetype.RATYP_BBR) <= CAST(CASE WHEN tb_range.RNG_MAX = -1 THEN ~0 ELSE tb_range.RNG_MAX END AS UNSIGNED)
                            )
                        FROM tb_rangensb
                        JOIN tb_rangetype ON(tb_rangetype.ID_RATYP = tb_rangensb.NSBR_TYPE)
                        WHERE (tb_apuppt.APU_RNGNSB1 = tb_rangensb.ID_NSBR
                        OR tb_apuppt.APU_RNGNSB2 = tb_rangensb.ID_NSBR
                        OR tb_apuppt.APU_RNGNSB3 = tb_rangensb.ID_NSBR
                        OR tb_apuppt.APU_RNGNSB4 = tb_rangensb.ID_NSBR
                        OR tb_apuppt.APU_RNGNSB5 = tb_rangensb.ID_NSBR
                        OR tb_apuppt.APU_RNGNSB6 = tb_rangensb.ID_NSBR
                        OR tb_apuppt.APU_RNGNSB7 = tb_rangensb.ID_NSBR
                        OR tb_apuppt.APU_RNGNSB8 = tb_rangensb.ID_NSBR
                        OR tb_apuppt.APU_RNGNSB9 = tb_rangensb.ID_NSBR)
                    ) AS RESK,
                    IFNULL(
                        MD5(MD5(MD5(MD5(tb_apuppt.ID_APU)))), MD5(MD5(tb_racc.ID_ACC))
                    ) AS MRX
                FROM tb_racc
                JOIN tb_member 
                ON (tb_racc.ACC_MBR = tb_member.MBR_ID)
                LEFT JOIN tb_apuppt ON(tb_apuppt.APU_MBR = tb_member.MBR_ID AND tb_apuppt.APU_ACC = tb_racc.ID_ACC)
                WHERE tb_racc.ACC_DERE = 1
                AND tb_racc.ACC_F_DISC = 1
            ');
            $dt->edit('ACC_STATUS', function($data){
                $ARR_CLR = [
                    "REGISTER"            => "#28a745",
                    "Verified"            => "#184421",
                    "Deposit New Account" => "#007bff",
                    "Waiting Depo"        => "purple",
                    "Waiting Depo"        => "purple",
                    "Waiting Depo."       => "purple",
                    "GoodFund"            => "#17a2b8",
                    "Active"              => "#ffc107",
                    "Unknown"             => "orange"
                ];
                return '
                    <div class="text-center">
                        <span class="badge h-50 d-inline-block bg-opacity-15 text-white" style="font-size: 12px; background-color: '.$ARR_CLR[$data["ACC_STATUS"]].';">'.ucfirst(strtolower($data['ACC_STATUS'])).'</span>
                    </div>
                ';
            });
            $dt->edit('RESK', function($data){
                if(!is_null($data["RESK"])){
                    $clr = ((strpos($data["RESK"], "Rendah") !== FALSE) ? 'success' : ((strpos($data["RESK"], "Menengah") !== FALSE) ? 'warning' : ((strpos($data["RESK"], "Tinggi") !== FALSE) ? 'danger' : 'secondary')));
                    return '
                        <div class="text-center">
                            <span class="badge bg-'.$clr.' h-50 d-inline-block bg-opacity-15 text-white" style="font-size: 12px;">'.$data["RESK"].'</span>
                        </div>
                    ';
                }
            });
            $dt->edit('MRX', function($data){
                return '
                    <div>
                        <a href="home.php?page=apu_evnasdtl&x='.$data["MRX"].'" class="btn btn-sm btn-primary">Detail</a>
                    </div>
                ';
            });
            echo $dt->generate()->toJson();
        }
    }