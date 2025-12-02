<?php
    include_once('setting.php');
    if(!isset($_GET["scndt"])){
        $adm = (isset($_GET["adm"])) ? $_GET["adm"] : 0;
        $dt->query('
            SELECT
                tb_member.MBR_DATETIME,
                tb_member.MBR_NAME,
                tb_member.MBR_NO_IDT,
                DATE(tb_member.MBR_TGLLAHIR) AS MBR_TGLLAHIR,
                tb_member.MBR_EMAIL,
                MD5(MD5(tb_member.MBR_ID)) AS MBR_ID,
                (
                    SELECT
                        tb_admin.ADM_LEVEL
                    FROM tb_admin
                    WHERE MD5(MD5(ADM_ID)) = "'.$adm.'"
                    LIMIT 1
                ) AS ADM_LVL
            FROM tb_member
            JOIN tb_schedule
            ON(tb_member.MBR_ID = tb_schedule.SCHD_ID)
            WHERE NOT EXISTS(SELECT 1 FROM tb_apuppt_evcannas WHERE tb_apuppt_evcannas.EVCAN_MBR = tb_member.MBR_ID)
            #AND NOT EXISTS(SELECT 1 FROM tb_racc WHERE tb_racc.ACC_DERE = 1  AND tb_racc.ACC_MBR = tb_schedule.SCHD_ID)
            AND tb_member.MBR_NO_IDT IS NOT NULL
            AND tb_schedule.SCHD_STS = -1
            AND tb_member.MBR_EMAIL NOT LIKE "%_ori"
            GROUP BY tb_schedule.SCHD_ID 
        ');
        $dt->hide('ADM_LVL');
        $dt->edit('MBR_ID', function($data){
            if(in_array($data["ADM_LVL"], [1, 8])){
                return '
                    <div class="text-center">
                        <a class="btn btn-sm btn-info" href="home.php?page=apu_evcannasdtl&x='.$data["MBR_ID"].'">Detail</a>
                    </div>
                ';
            }
        });
        echo $dt->generate()->toJson();
    }else{
        $dt->query('
            SELECT
                tb_apuppt_evcannas.EVCAN_DATETIME,
                tb_member.MBR_NAME,
                tb_member.MBR_NO_IDT,
                DATE(tb_member.MBR_TGLLAHIR) AS MBR_TGLLAHIR,
                tb_member.MBR_EMAIL,
                IF(tb_apuppt_evcannas.EVCAN_CONF = 0, "Ditolak",
                    IF(tb_apuppt_evcannas.EVCAN_CONF = 1, "Dipertimbangkan",
                        IF(tb_apuppt_evcannas.EVCAN_CONF = 2, "Dilanjutkan", "Unknown")
                    )
                ) AS EVCAN_CONF,
                MD5(MD5(MD5(tb_apuppt_evcannas.ID_EVCAN))) AS ID_EVCAN
            FROM tb_member
            JOIN tb_apuppt_evcannas
            ON(tb_member.MBR_ID = tb_apuppt_evcannas.EVCAN_MBR)
        ');
        $dt->edit('ID_EVCAN', function($data){
            return '
                <div class="text-center">
                    <a class="btn btn-sm btn-info" href="home.php?page=apu_evcannasdtl&x='.$data["ID_EVCAN"].'">Detail</a>
                </div>
            ';
        });
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
        echo $dt->generate()->toJson();
    }
