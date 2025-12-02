<?php
    require_once('setting.php');
    $usr = (isset($_GET["usr"])) ? $_GET["usr"] : 0;

    //$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
    $dt->query('
        SELECT
            IFNULL((
                SELECT
                    tb_acccond.ACCCND_DATEMARGIN
                FROM tb_acccond
                WHERE tb_acccond.ACCCND_ACC = tb_racc.ID_ACC
                LIMIT 1
            ), IF(DATE(ACC_F_PROFILE_DATE) > DATE("2023-02-06"),(tb_racc.ACC_DATETIME + INTERVAL 7 HOUR), tb_racc.ACC_DATETIME)) AS ACC_F_PROFILE_DATE,
            tb_racc.ACC_LOGIN,
            tb_racc.ACC_F_APP_PRIBADI_NAMA AS MBR_NAME,
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
                                    IF(tb_racc.ACC_LOGIN <> 0 AND tb_racc.ACC_WPCHECK = 6, "Active", "Unknown")
                                )
                            )
                        )
                    )
                )
            ) AS ACC_STATUS,
            MD5(MD5(tb_racc.ID_ACC)) AS ID_ACC,
            MD5(MD5(tb_racc.ID_ACC)) AS EDIT_ACC,
            IFNULL(
                (
                    SELECT 
                        tb_admin.ADM_LEVEL 
                    FROM tb_admin 
                    WHERE MD5(MD5(tb_admin.ADM_ID)) = "'.$usr.'" 
                    LIMIT 1
                )
            ,0) AS ADM_LEVEL
        FROM tb_racc
        JOIN tb_member
        ON (tb_racc.ACC_MBR = tb_member.MBR_ID)
        WHERE tb_racc.ACC_DERE = 1
        AND ACC_F_DISC = 1
        AND tb_racc.ACC_LOGIN <> 0 
        AND tb_racc.ACC_WPCHECK = 6
    ');
    $dt->hide('ADM_LEVEL');
    $dt->edit('ID_ACC', function($data){
        if($data['ACC_STATUS'] == 'REGISTER'){
            return "
                <div class='text-center'>
                    <a href='home.php?page=member_realacc_detail&x=".$data['ID_ACC']."&sub_page=wp_verification1' class='btn btn-sm btn-info'>Detail</a>
                </div>
            ";
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
            if($data['ACC_F_PROFILE_DATE'] < '2023-03-16 00:00:00'){
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
    });
    $dt->edit('ACC_F_PROFILE_DATE', function($data){ return "<div class='text-center'>".$data['ACC_F_PROFILE_DATE']."</div>"; });
    $dt->edit('ACC_TYPE', function($data){ return "<div class='text-center'>".$data['ACC_TYPE']."</div>"; });
    $dt->edit('ACC_RATE', function($data){ return "<div class='text-right'>".$data['ACC_RATE']."</div>"; });
    $dt->edit('EDIT_ACC', function($data){ 
        if($data["ADM_LEVEL"] != 0){
            return "<div class='text-center'><a href='home.php?page=member_realacc_active_edit&id=".$data['EDIT_ACC']."' class='btn btn-sm btn-primary'><i class='fa fa-pencil' aria-hidden='true'></i> Edit</a></div>"; 
        }
    });
    $dt->edit('ACC_STATUS', function($data){ 
        if($data['ACC_STATUS'] == 'REGISTER'){
            return "
                <div class='text-center'>
                    <span class='badge bg-success h-50 d-inline-block bg-opacity-15 text-white' style='font-size: 12px;'>Register</span>
                </div>
            ";
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
            if($data['ACC_F_PROFILE_DATE'] < '2023-02-06 00:00:00'){
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