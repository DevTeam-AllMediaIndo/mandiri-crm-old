<?php
    require_once('setting.php');
    //$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
    $dt->query('
        SELECT
            tb_schedule.SCHD_DATETIME,
            tb_member.MBR_NAME,
            tb_member.MBR_PHONE,
            tb_member.MBR_EMAIL,
            DATE(tb_member.MBR_TGLLAHIR) AS MBR_TGLLAHIR,
            tb_schedule.SCHD_TANGGAL,
            tb_schedule.SCHD_JAM,
            IF(tb_schedule.SCHD_STS = -1, "Meet",
                IF(tb_schedule.SCHD_STS = 1, "Reject", "Unknown")
            ) AS SCHD_STS,
            tb_schedule.SCHD_REASON,
            tb_schedule.ID_SCHD
        FROM tb_schedule
        JOIN tb_member
        ON (tb_member.MBR_ID = tb_schedule.SCHD_ID)
        WHERE tb_schedule.SCHD_STS<>0

        UNION ALL
        
        SELECT
            tb_schedule1.SCHD_DATETIME,
            tb_member.MBR_NAME,
            tb_member.MBR_PHONE,
            tb_member.MBR_EMAIL,
            DATE(tb_member.MBR_TGLLAHIR) AS MBR_TGLLAHIR,
            tb_schedule1.SCHD_TANGGAL,
            tb_schedule1.SCHD_JAM,
            IF(tb_schedule1.SCHD_STS = -1, "Meet",
                IF(tb_schedule1.SCHD_STS = 1, "Reject.", "Unknown")
            ) AS SCHD_STS,
            tb_schedule1.SCHD_REASON,
            tb_schedule1.ID_SCHD
        FROM tb_schedule1
        JOIN tb_member
        ON (tb_member.MBR_ID = tb_schedule1.SCHD_ID)
        WHERE tb_schedule1.SCHD_STS<>0
    ');
    $dt->edit('ID_SCHD', function($data){
        // if($data['SCHD_STS'] == 'Meet'){
            return "<div class='text-center'><a target='_blank' href='pdf/root/doc_g1_detail.php?x=".MD5(MD5($data['ID_SCHD']))."' class='btn btn-sm btn-info'>Detail</a></div>";
        // }
    });
    $dt->edit('SCHD_DATETIME', function($data){
        return "<div class='text-center'>".(($data['SCHD_DATETIME']))."</div>";
    });
    $dt->edit('SCHD_STS', function($data){
        if($data['SCHD_STS'] == 'Meet'){
            return "
                <div class='text-center'>
                    <span class='badge bg-success h-50 d-inline-block bg-opacity-15 text-white' style='font-size: 12px;'>".(($data['SCHD_STS']))."</span>
                </div>
            ";
        } else {
            return "
                <div class='text-center'>
                    <span class='badge bg-danger h-50 d-inline-block bg-opacity-15 text-white' style='font-size: 12px;'>".(($data['SCHD_STS']))."</span>
                </div>
            ";
        }
    });
    $dt->edit('SCHD_TANGGAL', function($data){
        return "<div class='text-center'>".(($data['SCHD_TANGGAL']))."</div>";
    });
    $dt->edit('MBR_TGLLAHIR', function($data){
        return "<div class='text-center'>".(($data['MBR_TGLLAHIR']))."</div>";
    });


    echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';