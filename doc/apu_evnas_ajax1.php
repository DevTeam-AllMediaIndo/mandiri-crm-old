<?php
    require_once('setting.php');
    $dt->query('
        SELECT
            tb_apuppt.APU_DATETIME,
            tb_member.MBR_NAME,
            tb_member.MBR_EMAIL,
            tb_racc.ACC_LOGIN,
            (
                SELECT
                    SUM(tb_rangensb.NSBR_VAL * tb_rangetype.RATYP_BBR)
                FROM tb_rangensb
                JOIN tb_rangetype ON(tb_rangetype.ID_RATYP = tb_rangensb.NSBR_TYPE)
                JOIN tb_apuppt tb_1
                WHERE (tb_1.APU_RNGNSB1 = tb_rangensb.ID_NSBR
                    OR tb_1.APU_RNGNSB2 = tb_rangensb.ID_NSBR
                    OR tb_1.APU_RNGNSB3 = tb_rangensb.ID_NSBR
                    OR tb_1.APU_RNGNSB4 = tb_rangensb.ID_NSBR
                    OR tb_1.APU_RNGNSB5 = tb_rangensb.ID_NSBR
                    OR tb_1.APU_RNGNSB6 = tb_rangensb.ID_NSBR
                    OR tb_1.APU_RNGNSB7 = tb_rangensb.ID_NSBR
                    OR tb_1.APU_RNGNSB8 = tb_rangensb.ID_NSBR
                    OR tb_1.APU_RNGNSB9 = tb_rangensb.ID_NSBR
                )
                AND tb_1.ID_APU = MAX(tb_apuppt.ID_APU)
                ORDER BY tb_1.ID_APU DESC
                LIMIT 1
            ) AS APUC,
            MD5(MD5(MAX(tb_apuppt.ID_APU))) AS ID_APU
        FROM tb_apuppt
        JOIN tb_racc ON(tb_apuppt.APU_MBR = tb_racc.ACC_MBR)
        JOIN tb_member ON(tb_apuppt.APU_MBR = tb_member.MBR_ID)
        WHERE tb_racc.ACC_DERE = 1
        GROUP BY  tb_apuppt.APU_ACC
        ORDER BY tb_apuppt.ID_APU DESC
    ');
    $dt->edit('APU_DATETIME', function($data){
        return '<div class="text-center">'.$data["APU_DATETIME"].'</div>';
    });
    $dt->edit('APUC', function($data){
        return '<div class="text-center apuc" data-val="'.$data["APUC"].'">'.$data["APUC"].'</div>';
    });
    $dt->edit('ID_APU', function($data){
        return '
            <div class="text-center">
                <a class="btn btn-md btn-info" href="home.php?page=apu_evnasdtl&x2='.$data["ID_APU"].'">Detail</a>
            </div>
        ';
    });
    echo $dt-> generate()->toJson();