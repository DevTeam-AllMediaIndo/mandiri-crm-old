<?php
    require_once('setting.php');

    //$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
    $dt->query('
        SELECT
            tb_racc.ACC_DATETIME,
            tb_racc.ACC_LOGIN,
            tb_member.MBR_NAME,
            tb_member.MBR_EMAIL,
            tb_racc.ACC_PASS
        FROM tb_racc
        JOIN tb_member
        ON (tb_racc.ACC_MBR = tb_member.MBR_ID)
        WHERE tb_racc.ACC_DERE = 2
    ');
    $dt->edit('ACC_DATETIME', function($data){
        return "<div class='text-center'>".$data['ACC_DATETIME']."</div>";
    });

    echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';