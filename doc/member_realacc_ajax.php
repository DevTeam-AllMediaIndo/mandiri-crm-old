<?php
    require_once('setting.php');
    $usr = (isset($_GET["usr"])) ? $_GET["usr"] : 0;
    //$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
    $dt->query('
        SELECT
            IFNULL(tb_racc.ACC_F_KODE_DATE, tb_racc.ACC_F_PROFILE_DATE) AS ACC_F_PROFILE_DATE,
            tb_racc.ACC_LOGIN,
            tb_racc.ACC_F_APP_PRIBADI_NAMA,
            LOWER(tb_member.MBR_EMAIL) AS MBR_EMAIL,
            IF(tb_racc.ACC_TYPE = 1, "SPA", "Multilateral") AS ACC_TYPE,
            tb_racc.ACC_PRODUCT,
            tb_racc.ACC_RATE,
            IFNULL((
                SELECT CONCAT(tb_ib.IB_NAME, " (", tb_ib.IB_CODE, ")")
                FROM tb_ib
                JOIN tb_acccond
                ON(tb_ib.IB_ID = tb_acccond.ACCCND_IB)
                WHERE tb_acccond.ACCCND_ACC = tb_racc.ID_ACC
                ORDER BY tb_acccond.ID_ACCCND DESC
                LIMIT 1
            ), "0") AS IB_CODENAME,
            IF(tb_racc.ACC_LOGIN = 0 AND tb_racc.ACC_WPCHECK = 0, "REGISTER",
                IF(tb_racc.ACC_LOGIN = 0 AND tb_racc.ACC_WPCHECK = 1, "Verified",
                    IF(tb_racc.ACC_LOGIN = 0 AND tb_racc.ACC_WPCHECK = 2, "Deposit New Account",
                        IF(tb_racc.ACC_LOGIN = 0 AND tb_racc.ACC_WPCHECK = 3, "Waiting Depo",
                            IF(tb_racc.ACC_LOGIN = 0 AND tb_racc.ACC_WPCHECK = 4, "Waiting Depo.",
                                IF(tb_racc.ACC_LOGIN = 0 AND tb_racc.ACC_WPCHECK = 5, "GoodFund",
                                    IF(tb_racc.ACC_LOGIN <> 0 AND tb_racc.ACC_WPCHECK = 6, "Active", 
                                        IF(tb_racc.ACC_LOGIN = 0 AND tb_racc.ACC_WPCHECK = -5, "Pending", "Unknown")
                                    )
                                )
                            )
                        )
                    )
                )
            ) AS ACC_STATUS,
            MD5(MD5(tb_racc.ID_ACC)) AS ID_ACC,
            IFNULL(
                (
                    SELECT 
                        tb_admin.ADM_LEVEL 
                    FROM tb_admin 
                    WHERE MD5(MD5(tb_admin.ADM_ID)) = "'.$usr.'" 
                    LIMIT 1
                )
            ,0) AS ADM_LEVEL,
            (
	            SELECT
	                tb_apuppt_evcannas.EVCAN_CONF
	            FROM tb_apuppt_evcannas
	            WHERE tb_apuppt_evcannas.EVCAN_MBR = tb_member.MBR_ID
	            LIMIT 1
            ) AS EVCNSSTS,
            IFNULL(
                (
                    SELECT
                        MD5(MD5(MD5(tb_apuppt_evcannas.ID_EVCAN))) AS ID_EVCAN
                    FROM tb_apuppt_evcannas
                    WHERE tb_apuppt_evcannas.EVCAN_MBR = tb_member.MBR_ID
                    LIMIT 1
                ), MD5(MD5(tb_racc.ACC_MBR))
            ) AS EVCNS
        FROM tb_racc
        JOIN tb_member
        ON (tb_racc.ACC_MBR = tb_member.MBR_ID)
        WHERE tb_racc.ACC_DERE = 1
        #AND ACC_F_DISC = 1
        AND ACC_F_KODE = 1
        AND tb_racc.ACC_LOGIN = 0
    ');
    $dt->hide('ADM_LEVEL');
    $dt->hide('EVCNS');
    $dt->hide('EVCNSSTS');
    $dt->edit('ID_ACC', function($data){
        if($data["ADM_LEVEL"] != 0){
            if($data['ACC_STATUS'] == 'REGISTER'){
                if($data["EVCNSSTS"] == 2){
                    return "
                        <div class='text-center'>
                            <a href='home.php?page=member_realacc_detail&x=".$data['ID_ACC']."&sub_page=wp_verification1' class='btn btn-sm btn-info'>Detail</a>
                        </div>
                    ";
                }else{
                    if(!in_array($data["EVCNSSTS"], [0, 1])){
                        if(in_array($data["ADM_LEVEL"], [1])){
                            return '
                                <div class="text-center">
                                    <a class="btn btn-sm btn-info" href="home.php?page=apu_evcannasdtl&x='.$data["EVCNS"].'">Detail</a>
                                </div>
                            ';
                        }
                    }else{
                        if(in_array($data["ADM_LEVEL"], [1, 8])){
                            return '
                                <div class="text-center">
                                    <a class="btn btn-sm btn-info" href="home.php?page=apu_evcannasdtl&x='.$data["EVCNS"].'&xacc='.$data['ID_ACC'].'">Detail</a>
                                </div>
                            ';
                        }
                    }
                }
            } else if($data['ACC_STATUS'] == 'Deposit New Account'){
                return "
                    <div class='text-center'>
                        <a href='home.php?page=member_realacc_detail&x=".$data['ID_ACC']."&sub_page=client_deposit1' class='btn btn-sm btn-info'>Detail</a>
                    </div>
                ";
            } else if($data['ACC_STATUS'] == 'Waiting Depo'){
                return "
                    <div class='text-center'>
                        <a href='home.php?page=member_realacc_detail&x=".$data['ID_ACC']."&sub_page=wp_check1' class='btn btn-sm btn-info'>Detail</a>
                    </div>
                ";
            } else if($data['ACC_STATUS'] == 'Waiting Depo.'){
                return "
                    <div class='text-center'>
                        <a href='home.php?page=member_realacc_detail&x=".$data['ID_ACC']."&sub_page=accounting1' class='btn btn-sm btn-info'>Detail</a>
                    </div>
                ";
            } else if($data['ACC_STATUS'] == 'GoodFund'){
                return "
                    <div class='text-center'>
                        <a href='home.php?page=member_realacc_detail&x=".$data['ID_ACC']."&sub_page=dealer1' class='btn btn-sm btn-info'>Detail</a>
                    </div>
                ";
            } else if($data['ACC_STATUS'] == 'Active'){
                if(strtotime($data['ACC_F_PROFILE_DATE']) < strtotime('2023-03-16 00:00:00')){
                    return "
                        <div class='text-center'>
                            <a href='home.php?page=member_realacc_detail&x=".$data['ID_ACC']."&sub_page=document1' class='btn btn-sm btn-info'>Detail</a>
                        </div>
                    ";
                } else {
                    return "
                        <div class='text-center'>
                            <a href='home.php?page=member_realacc_detail&x=".$data['ID_ACC']."&sub_page=document' class='btn btn-sm btn-info'>Detail</a>
                        </div>
                    ";
                }
            } else { 
                return "
                    <div class='text-center'>
                        <a href='home.php?page=member_realacc_detail&x=".$data['ID_ACC']."&sub_page=temporary_detail' class='btn btn-sm btn-info'>Detail</a>
                    </div>
                "; 
            }
        }
    });
    $dt->edit('ACC_F_PROFILE_DATE', function($data){ return "<div class='text-center'>".$data['ACC_F_PROFILE_DATE']."</div>"; });
    $dt->edit('ACC_TYPE', function($data){ return "<div class='text-center'>".$data['ACC_TYPE']."</div>"; });
    $dt->edit('ACC_RATE', function($data){ return "<div class='text-right'>".$data['ACC_RATE']."</div>"; });
    $dt->edit('ACC_STATUS', function($data){ 
        if($data['ACC_STATUS'] == 'Pending'){
            return "
                <div class='text-center'>
                    <span class='badge bg-warning h-50 d-inline-block bg-opacity-15 text-dark' style='font-size: 12px;'>Pending</span>
                </div>
            ";
        } else if($data['ACC_STATUS'] == 'REGISTER'){
            if($data["EVCNSSTS"] == 2){
                return "
                    <div class='text-center'>
                        <span class='badge bg-success h-50 d-inline-block bg-opacity-15 text-white' style='font-size: 12px;'>Dilanjutkan</span>
                    </div>
                ";
            }else{
                $ARR_CLR  = ["danger", "warning", "success", "success"];
                $ARR_CONF = ["Ditolak", "Dipertimbangkan", "Dilanjutkan", "Register"];
                $number   = (empty($data["EVCNSSTS"])) ? 3 : $data["EVCNSSTS"];
                return "
                    <div class='text-center'>
                        <span class='badge bg-".$ARR_CLR[$number]." h-50 d-inline-block bg-opacity-15 text-white' style='font-size: 12px;'>".$ARR_CONF[$number]."</span>
                    </div>
                ";
            }
        } else if($data['ACC_STATUS'] == 'Deposit New Account'){
            return "
                <div class='text-center'>
                    <span class='badge bg-primary h-50 d-inline-block bg-opacity-15 text-white' style='font-size: 12px;'>".(($data['ACC_STATUS']))."</span>
                </div>
            ";
        } else if($data['ACC_STATUS'] == 'Waiting Depo'){
            return "
                <div class='text-center'>
                    <span class='badge h-50 d-inline-block bg-opacity-15 text-white' style='font-size: 12px; background-color: purple;'>".(($data['ACC_STATUS']))."</span>
                </div>
            ";
        } else if($data['ACC_STATUS'] == 'Waiting Depo.'){
            return "
                <div class='text-center'>
                    <span class='badge bg-secondary h-50 d-inline-block bg-opacity-15 text-white' style='font-size: 12px;'>Waiting Finance</span>
                </div>
            ";
        } else if($data['ACC_STATUS'] == 'GoodFund'){
            return "
                <div class='text-center'>
                    <span class='badge bg-info h-50 d-inline-block bg-opacity-15 text-white' style='font-size: 12px;'>".(($data['ACC_STATUS']))."</span>
                </div>
            ";
        } else if($data['ACC_STATUS'] == 'Active'){
            if($data['ACC_F_PROFILE_DATE'] < '2023-03-16 00:00:00'){
                return "
                    <div class='text-center'>
                        <span class='badge h-50 d-inline-block bg-opacity-15 text-white' style='font-size: 12px; background: orange ;'>".(($data['ACC_STATUS']))."</span>
                    </div>
                ";
            } else {
                return "
                    <div class='text-center'>
                        <span class='badge bg-warning h-50 d-inline-block bg-opacity-15 text-white' style='font-size: 12px;'>".(($data['ACC_STATUS']))."</span>
                    </div>
                ";
            }
        } else { 
            return "
                <div class='text-center'>
                    <span class='badge h-50 d-inline-block bg-opacity-15 text-white' style='font-size: 12px; background-color: #184421;'>".(($data['ACC_STATUS']))."</span>
                </div>
            "; }
        return "<div class='text-center'>".$data['ACC_STATUS']."</div>";
    });

    echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';