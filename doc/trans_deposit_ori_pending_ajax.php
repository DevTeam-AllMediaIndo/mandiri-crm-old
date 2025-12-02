<?php
    require_once('setting.php');

    //$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
    $dt->query('
        SELECT
            tb_dpwd.ID_DPWD,
            tb_dpwd.DPWD_DATETIME,
            tb_member.MBR_USER,
            CONCAT(tb_member.MBR_NAME, " - ", tb_member.MBR_EMAIL) AS MBR_NAME,
            tb_racc.ACC_LOGIN,
            tb_dpwd.DPWD_AMOUNT,
            tb_dpwd.DPWD_PIC
        FROM tb_member
        JOIN tb_racc
        JOIN tb_dpwd
        ON(tb_member.MBR_ID = tb_racc.ACC_MBR
        AND tb_dpwd.DPWD_MBR = tb_member.MBR_ID
        AND tb_dpwd.DPWD_LOGIN = tb_racc.ID_ACC)
        WHERE tb_dpwd.DPWD_STS = 0
        AND tb_dpwd.DPWD_TYPE = 1
    ');
    $dt->hide('ID_DPWD');
    $dt->edit('DPWD_DATETIME', function($data){
        return "<div class='text-center'>".$data['DPWD_DATETIME']."</div>";
    });
    $dt->edit('DPWD_AMOUNT', function($data){
        return "<div class='text-right'>".number_format($data['DPWD_AMOUNT'], 0)."</div>";
    });
    $dt->edit('DPWD_PIC', function($data){
        return "<div class='text-center'><a target='_blank' href='https://allmediaindo-2.s3.ap-southeast-1.amazonaws.com/ibftrader/".$data['DPWD_PIC']."'>Pic</a></div>";
    });
    $dt->add('action', function($data){
        return "<div class='text-center'>
            <a href='home.php?page=trans_deposit&action=accept&x=".md5(md5($data['ID_DPWD']))."' class='btn btn-sm btn-success'>Accept</a>
            <a href='home.php?page=trans_deposit&action=reject&x=".md5(md5($data['ID_DPWD']))."' class='btn btn-sm btn-danger'>Reject</a>
            </div>
        ";
    });

    echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';