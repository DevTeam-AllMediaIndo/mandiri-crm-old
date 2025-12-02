<?php
    require_once('setting.php');

    //$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
    $dt->query('
        SELECT
            tb_dpwd.DPWD_DATETIME,
            tb_member.MBR_USER,
            tb_member.MBR_NAME,
            tb_member.MBR_BK_NAME,
            tb_member.MBR_BK_ACC,
            tb_racc.ACC_LOGIN,
            tb_dpwd.DPWD_AMOUNT,
            tb_racc.ACC_RATE,
            tb_dpwd.DPWD_AMOUNT/tb_racc.ACC_RATE AS DPWD_USD,
            tb_dpwd.ID_DPWD
        FROM tb_member
        JOIN tb_racc
        JOIN tb_dpwd
        ON(tb_member.MBR_ID = tb_racc.ACC_MBR
        AND tb_dpwd.DPWD_MBR = tb_member.MBR_ID
        AND tb_dpwd.DPWD_LOGIN = tb_racc.ID_ACC)
        WHERE tb_dpwd.DPWD_TYPE = 2
        AND tb_dpwd.DPWD_STS = 0
    ');
    $dt->edit('DPWD_DATETIME', function($data){
        return "<div class='text-center'>".$data['DPWD_DATETIME']."</div>";
    });
    $dt->edit('DPWD_AMOUNT', function($data){
        return "<div class='text-right'>".number_format($data['DPWD_AMOUNT'], 0)."</div>";
    });
    $dt->edit('ACC_RATE', function($data){
        return "<div class='text-right'>".number_format($data['ACC_RATE'], 0)."</div>";
    });
    $dt->edit('DPWD_USD', function($data){
        return "<div class='text-right'>".number_format($data['DPWD_USD'], 2)."</div>";
    });
    $dt->edit('ID_DPWD', function($data){
        return "<div class='text-center'>
            <a href='home.php?page=trans_withdrawal&action=accept&x=".md5(md5($data['ID_DPWD']))."' class='btn btn-sm btn-success'>Accept</a>
            <a href='home.php?page=trans_withdrawal&action=reject&x=".md5(md5($data['ID_DPWD']))."' class='btn btn-sm btn-danger'>Reject</a>
            </div>
        ";
    });

    echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';