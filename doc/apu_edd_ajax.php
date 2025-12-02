<?php
    include_once('setting.php');
    include_once('../setting.php');
    $dt->query('
        SELECT
            tb_racc.ACC_DATETIME,
            tb_racc.ACC_F_APP_PRIBADI_NAMA,
            tb_racc.ACC_F_APP_PRIBADI_ID,
            JSON_ARRAYAGG(tb_racc.ACC_MBR) AS ACC_MBR,
            JSON_ARRAYAGG(tb_racc.ACC_LOGIN) AS ACC_LOGIN,
            MD5(MD5(tb_racc.ID_ACC)) AS ID_ACC
        FROM tb_racc
        WHERE tb_racc.ACC_DERE = 1 
        AND tb_racc.ACC_F_APP_PRIBADI_ID IS NOT NULL
        AND (tb_racc.ACC_LOGIN != "0" AND tb_racc.ACC_LOGIN IS NOT NULL)
        GROUP BY tb_racc.ACC_F_APP_PRIBADI_ID
    ');

    $dt->edit('ID_ACC', function($data){
        return '
            <div class="text-center" style="display: flex;">
                <a href="home.php?page=apu_eddadtl&x='.$data["ID_ACC"].'" class="btn btn-sm btn-primary">Detail</a>&nbsp;
                <a href="home.php?page=apu_eddhstry&x='.$data["ID_ACC"].'" class="btn btn-sm btn-success">History</a>
            </div>
        ';
    });

    $dt->edit('ACC_LOGIN', function($data){
        return '
            <div class="text-center">('.preg_replace('/^\s+|\s+$/', '', preg_replace('/[^\d\n,.]/', ' ', $data["ACC_LOGIN"])).')</div>
        ';
    });

    $dt->edit('ACC_MBR', function($data){
        global $db;
        $str = [];
        $num = 0;
        $SQL_QUER = mysqli_query($db,'
            SELECT
                tb_member.MBR_EMAIL
            FROM tb_member
            WHERE tb_member.MBR_ID IN('.implode(",", json_decode($data["ACC_MBR"], true)).')
        ')or die(mysqli_error($db));
        $num = mysqli_num_rows($SQL_QUER);
        if($SQL_QUER && mysqli_num_rows($SQL_QUER) > 0){
            while($RSLT_QUER = mysqli_fetch_assoc($SQL_QUER)){
                if(!in_array($RSLT_QUER["MBR_EMAIL"], $str)){
                    $str[] = $RSLT_QUER["MBR_EMAIL"];
                }
            }

        }
        // return '
        //     <div class="text-center mailv">'.preg_replace('/[^\w,-]/', '', $data["ACC_MBR"]).'</div>
        // ';
        return '
            <div class="text-center">
                '.implode(", ", $str).'
            </div>
        ';
    });

    echo $dt->generate()->toJson();