<?php
    require_once('setting.php');

    //$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
    $dt->query('
        SELECT
            tb_log.LOG_DATETIME,
            tb_member.MBR_NAME,
            tb_member.MBR_EMAIL,
            tb_log.LOG_MESSAGE,
            tb_log.LOG_IP,
            tb_log.LOG_DEVICE,
            tb_log.LOG_DEVICENAME
        FROM tb_log
        JOIN tb_member
        ON(tb_log.LOG_MBR = tb_member.MBR_ID)
    ');
    $dt->edit('LOG_DATETIME', function($data){ return "<div class='text-center'>".$data['LOG_DATETIME']."</div>"; });
    $dt->edit('LOG_IP', function($data){ return "<div class='text-center'>".$data['LOG_IP']."</div>"; });
    $dt->edit('LOG_DEVICE', function($data){ return "<div class='text-center'>".$data['LOG_DEVICE']."</div>"; });
    $dt->edit('LOG_DEVICENAME', function($data){ return "<div class='text-center'>".$data['LOG_DEVICENAME']."</div>"; });

    echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';