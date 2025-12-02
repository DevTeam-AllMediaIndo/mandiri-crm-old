<?php
    require_once('setting.php');

    //$dt->query('SELECT ID_TRD, TRD_MBR, TRD_PRICE FROM tb_trade');
    $dt->query('
        SELECT
            tb_racc.ACC_F_DISC_DATE,
            tb_racc.ACC_TYPEACC,
            tb_racc.ACC_PRODUCT,
            tb_member.MBR_NAME,
            tb_member.MBR_EMAIL,
            tb_racc.ACC_RATE,
            MD5(MD5(ID_ACC)) AS ID_ACC_HASH,
            tb_racc.ACC_WPCHECK
        FROM tb_racc
        JOIN tb_member
        ON(tb_racc.ACC_MBR = tb_member.MBR_ID)
        WHERE tb_racc.ACC_LOGIN = "0"
        AND tb_racc.ACC_WPCHECK = 0
        AND tb_racc.ACC_F_DISC = 1
    ');
    $dt->edit('ACC_F_DISC_DATE', function($data){
        return "<div class='text-center'>".$data['ACC_F_DISC_DATE']."</div>";
    });

    $dt->hide('ACC_WPCHECK');
    $dt->edit('ID_ACC_HASH', function($data){
        if($data['ACC_WPCHECK'] == 1){
            return "
                <div class='text-center'>
                    <a href='home.php?page=member_wp_detail&action=detail&x=".$data['ID_ACC_HASH']."' class='btn btn-sm btn-info'>Detail</a>
                </div>
            ";
        } else {
            return "
                <div class='text-center'>
                    <a href='home.php?page=member_wp&action=confirm&x=".$data['ID_ACC_HASH']."' class='btn btn-sm btn-primary'>Confirm</a>
                    <a href='home.php?page=member_wp_detail&action=detail&x=".$data['ID_ACC_HASH']."' class='btn btn-sm btn-info'>Detail</a>
                </div>
            ";
        }
    });

    echo $dt->generate()->toJson(); // same as 'echo $dt->generate()';