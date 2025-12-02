<?php
    require_once('setting.php');
    $usr = (isset($_GET["usr"])) ? $_GET["usr"]: 0;
    //$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
    $dt->query('
        SELECT
            tb_member.MBR_DATETIME,
            tb_member.MBR_ID,
            tb_member.MBR_NAME,
            tb_member.MBR_EMAIL,
            IF(tb_member.MBR_STS = -1, "Active", 
                IF(tb_member.MBR_STS = 0, "Email Not Confirm",
                    IF(tb_member.MBR_STS = 1, "Blocked", "Unknwon")
                )
            ) AS MBR_STS,
            MD5(MD5(tb_member.MBR_ID)) AS MBR_ID_HASH,
            MD5(MD5(tb_member.MBR_ID)) AS EDIT,
            IFNULL(
                (
                    SELECT 
                        tb_admin.ADM_LEVEL 
                    FROM tb_admin 
                    WHERE MD5(MD5(tb_admin.ADM_ID)) = "'.$usr.'" 
                    LIMIT 1
                )
            ,0) AS ADM_LEVEL
        FROM tb_member
    ');
    $dt->hide('ADM_LEVEL');
    $dt->edit('MBR_DATETIME', function($data){
        return "<div class='text-center'>".$data['MBR_DATETIME']."</div>";
    });
    $dt->edit('MBR_ID_HASH', function($data){
        return "<div class='text-center'><a href='home.php?page=member_user_detail&x=".$data['MBR_ID_HASH']."' class='btn btn-sm btn-info'>Detail</a></div>";
    });
    $dt->edit('EDIT', function($data){
        if($data["ADM_LEVEL"] != 0){
            return "<div class='text-center'><a href='home.php?page=member_user_edit&x=".$data['EDIT']."' class='btn btn-sm btn-primary'>Edit <i class='fa fa-pencil' aria-hidden='true'></a></div>";
        }
    });

    $dt->edit('MBR_STS', function($data){
        if($data['MBR_STS'] == 'Active'){
            return "
                <div class='text-center'>
                    <span class='badge bg-success h-50 d-inline-block bg-opacity-15 text-white' style='font-size: 12px;'>Active</span>
                </div>
            ";
        }  else if($data['MBR_STS'] == 'Email Not Confirm') {
            return "
                <div class='text-center'>
                    <span class='badge bg-warning h-50 d-inline-block bg-opacity-15 text-white' style='font-size: 12px;'>Email Not Confirm</span>
                </div>
            ";
        } else if($data['MBR_STS'] == 'Blocked'){
            return "
                <div class='text-center'>
                    <span class='badge bg-danger h-50 d-inline-block bg-opacity-15 text-white' style='font-size: 12px;'>Blocked</span>
                </div>
            ";
        } else {
            return "
                <div class='text-center'>
                    <span class='badge bg-secondary h-50 d-inline-block bg-opacity-15 text-white' style='font-size: 12px;'>Unknwon</span>
                </div>
            ";
        }
    });

    echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';